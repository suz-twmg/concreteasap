<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Role extends Model
{
	protected $fillable = [
        'name', 'description'
    ];

 	protected $hidden = ['pivot'];
    //
    public function users()
	{
  		return $this->belongsToMany(User::class);
	}
}
