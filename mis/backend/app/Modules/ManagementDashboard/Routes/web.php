<?php

use App\Modules\ManagementDashboard\Http\Controllers\ManagementDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('managementdashboard')->group(function () {
        Route::controller(ManagementDashboardController::class)->group(function () {
            Route::get('getApplicationsCartesianDasboardReport', 'getApplicationsCartesianDasboardReport');
            Route::get('getApplicationsGridDasboardReport', 'getApplicationsGridDasboardReport');
            Route::get('ProductgetApplicationsDasboardReport', 'ProductgetApplicationsDasboardReport');
            Route::get('getSectionRevenueApplicationsDasboardReport', 'getSectionRevenueApplicationsDasboardReport');
            Route::get('getZonalRevenueApplicationsDasboardReport', 'getZonalRevenueApplicationsDasboardReport');
        
        
    
        });

    });
});
// Route::group(['middleware' => 'web', 'prefix' => 'managementdashboard', 'namespace' => 'App\\Modules\ManagementDashboard\Http\Controllers'], function()
// {
//     Route::get('getApplicationsCartesianDasboardReport', 'ManagementDashboardController@getApplicationsCartesianDasboardReport');
//     Route::get('getApplicationsGridDasboardReport', 'ManagementDashboardController@getApplicationsGridDasboardReport');
//     Route::get('ProductgetApplicationsDasboardReport', 'ManagementDashboardController@ProductgetApplicationsDasboardReport');
//     Route::get('getSectionRevenueApplicationsDasboardReport', 'ManagementDashboardController@getSectionRevenueApplicationsDasboardReport');
//     Route::get('getZonalRevenueApplicationsDasboardReport', 'ManagementDashboardController@getZonalRevenueApplicationsDasboardReport');
    


   
// });
