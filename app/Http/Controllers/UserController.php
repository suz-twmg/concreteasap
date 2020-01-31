<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class UserController extends Controller
{
    private $user_repo;
    private $user;
    //
    public function __construct(UserRepository $user_repo)
    {
        $this->user_repo=$user_repo;
        $this->user=auth('api')->user();
    }

    /**
     * update the user details
     *
     * @param Request $request
     * @return void
     */
    public function updateUser(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'company' => 'required',
                'abn' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone_number' => 'required',
                'city' => 'required',
                'state' => 'required',
            ]);

            if(!$validator->fails()){
                $email=$request->get("email");
                $user_details=$request->except(['email','photo']);
                $photo=$request->file("photo");
                $this->user_repo->updateUser($email,$user_details,$photo);
                return response()->json(['message'=>"Successfully Updated"],200);
            }
            else{
                return response()->json(['error' =>$validator->getMessageBag()], 401);
            }
        }
        catch(\Exception $error){
            return response()->json(['message' =>$error->getMessage()], 401);
        }

    }

    public function saveDeviceId(Request $request){
        $device_validation=Validator::make($request->all(),[
            "device_id"=>"required"
        ]);

        if($device_validation->fails()){
            return response()->json(['message' => $device_validation->getMessageBag()], 401);
        }

        if($this->user_repo->saveDevice($request["device_id"],$this->user->id)){
            return response()->json(['message' => "Successfully updated device id"], 200);
        }
    }

    public function removeDeviceId(){
        if($this->user_repo->removeDevice($this->user->id)){
            return response()->json(['message'=>"Successfully removed"],200);
        }
    }

    public function notifications(){
        $notifications=[];
        foreach($this->user->unreadNotifications as $notification){
            $data=[];
            $data["id"]=$notification["id"];
            $data["notification"]=$notification["data"];
            $data["date"]=$notification["created_at"];
            array_push($notifications,$data);
        }
        return response()->json($notifications,200);
    }

    public function mark_read(Request $request){
        $this->user->unreadNotifications()->where("id",$request->get("notification_id"))->update(["read_at"=>now()]);
        return response()->json(["message"=>"Successfully removed"],200);
    }
}
