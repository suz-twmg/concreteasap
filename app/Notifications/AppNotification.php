<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalButton;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class AppNotification extends Notification
{
    use Queueable;

    private $notification_data;

    /**
     * Create a new notification instance.
     *
     * @param $notification_data
     */
    public function __construct($notification_data)
    {
        //
        $this->notification_data = $notification_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [OneSignalChannel::class,'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \NotificationChannels\OneSignal\OneSignalMessage
     */
    public function toOneSignal($notifiable)
    {
        $one_signal=OneSignalMessage::create();
        if(isset($this->notification_data["btn"])){
            $one_signal=$one_signal
                ->setButton(
                    OneSignalButton::create($this->notification_data["btn"]["id"])
                        ->text($this->notification_data["btn"]["text"])
                );
        }
        if(isset($this->notification_data["route"])){
            $one_signal=$one_signal->setData("route",$this->notification_data["route"]);
        }
        if(isset($this->notification_data["params"])){
            $one_signal=$one_signal->setData("params",$this->notification_data["params"]);
        }
        return $one_signal
            ->setBody($this->notification_data["msg"]);
    }

    public function toArray($notifiable)
    {
        return [
            'message'=>$this->notification_data["msg"],
            'route'=>isset($this->notification_data["route"])?$this->notification_data["route"]:"",
            'params'=>isset($this->notification_data["params"])?$this->notification_data["params"]:""
        ];
    }
}
