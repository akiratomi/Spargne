<?php

namespace App\Repository;

use App\Entity\HireStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HireStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method HireStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method HireStatus[]    findAll()
 * @method HireStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HireStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HireStatus::class);
    }

    // /**
    //  * @return HireStatus[] Returns an array of HireStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HireStatus
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
