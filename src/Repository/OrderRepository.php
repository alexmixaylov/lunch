<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Order[] Returns an array of Order objects
     */

    public function findOrdersByDate($date)
    {
        return $this->createQueryBuilder('o')
                    ->select('o.id')
                    ->addSelect('o.total')
                    ->addSelect('o.status')
                    ->addSelect('p.name')
                    ->addSelect('c.title')
                    ->innerJoin('o.menu', 'm')
                    ->innerJoin('o.person', 'p')
                    ->innerJoin('p.company', 'c')
                    ->andWhere('m.date = :date')
                    ->setParameter('date', $date)
                    ->orderBy('o.id', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return Order[] Returns an array of Order objects
     */

    public function findOrdersByMenu($id)
    {
        return $this->createQueryBuilder('o')
                    ->select('o.id')
                    ->addSelect('o.status')
                    ->addSelect('d.type')
                    ->innerJoin('o.menu', 'm')
                    ->innerJoin('o.dishes', 'd')
                    ->andWhere('m.id = :id')
                    ->setParameter('id', $id)
                    ->orderBy('o.id', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return Order[] Returns an array of Order objects
     */

    public function findOrdersByPerson($id)
    {
        return $this->createQueryBuilder('o')
                    ->select('o.id')
                    ->addSelect('o.status')
                    ->addSelect('o.date')
                    ->addSelect('o.total')
                    ->innerJoin('o.person', 'p')
                    ->andWhere('p.id = :person_id')
                    ->setParameter('person_id', $id)
                    ->orderBy('o.id', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return Order[] Returns an array of Order objects
     */

    public function findOrdersByDish($id)
    {
        return $this->createQueryBuilder('o')
                    ->select('o.id')
                    ->innerJoin('o.dishes', 'd')
                    ->andWhere('d.id = :dish_id')
                    ->setParameter('dish_id', $id)
                    ->getQuery()
                    ->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
