<?php
namespace Inventory\Interfaces;

//Class for processing daily orders
interface DailyOrdersInterface
{

    /**
     * @param array $dayOrders
     * @return void
     */
    public function processDayOrders(array $dayOrders): void;

    /**
     * @return void
     */
    public function processDailyProductPurchase(): void;
}
