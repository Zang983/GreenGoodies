<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
//        $payload = $event->getData();
//        $user = $event->getUser();
//        if (!$user->isApiAccess()) {
//            throw new AccessDeniedException('You need to activate your access on website !');
//        }
//        $payload['id'] = $user->getId();
//        $payload['email'] = $user->getEmail();
//        $payload['roles'] = $user->getRoles();
//
//        $event->setData($payload);
    }
}