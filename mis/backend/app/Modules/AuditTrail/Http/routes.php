<?php

use App\Modules\AuditTrail\Http\Controllers\AuditTrailController;
use Illuminate\Support\Facades\Route;
Route::middleware(['web'])->group( function () {
    Route::prefix('audittrail')->group(function () {
        Route::controller(AuditTrailController::class)->group(function () {
            Route::get('getMisAuditTrail', 'getMisAuditTrail');
            Route::get('getPortalAuditTrail', 'getPortalAuditTrail');
            Route::get('getPortalAuditTableData', 'getPortalAuditTableData');
            Route::get('getMISAuditTableData', 'getMISAuditTableData');
            Route::get('revertAuditRecord', 'revertAuditRecord');
            Route::get('getTableslist', 'getTableslist');
            Route::get('getAllAuditTrans', 'getAllAuditTrans');
            Route::get('exportAudit', 'exportAudit');
            Route::get('getAllUsers/{table}/{id?}', 'getAllUsers');
        
            Route::get('getloginLogs', 'getloginLogs');
            Route::get('getloginAttemptsLogs', 'getloginAttemptsLogs');
        
        
    
        });

    });
});



// Route::group(['middleware' => 'web', 'prefix' => 'audittrail', 'namespace' => 'App\\Modules\AuditTrail\Http\Controllers'], function()
// {
//     Route::get('getMisAuditTrail', 'AuditTrailController@getMisAuditTrail');
//     Route::get('getPortalAuditTrail', 'AuditTrailController@getPortalAuditTrail');
//     Route::get('getPortalAuditTableData', 'AuditTrailController@getPortalAuditTableData');
//     Route::get('getMISAuditTableData', 'AuditTrailController@getMISAuditTableData');
//     Route::get('revertAuditRecord', 'AuditTrailController@revertAuditRecord');
//     Route::get('getTableslist', 'AuditTrailController@getTableslist');
//     Route::get('getAllAuditTrans', 'AuditTrailController@getAllAuditTrans');
//     Route::get('exportAudit', 'AuditTrailController@exportAudit');
//     Route::get('getAllUsers/{table}/{id?}', 'AuditTrailController@getAllUsers');

//     Route::get('getloginLogs', 'AuditTrailController@getloginLogs');
//     Route::get('getloginAttemptsLogs', 'AuditTrailController@getloginAttemptsLogs');


// });


