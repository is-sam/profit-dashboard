<?php

namespace App\Command;

use App\Entity\Shop;
use App\Service\ShopifyAdminAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShopifyProductSyncCommand extends Command
{
    protected static $defaultName = 'app:shopify:products:sync';
    protected ShopifyAdminAPIService $adminAPI;
    protected EntityManagerInterface $entityManager;

    /**
     * Class constructor.
     */
    public function __construct(ShopifyAdminAPIService $adminAPI, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->adminAPI = $adminAPI;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Sync Products & Variants Command !');

        $shops = $this->entityManager->getRepository(Shop::class)
            ->findAll();

        $shopsCount = count($shops);

        $output->writeln("Syncing $shopsCount shops");

        foreach ($shops as $shop) {
            $output->writeln("Shop: {$shop->getUrl()}");
            $this->adminAPI->setShop($shop);
            try {
                $this->adminAPI->syncProducts();
            } catch (Exception $e) {
                $output->writeln("Error: {$e->getMessage()}");
            }
        }

        return Command::SUCCESS;
    }
}
