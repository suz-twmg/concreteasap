<?php

namespace App\Providers;

use App\Repositories\BidRepository;
use App\Repositories\Interfaces\BidRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(            
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(            
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->bind(
            BidRepositoryInterface::class,
            BidRepository::class
        );

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //       
    }
}
