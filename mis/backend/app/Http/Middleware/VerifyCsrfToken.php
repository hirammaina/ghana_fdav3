<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
/*start*/
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
/*end change */

/**
 * @Author: HiramMaina
 * @Create Time: 2024-01-10 10:38:16
 * @Modified by: JobMurumba
 * @Modified time: 2024-01-25 15:38:13
 * @Description:
 */

class VerifyCsrfToken extends Middleware
{



    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // 'authenticateMisMobileUser',
        // 'authenticateApiUser',
        // 'openoffice/exportall',
        // 'mobileapp/saveToTable',
        // 'mobileapp/getImportPermitDetails',
        // 'mobileapp/saveApplicationPoeProductDetails',
        // 'mobileapp/submitpoe',
        // 'mobileapp/saveInspectionRecommendation',
        // 'uploadFile',
    ];
    /*start new modification Job */

    /**
     * Constructor.
     */
    public function __construct(Application $app, Encrypter $encrypter)
    {
        parent::__construct($app, $encrypter);
        // Dynamically add routes to the except array based on the environment
        $this->setEnvironmentSpecificRoutes();
    }


    protected function setEnvironmentSpecificRoutes()
    {
        $environment = App::environment();
        //exempt all in development to prevent disruption in developments
        if ($environment === 'local') {
            $localRoutes = array();
            $routes = Route::getRoutes();
            foreach ($routes as $route) {
                $uri = $route->uri();
                $localRoutes[] = $uri;
            }


            $this->except = array_merge($this->except, $localRoutes);
        } else {

            //for production exception
            $commonRoutes = [
                'authenticateMisMobileUser',
                'authenticateApiUser',
                'openoffice/exportall',
                'mobileapp/saveToTable',
                'mobileapp/getImportPermitDetails',
                'mobileapp/saveApplicationPoeProductDetails',
                'mobileapp/submitpoe',
                'mobileapp/saveInspectionRecommendation',
                'uploadFile',
            ];
            $this->except = array_merge($this->except, $commonRoutes);
        }
    }
    /** end **/
}
