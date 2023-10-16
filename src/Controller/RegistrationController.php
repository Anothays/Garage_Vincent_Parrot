<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserCustomer;
use App\Form\RegistrationFormType;
use App\Form\VerifyEmailAccountType;
use App\Repository\UserCustomerRepository;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(MailerInterface $mailer, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = new UserCustomer();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Début transaction
            $entityManager->beginTransaction();
            try {
                $user->setRoles(["ROLE_CLIENT"]);
                // Hash du mot de passe
                $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
                // Persit en BDD
                $entityManager->persist($user);
                $entityManager->flush();
                // Envoi confirmation email
                $this->makeAndSendEmail($verifyEmailHelper, $mailer, $user);
                // Transaction réussie
                $entityManager->commit();
                return $this->redirectToRoute('app_verify_send_email', [
                    'email' => $user->getEmail(),
                ]);
            } catch (\Exception $e) {
                // Transaction échouée
                $entityManager->rollback();
                $this->addFlash('error', "Une erreur est survenue pendant le processus d'inscription. " . $e->getMessage());
            }
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    // Route appelée en cliquant sur le lien du mail de validation
    #[Route('/verify', name: 'app_registration_verify_email')]
    public function verifyEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelper, UserCustomerRepository $userRepository, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator) : Response
    {
        $user = $userRepository->find($request->query->get('1'));
        if (!$user) {
            throw $this->createNotFoundException();
        }
        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail()
            );
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }
        $user->setIsVerified(true);
        $userRepository->save($user, true);

        $this->addFlash('success', 'Compte vérifié ! Vous êtes maintenant inscrit !');

        // Authenticate user
        $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );
        return $this->redirectToRoute('app_home_index');
    }

    #[Route('/verify/send', name: 'app_verify_send_email')]
    public function verifySendEmail(Request $request, MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper, UserCustomerRepository $userRepository): Response
    {
        $email = $request->query->get('email');
        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user && $user->getIsVerified()) {
            return $this->redirectToRoute('app_home_index');
        }

        $form = $this->createForm(VerifyEmailAccountType::class);
        $form->handleRequest($request);

        // Le bouton de renvoi d'email a été cliqué
        if ($form->isSubmitted() && $form->isValid()) {
            $this->makeAndSendEmail($verifyEmailHelper, $mailer, $user);
            $this->addFlash('notice', 'Email de vérification renvoyé');
        }
        return $this->render('registration/send_verify_email.html.twig', [
            'sendEmailForm' => $form
        ]);
    }

    public function makeAndSendEmail(VerifyEmailHelperInterface $verifyEmailHelper, MailerInterface $mailer, UserCustomer $user)
    {
        $signedURL = $verifyEmailHelper->generateSignature(
            'app_registration_verify_email',
            $user->getId(),
            $user->getEmail(),
            ['id', $user->getId()]
        );
        $email = (new TemplatedEmail())
            ->from($_ENV['CONTACT_EMAIL'])
            ->to($user->getEmail())
            ->subject('Garage Vincent Parrot : email de validation de compte')
            ->htmlTemplate('email/email_verify_registration.html.twig')
            ->context([
                'url' => $signedURL->getSignedUrl(),
                'firstname' => $user->getFirstname(),
            ]);
        $mailer->send($email);
    }
}