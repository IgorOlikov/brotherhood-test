<?php

namespace App\Command;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:purge-fixtures-rows',
    description: 'Очистить все фикстуры без удаления структуры базы',
)]
class PurgeFixturesRowsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $output->writeln('All database data removed successfully!');
        return Command::SUCCESS;
    }
}
