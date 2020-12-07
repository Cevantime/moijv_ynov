<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function getLatestPaginatedGames(PaginatorInterface $paginator, $page = 1)
    {
        // SELECT g.* FROM App\Entity\Game as g ORDER BY g.date_add DESC
        $query = $this->createCommonQueryBuilder() // SELECT game as g
            ->getQuery();

        return $paginator->paginate($query, $page, 9);
    }

    public function getLatestPaginatedGamesByCategory(Category $category, PaginatorInterface $paginator, $page = 1)
    {
        // SELECT g.* FROM App\Entity\Game as g WHERE g.category = :category ORDER BY g.date_add DESC
        $query = $this->createCommonQueryBuilder() // SELECT game as g
            ->where('g.category = :category')
            ->setParameter('category', $category)
            ->getQuery();

        return $paginator->paginate($query, $page, 9);
    }

    private function createCommonQueryBuilder()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.date_add', 'DESC') // ORDER BY g.date_add DESC
            ->leftJoin('g.user', 'u')
            ->addSelect('u')
            ->leftJoin('g.tags', 't')
            ->addSelect('t');
    }

    public function getLatestPaginatedGamesByTag(Tag $tag, PaginatorInterface $paginator, int $page)
    {
        $query = $this->createCommonQueryBuilder()
            ->where(':tag MEMBER OF g.tags')
            ->setParameter('tag', $tag)
            ->getQuery();

        return $paginator->paginate($query, $page, 9);
    }

    // /**
    //  * @return Game[] Returns an array of Game objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
