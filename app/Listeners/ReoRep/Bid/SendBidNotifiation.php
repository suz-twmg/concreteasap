<?php

namespace App\Listeners\ReoRep\Bid;

use App\Helpers\NotificationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBidNotifiation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        $order=$event->order;
        NotificationHelper::sendNotification($order,"You have received a new bid");
    }
}
