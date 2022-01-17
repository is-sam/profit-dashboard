<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Shop;
use App\Entity\Variant;
use App\Exception\ShopifySessionDataEmptyException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Shopify\Clients\Rest;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class ShopifyAdminAPIService.
 */
class ShopifyAdminAPIService
{
    public const ORDERS_TOTAL_PRICE = 'total_price';
    public const ORDERS_FINANCIAL_STATUS = 'financial_status';

    protected string $shop;
    protected Rest|null $client = null;

    protected EntityManagerInterface $entityManager;

    /**
     * Class constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setShop(string $shop)
    {
        $this->shop = $shop;
    }

    protected function getClient()
    {
        if ($this->shop === null) {
            throw new Exception("Shop empty in ShopifyAdminAPIService");
        }

        if ($this->client instanceof Rest) {
            return $this->client;
        }

        /** @var Shop $shop */
        $shop = $this->entityManager->getRepository(Shop::class)
            ->findOneBy(['url' => $this->shop]);

        if (empty($shop)) {
            throw new ShopifySessionDataEmptyException();
        }

        $this->client = new Rest($shop->getUrl(), $shop->getAccessToken());

        return $this->client;
    }

    public function getOrders(DateTime $dateStart = null, DateTime $dateEnd = null): array
    {
        $client = $this->getClient();

        $fields = [
            self::ORDERS_TOTAL_PRICE,
            self::ORDERS_FINANCIAL_STATUS,
            'line_items'
        ];

        $params = [
            'created_at_min' => $dateStart->format('c'),
            'created_at_max' => $dateEnd->modify("+1 day")->format('c'),
            'status'         => 'any',
            'fields'         => implode(',', $fields)
        ];

        $response = $client->get("orders", [], $params)
            ->getDecodedBody();

        if (array_key_exists('errors', $response)) {
            throw new Exception("getOrders: {$response['errors']}");
        }

        $orders = $response['orders'];

        return $orders;
    }

    public function getProducts(): array
    {
        $client = $this->getClient();

        // get products
        $fields = [
            'id',
            'title',
            'handle',
            'variants'
        ];

        $params = [
            'fields'         => implode(',', $fields)
        ];

        $response = $client->get("products", [], $params)
            ->getDecodedBody();

        if (!array_key_exists('products', $response)) {
            throw new Exception("key 'products' not found in getProducts() response");
        }

        $products = $response['products'];
        // dd($products);

        return $products;
    }

    public function getInventoryItems(array $ids): array
    {
        $client = $this->getClient();

        // get inventory items by ids
        $fields = [
            'id',
            'cost'
        ];

        $params = [
            'ids'       => implode(',', $ids),
            'fields'    => implode(',', $fields)
        ];

        $response = $client->get('inventory_items', [], $params)
            ->getDecodedBody();

        if (!array_key_exists('inventory_items', $response)) {
            throw new Exception("key 'inventory_items' not found in getInventoryItems() response");
        }

        return $response['inventory_items'];
    }

    public function getInventoryItem(int $id): array
    {
        $client = $this->getClient();

        // get inventory items by ids
        $fields = [
            'id',
            'cost'
        ];

        $params = [
            'fields'    => implode(',', $fields)
        ];

        $response = $client->get("inventory_items/$id", [], $params)
            ->getDecodedBody();

        if (!array_key_exists('inventory_item', $response)) {
            throw new Exception("key 'inventory_item' not found in getInventoryItem() response");
        }

        return $response['inventory_item'];
    }

    public function syncProducts()
    {
        $shopifyProducts = $this->getProducts();

        foreach ($shopifyProducts as $shopifyProduct) {
            /** @var Product $product */
            $product = $this->entityManager->getRepository(Product::class)
                ->findOneBy(['identifier' => $shopifyProduct['id']]);

            if (empty($product)) {
                $shop = $this->entityManager->getRepository(Shop::class)
                    ->findOneBy(['url' => $this->shop]);
                $product = new Product();
                $product->setShop($shop);
                $product->setIdentifier($shopifyProduct['id']);
                $this->entityManager->persist($product);
            }

            if ($product->getIdentifier() != $shopifyProduct['id']) {
                throw new Exception("Product identifier mismatch for Product:{$product->getId()}");
            }

            $product->setTitle($shopifyProduct['title']);
            $product->setHandle($shopifyProduct['handle']);

            foreach ($shopifyProduct['variants'] as $shopifyVariant) {
                /** @var Variant $variant */
                $variant = $this->entityManager->getRepository(Variant::class)
                    ->findOneBy(['identifier' => $shopifyVariant['id']]);
                $inventoryItem = $this->getInventoryItem($shopifyVariant['inventory_item_id']);

                if (empty($variant)) {
                    $variant = new Variant();
                    $variant->setProduct($product);
                    $variant->setIdentifier($shopifyVariant['id']);
                    $this->entityManager->persist($variant);
                }

                if ($variant->getIdentifier() != $shopifyVariant['id']) {
                    throw new Exception("Product variant identifier mismatch for Variant:{$variant->getId()}");
                }

                $variant->setTitle($shopifyVariant['title']);
                $variant->setCost($inventoryItem['cost']);
            }
        }

        $this->entityManager->flush();
    }
}
