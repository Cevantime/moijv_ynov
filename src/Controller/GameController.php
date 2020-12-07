<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/game/{id<\d+>}", name="game_details")
     */
    public function gameDetails(Game $game): Response
    {
        return $this->render('game/details.html.twig', [
            'game' => $game,
        ]);
    }

    /**
     * @Route("/game/add", name="game_add")
     * @Route("/game/edit/{id}", name="game_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY') and (game === null or game.getUser() == user)")
     */
    public function gameForm(Request $request, EntityManagerInterface $manager, Game $game = null): Response
    {
//        if($this->getUser() === null || ($game && $game->getUser() != $this->getUser())) {
//            throw $this->createAccessDeniedException();
//        }
        if($game === null) {
            $game = new Game();
        }

        $gameForm = $this->createForm(GameType::class, $game);

        $gameForm->handleRequest($request);

        if($gameForm->isSubmitted() && $gameForm->isValid()) {
            // enregistrement du jeu en base de données
            if( ! $game->getId()) {
                $game->setDateAdd(new \DateTime());
                $game->setUser($this->getUser());
            }
            $manager->persist($game);
            $manager->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('game/game-form.html.twig', [
            'game_form' => $gameForm->createView(),
            'game' => $game
        ]);
    }
}
