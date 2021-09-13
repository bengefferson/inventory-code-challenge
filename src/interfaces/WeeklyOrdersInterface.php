<?php
namespace Inventory\Interfaces;

//Interface that handles weekly ordees
interface WeeklyOrdersInterface
{

    /**
     * @param array $weekOrders
     * @return void
     */
    public function processWeekOrders(array $weekOrders): void;
}