<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type as Type;


/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{

    private $passwordHasher;
    /**
     * UserController constructor.
     */

    private $eventDispatcher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordHasher = $userPasswordHasher;
    }


    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {

       $userForm = $this->createForm(UserType::class);
        $userForm->add('submit', Type\SubmitType::class,[
            'label' => 'register']);

        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()){
            /** @var User $user */
            $user = $userForm->getData();
            $passwordHash = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($passwordHash);
            $entityManager->persist($user);
            $entityManager->flush();
            dump($user);

        }


        return $this->render('user/register.html.twig', [
            'register_form' => $userForm->createView(),
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


    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheck(): Response
    {

    }

    /**
     * @Route("/logout", name="login_logout")
     */
    public function logout(): Response
    {

    }

}
