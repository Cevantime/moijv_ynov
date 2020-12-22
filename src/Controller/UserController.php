<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ProfileFormType;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @Route("/user/{id}", name="user_details")
     */
    public function details(LoanRepository $loanRepository, User $user = null): Response
    {
        if($user && $user === $this->getUser()) {
            return $this->redirectToRoute('profile');
        }

        $user ??= $this->getUser();

        if( ! $user) {
            return $this->redirectToRoute('login');
        }

        $remoteLoans = $loanRepository->findRemoteLoansForOwner($user);

        return $this->render('user/details.html.twig', [
            'user' => $user,
            'remoteLoans' => $remoteLoans,
        ]);
    }

    /**
     * @Route("/profile/update", name="profile_update")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function updateProfile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $profileForm = $this->createForm(ProfileFormType::class, $user);

        $profileForm->handleRequest($request);

        if($profileForm->isSubmitted() && $profileForm->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/profile_form.html.twig', [
            'profile_form' => $profileForm->createView()
        ]);
    }

    /**
     * @Route("/profile/update-password", name="profile_update_password")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $passwordForm = $this->createForm(ChangePasswordFormType::class);

        $passwordForm->handleRequest($request);

        if($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $newPassword = $passwordForm->get('newPassword')->getData();
            $encodedPassword = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($encodedPassword);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/password_form.html.twig', [
            'password_form' => $passwordForm->createView()
        ]);
    }
}
