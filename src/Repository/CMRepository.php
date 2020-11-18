<?php

namespace App\Repository;

use App\Entity\CM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CM|null find($id, $lockMode = null, $lockVersion = null)
 * @method CM|null findOneBy(array $criteria, array $orderBy = null)
 * @method CM[]    findAll()
 * @method CM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CM::class);
    }

    // /**
    //  * @return CM[] Returns an array of CM objects
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
    public function findOneBySomeField($value): ?CM
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
