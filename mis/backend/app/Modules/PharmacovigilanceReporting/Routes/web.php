<?php

use App\Modules\PharmacovigilanceReporting\Http\Controllers\PharmacovigilanceReportingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('pharmacovigilancereporting')->group(function () {
        Route::controller(PharmacovigilanceReportingController::class)->group(function () {
            Route::get('/', 'index');

            Route::post('saveReceivingSafetyAlertReportsDetails', 'saveReceivingSafetyAlertReportsDetails');
            Route::get('getPharmacoVigilanceApps', 'getPharmacoVigilanceApps');
            Route::get('prepareReceivingSafetyAlertReportsStage', 'prepareReceivingSafetyAlertReportsStage');
            Route::get('getPharmacoVigilancerRptManagerApplicationsGeneric', 'getPharmacoVigilancerRptManagerApplicationsGeneric');
        
            Route::get('getSafetyAlertApplicationMoreDetails', 'getSafetyAlertApplicationMoreDetails');
            Route::get('prepareSafetyalertreportsassessment', 'prepareSafetyalertreportsassessment');
            Route::get('getSafetyalertreportsobservationsDetails', 'getSafetyalertreportsobservationsDetails');
            Route::post('saveSafetyAlertReportsObservations', 'saveSafetyAlertReportsObservations');
            
        
        
    
        });

    });
});
// Route::group(['middleware' => 'auth:api', 'prefix' => 'pharmacovigilancereporting', 'namespace' => 'App\\Modules\PharmacovigilanceReporting\Http\Controllers'], function()
// {
//     Route::get('/', 'PharmacovigilanceReportingController@index');

//     Route::post('saveReceivingSafetyAlertReportsDetails', 'PharmacovigilanceReportingController@saveReceivingSafetyAlertReportsDetails');
//     Route::get('getPharmacoVigilanceApps', 'PharmacovigilanceReportingController@getPharmacoVigilanceApps');
//     Route::get('prepareReceivingSafetyAlertReportsStage', 'PharmacovigilanceReportingController@prepareReceivingSafetyAlertReportsStage');
//     Route::get('getPharmacoVigilancerRptManagerApplicationsGeneric', 'PharmacovigilanceReportingController@getPharmacoVigilancerRptManagerApplicationsGeneric');

//     Route::get('getSafetyAlertApplicationMoreDetails', 'PharmacovigilanceReportingController@getSafetyAlertApplicationMoreDetails');
//     Route::get('prepareSafetyalertreportsassessment', 'PharmacovigilanceReportingController@prepareSafetyalertreportsassessment');
//     Route::get('getSafetyalertreportsobservationsDetails', 'PharmacovigilanceReportingController@getSafetyalertreportsobservationsDetails');
//     Route::post('saveSafetyAlertReportsObservations', 'PharmacovigilanceReportingController@saveSafetyAlertReportsObservations');
    

    
// });

