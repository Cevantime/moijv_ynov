<?php

namespace App\Repository;

use App\Entity\LoanMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoanMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoanMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoanMessage[]    findAll()
 * @method LoanMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoanMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoanMessage::class);
    }

    // /**
    //  * @return LoanMessage[] Returns an array of LoanMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LoanMessage
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
