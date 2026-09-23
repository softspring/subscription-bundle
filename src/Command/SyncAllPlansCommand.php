<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Command;

use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'sfs_subscription:plans:sync-all')]
class SyncAllPlansCommand extends Command
{
    public function __construct(protected PlanManagerInterface $planManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->planManager->syncAll();
        $output->writeln('Synced all plans');

        return Command::SUCCESS;
    }
}
