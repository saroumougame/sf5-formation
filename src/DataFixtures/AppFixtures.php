<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Movie;
use App\Entity\Review;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getMovies() as $movie) {
            $m = new Movie();
            $m
                ->setTitle($movie['title'])
                ->setReleaseDate(new \DateTimeImmutable($movie['releaseDate']))
                ->setImage($movie['image'])
                ->setPlot($movie['plot'])
            ;
            $manager->persist($m);
        }

        foreach ($this->getUsers() as $user) {
            $u = new User();
            $u
                ->setFirstName($user[0])
                ->setLastName($user[1])
                ->setEmail($user[2])
                ->setPassword($user[3])
            ;
            $manager->persist($u);
        }

        $manager->flush();

        $movie1 = $manager->getRepository(Movie::class)->findOneBy(['title' => 'Memento']);
        $user1 = $manager->getRepository(User::class)->findOneBy(['email' => 'joseph@joseph.io']);

        $review = new Review();
        $review
            ->setRating(5)
            ->setContent("Ca me fait mal de dire ça car j'avais bien aimé le précédent mais pour moi c'est une totale déception! Ce film sous-exploite totalement les possibilitées d'Infinity War pour uniquement pour se concentrer sur de curieux égarements dont on se fiche royalement! C'est laborieux de bout en bout, car trop boursouflé (acteurs en roue libre) et l'humour je n'en parle même pas. Pour moi c'est niveau 'thor ragnarok' ...")
            ->setMovie($movie1)
            ->setUser($user1)
        ;

        $manager->persist($review);

        $manager->flush();
    }

    public function getMovies(): array
    {
        return [
            ['title' => 'Memento', 'releaseDate' => '2000-01-01', 'plot' => 'Plot 1', 'image' => '/assets/images/movie-image-samples/memento.jpeg'],
            ['title' => 'Insomnia', 'releaseDate' => '2002-03-04', 'plot' => 'Plot 2', 'image' => '/assets/images/movie-image-samples/insomnia.jpeg'],
            ['title' => 'The Dark Knight ', 'releaseDate' => '2008-05-01', 'plot' => 'Plot 3', 'image' => '/assets/images/movie-image-samples/the-dark-knight.jpeg'],
            ['title' => 'Inception', 'releaseDate' => '2010-02-05', 'plot' => 'Plot 4', 'image' => '/assets/images/movie-image-samples/inception.jpeg'],
            ['title' => 'Man Of Steel', 'releaseDate' => '2013-05-05', 'plot' => 'Plot 5', 'image' => '/assets/images/movie-image-samples/man-of-steel.jpeg'],
            ['title' => 'Dunkirk', 'releaseDate' => '2017-06-07', 'plot' => 'Plot 6', 'image' => '/assets/images/movie-image-samples/dunkirk.jpeg'],
        ];
    }

    public function getUsers(): array
    {
        return [
            ['joseph', 'ROUFF', 'joseph@joseph.io', '$2y$13$gUT1cQ4zkVv59X.LgMdoResStdRxpbxPVuBoRcQK03enmdz6iirN6'],
            ['omar', 'SY', 'omar@sy.io', '$2y$13$zyfUKHUIZeGXD1OdrDGKx.bOpwmUsImDq0BqFncG4g8lcD6eoM3Je']
        ];
    }
}
