<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\LoanMessage;
use App\Form\LoanMessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoanMessageController extends AbstractController
{
    /**
     * @Route("/loan/{id}/messages", name="loan_messages")
     * @IsGranted("POST_MESSAGE_ON_LOAN", subject="loan")
     */
    public function loanMessages(Loan $loan, Request $request, EntityManagerInterface $manager): Response
    {
        $message = new LoanMessage();

        $form = $this->createForm(LoanMessageFormType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $message->setAuthor($this->getUser());
            $message->setDate(new \DateTimeImmutable());
            $loan->addLoanMessage($message);
            $manager->persist($message);
            $manager->flush();
        }

        return $this->render('loan_message/index.html.twig', [
            'form_message' => $form->createView(),
            'loan' => $loan
        ]);
    }
}
