<?php

use App\Modules\MigrationScripts\Http\Controllers\MigrationScriptsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('migrationscripts')->group(function () {
        Route::controller(MigrationScriptsController::class)->group(function () {
          Route::get('/', 'index');
          Route::get('initiatVetemigrateNewProductsDetails','initiatVetemigrateNewProductsDetails');
          Route::get('initiatNewMedicinesProductsemigrateDetails','initiatNewMedicinesProductsemigrateDetails');
        
         Route::get('initiatNewMedicaldevicesProductsemigrateDetails','initiatNewMedicaldevicesProductsemigrateDetails');
          Route::get('testemail','testemail');
      
           Route::get('initiatemappingProductRegistrationSubmission','initiatemappingProductRegistrationSubmission');
      Route::get('initiatTobaccoProductsemigrateDetails','initiatTobaccoProductsemigrateDetails');
           
           Route::get('initiatNewFoodProductsemigrateDetails','initiatNewFoodProductsemigrateDetails');
           Route::get('initiatNewCosmeticsProductsemigrateDetails','initiatNewCosmeticsProductsemigrateDetails');
         
           Route::get('initiatemigrateClinicalTrialDatasets','initiatemigrateClinicalTrialDatasets');
           Route::get('initiatemigratePromotionalDatasets','initiatemigratePromotionalDatasets');
      Route::get('initiatemappingClincialTrialRegistrationSubmission','initiatemappingClincialTrialRegistrationSubmission');
      Route::get('initiatePremisesDataMapping','initiatePremisesDataMapping');
        
          Route::get('initiateGmpRegistrationMigration','initiateGmpRegistrationMigration');
          Route::get('initiateMedProductAuthorisationMigration','initiateMedProductAuthorisationMigration');
         Route::get('initiatevetProductAuthorisationMigration','initiatevetProductAuthorisationMigration');
         Route::get('initiateCosmeticsProductAuthorisationMigration','initiateCosmeticsProductAuthorisationMigration');
         
         Route::get('initiateFoodPremisesDataMapping','initiateFoodPremisesDataMapping');
        //migration_food_premises
        Route::get('initiatemappingPremisesSubmission','initiatemappingPremisesSubmission');
        
        
        Route::get('getAppdataMigrationRequests','getAppdataMigrationRequests');
      Route::get('getProductAppdataMigrationDetails','getProductAppdataMigrationDetails');
        
      Route::get('getAppDataMigrationsGridColumnsConfig','getAppDataMigrationsGridColumnsConfig');
        
      Route::get('downloadappdatamigrationuploadTemplate','downloadappdatamigrationuploadTemplate');
      Route::post('saveappdatamigrationuploads','saveappdatamigrationuploads');
      
      Route::post('deleteApplicationMigratedDataSets','deleteApplicationMigratedDataSets');
      Route::post('synApplicationMigratedDataSets','synApplicationMigratedDataSets');
      Route::get('getParameterFormColumnsConfig','getParameterFormColumnsConfig');
      Route::get('mapNewWorkflowfromExisting','mapNewWorkflowfromExisting');
      
      
      Route::get('remapProductAuthorisationManuacturer','remapProductAuthorisationManuacturer');
      Route::get('remapImportApplicationsToPortal','remapImportApplicationsToPortal');
        
        
          Route::get('initiatemappingGmpSubmission','initiatemappingGmpSubmission');
        Route::get('initiatemappingPromotionsSubmission','initiatemappingPromotionsSubmission');
        
       Route::get('initiateMedProductDatabaseMigration','initiateMedProductDatabaseMigration');
        
        
    
        });

    });
});

// Route::group([ 'prefix' => 'migrationscripts', 'namespace' => 'App\\Modules\MigrationScripts\Http\Controllers'], function()
// {
//     Route::get('/', 'MigrationScriptsController@index');
//     Route::get('initiatVetemigrateNewProductsDetails','MigrationScriptsController@initiatVetemigrateNewProductsDetails');
//     Route::get('initiatNewMedicinesProductsemigrateDetails','MigrationScriptsController@initiatNewMedicinesProductsemigrateDetails');
	
// 	 Route::get('initiatNewMedicaldevicesProductsemigrateDetails','MigrationScriptsController@initiatNewMedicaldevicesProductsemigrateDetails');
//     Route::get('testemail','MigrationScriptsController@testemail');

//      Route::get('initiatemappingProductRegistrationSubmission','MigrationScriptsController@initiatemappingProductRegistrationSubmission');
// Route::get('initiatTobaccoProductsemigrateDetails','MigrationScriptsController@initiatTobaccoProductsemigrateDetails');
     
//      Route::get('initiatNewFoodProductsemigrateDetails','MigrationScriptsController@initiatNewFoodProductsemigrateDetails');
//      Route::get('initiatNewCosmeticsProductsemigrateDetails','MigrationScriptsController@initiatNewCosmeticsProductsemigrateDetails');
	 
//      Route::get('initiatemigrateClinicalTrialDatasets','MigrationScriptsController@initiatemigrateClinicalTrialDatasets');
//      Route::get('initiatemigratePromotionalDatasets','MigrationScriptsController@initiatemigratePromotionalDatasets');
// Route::get('initiatemappingClincialTrialRegistrationSubmission','MigrationScriptsController@initiatemappingClincialTrialRegistrationSubmission');
// Route::get('initiatePremisesDataMapping','MigrationScriptsController@initiatePremisesDataMapping');
  
//     Route::get('initiateGmpRegistrationMigration','MigrationScriptsController@initiateGmpRegistrationMigration');
//     Route::get('initiateMedProductAuthorisationMigration','MigrationScriptsController@initiateMedProductAuthorisationMigration');
//    Route::get('initiatevetProductAuthorisationMigration','MigrationScriptsController@initiatevetProductAuthorisationMigration');
//    Route::get('initiateCosmeticsProductAuthorisationMigration','MigrationScriptsController@initiateCosmeticsProductAuthorisationMigration');
   
//    Route::get('initiateFoodPremisesDataMapping','MigrationScriptsController@initiateFoodPremisesDataMapping');
//   //migration_food_premises
//   Route::get('initiatemappingPremisesSubmission','MigrationScriptsController@initiatemappingPremisesSubmission');
  
  
//   Route::get('getAppdataMigrationRequests','MigrationScriptsController@getAppdataMigrationRequests');
// Route::get('getProductAppdataMigrationDetails','MigrationScriptsController@getProductAppdataMigrationDetails');
  
// Route::get('getAppDataMigrationsGridColumnsConfig','MigrationScriptsController@getAppDataMigrationsGridColumnsConfig');
  
// Route::get('downloadappdatamigrationuploadTemplate','MigrationScriptsController@downloadappdatamigrationuploadTemplate');
// Route::post('saveappdatamigrationuploads','MigrationScriptsController@saveappdatamigrationuploads');

// Route::post('deleteApplicationMigratedDataSets','MigrationScriptsController@deleteApplicationMigratedDataSets');
// Route::post('synApplicationMigratedDataSets','MigrationScriptsController@synApplicationMigratedDataSets');
// Route::get('getParameterFormColumnsConfig','MigrationScriptsController@getParameterFormColumnsConfig');
// Route::get('mapNewWorkflowfromExisting','MigrationScriptsController@mapNewWorkflowfromExisting');


// Route::get('remapProductAuthorisationManuacturer','MigrationScriptsController@remapProductAuthorisationManuacturer');
// Route::get('remapImportApplicationsToPortal','MigrationScriptsController@remapImportApplicationsToPortal');
  
  
//     Route::get('initiatemappingGmpSubmission','MigrationScriptsController@initiatemappingGmpSubmission');
//   Route::get('initiatemappingPromotionsSubmission','MigrationScriptsController@initiatemappingPromotionsSubmission');
  
//  Route::get('initiateMedProductDatabaseMigration','MigrationScriptsController@initiateMedProductDatabaseMigration');
// });
