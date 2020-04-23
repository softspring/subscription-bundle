<?php

namespace Softspring\SubscriptionBundle\Command;

use Softspring\SubscriptionBundle\Manager\ProductManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncAllProductsCommand extends Command
{
    /**
     * @var ProductManagerInterface
     */
    protected $productManager;

    /**
     * SyncAllProductsCommand constructor.
     *
     * @param ProductManagerInterface $productManager
     */
    public function __construct(ProductManagerInterface $productManager)
    {
        parent::__construct();
        $this->productManager = $productManager;
    }

    protected function configure()
    {
        $this->setName('sfs_subscription:products:sync-all');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->productManager->syncAll();
        $output->writeln('Synced all products');
    }
}