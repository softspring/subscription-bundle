<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Command;

use Softspring\SubscriptionBundle\Manager\ProductManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'sfs_subscription:products:sync-all')]
class SyncAllProductsCommand extends Command
{
    public function __construct(protected ProductManagerInterface $productManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->productManager->syncAll();
        $output->writeln('Synced all products');

        return Command::SUCCESS;
    }
}
