<?php

use App\Modules\OrganisationConfig\Http\Controllers\OrganisationConfigController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('organisationconfig')->group(function () {
        Route::controller(OrganisationConfigController::class)->group(function () {
            Route::get('/', 'ndex');
            Route::get('getOrgConfigParamFromModel', 'getOrgConfigParamFromModel');
            Route::get('getDepartments', 'getDepartments');
        
        });

    });
});

// Route::group(['middleware' => 'web', 'prefix' => 'organisationconfig', 'namespace' => 'App\\Modules\OrganisationConfig\Http\Controllers'], function()
// {
//     Route::get('/', 'OrganisationConfigController@index');
//     Route::get('getOrgConfigParamFromModel', 'OrganisationConfigController@getOrgConfigParamFromModel');
//     Route::get('getDepartments', 'OrganisationConfigController@getDepartments');
// });
