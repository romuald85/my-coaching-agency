<?php

namespace App\Repository;

use App\Entity\CartPersist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CartPersist|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartPersist|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartPersist[]    findAll()
 * @method CartPersist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartPersistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartPersist::class);
    }

    // /**
    //  * @return CartPersist[] Returns an array of CartPersist objects
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
    public function findOneBySomeField($value): ?CartPersist
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
