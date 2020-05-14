<?php

namespace App\Repository;

use App\Entity\Dish;
use App\Entity\Menu;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;


class ReducerRepository
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function countDishesByDate($date)
    {

        return $this->em->createQueryBuilder()
                        ->select('d.id as dish_id')
                        ->addSelect('d.title')
                        ->addSelect('COUNT(d) as cnt')
                        ->groupBy('d.id')
                        ->from(Order::class, 'o')
                        ->innerJoin('o.dishes', 'd')
                        ->innerJoin('o.menu', 'm')
                        ->where('m.date = :date')
                        ->setParameter('date', $date)
                        ->orderBy('d.type')
                        ->getQuery()
                        ->getResult();

    }
}
