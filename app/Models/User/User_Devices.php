<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class User_Devices extends Model
{
    //

    public function user(){
        $this->belongsTo("user");
    }
}
