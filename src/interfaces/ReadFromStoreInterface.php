<?php
namespace Inventory\Interfaces;

//Interface that handles reading from a store
interface ReadFromStoreInterface 
{

    /**
     * @return array
     */
    public function getAllProducts(): array;

    /**
     * @param int $productId
     * @return array
     */
    public function getProduct(int $productId): array;

    /**
     * @param int $productId
     * @param string $key
     * @return int
     */
    public function getProductKeyValue(int $productId, string $key): int;

}