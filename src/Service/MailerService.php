<?php

namespace App\Service;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class MailerService
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
    ){}

    public function makeAndSendEmail(User $user, string $route, string $from, string $subject, string $htmlTemplate): bool
    {
        try {
            $signedURL = $this->verifyEmailHelper->generateSignature(
                $route,
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId(), 'email' => $user->getEmail()]
            );

            $email = (new TemplatedEmail())
                ->from($from)
                ->to($user->getEmail())
                ->subject($subject)
                ->htmlTemplate($htmlTemplate)
                ->context([
                    'url' => $signedURL->getSignedUrl(),
                    'firstname' => $user->getFirstname(),
                ]);
            $this->mailer->send($email);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}