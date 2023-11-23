<?php

use App\Modules\Revenuemanagement\Http\Controllers\RetentionmanagementController;
use App\Modules\Revenuemanagement\Http\Controllers\RevenuemanagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('revenuemanagement')->group(function () {
        Route::controller(RevenuemanagementController::class)->group(function () {
            Route::get('/', 'index');
	
    Route::get('getGepgbillinvoicepostingdetails', 'getGepgbillinvoicepostingdetails');
    Route::get('getpostPaymentspostingdetails', 'getpostPaymentspostingdetails');
    Route::get('getGepgbillPaymentspostingdetails', 'getGepgbillPaymentspostingdetails');
    Route::post('saveBatchInvoiceDetails', 'saveBatchInvoiceDetails');
    Route::get('getBatchInvoiceApplications', 'getBatchInvoiceApplications');
    Route::get('getBatchRetentionsInvoices', 'getBatchRetentionsInvoices');
	Route::get('getBatchApplicationInvoicesDetails', 'getBatchApplicationInvoicesDetails');
    Route::get('getRetentionChargesInvoicesdetails', 'getRetentionChargesInvoicesdetails');
    
    
    Route::get('getReversedRequestsApplicationInvoices', 'getReversedRequestsApplicationInvoices');
    
    Route::get('getApplicationInvoicesDetails', 'getApplicationInvoicesDetails');
    Route::get('prepareCancellationREquestDetails', 'prepareCancellationREquestDetails');
    Route::post('approveInvoiceCancellationRequest', 'approveInvoiceCancellationRequest');
    Route::get('getPaymentsReversalRequestApplications', 'getPaymentsReversalRequestApplications');
  //  Route::get('getGepgbillPaymentspostingdetails', 'getGepgbillPaymentspostingdetails');
    Route::get('getPaymentspostingdetails', 'getPaymentspostingdetails');
    Route::post('approvePaymentCancellationRequest', 'approvePaymentCancellationRequest');
    Route::post('funcOnFetchCurrencyExchangeRate', 'funcOnFetchCurrencyExchangeRate');
	
	
    
    Route::get('getWavePaymentManagementDashDetails', 'getWavePaymentManagementDashDetails');
    Route::post('approveCreditNoteRequest', 'approveCreditNoteRequest');
	
	Route::get('getApplicationRaisedInvoices', 'getApplicationRaisedInvoices');
	Route::get('getBatchApplicationRaisedInvoices', 'getBatchApplicationRaisedInvoices');
	Route::get('getNewInvoiceQuotation', 'getNewInvoiceQuotation');
	Route::get('getOnlineAppNewInvoiceQuotation', 'getOnlineAppNewInvoiceQuotation');
	
	Route::get('getImportFOBApplicationInvoiceDetails', 'getImportFOBApplicationInvoiceDetails');
   
    Route::get('getAdhocInvoicingApplicationsDetails', 'getAdhocInvoicingApplicationsDetails');
    Route::post('saveInspectionAtOwnersPremises', 'saveInspectionAtOwnersPremises');
    Route::get('prepareInspectionatownerpremreceiving', 'prepareInspectionatownerpremreceiving');
    Route::get('prepareadhocinvoicingreceiptingpnl', 'prepareadhocinvoicingreceiptingpnl');
   
    Route::get('getApplicationInvoiceDetails', 'getApplicationInvoiceDetails');
    Route::get('getRetentionPendingInvoicesdetails', 'getRetentionPendingInvoicesdetails');
    Route::get('getRetentionAplicantsDetails', 'RetentionmanagementController@getRetentionAplicantsDetails');
    Route::get('getRetentionChargesPaymentsdetails', 'RetentionmanagementController@getRetentionChargesPaymentsdetails');
    Route::get('prepareAdhocInvoiceRequestpnl', 'RetentionmanagementController@prepareAdhocInvoiceRequestpnl');
    Route::post('saveAdhocApplicationInvoiceDetails', 'saveAdhocApplicationInvoiceDetails');
	
	 Route::post('saveapplicationreceiceinvoiceDetails', 'saveapplicationreceiceinvoiceDetails');
	 Route::post('saveonlineapplicationreceiceinvoiceDetails', 'saveonlineapplicationreceiceinvoiceDetails');
    Route::post('checkApplicationInvoiceBalance', 'checkApplicationInvoiceBalance');
    Route::get('getRaisedApplicationReinvoices', 'getRaisedApplicationReinvoices');
    Route::get('onCancelGeneratedApplicationInvoice', 'onCancelGeneratedApplicationInvoice');
        
        
    
        });

    });
});




    Route::prefix('retentionmanagement')->group(function () {
        Route::controller(RetentionmanagementController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('getRetentionChargesInvoicesdetails', 'getRetentionChargesInvoicesdetails');
           
            
            Route::get('generateSingleProductRetentionCharge', 'generateSingleProductRetentionCharge');
        
            Route::get('generateProductRetentionCharges', 'generateProductRetentionCharges');
            Route::get('generateProductRetentionPenalty', 'generateProductRetentionPenalty');
        
            //post notifications
            Route::get('sendProductRetentionChargesNotifications', 'sendProductRetentionChargesNotifications');
            Route::get('getRetentionReport', 'getRetentionReport');
            Route::get('exportRevenueReportsData', 'exportRevenueReportsData');
        
    
        });

    });




// Route::group(['middleware' => 'auth:api', 'prefix' => 'revenuemanagement', 'namespace' => 'App\\Modules\Revenuemanagement\Http\Controllers'], function()
// {
//     Route::get('/', 'RevenuemanagementController@index');
	
//     Route::get('getGepgbillinvoicepostingdetails', 'RevenuemanagementController@getGepgbillinvoicepostingdetails');
//     Route::get('getpostPaymentspostingdetails', 'RevenuemanagementController@getpostPaymentspostingdetails');
//     Route::get('getGepgbillPaymentspostingdetails', 'RevenuemanagementController@getGepgbillPaymentspostingdetails');
//     Route::post('saveBatchInvoiceDetails', 'RevenuemanagementController@saveBatchInvoiceDetails');
//     Route::get('getBatchInvoiceApplications', 'RevenuemanagementController@getBatchInvoiceApplications');
//     Route::get('getBatchRetentionsInvoices', 'RevenuemanagementController@getBatchRetentionsInvoices');
// 	Route::get('getBatchApplicationInvoicesDetails', 'RevenuemanagementController@getBatchApplicationInvoicesDetails');
//     Route::get('getRetentionChargesInvoicesdetails', 'RevenuemanagementController@getRetentionChargesInvoicesdetails');
    
    
//     Route::get('getReversedRequestsApplicationInvoices', 'RevenuemanagementController@getReversedRequestsApplicationInvoices');
    
//     Route::get('getApplicationInvoicesDetails', 'RevenuemanagementController@getApplicationInvoicesDetails');
//     Route::get('prepareCancellationREquestDetails', 'RevenuemanagementController@prepareCancellationREquestDetails');
//     Route::post('approveInvoiceCancellationRequest', 'RevenuemanagementController@approveInvoiceCancellationRequest');
//     Route::get('getPaymentsReversalRequestApplications', 'RevenuemanagementController@getPaymentsReversalRequestApplications');
//   //  Route::get('getGepgbillPaymentspostingdetails', 'RevenuemanagementController@getGepgbillPaymentspostingdetails');
//     Route::get('getPaymentspostingdetails', 'RevenuemanagementController@getPaymentspostingdetails');
//     Route::post('approvePaymentCancellationRequest', 'RevenuemanagementController@approvePaymentCancellationRequest');
//     Route::post('funcOnFetchCurrencyExchangeRate', 'RevenuemanagementController@funcOnFetchCurrencyExchangeRate');
	
	
    
//     Route::get('getWavePaymentManagementDashDetails', 'RevenuemanagementController@getWavePaymentManagementDashDetails');
//     Route::post('approveCreditNoteRequest', 'RevenuemanagementController@approveCreditNoteRequest');
	
// 	Route::get('getApplicationRaisedInvoices', 'RevenuemanagementController@getApplicationRaisedInvoices');
// 	Route::get('getBatchApplicationRaisedInvoices', 'RevenuemanagementController@getBatchApplicationRaisedInvoices');
// 	Route::get('getNewInvoiceQuotation', 'RevenuemanagementController@getNewInvoiceQuotation');
// 	Route::get('getOnlineAppNewInvoiceQuotation', 'RevenuemanagementController@getOnlineAppNewInvoiceQuotation');
	
// 	Route::get('getImportFOBApplicationInvoiceDetails', 'RevenuemanagementController@getImportFOBApplicationInvoiceDetails');
   
//     Route::get('getAdhocInvoicingApplicationsDetails', 'RevenuemanagementController@getAdhocInvoicingApplicationsDetails');
//     Route::post('saveInspectionAtOwnersPremises', 'RevenuemanagementController@saveInspectionAtOwnersPremises');
//     Route::get('prepareInspectionatownerpremreceiving', 'RevenuemanagementController@prepareInspectionatownerpremreceiving');
//     Route::get('prepareadhocinvoicingreceiptingpnl', 'RevenuemanagementController@prepareadhocinvoicingreceiptingpnl');
   
//     Route::get('getApplicationInvoiceDetails', 'RevenuemanagementController@getApplicationInvoiceDetails');
//     Route::get('getRetentionPendingInvoicesdetails', 'RevenuemanagementController@getRetentionPendingInvoicesdetails');
//     Route::get('getRetentionAplicantsDetails', 'RetentionmanagementController@getRetentionAplicantsDetails');
//     Route::get('getRetentionChargesPaymentsdetails', 'RetentionmanagementController@getRetentionChargesPaymentsdetails');
//     Route::get('prepareAdhocInvoiceRequestpnl', 'RetentionmanagementController@prepareAdhocInvoiceRequestpnl');
//     Route::post('saveAdhocApplicationInvoiceDetails', 'RevenuemanagementController@saveAdhocApplicationInvoiceDetails');
	
// 	 Route::post('saveapplicationreceiceinvoiceDetails', 'RevenuemanagementController@saveapplicationreceiceinvoiceDetails');
// 	 Route::post('saveonlineapplicationreceiceinvoiceDetails', 'RevenuemanagementController@saveonlineapplicationreceiceinvoiceDetails');
//     Route::post('checkApplicationInvoiceBalance', 'RevenuemanagementController@checkApplicationInvoiceBalance');
//     Route::get('getRaisedApplicationReinvoices', 'RevenuemanagementController@getRaisedApplicationReinvoices');
//     Route::get('onCancelGeneratedApplicationInvoice', 'RevenuemanagementController@onCancelGeneratedApplicationInvoice');
 
   
// });

// Route::group([ 'prefix' => 'retentionmanagement', 'namespace' => 'App\\Modules\Revenuemanagement\Http\Controllers'], function()
// {
//     Route::get('/', 'RetentionmanagementController@index');
//     Route::get('getRetentionChargesInvoicesdetails', 'RetentionmanagementController@getRetentionChargesInvoicesdetails');
   
    
//     Route::get('generateSingleProductRetentionCharge', 'RetentionmanagementController@generateSingleProductRetentionCharge');

//     Route::get('generateProductRetentionCharges', 'RetentionmanagementController@generateProductRetentionCharges');
//     Route::get('generateProductRetentionPenalty', 'RetentionmanagementController@generateProductRetentionPenalty');

//     //post notifications
//     Route::get('sendProductRetentionChargesNotifications', 'RetentionmanagementController@sendProductRetentionChargesNotifications');
//     Route::get('getRetentionReport', 'RetentionmanagementController@getRetentionReport');
//     Route::get('exportRevenueReportsData', 'RetentionmanagementController@exportRevenueReportsData');
// });