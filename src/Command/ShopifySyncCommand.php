<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShopifySyncCommand extends Command
{
    protected static $defaultName = 'app:shopify:products:sync';

    protected function configure()
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = $this->adminAPI->getProducts();

        $variants = array_column($products, 'variants');

        // extract invetory items ids from product variants
        $inventoryItemsIds = array_map(
            fn ($variant) => array_column($variant, 'inventory_item_id'),
            $variants
        );
        $inventoryItemsIds = array_merge(...$inventoryItemsIds);

        $inventoryItems = $this->adminAPI->getInventoryItems($inventoryItemsIds);

        // set id as key
        $inventoryItems = array_combine(array_column($inventoryItems, 'id'), $inventoryItems);

        // add inventory items to product variants
        foreach ($products as &$product) {
            foreach ($product['variants'] as &$variant) {
                $variant['inventory_item'] = $inventoryItems[$variant['inventory_item_id']];
            }
        }

        return Command::SUCCESS;
    }
}
