<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(): Response
    {
        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
