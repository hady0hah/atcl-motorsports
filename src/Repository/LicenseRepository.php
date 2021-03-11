<?php

namespace App\Repository;

use App\Entity\License;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method License|null find($id, $lockMode = null, $lockVersion = null)
 * @method License|null findOneBy(array $criteria, array $orderBy = null)
 * @method License[]    findAll()
 * @method License[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, License::class);
    }

    public function findLicenseExists($sequenceNumber, $expiry, $licenseId = null)
    {
        $expiryYear = $expiry->format('Y');
        $firstDate = new \DateTime("first day of january {$expiryYear}");
        $lastDate = new \DateTime("last day of december {$expiryYear}");
        $q = $this->createQueryBuilder('l')
            ->where('l.expiryDate <= :lastDate')
            ->andWhere('l.expiryDate >= :firstDate')
            ->andWhere('l.sequenceNumber = :sequenceNumber')
            ->setParameter('lastDate', $lastDate)
            ->setParameter('firstDate', $firstDate)
            ->setParameter('sequenceNumber', $sequenceNumber);

        if ($licenseId) {
            $q->andWhere('l.id != :licenseId')
                ->setParameter('licenseId', $licenseId);
        }

        return $q->getQuery()->getResult();
    }

    public function findLicensesByGradeType($type, $date = null)
    {
        if (!$date)
            $date = (new \DateTime('now'))->format('Y');
        $firstDate = new \DateTime("first day of january {$date}");
        $lastDate = new \DateTime("last day of december {$date}");
        return $this->createQueryBuilder('l')
            ->innerJoin('l.licenseGrade', 'lg')
            ->innerJoin('lg.gradeType', 'gt')
            ->where('gt.name = :type')
            ->andWhere('l.expiryDate <= :lastDate')
            ->andWhere('l.expiryDate >= :firstDate')
            ->orderBy('l.sequenceNumber', 'ASC')
            ->setParameter('type', $type)
            ->setParameter('lastDate', $lastDate)
            ->setParameter('firstDate', $firstDate)
            ->getQuery()->getResult();
    }

    public function findLastLicenseByDriver($driver)
    {
        return $this->createQueryBuilder('l')
            ->where('l.driver = :driver')
            ->orderBy('l.createdAt', 'DESC')
            ->setParameter('driver', $driver)
            ->setMaxResults(1)
            ->getQuery()->getResult();
    }
    public function findLicenseByDriverIssuedDate($driver, $issuedDate)
    {
        $firstDate = new \DateTime("first day of january {$issuedDate->format('Y')}");
        $lastDate = new \DateTime("last day of december {$issuedDate->format('Y')}");
        return $this->createQueryBuilder('l')
            ->where('l.driver = :driver')
            ->andWhere('l.expiryDate <= :lastDate')
            ->andWhere('l.expiryDate >= :firstDate')
            ->setParameter('driver', $driver)
            ->setParameter('lastDate', $lastDate)
            ->setParameter('firstDate', $firstDate)
            ->getQuery()->getResult();
    }
    // /**
    //  * @return License[] Returns an array of License objects
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
    public function findOneBySomeField($value): ?License
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
