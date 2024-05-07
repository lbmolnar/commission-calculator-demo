<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\CommissionCalculator as CommissionCalculatorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:commission:calculate',
    description: 'Import news from JSON file',
    hidden: false
)]
class CommissionCalculator extends Command
{
    public function __construct(
        private readonly CommissionCalculatorService $calculator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'File to import');
    }
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $commissions = $this->calculator->calculateCommission($input->getArgument('file'));

        foreach ($commissions as $commission) {
            $output->writeln((string) $commission->getCommission());
        }

        return Command::SUCCESS;
    }
}
