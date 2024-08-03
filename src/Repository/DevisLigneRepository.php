<?php

namespace App\Repository;

use App\Entity\DevisLigne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DevisLigne>
 *
 * @method DevisLigne|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevisLigne|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevisLigne[]    findAll()
 * @method DevisLigne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisLigneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevisLigne::class);
    }

//    /**
//     * @return DevisLigne[] Returns an array of DevisLigne objects
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

//    public function findOneBySomeField($value): ?DevisLigne
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
