<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;



class orderReview extends Model
{
    protected $hidden = [
        "user_id"
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id','comment','rating'];

    //
    public function order(){
        $this->belongsTo(Order::class,"id","order_id");
    }
}
