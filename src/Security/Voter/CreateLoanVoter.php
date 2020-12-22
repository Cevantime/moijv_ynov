<?php

namespace App\Security\Voter;

use App\Entity\Game;
use App\Repository\LoanRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateLoanVoter extends Voter
{
    /**
     * @var LoanRepository $loanRepository
     */
    private $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CREATE_LOAN'])
            && $subject instanceof \App\Entity\Game;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Game $game */
        $game = $subject;

        if($game->getUser() === $user) {
            return false;
        }

        $currentLoan = $this->loanRepository->findCurrentLoanForGame($game);

        return $currentLoan === null;
    }
}
