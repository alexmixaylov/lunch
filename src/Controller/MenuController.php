<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\DishRepository;
use App\Repository\MenuRepository;
use App\Services\DateGenerator;
use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menus")
 */
class MenuController extends AbstractController
{
// какой то большой метод, читать сложно. нужно разбить на несколько приватныйх (вот тебе и ответ зачем нужны приватные методы)
    /**
     * @Route("/table/{userDate}", name="menus#table", methods={"GET"})
     */
    public function getMenusTableOrInitEmptyMenus(
        string $userDate,
        MenuRepository $repository,
        DishRepository $dish_repository,
        DateGenerator $dateGenerator
    ): JsonResponse {
        $allWeekDates = $dateGenerator->rangeOfDates($dateGenerator->getMonday($userDate));

        $existingMenuItems = $repository->findMenusByDates($dateGenerator->getMonday($userDate),
            $dateGenerator->getFriday($userDate));

        $menusWithAttachedDishes = array_map(function ($menu) use ($dish_repository) {
            $menuId = $menu->getId();
            $dishes = $dish_repository->findDishesByMenuId($menuId);

            return [
                'date'    => $menu->getDate()->format('Y-m-d'),
                'menu_id' => $menuId,
                'dishes'  => $dishes,
            ];
        }, $existingMenuItems);

        $initEmptyMenuWithDate = function ($date) {
            $menu = new Menu();
            $menu->setDate(new DateTimeImmutable($date));

            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            return [
                'date'    => $menu->getDate()->format('Y-m-d'),
                'menu_id' => $menu->getId(),
                'dishes'  => [],
            ];
        };

        $menus = array_map(function ($date) use ($initEmptyMenuWithDate, $menusWithAttachedDishes) {
            // проверяем подтянулось ли меню из базы, то есть существует ли оно в принципе
            $existMenu = array_filter($menusWithAttachedDishes, function ($menu) use ($date) {
                return $menu['date'] == $date;
            });

            if ($existMenu) {
                return $existMenu;
            }

            // добавляем меню с отсутствующей датой
            return $menusWithAttachedDishes[] = $initEmptyMenuWithDate($date);
        }, $allWeekDates);

        //return $this->render('app.html.twig', ['data' => $menus]);
        //without this Key JSON_FORCE_OBJECT getting bug when the first element is Array but others are Objects
//        return new JsonResponse(json_encode($menus, JSON_FORCE_OBJECT));
        return $this->json(json_encode($menus, JSON_FORCE_OBJECT));

    }

    /**
     * @Route("/date/{date}", name="menus#by_date", methods={"GET"})
     */
    public function getMenuByDate(string $date, MenuRepository $repository): JsonResponse
    {

        $menu = $repository->findOneByDate($date);

        // почему тут два пробела у восклицательного знака? ты знаешь что пхпшторм умеет выравнивать код, trl + alt + L
        // попробуй, понравится. Постоянно пользуюсь, нравится :) Похоже, что это Шторм как раз и поставил
        if ( ! $menu) {
            return new JsonResponse(['error' => 'Меню на эту дату еще не создано']);
        }

        // а в чем посыл этого array_map?
        // пришлось делать сериализацию dishes, согласен, выглядит не очень
        // можно было бы не полениться и дернуть из базы массив dishes по menu_id
        $serializedDishes = array_map(function ($dish) {
            return [
                'dish_id' => $dish->getId(),
                'title'   => $dish->getTitle(),
                'price'   => $dish->getPrice(),
                'weight'  => $dish->getWeight(),
                'type'    => $dish->getType()
            ];
        },
            $menu->getDishes()->getValues()
        );

        return new JsonResponse([
            'menu_id' => $menu->getId(),
            'date'    => $menu->getDate()->format('Y-m-d'),
            'dishes'  => $serializedDishes
        ]);
    }

    /**
     * @param string $date
     * @param MenuRepository $repository
     *
     * @return JsonResponse
     * @Route("/id/{date}", name="menus#get_id_by_date", methods={"GET"})
     */
    public function getIdMenuByDate(string $date, MenuRepository $repository): JsonResponse
    {
        $menu = $repository->findOneByDate($date);
        if ( ! $menu) {
            throw $this->createNotFoundException("Меню на эту дату $date не найдено");
        }

        return new JsonResponse($menu->getId());
    }

    /**
     * @Route("/order/{id}", name="menus#by_order", methods={"GET"})
     */
    public function getMenuByOrder(int $id, MenuRepository $menu_repository): JsonResponse
    {
        $menu = $menu_repository->findMenuById($id);

        return new JsonResponse($menu);
    }

    /**
     * @Route("/", name="menus#list", methods={"GET"})
     */
    public function list(MenuRepository $repository): JsonResponse
    {
        $menus = $repository->findAll();

        return new JsonResponse($menus);
    }

    /**
     * @Route("/{id}", name="menus#read", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function read(int $id, MenuRepository $repository): JsonResponse
    {
        $menu = $repository->findMenuById($id);

        if ( ! $menu) {
            throw $this->createNotFoundException("Menu with ID: $id not Found");
        }

        return new JsonResponse($menu);
    }

    /**
     * @Route("/", name="menus#create", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     * для методов ниже требуются привилегии, здесь преимущество разделения на модули
     * можно было бы сделать модуль Admin и там создать MenuController
     *
     */
    public function create(Request $request): JsonResponse
    {
        $post = json_decode($request->getContent(), true);
        $date = new DateTimeImmutable($post['date']);

        $menu = new Menu();
        $menu->setDate($date);

        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);
        $em->flush();
        // +1 не любить доктрину. Не понимаю почему.
        // Мне кажется удобно очень. Сделал persist кучи разных сущностей, потом разочек  flush
        // и все записалось в базу за один раз
        return new JsonResponse($menu->getId());
    }

    /**
     * @Route("/{id}", name="menus#update", methods={"PATCH, PUT"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(int $id, MenuRepository $repository): JsonResponse
    {
        $menu = $repository->find($id);

        if ( ! $menu) {
            throw $this->createNotFoundException("Menu with ID: $id not Found");
        }

        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="menus#delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Menu $menu): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($menu);
        $em->flush();

        return new JsonResponse('Deleted');
    }
}
