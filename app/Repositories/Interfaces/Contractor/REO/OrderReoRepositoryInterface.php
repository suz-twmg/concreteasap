<?php
namespace App\Repositories\Interfaces\Contractor\REO;

use App\Models\Order\Order;

interface OrderReoRepositoryInterface
{
    function createReo($order_request);
}
