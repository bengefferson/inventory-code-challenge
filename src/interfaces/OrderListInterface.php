<?php
namespace Inventory\Interfaces;

//Class to process a single order list
interface OrderListInterface
{
    /**
     * @param array $orderList
     * @return void
     */
    public function processOrderList(array $orderList): void;

    /**
     * @param array $orderList
     * @return bool
     */
    public function validateOrderList(array $orderList): bool;
}