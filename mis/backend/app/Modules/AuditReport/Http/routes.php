<?php

use App\Modules\AuditReport\Http\Controllers\AuditReportController;
use Illuminate\Support\Facades\Route;
// Route::group(['middleware' => 'web', 'prefix' => 'auditreport', 'namespace' => 'App\\Modules\AuditReport\Http\Controllers'], function()
// {
//     Route::get('generateReportData', 'AuditReportController@generateReportData');
//     Route::post('saveAuditDefinationConfigParam', 'AuditReportController@saveAuditDefinationConfigParam');
//     Route::post('dropAuditDefinationParam', 'AuditReportController@dropAuditDefinationParam');
//     Route::get('exportAuditLogs', 'AuditReportController@exportAuditLogs');
    

// });

Route::middleware(['web'])->group( function () {
    Route::prefix('auditreport')->group(function () {
        Route::controller(AuditReportController::class)->group(function () {
            Route::get('generateReportData', 'generateReportData');
            Route::post('saveAuditDefinationConfigParam', 'saveAuditDefinationConfigParam');
            Route::post('dropAuditDefinationParam', 'dropAuditDefinationParam');
            Route::get('exportAuditLogs', 'exportAuditLogs');
    
        });

    });
});




