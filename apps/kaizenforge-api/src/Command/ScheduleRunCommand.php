<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:schedule:run', description: 'Placeholder scheduler command (runs every 60s in Docker when enabled).')]
final class ScheduleRunCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('[scheduler] tick ' . date('c'));
        return Command::SUCCESS;
    }
}
