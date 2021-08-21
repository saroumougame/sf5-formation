<?php
namespace App\Command;
use App\Omdb\OmdbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportException;

class OmdbSearchCommand extends Command
{
    protected static $defaultName = 'app:omdb:search';
    protected static $defaultDescription = 'Add a short description for your command';
    private $omdbClient;
    public function __construct(OmdbClient $omdbClient, string $name = null)
    {
        $this->omdbClient = $omdbClient;
        parent::__construct($name);
    }
    protected function configure(): void
    {
        $this
            ->addArgument('movie_name', InputArgument::REQUIRED, 'Seach all movies matching movie_name')
            ->addOption('all', null, InputOption::VALUE_NONE,'all allow all type of entry')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $movieName = $input->getArgument('movie_name');
        $output -> writeln(sprintf('Search all movies form OMDB matching: %s.', $movieName));
        $omdb_movie_parameters = [];

        if ($movieType = $io->choice('Are you sure to display all items or only one of the followings ?', ['movie', 'series'])){
            if ($movieType !== 'all') {
                $omdb_movie_parameters['type'] = $movieType;
            }
        }

        try {
            $rows = $this->omdbClient->requestBySearch($movieName, $omdb_movie_parameters);
            if (isset($rows['Search']) && count ($rows['Search'])) {
                $tableRows = [];
                foreach ($rows['Search'] as $row) {
                    $tableRows[] = [$row['Title'], $row['Year'], $row['imdbID']];
                }
                $io->table(
                    ['Tile', 'Year', 'IMDB ID'],
                    $tableRows
                );
            } else {
                $io->warning('😔 Nothing found');
            }
        } catch(TransportException $e) {
            $io->warning(sprintf('The entertainment "%s" could not be found, please try with another name.', $movieName));
        }

        return Command::SUCCESS;
    }
}

