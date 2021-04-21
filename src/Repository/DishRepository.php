<?php

namespace App\Repository;

use App\Entity\Dish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dish[]    findAll()
 * @method Dish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dish::class);
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
                    ->orderBy('d.type')
                    ->getQuery()
                    ->getResult();

    }

    public function findDishesByDate(string $date)
    {

        return $this->createQueryBuilder('d')
                    ->select('d.id as dish_id')
                    ->addSelect('d.title')
                    ->addSelect('d.price')
                    ->addSelect('d.weight')
                    ->addSelect('d.type')
                    ->leftJoin('d.menu', 'm')
                    ->where('m.date = :date')
                    ->setParameter('date', $date)
                    ->orderBy('d.type')
                    ->getQuery()
                    ->getResult();

    }
}
