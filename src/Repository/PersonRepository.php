<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @param int $id
     *
     * @return Person[]
     */
    public function findByCompanyId(int $id): array
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


    /**
     * @param int $id
     *
     * @return Person|null
     * @throws NonUniqueResultException
     */
    public function findByUserId(int $id): ?Person
    {
        return $this->createQueryBuilder('p')
                    ->select('p.id as person_id')
                    ->addSelect('p.name')
                    ->innerJoin('p.user', 'u')
                    ->andWhere('u.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }


    /**
     * @param int $id
     *
     * @return Person|null
     * @throws NonUniqueResultException
     */
    public function findById(int $id): ?Person
    {
        return $this->createQueryBuilder('p')
                    ->select('p.id as person_id')
                    ->addSelect('p.name')
                    ->addSelect('c.title')
                    ->innerJoin('p.company', 'c')
                    ->andWhere('p.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
