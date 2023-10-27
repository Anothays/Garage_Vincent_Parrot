<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CustomPasswordType;
use App\Form\ForgetPasswordEmailType;
use App\Repository\UserStaffMemberRepository;
use App\Security\AppAuthenticator;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[Route('/password')]
class PasswordController extends AbstractController
{
    public function __construct( private MailerService $mailerService, private UserStaffMemberRepository $staffMemberRepository){}

    #[Route('/forget-password', name: 'app_forget_password')]
    public function forgetPassword(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForgetPasswordEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            try {
                $user = $entityManager
                    ->createQuery("select u from App\Entity\UserStaffMember u where u.email = :email")
                    ->setParameter('email', $email)
                    ->getResult();
                if (empty($user)) {
                    $user = $entityManager
                        ->createQuery("select u from App\Entity\UserCustomer u where u.email = :email")
                        ->setParameter('email', $email)
                        ->getOneOrNullResult();
                }
                if (!empty($user)) {
                    $from = $this->staffMemberRepository->findByRole("ROLE_SUPER_ADMIN")[0]->getEmail();
                    $subject = 'Garage Vincent Parrot : réinitialisation du mot de passe';
                    $htmlTemplate = 'email/email_reset_password.html.twig';
                    $this->mailerService->makeAndSendEmail($user, 'app_reset_password_form', $from, $subject, $htmlTemplate);
                    return $this->redirectToRoute('forget_password_email_send');
                }
                return $this->redirectToRoute('forget_password_email_send');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Une erreur est survenue');
                $this->redirectToRoute('app_home_index');
            }

        }
        return $this->render('password/forget_password.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/forget-password-email-send', name: 'forget_password_email_send')]
    public function resetPasswordEmailSend(): Response
    {
        return $this->render('password/forget_password_email_send.html.twig');
    }

    #[Route('/reset-password', name: 'app_reset_password_form')]
    public function resetPassword(
        Request $request,
        VerifyEmailHelperInterface $verifyEmailHelper,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppAuthenticator $authenticator
    )
    {
        /**
         * @var User $user
         * retrieve user in database
         */
        $user = $entityManager
            ->createQuery('select us from App\Entity\UserStaffMember us where us.email = :email')
            ->setParameter('email', $request->query->get('email'))
            ->getOneOrNullResult();
        if (empty($user)) {
            $user = $entityManager
                ->createQuery('select uc from App\Entity\UserCustomer uc where uc.email = :email')
                ->setParameter('email', $request->query->get('email'))
                ->getOneOrNullResult();
            if (empty($user)) { return $this->redirectToRoute('app_home_index'); }
        }

        // Validate email
        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail()
            );
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());
            return $this->redirectToRoute('app_home_index');
        }

        // create reset-password form
        $form = $this->createForm(CustomPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pw = $form->getData()['password'];
            $user->setPassword($userPasswordHasher->hashPassword($user, $pw));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a bien été changé');

            // Authenticate User
            $userAuthenticator->authenticateUser($user, $authenticator, $request);

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('password/reset_password_form.html.twig', [
            'form' => $form
        ]);
    }

}