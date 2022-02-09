<?php

namespace App\Repository;

use App\Entity\HireType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HireType|null find($id, $lockMode = null, $lockVersion = null)
 * @method HireType|null findOneBy(array $criteria, array $orderBy = null)
 * @method HireType[]    findAll()
 * @method HireType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HireTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HireType::class);
    }

    // /**
    //  * @return HireType[] Returns an array of HireType objects
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
    public function findOneBySomeField($value): ?HireType
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
