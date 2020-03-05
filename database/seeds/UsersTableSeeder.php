<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $admin_role = factory("App\Role")->create([
        	'name'=>"administrator",
        	"description"=>"administrator"
        ]);

        $rep_role=factory("App\Role")->create([
        	'name'=>"rep",
        	"description"=>"Rep Role"
        ]);

        $reo_role=factory("App\Role")->create([
            'name'=>"reo_role",
            "description"=>"Reo Role"
        ]);

        $contractor_role=factory("App\Role")->create([
        	'name'=>"contractor",
        	"description"=>"Contractor Role"
        ]);

        $admin_user = factory("App\User")->create([
        	'username'=>'reggie',
        	"device_id"=>"",
        	'status'=>'verified',
	        'email'=>"reggie@twmg.com.au",
	        "password"=>bcrypt("twmg#2019")
	    ])->each(function($admin_user) use($admin_role){
	    	$admin_user->roles()->sync($admin_role);
	    });

//	    $rep_user = factory("App\User")->create([
//        	'username'=>'sujan',
//        	"device_id"=>"",
//        	'status'=>'verified',
//	        'email'=>"sujan@twmg.com.au",
//	        "password"=>bcrypt("twmg#2019")
//	    ])->each(function($user) use($rep_role){
//	    	$user->roles()->sync($rep_role);
//	    });
//
	    $contractor_user = factory("App\User")->create([
        	'username'=>'isuru',
        	"device_id"=>"",
        	'status'=>'verified',
	        'email'=>"isuru@twmg.com.au",
	        "password"=>bcrypt("twmg#2019")
	    ])->each(function($user) use($contractor_role){
	    	$user->roles()->sync($contractor_role);
	    });
//
		// $admin_role->first()->users()->sync($admin_user);
		// $rep_user->first()->roles()->sync($rep_role);
		// $contractor_user->first()->users()->sync($contractor_role);
	}
}
