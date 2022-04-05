<?php

namespace App\Repository;

use App\Entity\MeetingTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MeetingTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeetingTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeetingTopic[]    findAll()
 * @method MeetingTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeetingTopic::class);
    }

    // /**
    //  * @return MeetingTopic[] Returns an array of MeetingTopic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MeetingTopic
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
