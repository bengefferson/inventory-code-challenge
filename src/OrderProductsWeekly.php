<?php
declare(strict_types=1);
namespace Inventory;

use Inventory\Interfaces\OrderProductInterface;
use Inventory\Interfaces\OrderProductsInterface;
use Inventory\Interfaces\ProductsStoreInterface;
use Exception;

//Class that handles the order process for all orders in a week and maintains a summary for all products
class OrderProductsWeekly implements OrderProductsInterface
{
    protected $product;
    protected $productStore;

    public function __construct(OrderProductInterface $orderProduct, ProductsStoreInterface $productStore)
    {
        $this->orderProduct = $orderProduct;
        $this->productStore = $productStore;
    }

    /**
     * @param array $orders
     * @return void
     */
    public function processOrders(array $orders): void
    {
        $this->processWeekOrders($orders);
    }

    /**
     * @param array $weekOrders
     * @return void
     */
    public function processWeekOrders(array $weekOrders): void
    {
        if (sizeof($weekOrders) != 7){
            throw new Exception('Invalid number of days for the week');
        }
        foreach($weekOrders as $dayOrders){
            $this->processDayOrders($dayOrders);
        }
    }

    /**
     * @param array $dayOrders
     * @return void
     */
    public function processDayOrders(array $dayOrders): void
    {
        $this->processDailyProductPurchase();
        foreach($dayOrders as $orderList){
            $this->processOrderList($orderList);
        }
    }

    /**
     * @param array $orderList
     * @return void
     */
    public function processOrderList(array $orderList): void
    {
        if ($this->validateOrderList($orderList)){
            foreach($orderList as $prodctId => $itemUnits){
                $this->orderProduct->updateSoldTotal($prodctId, $itemUnits);
            }
        }else{
            $list = json_encode($orderList);
            print_r("Warning: Order $list skipped because there wasn't enough stock for one or more products or the order was invalid");
            echo "\n";
        }
    }

    /**
     * Receives stock for any pending purchase orders made 2 days prior
     * @return void
     */
    public function processDailyProductPurchase(): void
    {
        foreach(array_keys($this->productStore->getAllProducts()) as $productId){
            $this->orderProduct->updatePurchasedReceivedTotal($productId);
            if($this->orderProduct->getPurchasedPendingWait($productId) != 0 && $this->orderProduct->getPurchasedPendingWait($productId) < Config::MAX_WAIT_TIME){
                $this->orderProduct->updatePurchasedPendingWait($productId);
            }
        }
    }

    /**
     * Validates single order list
     * @param array $orderList
     * @return bool
     */
    public function validateOrderList(array $orderList): bool
    {
        $validated = null;
        foreach($orderList as $prodctId => $itemUnits){
            if(in_array($prodctId,array_keys($this->productStore->getAllProducts())) && $this->orderProduct->getStockLevel($prodctId) >= $itemUnits){
                $validated = True;
                continue;
            }else{
                $validated = False;
                break;
            }
        }
        return $validated;
    }

    /**
     * @return array
     */
    public function orderSummary(): array
    {
        $summary = [];
        foreach ($this->productStore->getAllProducts() as $productId => $productArray){
            $summary[$productId] = array_slice($productArray, 0, sizeOf($productArray)-1);
        }
        return $summary;
    }
}