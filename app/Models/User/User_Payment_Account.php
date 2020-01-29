<?php

namespace App\Models\User;

use App\User;
use Illuminate\Database\Eloquent\Model;

class User_Payment_Account extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_payment_accounts';
    //
    public function user(){
        $this->belongsTo(User::class);
    }

}
