<?php
namespace Inventory\Interfaces;

//Inetrface that handles writing to store
interface WriteToStoreInterface 
{

    /**
     * @param int $productId
     * @param string $key
     * @param int $value
     * @return void
     */
    public function updateProductKeyValue(int $productId, string $key, int $value): void;

    /**
     * @param int $productId
     * @param string $key
     * @param int $value
     * @return void
     */
    public function setProductKeyValue(int $productId, string $key, int $value): void;

    /**
     * @param int $productId
     * @param array $product
     * @return void
     */
    public function addProduct(int $productId, array $product): void;

}