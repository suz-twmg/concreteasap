<?php

namespace App\Http\Controllers\Contractor;

use App\Notifications\AppNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\OrderRepositoryInterface;

// use App\Repositories\OrderRepository;

//use Illuminate\Http\Resources\Json\JsonResource;
//use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private $orderRep;

//    private $user;

    public function __construct(OrderRepositoryInterface $orderRep)
    {
        $this->orderRep = $orderRep;
//        $this->user = auth('api')->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->orderRep->getUserConcreteOrder(), 200);
    }

    public function getAllOrders(){
//        $this->orderRep->getUserConcreteOrder();
//        $this->orderRep->getAcceptedOrders();
//        $this->orderRep->
    }


    public function getAcceptedOrders()
    {
        try{
            return response()->json($this->orderRep->getAcceptedOrders(), 200);
        }
        catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'suburb' => 'required|max:255',
            'type' => 'required',
            'mpa' => 'required',
            'agg' => 'required',
            'slump' => 'required',
            "acc" => 'required',
            "placement_type" => 'required',
            "quantity" => 'required',
            'delivery_date' => 'required',
            "time_preference1" => 'required',
            "time_preference2" => 'required',
            "time_preference3" => 'required',
            "time_deliveries" => 'required',
            "urgency" => 'required',
            "message_required" => 'required',
            "preference" => 'required',
        ]);

        if (!$validator->fails()) {
            if ($this->orderRep->createConcrete($request->all())) {
                return response()->json(array("message" => "Your Order has been placed"), 200);
            }

        } else {
            return response()->json($validator->errors(), 400);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
//        var_dump($request->all());
//        die;
        //
        $validator = Validator::make($request->all(), [
            'suburb' => 'required|max:255',
            'type' => 'required',
            'mpa' => 'required',
            'agg' => 'required',
            'slump' => 'required',
            "acc" => 'required',
            "placement_type" => 'required',
            "quantity" => 'required',
            'delivery_date' => 'required',
            "time_preference1" => 'required',
            "time_deliveries" => 'required',
            "urgency" => 'required',
            "message_required" => 'required',
            "preference" => 'required',
        ]);

        if (!$validator->fails()) {
            $bid = $this->orderRep->updateConcrete($request->all());
            $bid_user = $bid->user()->get();
            if ($bid_user) {
                $notification = [
                    "msg" => "Order " . $request->get("order_id") . " has been modified.",
                    "route" => "Accepted Bid Detail",
                    "params" => array(
                        "bid_id" => $bid->id
                    )
                ];
                Notification::send($bid_user, new AppNotification($notification));
                return response()->json(array("message" => "Successfully Updated"), 200);
            }

        } else {
            return response()->json($validator->errors(), 400);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeOrder(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'quantity' => 'required',
//            'message_quantity'=>'required',
            'rating' => 'required',
            'comment' => 'required'
        ]);
        try {
            if (!$validator->fails()) {
                $order_id = $request->get("order_id");
                $quantity = $request->get("quantity");
                $total = $request->get("total") ? $request->get("total") : 0;
                $message_quantity = $request->get("message_quantity") ? $request->get("message_quantity") : 0;
                $message_total = $request->get("message_total") ? $request->get("message_total") : 0;

                $review = ["comment" => $request->get("comment"), "rating" => $request->get("rating")];
                $result = $this->orderRep->completeOrder($order_id, $quantity, $total, $message_quantity, $message_total, $review);

                if ($result["user"]) {
                    $notification = [
                        "msg" => "Order " . $order_id . " has been completed.",
                        "route" => "OrderStatus",
                        "params" => array(
                            "order_id" => $request->get("order_id")
                        )
                    ];
                    Notification::send($result["user"], new AppNotification($notification));
                }

                return response()->json(array("message" => "Order has been completed"), 200);
            }
            else {
                return response()->json($validator->errors(), 400);
            }
        }
        catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmOrderDelivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);
        try{
            if ($validator->validate()) {
                $order_id = $request->get("order_id");
                $user = $this->orderRep->confirmOrderDelivery($order_id);
                if ($user) {
//                var_dump($user);
                    $notification = [
                        "msg" => "Order " . $order_id . " has been delivered.",
                        "route" => "OrderStatus",
                        "params" => array(
                            "order_id" => $request->get("order_id")
                        )
                    ];
                    Notification::send($user, new AppNotification($notification));
                }
                return response()->json(array("message" => "Order has been completed"), 200);
            }
        }
        catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        try {
            if ($validator->validate()) {
                $order_id = $request->get("order_id");
                $user = $this->orderRep->cancelOrder($order_id);
                if ($user) {
                    $notification = [
                        "msg" => "Order has been cancelled.",
                        "route" => "OrderStatus",
                        "params" => array(
                            "order_id" => $request->get("order_id")
                        )
                    ];
                    Notification::send($user, new AppNotification($notification));
                }
                return response()->json(array("message" => "Order has been cancelled"), 200);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function getAllDayOfPour()
    {
        return response()->json($this->orderRep->getDayOfPourOrders(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function archiveOrder(Request $request)
    {
        if ($this->orderRep->archiveOrder($request->get("order_id"))) {
            return response()->json("Succesfully Archived", 200);
        } else {
            return response()->json("Couldn't Archive", 400);
        }
    }

    public function messageOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'quantity' => 'required'
        ]);
        try {
            if (!$validator->fails()) {
                $order_id = $request->get("order_id");
                $quantity = $request->get("quantity");
                $order = $this->orderRep->messageOrder($order_id, $quantity);
                if ($order) {
                    $notification = [
                        "msg" => "Message has been requested.",
                        "route" => "OrderStatus",
                        "params" => array(
                            "order_id" => $request->get("order_id")
                        )
                    ];

                    Notification::send($order["user"], new AppNotification($notification));
                    return response()->json(array("message" => "Message has been requested", "order_message" => $order["order_message"]), 200);
                } else {
                    return response()->json(array("message" => "Message has been requested"), 400);
                }
            } else {
                return response()->json($validator->errors(), 400);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function updateMessageStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'message_id' => 'required',
            'status' => 'required'
        ]);
        try {
            if($validator->validate()) {
                $message_id = $request->get("message_id");
                $status = $request->get("status");
                $response = $this->orderRep->updateMessageStatus($message_id, $status);
                if($response){
                    $order=$response["order"];
                    $notification = [
                        "msg" => $response["message"],
                        "route" => "OrderStatus",
                        "params" => array(
                            "order_id" => $order["id"]
                        )
                    ];
                    Notification::send($order->getAcceptedBidUser(), new AppNotification($notification));
                    return response()->json(array("message" => "Message has been accepted", "order_message" => $order["message"]), 200);
                }
            }
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


}
