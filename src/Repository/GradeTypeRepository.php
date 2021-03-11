<?php

namespace App\Repository;

use App\Entity\GradeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GradeType|null find($id, $lockMode = null, $lockVersion = null)
 * @method GradeType|null findOneBy(array $criteria, array $orderBy = null)
 * @method GradeType[]    findAll()
 * @method GradeType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GradeTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GradeType::class);
    }

    // /**
    //  * @return GradeType[] Returns an array of GradeType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GradeType
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
