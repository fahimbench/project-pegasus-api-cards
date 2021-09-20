<?php

namespace App\Repository;

use App\Entity\MonsterType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MonsterType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonsterType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonsterType[]    findAll()
 * @method MonsterType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonsterTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonsterType::class);
    }

    // /**
    //  * @return MonsterType[] Returns an array of MonsterType objects
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
    public function findOneBySomeField($value): ?MonsterType
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
