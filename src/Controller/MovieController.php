<?php

namespace App\Controller;

use App\Omdb\OmdbClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/movie", name="movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(int $id = null): Response
    {
        return $this->render('movie/show.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }


    /**
     * @Route("/latest", name="latest")
     */
    public function latest(): Response
    {
        return $this->render('movie/latest.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request , HttpClientInterface $httpClient): Response
    {

        $omdb = new OmdbClient($httpClient , '28c5b7b1' , 'https://www.omdbapi.com');


        $keyword = $request->query->get('keyword', 'sky');
        $movieSearch = $omdb->requestBySearch($keyword)['Search'];

        dump($movieSearch);

        return $this->render('movie/search.html.twig', [
            'keyword' => $keyword,
            'moviesSearch' => $movieSearch
        ]);
    }

}
