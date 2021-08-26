<?php

namespace App\EventSubscriber;

use App\Entity\Movie;

use App\Event\MovieShowEvent;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedSubscriber implements EventSubscriberInterface
{

    public function onRegister(MovieShowEvent $movie)
    {
        dump($movie);

        return new Response(new RedirectResponse('/'));
    }


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
            'user_registered' => 'onRegister',
        ];
    }
}
