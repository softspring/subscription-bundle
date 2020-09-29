<?php

namespace Softspring\SubscriptionBundle\Command;

use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncAllPlansCommand extends Command
{
    /**
     * @var PlanManagerInterface
     */
    protected $planManager;

    /**
     * SyncAllPlansCommand constructor.
     *
     * @param PlanManagerInterface $planManager
     */
    public function __construct(PlanManagerInterface $planManager)
    {
        parent::__construct();
        $this->planManager = $planManager;
    }

    protected function configure()
    {
        $this->setName('sfs_subscription:plans:sync-all');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->planManager->syncAll();
        $output->writeln('Synced all plans');

        return 0;
    }
}