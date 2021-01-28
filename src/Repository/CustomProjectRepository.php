<?php

namespace App\Repository;

use App\Entity\CustomProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomProject[]    findAll()
 * @method CustomProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomProject::class);
    }

    // /**
    //  * @return CustomProject[] Returns an array of CustomProject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomProject
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
