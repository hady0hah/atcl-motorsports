<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    private function futureEvents($today, $maxResult)
    {
        return $this->createQueryBuilder('e')
            ->where('e.startDate >= :today')
            ->setParameter('today', $today)
            ->orderBy('e.startDate', 'ASC')
            ->setMaxResults($maxResult)
            ->getQuery();
    }

    private function pastEvents($today, $maxResult)
    {
        return $this->createQueryBuilder('e')
            ->where('e.startDate < :today')
            ->setParameter('today', $today)
            ->orderBy('e.startDate', 'DESC')
            ->setMaxResults($maxResult)
            ->getQuery();
    }

    private function eventBetweenDateQuery($first, $last)
    {

        return $this->createQueryBuilder('e')
            ->where('e.startDate BETWEEN :first AND :last')
            ->andWhere('e.published = true')
            ->orderBy('e.startDate', 'ASC')
            ->setParameter('first', $first)
            ->setParameter('last', $last);

    }
    private function upcomingEventsQuery($today)
    {
        return $this->createQueryBuilder('e')
            ->where('e.startDate > :today')
            ->setParameter('today', $today);
//            ->setMaxResults(1)
//            ->getQuery();
    }

//    public function findEventsByChampionshipThisYear()
//    {
//        $endYear = date('Y-m-d', strtotime('Dec 31'));
//        $startYear = date('Y-m-d', strtotime('Jan 01'));
//
//
//        return $this->createQueryBuilder('e')
////            ->select(['e.id', 'e.label', 'e.name', 'e.startDate', 'e.endDate'])
//            ->innerJoin('e.championship', 'c')
//            ->where('c.year BETWEEN :start AND :end')
//            ->setParameter('start', $startYear)
//            ->setParameter('end', $endYear)
//            ->getQuery();
////            ->getResult();
//
//    }

    public function findTopBannerEvents()
    {
        return $this->createQueryBuilder('e')
            ->where('e.isTopBanner = true')
            ->getQuery()
            ->getResult();
    }

    public function findNearestEvent()
    {
        $today = new \DateTime('now');
        try {
            return $this->upcomingEventsQuery($today)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findUpcomingEvents($first , $last)
    {
        return $this->eventBetweenDateQuery($first, $last)
            ->orderBy('e.startDate', 'DESC')
            ->getQuery()
            ->getResult();
//        $today = new \DateTime('now');
//        return $this->upcomingEventsQuery($today)
//            ->getQuery();
////            ->getResult();
    }

    public function findEvents()
    {
        $MAX_RESULT= 4;

        $today = new \DateTime('now');
        $futureEvents = $this->futureEvents($today, $MAX_RESULT)->getResult();
        $futureCount = count($futureEvents);
        if ($futureCount < $MAX_RESULT)
        {
            $pastEvents = $this->pastEvents($today, ($MAX_RESULT * 2) - $futureCount)->getResult();
        }
        else {
            $pastEvents  =$this->pastEvents($today, $MAX_RESULT)->getResult();
        }
//        dump($futureEvents);
//        dump($pastEvents);
//        die();
        return array_merge($futureEvents, $pastEvents);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
