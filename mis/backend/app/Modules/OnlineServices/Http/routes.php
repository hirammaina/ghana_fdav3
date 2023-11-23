<?php

use App\Modules\OnlineServices\Http\Controllers\OnlineServicesConfigController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('onlineservices')->group(function () {
        Route::controller(OnlineServicesConfigController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('doDeleteConfigWidgetParam', 'doDeleteConfigWidgetParam');
            Route::post('saveApplicationstatusactions', 'saveApplicationstatusactions');
            Route::post('saveOnlineservices', 'saveOnlineservices');
        
            Route::post('saveOnlinePortalData', 'saveOnlinePortalData');
            Route::post('saveUniformOnlinePortalData', 'saveUniformOnlinePortalData');
            
            
            Route::post('saveApplicationstatusactions', 'saveApplicationstatusactions');
            
            Route::get('getApplicationTermsConditions', 'getApplicationTermsConditions');
            Route::get('getapplicationstatusactions', 'getapplicationstatusactions');
            Route::get('getOnlineMenuLevel0', 'getOnlineMenuLevel0');
            Route::get('getSystemNavigationMenuItems', 'getSystemNavigationMenuItems');
            Route::get('getOnlinePortalServicesDetails', 'getOnlinePortalServicesDetails');
            Route::get('getApplicationdocumentdefination', 'getApplicationdocumentdefination');
            Route::get('getOnlineProcessTransitionsdetails', 'getOnlineProcessTransitionsdetails');
           
            Route::get('getapplicationstatus', 'getapplicationstatus');
            Route::get('getApplicationprocessguidelines', 'getApplicationprocessguidelines');
            Route::post('deleteSystemMenuItem','deleteSystemMenuItem');
            Route::get('getOnlineProcesdetails','getOnlineProcesdetails');
            Route::post('deleteSystemProcess','deleteSystemProcess');
            Route::get('getOnlineFormsParams','getOnlineFormsParams');
        
        
    
        });

    });
});


// Route::group(['middleware' => 'web', 'prefix' => 'onlineservices', 'namespace' => 'App\\Modules\OnlineServices\Http\Controllers'], function()
// {
//     Route::get('/', 'OnlineServicesController@index');
//     Route::post('doDeleteConfigWidgetParam', 'OnlineServicesConfigController@doDeleteConfigWidgetParam');
//     Route::post('saveApplicationstatusactions', 'OnlineServicesConfigController@saveApplicationstatusactions');
//     Route::post('saveOnlineservices', 'OnlineServicesConfigController@saveOnlineservices');

//     Route::post('saveOnlinePortalData', 'OnlineServicesConfigController@saveOnlinePortalData');
//     Route::post('saveUniformOnlinePortalData', 'OnlineServicesConfigController@saveUniformOnlinePortalData');
    
    
//     Route::post('saveApplicationstatusactions', 'OnlineServicesConfigController@saveApplicationstatusactions');
    
// 	Route::get('getApplicationTermsConditions', 'OnlineServicesConfigController@getApplicationTermsConditions');
// 	Route::get('getapplicationstatusactions', 'OnlineServicesConfigController@getapplicationstatusactions');
// 	Route::get('getOnlineMenuLevel0', 'OnlineServicesConfigController@getOnlineMenuLevel0');
//     Route::get('getSystemNavigationMenuItems', 'OnlineServicesConfigController@getSystemNavigationMenuItems');
//     Route::get('getOnlinePortalServicesDetails', 'OnlineServicesConfigController@getOnlinePortalServicesDetails');
//     Route::get('getApplicationdocumentdefination', 'OnlineServicesConfigController@getApplicationdocumentdefination');
//     Route::get('getOnlineProcessTransitionsdetails', 'OnlineServicesConfigController@getOnlineProcessTransitionsdetails');
   
//     Route::get('getapplicationstatus', 'OnlineServicesConfigController@getapplicationstatus');
//     Route::get('getApplicationprocessguidelines', 'OnlineServicesConfigController@getApplicationprocessguidelines');
//     Route::post('deleteSystemMenuItem','OnlineServicesConfigController@deleteSystemMenuItem');
//     Route::get('getOnlineProcesdetails','OnlineServicesConfigController@getOnlineProcesdetails');
//     Route::post('deleteSystemProcess','OnlineServicesConfigController@deleteSystemProcess');
//     Route::get('getOnlineFormsParams','OnlineServicesConfigController@getOnlineFormsParams');



    
// });
