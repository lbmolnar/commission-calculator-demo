<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:commission:calculate',
    description: 'Import news from JSON file',
    hidden: false
)]
class CommissionCalculator extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        echo 'HERE' . PHP_EOL;
        return Command::SUCCESS;
    }
}
