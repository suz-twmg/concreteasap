<?php
namespace App\Repositories\Interfaces;

use App\Models\Order\Order;

interface OrderRepositoryInterface{
    //Contractor Methods
	public function createConcrete($order_request);
    public function getUserConcreteOrder();
    public function getOrder($order_id);
    public function getPendingOrders();
    public function getAcceptedOrders();
    public function completeOrder($order_id,$quantity,$total,$message_quantity,$message_total,$review);
    public function setMessagePrice(int $message_id,float $price);
    public function cancelOrder($order_id);
    public function confirmOrderDelivery($order_id);
    public function getDayOfPourOrders();
    //Rep Methods
    public function getRepAllOrders();
    public function getRepOrders();
    public function getRepAcceptedOrders();
    public function getRepPendingOrders();

    public function markAsPaid(Order $order_id);
}
