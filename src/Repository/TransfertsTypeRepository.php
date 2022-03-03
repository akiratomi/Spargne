<?php

namespace App\Repository;

use App\Entity\TransfertsType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransfertsType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransfertsType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransfertsType[]    findAll()
 * @method TransfertsType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransfertsTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransfertsType::class);
    }

    // /**
    //  * @return TransfertsType[] Returns an array of TransfertsType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransfertsType
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
