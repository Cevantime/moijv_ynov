<?php

namespace App\Security\Voter;

use App\Entity\Loan;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CreatePostMessageVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['POST_MESSAGE_ON_LOAN'])
            && $subject instanceof Loan;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Loan $loan */
        $loan = $subject;

        return in_array($user, [$loan->getUser(), $loan->getGame()->getUser()]) && $loan->getDateEnd() === null;
    }
}
