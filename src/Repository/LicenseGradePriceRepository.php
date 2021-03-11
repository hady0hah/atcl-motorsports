<?php

namespace App\Repository;

use App\Entity\LicenseGradePrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LicenseGradePrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicenseGradePrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicenseGradePrice[]    findAll()
 * @method LicenseGradePrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenseGradePriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenseGradePrice::class);
    }

    // /**
    //  * @return LicenseGradePrice[] Returns an array of LicenseGradePrice objects
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
    public function findOneBySomeField($value): ?LicenseGradePrice
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
