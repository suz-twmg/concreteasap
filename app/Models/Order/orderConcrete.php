<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class orderConcrete extends Model
{
    //

//    protected $columns = ["id","suburb","type","placement_type","mpa","agg","slump","acc","quantity","delivery_date",
//        "delivery_date1","delivery_date2","special_instructions","delivery_instructions","colours",
//        "preference","message_required","urgency","time_preference1","time_preference2","time_preference3","time_deliveries"]; // add all columns from you table

    public function order(){
    	return $this->belongsTo('App\Models\Order\Order','id','order_id');
    }

//    public function scopeExclude($query,$value = array())
//    {
//        return $query->select( array_diff( $this->columns,(array) $value) );
//    }
}
