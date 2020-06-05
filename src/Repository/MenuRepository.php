<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findOneByDate($date)
    {
        return $this->createQueryBuilder('m')
                    ->andWhere('m.date = :date')
                    ->leftJoin('m.dishes', 'd')
                    ->setParameter('date', $date)
                    ->orderBy('d.type')
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findMenuById($id)
    {

        $result = $this->createQueryBuilder('m')
                       ->select('m.id menu_id')
                       ->addSelect('d.id as dish_id')
                       ->addSelect('m.date')
                       ->addSelect('d.title')
                       ->addSelect('d.price')
                       ->addSelect('d.weight')
                       ->leftJoin('m.dishes', 'd')
                       ->andWhere('m.id = :id')
                       ->setParameter('id', $id)
                       ->getQuery()
                       ->getResult();

        return $result;
    }

    public function findMenusByDates($start, $end)
    {
        $qb = $this->createQueryBuilder('m')
//                   ->select('m.id as menu_id')
//                   ->addSelect('m.date')
                   ->where('m.date >= :start')
                   ->andWhere('m.date <= :end')
                   ->setParameters(['start' => $start, 'end' => $end])
                   ->orderBy('m.date')
                   ->getQuery();

        return $qb->getResult();
    }
}
