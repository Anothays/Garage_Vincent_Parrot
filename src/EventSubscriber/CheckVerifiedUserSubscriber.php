<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\UserCustomer;
use App\Security\AccountNotVerifiedAuthenticationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{

    public function __construct(private RouterInterface $router)
    {

    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();

        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

        if ($user instanceof UserCustomer) {
            if (!$user->getIsVerified()) {
                throw new AccountNotVerifiedAuthenticationException();
            }
        }


    }

    public function onLoginFailure(LoginFailureEvent $event)
    {
        if (!$event->getException() instanceof AccountNotVerifiedAuthenticationException) {
            return;
        }

        $passport = $event->getPassport();
        $user = $passport->getUser();

        $response = new RedirectResponse($this->router->generate('app_verify_send_email', [
            'email' => $user->getEmail(),
            'id' => $user->getId()
        ]));
        $event->setResponse($response);
    }
}