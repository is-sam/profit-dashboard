<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Variant;
use DateTime;
use Exception;
use Shopify\Clients\Rest;

/**
 * Class ShopifyAdminAPIService.
 */
class ShopifyAdminAPIService extends AbstractService
{
    public const ORDERS = 'orders';
    public const ORDERS_TOTAL_PRICE = 'total_price';
    public const ORDERS_FINANCIAL_STATUS = 'financial_status';
    public const LINE_ITEMS = 'line_items';
    public const LINE_ITEMS_NAME = 'name';

    public const PRODUCTS = 'products';
    public const PRODUCTS_ID = 'id';
    public const PRODUCTS_TITLE = 'title';
    public const PRODUCTS_HANDLE = 'handle';
    public const PRODUCTS_IMAGES = 'images';

    public const VARIANTS = 'variants';
    public const VARIANTS_ID = 'id';
    public const VARIANTS_VARIANT_ID = 'variant_id';
    public const VARIANTS_TITLE = 'title';
    public const VARIANTS_COST = 'cost';
    public const VARIANTS_INVENTORY_ITEM_ID = 'inventory_item_id';

    public const INVENTORY_ITEMS = 'inventory_items';
    public const INVENTORY_ITEM = 'inventory_item';
    public const INVENTORY_ITEMS_ID = 'id';
    public const INVENTORY_ITEMS_COST = 'cost';

    protected ?Rest $client = null;

    protected function getClient()
    {
        if (null === $this->client) {
            $this->client = new Rest($this->shop->getUrl(), $this->shop->getAccessToken());
        }

        return $this->client;
    }

    public function isTokenValid()
    {
        $client = $this->getClient();

        $response = $client->get('products/count')->getDecodedBody();

        if (array_key_exists('errors', $response)) {
            return false;
        }

        return true;
    }

    public function getOrders(DateTime $dateStart = null, DateTime $dateEnd = null): array
    {
        $client = $this->getClient();

        $fields = [
            self::ORDERS_TOTAL_PRICE,
            self::ORDERS_FINANCIAL_STATUS,
            self::LINE_ITEMS,
        ];

        $params = [
            'status' => 'any',
            'fields' => implode(',', $fields),
        ];

        if ($dateStart) {
            $params['created_at_min'] = $dateStart->format('c');
        }

        if ($dateEnd) {
            $params['created_at_max'] = $dateEnd->format('c');
        }

        $response = $client->get(self::ORDERS, [], $params)
            ->getDecodedBody();

        if (array_key_exists('errors', $response)) {
            throw new Exception("getOrders: {$response['errors']}");
        }

        $orders = $response[self::ORDERS];

        return $orders;
    }

    public function getProducts(): array
    {
        $client = $this->getClient();

        // get products
        $fields = [
            self::PRODUCTS_ID,
            self::PRODUCTS_TITLE,
            self::PRODUCTS_HANDLE,
            self::VARIANTS,
            self::PRODUCTS_IMAGES,
        ];

        $params = [
            'fields' => implode(',', $fields),
        ];

        $response = $client->get(self::PRODUCTS, [], $params)
            ->getDecodedBody();

        if (array_key_exists('errors', $response)) {
            throw new Exception("getProducts: {$response['errors']}");
        }

        $products = $response[self::PRODUCTS];
        return $products;
    }

    public function getInventoryItems(array $ids): array
    {
        $client = $this->getClient();

        // get inventory items by ids
        $fields = [
            self::INVENTORY_ITEMS_ID,
            self::INVENTORY_ITEMS_COST,
        ];

        $params = [
            'ids' => implode(',', $ids),
            'fields' => implode(',', $fields),
        ];

        $response = $client->get(self::INVENTORY_ITEMS, [], $params)
            ->getDecodedBody();

        if (array_key_exists('errors', $response)) {
            throw new Exception("getInventoryItems: {$response['errors']}");
        }

        return $response[self::INVENTORY_ITEMS];
    }

    public function getInventoryItem(int $id): array
    {
        $client = $this->getClient();

        // get inventory items by ids
        $fields = [
            self::INVENTORY_ITEMS_ID,
            self::INVENTORY_ITEMS_COST,
        ];

        $params = [
            'fields' => implode(',', $fields),
        ];

        $response = $client->get(self::INVENTORY_ITEMS."/$id", [], $params)
            ->getDecodedBody();

        if (array_key_exists('errors', $response)) {
            throw new Exception("getInventoryItem: {$response['errors']}");
        }

        return $response[self::INVENTORY_ITEM];
    }

    public function syncProducts()
    {
        $shopifyProducts = $this->getProducts();
        $shopifyProducts = array_combine(array_column($shopifyProducts, self::PRODUCTS_ID), $shopifyProducts);

        $this->saveProductsAndVariants($shopifyProducts);
        
        $this->deleteProducts($shopifyProducts);

        $this->getOrphanVariants($shopifyProducts);
    }

    private function saveProductsAndVariants($shopifyProducts)
    {
        foreach ($shopifyProducts as $shopifyProduct) {
            /** @var Product $product */
            $product = $this->entityManager->getRepository(Product::class)
                ->findOneBy(['identifier' => $shopifyProduct[self::PRODUCTS_ID]]);

            if (empty($product)) {
                $product = new Product();
                $product->setShop($this->shop);
                $product->setIdentifier($shopifyProduct[self::PRODUCTS_ID]);
                $this->entityManager->persist($product);
            }

            if ($product->getIdentifier() != $shopifyProduct[self::PRODUCTS_ID]) {
                throw new Exception("Product identifier mismatch for Product:{$product->getId()}");
            }

            $product->setTitle($shopifyProduct[self::PRODUCTS_TITLE]);
            $product->setHandle($shopifyProduct[self::PRODUCTS_HANDLE]);

            $images = $shopifyProduct[self::PRODUCTS_IMAGES];
            $variantImages = [];
            foreach ($images as $image) {
                if ($image['position'] == 1) {
                    $product->setImage($image['src']);
                }
                foreach ($image['variant_ids'] as $variantId) {
                    $variantImages[$variantId] = $image['src'];
                }
            }

            foreach ($shopifyProduct[self::VARIANTS] as $shopifyVariant) {
                /** @var Variant $variant */
                $variant = $this->entityManager->getRepository(Variant::class)
                    ->findOneBy(['identifier' => $shopifyVariant[self::VARIANTS_ID]]);
                $inventoryItem = $this->getInventoryItem($shopifyVariant[self::VARIANTS_INVENTORY_ITEM_ID]);

                if (empty($variant)) {
                    $variant = new Variant();
                    $variant->setProduct($product);
                    $variant->setIdentifier($shopifyVariant[self::VARIANTS_ID]);
                    $this->entityManager->persist($variant);
                }

                if ($variant->getIdentifier() != $shopifyVariant[self::VARIANTS_ID]) {
                    throw new Exception("Product variant identifier mismatch for Variant:{$variant->getId()}");
                }

                $variant->setTitle($shopifyVariant[self::VARIANTS_TITLE]);
                if (null === $variant->getCost()) {
                    $variant->setCost($inventoryItem[self::VARIANTS_COST]);
                }

                if ($image = ($variantImages[$variant->getIdentifier()] ?? null)) {
                    $variant->setImage($image);
                }
            }
        }

        $this->entityManager->flush();
    }
    
    private function deleteProducts($shopifyProducts)
    {
        $products = $this->entityManager->getRepository(Product::class)
            ->findBy(['shop' => $this->shop, 'status' => Product::STATUS_ACTIVE]);
        
        foreach ($products as $product) {
            if (!in_array($product->getIdentifier(), array_column($shopifyProducts, self::PRODUCTS_ID))) {
                $this->entityManager->remove($product);
                continue;
            }

            $variantIds = array_column($shopifyProducts[$product->getIdentifier()][self::VARIANTS], self::VARIANTS_ID);
            foreach ($product->getVariants() as $variant) {
                if (!in_array($variant->getIdentifier(), $variantIds)) {
                    $this->entityManager->remove($variant);
                }
            }
        }
        
        $this->entityManager->flush();
    }

    /**
     * @param array<Product> $shopifyProducts
     */
    private function getOrphanVariants(array $shopifyProducts)
    {
        $orders = $this->getOrders();
        $shopifyVariants = array_reduce(
            $shopifyProducts,
            fn ($carry, $product) => array_merge(
                $carry,
                array_column($product[self::VARIANTS], self::VARIANTS_ID)
            ),
            []
        );

        /** @var Product $placeholderProduct */
        $placeholderProduct = $this->entityManager->getRepository(Product::class)
            ->findOneBy([
                'shop' => $this->shop,
                'status' => Product::STATUS_PLACEHOLDER,
            ]);

        if (empty($placeholderProduct)) {
            $placeholderProduct = new Product();
            $placeholderProduct->setStatus(Product::STATUS_PLACEHOLDER);
            $placeholderProduct->setShop($this->shop);
            $this->entityManager->persist($placeholderProduct);
        }

        $variants = $placeholderProduct->getVariants();
        $variants = array_map(fn (Variant $variant) => $variant->getTitle(), iterator_to_array($variants));

        foreach ($orders as $order) {
            foreach ($order[self::LINE_ITEMS] as $lineItem) {
                if (!in_array($lineItem[self::VARIANTS_VARIANT_ID], $shopifyVariants) and !in_array($lineItem[self::LINE_ITEMS_NAME], $variants)) {
                    $variant = new Variant();
                    $variant->setProduct($placeholderProduct);
                    $variant->setTitle($lineItem[self::LINE_ITEMS_NAME]);
                    $variant->setIdentifier($lineItem[self::VARIANTS_VARIANT_ID]);
                    $variants[] = $lineItem[self::LINE_ITEMS_NAME];

                    $this->entityManager->persist($variant);
                }
            }
        }

        $this->entityManager->flush();
    }
}
