<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Omdb\OmdbClient;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/movie", name="movie_")
 */
class MovieController extends AbstractController
{

    private $omdb;

    public function __construct(OmdbClient $omdb)
    {
        $this->omdb = $omdb;
    }


    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Movie $id): Response
    {
        if($this->isGranted('MOVIE_VIEW', $id)){
            throw new AccessDeniedException('No role movie view');
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $id,
        ]);
    }


    /**
     * @Route("/latest", name="latest")
     */
    public function latest(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();
        return $this->render('movie/latest.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request , HttpClientInterface $httpClient): Response
    {

        $omdb = $this->omdb;


        $keyword = $request->query->get('keyword', 'sky');
        $movieSearch = $omdb->requestBySearch($keyword)['Search'];

        dump($movieSearch);

        return $this->render('movie/search.html.twig', [
            'keyword' => $keyword,
            'moviesSearch' => $movieSearch
        ]);
    }


    /**
     * @Route("/{id}/import")
     * @IsGranted(attributes="ROLE_USER", message="you need to connect")
     */
    public function import($id, Request $request, EntityManagerInterface $entityManager): Response
    {


        $omdb = $this->omdb;

        $result = $omdb->requestById($id);

        $movie = Movie::fromApi($result);

        $entityManager->persist($movie);
        $entityManager->flush();



        return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
    }



}
