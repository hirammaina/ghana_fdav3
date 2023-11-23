<?php

use App\Modules\MobileApp\Http\Controllers\MobileAppController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('mobileapp')->group(function () {
        Route::controller(MobileAppController::class)->group(function () {
           //reusable routes
	Route::get('getCommonParamFromTable', 'getCommonParamFromTable');
    Route::get('getFromTable', 'getFromTable');
    Route::post('saveToTable', 'saveToTable');

    //specific routes
	Route::get('getPremiseApplications', 'getPremiseApplications');
	Route::get('getInspectionRecommendations', 'getInspectionRecommendations');
	Route::get('getInspectionDetails', 'getInspectionDetails');
	Route::get('checkpremisdata', 'checkpremisdata');
	Route::get('getpremisesProd', 'getpremisesProd');
	Route::get('getpremisesProducts', 'getpremisesProducts');
	Route::get('checkPoeApplication', 'checkPoeApplication');
    Route::get('getAgentDetails', 'getAgentDetails');
	Route::post('authenticateMisMobileUser', 'authenticateMisMobileUser');
	Route::post('saveInspectionRecommendation', 'saveInspectionRecommendation');
    Route::post('getImportPermitDetails', 'getImportPermitDetails');
	Route::post('saveApplicationPoeProductDetails', 'saveApplicationPoeProductDetails');
	Route::post('submitpoe', 'submitpoe');
        
        
    
        });

    });
});

// Route::group(['middleware' => 'web', 'prefix' => 'mobileapp', 'namespace' => 'App\\Modules\MobileApp\Http\Controllers'], function()
// {
// 	//reusable routes
// 	Route::get('getCommonParamFromTable', 'MobileAppController@getCommonParamFromTable');
//     Route::get('getFromTable', 'MobileAppController@getFromTable');
//     Route::post('saveToTable', 'MobileAppController@saveToTable');

//     //specific routes
// 	Route::get('getPremiseApplications', 'MobileAppController@getPremiseApplications');
// 	Route::get('getInspectionRecommendations', 'MobileAppController@getInspectionRecommendations');
// 	Route::get('getInspectionDetails', 'MobileAppController@getInspectionDetails');
// 	Route::get('checkpremisdata', 'MobileAppController@checkpremisdata');
// 	Route::get('getpremisesProd', 'MobileAppController@getpremisesProd');
// 	Route::get('getpremisesProducts', 'MobileAppController@getpremisesProducts');
// 	Route::get('checkPoeApplication', 'MobileAppController@checkPoeApplication');
//     Route::get('getAgentDetails', 'MobileAppController@getAgentDetails');
// 	Route::post('authenticateMisMobileUser', 'MobileAppController@authenticateMisMobileUser');
// 	Route::post('saveInspectionRecommendation', 'MobileAppController@saveInspectionRecommendation');
//     Route::post('getImportPermitDetails', 'MobileAppController@getImportPermitDetails');
// 	Route::post('saveApplicationPoeProductDetails', 'MobileAppController@saveApplicationPoeProductDetails');
// 	Route::post('submitpoe', 'MobileAppController@submitpoe');
// });
