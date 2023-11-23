<?php

use App\Modules\SampleAnalysis\Http\Controllers\SampleAnalysisController;
use App\Modules\SampleAnalysis\Http\Controllers\SampleAnalysisRptController;
use Illuminate\Support\Facades\Route;


    Route::prefix('sampleanalysis')->group(function () {
        Route::controller(SampleAnalysisRptController::class)->group(function () {
            Route::get('printSampleTestRequestReview', 'printSampleTestRequestReview');
        
        
    
        });

    });




Route::middleware(['auth:api'])->group( function () {
    Route::prefix('sampleanalysis')->group(function () {
        Route::controller(SampleAnalysisController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('getsampleanalysistestrequests', 'getsampleanalysistestrequests');
            Route::get('getSampleAnalyisParameter', 'getSampleAnalyisParameter');
            Route::get('getsampleanalysistestParameters', 'getsampleanalysistestParameters');
            Route::get('getTestParametersDetails', 'getTestParametersDetails');
            Route::get('getCostSubCategoryParameter', 'getCostSubCategoryParameter');
            Route::get('getSampleanalysistestrequestsprocesses', 'getSampleanalysistestrequestsprocesses');
            Route::get('getsampleanalysistestAnalysisResults', 'getsampleanalysistestAnalysisResults');
            Route::post('saveSampleAnalysisRequestdetails', 'saveSampleAnalysisRequestdetails');
            Route::post('funcAddSampleTestParameters', 'funcAddSampleTestParameters');
            Route::post('onDeleteLabSampleOtherDetails', 'onDeleteLabSampleOtherDetails');
            Route::post('funcSampleApplicationSubmissionWin', 'funcSampleApplicationSubmissionWin');
            Route::get('getLimsSampleIngredients', 'getLimsSampleIngredients');
            Route::get('prepareLabServicesSamplePaymentPanel', 'prepareLabServicesSamplePaymentPanel');
            Route::get('submitRegistrationToNextStage', 'submitRegistrationToNextStage');
        
        
        
    
        });

    });
});


// Route::group(['prefix' => 'sampleanalysis', 'namespace' => 'App\\Modules\SampleAnalysis\Http\Controllers'], function()
// {
//     Route::get('printSampleTestRequestReview', 'SampleAnalysisRptController@printSampleTestRequestReview');
    
// });
// Route::group(['middleware' => 'auth:api','prefix' => 'sampleanalysis', 'namespace' => 'App\\Modules\SampleAnalysis\Http\Controllers'], function()
// {
//     Route::get('/', 'SampleAnalysisController@index');
//     Route::get('getsampleanalysistestrequests', 'SampleAnalysisController@getsampleanalysistestrequests');
//     Route::get('getSampleAnalyisParameter', 'SampleAnalysisController@getSampleAnalyisParameter');
//     Route::get('getsampleanalysistestParameters', 'SampleAnalysisController@getsampleanalysistestParameters');
//     Route::get('getTestParametersDetails', 'SampleAnalysisController@getTestParametersDetails');
//     Route::get('getCostSubCategoryParameter', 'SampleAnalysisController@getCostSubCategoryParameter');
//     Route::get('getSampleanalysistestrequestsprocesses', 'SampleAnalysisController@getSampleanalysistestrequestsprocesses');
//     Route::get('getsampleanalysistestAnalysisResults', 'SampleAnalysisController@getsampleanalysistestAnalysisResults');
//     Route::post('saveSampleAnalysisRequestdetails', 'SampleAnalysisController@saveSampleAnalysisRequestdetails');
//     Route::post('funcAddSampleTestParameters', 'SampleAnalysisController@funcAddSampleTestParameters');
//     Route::post('onDeleteLabSampleOtherDetails', 'SampleAnalysisController@onDeleteLabSampleOtherDetails');
//     Route::post('funcSampleApplicationSubmissionWin', 'SampleAnalysisController@funcSampleApplicationSubmissionWin');
//     Route::get('getLimsSampleIngredients', 'SampleAnalysisController@getLimsSampleIngredients');
//     Route::get('prepareLabServicesSamplePaymentPanel', 'SampleAnalysisController@prepareLabServicesSamplePaymentPanel');
//     Route::get('submitRegistrationToNextStage', 'SampleAnalysisController@submitRegistrationToNextStage');

    
// });
