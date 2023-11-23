<?php

use App\Modules\ProductApplicationsReport\Http\Controllers\ProductApplicationsReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('productapplicationsreport')->group(function () {
        Route::controller(ProductApplicationsReportController::class)->group(function () {
            Route::get('ExportProductApplicationDetails', 'ExportProductApplicationDetails');
    Route::get('ExportPremiseApplicationDetails', 'ExportPremiseApplicationDetails');
    Route::get('ExportGMPApplicationDetails', 'ExportGMPApplicationDetails');
    Route::get('ExportClinicalTrailApplicationDetails', 'ExportClinicalTrailApplicationDetails');
    Route::get('ExportImportExportApplicationDetails', 'ExportImportExportApplicationDetails');
        
        
    
        });

    });
});




// Route::group(['middleware' => 'web', 'prefix' => 'productapplicationsreport', 'namespace' => 'App\\Modules\ProductApplicationsReport\Http\Controllers'], function()
// {
//     Route::get('ExportProductApplicationDetails', 'ProductApplicationsReportController@ExportProductApplicationDetails');
//     Route::get('ExportPremiseApplicationDetails', 'ProductApplicationsReportController@ExportPremiseApplicationDetails');
//     Route::get('ExportGMPApplicationDetails', 'ProductApplicationsReportController@ExportGMPApplicationDetails');
//     Route::get('ExportClinicalTrailApplicationDetails', 'ProductApplicationsReportController@ExportClinicalTrailApplicationDetails');
//     Route::get('ExportImportExportApplicationDetails', 'ProductApplicationsReportController@ExportImportExportApplicationDetails');
    
// });
