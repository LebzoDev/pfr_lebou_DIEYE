<?php

namespace App\Repository;

use App\Entity\ProfilId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilId|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilId|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilId[]    findAll()
 * @method ProfilId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilId::class);
    }

    // /**
    //  * @return ProfilId[] Returns an array of ProfilId objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfilId
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
