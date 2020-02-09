<?php

namespace App\Http\Controllers\Rep;

use App\Models\Order\Order;
use App\Notifications\AppNotification;
use App\Repositories\BidRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\User;

class OrderController extends Controller
{
    private $order_repo;
    private $bid_repo;
    private $user;

    public function __construct(OrderRepositoryInterface $order_repo,BidRepository $bid_repo){
        $this->order_repo=$order_repo;
        $this->bid_repo=$bid_repo;
//        $this->user=auth('api')->user();
    }
    //
    public function getRepAllOrders(Request $request){
        try{
            return $this->order_repo->getRepAllOrders();
        }
        catch(\Exception $e){
            return $this->handle_exception($e->getMessage());
        }
    }

    public function getPendingOrders(){
        try{
            return $this->order_repo->getRepPendingOrders();
        }
        catch(\Exception $e){
            return $this->handle_exception($e->getMessage());
        }
    }

    public function getAcceptedOrders(){
        try{
            return $this->bid_repo->getRepAcceptedBids();
        }
        catch(\Exception $e){
            return $this->handle_exception($e->getMessage());
        }
    }

    public function getDebugAcceptedOrders(){
        try{
            return $this->order_repo->getRepAcceptedOrders();
        }
        catch(\Exception $e){
            return $this->handle_exception($e->getMessage());
        }
    }

    /**
     * Store a newly created bid in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelOrder(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'order_id'=>'required'
            ]);
            if($validator->validate()){
                $user=$this->order_repo->cancelOrder($request->get("order_id"));
                if($user){
                    $notification = [
                        "msg" => "Order has been cancelled.",
                    ];
                    Notification::send($user, new AppNotification($notification));
                }
                return response()->json(array("msg" =>"Order has been cancelled"), 200);
            }

        }
        catch(\Exception $e){
            return $this->handle_exception($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function releaseOrder(Request $request)
    {
        try {
            $order=$this->order_repo->releaseOrder($request->get("bid_id"));

            if($order){
                //$user=$order->user();
                $notification = [
                    "msg" => "Order has been released.",
                    "route" => "Day of Pour",
                    "params" => array(
                        "order_id" => $order["id"]
                    )
                ];
                Notification::send(User::find($order->user_id), new AppNotification($notification));
                return response()->json(array("msg" =>"Order Release has been sent"), 200);
            }
        } catch (\Exception $e) {
            return $this->handle_exception($e->getMessage());
        }
    }

    public function setMessagePrice(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'message_id'=>'required',
                'price'=>'required'
            ]);
            if($validator->validate()){
                $message_id=$request->get("message_id");
                $price=$request->get("price");
                $message=$this->order_repo->setMessagePrice($message_id,$price);
                $order=Order::find($message->order_id);
                $user=$order->user()->get();
                $notification = [
                    "msg" => "Order Message has been updated.",
                    "route" => "OrderStatus",
                    "params" => array(
                        "order_id" => $order["id"]
                    )
                ];
                Notification::send($user, new AppNotification($notification));

                return response()->json(array("msg" =>"Order Message has been responded","orders"=>$order->message()), 200);
            }
        } catch(\Exception $e){
            return $this->handle_exception($e->getMessage());
        }
    }

    private function handle_exception($message){
        return response()->json(["msg"=>$message],400);
    }
}
