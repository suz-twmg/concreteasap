<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Gate::define("order-owner",function($user,$order){
            return auth()->user()->id===$order->user_id;
        });

        Gate::define("bid-owner",function($bid){
            return auth()->user()->id===$bid->user_id;
        });
    }
}
