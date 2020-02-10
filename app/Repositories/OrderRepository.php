<?php

namespace App\Repositories;

use App\Models\Bids\Bids;
use App\Models\Order\Order;
use App\Models\Order\orderConcrete;
use App\Models\Order\BidMessage;
use App\Models\Order\orderMessage;
use App\Models\Order\orderReview;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

// use Hashids\Hashids;

class OrderRepository implements Interfaces\OrderRepositoryInterface
{
    private $user;
    private $roles;

    private $custom_columns;

    public function __construct()
    {
        $this->user = auth('api')->user();
        $this->custom_columns = ["id", "order_id","post_code","state","suburb", "type", "placement_type", "mpa", "agg", "slump", "acc", "quantity", "delivery_date",
            "delivery_date1", "delivery_date2", "special_instructions", "delivery_instructions", "colours",
            "preference", "message_required", "urgency", "time_preference1", "time_preference2", "time_preference3", "time_deliveries"]; // add all columns from you table
    }



    public function createConcrete($order_request)
    {
        $order = new Order();
        //generate order id
        $order->order_hash_id = Str::random(8);
        $order->user_id = $this->user->id;
        $order->order_type = "concrete";
        $order->status = "Pending";
        $order->job_id=$order->generateCustomJobId();
        $order->touch();

        // var_dump();
        // die;v
        $order_concrete = new orderConcrete();
        $order_concrete->address = isset($order_request["address"]) ? $order_request["address"] : "";
        $order_concrete->suburb = $order_request["suburb"];
        $order_concrete->post_code=isset($order_request["post_code"])?$order_request["post_code"]:"";
        $order_concrete->state=isset($order_request["state"])?$order_request["state"]:"";
        $order_concrete->type = $order_request["type"];
        $order_concrete->mpa = $order_request["mpa"];
        $order_concrete->agg = $order_request["agg"];
        $order_concrete->slump = $order_request["slump"];
        $order_concrete->acc = $order_request["acc"];
        $order_concrete->placement_type = $order_request["placement_type"];
        $order_concrete->delivery_date = $order_request["delivery_date"];
        $order_concrete->delivery_date1 = $order_request["delivery_date1"];
        $order_concrete->delivery_date2 = $order_request["delivery_date2"];
        $order_concrete->quantity = $order_request["quantity"];
        $order_concrete->time_preference1 = $order_request["time_preference1"];
        $order_concrete->time_preference2 = isset($order_request["time_preference2"]) ? $order_request["time_preference2"] : "";
        $order_concrete->time_preference3 = isset($order_request["time_preference3"]) ? $order_request["time_preference3"] : "";
        $order_concrete->time_deliveries = $order_request["time_deliveries"];
        $order_concrete->urgency = $order_request["urgency"];
        $order_concrete->message_required = $order_request["message_required"];
        $order_concrete->preference = $order_request["preference"];
        $order_concrete->colours = isset($order_request["colours"]) ? $order_request["colours"] : "";
        $order_concrete->delivery_instructions = isset($order_request["delivery_instructions"]) ? $order_request["delivery_instructions"] : "";
        $order_concrete->special_instructions = isset($order_request["special_instructions"]) ? $order_request["special_instructions"] : "";
        $order_concrete->order_id = $order->id;
        $order_concrete->touch();

        $result = false;
        try {
            DB::beginTransaction();
            $order->save();
            $result = $order->orderConcrete()->save($order_concrete);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return $result;
    }

    public function updateConcrete($order_request)
    {
        $order = Order::find($order_request["order_id"]);
        $order_concrete = [];
        $order_concrete["address"] = $order_request["address"] ? $order_request["address"] : "";
        $order_concrete["suburb"] = $order_request["suburb"];
        $order_concrete["type"] = $order_request["type"];
        $order_concrete["mpa"] = $order_request["mpa"];
        $order_concrete["agg"] = $order_request["agg"];
        $order_concrete["slump"] = $order_request["slump"];
        $order_concrete["acc"] = $order_request["acc"];
        $order_concrete["placement_type"] = $order_request["placement_type"];
        $order_concrete["delivery_date"] = $order_request["delivery_date"];
        $order_concrete["delivery_date1"] = $order_request["delivery_date1"];
        $order_concrete["delivery_date2"] = $order_request["delivery_date2"];
        $order_concrete["quantity"] = $order_request["quantity"];
        $order_concrete["time_preference1"] = $order_request["time_preference1"];
        $order_concrete["time_preference2"] = isset($order_request["time_preference2"]) ? $order_request["time_preference2"] : "";
        $order_concrete["time_preference3"] = isset($order_request["time_preference3"]) ? $order_request["time_preference3"] : "";
        $order_concrete["time_deliveries"] = $order_request["time_deliveries"];
        $order_concrete["urgency"] = $order_request["urgency"];
        $order_concrete["message_required"] = $order_request["message_required"];
        $order_concrete["preference"] = $order_request["preference"];
        $order_concrete["colours"] = isset($order_request["colours"]) ? $order_request["colours"] : "";
        $order_concrete["delivery_instructions"] = isset($order_request["delivery_instructions"]) ? $order_request["delivery_instructions"] : "";
        $order_concrete["special_instructions"] = isset($order_request["special_instructions"]) ? $order_request["special_instructions"] : "";
        $order_concrete["order_id"] = $order->id;
        if ($order->orderConcrete()->update($order_concrete)) {
            return $order->getAcceptedBid();
        } else {
            throw new \Exception("Some Issue has occured");
        }
    }

    public function getUserConcreteOrder()
    {
        return $this->user->orders()->whereHas("orderConcrete")->with(["orderConcrete", "bids" => function ($query) {
            $query->with(["user" => function ($query) {
                $query->with(["detail" => function ($query) {
                    $query->select("user_id", "company")->get();
                }])->select("id")->get();
            }])->where("status", "!=", "Rejected");
        }])->whereNotIn("status", ["Accepted", "Released", "Paid", "archive"])->orderBy('id', 'DESC')->get();
    }

    public function getPendingOrders()
    {
        try {
            return Order::with(["orderConcrete", "bids"])->leftJoin('bids', 'bids.order_id', '=', 'orders.id')
                ->where("bids.user_id", "!=", $this->user->id)->where("status", "===", "pending")->orderBy("id", "DESC")->get();
        } catch (\Exception $e) {

        }
    }

    public function completeOrder($order_id, $quantity, $total, $message_quantity, $message_total, $review = [])
    {
        $order = Order::where("id", $order_id)->first();
//        $order_confirmation = orderConfirmation::where("order_id", $order_id)->first();
//        if (!$order_confirmation) {
//            $order_confirmation = new orderConfirmation();
//            $order_confirmation->order_id = $order_id;
//        }
        if ($this->user->hasRole("contractor")) {
            //update order status
            $order->status = "Complete";


            //create new order review
            $order_review = new orderReview();
            $order_review->order_id = $order_id;
            $order_review->comment = $review["comment"];
            $order_review->rating = $review["rating"];
            //complete order message
            $order_message = orderMessage::find($order_id);
            if($order_message){
                $order_message->complete = true;
            }

            try {
                $bid = Bids::where("order_id", $order_id)->where("status", "Accepted")->first();
                $bid->status = "Complete";
                DB::beginTransaction();
                $order->save();
                $bid->save();
                $order_review->save();
                if($order_message){
                    $order_message->status="Complete";
                    $order_message->save();
                }
                DB::commit();
                $user = null;
                if ($bid) {
                    $user = User::find($bid->user_id);
                }
                return [
                    "user" => $user
                ];
            } catch (Throwable $e) {
                \DB::rollback();
            }

        } else if ($this->user->hasRole("rep")) {
            return [
                "user" => $order->user_id
            ];
        }
    }

    public function cancelOrder($order_id)
    {
        $user = null;
        $order = Order::find($order_id);
        $bid = Bids::where("order_id", $order_id)->where("status", "Accepted")->first();

        $bid->status = "Cancelled";
        $order->status = "Cancelled";

        try {
            $order->save();
            $bid->save();
        } catch (Throwable $e) {
            \DB::rollback();
        }
        $user = $order->user;
        if ($this->user->hasRole("contractor")) {
            $user = $bid->user;
        }
        return $user;
    }

    public function confirmOrderDelivery($order_id)
    {
        // TODO: Implement confirmOrderDelivery() method.
        $order = Order::find($order_id);
        $order->confirm_delivery = true;
        if ($order->save()) {
            $bid_user = $order->getAcceptedBidUser();
            return $bid_user;
        }
    }

    public function getAcceptedOrders()
    {
        return $this->user->orders()->whereHas("orderConcrete")->with(["message", "orderConcrete", "bids" => function ($query) {
            $query->with(["user" => function ($query) {
                $query->with(["detail" => function ($query) {
                    $query->select(["user_id", "company", "first_name", "last_name", "phone_number", "profile_image", "abn"]);
                }])->select(["id", "email"]);
            }])->where("status", "Accepted");
        }])->whereIn("status", ["Accepted", "Released", "Paid"])->orderBy("id", "DESC")->get();
    }

    //Rep Functions

    public function getRepAllOrders()
    {
        $columns = $this->custom_columns;
        $orders = Order::whereHas("orderConcrete")->with(["orderConcrete" => function ($query) use ($columns) {
            $query->select($columns);
        }])->whereNotIn("id", Bids::where("user_id", "=", $this->user->id)->get(['order_id'])->toArray())
            ->whereNotIn("status", ["Accepted", "Released", "Paid", "Complete", "Cancelled", "archive"])
            ->orderBy("id", "DESC");

        $orders = $orders->paginate(25);
//        $orders["sql"]=$orders->toSql();
        return $orders;
    }

    public function getRepPendingOrders()
    {
        $columns = $this->custom_columns;
        $orders = $this->user->bidPendingOrders();
//        []
        return $orders;
    }

    public function getOrder($id)
    {
        $order = Order::with(["orderConcrete"])->where("id", $id)->first();
        return $order;
    }

    public function getRepOrders()
    {
        $user_id = $this->user->id;
        $orders = Order::with(["orderConcrete"])->whereIn("id", function ($query) use ($user_id) {
            $query->select("order_id")->from("bids")->where("user_id", "=", $user_id);
        })->get();

        return $orders;
        // TODO: Implement getRepOrders() method.
    }

    public function getRepAcceptedOrders()
    {
        $orders = $this->user->bids()->whereHas("order", function ($query) {
            $query->whereIn("status", ["Released", "Paid","Accepted"]);
        })->with(["order" => function ($query) {
            $query->has("orderConcrete")->with(["orderConcrete", "user" => function ($query) {
                $query->with(['detail' => function ($query) {
                    $query->select(["user_id", "phone_number", "company", "first_name", "last_name", "state", "city", "abn", "profile_image"]);
                }])->select([
                    'id',
                    "email"
                ]);
            }]);
        }])->where("status", "Accepted")->orderBy("id", "DESC")->paginate(25);
        return $orders;
        // TODO: Implement getRepAcceptedOrders() method.
    }

    public function releaseOrder($bid_id)
    {
        try{
            if ($this->user->hasRole("rep")) {
                $bid = Bids::find($bid_id);
                $bid->released=true;
                if ($bid->save()) {
                    $order=Order::find($bid->order_id);
                    $order->status="Released";
                    $order->save();
                    return $order;
                }
            }
            else{
                return null;
            }

        }
        catch(\Exception $e){
            return $e->getMessage();
        }

    }

    public function archiveOrder($order_id)
    {
        $order = Order::find($order_id);
        $order->status = "archive";
        $accepted_bid = $order->getAcceptedBid();

        $result = false;
        try {
            $result = $order->save();
            if ($accepted_bid) {
                $accepted_bid->status = "archive";
                $result = $accepted_bid->save();
            }
        } catch (\Throwable $e) {
            \DB::rollback();
        }
        return $result;
    }

    public function getDayOfPourOrders()
    {
        $orders = $this->user->orders()->with(["orderConcrete", "user", "bids" => function ($query) {
            $query->with(["user" => function ($query) {
                $query->with(["detail" => function ($query) {
                    $query->select(["user_id", "company", "first_name", "last_name", "phone_number", "profile_image", "abn"]);
                }])->select(["id", "email"]);
            }])->where("status", "Accepted");
        }])->whereHas("bids", function ($query) {
            $query->where("date_delivery", \Illuminate\Support\Carbon::now('Australia/Sydney')->format("Y-m-d"));
        })->whereIn("status", ["Accepted", "Released", "Paid"])->paginate(20);

        return $orders;
    }

    public function messageOrder(int $order_id, float $quantity)
    {
        $user = null;
        $order = Order::find($order_id);
        $bid_message = $order->message()->create([
            "quantity" => $quantity,
            "price"=>0,
            "status" => "Awaiting",
            "complete" => false,
        ]);
        $bid_message->touch();
        if ($order->message()->save($bid_message)) {
            $user = $order->getAcceptedBidUser();
        }
        return ["user" => $user, "order_message" =>$order->message()->get(),"bid"=>$order->getAcceptedBid()];
    }

    public function setMessagePrice(int $message_id, float $price)
    {
        $order = null;
        try{
            $message = orderMessage::findorFail($message_id);
            $message->price=$price;
            $message->touch();
            $message->save();
            return $message;
        }
        catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function updateMessageStatus(int $message_id,string $status){
        $user = null;
        try{
            $order_message = orderMessage::findOrFail($message_id);
            if ($order_message) {
                $order_message->status=$status;
                $order_message->save();
//                $user=$order_message->getAcceptedBidUser();
            }
            $message=$status==="Accepted"?"Message has been accepted":"Message has been rejected";
            return ["order" =>Order::find($order_message->order_id), "message" =>$message];

        }catch(\Exception $e){
            return $e->getMessage();
        }

    }

}
