<?php

use App\Modules\Systemadminprocess\Http\Controllers\SystemadminprocessController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('systemadminprocess')->group(function () {
        Route::controller(SystemadminprocessController::class)->group(function () {
            Route::get('getChangemarketAuthorisationdetails', 'getChangemarketAuthorisationdetails');
    Route::get('getMarketauthorisationProducts', 'getMarketauthorisationProducts');
    Route::post('saveChangemarketAuthorisationdetails', 'saveChangemarketAuthorisationdetails');
    Route::post('saveChangeLocalTechnicalRepresentative', 'saveChangeLocalTechnicalRepresentative');
    Route::get('getChangeLocalTechnicalRepresentative', 'getChangeLocalTechnicalRepresentative');
    Route::get('getApplicationOwnershipAmmendmentsdata', 'getApplicationOwnershipAmmendmentsdata');
    Route::post('saveApplicationownershipammendmentsDetails', 'saveApplicationownershipammendmentsDetails');
    Route::get('getappCertificateReupdateRequests', 'getappCertificateReupdateRequests');
        
        
    
        });

    });
});

// Route::group(['middleware' => 'auth:api', 'prefix' => 'systemadminprocess', 'namespace' => 'App\\Modules\Systemadminprocess\Http\Controllers'], function()
// {
//     Route::get('getChangemarketAuthorisationdetails', 'SystemadminprocessController@getChangemarketAuthorisationdetails');
//     Route::get('getMarketauthorisationProducts', 'SystemadminprocessController@getMarketauthorisationProducts');
//     Route::post('saveChangemarketAuthorisationdetails', 'SystemadminprocessController@saveChangemarketAuthorisationdetails');
//     Route::post('saveChangeLocalTechnicalRepresentative', 'SystemadminprocessController@saveChangeLocalTechnicalRepresentative');
//     Route::get('getChangeLocalTechnicalRepresentative', 'SystemadminprocessController@getChangeLocalTechnicalRepresentative');
//     Route::get('getApplicationOwnershipAmmendmentsdata', 'SystemadminprocessController@getApplicationOwnershipAmmendmentsdata');
//     Route::post('saveApplicationownershipammendmentsDetails', 'SystemadminprocessController@saveApplicationownershipammendmentsDetails');
//     Route::get('getappCertificateReupdateRequests', 'SystemadminprocessController@getappCertificateReupdateRequests');
 
    
    
// });
