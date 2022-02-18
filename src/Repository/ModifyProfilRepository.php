<?php

namespace App\Repository;

use App\Entity\ModifyProfil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModifyProfil|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModifyProfil|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModifyProfil[]    findAll()
 * @method ModifyProfil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModifyProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModifyProfil::class);
    }

    // /**
    //  * @return ModifyProfil[] Returns an array of ModifyProfil objects
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
    public function findOneBySomeField($value): ?ModifyProfil
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
