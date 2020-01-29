<?php


namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class orderMessage extends Model
{
    protected $fillable=["quantity","status","complete"];

    public function order(){
        return $this->belongsTo(Order::class);
    }

}
