<?php

namespace App\Repository;

use App\Entity\Dish;
use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Query\Expr\Join;
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
                    ->setParameter('date', $date)
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

    // /**
    //  * @return Menu[] Returns an array of Menu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
