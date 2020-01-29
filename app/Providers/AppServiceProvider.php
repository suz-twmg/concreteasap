<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config()->set("jwt.required_claims",[
            'iss',
            'iat',
            // 'exp',
            'nbf',
            'sub',
            'jti',
        ]);
        config()->set('jwt.ttl',null);
        config()->set("mail.password","pmvbxvoinjituzpu");

        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \Braintree_Configuration::environment("sandbox");
        \Braintree_Configuration::merchantId("6ndm75pghr7mmd8m");
        \Braintree_Configuration::publicKey("kqs3g497hr9x9z6z");
        \Braintree_Configuration::privateKey("8698ac3a671fea6479a2206fa50b8e70");
        Stripe::setApiKey('sk_test_UqXCc0jBm9HEH5SBV3RRIc8M00lxPjucgl');
        $this->app['request']->server->set('HTTPS', false);
     }
}
