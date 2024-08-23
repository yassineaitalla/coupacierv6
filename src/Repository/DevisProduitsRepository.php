<?php

namespace App\Repository;

use App\Entity\DevisProduits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DevisProduits>
 *
 * @method DevisProduits|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevisProduits|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevisProduits[]    findAll()
 * @method DevisProduits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevisProduits::class);
    }

    //    /**
    //     * @return DevisProduits[] Returns an array of DevisProduits objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DevisProduits
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
