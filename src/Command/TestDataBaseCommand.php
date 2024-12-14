<?php

namespace App\Command;

use App\Controller\UrlController\FetchUrlScheduler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:database',
    description: 'Add a short description for your command',
)]
class TestDataBaseCommand extends Command
{
    public function __construct(private FetchUrlScheduler $fetchUrlScheduler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       $response = $this->fetchUrlScheduler->fetchNewsUrlSchedule();
         dump($response);
       $output->writeln('Done');

       return Command::SUCCESS;
    }
}
