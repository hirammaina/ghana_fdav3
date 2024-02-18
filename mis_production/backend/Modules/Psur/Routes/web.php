<?php

use Modules\Psur\Http\Controllers\PsurController;
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
