<?php
declare(strict_types=1);
namespace Inventory;

use Inventory\Interfaces\OutputSummaryInterface;
use MathieuViossat\Util\ArrayToTextTable;

class OutputAsciiTableSummary implements OutputSummaryInterface
{

    public function __construct()
    {

    }
    
    /**
     * @param array $products
     * @return void
     */
    public function echoSummary(array $products): void
    {
        $renderer = new ArrayToTextTable($products);
        $renderer->setDecorator(new \Zend\Text\Table\Decorator\Ascii());
        echo $renderer->getTable();
        echo "\n";
    }
}