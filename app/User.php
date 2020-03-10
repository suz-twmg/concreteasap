<?php

namespace App;

use App\Models\Bids\Bid;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Role;
use App\Models\User\User_Details;
use App\Models\Order\Order;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', "id"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private $custom_columns = ["id", "order_id","post_code","state","suburb", "type", "placement_type", "mpa", "agg", "slump", "acc", "quantity", "delivery_date",
        "delivery_date1", "delivery_date2", "special_instructions", "delivery_instructions", "colours",
        "preference", "message_required", "urgency", "time_preference1", "time_preference2", "time_preference3", "time_deliveries"];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function getRoleNames()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->select(array('roles.name'))->get();
    }

    public function detail()
    {
        return $this->hasOne(User_Details::class, 'user_id', 'id');
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class,"user_id","id");
    }

    public function acceptedOrderBids(){
        return $this->bids()->whereHas("orders");
    }

    public function bidPendingOrders()
    {
        $columns = $this->custom_columns;
//        $this->bids()->whereHas("order")->get();
        return $this->bids()->whereHas("order",function($query){
            $query->whereIn("status", ["Pending"]);
        })->with(["order" => function ($query) use ($columns) {
            $query->whereHas("orderConcrete")->with(["orderConcrete" => function ($query) use ($columns) {
                return $query->select($columns);
            }])->orderBy("id", "DESC");
        }])->whereIn("status", ["Pending"])->orderBy("order_id","DESC")->get();
    }


    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function routeNotificationForOneSignal()
    {
        return $this->devices;
    }

    public function getContractorOrders($status){
        return $this->orders()->whereHas("orderConcrete")->with(["orderConcrete", "bids" => function ($query) {
            $query->with(["user" => function ($query) {
                $query->with(["detail" => function ($query) {
                    $query->select("user_id", "company")->get();
                }])->select("id")->get();
            }])->where("status", "!=", "Rejected");
        }])->whereIn("status",$status)->orderBy('id', 'DESC')->get();
    }

    public function devices(){
        $this->hasMany("devices");
    }



//    public function setEmailAttribute($value)
//    {
//        $this->attributes['email'] = strtolower($value);
//    }
//
//    public function getEmailAttribute($value)
//    {
//        return strtolower($value);
//    }
}
