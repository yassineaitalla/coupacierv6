<?php

namespace App\Repository;

use App\Entity\EmployeEntreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployeEntreprise>
 *
 * @method EmployeEntreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeEntreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeEntreprise[]    findAll()
 * @method EmployeEntreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeEntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeEntreprise::class);
    }

    //    /**
    //     * @return EmployeEntreprise[] Returns an array of EmployeEntreprise objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EmployeEntreprise
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
