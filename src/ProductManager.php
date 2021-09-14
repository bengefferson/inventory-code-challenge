<?php
declare(strict_types=1);
namespace Inventory;

use Inventory\Interfaces\ProductManagementInterface;
use Inventory\Interfaces\ProductsStoreInterface;
use Inventory\Config;

//Class that handles the managemnt of a products in the product store
class ProductManager implements ProductManagementInterface
{
    protected $productStore;

    public function __construct(ProductsStoreInterface $productStore)
    {
        $this->productStore = $productStore;
    }

    /**
     * Gets the total stock level of a particular product
     * @param int $productId
     * @return int
     */
    public function getStockLevel(int $productId): int
    {
        return $this->productStore->getProductKeyValue($productId, 'stock');
    }

    /**
     * Gets the total number of sold items for a particular product
     * @param int $productId
     * @return int
     */
    public function getSoldTotal(int $productId): int
    {
        return $this->productStore->getProductKeyValue($productId, 'sold');
    }

    /**
     * Gets the total items purchased that have been received for a particular product
     * @param int $productId
     * @return int
     */
    public function getPurchasedReceivedTotal(int $productId): int
    {
        return $this->productStore->getProductKeyValue($productId, 'received');
    }

    /**
     * Gets the total items purchased that are pending for a particular product
     * @param int $productId
     * @return int
     */
    public function getPurchasedPendingTotal(int $productId): int
    {
        return $this->productStore->getProductKeyValue($productId, 'pending');
    }

    /**
     * Gets the total wait time for items purchased that are pending for a particular product
     * @param int $productId
     * @return int
     */
    public function getPurchasedPendingWait(int $productId): int
    {
        return $this->productStore->getProductKeyValue($productId, 'pending_wait');
    }

    /**
     * Updates stock level for a particular product
     * @param int $productId
     * @return void
     */
    public function updateStockLevel(int $productId, int $itemUnits): void
    {
        $this->productStore->updateProductKeyValue($productId, 'stock', $itemUnits);
        if($this->getStockLevel($productId) < Config::MIN_STOCK_BEFORE_PURCHASE && $this->getPurchasedPendingWait($productId) == 0){
            $this->updatePurchasedPendingTotal($productId);     
        }
    }

    /**
     * Updates/resets the total wait time for items purchased that are pending for a particular product
     * @param int $productId
     * @return void
     */
    public function updatePurchasedPendingWait(int $productId): void
    {
        if ($this->getPurchasedPendingWait($productId) == Config::MAX_WAIT_TIME){
            $this->productStore->setProductKeyValue($productId, 'pending_wait', 0);
            $this->productStore->setProductKeyValue($productId, 'pending', 0);
        }else{
            $this->productStore->updateProductKeyValue($productId, 'pending_wait', 1);
        }
    }

    /**
     * Updates the total items purchased that have been received for a particular product
     * @param int $productId
     * @return void
     */
    public function updatePurchasedReceivedTotal(int $productId): void
    {
        if($this->getPurchasedPendingWait($productId) == Config::MAX_WAIT_TIME){
            $this->productStore->updateProductKeyValue($productId, 'received', Config::STOCK_REPLENISH_VAL);
            $this->updateStockLevel($productId,Config::STOCK_REPLENISH_VAL);
            $this->updatePurchasedPendingWait($productId);
        }
    }

    /**
     * Updates the total items purchased that are pending for a particular product
     * @param int $productId
     * @return void
     */
    public function updatePurchasedPendingTotal(int $productId): void
    {
        if($this->getPurchasedPendingWait($productId) == 0){
            $this->productStore->updateProductKeyValue($productId, 'pending', Config::STOCK_REPLENISH_VAL);
            $this->updatePurchasedPendingWait($productId);
        }
    }

    /**
     * Updates the total items sold for a particular product
     * @param int $productId
     * @return void
     */
    public function updateSoldTotal(int $productId, int $itemUnits): void
    {
        if($this->getStockLevel($productId) >= $itemUnits){
            $this->productStore->updateProductKeyValue($productId, 'sold', $itemUnits);
            $this->updateStockLevel($productId,-$itemUnits);
        }
    }
}