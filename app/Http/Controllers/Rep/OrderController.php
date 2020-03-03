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
                $response=$this->order_repo->cancelOrder($request->get("order_id"));
                if($response){
                    $notification = [
                        "route"=>"Previous Order List",
                        "msg" => "Order has been cancelled.",
                        "params"=>array(
                            "order_id"=>$request->get("order_id")
                        )
                    ];
                    Notification::send($response["user"], new AppNotification($notification));
                }
                return response()->json(array("msg" =>"Order has been cancelled"), 200);
            }

        }
        catch(\Exception $e){
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
                $result=$this->order_repo->setMessagePrice($message_id,$price);
                $message=$result["message"];
                $order=Order::find($message->order_id);
                $user=$order->user()->first();
                $job_id=isset($result["job_id"])?$result["job_id"]:"";
                $notification = [
                    "msg" => "Job {$job_id}'s message has been updated.",
                    "route" => "Order Message",
                    "params" => array(
                        "order_id" => $order["id"],
                        "order_type"=>$result["order_type"]
                    )
                ];
                Notification::send($user, new AppNotification($notification));

                return response()->json(array("msg" =>"Job {$job_id} Message has been responded","orders"=>$order->message()), 200);
            }
        } catch(\Exception $e){
            return $this->handle_exception($e->getMessage());
        }
    }

    private function handle_exception($message){
        return response()->json(["msg"=>$message],400);
    }
}
