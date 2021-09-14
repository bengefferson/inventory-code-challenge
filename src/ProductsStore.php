<?php
declare(strict_types=1);
namespace Inventory;

use Inventory\Interfaces\ProductsStoreInterface;
use Inventory\Config;

//Track total products sold for whole week
class ProductsStore implements ProductsStoreInterface
{
    protected $products;

    public function __construct()
    {
        $this->products = Config::PRODUCTS_TABLE_INIT;
    }

    /**
     * @return array
     */
    public function getAllProducts(): array
    {
        return $this->products;
    }

    /**
     * @param int $productId
     * @param string $key
     * @return mixed
     */
    public function getProductKeyValue(int $productId, string $key): mixed
    {
        return $this->products[$productId][$key];
    }

    /**
     * @param int $productId
     * @return array
     */
    public function getProduct(int $productId): array
    {
        return $this->products[$productId];
    }

    /**
     * @param int $productId
     * @param string $key
     * @param int $value
     * @return void
     */
    public function updateProductKeyValue(int $productId, string $key, int $value): void
    {
        $this->products[$productId][$key] += $value;
    }

    /**
     * @param int $productId
     * @param string $key
     * @param int $value
     * @return void
     */
    public function setProductKeyValue(int $productId, string $key, int $value): void
    {
        $this->products[$productId][$key] = $value;
    }

    /**
     * @param int $productId
     * @param array $product
     * @return void
     */
    public function addProduct(int $productId, array $product): void
    {
        $this->products[$productId]= $product;
    }
}