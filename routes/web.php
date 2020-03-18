<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Notifications\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

Auth::routes();

//redirect index to login
Route::get('/',function(){
	return redirect('login');
});

Route::get('/test',function(\App\Repositories\UserRepository $user_repo){
    $order_user=$user_repo->getOrderUser(126);
    $notification = [
        "msg" => "You have received new bid.",
        "route"=>"ViewBids",
        "btn"=>["id"=>"VIEW_BIDS","text"=>"View Bid"],
        "params"=>array(
            "order_id"=>126
        )
    ];
    Notification::send($order_user, new AppNotification($notification));
//    var_dump(phpinfo());
//   return view("test");
});


Route::post("upload_file",function(Request $request){
    $photo=$request->file("photo");
    $fileExtension=$photo->getClientOriginalName();
    $file_name=pathinfo($fileExtension,PATHINFO_FILENAME);
    $extension=$photo->getClientOriginalExtension();
    $file_name=$file_name."_".uniqid().".".$extension;
    Storage::disk('ftp')->put($file_name,fopen($photo,'r+'));

});

//protect route
Route::group([
    'middleware' => ['auth','checkRole'],
], function ($router) {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::resource('/order','Admin\OrderController');
	Route::get('/api/orders/getAll','Admin\OrderController@getOrders');
});

