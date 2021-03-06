<?php

namespace App\Security\Voter;

use App\Entity\Loan;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class DeleteLoanVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['DELETE_LOAN'])
            && $subject instanceof Loan;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Loan $loan */
        $loan = $subject;

        return $loan->getUser() === $user && ($loan->getDateEnd() !== null || $loan->getStatus() === Loan::STATUS_REFUSED);
    }
}
