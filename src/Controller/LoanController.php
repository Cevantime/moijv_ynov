<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Loan;
use App\Mailing\MoiJVMailer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoanController extends AbstractController
{
    /**
     * @Route("/{id}/loan", name="create_loan")
     * @IsGranted("CREATE_LOAN", subject="game")
     */
    public function createLoan(Game $game, EntityManagerInterface $manager, MoiJVMailer $mailer): Response
    {
        $loan = new Loan();
        $loan->setDateStart(new \DateTimeImmutable());
        $loan->setStatus(Loan::STATUS_WAITING);
        $loan->setGame($game);
        $loan->setUser($this->getUser());

        $manager->persist($loan);
        $manager->flush();

        $owner = $loan->getGame()->getUser();

        $mailer->send($owner->getEmail(),'Un joueur a demandé à emprunter l\'un de vos jeux', 'loan/mail-new-loan-owner.html.twig', ['loan' => $loan]);
        $mailer->send($loan->getUser()->getEmail(), 'Votre demande d\'emprunt a bien été effectuée', 'loan/mail-new-loan-loaner.html.twig', ['loan' => $loan]);

        return $this->redirectToRoute('loan_messages', ['id' => $loan->getId()]);
    }

    /**
     * @Route("/{id}/accept", name="accept_loan")
     * @IsGranted("CHANGE_LOAN_STATUS", subject="loan")
     */
    public function acceptLoan(Loan $loan, EntityManagerInterface $manager, MoiJVMailer $mailer)
    {
        $loan->setStatus(Loan::STATUS_VALIDATED);

        $mailer->send($loan->getUser()->getEmail(), 'Votre demande de location a été acceptée', 'loan/mail-accept-loan-loaner.html.twig', ['loan' => $loan]);

        $manager->persist($loan);
        $manager->flush();

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/{id}/reject", name="reject_loan")
     * @IsGranted("CHANGE_LOAN_STATUS", subject="loan")
     */
    public function rejectLoan(Loan $loan, EntityManagerInterface $manager, MoiJVMailer $mailer)
    {
        $loan->setStatus(Loan::STATUS_REFUSED);

        $mailer->send($loan->getUser()->getEmail(), 'Votre demande de location a été rejetée :(', 'loan/mail-reject-loan-loaner.html.twig', ['loan' => $loan]);

        $manager->flush();

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/{id}/close", name="close_loan")
     * @IsGranted("CLOSE_LOAN", subject="loan")
     */
    public function closeLoan(Loan $loan, EntityManagerInterface $manager, MoiJVMailer $mailer)
    {
        $loan->setDateEnd(new \DateTimeImmutable());

        $manager->flush();

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/{id}/delete", name="delete_loan")
     * @IsGranted("DELETE_LOAN", subject="loan")
     */
    public function deleteLoan(Loan $loan, EntityManagerInterface $manager)
    {
        $manager->remove($loan);
        $manager->flush();

        return $this->redirectToRoute('profile');
    }
}
