<?php

Route::group(['middleware' => 'auth:api', 'prefix' => 'administration', 'namespace' => 'Modules\Administration\Http\Controllers'], function()
{
    Route::get('getUsers', 'AdministrationController@getUsers');
    Route::post('onUserLogOut', 'AdministrationController@onUserLogOut');
    Route::get('onApplicationInitialisation', 'AdministrationController@onApplicationInitialisation');

});