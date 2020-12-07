<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\GameRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag/{slug}/{page}", name="games_by_tag")
     */
    public function index(Tag $tag, GameRepository $gameRepository, PaginatorInterface $paginator, $page = 1): Response
    {
        return $this->render('tag/games_by_tag.html.twig', [
            'tag' => $tag,
            'games' => $gameRepository->getLatestPaginatedGamesByTag($tag, $paginator, $page)
        ]);
    }

    /**
     * @Route("/select/tag", name="select_tag")
     */
    public function selectTag(TagRepository $tagRepository, Request $request)
    {
        $q = $request->get('q');
        $tags = $tagRepository->searchTags($q);
        $tagsArray = array_map(fn($tag) => ['id' => $tag->getId(), 'text' => $tag->getName()], $tags);
        return $this->json($tagsArray);
    }
}
