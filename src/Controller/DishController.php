<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Repository\DishRepository;
use App\Repository\MenuRepository;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dishes")
 */
class DishController extends AbstractController
{
    /**
     * @Route("/menu/{menuId}", name="dishes#by_menu", methods={"GET"})
     */
    public function getDishesByMenuId($menuId, DishRepository $repository)
    {
        $dishes = $repository->findDishesByMenuId($menuId);


        return new JsonResponse([
            'menu_id' => $menuId,
            'dishes'  => $dishes
        ]);
    }

    /**
     * @Route("/date/{date}", name="dishes#by_date", methods={"GET"})
     */
    public function getDishesByDate($date, DishRepository $repository)
    {
        $dishes = $repository->findDishesByDate($date);

        // красава)
        //TODO если на такую дату нет элементов, нужно показать сообщение и редиректить на страницу с неделями WEEK
        //TODO можно сделать дополнительный запрос в базу.... Короче я сам забыл в чем проблема

        return new JsonResponse([
            'date'   => $date,
            'dishes' => $dishes
        ]);
    }


    /**
     * @Route("/", name="dishes#list", methods={"GET"})
     */ // потом расскажешь мне как этот репозиторий тут оказывается
    public function list(DishRepository $repository)
    {
        $dishes = $repository->findAll();

        return new JsonResponse($dishes);
    }

    /**
     * @Route("/{id}", name="dishes#read", methods={"GET"})
     */
    public function read(int $id, DishRepository $repository)
    {
        $dish = $repository->find($id);

        if ( ! $dish) {
            throw $this->createNotFoundException("Dish with ID:{$id} not Found");
        }

        return new JsonResponse($dish);
    }

    /**
     * @Route("/", name="dishes#create", methods={"POST"})
     */
    public function create(Request $request, MenuRepository $menu_repository)
    {
        $post = json_decode($request->getContent(), true);

        $menu = $menu_repository->find($post['menu_id']);

        $dish = new Dish();
        $dish->setPrice($post['price']);
        $dish->setWeight($post['weight']);
        $dish->setType($post['type']);
        $dish->setTitle($post['title']);
        $dish->setMenu($menu);

        $em = $this->getDoctrine()->getManager();
        $em->persist($dish);
        $em->flush();

        return new JsonResponse($dish->getId());
    }

    /**
     * @Route("/{id}", name="dishes#update", methods={"PATCH"})
     */
    public function update(int $id, Request $request, DishRepository $repository)
    {
        $post = json_decode($request->getContent(), true);

        $dish = $repository->find($post['dish_id']);

        if ( ! $dish) {
            throw $this->createNotFoundException("Dish with ID:{$id} not Found");
        }

        $dish->setPrice($post['price']);
        $dish->setWeight($post['weight']);
        $dish->setType($post['type']);
        $dish->setTitle($post['title']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($dish);
        $em->flush();

        return new JsonResponse("Обновлено");
    }

    /**
     * @Route("/{id}", name="dishes#delete", methods={"DELETE"})
     */
    public function delete(int $id, DishRepository $repository, OrderRepository $order_repository)
    {
        $dish = $repository->find($id);

        $orders = $order_repository->findOrdersByDish($id);

        if($orders) {
            return new JsonResponse(null, 403);
        }


        if ($dish) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dish);
            $entityManager->flush();

            return new JsonResponse($id);
        }

        //TODO нужно придумать как передать сообщение
        return new JsonResponse(null,404);
    }
}
