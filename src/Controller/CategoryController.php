<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\GameRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}/{page<\d+>}", name="games_by_category")
     */
    public function gamesByCategory(Category $category, GameRepository $gameRepository, PaginatorInterface  $paginator, $page = 1): Response
    {
        $games = $gameRepository->getLatestPaginatedGamesByCategory($category, $paginator, $page);
        return $this->render('category/games_by_category.html.twig', [
            'games' => $games,
            'category' => $category
        ]);
    }
}
