<?php

use App\Modules\Surveillance\Http\Controllers\SurveillanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('surveillance')->group(function () {
        Route::controller(SurveillanceController::class)->group(function () {
            Route::get('/', 'index');
        
    
        });

    });
});



Route::middleware(['web'])->group( function () {
    Route::prefix('surveillance')->group(function () {
        Route::controller(SurveillanceController::class)->group(function () {
            Route::post('saveSurveillanceCommonData', 'saveSurveillanceCommonData');
    Route::post('saveSurveillancePlansDetailsCommonData', 'saveSurveillancePlansDetailsCommonData');

    
    Route::post('deleteSurveillanceRecord', 'deleteSurveillanceRecord');

    Route::post('savePmsProgramRegions', 'savePmsProgramRegions');
    Route::post('savePmsProgramSamplingSite', 'savePmsProgramSamplingSite');
    Route::post('savePmsProgramProducts', 'savePmsProgramProducts');

    Route::get('getPmsProgramRegions', 'getPmsProgramRegions');
    Route::get('getPmsProgramSamplingSites', 'getPmsProgramSamplingSites');
    Route::get('getPmsProgramSamplingSitesLevels', 'getPmsProgramSamplingSitesLevels');

    
    Route::get('getPmsProgramProducts', 'getPmsProgramProducts');
    Route::get('getPmsPrograms', 'getPmsPrograms');

    Route::get('getPmsProgramsImplementation', 'getPmsProgramsImplementation');
    Route::get('getPmsProgramsImplementationDetails', 'getPmsProgramsImplementationDetails');

    
    Route::get('getAppSelectionPmsPrograms', 'getAppSelectionPmsPrograms');

    
    Route::get('getPmsProgramPlans', 'getPmsProgramPlans');
    Route::get('getSurveillanceApplications', 'getSurveillanceApplications');
    Route::post('saveNewReceivingBaseDetails', 'saveNewReceivingBaseDetails');
    //start prepare
    Route::get('prepareStructuredPmsReceivingStage', 'prepareStructuredPmsReceivingStage');
    Route::get('prepareStructuredPmsTCMeetingStage', 'prepareStructuredPmsTCMeetingStage');
    //end prepare
    Route::post('saveSurveillanceSampleDetails', 'saveSurveillanceSampleDetails');
    Route::get('getPmsApplicationSamplesReceiving', 'getPmsApplicationSamplesReceiving');
    Route::get('getPmsApplicationSamplesLabStages', 'getPmsApplicationSamplesLabStages');
    Route::get('getPmsApplicationSamplesApprovalStages', 'getPmsApplicationSamplesApprovalStages');
    Route::get('getManagerApplicationsGeneric', 'getManagerApplicationsGeneric');
    Route::get('getPmsApplicationMoreDetails', 'getPmsApplicationMoreDetails');
    Route::post('savePmsPIRRecommendation', 'savePmsPIRRecommendation');
    Route::get('getPmsSampleIngredients', 'getPmsSampleIngredients');
    Route::get('getSampleLabAnalysisResults', 'getSampleLabAnalysisResults');
    Route::post('saveTCMeetingDetails', 'saveTCMeetingDetails');
    Route::get('getSurveillanceSampleDetails', 'getSurveillanceSampleDetails');
    Route::get('getDismissedSurveillanceApplications', 'getDismissedSurveillanceApplications');

    Route::post('processReturnBackApplicationSubmission', 'processReturnBackApplicationSubmission');
    Route::get('getPmsPremisesList', 'getPmsPremisesList');
    Route::get('getGroupSampleAnalysisDetails', 'getGroupSampleAnalysisDetails');
        
        
    
        });

    });
});



// Route::group(['middleware' => 'web', 'prefix' => 'surveillance', 'namespace' => 'App\\Modules\Surveillance\Http\Controllers'], function()
// {
//     Route::get('/', 'SurveillanceController@index');
// });

// //API Routes
// Route::group(['middleware' => 'auth:api', 'prefix' => 'surveillance', 'namespace' => 'App\\Modules\Surveillance\Http\Controllers'], function () {
//     Route::post('saveSurveillanceCommonData', 'SurveillanceController@saveSurveillanceCommonData');
//     Route::post('saveSurveillancePlansDetailsCommonData', 'SurveillanceController@saveSurveillancePlansDetailsCommonData');

    
//     Route::post('deleteSurveillanceRecord', 'SurveillanceController@deleteSurveillanceRecord');

//     Route::post('savePmsProgramRegions', 'SurveillanceController@savePmsProgramRegions');
//     Route::post('savePmsProgramSamplingSite', 'SurveillanceController@savePmsProgramSamplingSite');
//     Route::post('savePmsProgramProducts', 'SurveillanceController@savePmsProgramProducts');

//     Route::get('getPmsProgramRegions', 'SurveillanceController@getPmsProgramRegions');
//     Route::get('getPmsProgramSamplingSites', 'SurveillanceController@getPmsProgramSamplingSites');
//     Route::get('getPmsProgramSamplingSitesLevels', 'SurveillanceController@getPmsProgramSamplingSitesLevels');

    
//     Route::get('getPmsProgramProducts', 'SurveillanceController@getPmsProgramProducts');
//     Route::get('getPmsPrograms', 'SurveillanceController@getPmsPrograms');

//     Route::get('getPmsProgramsImplementation', 'SurveillanceController@getPmsProgramsImplementation');
//     Route::get('getPmsProgramsImplementationDetails', 'SurveillanceController@getPmsProgramsImplementationDetails');

    
//     Route::get('getAppSelectionPmsPrograms', 'SurveillanceController@getAppSelectionPmsPrograms');

    
//     Route::get('getPmsProgramPlans', 'SurveillanceController@getPmsProgramPlans');
//     Route::get('getSurveillanceApplications', 'SurveillanceController@getSurveillanceApplications');
//     Route::post('saveNewReceivingBaseDetails', 'SurveillanceController@saveNewReceivingBaseDetails');
//     //start prepare
//     Route::get('prepareStructuredPmsReceivingStage', 'SurveillanceController@prepareStructuredPmsReceivingStage');
//     Route::get('prepareStructuredPmsTCMeetingStage', 'SurveillanceController@prepareStructuredPmsTCMeetingStage');
//     //end prepare
//     Route::post('saveSurveillanceSampleDetails', 'SurveillanceController@saveSurveillanceSampleDetails');
//     Route::get('getPmsApplicationSamplesReceiving', 'SurveillanceController@getPmsApplicationSamplesReceiving');
//     Route::get('getPmsApplicationSamplesLabStages', 'SurveillanceController@getPmsApplicationSamplesLabStages');
//     Route::get('getPmsApplicationSamplesApprovalStages', 'SurveillanceController@getPmsApplicationSamplesApprovalStages');
//     Route::get('getManagerApplicationsGeneric', 'SurveillanceController@getManagerApplicationsGeneric');
//     Route::get('getPmsApplicationMoreDetails', 'SurveillanceController@getPmsApplicationMoreDetails');
//     Route::post('savePmsPIRRecommendation', 'SurveillanceController@savePmsPIRRecommendation');
//     Route::get('getPmsSampleIngredients', 'SurveillanceController@getPmsSampleIngredients');
//     Route::get('getSampleLabAnalysisResults', 'SurveillanceController@getSampleLabAnalysisResults');
//     Route::post('saveTCMeetingDetails', 'SurveillanceController@saveTCMeetingDetails');
//     Route::get('getSurveillanceSampleDetails', 'SurveillanceController@getSurveillanceSampleDetails');
//     Route::get('getDismissedSurveillanceApplications', 'SurveillanceController@getDismissedSurveillanceApplications');

//     Route::post('processReturnBackApplicationSubmission', 'SurveillanceController@processReturnBackApplicationSubmission');
//     Route::get('getPmsPremisesList', 'SurveillanceController@getPmsPremisesList');
//     Route::get('getGroupSampleAnalysisDetails', 'SurveillanceController@getGroupSampleAnalysisDetails');

    
// });

