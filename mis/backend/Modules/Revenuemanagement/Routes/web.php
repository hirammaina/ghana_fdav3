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

// Route::prefix('revenuemanagement')->group(function() {
//     Route::get('/', 'RevenuemanagementController@index');
// });
use Modules\Revenuemanagement\Http\Controllers\RevenuemanagementController;
use Modules\Revenuemanagement\Http\Controllers\RetentionmanagementController;

Route::group(['prefix' => 'revenuemanagement','middleware' => ['auth:api', 'web']], function() {
    Route::get('getApplicationRaisedInvoices', [RevenuemanagementController::class, 'getApplicationRaisedInvoices']);
    Route::get('/', [RevenuemanagementController::class,'index']);
    
    Route::get('getGepgbillinvoicepostingdetails', [RevenuemanagementController::class,'getGepgbillinvoicepostingdetails']);
    Route::get('getGepgbillPaymentspostingdetails', [RevenuemanagementController::class,'getGepgbillPaymentspostingdetails']);
    Route::post('saveBatchInvoiceDetails', [RevenuemanagementController::class,'saveBatchInvoiceDetails']);
    Route::get('getBatchInvoiceApplications', [RevenuemanagementController::class,'getBatchInvoiceApplications']);
    Route::get('getBatchRetentionsInvoices', [RevenuemanagementController::class,'getBatchRetentionsInvoices']);
    Route::get('getBatchApplicationInvoicesDetails', [RevenuemanagementController::class,'getBatchApplicationInvoicesDetails']);
    Route::get('getRetentionChargesInvoicesdetails', [RevenuemanagementController::class,'getRetentionChargesInvoicesdetails']);
    
    
    Route::get('getReversedRequestsApplicationInvoices', [RevenuemanagementController::class,'getReversedRequestsApplicationInvoices']);
    
    Route::get('getApplicationInvoicesDetails', [RevenuemanagementController::class,'getApplicationInvoicesDetails']);
    Route::get('prepareCancellationREquestDetails', [RevenuemanagementController::class,'prepareCancellationREquestDetails']);
    Route::post('approveInvoiceCancellationRequest', [RevenuemanagementController::class,'approveInvoiceCancellationRequest']);
    Route::get('getPaymentsReversalRequestApplications', [RevenuemanagementController::class,'getPaymentsReversalRequestApplications']);
    Route::get('getGepgbillPaymentspostingdetails', [RevenuemanagementController::class,'getGepgbillPaymentspostingdetails']);
    Route::get('getPaymentspostingdetails', [RevenuemanagementController::class,'getPaymentspostingdetails']);
    Route::post('approvePaymentCancellationRequest', [RevenuemanagementController::class,'approvePaymentCancellationRequest']);
    Route::post('funcOnFetchCurrencyExchangeRate', [RevenuemanagementController::class,'funcOnFetchCurrencyExchangeRate']);
    
    
    
    Route::get('getWavePaymentManagementDashDetails', [RevenuemanagementController::class,'getWavePaymentManagementDashDetails']);
    Route::post('approveCreditNoteRequest', [RevenuemanagementController::class,'approveCreditNoteRequest']);
    
    Route::get('getApplicationRaisedInvoices', [RevenuemanagementController::class,'getApplicationRaisedInvoices']);
    Route::get('getNewInvoiceQuotation', [RevenuemanagementController::class,'getNewInvoiceQuotation']);
    Route::get('getOnlineAppNewInvoiceQuotation', [RevenuemanagementController::class,'getOnlineAppNewInvoiceQuotation']);
    
    Route::get('getImportFOBApplicationInvoiceDetails', [RevenuemanagementController::class,'getImportFOBApplicationInvoiceDetails']);
   
    Route::get('getAdhocInvoicingApplicationsDetails', [RevenuemanagementController::class,'getAdhocInvoicingApplicationsDetails']);
    Route::post('saveInspectionAtOwnersPremises', [RevenuemanagementController::class,'saveInspectionAtOwnersPremises']);
    Route::get('prepareInspectionatownerpremreceiving', [RevenuemanagementController::class,'prepareInspectionatownerpremreceiving']);
    Route::get('prepareadhocinvoicingreceiptingpnl', [RevenuemanagementController::class,'prepareadhocinvoicingreceiptingpnl']);
   
    Route::get('getApplicationInvoiceDetails', [RevenuemanagementController::class,'getApplicationInvoiceDetails']);
    Route::get('getRetentionPendingInvoicesdetails', [RevenuemanagementController::class,'getRetentionPendingInvoicesdetails']);
    Route::get('getRetentionAplicantsDetails', [RetentionmanagementController::class, 'getRetentionAplicantsDetails']);
    Route::get('getRetentionChargesPaymentsdetails', [RetentionmanagementController::class,'getRetentionChargesPaymentsdetails']);
    Route::get('prepareAdhocInvoiceRequestpnl', [RetentionmanagementController::class,'prepareAdhocInvoiceRequestpnl']);
    Route::post('saveAdhocApplicationInvoiceDetails', [RevenuemanagementController::class,'saveAdhocApplicationInvoiceDetails']);
    
    Route::post('saveapplicationreceiceinvoiceDetails', [RevenuemanagementController::class,'saveapplicationreceiceinvoiceDetails']);
    Route::post('saveonlineapplicationreceiceinvoiceDetails', [RevenuemanagementController::class,'saveonlineapplicationreceiceinvoiceDetails']);
    Route::post('checkApplicationInvoiceBalance', [RevenuemanagementController::class,'checkApplicationInvoiceBalance']);
    Route::get('getRaisedApplicationReinvoices', [RevenuemanagementController::class,'getRaisedApplicationReinvoices']);
 
    Route::get('onCancelGeneratedApplicationInvoice', [RevenuemanagementController::class,'onCancelGeneratedApplicationInvoice']);
     Route::get('/', [RetentionmanagementController::class,'index']);
    Route::get('getRetentionChargesInvoicesdetails', [RetentionmanagementController::class,'getRetentionChargesInvoicesdetails']);
   
    
    Route::get('generateSingleProductRetentionCharge', [RetentionmanagementController::class,'generateSingleProductRetentionCharge']);

    Route::get('generateProductRetentionCharges', [RetentionmanagementController::class,'generateProductRetentionCharges']);
    Route::get('generateProductRetentionPenalty', [RetentionmanagementController::class,'generateProductRetentionPenalty']);

    //post notifications
    Route::get('sendProductRetentionChargesNotifications', [RetentionmanagementController::class,'sendProductRetentionChargesNotifications']);
    Route::get('getRetentionReport', [RetentionmanagementController::class,'getRetentionReport']);
    Route::get('exportRevenueReportsData', [RetentionmanagementController::class,'exportRevenueReportsData']); 




    //Bomra
    Route::get('getApplicationRaisedInvoices', [RevenuemanagementController::class, 'getApplicationRaisedInvoices']);
    Route::get('getNewInvoiceQuotation', [RevenuemanagementController::class, 'getNewInvoiceQuotation']);
    Route::post('saveonlineapplicationreceiceinvoiceDetails', [RevenuemanagementController::class, 'saveonlineapplicationreceiceinvoiceDetails']);
    Route::post('checkApplicationInvoiceBalance', [RevenuemanagementController::class, 'checkApplicationInvoiceBalance']);
    Route::get('getApplicationPaymentDetails', [RevenuemanagementController::class, 'getApplicationPaymentDetails']);
    Route::get('getApplicationApplicantDetails', [RevenuemanagementController::class, 'getApplicationApplicantDetails']);
    Route::get('checkInvoicePaymentsLimit', [RevenuemanagementController::class, 'checkInvoicePaymentsLimit']);
    Route::post('saveApplicationPaymentDetails', [RevenuemanagementController::class, 'saveApplicationPaymentDetails']);
    Route::post('shareQuotewithCustomer', [RevenuemanagementController::class, 'shareQuotewithCustomer']);
    Route::post('generateInvoiceBasedonQuote', [RevenuemanagementController::class, 'generateInvoiceBasedonQuote']);
    Route::post('approveSelectedQuote', [RevenuemanagementController::class, 'approveSelectedQuote']);
    Route::get('getApprovedInvoiceQuotation', [RevenuemanagementController::class, 'getApprovedInvoiceQuotation']);
    Route::post('saveAdvancedCustomerReceivingDetails', [RevenuemanagementController::class, 'saveAdvancedCustomerReceivingDetails']);
    Route::get('prepareadvancedApplicationReceiving', [RevenuemanagementController::class, 'prepareadvancedApplicationReceiving']);
    Route::get('getAdvancedCustomersApplications', [RevenuemanagementController::class, 'getAdvancedCustomersApplications']);
    Route::post('onSaveAdvancedCustomerApproval', [RevenuemanagementController::class, 'onSaveAdvancedCustomerApproval']);
    Route::get('getCustomerDashboardApplications', [RevenuemanagementController::class, 'getCustomerDashboardApplications']);
    Route::get('getCustomerInvoices', [RevenuemanagementController::class, 'getCustomerInvoices']);
    Route::post('saveInvoiceRefundReceivingDetails', [RevenuemanagementController::class, 'saveInvoiceRefundReceivingDetails']);
    Route::get('getAdvancedCustomerApplicationMoreDetails', [RevenuemanagementController::class, 'getAdvancedCustomerApplicationMoreDetails']);
    Route::post('saveRefundInvoicesDetails', [RevenuemanagementController::class, 'saveRefundInvoicesDetails']);
    Route::get('getApprovedAdvancedCustomersApplications', [RevenuemanagementController::class, 'getApprovedAdvancedCustomersApplications']);
    Route::get('getRefundApplicationInvoices', [RevenuemanagementController::class, 'getRefundApplicationInvoices']);
    Route::get('prepareadvancedCustomerApplicationReceiving', [RevenuemanagementController::class, 'prepareadvancedCustomerApplicationReceiving']);
    Route::get('getAppliedRefundInvoices', [RevenuemanagementController::class, 'getAppliedRefundInvoices']);
    Route::get('getRevenueRefundApplicationMoreDetails', [RevenuemanagementController::class, 'getRevenueRefundApplicationMoreDetails']);
    Route::post('onRefundInvoicesApproval', [RevenuemanagementController::class, 'onRefundInvoicesApproval']);
    Route::get('getCustomerReceivedAmount', [RevenuemanagementController::class, 'getCustomerReceivedAmount']);
    Route::get('getIssuedInvoicesList', [RevenuemanagementController::class, 'getIssuedInvoicesList']);
    Route::get('getAccountBalances', [RevenuemanagementController::class, 'getAccountBalances']);
    Route::get('getApprovedRefundsList', [RevenuemanagementController::class, 'getApprovedRefundsList']);
    //
     Route::get('getApprovedRefundsList', [RevenuemanagementController::class, 'getApprovedRefundsList']);
     Route::get('getIssuedInvoicesList', [RevenuemanagementController::class, 'getIssuedInvoicesList']);
     Route::get('getCustomerList', [RevenuemanagementController::class, 'getCustomerList']);
     

      
});


   