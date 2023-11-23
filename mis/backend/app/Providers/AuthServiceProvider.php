<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route,
    Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider,
    Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
      // Passport::routes();
        Route::group(['middleware' => 'passport-administrators'], function () {
            
          //Passport::routes();
        });
        // Middleware `oauth.providers` middleware defined on $routeMiddleware above
        Route::group(['middleware' => 'oauth.providers'], function () {
            // Passport::routes(function ($router) {
            //     return $router->forAccessTokens();
            // });
        });
    }
}
