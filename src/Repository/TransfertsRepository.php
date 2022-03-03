<?php

namespace App\Repository;

use App\Entity\Transferts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transferts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transferts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transferts[]    findAll()
 * @method Transferts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransfertsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transferts::class);
    }

    // /**
    //  * @return Transferts[] Returns an array of Transferts objects
    //  */
    public function getAllTransactionByYear($year, $account): array
    {
        return $this->createQueryBuilder('transfert')
            ->andWhere('transfert.fromAccount = :accountFrom')
            ->orWhere('transfert.destinationAccount = :accountDestination')
            ->setParameter('accountFrom', $account->getId())
            ->setParameter('accountDestination', $account->getId())
            ->orderBy('transfert.date', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Transferts
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
