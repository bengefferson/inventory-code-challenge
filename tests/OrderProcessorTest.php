<?php
declare(strict_types=1);

namespace Test;

use Inventory\ProductManager;
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
        $this->productManager = $this->mock(ProductManager::class, [$this->productStore]);
        $this->orderProducts = $this->mock(OrderProductsWeekly::class, [$this->productManager, $this->productStore]);
        $this->process = $this->mock(OrderProcessor::class, [$this->orderProducts, $this->summary]);
    }

    public function testExceptionIsPrintedToStdoutWhenInvalidFilePathGiven()
    {
        $expected ="Invalid input file, please enter valid file\n";
        $this->expectOutputString($expected);
        $this->process->processFromJson('invalid.json');
    }

    public function testExceptionIsPrintedToStdoutWhenInvalidFileExtensionGiven()
    {
        $expected ="Invalid input file, please enter valid file\n";
        $this->expectOutputString($expected);
        $this->process->processFromJson('invalid.txt');
    }

}
