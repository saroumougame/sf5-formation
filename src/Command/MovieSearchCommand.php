<?php

namespace App\Command;

use App\Omdb\OmdbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieSearchCommand extends Command
{
    protected static $defaultName = 'app:movie:search';
    protected static $defaultDescription = 'Add a short description for your command';

    private $omdbClient;
    /**
     * MovieSearchCommand constructor.
     */
    public function __construct(OmdbClient $omdbClient)
    {
        $this->omdbClient = $omdbClient;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('keyword', InputArgument::OPTIONAL, 'keyword movie')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $keyword = $input->getArgument('keyword');

        if(!$keyword){
            $keyword = $io->ask('saisie titre:', 'sky');
        }

        $movies = $this->omdbClient->requestBySearch($keyword);

        $io->progressStart(count($movies['Search']));

        $moviedisplays = [];
        foreach ($movies['Search'] as $moviedisplay){
            $moviedisplays[] = [ $moviedisplay['Title'], $moviedisplay['Year'], $moviedisplay['Type'], 'https://www.imdb.com/title/'.$moviedisplay['imdbID'].'/' ,'<href="'.$moviedisplay['Type'].'">Preview</>' ];
            usleep(100000);
            $io->progressAdvance(1);
        }

        $io->table(['title', 'Year', 'Url' ,'Type'], $moviedisplays);

        $io->success('movie matching keywork: '.$movies['totalResults']);

        return Command::SUCCESS;
    }
}
