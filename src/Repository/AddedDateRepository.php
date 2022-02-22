<?php

namespace App\Repository;

use App\Entity\AddedDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AddedDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddedDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddedDate[]    findAll()
 * @method AddedDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddedDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddedDate::class);
    }

    // /**
    //  * @return AddedDate[] Returns an array of AddedDate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AddedDate
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
