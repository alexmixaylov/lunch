<?php

namespace App\Controller;

use App\Repository\DeliveryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/delivery")
 */
class DeliveryController extends AbstractController
{
    /**
     * @Route("/dishes/{orderId}", name="delivery#dishes_by_order")
     */
    public function getDishesByOrderId(int $orderId, DeliveryRepository $repository): JsonResponse
    {
        $dishes = $repository->findDishesByOrder($orderId);

        return new JsonResponse($dishes);
    }

    /**
     * @Route("/orders/{date}", name="orders#by_date_group_by_company")
     */
    public function getOrdersByDate(string $date, DeliveryRepository $repository): JsonResponse
    {
        $orders = $repository->findOrdersByDateGroupByCompany($date);

        return new JsonResponse($orders);
    }

    /**
     * @Route("/company/{company}/{date}", name="orders#by_company_date")
     */
    public function getOrdersByCompanyDate(string $date, int $company, DeliveryRepository $repository): JsonResponse
    {
        $orders = $repository->findOrdersByCompanyDate($company, $date);

        return new JsonResponse($orders);
    }
}
