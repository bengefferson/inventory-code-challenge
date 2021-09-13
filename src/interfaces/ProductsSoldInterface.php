<?php
namespace Inventory\Interfaces;

//Interface that handles the managemt of product sale
interface ProductsSoldInterface
{
    /**
     * @param int $productId
     * @return int
     */
    public function getSoldTotal(int $productId): int;

    /**
     * @param int $productId
     * @param int $itemUnits
     * @return void
     */
    public function updateSoldTotal(int $productId, int $itemUnits): void;
}
