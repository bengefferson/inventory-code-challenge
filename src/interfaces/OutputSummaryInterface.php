<?php
namespace Inventory\Interfaces;

//Interface that handles output summary to stdout
interface OutputSummaryInterface
{

    /**
     * @param array $products
     * @return void
     */
    public function echoSummary(array $products): void;

}