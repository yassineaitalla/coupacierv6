<?php

namespace App\Repository;

use App\Entity\BordereauLivraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BordereauLivraison>
 *
 * @method BordereauLivraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method BordereauLivraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method BordereauLivraison[]    findAll()
 * @method BordereauLivraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BordereauLivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BordereauLivraison::class);
    }

    //    /**
    //     * @return BordereauLivraison[] Returns an array of BordereauLivraison objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BordereauLivraison
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
