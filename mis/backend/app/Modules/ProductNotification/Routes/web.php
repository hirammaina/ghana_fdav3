<?php

use App\Modules\ProductNotification\Http\Controllers\ProductNotificationController;
use Illuminate\Support\Facades\Route;


    Route::prefix('productnotification')->group(function () {
        Route::controller(ProductNotificationController::class)->group(function () {
            Route::get('getOnlineApplications', 'getOnlineApplications');
    Route::get('prepareOnlineProductNotReceivingStage', 'prepareOnlineProductNotReceivingStage');
    Route::get('onLoadOnlineproductNotificationManufacturer', 'onLoadOnlineproductNotificationManufacturer');
    Route::get('getMedicalDevicesNotificationapps', 'getMedicalDevicesNotificationapps');
    
    Route::get('onLoadproductManufacturer', 'onLoadproductManufacturer');
    Route::get('prepareProductNotificationReceivingStage', 'prepareProductNotificationReceivingStage');
    Route::get('prepareMedicaldevicesUniformStage', 'prepareMedicaldevicesUniformStage');
    
    
    Route::post('saveProductNotificationReceivingBaseDetails', 'saveProductNotificationReceivingBaseDetails');
   
    Route::post('onSaveProductNotificationinformation', 'onSaveProductNotificationinformation');
    Route::get('getProductNotificationDetails', 'getProductNotificationDetails');
    
    
    Route::get('getManagerEvaluationApplications', 'getManagerEvaluationApplications');
    Route::get('getProductNotificationApprovalApplications', 'getProductNotificationApprovalApplications');

    Route::post('saveOnlineProductNotificationReceiving', 'saveOnlineProductNotificationReceiving');
   
   Route::get('ScheduledExpiryNotificationScript', 'ScheduledExpiryNotificationScript');
        
        
    
        });

    });


// Route::group([ 'prefix' => 'productnotification', 'namespace' => 'App\\Modules\ProductNotification\Http\Controllers'], function()
// {
//     Route::get('getOnlineApplications', 'ProductNotificationController@getOnlineApplications');
//     Route::get('prepareOnlineProductNotReceivingStage', 'ProductNotificationController@prepareOnlineProductNotReceivingStage');
//     Route::get('onLoadOnlineproductNotificationManufacturer', 'ProductNotificationController@onLoadOnlineproductNotificationManufacturer');
//     Route::get('getMedicalDevicesNotificationapps', 'ProductNotificationController@getMedicalDevicesNotificationapps');
    
//     Route::get('onLoadproductManufacturer', 'ProductNotificationController@onLoadproductManufacturer');
//     Route::get('prepareProductNotificationReceivingStage', 'ProductNotificationController@prepareProductNotificationReceivingStage');
//     Route::get('prepareMedicaldevicesUniformStage', 'ProductNotificationController@prepareMedicaldevicesUniformStage');
    
    
//     Route::post('saveProductNotificationReceivingBaseDetails', 'ProductNotificationController@saveProductNotificationReceivingBaseDetails');
   
//     Route::post('onSaveProductNotificationinformation', 'ProductNotificationController@onSaveProductNotificationinformation');
//     Route::get('getProductNotificationDetails', 'ProductNotificationController@getProductNotificationDetails');
    
    
//     Route::get('getManagerEvaluationApplications', 'ProductNotificationController@getManagerEvaluationApplications');
//     Route::get('getProductNotificationApprovalApplications', 'ProductNotificationController@getProductNotificationApprovalApplications');

//     Route::post('saveOnlineProductNotificationReceiving', 'ProductNotificationController@saveOnlineProductNotificationReceiving');
   
//    Route::get('ScheduledExpiryNotificationScript', 'ProductNotificationController@ScheduledExpiryNotificationScript');
   
// });
