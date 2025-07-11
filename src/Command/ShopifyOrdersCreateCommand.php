<?php

namespace App\Command;

use App\Repository\VariantRepository;
use Shopify\Clients\Rest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShopifyOrdersCreateCommand extends Command
{
    protected static $defaultName = 'app:shopify:orders:create';

    protected VariantRepository $variantRepository;

    /**
     * Class constructor.
     */
    public function __construct(VariantRepository $variantRepository)
    {
        parent::__construct();
        $this->variantRepository = $variantRepository;
    }

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new Rest(
            getenv('SHOPIFY_STORE_DOMAIN') ?: '',
            getenv('SHOPIFY_ADMIN_API_TOKEN') ?: ''
        );
        $variants = $this->variantRepository->findAll();

        if (empty($variants)) {
            $output->write('No product variants found !');

            return Command::FAILURE;
        }

        for ($i = 0; $i < 10; ++$i) {
            $lineItems = [];
            for ($j = 0; $j < rand(1, 3); ++$j) {
                $variant = $variants[rand(0, count($variants) - 1)];
                $lineItems[] = [
                    'variant_id' => $variant->getIdentifier(),
                    'quantity' => rand(1, 3),
                ];
            }

            $response = $client->post('orders', [
                'order' => [
                    'line_items' => $lineItems,
                ],
            ]);
            dump($response->getDecodedBody());
            sleep(.5);
        }

        $output->write($response->getDecodedBody());

        return Command::SUCCESS;
    }
}
