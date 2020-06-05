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
     * @param int $companyId
     * @param int | null $personId
     * @param string| null $date
     *
     * @return Order[] Returns an array of Order objects
     */
    public function findOrdersByParams($companyId, $personId, $date)
    {
        $qb = $this->createQueryBuilder('o')
                   ->select('o.id')
                   ->addSelect('p.name')
                   ->addSelect('o.status')
                   ->addSelect('o.date')
                   ->addSelect('o.total')
                   ->innerJoin('o.person', 'p')
                   ->innerJoin('p.company', 'c')
                   ->andWhere('c.id = :company_id');

        if ($personId && $date) {

            $qb->setParameters(
                [
                    'company_id' => $companyId,
                    'person_id'  => $personId,
                    'date'       => $date
                ]
            )
               ->andWhere('p.id = :person_id')
               ->andWhere('o.date = :date');

        } elseif ($personId) {
            $qb->setParameters(
                [
                    'company_id' => $companyId,
                    'person_id'  => $personId
                ]
            )->andWhere('p.id = :person_id');
        } else {
            $qb->setParameters(
                [
                    'company_id' => $companyId,
                    'date'       => $date
                ]
            )->andWhere('o.date = :date');
        }

        $qb->setParameter('company_id', $companyId);
        $qb->orderBy('o.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
