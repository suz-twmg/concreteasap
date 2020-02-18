<?php

namespace App\Repositories;

use App\Model\Bids\Bid_Transactions;
use App\Models\Bids\Bids;
use App\Models\Order\Order;
use App\User;
use Illuminate\Support\Facades\Hash;

// use Hashids\Hashids;

class BidRepository implements Interfaces\BidRepositoryInterface
{

    private $bids;
    private $user;

    public function __construct(Bids $bids)
    {
        $this->bids = $bids;
        $this->user = auth('api')->user();
    }

    public function save($price, $order_id, $user_id, $transaction, $date_delivery, $time_delivery)
    {
        $price = (float)$price;
        $bid = new Bids();
        $bid->rep_price = $price;
        $bid->payment_type = "none";
        $bid->released = false;
        $bid->price = $price + ($price * 10 / 100);
        $bid->order_id = $order_id;
        $bid->user_id = $user_id;
        $bid->status = "Pending";

        $order=Order::find($order_id);

        $order_concrete=$order->orderConcrete;

        if(!is_null($order_concrete)){
            if($date_delivery==="time1"){
                $date_delivery=$order_concrete->delivery_date;
                $time_delivery=$order_concrete->time_preference1;
            }
            else if($date_delivery==="time2"){
                $date_delivery=$order_concrete->delivery_date1;
                $time_delivery=$order_concrete->time_preference2;
            }
            else if($date_delivery==="time3"){
                $date_delivery=$order_concrete->delivery_date2;
                $time_delivery=$order_concrete->time_preference3;
            }
        }

        $bid->date_delivery = $date_delivery;
        $bid->time_delivery = $time_delivery;

        $bid->save();
        $bid_transaction = new Bid_Transactions();
        $bid_transaction->bid_id = $bid->id;
        $bid_transaction->transaction_id = $transaction["id"];
        $bid_transaction->invoice_url = $transaction["invoice_url"];
        $bid_transaction->approved = $transaction["approved"];
        return ["job_id"=>$order->job_id];
    }

    public function acceptBid(int $bid_id, string $payment_method)
    {
        $bid = Bids::where("id", $bid_id)->first();
        if ($bid) {
            $bids = Bids::where("order_id", $bid->order_id)->whereNotIn("id", [$bid_id])->update(array("status" => "Rejected"));
            $bid->payment_type=$payment_method;
            $bid->status = "Accepted";
            if ($bid->save()) {
                $order=Order::where("id", $bid->order_id)->first();
                $all_bids = [
                    "accepted_users" => $bid->user_id,
                    "rejected_users" => Bids::where("order_id", "=", $bid->order_id)->where("status", '=', 'Rejected')->pluck("user_id")->toArray(),
                    "bid"=>$bid,
                    "job_id"=>$order->job_id
                ];
                if ($order->update(['status' => "Accepted"])) {
                    return $all_bids;
                }
            }
        } else {
            throw new \Exception("No bid found with id");
        }
    }

    public function rejectBid(int $bid_id)
    {
        $bid = Bids::where("id", $bid_id)->first();
        if ($bid) {
            $bid->status = "Rejected";
            $order=Order::find($bid->order_id);
            if ($bid->save()) {
                return ["bid_user"=>User::find($bid->user_id),"job_id"=>$order->job_id];
            }
        } else {
            throw new \Exception("No bid found with id");
        }
    }

    public function getUserBids($user_id, $paginate = 5)
    {
        return Bids::with(["order" => function ($query) {
            $query->orderBy("id", "DESC");
        }])->get();
    }

    public function getOrderBids(int $order_id, int $user_id)
    {
        // TODO: Implement getOrderBids() method.
        $bids = Bids::where("order_id", $order_id)->where("user_id", $user_id);
        return $bids;
    }

    public function getRepPreviousBids()
    {
        return $this->user->bids()->with(["order" => function ($query) {
            $query->with(["orderConcrete"])->orderBy("id", "DESC")->get();
        }])->whereIn("status", ["Complete", "Cancelled"])->orderBy("id","DESC")->paginate(25);
    }

    public function getRepAcceptedBids()
    {
        $orders = $this->user->bids()->whereHas("order",function($query){
            $query->whereIn("status",["Released", "Paid","Accepted"]);
        })->with(["order" => function ($query) {
            $query->has("orderConcrete")->with(["orderConcrete","message","user" => function ($query) {
                $query->with(['detail' => function ($query) {
                    $query->select(["user_id", "phone_number", "company", "first_name", "last_name", "state", "city", "abn", "profile_image"]);
                }])->select([
                    'id',
                    "email"
                ]);
            }]);
        }])->where("status", "Accepted")->orderBy("id", "DESC")->get();

        return $orders;
        // TODO: Implement getRepAcceptedOrders() method.
    }

    public function updatePaymentMethod(int $bid_id, string $payment_method)
    {
        $order = null;
        $order_type=null;
        $bid = Bids::find($bid_id);
        $order=$bid->order;
        if($bid->status!=="Complete"&&$bid->status!=="Cancelled"&&$order->status!=="archive"){
            if ($bid) {
                $bid->order()->update(["status" => "Paid"]);
                $order_type=$bid->getOrderType();
            }
            return ["order"=>$order,"order_type"=>$order_type];
        }
        else{
            return [];
        }

        // TODO: Implement updatePaymentMethod() method.
    }

    public function getRepBidOrders()
    {
        // TODO: Implement getRepBidOrders() method.
    }

    public function messageOrder(int $bid_id, float $quantity)
    {
        $user=null;
        $bid= Bids::find($bid_id);
        $bid_message=$bid->message()->firstOrCreate([
            "quantity"=>$quantity,
            "status"=>"Awaiting",
            "complete"=>false,
        ]);
        $bid_message->touch();
        if($bid->message()->save($bid_message)){
            $user=$bid->order()->user();
        }
        return ["user"=>$user,"order_message"=>"Message has been sent"];
    }
}
