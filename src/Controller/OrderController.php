<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\ClientRepository;
use App\Repository\DishRepository;
use App\Repository\MenuRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/date/{date}", name="orders#by_date")
     */
    public function getOrdersByDate(string $date, OrderRepository $repository)
    {
        $orders = $repository->findOrdersByDate($date);

        return new JsonResponse($orders);
    }

    /**
     * @Route("/menu/{id}", name="orders#by_menu")
     */
    public function getOrdersByMenu(int $id, OrderRepository $repository)
    {
        $orders = $repository->findOrdersByMenu($id);

        return new JsonResponse($orders);
    }

    /**
     * @Route("/", name="orders#list", methods={"GET"})
     */
    public function list(OrderRepository $repository)
    {
        $orders = $repository->findAll();

        return new JsonResponse($orders);
    }

    /**
     * @Route("/{id}", name="orders#read", methods={"GET"})
     */
    public function read(int $id, OrderRepository $repository)
    {

        $order = $repository->find($id);

        if ( ! $order) {
            throw $this->createNotFoundException(`Order with ID:${$id} not found`);
        }

        return new JsonResponse($order);
    }

    /**
     * @Route("/", name="orders#create", methods={"POST"})
     */
    public function create(
        Request $request,
        MenuRepository $menu_repository,
        DishRepository $dish_repository,
        ClientRepository $client_repository
    ) {
        $post = json_decode($request->getContent(), true);

        $menu = $menu_repository->find($post['menu_id']);
        $client = $client_repository->find($post['client']);

        $dishes = array_map(function ($dishId) use ($dish_repository) {
            return $dish_repository->find($dishId);
        }, $post['dishes']);


        $order = new Order();
        $order->setCreatedAt(new \DateTime('now'));
        $order->setUpdatedAt(new \DateTime('now'));
        $order->setMenu($menu);
        $order->setTotal($post['total']);
        $order->setStatus($post['status']);
        $order->setDate($menu->getDate());
        $order->setClient($client);

        foreach ($dishes as $dish) {
            $order->addDish($dish);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return new JsonResponse($order->getId());
    }

    /**
     * @Route("/{id}", name="orders#update", methods={"PATCH"})
     */
    public function update(int $id, Request $request, OrderRepository $repository)
    {
        $post = json_decode($request->getContent(), true);

        $order = $repository->find($post['order_id']);

        if ( ! $order) {
            throw $this->createNotFoundException(`Order with ID:${$id} not found`);
        }

        $order->setStatus();

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);

//        $em->flush();
        return new JsonResponse("Обновлено");
    }

    /**
     * @param int $id
     * @Route("/{id}", name="orders#delete", methods={"DELETE"})
     */
    public function delete(int $id, OrderRepository $repository)
    {
        $order = $repository->find($id);

        if ( ! $order) {
            return new JsonResponse('Нет такого заказа');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);
        $entityManager->flush();

        return new JsonResponse('Deleted');
    }
}
