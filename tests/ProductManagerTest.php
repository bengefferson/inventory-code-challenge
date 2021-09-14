<?php
declare(strict_types=1);

namespace Test;

use Inventory\ProductManager;
use Inventory\ProductsStore;
use Test\TestCase;
use Exception;

class ProductManagerTest extends TestCase
{

    public function setUp() :void
    {
        parent::setUp();
        $this->productStore = $this->mock(ProductsStore::class);
        $this->productManager = $this->mock(ProductManager::class, [$this->productStore]);
    }

    public function testCanGetTotalSoldItemsOfAParticularProductFromStore()
    {
        $this->assertEquals($this->productManager->getSoldTotal(1),0);
    }

    public function testCanGetStockLevelOfAParticularProductFromStore()
    {
        $this->assertEquals($this->productManager->getStockLevel(1),20);
    }

    public function testCanGetTotalReceivedPurchasesOfAParticularProductFromStore()
    {
        $this->assertEquals($this->productManager->getPurchasedReceivedTotal(1),0);
    }

    public function testCanGetTotalPendingPurchasesOfAParticularProductFromStore()
    {
        $this->assertEquals($this->productManager->getPurchasedPendingTotal(1),0);
    }

    public function testCanGetTotalPendingPurchasesWaitTimeOfAParticularProductFromStore()
    {
        $this->assertEquals($this->productManager->getPurchasedPendingWait(1),0);
    }

    public function testCanUpdateStockLevelOfAParticularProductFromStore()
    {
        $this->productManager->updateStockLevel(1,20);
        $this->assertEquals($this->productManager->getStockLevel(1),40);

        $this->productManager->updateStockLevel(1,-30);
        $this->assertEquals($this->productManager->getStockLevel(1),10);
    }

    public function testCanUpdateTotalSoldItemsOfAParticularProductFromStore()
    {
        $this->productManager->updateSoldTotal(1,5);
        $this->assertEquals($this->productManager->getSoldTotal(1),5);

        $this->productManager->updateSoldTotal(1,3);
        $this->assertEquals($this->productManager->getSoldTotal(1),8);
    }

    public function testCanUpdateAndResetTotalPendingPurchasesWaitTimeOfAParticularProductFromStore()
    {
        $this->productStore->setProductKeyValue(1, 'pending', 20);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 1);
        $this->productManager->updatePurchasedPendingWait(1);
        $this->assertEquals($this->productManager->getPurchasedPendingWait(1),2);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 2);
        $this->productManager->updatePurchasedPendingWait(1);
        $this->assertEquals($this->productManager->getPurchasedPendingWait(1),0);
    }

    public function testCanUpdateTotalReceivedPurchasesOfAParticularProductFromStore()
    {
        $this->assertEquals($this->productManager->getPurchasedReceivedTotal(1),0);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 2);
        $this->productManager->updatePurchasedReceivedTotal(1);
        $this->assertEquals($this->productManager->getPurchasedReceivedTotal(1),20);
    }

    public function testCanUpdateAndResetTotalPendingPurchasesOfAParticularProductFromStore()
    {
        $this->assertEquals($this->productManager->getPurchasedPendingTotal(1),0);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 0);
        $this->productManager->updatePurchasedPendingTotal(1);
        $this->assertEquals($this->productManager->getPurchasedPendingTotal(1),20);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 2);
        $this->productManager->updatePurchasedPendingWait(1);
        $this->assertEquals($this->productManager->getPurchasedPendingTotal(1),0);

    }

    
}