<?php

namespace App\Providers;

use App\Repositories\BidRepository;
use App\Repositories\Contractor\REO\OrderReoRepository;
use App\Repositories\Interfaces\BidRepositoryInterface;
use App\Repositories\Interfaces\Contractor\REO\OrderReoRepositoryInterface;
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

        $this->app->bind(
            OrderReoRepositoryInterface::class,
            OrderReoRepository::class
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
