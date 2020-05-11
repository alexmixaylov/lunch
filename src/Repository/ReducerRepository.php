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

    public function findDishesByMenuId($menuId)
    {

        return $this->createQueryBuilder('d')
                    ->select('d.id as dish_id')
                    ->addSelect('d.title')
                    ->addSelect('d.price')
                    ->addSelect('d.weight')
                    ->addSelect('d.type')
                    ->leftJoin('d.menu', 'm')
                    ->where('m.id = :menu_id')
                    ->setParameter('menu_id', $menuId)
                    ->getQuery()
                    ->getResult();

    }

    public function findDishesByDate($date)
    {

        return $this->em->createQueryBuilder('d')
                        ->select('d.id as dish_id')
                        ->addSelect('d.title')
                        ->addSelect('COUNT(d) as cnt')
                        ->groupBy('d.id')
                        ->from(Dish::class, 'd')
                        ->leftJoin('d.menu', 'm')
                        ->where('m.date = :date')
                        ->setParameter('date', $date)
                        ->orderBy('d.type')
                        ->getQuery()
                        ->getResult();

    }
}
