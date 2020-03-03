<?php

namespace App\Http\Controllers\Rep;

use App\Models\Bids\Bids;
use App\Notifications\AppNotification;
use App\Repositories\Interfaces\BidRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class BidController extends Controller
{
    private $bid_repo;
    private $order_repo;
    private $user;
    //
    public function __construct(BidRepositoryInterface $bid_repo,OrderRepositoryInterface $order_repo){
        $this->bid_repo=$bid_repo;
        $this->order_repo=$order_repo;
        $this->user=auth('api')->user();
    }

//    /**
//     * Store a newly created bid in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function saveBid(Request $request){
//        $validator = Validator::make($request->all(), [
//            'price' => 'required',
//            'order_id'=>'required',
//        ]);
//
//        if(!$validator->fails()){
//            try{
//                $date_delivery=!$request->get("date_delivery")?$request->get("date_delivery"):"";
//                $time_delivery=!$request->get("time_delivery")?$request->get("time_delivery"):"";
//                if($this->bid_repo->save($request["price"],$request["order_id"],$this->user->id,$date_delivery,$time_delivery)){
//                    return response()->json(array("message"=>"Successfully Bid"),200);
//                }
//            }
//            catch(\Exception $e){
//                return response()->json(["message"=>$e->getMessage()],401);
//            }
//        }
//        else{
//            return response()->json(["message"=>$validator->errors()],401);
//        }
//
//    }

    public function getUserBid(){
        try{
            return $this->bid_repo->getUserBids($this->user->id);
        }
        catch(\Exception $e){
            $this->handle_exception($e->getMessage());
        }
    }

    public function getBidOrders(){
        return $this->order_repo->getRepAllOrders();
    }

    public function previousOrder(){
        try{
            return $this->bid_repo->getRepPreviousBids();
        }
        catch(\Exception $e){
            $this->handle_exception($e->getMessage());
        }
    }

    public function acceptedOrder(){
        try{
            return $this->bid_repo->getRepAcceptedBids();
        }
        catch(\Exception $e){
            $this->handle_exception($e->getMessage());
        }
    }

    /**
     * Store a newly created bid in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePaymentMethod(Request $request){
        $validator = Validator::make($request->all(), [
            'payment_method'=>'required',
            'bid_id'=>'required'
        ]);
        try{
            if(!$validator->fails()){
                $bid=Bids::findOrFail($request->get("bid_id"));
                $result=$this->bid_repo->updatePaymentMethod($bid,$request->get("payment_method"));
                $order=$result["order"];
                $notification=[
                    "msg"=>"Job {$order["job_id"]} has been confirmed as paid.",
                    "route" => "DayOfPour",
                    "params" => array(
                        "order_id"=>$order->id,
                        "order_type"=>$result["order_type"]
                    )
                ];
                if($order){
                    Notification::send($order->user()->get(),new AppNotification($notification));
                    return response()->json(array("msg"=>"Job has been confirmed as paid."),200);
                }
                else{
                    return response()->json(array("msg"=>"There is some issue in the server."),400);
                }
            }

        }
        catch (\Exception $e){
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
            $bid=Bids::findOrFail($request->get("bid_id"));
            $result=$this->bid_repo->releaseOrder($bid);

            if(isset($result["order"])){
                $order=$result["order"];
                //$user=$order->user();
                $notification = [
                    "msg" => "Job {$result["job_id"]} has been released.",
                    "route" => "DayOfPour",
                    "params" => array(
                        "order_id" => $order["id"],
                        "order_type"=>$result["order_type"]
                    )
                ];
                Notification::send(User::find($order->user_id), new AppNotification($notification));
                return response()->json(array("msg" =>"Job {$result["job_id"]} has been released."), 200);
            }
            else{
                return response()->json(array("Order has been already Complete or Cancelled"),200);
            }
        } catch (\Exception $e) {
            return $this->handle_exception($e->getMessage());
        }
    }


    private function handle_exception($message){
        return response()->json(["message"=>"$message"],400);
    }
}
