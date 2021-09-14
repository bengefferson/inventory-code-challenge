<?php
declare(strict_types=1);

namespace Test;

use Inventory\OrderProduct;
use Inventory\ProductsStore;
use Inventory\OrderProductsWeekly;
use Test\TestCase;
use Exception;

class OrderProductsTest extends TestCase
{

    public function setUp() :void
    {
        parent::setUp();
        $this->productStore = $this->mock(ProductsStore::class);
        $this->orderProduct = $this->mock(OrderProduct::class, [$this->productStore]);
        $this->orderProducts = $this->mock(OrderProductsWeekly::class, [$this->orderProduct, $this->productStore]);
    }

    public function testReturnsExpectedOrderSummaryFromStore()
    {
        $array = [
            1 =>[
                'product' => 'Brownie',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
            ],
            2 =>[
                'product' => 'Lamington',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
            ],
            3 =>[
                'product' => 'Blueberry Muffin',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
            ],
            4 =>[
                'product' => 'Croissant',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
            ],
            5 =>[
                'product' => 'Chocolate Cake',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,                
            ],
        ];
        $this->assertEquals($this->orderProducts->orderSummary(),$array);
    }

    public function testCanValidateOrderListBasedOnStockLevelInStore()
    {
        $array = [1=>11,2=>5,3=>5];
        $this->assertTrue($this->orderProducts->validateOrderList($array));
        $array = [1=>21,2=>5,3=>5];
        $this->assertFalse($this->orderProducts->validateOrderList($array));
    }

    public function testCanProcessDailyPurchaseAsExpected()
    {
        $this->productStore->setProductKeyValue(1, 'pending', 20);
        $this->productStore->setProductKeyValue(1, 'pending_wait', 2);
        $this->assertEquals($this->orderProduct->getPurchasedPendingWait(1),2);
        $this->assertEquals($this->orderProduct->getPurchasedReceivedTotal(1),0);
        $this->assertEquals($this->orderProduct->getPurchasedPendingTotal(1),20);
        $this->orderProducts->processDailyProductPurchase();
        $this->assertEquals($this->orderProduct->getPurchasedPendingWait(1),0);
        $this->assertEquals($this->orderProduct->getPurchasedReceivedTotal(1),20);
        $this->assertEquals($this->orderProduct->getPurchasedPendingTotal(1),0);
    }

    public function testCanProcessAValidOrderListAndUpdateStoreAsExpected()
    {
        $arrayOrder = [1=>11,2=>5,3=>5,4=>8,5=>3];
        $this->orderProducts->processOrderList($arrayOrder);
        $arrayExpected = [
            1 =>[
                'product' => 'Brownie',
                'sold' => 11,
                'received' => 0,
                'pending' => 20,
                'stock' => 9,
                'pending_wait' => 1,
            ],
            2 =>[
                'product' => 'Lamington',
                'sold' => 5,
                'received' => 0,
                'pending' => 0,
                'stock' => 15,
                'pending_wait' => 0,
            ],
            3 =>[
                'product' => 'Blueberry Muffin',
                'sold' => 5,
                'received' => 0,
                'pending' => 0,
                'stock' => 15,
                'pending_wait' => 0,
            ],
            4 =>[
                'product' => 'Croissant',
                'sold' => 8,
                'received' => 0,
                'pending' => 0,
                'stock' => 12,
                'pending_wait' => 0,
            ],
            5 =>[
                'product' => 'Chocolate Cake',
                'sold' => 3,
                'received' => 0,
                'pending' => 0,
                'stock' => 17,
                'pending_wait' => 0,
                
            ],
        ];
        $this->assertEquals($this->productStore->getAllProducts(),$arrayExpected);
    }

    public function testCanOutputErrorMessageToStdoutWhenInvalidOrderListIsGiven()
    {
        $arrayOrder = [1=>21,'b'=>5,3=>5,4=>8,5=>3];
        $list = json_encode($arrayOrder);
        $expected ="Warning: Order $list skipped because there wasn't enough stock for one or more products or the order was invalid\n";
        $this->expectOutputString($expected);
        $this->orderProducts->processOrderList($arrayOrder);
    }

    public function testCanIgnoreAnInvalidOrderListAndDoesNotUpdateStore()
    {
        $arrayOrder = [1=>21,2=>5,3=>5,4=>8,5=>3];
        $this->orderProducts->processOrderList($arrayOrder);
        $arrayExpected = [
            1 =>[
                'product' => 'Brownie',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
            ],
            2 =>[
                'product' => 'Lamington',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
            ],
            3 =>[
                'product' => 'Blueberry Muffin',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
            ],
            4 =>[
                'product' => 'Croissant',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
            ],
            5 =>[
                'product' => 'Chocolate Cake',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
                
            ],
        ];
        $this->assertEquals($this->productStore->getAllProducts(),$arrayExpected);
    }

    public function testCanProcessDailyOrdersAndUpdateStoreAsExpected()
    {
        $dayOrders = [[1=>11,2=>5,3=>5,4=>8,5=>3],[1=>21,2=>5,3=>5,4=>8,5=>3]];
        $this->orderProducts->processDayOrders($dayOrders);
        $arrayExpected = [
            1 =>[
                'product' => 'Brownie',
                'sold' => 11,
                'received' => 0,
                'pending' => 20,
                'stock' => 9,
                'pending_wait' => 1,
            ],
            2 =>[
                'product' => 'Lamington',
                'sold' => 5,
                'received' => 0,
                'pending' => 0,
                'stock' => 15,
                'pending_wait' => 0,
            ],
            3 =>[
                'product' => 'Blueberry Muffin',
                'sold' => 5,
                'received' => 0,
                'pending' => 0,
                'stock' => 15,
                'pending_wait' => 0,
            ],
            4 =>[
                'product' => 'Croissant',
                'sold' => 8,
                'received' => 0,
                'pending' => 0,
                'stock' => 12,
                'pending_wait' => 0,
            ],
            5 =>[
                'product' => 'Chocolate Cake',
                'sold' => 3,
                'received' => 0,
                'pending' => 0,
                'stock' => 17,
                'pending_wait' => 0,
                
            ],
        ];
        $this->assertEquals($this->productStore->getAllProducts(),$arrayExpected);
    }

    public function testThrowsExceptionWhenWeeklyArrayIsNotEqualTo7()
    {
        $this->expectException(Exception::class);
        $weekOrders = [
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],

        ];
        $this->orderProducts->processWeekOrders($weekOrders);    
    }

    public function testCanProcessWeeklyOrdersAndUpdateStoreAsExpected()
    {
        $weekOrders = [
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
        ];
        $this->orderProducts->processWeekOrders($weekOrders);
        $arrayExpected = [
            1 =>[
                'product' => 'Brownie',
                'sold' => 7,
                'received' => 0,
                'pending' => 0,
                'stock' => 13,
                'pending_wait' => 0,
            ],
            2 =>[
                'product' => 'Lamington',
                'sold' => 14,
                'received' => 0,
                'pending' => 20,
                'stock' => 6,
                'pending_wait' => 2,
            ],
            3 =>[
                'product' => 'Blueberry Muffin',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
            ],
            4 =>[
                'product' => 'Croissant',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
            ],
            5 =>[
                'product' => 'Chocolate Cake',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
                
            ],
        ];
        $this->assertEquals($this->productStore->getAllProducts(),$arrayExpected);
    }

    

    public function testCanProcessOrdersAndUpdateStoreAsExpected()
    {
        $orders = [
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
            [[1=>1],[2=>2]],
        ];
        $this->orderProducts->processWeekOrders($orders);
        $arrayExpected = [
            1 =>[
                'product' => 'Brownie',
                'sold' => 7,
                'received' => 0,
                'pending' => 0,
                'stock' => 13,
                'pending_wait' => 0,
            ],
            2 =>[
                'product' => 'Lamington',
                'sold' => 14,
                'received' => 0,
                'pending' => 20,
                'stock' => 6,
                'pending_wait' => 2,
            ],
            3 =>[
                'product' => 'Blueberry Muffin',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
            ],
            4 =>[
                'product' => 'Croissant',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
            ],
            5 =>[
                'product' => 'Chocolate Cake',
                'sold' => 0,
                'received' => 0,
                'pending' => 0,
                'stock' => 20,
                'pending_wait' => 0,
                
            ],
        ];
        $this->assertEquals($this->productStore->getAllProducts(),$arrayExpected);
    }
}