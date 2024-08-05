<?php

namespace App\Repository;

use App\Entity\CommandeF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeF>
 *
 * @method CommandeF|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeF|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeF[]    findAll()
 * @method CommandeF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeF::class);
    }

    //    /**
    //     * @return CommandeF[] Returns an array of CommandeF objects
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

    //    public function findOneBySomeField($value): ?CommandeF
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
