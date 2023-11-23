<?php

use App\Modules\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

    Route::prefix("dashboard")
    ->controller(DashboardController::class)
    ->group(function () {
        Route::post("", "index");
       
    });


Route::middleware(['web'])->group( function () {
    Route::prefix('/dashboard')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('getInTrayItems', 'getInTrayItems');
    Route::get('getTrackingInTrayItems', 'getTrackingInTrayItems');
    Route::get('getOutTrayItems', 'getOutTrayItems');
    Route::get('getSystemGuidelines', 'getSystemGuidelines');
    Route::post('saveDashCommonData', 'saveDashCommonData');
    Route::get('getOnlineApplicationDashboard', 'getOnlineApplicationDashboard');
    Route::get('getOnlineAppsSubmissionCounter', 'getOnlineAppsSubmissionCounter');

    Route::get('getDashApplicationGraphSummaryDetails', 'getDashApplicationGraphSummaryDetails');
    Route::get('getDashApplicationSummaryDetails', 'getDashApplicationSummaryDetails');

    Route::get('getDashRevenueGraphSummaryDetails', 'getDashRevenueGraphSummaryDetails');

    Route::get('getDashRevenueSummaryDetails', 'getDashRevenueSummaryDetails');
    Route::get('getExternalUserInTrayItems', 'getExternalUserInTrayItems');

    Route::get('getExternalUserInTrayItems', 'getExternalUserInTrayItems');
    Route::get('getExternalOutTrayItems', 'getExternalOutTrayItems');
    Route::get('getScheduledTcMeetingDetails', 'getScheduledTcMeetingDetails');
    
    Route::post('checkAssignmentDefination', 'checkAssignmentDefination');
    Route::get('getApplicationAssaignmentRecords', 'getApplicationAssaignmentRecords');
    Route::get('getApplicationAssaignmentCount', 'getApplicationAssaignmentCount');
    Route::get('getAssaignmentApplications', 'getAssaignmentApplications');

    Route::get('exportDashboard', 'exportDashboard');
    Route::get('getOnlineImportExportManagerReviewApplications', 'getOnlineImportExportManagerReviewApplications');
     
    Route::get('getAssignedFasttrackApplications', 'getAssignedFasttrackApplications');
    Route::get('checkFastTrackApplications', 'checkFastTrackApplications');
    
	 
    Route::get('getOverDueTrayItems', 'getOverDueTrayItems');
    Route::get('getApplicationSummaryOverDueTrayItems', 'getApplicationSummaryOverDueTrayItems');
    Route::get('getApplicationSummaryIntrayItems', 'getApplicationSummaryIntrayItems');
    Route::get('getApplicationTrackingSummaryIntrayItems', 'getApplicationTrackingSummaryIntrayItems');
    Route::get('getUserIntrayAssignments', 'getUserIntrayAssignments');
    
    
    Route::get('getUserCompletedAssPerformancSummary', 'getUserCompletedAssPerformancSummary');

    Route::get('getCompletedAssaignmentApplications', 'getCompletedAssaignmentApplications');

    Route::get('getUserCompletedAssignments', 'getUserCompletedAssignments');
    Route::get('getActiveUserCompletedAssignments', 'getActiveUserCompletedAssignments');
        
    
        });

    });
});
// Route::group(['middleware' => 'web', 'prefix' => 'dashboard', 'namespace' => 'App\\Modules\Dashboard\Http\Controllers'], function () {
//     Route::get('/', 'DashboardController@index');
//     Route::get('getInTrayItems', 'DashboardController@getInTrayItems');
//     Route::get('getTrackingInTrayItems', 'DashboardController@getTrackingInTrayItems');
//     Route::get('getOutTrayItems', 'DashboardController@getOutTrayItems');
//     Route::get('getSystemGuidelines', 'DashboardController@getSystemGuidelines');
//     Route::post('saveDashCommonData', 'DashboardController@saveDashCommonData');
//     Route::get('getOnlineApplicationDashboard', 'DashboardController@getOnlineApplicationDashboard');
//     Route::get('getOnlineAppsSubmissionCounter', 'DashboardController@getOnlineAppsSubmissionCounter');

//     Route::get('getDashApplicationGraphSummaryDetails', 'DashboardController@getDashApplicationGraphSummaryDetails');
//     Route::get('getDashApplicationSummaryDetails', 'DashboardController@getDashApplicationSummaryDetails');

//     Route::get('getDashRevenueGraphSummaryDetails', 'DashboardController@getDashRevenueGraphSummaryDetails');

//     Route::get('getDashRevenueSummaryDetails', 'DashboardController@getDashRevenueSummaryDetails');
//     Route::get('getExternalUserInTrayItems', 'DashboardController@getExternalUserInTrayItems');

//     Route::get('getExternalUserInTrayItems', 'DashboardController@getExternalUserInTrayItems');
//     Route::get('getExternalOutTrayItems', 'DashboardController@getExternalOutTrayItems');
//     Route::get('getScheduledTcMeetingDetails', 'DashboardController@getScheduledTcMeetingDetails');
    
//     Route::post('checkAssignmentDefination', 'DashboardController@checkAssignmentDefination');
//     Route::get('getApplicationAssaignmentRecords', 'DashboardController@getApplicationAssaignmentRecords');
//     Route::get('getApplicationAssaignmentCount', 'DashboardController@getApplicationAssaignmentCount');
//     Route::get('getAssaignmentApplications', 'DashboardController@getAssaignmentApplications');

//     Route::get('exportDashboard', 'DashboardController@exportDashboard');
//     Route::get('getOnlineImportExportManagerReviewApplications', 'DashboardController@getOnlineImportExportManagerReviewApplications');
     
//     Route::get('getAssignedFasttrackApplications', 'DashboardController@getAssignedFasttrackApplications');
//     Route::get('checkFastTrackApplications', 'DashboardController@checkFastTrackApplications');
    
	 
//     Route::get('getOverDueTrayItems', 'DashboardController@getOverDueTrayItems');
//     Route::get('getApplicationSummaryOverDueTrayItems', 'DashboardController@getApplicationSummaryOverDueTrayItems');
//     Route::get('getApplicationSummaryIntrayItems', 'DashboardController@getApplicationSummaryIntrayItems');
//     Route::get('getApplicationTrackingSummaryIntrayItems', 'DashboardController@getApplicationTrackingSummaryIntrayItems');
//     Route::get('getUserIntrayAssignments', 'DashboardController@getUserIntrayAssignments');
    
    
//     Route::get('getUserCompletedAssPerformancSummary', 'DashboardController@getUserCompletedAssPerformancSummary');

//     Route::get('getCompletedAssaignmentApplications', 'DashboardController@getCompletedAssaignmentApplications');

//     Route::get('getUserCompletedAssignments', 'DashboardController@getUserCompletedAssignments');
//     Route::get('getActiveUserCompletedAssignments', 'DashboardController@getActiveUserCompletedAssignments');


// });
