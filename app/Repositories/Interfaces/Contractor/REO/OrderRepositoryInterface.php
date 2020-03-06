<?php
namespace App\Repositories\Interfaces\Contractor\REO;

use App\Models\Order\Order;

interface OrderRepositoryInterface
{
    function createReo($order_request);
}
