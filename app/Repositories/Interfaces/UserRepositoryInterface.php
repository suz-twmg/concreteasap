<?php
namespace App\Repositories\Interfaces;

interface UserRepositoryInterface{
	public function save($user,$photo);
    public function saveDevice(string $device_id,int $user_id);
    public function removeDevice(int $user_id);
    public function savePaymentDetail(string $payment_token,int $user_id);
    public function getOrderUser(int $order_id);
	// public function getUser($username);
}
