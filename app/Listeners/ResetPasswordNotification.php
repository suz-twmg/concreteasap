<?php


namespace App\Listeners;


use Illuminate\Http\Request;

class ResetPasswordNotification
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function handle($event){
//        dispatch()
    }

}
