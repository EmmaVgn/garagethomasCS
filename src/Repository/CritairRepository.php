<?php

namespace App\Repository;

use App\Entity\Critair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Critair>
 *
 * @method Critair|null find($id, $lockMode = null, $lockVersion = null)
 * @method Critair|null findOneBy(array $criteria, array $orderBy = null)
 * @method Critair[]    findAll()
 * @method Critair[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CritairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Critair::class);
    }

//    /**
//     * @return Critair[] Returns an array of Critair objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Critair
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
