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
use Modules\MigrationScripts\Http\Controllers\MigrationScriptsController;
Route::group(['middleware' => 'web', 'prefix' => 'migrationscripts'], function () {

   Route::get('/', [MigrationScriptsController::class, 'index']);
 

    Route::get('initiatVetemigrateNewProductsDetails',[MigrationScriptsController::class, 'initiatVetemigrateNewProductsDetails']);
    Route::get('initiatNewMedicinesProductsemigrateDetails',[MigrationScriptsController::class, 'initiatNewMedicinesProductsemigrateDetails']);
    
     Route::get('initiatNewMedicaldevicesProductsemigrateDetails',[MigrationScriptsController::class, 'initiatNewMedicaldevicesProductsemigrateDetails']);
    Route::get('testemail',[MigrationScriptsController::class, 'testemail']);

     Route::get('initiatemappingProductRegistrationSubmission',[MigrationScriptsController::class, 'initiatemappingProductRegistrationSubmission']);
   Route::get('initiatTobaccoProductsemigrateDetails',[MigrationScriptsController::class, 'initiatTobaccoProductsemigrateDetails']);
     
     Route::get('initiatNewFoodProductsemigrateDetails',[MigrationScriptsController::class, 'initiatNewFoodProductsemigrateDetails']);
     Route::get('initiatNewCosmeticsProductsemigrateDetails',[MigrationScriptsController::class, 'initiatNewCosmeticsProductsemigrateDetails']);
     
     Route::get('initiatemigrateClinicalTrialDatasets',[MigrationScriptsController::class, 'initiatemigrateClinicalTrialDatasets']);
     Route::get('initiatemigratePromotionalDatasets',[MigrationScriptsController::class, 'initiatemigratePromotionalDatasets']);
    Route::get('initiatemappingClincialTrialRegistrationSubmission',[MigrationScriptsController::class, 'initiatemappingClincialTrialRegistrationSubmission']);
    Route::get('initiatePremisesDataMapping',[MigrationScriptsController::class, 'initiatePremisesDataMapping']);
  
    Route::get('initiateGmpRegistrationMigration',[MigrationScriptsController::class, 'initiateGmpRegistrationMigration']);
    Route::get('initiateMedProductAuthorisationMigration',[MigrationScriptsController::class, 'initiateMedProductAuthorisationMigration']);
   Route::get('initiatevetProductAuthorisationMigration',[MigrationScriptsController::class, 'initiatevetProductAuthorisationMigration']);
   Route::get('initiateCosmeticsProductAuthorisationMigration',[MigrationScriptsController::class, 'initiateCosmeticsProductAuthorisationMigration']);
   
   Route::get('initiateFoodPremisesDataMapping',[MigrationScriptsController::class, 'initiateFoodPremisesDataMapping']);
  //migration_food_premises
  Route::get('initiatemappingPremisesSubmission',[MigrationScriptsController::class, 'initiatemappingPremisesSubmission']);
  
  
  Route::get('getAppdataMigrationRequests',[MigrationScriptsController::class, 'getAppdataMigrationRequests']);
Route::get('getProductAppdataMigrationDetails',[MigrationScriptsController::class, 'getProductAppdataMigrationDetails']);
  
Route::get('getAppDataMigrationsGridColumnsConfig',[MigrationScriptsController::class, 'getAppDataMigrationsGridColumnsConfig']);
  
Route::get('downloadappdatamigrationuploadTemplate',[MigrationScriptsController::class, 'downloadappdatamigrationuploadTemplate']);
Route::post('saveappdatamigrationuploads',[MigrationScriptsController::class, 'saveappdatamigrationuploads']);

Route::post('deleteApplicationMigratedDataSets',[MigrationScriptsController::class, 'deleteApplicationMigratedDataSets']);
Route::post('synApplicationMigratedDataSets',[MigrationScriptsController::class, 'synApplicationMigratedDataSets']);
Route::get('getParameterFormColumnsConfig',[MigrationScriptsController::class, 'getParameterFormColumnsConfig']);
Route::get('mapNewWorkflowfromExisting',[MigrationScriptsController::class, 'mapNewWorkflowfromExisting']);


Route::get('remapProductAuthorisationManuacturer',[MigrationScriptsController::class, 'remapProductAuthorisationManuacturer']);
Route::get('remapImportApplicationsToPortal',[MigrationScriptsController::class, 'remapImportApplicationsToPortal']);
  
  
    Route::get('initiatemappingGmpSubmission',[MigrationScriptsController::class, 'initiatemappingGmpSubmission']);
  Route::get('initiatemappingPromotionsSubmission',[MigrationScriptsController::class, 'initiatemappingPromotionsSubmission']);
  
 Route::get('initiateMedProductDatabaseMigration',[MigrationScriptsController::class, 'initiateMedProductDatabaseMigration']);
});