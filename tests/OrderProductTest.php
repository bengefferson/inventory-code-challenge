<?php
declare(strict_types=1);

namespace Test;

use Inventory\OrderProduct;
use Inventory\ProductsStore;
use Test\TestCase;
use Exception;

class OrderProductTest extends TestCase
{

    public function setUp() :void
    {
        parent::setUp();
        $this->productStore = $this->mock(ProductsStore::class);
        $this->orderProduct = $this->mock(OrderProduct::class, [$this->productStore]);
    }

    public function testCanGetTotalSoldItemsOfAParticularProductFromStore()
    {
        $this->assertEquals($this->orderProduct->getSoldTotal(1),0);
    }

    public function testCanGetStockLevelOfAParticularProductFromStore()
    {
        $this->assertEquals($this->orderProduct->getStockLevel(1),20);
    }

    public function testCanGetTotalReceivedPurchasesOfAParticularProductFromStore()
    {
        $this->assertEquals($this->orderProduct->getPurchasedReceivedTotal(1),0);
    }

    public function testCanGetTotalPendingPurchasesOfAParticularProductFromStore()
    {
        $this->assertEquals($this->orderProduct->getPurchasedPendingTotal(1),0);
    }

    public function testCanGetTotalPendingPurchasesWaitTimeOfAParticularProductFromStore()
    {
        $this->assertEquals($this->orderProduct->getPurchasedPendingWait(1),0);
    }

    public function testCanUpdateStockLevelOfAParticularProductFromStore()
    {
        $this->orderProduct->updateStockLevel(1,20);
        $this->assertEquals($this->orderProduct->getStockLevel(1),40);

        $this->orderProduct->updateStockLevel(1,-30);
        $this->assertEquals($this->orderProduct->getStockLevel(1),10);
    }

    public function testCanUpdateTotalSoldItemsOfAParticularProductFromStore()
    {
        $this->orderProduct->updateSoldTotal(1,5);
        $this->assertEquals($this->orderProduct->getSoldTotal(1),5);

        $this->orderProduct->updateSoldTotal(1,3);
        $this->assertEquals($this->orderProduct->getSoldTotal(1),8);
    }

    public function testCanUpdateAndResetTotalPendingPurchasesWaitTimeOfAParticularProductFromStore()
    {
        $this->productStore->setProductKeyValue(1, 'pending', 20);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 1);
        $this->orderProduct->updatePurchasedPendingWait(1);
        $this->assertEquals($this->orderProduct->getPurchasedPendingWait(1),2);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 2);
        $this->orderProduct->updatePurchasedPendingWait(1);
        $this->assertEquals($this->orderProduct->getPurchasedPendingWait(1),0);
    }

    public function testCanUpdateTotalReceivedPurchasesOfAParticularProductFromStore()
    {
        $this->assertEquals($this->orderProduct->getPurchasedReceivedTotal(1),0);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 2);
        $this->orderProduct->updatePurchasedReceivedTotal(1);
        $this->assertEquals($this->orderProduct->getPurchasedReceivedTotal(1),20);
    }

    public function testCanUpdateAndResetTotalPendingPurchasesOfAParticularProductFromStore()
    {
        $this->assertEquals($this->orderProduct->getPurchasedPendingTotal(1),0);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 0);
        $this->orderProduct->updatePurchasedPendingTotal(1);
        $this->assertEquals($this->orderProduct->getPurchasedPendingTotal(1),20);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 2);
        $this->orderProduct->updatePurchasedPendingWait(1);
        $this->assertEquals($this->orderProduct->getPurchasedPendingTotal(1),0);

    }

    
}