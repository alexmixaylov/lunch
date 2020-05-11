<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\DishRepository;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menus")
 */
class MenuController extends AbstractController
{

    /**
     * @Route("/table/{userDate}", name="menus#table", methods={"GET"})
     */
    public function getMenuTableOrInitEmptyMenus(string $userDate, MenuRepository $repository, DishRepository $dish_repository) {
        define('HOW_MANY_DAYS_ADD', 4);
        // 4 days must be added Then we can get all days per week
        $start = date('Y-m-d', strtotime("monday this week", strtotime($userDate)));
        $end   = date('Y-m-d', strtotime("+" . HOW_MANY_DAYS_ADD . "days", strtotime($start)));

        $existingMenuItems = $repository->findMenusByDates($start, $end);

//        var_dump(json_encode($existingMenuItems, JSON_FORCE_OBJECT));
//        die();

        $menusWithAttachedDishes = array_map(function ($menu) use ($dish_repository) {
            $menuId = $menu->getId();
            $dishes = $dish_repository->findDishesByMenuId($menuId);

            return [
                'date'    => $menu->getDate()->format('Y-m-d'),
                'menu_id' => $menuId,
                'dishes'  => $dishes,
            ];
        }, $existingMenuItems);

//        print_r($menusWithAttachedDishes);

        // generate dates which should be in base. It is a Dictonary
        $generatePeriod = function ($date, $acc) use (&$generatePeriod, $end) {
            if ($date == $end) {
                $acc[] = $end;

                return $acc;
            }
            $newDate = date('Y-m-d', strtotime("+1days", strtotime($date)));
            $acc[]   = $date;

            return $generatePeriod($newDate, $acc);
        };
        $allWeekDates   = $generatePeriod($start, []);

        $initEmptyMenuWithDate = function ($date) {
            $menu = new Menu();
            $menu->setDate(new \DateTime($date));

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
        return new JsonResponse(json_encode($menus, JSON_FORCE_OBJECT));
//        return new JsonResponse($menus);

    }

    /**
     * @Route("/date/{date}", name="menus#by_date", methods={"GET"})
     */
    public function getMenuByDate(string $date, MenuRepository $repository)
    {

        $menu = $repository->findOneByDate($date);

        if ( ! $menu) {
            throw $this->createNotFoundException("Меню на эту дату ${$date} еще не создано");
        }

        return new JsonResponse([
            'menu_id' => $menu->getId(),
            'date'    => $menu->getDate()->format('Y-m-d'),
        ]);
    }

    /**
     * @Route("/", name="menus#list", methods={"GET"})
     */
    public function list(MenuRepository $repository)
    {
        $menus = $repository->findAll();

        return new JsonResponse($menus);
    }

    /**
     * @Route("/{id}", name="menus#read", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function read(int $id, MenuRepository $repository)
    {
        $menu = $repository->findMenuById($id);

        if ( ! $menu) {
            throw $this->createNotFoundException("Menu with ID:{$id} not Found");
        }
        return new JsonResponse($menu);
    }

    /**
     * @Route("/", name="menus#create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $post = json_decode($request->getContent(), true);
        $date = new \DateTime($post['date']);

        $menu = new Menu();
        $menu->setDate($date);

        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);
        $em->flush();

        return new JsonResponse($menu->getId());
    }

    /**
     * @Route("/{id}", name="menus#update", methods={"PATCH, PUT"})
     */
    public function update(int $id, MenuRepository $repository)
    {
        $menu = $repository->find($id);

        if ( ! $menu) {
            throw $this->createNotFoundException("Menu with ID:{$id} not Found");
        }

        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="menus#delete", methods={"DELETE"})
     */
    public function delete(int $id)
    {
        return new JsonResponse();
    }
}
