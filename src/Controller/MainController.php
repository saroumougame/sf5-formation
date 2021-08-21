<?php

namespace App\Controller;

use App\Omdb\OmdbClient;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="index_page")
     */
    public function index(MovieRepository $movieRepository, OmdbClient $omdbClient): Response
    {
        $movies = $movieRepository->findAll();
        $moviesFromDb = $omdbClient->requestBySearch('Lord Of the rings');
        dump($moviesFromDb);

        return $this->render('index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Help center
     * @Route("/contact")
     */
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }
}
