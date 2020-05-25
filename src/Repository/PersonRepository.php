<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @return Person[] Returns an array of Person objects
     */

    public function findByCompanyId($id)
    {
        return $this->createQueryBuilder('p')
                    ->select('p.id as person_id')
                    ->addSelect('p.name')
                    ->innerJoin('p.company', 'c')
                    ->andWhere('c.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getResult();
    }

    public function findById($id)
    {
        return $this->createQueryBuilder('p')
                    ->select('p.id')
                    ->addSelect('p.name')
                    ->addSelect('c.title')
                    ->innerJoin('p.company', 'c')
                    ->andWhere('p.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
