<?php

use App\Modules\Tradermanagement\Http\Controllers\TradermanagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('tradermanagement')->group(function () {
        Route::controller(TradermanagementController::class)->group(function () {
            Route::get('/', 'index');
    Route::post('saveTraderInformation', 'saveTraderInformation');
    Route::post('updateAccountApprovalStatus', 'updateAccountApprovalStatus');
    Route::post('saveTraderAccountUsers', 'saveTraderAccountUsers');
    
    Route::post('saveAuthorisedtradersdetails', 'saveAuthorisedtradersdetails');

    Route::post('getDownloadTinCertificateUrl', 'getDownloadTinCertificateUrl');
   

    Route::get('gettraderAccountsManagementDetails', 'gettraderAccountsManagementDetails');
    Route::get('getTraderStatusesCounter', 'getTraderStatusesCounter');
    Route::get('printtraderAccountsManagementDetails', 'TradermanagementReports@printtraderAccountsManagementDetails');
    Route::get('gettraderUsersAccountsManagementDetails', 'gettraderUsersAccountsManagementDetails');
    Route::get('getAuthorisedTradersDetailsinformation', 'getAuthorisedTradersDetailsinformation');
    Route::get('getTradersProductsDetailsinformation', 'getTradersProductsDetailsinformation');
    Route::post('saveTraderProductAuthorisation', 'saveTraderProductAuthorisation');
    Route::post('updateTraderProductAuthorisation', 'updateTraderProductAuthorisation');
   
    Route::get('getApplicantsList', 'getApplicantsList');

    Route::get('gettraderSyncApplicationsDetails', 'gettraderSyncApplicationsDetails');
    Route::get('gettraderSyncApplicationsRequestCounters', 'gettraderSyncApplicationsRequestCounters');
    
//email notifications
    Route::post('SendTraderNotificationEmail', 'SendTraderNotificationEmail');
    Route::get('GetTraderEmailNotifications', 'GetTraderEmailNotifications');
    Route::post('DeleteTraderNotificationMail', 'DeleteTraderNotificationMail');
    

    

    //for migration
    Route::post('mergeTraderSyncApplications', 'mergeTraderSyncApplications');
    Route::get('getTraderRegisteredProductsDetails', 'getTraderRegisteredProductsDetails');
    Route::get('getTraderRegisteredPremisesDetails', 'getTraderRegisteredPremisesDetails');
    Route::get('getTraderApprovedGmpDetails', 'getTraderApprovedGmpDetails');

    Route::post('mergeTraderSelectedSyncApplications', 'mergeTraderSelectedSyncApplications');

    Route::get('getTraderAuthorisedProducts', 'getTraderAuthorisedProducts');
    Route::get('getApplicationUploadProofAuthorisation', 'getApplicationUploadProofAuthorisation');
        
        
    
        });

    });
});



// Route::group(['middleware' => 'auth:api', 'prefix' => 'tradermanagement', 'namespace' => 'App\\Modules\Tradermanagement\Http\Controllers'], function()
// {
//     Route::get('/', 'TradermanagementController@index');
//     Route::post('saveTraderInformation', 'TradermanagementController@saveTraderInformation');
//     Route::post('updateAccountApprovalStatus', 'TradermanagementController@updateAccountApprovalStatus');
//     Route::post('saveTraderAccountUsers', 'TradermanagementController@saveTraderAccountUsers');
    
//     Route::post('saveAuthorisedtradersdetails', 'TradermanagementController@saveAuthorisedtradersdetails');

//     Route::post('getDownloadTinCertificateUrl', 'TradermanagementController@getDownloadTinCertificateUrl');
   

//     Route::get('gettraderAccountsManagementDetails', 'TradermanagementController@gettraderAccountsManagementDetails');
//     Route::get('getTraderStatusesCounter', 'TradermanagementController@getTraderStatusesCounter');
//     Route::get('printtraderAccountsManagementDetails', 'TradermanagementReports@printtraderAccountsManagementDetails');
//     Route::get('gettraderUsersAccountsManagementDetails', 'TradermanagementController@gettraderUsersAccountsManagementDetails');
//     Route::get('getAuthorisedTradersDetailsinformation', 'TradermanagementController@getAuthorisedTradersDetailsinformation');
//     Route::get('getTradersProductsDetailsinformation', 'TradermanagementController@getTradersProductsDetailsinformation');
//     Route::post('saveTraderProductAuthorisation', 'TradermanagementController@saveTraderProductAuthorisation');
//     Route::post('updateTraderProductAuthorisation', 'TradermanagementController@updateTraderProductAuthorisation');
   
//     Route::get('getApplicantsList', 'TradermanagementController@getApplicantsList');

//     Route::get('gettraderSyncApplicationsDetails', 'TradermanagementController@gettraderSyncApplicationsDetails');
//     Route::get('gettraderSyncApplicationsRequestCounters', 'TradermanagementController@gettraderSyncApplicationsRequestCounters');
    
// //email notifications
//     Route::post('SendTraderNotificationEmail', 'TradermanagementController@SendTraderNotificationEmail');
//     Route::get('GetTraderEmailNotifications', 'TradermanagementController@GetTraderEmailNotifications');
//     Route::post('DeleteTraderNotificationMail', 'TradermanagementController@DeleteTraderNotificationMail');
    

    

//     //for migration
//     Route::post('mergeTraderSyncApplications', 'TradermanagementController@mergeTraderSyncApplications');
//     Route::get('getTraderRegisteredProductsDetails', 'TradermanagementController@getTraderRegisteredProductsDetails');
//     Route::get('getTraderRegisteredPremisesDetails', 'TradermanagementController@getTraderRegisteredPremisesDetails');
//     Route::get('getTraderApprovedGmpDetails', 'TradermanagementController@getTraderApprovedGmpDetails');

//     Route::post('mergeTraderSelectedSyncApplications', 'TradermanagementController@mergeTraderSelectedSyncApplications');

//     Route::get('getTraderAuthorisedProducts', 'TradermanagementController@getTraderAuthorisedProducts');
//     Route::get('getApplicationUploadProofAuthorisation', 'TradermanagementController@getApplicationUploadProofAuthorisation');

    
// });
