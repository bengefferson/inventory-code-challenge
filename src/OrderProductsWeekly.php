<?php
declare(strict_types=1);
namespace Inventory;

use Inventory\Interfaces\ProductManagementInterface;
use Inventory\Interfaces\OrderProductsInterface;
use Inventory\Interfaces\ProductsStoreInterface;
use Exception;

//Class that handles the order process for all orders in a week and maintains a summary for all products
class OrderProductsWeekly implements OrderProductsInterface
{
    protected $productManager;
    protected $productStore;

    public function __construct(ProductManagementInterface $productManager, ProductsStoreInterface $productStore)
    {
        $this->productManager = $productManager;
        $this->productStore = $productStore;
    }

    /**
     * @param array $orders
     * @return void
     */
    public function processOrders(array $orders): void
    {
        if(gettype($orders) !='array'){
            throw new Exception('Orders must be an array of daily orders');
        }
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
            if(gettype($dayOrders) !='array'){
                throw new Exception('Week orders must contain array day order list');
            }
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
            if(gettype($orderList) !='array'){
                throw new Exception('Day orders must contain array order list');
            }
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
                $this->productManager->updateSoldTotal($prodctId, $itemUnits);
            }
        }else{
            $list = json_encode($orderList);
            print_r("Warning: Order $list skipped because there wasn't enough stock for one or more products or the product does not exist");
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
            $this->productManager->updatePurchasedReceivedTotal($productId);
            if($this->productManager->getPurchasedPendingWait($productId) != 0 && $this->productManager->getPurchasedPendingWait($productId) < Config::MAX_WAIT_TIME){
                $this->productManager->updatePurchasedPendingWait($productId);
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
            if(gettype($itemUnits) =='integer' && gettype($prodctId) =='integer'){
                if(in_array($prodctId,array_keys($this->productStore->getAllProducts())) && $this->productManager->getStockLevel($prodctId) >= $itemUnits){
                    $validated = True;
                    continue;
                }else{
                    $validated = False;
                    break;
                }
            }else{
                throw new Exception('Item units and product Ids need to be integers');
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