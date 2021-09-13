<?php
declare(strict_types=1);

namespace Test;

use Inventory\OrderProduct;
use Inventory\ProductsStore;
use Inventory\OutputAsciiTableSummary;
use Inventory\OrderProcessor;
use Inventory\OrderProductsWeekly;
use Test\TestCase;
use Exception;

class OrderProcessorTest extends TestCase
{

    public function setUp() :void
    {
        parent::setUp();
        $this->summary = $this->mock(OutputAsciiTableSummary::class);
        $this->productStore = $this->mock(ProductsStore::class);
        $this->orderProduct = $this->mock(OrderProduct::class, [$this->productStore]);
        $this->orderProducts = $this->mock(OrderProductsWeekly::class, [$this->orderProduct, $this->productStore]);
        $this->process = $this->mock(OrderProcessor::class, [$this->orderProducts, $this->summary]);
    }

    public function testExceptionIsThrownWhenInvalidFilePathGiven()
    {
        $this->expectException(Exception::class);
        $this->process->processFromJson('invalid.json');
    }
}
