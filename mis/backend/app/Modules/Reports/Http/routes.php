<?php

use App\Modules\Reports\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('reports')->group(function () {
        Route::controller(ReportsController::class)->group(function () {
          Route::get('/', 'index');
          Route::get('generateReport','generateReport');
          Route::get('generateApplicationInvoice','generateApplicationInvoice');
          Route::get('generateApplicationReceipt','generateApplicationReceipt');
          Route::get('generateApplicationReceipts','generateApplicationReceipt');
         
          Route::get('generatePremiseCertificate','generatePremiseCertificate');
          Route::get('generatePremisePermit','generatePremisePermit');
          Route::get('generateProductRegCertificate','generateProductRegCertificate');
          Route::get('generateGmpCertificate','generateGmpCertificate');
          Route::get('generateGmpApprovalLetter','generateGmpApprovalLetter');
          Route::get('generateClinicalTrialCertificate','generateClinicalTrialCertificate');
          Route::get('genenerateImportExportPermit','genenerateImportExportPermit');
      
          
          Route::get('generateProductNotificationCertificate','generateProductNotificationCertificate');
          Route::get('generateProductNotificationApprovalLetter','generateProductNotificationApprovalLetter');
          Route::get('generateProductRejectionLetter','generateProductRejectionLetter');
         
         //added for system report view
         Route::get('getProductsReport','getProductsReport');
         Route::get('funcExportInspectedpermits','funcExportInspectedpermits');
         Route::get('printSampleSubmissionReport','printSampleSubmissionReport');
         Route::get('generateProductEvaluationReport','generateProductEvaluationReport');
         Route::get('generateProductAuditReport','generateProductAuditReport');
      
         
         Route::get('print_test_report','print_test_report');
      
         Route::get('printProductInformationReport','printProductInformationReport');
         Route::get('printHospitalNarcoticsPermit','printHospitalNarcoticsPermit');
         Route::get('generateProductsNotificationRpt','generateProductsNotificationRpt');
         Route::get('printRetentionPaymentsStatement','printRetentionPaymentsStatement');
          
         Route::get('disposalCertificate','disposalCertificate');
         Route::get('getServiceCharterReportDetails','getServiceCharterReportDetails');
         Route::get('generatePromotionalRegCertificate','generatePromotionalRegCertificate');
         Route::get('printPromotionalScreeningReport','printPromotionalScreeningReport');
         
         
         Route::get('funcPrintServiceCharterSectionsSummaryRpt','funcPrintServiceCharterSectionsSummaryRpt');
         Route::get('funcPrintServiceCharterSummaryRpt','funcPrintServiceCharterSummaryRpt');
         Route::get('funcExportServiceCharterSummaryRpt','funcExportServiceCharterSummaryRpt');
         
         Route::get('printSpecialRequestScreeningfrm','printSpecialRequestScreeningfrm');
         Route::get('generateBatchPaymentsStatement','generateBatchPaymentsStatement');
         Route::get('generateRetentionBatchPaymentsStatement','generateRetentionBatchPaymentsStatement');
         Route::get('generateRetentionBatchInvoiceStatement','generateRetentionBatchInvoiceStatement');
         Route::get('generateDisposalpermit','generateDisposalpermit');
         Route::get('generateBatchInvoiceStatement','generateBatchInvoiceStatement');
         Route::get('printRequestForAdditionalInformation','printRequestForAdditionalInformation');
         
         Route::get('onDownloadunfitProductstemplate','onDownloadunfitProductstemplate');
         Route::get('onDownloadImportInvoiceProductstemplate','onDownloadImportInvoiceProductstemplate');
         
         Route::get('generateGroupedApplicationInvoice','generateGroupedApplicationInvoice');
           Route::get('generateGroupedApplicationReceipt','generateGroupedApplicationReceipt');
           Route::get('genenerateInspectionLicensePermit','genenerateInspectionLicensePermit');
           Route::get('getInspectedPoeinspectionProductsdetails','getInspectedPoeinspectionProductsdetails');
         
      
      
          Route::get('funcDownloadApprovVisaProductsProducts','funcDownloadApprovVisaProductsProducts');
        
        
        
    
        });

    });
});



// Route::group(['middleware' => 'web', 'prefix' => 'reports', 'namespace' => 'App\\Modules\Reports\Http\Controllers'], function()
// {
//     Route::get('/', 'ReportsController@index');
//     Route::get('generateReport','ReportsController@generateReport');
//     Route::get('generateApplicationInvoice','ReportsController@generateApplicationInvoice');
//     Route::get('generateApplicationReceipt','ReportsController@generateApplicationReceipt');
//     Route::get('generateApplicationReceipts','ReportsController@generateApplicationReceipt');
   
//     Route::get('generatePremiseCertificate','ReportsController@generatePremiseCertificate');
//     Route::get('generatePremisePermit','ReportsController@generatePremisePermit');
//     Route::get('generateProductRegCertificate','ReportsController@generateProductRegCertificate');
//     Route::get('generateGmpCertificate','ReportsController@generateGmpCertificate');
//     Route::get('generateGmpApprovalLetter','ReportsController@generateGmpApprovalLetter');
//     Route::get('generateClinicalTrialCertificate','ReportsController@generateClinicalTrialCertificate');
//     Route::get('genenerateImportExportPermit','ReportsController@genenerateImportExportPermit');

    
//     Route::get('generateProductNotificationCertificate','ReportsController@generateProductNotificationCertificate');
//     Route::get('generateProductNotificationApprovalLetter','ReportsController@generateProductNotificationApprovalLetter');
//     Route::get('generateProductRejectionLetter','ReportsController@generateProductRejectionLetter');
   
//    //added for system report view
//    Route::get('getProductsReport','ReportsController@getProductsReport');
//    Route::get('funcExportInspectedpermits','ReportsController@funcExportInspectedpermits');
//    Route::get('printSampleSubmissionReport','ReportsController@printSampleSubmissionReport');
//    Route::get('generateProductEvaluationReport','ReportsController@generateProductEvaluationReport');
//    Route::get('generateProductAuditReport','ReportsController@generateProductAuditReport');

   
//    Route::get('print_test_report','ReportsController@print_test_report');

//    Route::get('printProductInformationReport','ReportsController@printProductInformationReport');
//    Route::get('printHospitalNarcoticsPermit','ReportsController@printHospitalNarcoticsPermit');
//    Route::get('generateProductsNotificationRpt','ReportsController@generateProductsNotificationRpt');
//    Route::get('printRetentionPaymentsStatement','ReportsController@printRetentionPaymentsStatement');
    
//    Route::get('disposalCertificate','ReportsController@disposalCertificate');
//    Route::get('getServiceCharterReportDetails','ReportsController@getServiceCharterReportDetails');
//    Route::get('generatePromotionalRegCertificate','ReportsController@generatePromotionalRegCertificate');
//    Route::get('printPromotionalScreeningReport','ReportsController@printPromotionalScreeningReport');
   
   
//    Route::get('funcPrintServiceCharterSectionsSummaryRpt','ReportsController@funcPrintServiceCharterSectionsSummaryRpt');
//    Route::get('funcPrintServiceCharterSummaryRpt','ReportsController@funcPrintServiceCharterSummaryRpt');
//    Route::get('funcExportServiceCharterSummaryRpt','ReportsController@funcExportServiceCharterSummaryRpt');
   
//    Route::get('printSpecialRequestScreeningfrm','ReportsController@printSpecialRequestScreeningfrm');
//    Route::get('generateBatchPaymentsStatement','ReportsController@generateBatchPaymentsStatement');
//    Route::get('generateRetentionBatchPaymentsStatement','ReportsController@generateRetentionBatchPaymentsStatement');
//    Route::get('generateRetentionBatchInvoiceStatement','ReportsController@generateRetentionBatchInvoiceStatement');
//    Route::get('generateDisposalpermit','ReportsController@generateDisposalpermit');
//    Route::get('generateBatchInvoiceStatement','ReportsController@generateBatchInvoiceStatement');
//    Route::get('printRequestForAdditionalInformation','ReportsController@printRequestForAdditionalInformation');
   
//    Route::get('onDownloadunfitProductstemplate','ReportsController@onDownloadunfitProductstemplate');
//    Route::get('onDownloadImportInvoiceProductstemplate','ReportsController@onDownloadImportInvoiceProductstemplate');
   
//    Route::get('generateGroupedApplicationInvoice','ReportsController@generateGroupedApplicationInvoice');
//      Route::get('generateGroupedApplicationReceipt','ReportsController@generateGroupedApplicationReceipt');
//      Route::get('genenerateInspectionLicensePermit','ReportsController@genenerateInspectionLicensePermit');
//      Route::get('getInspectedPoeinspectionProductsdetails','ReportsController@getInspectedPoeinspectionProductsdetails');
   


//     Route::get('funcDownloadApprovVisaProductsProducts','ReportsController@funcDownloadApprovVisaProductsProducts');
  
// });
