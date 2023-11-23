<?php

use App\Modules\Systemnotifications\Http\Controllers\SystemnotificationsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('systemnotifications')->group(function () {
        Route::controller(SystemnotificationsController::class)->group(function () {
            Route::get('/', 'index');
    Route::get('submitInvoiceNotifications', 'submitInvoiceNotifications');
    
    Route::get('submitReceiptNotifications', 'submitReceiptNotifications');
    
    Route::get('submitProductExpiryDueNotification', 'submitProductExpiryDueNotification');
        
        
    
        });

    });
});


// Route::group(['prefix' => 'systemnotifications', 'namespace' => 'App\\Modules\Systemnotifications\Http\Controllers'], function()
// {
//     Route::get('/', 'SystemnotificationsController@index');
//     Route::get('submitInvoiceNotifications', 'SystemnotificationsController@submitInvoiceNotifications');
    
//     Route::get('submitReceiptNotifications', 'SystemnotificationsController@submitReceiptNotifications');
    
//     Route::get('submitProductExpiryDueNotification', 'SystemnotificationsController@submitProductExpiryDueNotification');
// });
