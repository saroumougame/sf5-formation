<?php

namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {

        $exeption = $event->getThrowable()->getPrevious();
        if(!$exeption instanceof AccessDeniedException){
          return;
        }

        $response = new RedirectResponse('/');
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
