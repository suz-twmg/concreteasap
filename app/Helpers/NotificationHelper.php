<?php


namespace App\Helpers;


use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Notification;

class NotificationHelper
{

    public static function formatNotification(string $route,string $msg,array $params){
        return ["route"=>$route,"msg" =>$msg,"params"=>$params];
    }

    public static function sendNotification($user,$notification){
        Notification::send($user, new AppNotification($notification));
    }

}
