<?php

namespace App\Repositories;

use App\Models\Order\Order;
use App\Models\User\User_Payment_Account;
use App\User;
use App\Models\User\User_Details;
use App\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository implements Interfaces\UserRepositoryInterface
{
    private $user;

    public function __construct()
    {
        $this->user=auth('api')->user();
    }

    public function save($user_details, $photo)
    {
        $user = new User();
        $user_detail = new User_Details();
        $user_role = Role::where('name', '=', $user_details['roles'])->first();
        $user->email = $user_details["email"];
        $user->password = Hash::make($user_details["password"]);
        $user->status = "verified";
        $user->username = "";
        $user->external_id=uniqid();
        $user->device_id = "";


        $user_detail->company = $user_details["company"];
        $user_detail->abn = $user_details["abn"];
//        $user_detail->title = $user_details["title"];
        $user_detail->first_name = $user_details["first_name"];
        $user_detail->last_name = $user_details["last_name"];
        $user_detail->phone_number = $user_details["phone"];

        $user_detail->city = $user_details["city"];
        $user_detail->state = $user_details["state"];

        if (!is_null($photo)) {
            $file_name = $this->uploadImage($photo);
            $user_detail->profile_image = "http://analytics.twmg.com.au/concrete/users/" . $file_name;
        }

        DB::beginTransaction();
        $result=false;
        try {
            $user->save();
            $user->detail()->save($user_detail);
            $result=$user->roles()->save($user_role);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            // something went wrong
        }

        return $result;
    }

    public function saveDevice(string $device_id, int $user_id)
    {
        $user = User::find($user_id);
        $user->device_id = $device_id;
        return $user->save();
    }

    public function savePaymentDetail(string $payment_token, int $user_id)
    {
        $user_payment = new User_Payment_Account();
        $user_payment->payment_token = $payment_token;
        $user_payment->verified = true;
        $user_payment->user_id = $user_id;
        return $user_payment->save();
    }

    public function getOrderUser(int $order_id)
    {
        $order = Order::find($order_id);
        return $order->user()->first();
    }

    public function removeDevice(int $user_id)
    {
        $user = User::find($user_id);
        $user->device_id = "";
        return $user->save();
        // TODO: Implement removeDevice() method.
    }

    public function updateUser(string $email, $user_details, $photo = null)
    {
        if ($this->user->email !== $email) {
            if (!$this->user->where('email', $email)->exists()) {
                $this->user->email = $email;
            } else {
                throw new \Exception("Email Already existed");
            }
        }
//        var_dump(!is_null($photo));
//        var_dump($photo);
        if (!is_null($photo)) {
            $user_detail=$this->user->detail()->first();
            $url="http://analytics.twmg.com.au/concrete/users/";
            $old_fileName=str_replace($url, '',$user_detail->profile_image);
            $this->deleteImage($old_fileName);
            $file_name = $this->uploadImage($photo);
            $user_details["profile_image"] = $url. $file_name;
        }
        try {
            DB::beginTransaction();
            $this->user->update();
            $this->user->detail()->update($user_details);
            DB::commit();
        } catch (Throwable $e) {
            \DB::rollback();
        }
    }

    private function deleteImage($old_fileName){
        if(Storage::disk('ftp')->exists($old_fileName)){
            Storage::disk("ftp")->delete($old_fileName);
        }
    }

    private function uploadImage($photo)
    {
        $fileExtension = $photo->getClientOriginalName();
        $file_name = pathinfo($fileExtension, PATHINFO_FILENAME);
        $extension = $photo->getClientOriginalExtension();
        $extension = $extension ? $extension : "jpg";
        $file_name = $file_name . "_" . uniqid() . "." . $extension;
        Storage::disk('ftp')->put($file_name, fopen($photo, 'r+'));
        return $file_name;
    }
}
