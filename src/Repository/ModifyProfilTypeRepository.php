<?php

namespace App\Repository;

use App\Entity\ModifyProfilType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModifyProfilType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModifyProfilType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModifyProfilType[]    findAll()
 * @method ModifyProfilType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModifyProfilTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModifyProfilType::class);
    }

    // /**
    //  * @return ModifyProfilType[] Returns an array of ModifyProfilType objects
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
    public function findOneBySomeField($value): ?ModifyProfilType
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
