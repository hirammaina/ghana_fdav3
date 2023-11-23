<?php
Route::group(['prefix' => 'authentication', 'namespace' => 'Modules\Authentication\Http\Controllers'], function()
{
    Route::post('onUserLogin', 'AuthenticationController@onUserLogin');
    Route::post('onAdminlogin', 'AuthenticationController@onAdminlogin');

    
    Route::post('onFuncRecoverPasswordRequest', 'AuthenticationController@onFuncRecoverPasswordRequest');

    
    //get requests
    Route::get('onRecoverAccountPassword', 'AuthenticationController@onRecoverAccountPassword');

});
Route::group(['middleware' =>'auth:api', 'prefix' => 'authentication', 'namespace' => 'Modules\Authentication\Http\Controllers'], function()
{
     Route::post('onFuncChangePassword', 'AuthenticationController@onFuncChangePassword');
  
});