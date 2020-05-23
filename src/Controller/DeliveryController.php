<?php

namespace App\Controller;

use App\Repository\DeliveryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/delivery")
 * @IsGranted("ROLE_ADMIN")
 */
class DeliveryController extends AbstractController
{
    /**
     * @Route("/dishes/{orderId}", name="delivery#dishes_by_order")
     */
    public function getDishesByOrderId(int $orderId, DeliveryRepository $repository)
    {
        $dishes = $repository->findDishesByOrder($orderId);

        return new JsonResponse($dishes);
    }

    /**
     * @Route("/orders/{date}", name="orders#by_date_group_by_company")
     */
    public function getOrdersByDate(string $date, DeliveryRepository $repository)
    {
        $orders = $repository->findOrdersByDateGroupByCompany($date);

        return new JsonResponse($orders);
    }

    /**
     * @Route("/company/{company}/{date}", name="orders#by_company_date")
     */
    public function getOrdersByCompanyDate(string $date, int $company, DeliveryRepository $repository)
    {
        $orders = $repository->findOrdersByCompanyDate($company, $date);

        return new JsonResponse($orders);
    }
}
