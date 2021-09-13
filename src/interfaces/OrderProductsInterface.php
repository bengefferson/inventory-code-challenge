<?php
namespace Inventory\Interfaces;

use Inventory\Interfaces\OrderListInterface;
use Inventory\Interfaces\DailyOrdersInterface;
use Inventory\Interfaces\WeeklyOrdersInterface;

//Interface that handles the management of all orders for a particular period dof time (a week in this case)
interface OrderProductsInterface extends WeeklyOrdersInterface,OrderListInterface,DailyOrdersInterface
{

    /**
     * @param array $orders
     * @return void
     */
    public function processOrders(array $orders): void;

    /**
     * @return array
     */
    public function orderSummary(): array;

}