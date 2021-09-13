<?php
declare(strict_types=1);

namespace Test;

use Inventory\ProductsStore;
use Inventory\Config;
use Test\TestCase;
use Exception;

class ProductsStoreTest extends TestCase
{

    public function setUp() :void
    {
        parent::setUp();
        $this->productStore = $this->mock(ProductsStore::class);
    }

    public function testCanGetAllProductsFromStore()
    {
        $array = Config::PRODUCTS_TABLE_INIT;
        $this->assertEquals($this->productStore->getAllProducts(),$array);
    }

    public function testCanGetAParticularProductFromStoreBasedOnId()
    {
        $array = Config::PRODUCTS_TABLE_INIT[1];
        $this->assertEquals($this->productStore->getProduct(1),$array);
    }

    public function testCanGetAParticularProductsKeyValueFromStoreBasedOnId()
    {
        $this->assertEquals($this->productStore->getProductKeyValue(1,'sold'),0);
        $this->assertEquals($this->productStore->getProductKeyValue(1,'stock'),20);
    }

    public function testCanSetAParticularProductsKeyValueFromStoreBasedOnId()
    {
        $this->productStore->setProductKeyValue(1,'pending',20);
        $this->assertEquals($this->productStore->getAllProducts()[1]['pending'],20);
        $this->productStore->setProductKeyValue(1,'pending_wait',2);
        $this->assertEquals($this->productStore->getAllProducts()[1]['pending_wait'],2);
    }

    public function testCanUpdateAParticularProductsKeyValueFromStoreBasedOnId()
    {
        $this->productStore->updateProductKeyValue(1,'stock',20);
        $this->assertEquals($this->productStore->getAllProducts()[1]['stock'],40);
        $this->productStore->setProductKeyValue(1,'received',20);
        $this->assertEquals($this->productStore->getAllProducts()[1]['received'],20);
    }
}