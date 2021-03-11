<?php

namespace App\Repository;

use App\Entity\LicenseType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LicenseType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicenseType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicenseType[]    findAll()
 * @method LicenseType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenseTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenseType::class);
    }

    // /**
    //  * @return LicenseType[] Returns an array of LicenseType objects
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

    /*
    public function findOneBySomeField($value): ?LicenseType
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
