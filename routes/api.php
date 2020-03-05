<?php

use App\Models\Order\Order;
use App\Models\Order\orderMessage;
use App\Notifications\AppNotification;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("test", function (App $app, OrderRepositoryInterface $orderRep, User $user, Order $order) {
//     $order = Order::find(83);
    var_dump(\Illuminate\Support\Carbon::now('Australia/Sydney')->format("Y-m-d"));
});


Route::group([
    'middleware' => ['cors', 'api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'APILoginController@login');
    Route::post('register', 'APILoginController@register');
    Route::post('logout', 'APILoginController@logout');
    Route::post('refresh', 'APILoginController@refresh');
    Route::post('me', 'APILoginController@me');
    Route::post('get_reset_token', "Auth\ForgotPasswordController@getResetToken");
    Route::post('reset_password', "Auth\ResetPasswordController@reset");

});

Route::group([
    'middleware' => ['cors', 'api', 'jwt.verify'],
], function ($router) {
//    Route::get('user/details','APILoginController@getUserDetails');
    Route::post('user/update', "UserController@updateUser");
    Route::post('user/save_device', 'UserController@saveDeviceId');
    Route::post('user/remove_device', 'UserController@removeDeviceId');
    Route::get('user/notifications', 'UserController@notifications');
    Route::post('user/mark_read', 'UserController@mark_read');
    Route::get('client/payment_token', 'Payment\PaymentController@getPaymentToken');
});


Route::group([
    'middleware' => ['api', 'jwt.verify'],
    'prefix' => 'contractor'
], function ($router) {

    Route::get('order/concrete', 'Contractor\OrderController@index');
    Route::post('order/concrete', 'Contractor\OrderController@store');
    Route::put('order/concrete', 'Contractor\OrderController@update');
    Route::get('orders/{id}/bids', 'Contractor\BidController@getOrderBid');
    Route::get('order/get_accepted_orders', 'Contractor\OrderController@getAcceptedOrders');
    Route::get('order/getAllDayOfPour', 'Contractor\OrderController@getAllDayOfPour');
    Route::get("orders/previous","Contractor\OrderController@getPreviousOrders");

    Route::post('order/accept', "Contractor\BidController@acceptOrderBid");
    Route::post('order/reject', "Contractor\BidController@rejectOrderBid");

    Route::post('order/messageOrder', 'Contractor\OrderController@messageOrder');
    Route::post("order/updateMessageStatus", 'Contractor\OrderController@updateMessageStatus');

    Route::post('order/markPaid','Contractor\OrderController@markOrderAsPaid');

    Route::post('order/completeOrder', 'Contractor\OrderController@completeOrder');
    Route::post('order/confirmOrderDelivery', 'Contractor\OrderController@confirmOrderDelivery');
    Route::post('order/modifyOrder', 'Contractor\OrderController@updateOrder');
    Route::post('order/cancelOrder', 'Contractor\OrderController@cancelOrder');
    Route::post('order/archiveOrder', 'Contractor\OrderController@archiveOrder');

    Route::post("order/reo","Contractor\REO\OrderController@create");
    Route::get("order/reo/pending","Contractor\REO\OrderController@getPending");
    Route::get("order/reo/{id}/bids","Contractor\REO\OrderController@getBids");
    Route::get("order/reo/acceptedOrders","Contractor\REO\OrderController@getAcceptedOrders");
    Route::get("order/reo/getDayOfPourOrders","Contractor\REO\OrderController@getDayOfPourOrders");


});

Route::group([
    'middleware' => ['api', 'jwt.verify'],
    'prefix' => 'rep'
], function ($router) {
    Route::get('orders', 'Rep\OrderController@getRepAllOrders');
    Route::get('pending_orders', 'Rep\OrderController@getPendingOrders');
    Route::get('accepted_orders', 'Rep\OrderController@getAcceptedOrders');
    Route::get('debug_accepted', 'Rep\OrderController@getDebugAcceptedOrders');
    Route::post('cancel_order', 'Rep\OrderController@cancelOrder');

    Route::post('bid', 'Rep\BidController@saveBid');
    Route::get('bid/previous_order', 'Rep\BidController@previousOrder');
    Route::get("bid/accepted_order", "Rep\BidController@acceptedOrder");

    Route::post('updatePaymentMethod', 'Rep\BidController@updatePaymentMethod');
    Route::post("release_order", "Rep\BidController@releaseOrder");
    Route::get('bids', 'Rep\BidController@getUserBid');
    Route::post('pay/bid', 'Payment\PaymentController@payBidAmount');

});

Route::group([
    'middleware' => ['api', 'jwt.verify'],
    'prefix' => 'rep'
], function ($router) {
    Route::post('order/setMessagePrice', 'Rep\OrderController@setMessagePrice');

});
