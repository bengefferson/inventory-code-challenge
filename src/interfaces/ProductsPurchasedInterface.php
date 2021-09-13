<?php
namespace Inventory\Interfaces;

//Interface that handles the managent of product purchase
interface ProductsPurchasedInterface
{
    /**
     * @param int $productId
     * @return int
     */
    public function getPurchasedReceivedTotal(int $productId): int;

    /**
     * @param int $productId
     * @return int
     */
    public function getPurchasedPendingTotal(int $productId): int;

    /**
     * @param int $productId
     * @return int
     */
    public function getPurchasedPendingWait(int $productId): int;

    /**
     * @param int $productId
     * @return void
     */
    public function updatePurchasedReceivedTotal(int $productId): void;

    /**
     * @param int $productId
     * @return void
     */
    public function updatePurchasedPendingTotal(int $productId): void;

    /**
     * @param int $productId
     * @return void
     */
    public function updatePurchasedPendingWait(int $productId): void;
}
