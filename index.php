#!/usr/bin/env php
<?php
declare(strict_types=1);
require 'vendor/autoload.php';

use Inventory\OrderProcessor;
use Inventory\ProductManager;
use Inventory\OrderProductsWeekly;
use Inventory\OutputAsciiTableSummary;
use Inventory\ProductsStore;


$productStore = new ProductsStore;
$productManager = new ProductManager($productStore);
$orderProducts = new OrderProductsWeekly($productManager, $productStore);
$summary = new OutputAsciiTableSummary;
$processOrder = new OrderProcessor($orderProducts, $summary);

if(sizeOf($argv) == 2){
    $source = $argv[1];
}else{
    echo "One argument is required, which should be a valid input file";
    echo "\n";
    die();
}

$processOrder->processFromJson($source);


