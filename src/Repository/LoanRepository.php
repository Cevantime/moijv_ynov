<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Loan;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Loan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loan[]    findAll()
 * @method Loan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    public function findCurrentLoanForGame(Game $game)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.dateEnd IS NULL')
            ->andWhere('l.status != :status')
            ->andWhere('l.game = :game')
            ->setParameter('game', $game)
            ->setParameter('status', Loan::STATUS_REFUSED)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findRemoteLoansForOwner(User $user)
    {
        return $this->createQueryBuilder('l')
            ->join('l.game', 'g')
            ->andWhere('g.user = :user')
            ->setParameter('user', $user)
            ->andWhere('l.dateEnd IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findCurrentLoansForOwner(User $user)
    {
        return $this->createQueryBuilder('l')
            ->join('l.game', 'g')
            ->andWhere('g.user = :user')
            ->setParameter('user', $user)
            ->andWhere('l.status = :status')
            ->setParameter('status', Loan::STATUS_VALIDATED)
            ->andWhere('l.dateEnd IS NOT NULL')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Loan[] Returns an array of Loan objects
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
    public function findOneBySomeField($value): ?Loan
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
