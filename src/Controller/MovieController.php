<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * Movie details
     * @Route("/movie/{id}", name="movie", requirements={"id"="\d+"}, defaults={"id": 1})
     */
    public function movieDetails($id, EntityManagerInterface $entityManager): Response
    {
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(['id' => $id]);
        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * Top rated
     * @Route("/movie/top-rated")
     */
    public function movieTopRated(): Response
    {
        return $this->render('movie/top-rated.html.twig');
    }

    /**
     * Genres
     * @Route("/movie/genres")
     */
    public function movieGenres(): Response
    {
        return $this->render('movie/genres.html.twig');
    }

    /**
     * Top rated
     * @Route("/movie/latest")
     */
    public function movieLatest(): Response
    {
        return $this->render('movie/latest.html.twig');
    }
}
