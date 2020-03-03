<?php

namespace App\Repositories\Interfaces;

use App\Models\Bids\Bids;

interface BidRepositoryInterface{
    public function save($price,int $order_id,int $user_id,$transaction,$date_delivery,$time_delivery);
    public function getUserBids(int $user_id);
    public function acceptBid(int $bid_id,string $payment_method);
    public function messageOrder(int $order_id,float $quantity);
    public function rejectBid(int $bid_id);
    public function getOrderBids(int $order_id,int $user_id);
    public function updatePaymentMethod(Bids $bid,string $payment_method);
    public function getRepBidOrders();
    public function getRepAcceptedBids();
    public function getRepPreviousBids();

}
