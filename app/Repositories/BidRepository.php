<?php

namespace App\Repositories;

use App\Model\Bids\Bid_Transactions;
use App\Models\Bids\Bid;
use App\Models\Order\Order;
use App\User;
use Illuminate\Support\Facades\Hash;

// use Hashids\Hashids;

class BidRepository implements Interfaces\BidRepositoryInterface
{

    private $bids;
    private $user;
    private $custom_columns;

    public function __construct(Bid $bids)
    {
        $this->bids = $bids;
        $this->user = auth('api')->user();
        $this->custom_columns = ["id", "order_id", "post_code", "state", "suburb", "type", "placement_type", "mpa", "agg", "slump", "acc", "quantity", "delivery_date",
            "delivery_date1", "delivery_date2", "special_instructions", "delivery_instructions", "colours",
            "preference", "message_required", "urgency", "time_preference1", "time_preference2", "time_preference3", "time_deliveries"]; // add all columns from you table
    }

    public function save($price, $order_id, $user_id, $transaction, $date_delivery, $time_delivery)
    {
        $price = (float)$price;
        $bid = new Bid();
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
        $bid = Bid::where("id", $bid_id)->first();
        if ($bid) {
            $bids = Bid::where("order_id", $bid->order_id)->whereNotIn("id", [$bid_id])->update(array("status" => "Rejected"));
            $bid->payment_type=$payment_method;
            $bid->status = "Accepted";
            if ($bid->save()) {
                $order=Order::where("id", $bid->order_id)->first();
                $all_bids = [
                    "accepted_users" => $bid->user_id,
                    "rejected_users" => Bid::where("order_id", "=", $bid->order_id)->where("status", '=', 'Rejected')->pluck("user_id")->toArray(),
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
        $bid = Bid::where("id", $bid_id)->first();
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
        return Bid::with(["order" => function ($query) {
            $query->orderBy("id", "DESC");
        }])->get();
    }

    public function getOrderBids(int $order_id, int $user_id)
    {
        // TODO: Implement getOrderBids() method.
        $bids = Bid::where("order_id", $order_id)->where("user_id", $user_id);
        return $bids;
    }

    public function getRepPreviousBids()
    {
        return $this->user->bids()->with(["order" => function ($query) {
            $query->with(["orderConcrete"=>function($query){
                $query->select($this->custom_columns);
            }])->orderBy("id", "DESC")->get();
        }])->whereIn("status", ["Complete", "Cancelled","Rejected"])->orderBy("id","DESC")->paginate(200);
    }

    public function getRepAcceptedBids()
    {
        $orders = $this->user->bids()->whereHas("order",function($query){
            $query->whereIn("status",["Released", "Paid","Accepted","Waiting Payment Confirmation"]);
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

    public function updatePaymentMethod(Bid $bid, string $payment_method)
    {
        $order = null;
        $order_type=null;

        $order=$bid->order;

        if($bid->isCompleteOrCancelled()){
            throw new \Exception("Job has been already been complete or cancelled");
        }

        if($order->isPaid()){
            throw new \Exception("Job has been already been paid");
        }

        if($order->isReleased()){
            throw new \Exception("Job has been already been released");
        }

        $bid->order()->update(["status" =>"Paid"]);
        $order_type=$bid->getOrderType();

        return ["order"=>$order,"order_type"=>$order_type];

        // TODO: Implement updatePaymentMethod() method.
    }

    public function releaseOrder(Bid $bid)
    {
        $bid->released = true;
        $order = $bid->order;

        if(!$bid->isDayOfPour()){
            throw new \Exception("Job can only be released on scheduled day of pour");
        }

        if($bid->isCompleteOrCancelled()){
            throw new \Exception("Job has been already been complete or cancelled");
        }

        if($order->status==="Released"){
            throw new \Exception("Job has already been released");
        }

        if(!$order->isPaid()){
            throw new \Exception("Job has not been paid");
        }

        if ($bid->save()) {
            $order->status = "Released";
            $order->save();
            return ["order" => $order, "order_type" => $bid->getOrderType(), "job_id" => $order->job_id];
        }
    }

    public function getRepBidOrders()
    {
        // TODO: Implement getRepBidOrders() method.
    }

    public function messageOrder(int $bid_id, float $quantity)
    {
        $user=null;
        $bid= Bid::find($bid_id);
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
