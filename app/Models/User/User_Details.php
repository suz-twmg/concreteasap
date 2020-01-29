<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use App\User;

class User_Details extends Model{

	protected $table = 'user_details';

    protected $hidden = array('id', 'created_at',"updated_at","user_id");

    protected $fillable = array('title','first_name','last_name','phone','company','abn','city','state');

	public function user(){
        return $this->belongsTo(User::class);
    }

}
