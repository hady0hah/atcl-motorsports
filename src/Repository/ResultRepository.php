<?php

namespace App\Repository;

use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    private function getOrderedResults($object)
    {
        $orderBy = $object->getResultType() === 'point' ? 'DESC' : 'ASC';
        $qb = $this->createQueryBuilder('r');
        $qb->addSelect('CASE WHEN r.valueNumber IS NULL THEN 1 ELSE 0 END AS HIDDEN myValueIsNull');
        $qb->orderBy('myValueIsNull','ASC');
        $qb->addOrderBy('r.valueNumber',$orderBy);

        return $qb;
    }
    public function findResultsBySection($section)
    {
        $qb = $this->getOrderedResults($section);
        $qb->innerJoin('r.section', 's');
        $qb->where('r.section = :section');
        $qb->andWhere('s.sectionType != :sectionType');
        $qb->andWhere('r.published = true');
        $qb->setParameter(':sectionType','tc');
        $qb->setParameter(':section',$section);
        return $qb->getQuery()->getResult();
    }

    public function findResultsByEvent($event)
    {
        $qb = $this->getOrderedResults($event);
        $qb->where('r.event = :event');
        $qb->andWhere('r.published = true');
        $qb->setParameter(':event', $event);
        return $qb->getQuery()->getResult();
//        $orderBy = $event->getResultType() === 'point' ? 'DESC' : 'ASC';
//
//        $qb = $this->createQueryBuilder('r');
//        $qb->addSelect('CASE WHEN r.valueNumber IS NULL THEN 1 ELSE 0 END AS HIDDEN myValueIsNull');
//        $qb->where('r.event = :eventId');
//        $qb->addOrderBy('r.valueNumber',$orderBy);
//        $qb->orderBy('myValueIsNull','ASC');
//
//        $qb->setParameter(':eventId',$event);
//
//        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Result[] Returns an array of Result objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Result
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
