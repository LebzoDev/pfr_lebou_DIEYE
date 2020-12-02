<?php

namespace App\Repository;

use App\Entity\GroupePromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupePromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupePromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupePromo[]    findAll()
 * @method GroupePromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupePromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupePromo::class);
    }

    // /**
    //  * @return GroupePromo[] Returns an array of GroupePromo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupePromo
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
