<?php

use App\Modules\GmpApplications\Http\Controllers\GmpApplicationsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('gmpapplications')->group(function () {
        Route::controller(GmpApplicationsController::class)->group(function () {
            Route::get('/', 'GmpApplicationsController@index');
        });
        
        
    
        });

    });




Route::middleware(['auth:api'])->group( function () {
    Route::prefix('gmpapplications')->group(function () {
        Route::controller(GmpApplicationsController::class)->group(function () {
            Route::get('getGmpApplicationParamFromModel', 'getGmpApplicationParamFromModel');
    Route::post('saveGmpApplicationCommonData', 'saveGmpApplicationCommonData');
    Route::post('deleteGmpApplicationRecord', 'deleteGmpApplicationRecord');
    Route::get('getGmpApplications', 'getGmpApplications');
    Route::get('getManagerApplicationsGeneric', 'getManagerApplicationsGeneric');
    Route::get('getManagerInspectionApplications', 'getManagerInspectionApplications');
    Route::get('getGmpInspectionSchedulingApplications', 'getGmpInspectionSchedulingApplications');
    Route::get('getTCMeetingSchedulingApplications', 'getTCMeetingSchedulingApplications');
    Route::get('getTCMeetingSchedulingReviewApplications', 'getTCMeetingSchedulingReviewApplications');

    
    Route::get('getGmpApplicationsAtApproval', 'getGmpApplicationsAtApproval');
    Route::post('saveNewGmpReceivingBaseDetails', 'saveNewGmpReceivingBaseDetails');
    Route::post('saveRenewalGmpReceivingBaseDetails', 'saveRenewalGmpReceivingBaseDetails');
    //start prepare
    Route::get('prepareNewGmpReceivingStage', 'prepareNewGmpReceivingStage');
    Route::get('prepareNewGmpInvoicingStage', 'prepareNewGmpInvoicingStage');
    Route::get('prepareNewGmpPaymentStage', 'prepareNewGmpPaymentStage');
    Route::get('prepareNewGmpChecklistsStage', 'prepareNewGmpChecklistsStage');
    Route::get('prepareNewGmpSmfUploadsStage', 'prepareNewGmpSmfUploadsStage');
    Route::get('prepareNewGmpManagerInspectionStage', 'prepareNewGmpManagerInspectionStage');
    //end prepare
    Route::get('getSitePersonnelDetails', 'getSitePersonnelDetails');
    Route::get('getSiteOtherDetails', 'getSiteOtherDetails');
    Route::get('getSiteBlockDetails', 'getSiteBlockDetails');
    Route::post('saveSiteOtherDetails', 'saveSiteOtherDetails');
    Route::get('getGmpCommonParams', 'getGmpCommonParams');
    Route::post('saveGmpInspectionLineDetails', 'saveGmpInspectionLineDetails');
    Route::get('getGmpInspectionLineDetails', 'getGmpInspectionLineDetails');
    Route::post('saveApplicationApprovalDetails', 'saveApplicationApprovalDetails');
    Route::get('getGmpApplicationMoreDetails', 'getGmpApplicationMoreDetails');
    Route::get('getManufacturingSitesList', 'getManufacturingSitesList');
    Route::get('getManSitesList', 'getManSitesList');
    Route::get('getOnlineApplications', 'getOnlineApplications');
    Route::get('prepareNewGmpOnlineReceivingStage', 'prepareNewGmpOnlineReceivingStage');
    Route::get('getOnlineAppGmpPersonnelDetails', 'getOnlineAppGmpPersonnelDetails');
    Route::get('getOnlineAppGmpOtherDetails', 'getOnlineAppGmpOtherDetails');
    Route::get('getOnlineProductLineDetails', 'getOnlineProductLineDetails');
    Route::get('getGmpScheduleTeamDetails', 'getGmpScheduleTeamDetails');
    Route::post('saveGmpScheduleInspectionTypes', 'saveGmpScheduleInspectionTypes');
    Route::get('getGmpScheduleInspectionTypes', 'getGmpScheduleInspectionTypes');
    Route::post('saveGmpScheduleInspectors', 'saveGmpScheduleInspectors');
    Route::get('getGmpScheduleInspectors', 'getGmpScheduleInspectors');
    Route::get('getAssignedGmpInspections', 'getAssignedGmpInspections');
    Route::get('getGmpApplicationsForInspection', 'getGmpApplicationsForInspection');
    Route::post('addGmpApplicationsIntoInspectionSchedule', 'addGmpApplicationsIntoInspectionSchedule');
    Route::post('addGmpApplicationIntoInspectionSchedule', 'addGmpApplicationIntoInspectionSchedule');
    Route::post('saveGmpProductInfoLinkage', 'saveGmpProductInfoLinkage');
    Route::post('updateGmpProductInfoLinkage', 'updateGmpProductInfoLinkage');
    Route::get('getGmpProductInfoLinkage', 'getGmpProductInfoLinkage');
    Route::get('getGmpProductInfoLinkageOnline', 'getGmpProductInfoLinkageOnline');
    Route::post('saveManSitePersonnelLinkageDetails', 'saveManSitePersonnelLinkageDetails');
    Route::post('updateGmpApplicationsInspectionType', 'updateGmpApplicationsInspectionType');
    Route::get('getNonComplianceObservations', 'getNonComplianceObservations');
    Route::post('saveGmpDeskReviewScheduleDetails', 'saveGmpDeskReviewScheduleDetails');
    Route::get('getPreviousProductLineDetails', 'getPreviousProductLineDetails');
    Route::get('getGmpWithdrawalApplicationsAtApproval', 'getGmpWithdrawalApplicationsAtApproval');
    Route::get('getGmpCompareDetails', 'getGmpCompareDetails');
    Route::get('getDismissedGmpApplications', 'getDismissedGmpApplications');
    Route::get('getAllGmpApplications', 'getAllGmpApplications');
    Route::post('saveGmpEditAppBaseDetails', 'saveGmpEditAppBaseDetails');

 
    Route::post('saveGmpproductlinedetails', 'saveGmpproductlinedetails');
    Route::get('getManufacturingSiteGmpInspectionLineDetails', 'getManufacturingSiteGmpInspectionLineDetails');
        
        
    
        });

    });
});


// Route::group(['middleware' => 'web', 'prefix' => 'gmpapplications', 'namespace' => 'App\\Modules\GmpApplications\Http\Controllers'], function()
// {
//     Route::get('/', 'GmpApplicationsController@index');
// });

// Route::group(['middleware' => 'auth:api', 'prefix' => 'gmpapplications', 'namespace' => 'App\\Modules\GmpApplications\Http\Controllers'], function()
// {
//     Route::get('getGmpApplicationParamFromModel', 'GmpApplicationsController@getGmpApplicationParamFromModel');
//     Route::post('saveGmpApplicationCommonData', 'GmpApplicationsController@saveGmpApplicationCommonData');
//     Route::post('deleteGmpApplicationRecord', 'GmpApplicationsController@deleteGmpApplicationRecord');
//     Route::get('getGmpApplications', 'GmpApplicationsController@getGmpApplications');
//     Route::get('getManagerApplicationsGeneric', 'GmpApplicationsController@getManagerApplicationsGeneric');
//     Route::get('getManagerInspectionApplications', 'GmpApplicationsController@getManagerInspectionApplications');
//     Route::get('getGmpInspectionSchedulingApplications', 'GmpApplicationsController@getGmpInspectionSchedulingApplications');
//     Route::get('getTCMeetingSchedulingApplications', 'GmpApplicationsController@getTCMeetingSchedulingApplications');
//     Route::get('getTCMeetingSchedulingReviewApplications', 'GmpApplicationsController@getTCMeetingSchedulingReviewApplications');

    
//     Route::get('getGmpApplicationsAtApproval', 'GmpApplicationsController@getGmpApplicationsAtApproval');
//     Route::post('saveNewGmpReceivingBaseDetails', 'GmpApplicationsController@saveNewGmpReceivingBaseDetails');
//     Route::post('saveRenewalGmpReceivingBaseDetails', 'GmpApplicationsController@saveRenewalGmpReceivingBaseDetails');
//     //start prepare
//     Route::get('prepareNewGmpReceivingStage', 'GmpApplicationsController@prepareNewGmpReceivingStage');
//     Route::get('prepareNewGmpInvoicingStage', 'GmpApplicationsController@prepareNewGmpInvoicingStage');
//     Route::get('prepareNewGmpPaymentStage', 'GmpApplicationsController@prepareNewGmpPaymentStage');
//     Route::get('prepareNewGmpChecklistsStage', 'GmpApplicationsController@prepareNewGmpChecklistsStage');
//     Route::get('prepareNewGmpSmfUploadsStage', 'GmpApplicationsController@prepareNewGmpSmfUploadsStage');
//     Route::get('prepareNewGmpManagerInspectionStage', 'GmpApplicationsController@prepareNewGmpManagerInspectionStage');
//     //end prepare
//     Route::get('getSitePersonnelDetails', 'GmpApplicationsController@getSitePersonnelDetails');
//     Route::get('getSiteOtherDetails', 'GmpApplicationsController@getSiteOtherDetails');
//     Route::get('getSiteBlockDetails', 'GmpApplicationsController@getSiteBlockDetails');
//     Route::post('saveSiteOtherDetails', 'GmpApplicationsController@saveSiteOtherDetails');
//     Route::get('getGmpCommonParams', 'GmpApplicationsController@getGmpCommonParams');
//     Route::post('saveGmpInspectionLineDetails', 'GmpApplicationsController@saveGmpInspectionLineDetails');
//     Route::get('getGmpInspectionLineDetails', 'GmpApplicationsController@getGmpInspectionLineDetails');
//     Route::post('saveApplicationApprovalDetails', 'GmpApplicationsController@saveApplicationApprovalDetails');
//     Route::get('getGmpApplicationMoreDetails', 'GmpApplicationsController@getGmpApplicationMoreDetails');
//     Route::get('getManufacturingSitesList', 'GmpApplicationsController@getManufacturingSitesList');
//     Route::get('getManSitesList', 'GmpApplicationsController@getManSitesList');
//     Route::get('getOnlineApplications', 'GmpApplicationsController@getOnlineApplications');
//     Route::get('prepareNewGmpOnlineReceivingStage', 'GmpApplicationsController@prepareNewGmpOnlineReceivingStage');
//     Route::get('getOnlineAppGmpPersonnelDetails', 'GmpApplicationsController@getOnlineAppGmpPersonnelDetails');
//     Route::get('getOnlineAppGmpOtherDetails', 'GmpApplicationsController@getOnlineAppGmpOtherDetails');
//     Route::get('getOnlineProductLineDetails', 'GmpApplicationsController@getOnlineProductLineDetails');
//     Route::get('getGmpScheduleTeamDetails', 'GmpApplicationsController@getGmpScheduleTeamDetails');
//     Route::post('saveGmpScheduleInspectionTypes', 'GmpApplicationsController@saveGmpScheduleInspectionTypes');
//     Route::get('getGmpScheduleInspectionTypes', 'GmpApplicationsController@getGmpScheduleInspectionTypes');
//     Route::post('saveGmpScheduleInspectors', 'GmpApplicationsController@saveGmpScheduleInspectors');
//     Route::get('getGmpScheduleInspectors', 'GmpApplicationsController@getGmpScheduleInspectors');
//     Route::get('getAssignedGmpInspections', 'GmpApplicationsController@getAssignedGmpInspections');
//     Route::get('getGmpApplicationsForInspection', 'GmpApplicationsController@getGmpApplicationsForInspection');
//     Route::post('addGmpApplicationsIntoInspectionSchedule', 'GmpApplicationsController@addGmpApplicationsIntoInspectionSchedule');
//     Route::post('addGmpApplicationIntoInspectionSchedule', 'GmpApplicationsController@addGmpApplicationIntoInspectionSchedule');
//     Route::post('saveGmpProductInfoLinkage', 'GmpApplicationsController@saveGmpProductInfoLinkage');
//     Route::post('updateGmpProductInfoLinkage', 'GmpApplicationsController@updateGmpProductInfoLinkage');
//     Route::get('getGmpProductInfoLinkage', 'GmpApplicationsController@getGmpProductInfoLinkage');
//     Route::get('getGmpProductInfoLinkageOnline', 'GmpApplicationsController@getGmpProductInfoLinkageOnline');
//     Route::post('saveManSitePersonnelLinkageDetails', 'GmpApplicationsController@saveManSitePersonnelLinkageDetails');
//     Route::post('updateGmpApplicationsInspectionType', 'GmpApplicationsController@updateGmpApplicationsInspectionType');
//     Route::get('getNonComplianceObservations', 'GmpApplicationsController@getNonComplianceObservations');
//     Route::post('saveGmpDeskReviewScheduleDetails', 'GmpApplicationsController@saveGmpDeskReviewScheduleDetails');
//     Route::get('getPreviousProductLineDetails', 'GmpApplicationsController@getPreviousProductLineDetails');
//     Route::get('getGmpWithdrawalApplicationsAtApproval', 'GmpApplicationsController@getGmpWithdrawalApplicationsAtApproval');
//     Route::get('getGmpCompareDetails', 'GmpApplicationsController@getGmpCompareDetails');
//     Route::get('getDismissedGmpApplications', 'GmpApplicationsController@getDismissedGmpApplications');
//     Route::get('getAllGmpApplications', 'GmpApplicationsController@getAllGmpApplications');
//     Route::post('saveGmpEditAppBaseDetails', 'GmpApplicationsController@saveGmpEditAppBaseDetails');

 
//     Route::post('saveGmpproductlinedetails', 'GmpApplicationsController@saveGmpproductlinedetails');
//     Route::get('getManufacturingSiteGmpInspectionLineDetails', 'GmpApplicationsController@getManufacturingSiteGmpInspectionLineDetails');

// });