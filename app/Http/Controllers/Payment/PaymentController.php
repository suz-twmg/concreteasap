<?php

namespace App\Http\Controllers\Payment;

use App\Notifications\AppNotification;
use App\Repositories\BidRepository;
use App\Repositories\UserRepository;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Braintree\ClientToken;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Stripe\Charge;


class PaymentController extends Controller
{
    private $bid_repo;
    private $user_repo;
    private $user;

    public function __construct(BidRepository $bid_repo,UserRepository $user_repo){
        $this->user_repo=$user_repo;
        $this->bid_repo=$bid_repo;
        $this->user=auth('api')->user();
    }

    //
    public function getPaymentToken(){
        return response()->json(['payment_token' => ClientToken::generate()]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function payBidAmount(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'price'=>'required',
                'order_id'=>'required',
                'save_details'=>'required'
            ]);

            if($validator->fails()){
                $errors = $validator->errors();
                return response()->json(['message'=>$errors],400);
            }

            $is_save_details=$request->get("save_details");

            //amount must be set in cents
            $charge_details=[
                'amount' => 1500,
                'currency' => 'aud'
            ];
            $customer="";

            if($is_save_details){
                $customer = \Stripe\Customer::create([
                    'source' => $request["token"]["tokenId"],
                    'email' => $this->user->email
                ]);
                $charge_details["customer"]=$customer->id;
            }
            else{
                $charge_details['source']=$request["token"]["tokenId"];
            }

            $charge = Charge::create($charge_details);

            if($charge){
                $transaction=[
                    "id"=>"",
                    "invoice_url"=>"",
                    "approved"=>true
                ];
                $date_delivery=isset($request["date_delivery"])?$request["date_delivery"]:"";
                $time_delivery=isset($request["time_delivery"])?$request["time_delivery"]:"";
                $result=$this->bid_repo->save($request["price"],$request["order_id"],$this->user->id,$transaction,$date_delivery,$time_delivery);
                if(isset($result["job_id"])){
                    $job_id=$result["job_id"];
                    if($is_save_details){
                        $this->user_repo->savePaymentDetail($customer->id,$this->user->id);
                    }

                    $order_user=$this->user_repo->getOrderUser($request["order_id"]);
                    $params=array(
//                        "bid"=>$this->bid_repo->getOrderBids($request["order_id"],$order_user["id"])
                        "order_id"=>$request["order_id"]
                    );
                    $notification = [
                        "msg" => "You have received new bid in job {$job_id}.",
                        "route"=>"ViewBids",
                        "btn"=>["id"=>"VIEW_BIDS","text"=>"View Bid"],
                        "params"=>$params
                    ];
                    Notification::send($order_user, new AppNotification($notification));

                    return response()->json(['message'=>"Bid Successfully","payment_complete"=>true,"payment_info"=>$charge],200);
                }
            }
        }
        catch(\Exception $e){
            return response()->json(['message'=>$e->getMessage()],401);
        }
    }
}
