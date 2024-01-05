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
use Modules\PersonnelManagement\Http\Controllers\PersonnelManagementController;
Route::group(['prefix' => 'personnelmanagement','middleware' => ['web']], function() {
    Route::post('savePersonnelImage', [PersonnelManagementController::class, 'savePersonnelImage']);
    Route::post('savePersonnelInformation', [PersonnelManagementController::class, 'savePersonnelInformation']);

    Route::post('savePvPersonnelInformation', [PersonnelManagementController::class, 'savePvPersonnelInformation']);

    Route::get('getDrugShopInchargesDetails', [PersonnelManagementController::class, 'getDrugShopInchargesDetails']);
    Route::post('deletePersonelRecord', [PersonnelManagementController::class, 'deletePersonnelRecord']);
    Route::post('activatePersonelRecord', [PersonnelManagementController::class, 'activatePersonelRecord']);
    Route::post('deactivatePersonnel', [PersonnelManagementController::class, 'deactivatePersonnel']);

     Route::post('deactivatePvPersonnel', [PersonnelManagementController::class, 'deactivatePvPersonnel']);

    Route::get('getPvPersonnelDetails', [PersonnelManagementController::class, 'getPvPersonnelDetails']);
});

