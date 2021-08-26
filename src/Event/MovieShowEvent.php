<?php
namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

use App\Entity\Movie;

class MovieShowEvent extends Event
{


public $movie;

/**
* @param Movie $movie
*/
public function __construct(Movie $movie)
{
$this->movie = $movie;
}
}