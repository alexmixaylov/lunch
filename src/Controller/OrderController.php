<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\CompanyRepository;
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
     * @Route("/{id}", name="orders#read", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function read(int $id, OrderRepository $repository)
    {

        $order = $repository->find($id);

        if ( ! $order) {
            throw $this->createNotFoundException(`Order with ID:${$id} not found`);
        }

        // клонируем dish в согласии со счетчиками 118 - 121 строки
        $counters = $order->getCounters();
        $dishes   = array_map(function ($dish) use ($counters) {
            $dishId    = $dish->getId();
            $dish      = [
                'dish_id' => $dishId,
                'title'   => $dish->getTitle(),
                'price'   => $dish->getPrice()
            ];
            $cloneDish = function ($cnt, $acc) use ($dish, &$cloneDish) {
                if ($cnt === 0) {
                    return $acc;
                }
                $acc[] = $dish;

                return $cloneDish($cnt - 1, $acc);
            };

            return array_values($cloneDish($counters[$dishId], []));
        }, $order->getDishes()->getValues());

        $normalizeDishes = [];

        foreach ($dishes as $dishChildren) {
            foreach ($dishChildren as $dish) {
                $normalizeDishes[] = $dish;
            }
        }

        $result = [
            'order_id' => $order->getId(),
            'status'   => $order->getStatus(),
            'total'    => $order->getTotal(),
            'date'     => $order->getDate()->format('Y-m-d'),
            'dishes'   => $normalizeDishes,
            'created'  => $order->getCreatedAt()->format('Y-m-d\TH:i:sP'),
            'updated'  => $order->getUpdatedAt()->format('Y-m-d\TH:i:sP'),
        ];

        return new JsonResponse($result);
    }

    /**
     * @Route("/", name="orders#create", methods={"POST"})
     */
    public function create(
        Request $request,
        MenuRepository $menu_repository,
        DishRepository $dish_repository,
        CompanyRepository $company_repository
    ) {
        $post = json_decode($request->getContent(), true);

        $menu   = $menu_repository->find($post['menu_id']);
        $company = $company_repository->find($post['company']);

        // так как невозможно несколько идентичных связея для manyToMany
        // мы прогоняем массив с дублями и записываем счетчик каждой повторяющейся позиции dish
        // нужно сформировать массив с парами dish_id => cnt
        // на операции чтения нужно получить сущности и прогнать их через счетчик, продублировав позиции 62 строка

        $rawDishes    = $post['dishes'];
        $dishCounters = [];
        foreach ($rawDishes as $dishId) {
            if (array_key_exists($dishId, $dishCounters)) {
                $dishCounters[$dishId]++;

            } else {
                $dishCounters[$dishId] = 1;
            }
        }

        $dishes = array_map(function ($dishId) use ($dish_repository) {
            return $dish_repository->find($dishId);
        }, array_unique($rawDishes));


        $order = new Order();
        $order->setCreatedAt(new \DateTime('now'));
        $order->setUpdatedAt(new \DateTime('now'));
        $order->setMenu($menu);
        $order->setTotal($post['total']);
        $order->setStatus($post['status']);
        $order->setDate($menu->getDate());
        $order->setCompany($company);
        $order->setCounters($dishCounters);

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
