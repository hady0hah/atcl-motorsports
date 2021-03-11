<?php

namespace App\Repository;

use App\Entity\LicenseGrade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LicenseGrade|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicenseGrade|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicenseGrade[]    findAll()
 * @method LicenseGrade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenseGradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenseGrade::class);
    }

    // /**
    //  * @return LicenseGrade[] Returns an array of LicenseGrade objects
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
    public function findOneBySomeField($value): ?LicenseGrade
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
