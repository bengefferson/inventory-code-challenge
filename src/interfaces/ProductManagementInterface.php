<?php
namespace Inventory\Interfaces;

use Inventory\Interfaces\ProductsSoldInterface;
use Inventory\Interfaces\ProductsPurchasedInterface;
use Inventory\Interfaces\InventoryInterface;

// Interface to handle management of a single product
interface ProductManagementInterface extends InventoryInterface,ProductsPurchasedInterface,ProductsSoldInterface
{

}