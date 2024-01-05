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

use Modules\Pharmacovigilancereporting\Http\Controllers\PharmacovigilanceReportingController;
use Modules\Psur\Http\Controllers\PsurController;
Route::group(['middleware' => ['auth:api', 'web'], 'prefix' => 'pharmacovigilancereporting'], function()
{
    Route::get('/', [PharmacovigilanceReportingController::class, 'index']);

    Route::post('saveReceivingSafetyAlertReportsDetails', [PharmacovigilanceReportingController::class,'saveReceivingSafetyAlertReportsDetails']);
    Route::get('getPharmacoVigilanceApps', [PharmacovigilanceReportingController::class,'getPharmacoVigilanceApps']);
    Route::get('prepareReceivingSafetyAlertReportsStage', [PharmacovigilanceReportingController::class,'prepareReceivingSafetyAlertReportsStage']);
    Route::get('getPharmacoVigilancerRptManagerApplicationsGeneric', [PharmacovigilanceReportingController::class,'getPharmacoVigilancerRptManagerApplicationsGeneric']);

    Route::get('getSafetyAlertApplicationMoreDetails', [PharmacovigilanceReportingController::class,'getSafetyAlertApplicationMoreDetails']);
    Route::get('prepareSafetyalertreportsassessment', [PharmacovigilanceReportingController::class,'prepareSafetyalertreportsassessment']);
});

Route::group(['middleware' => 'web', 'prefix' => 'psur'], function () {
Route::get('/getPsurApplications', [PsurController::class, 'getPsurApplications']);
Route::post('/saveNewPsurReceivingBaseDetails', [PsurController::class, 'saveNewPsurReceivingBaseDetails']);
Route::get('/preparenewPsurReceiving', [PsurController::class, 'preparenewPsurReceiving']);
Route::get('/preparenewPsurAssessment', [PsurController::class, 'preparenewPsurAssessment']);
Route::post('/onSavePsurAssessmentDetails', [PsurController::class, 'onSavePsurAssessmentDetails']);
Route::get('/getStagePsurApplications', [PsurController::class, 'getStagePsurApplications']);
Route::get('/getPsurApplicationMoreDetails', [PsurController::class, 'getPsurApplicationMoreDetails']);
Route::get('/getPreviousPsurReportApplications', [PsurController::class, 'getPreviousPsurReportApplications']);
});
