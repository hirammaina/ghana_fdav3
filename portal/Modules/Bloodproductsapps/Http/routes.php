<?php

Route::group(['middleware' => 'auth:api', 'prefix' => 'bloodproductsapps', 'namespace' => 'Modules\Bloodproductsapps\Http\Controllers'], function()
{
    Route::get('/', 'BloodproductsappsController@index');

    
    Route::get('getBloodEstabishmentApplicationsLoading', 'BloodproductsappsController@getBloodEstabishmentApplicationsLoading');
    Route::post('saveBloodEstabishmentApplications', 'BloodproductsappsController@saveBloodEstabishmentApplications');
    Route::post('saveDisposalPermitProductdetails', 'BloodproductsappsController@saveDisposalPermitProductdetails');
    
    Route::get('getAllRegisteredNonRedProducts', 'BloodproductsappsController@getAllRegisteredNonRedProducts');
    Route::get('getDisposalPermitProductsDetails', 'BloodproductsappsController@getDisposalPermitProductsDetails');
    
});
