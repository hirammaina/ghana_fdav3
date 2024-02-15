<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Modules\Administration\Http\Controllers\AdministrationController;

Route::group(['prefix' => 'administration'], function () {

    Route::get('getUsers', [AdministrationController::class, 'getUsers']);
    Route::post('onUserLogOut', [AdministrationController::class, 'onUserLogOut']);
    Route::get('onApplicationInitialisation', [AdministrationController::class, 'onApplicationInitialisation']);
});
