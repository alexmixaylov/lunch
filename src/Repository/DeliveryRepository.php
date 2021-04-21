<?php

namespace App\Repository;

use App\Entity\Dish;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryRepository
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function findDishesByOrder(int $orderId)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('d.title')
            ->addSelect('d.id as dish_id')
            ->addSelect('o.counters')
            ->from(Dish::class, 'd')
            ->innerJoin('d.orders', 'o')
            ->setParameter('order_id', $orderId)
            ->where('o.id = :order_id')
            ->getQuery()
            ->getResult();
    }

    public function findOrdersByCompanyDate($company, $date)
    {
        $orders = $this->em->createQueryBuilder()
            ->select('o.id as order_id')
            ->addSelect('p.id as person_id')
            ->addSelect('p.name')
            ->addSelect('o.status')
            ->from(Order::class, 'o')
            ->innerJoin('o.person', 'p')
            ->innerJoin('p.company', 'c')
            ->where('o.date = :date')
            ->andWhere('c.id = :company')
            ->andWhere('o.status != :status')
            ->setParameters([
                'date'    => $date,
                'company' => $company,
                'status'  => 'canceled'
            ])
            ->getQuery()
            ->getResult();;

        return $orders;
    }

    public function findOrdersByDateGroupByCompany($date)
    {
        return $this->em->createQueryBuilder()
            ->select('c.id as company_id')
            ->addSelect('c.title')
            ->addSelect('COUNT(0) as cnt')
            ->from(Order::class, 'o')
            ->innerJoin('o.person', 'p')
            ->innerJoin('p.company', 'c')
            ->where('o.date = :date')
            ->andWhere('o.status != :status')
            ->setParameters([
                'date'   => $date,
                'status' => 'canceled'
            ])
            ->groupBy('company_id')
            ->addGroupBy('c.title')
            ->orderBy('c.title')
            ->getQuery()
            ->getResult();
    }
}
