<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @return Company[] Returns an array of Company objects
     */

    public function findAllCompanies()
    {
        return $this->createQueryBuilder('c')
                    ->select('c.id as company_id')
                    ->addSelect('c.title')
                    ->orderBy('c.title', 'ASC')
                    ->setMaxResults(10)
                    ->getQuery()
                    ->getResult();
    }


    public function findCompanyById($id)
    {
        return $this->createQueryBuilder('c')
                    ->select('c.id as company_id')
                    ->addSelect('c.title')
                    ->addSelect('c.uuid')
                    ->addSelect('owner.name as owner_name')
                    ->addSelect('owner.id as owner_id')
                    ->leftJoin('c.owner', 'owner')
                    ->andWhere('c.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findCompanyByOwner($owner)
    {
        return $this->createQueryBuilder('c')
                    ->select('c.id as company_id')
                    ->addSelect('c.title')
                    ->addSelect('c.uuid')
                    ->andWhere('c.owner = :owner_id')
                    ->setParameter('owner_id', $owner)
                    ->orderBy('c.title')
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findCompanyByUUID($uuid)
    {
        return $this->createQueryBuilder('c')
                    ->select('c.id as company_id')
                    ->addSelect('c.title')
                    ->andWhere('c.uuid = :uuid')
                    ->setParameter('uuid', $uuid)
                    ->orderBy('c.title')
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findCompanyByUUIDLazyObj($uuid)
    {
        return $this->createQueryBuilder('c')
                    ->andWhere('c.uuid = :uuid')
                    ->setParameter('uuid', $uuid)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

}
