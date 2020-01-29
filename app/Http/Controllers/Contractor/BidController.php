<?php

namespace App\Http\Controllers\Contractor;

use App\Notifications\AppNotification;
use App\Repositories\Interfaces\BidRepositoryInterface;
use App\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class BidController extends Controller
{
    private $bid_repo;
    private $user;
    public function __construct(BidRepositoryInterface $bid_repo){
        $this->bid_repo=$bid_repo;
        $this->user=auth('api')->user();
    }

    /**
     * Store a newly created bid in storage.
     * @param $order_id
     * @return JsonResponse
     */
    public function getOrderBid($order_id){
       try{
            return response()->json($this->bid_repo->getOrderBids($order_id,$this->user->id),200);
        }
        catch(\Exception $e){
            return $this->handleError($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function acceptOrderBid(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "bid_id"=>"required",
            ]);
            if(!$validator->fails()){
                $bid_id=$request->get("bid_id");
                $payment_method=$request->get("payment_method");
                $bid_data=$this->bid_repo->acceptBid($bid_id,$payment_method);
                if($bid_data){
                    $notification=[
                        "msg"=>"Bid has been accepted",
                        "route"=>"Accepted Bid Detail",
                        "btn"=>["id"=>"VIEW_ORDER","text"=>"View Order"],
                        "params"=>array(
                            "bid"=>$bid_data["bid"]->getAcceptedOrder()
                        )
                    ];
                    Notification::send(User::find($bid_data["accepted_users"]),new AppNotification($notification));
                    $rejected_users=User::whereIn("id",$bid_data["rejected_users"])->get();
                    $notification=[
                        "msg"=>"Bid has been rejected",
                        "route" => "Previous Bid List",
                        "btn"=>["id"=>"VIEW_ORDER","text"=>"View Order"],
                        "params"=>array(
                            "bid"=>$bid_data["bid"]->getRejectedOrder()
                        )
                    ];
                    if($rejected_users->isNotEmpty()){
                        Notification::send($rejected_users,new AppNotification($notification));
                    }
                    return response()->json(array("message"=>"You have accepted the bid."),200);
                }
            }
            else{
                throw new \Exception("Bid id is missing from request",401);
            }
        }
        catch(\Exception $e){
            return $this->handleError($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function rejectOrderBid(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "bid_id"=>"required"
            ]);
            if(!$validator->fails()) {
                $bid_id = $request->get("bid_id");
                $bid_user = $this->bid_repo->rejectBid($bid_id);
                if ($bid_user) {
                    $notification = [
                        "msg" => "Your bid has been rejected",
                        "route" => "Order Pending Details",
                        "params" => array(
                            "order_id"=>$request->get("bid_id")
                        )
                    ];
                    Notification::send($bid_user, new AppNotification($notification));
                    return response()->json(array("message" => "You have rejected the bid."), 200);
                }
            }
            else{
                throw new \Exception("Bid id is missing from request",400);
            }
        }
        catch(\Exception $e){
            return $this->handleError($e);
        }
    }

    public function sendNotification($msg,$user_id){
        OneSignalFacade::sendNotificationToUser(
            "You have received new bid",
            $user_id
        );
    }

    public function handleError(\Exception $e){
        return response()->json(["message"=>$e->getMessage()],400);
    }
}
