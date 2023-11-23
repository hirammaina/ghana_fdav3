<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boo12t()
    {
        Schema::defaultStringLength(191);
if (config('app.debug')) {
    error_reporting(E_ALL & ~E_USER_DEPRECATED);
} else {
    error_reporting(0);
}
    }
  public function boot()
    { error_reporting(0);
        Schema::defaultStringLength(191);
		/*if (config('app.debug')) {
			error_reporting(E_ALL & ~E_USER_DEPRECATED);
		} else {
		   
		}
		*/

    //\URL::forceScheme('https');
  }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
