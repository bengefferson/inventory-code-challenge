<?php
namespace Inventory\Interfaces;


interface InventoryInterface
{
    /**
     * @param int $productId
     * @return int
     */
    public function getStockLevel(int $productId): int;

    /**
     * @param int $productId
     * * @param int $itemUnits
     * @return void
     */
    public function updateStockLevel(int $productId, int $itemUnits): void;
}
