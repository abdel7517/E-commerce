<?php
namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }
    

      /**
     * @return Product[] Returns an array of Product objects
     */
    
    public function findByValue($orderCode)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Order p
            WHERE  p.orderCode = :val'
        )->setParameter('val', $orderCode);

        if(empty($query->getResult()))
        {
            return $this->findAll();
        }
        // returns an array of Product objects
        return $query->getResult();
    }

     /**
     * @return Product[] Returns an array of Product objects
     */
    
    public function findById($id, $state)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Order p
            WHERE  p.user = :val and p.state = :val2'
        )->setParameter('val', $id)
        ->setParameter('val2', $state);

        // if(empty($query->getResult()))
        // {
        //     return $this->findAll();
        // }

        return $query->getResult();
    }

     /**
     * @return Product[] Returns an array of Product objects
     */
    
    public function findByState(int $state)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Order p
            WHERE  p.state = :val'
        )->setParameter('val', $state);

        // if(empty($query->getResult()))
        // {
        //     return $this->findAll();
        // }

        return $query->getResult();
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
