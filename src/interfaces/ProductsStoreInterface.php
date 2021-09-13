<?php
namespace Inventory\Interfaces;

use Inventory\Interfaces\ReadFromStoreInterface;
use Inventory\Interfaces\WriteToStoreInterface;

//Interfaceb that handles the reading and writing to and from product store
interface ProductsStoreInterface extends ReadFromStoreInterface, WriteToStoreInterface
{

}