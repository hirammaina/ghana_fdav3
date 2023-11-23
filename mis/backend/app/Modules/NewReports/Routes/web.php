<?php

use App\Modules\NewReports\Http\Controllers\NewReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('newreports')->group(function () {
        Route::controller(NewReportsController::class)->group(function () {
          Route::get('getControlledDrugsSubModules', 'getControlledDrugsSubModules');
          Route::get('getControlledDrugsPermitType', 'getControlledDrugsPermitType');
         Route::get('getPermitType', 'getPermitType');
         //product routes
         Route::get('getProductSummaryReport', 'getProductSummaryReport');
         Route::get('printProductSummaryReport', 'printProductSummaryReport');
         Route::get('productDetailedReportPreview', 'productDetailedReportPreview');
         Route::get('exportDetailedReport', 'exportDetailedReport');
         Route::get('exportProductSummaryReport', 'exportProductSummaryReport');
         Route::get('printProductDetailedReport', 'printProductDetailedReport');
         Route::get('getProductSummaryCartesianReport', 'getProductSummaryCartesianReport');
     
         Route::get('getSectionParams', 'getSectionParams');
     
         //end product routes
         //start premise routes
          Route::get('getPremiseSummaryReport', 'getPremiseSummaryReport');
         Route::get('printPremiseSummaryReport', 'printPremiseSummaryReport');
         Route::get('premiseDetailedReportPreview', 'premiseDetailedReportPreview');
         Route::get('exportPremiseSummaryReport', 'exportPremiseSummaryReport');
         Route::get('getPremiseSummaryCartesianReport', 'getPremiseSummaryCartesianReport');
          //start import export routes 
          Route::get('getImportExportSummaryReport', 'getImportExportSummaryReport');
         Route::get('printImportExportSummaryReport', 'printImportExportSummaryReport');
         Route::get('importExportDetailedReportPreview', 'importExportDetailedReportPreview');
         Route::get('importExportSummaryReportExport', 'importExportSummaryReportExport');
         Route::get('getImportExportSummaryCartesianReport', 'getImportExportSummaryCartesianReport');
          //start Gmp report routes 
         Route::get('getGmpSummaryReport', 'getGmpSummaryReport');
         Route::get('printGmpSummaryReport', 'printGmpSummaryReport');
         Route::get('gmpDetailedReportPreview', 'gmpDetailedReportPreview');
         Route::get('gmpSummaryReportExport', 'gmpSummaryReportExport');
         Route::get('getGmpSummaryCartesianReport', 'getGmpSummaryCartesianReport');
     
         //start Clinical Trial report routes 
         Route::get('getClinicalTrialSummaryReport', 'getClinicalTrialSummaryReport');
         Route::get('printClinicalTrialSummaryReport', 'printClinicalTrialSummaryReport');
         Route::get('clinicalTrialDetailedReportPreview', 'clinicalTrialDetailedReportPreview');
         Route::get('clinicalTrialSummaryReportExport', 'clinicalTrialSummaryReportExport');
         Route::get('getClinicalTrialSummaryCartesianReport', 'getClinicalTrialSummaryCartesianReport');
     
         //start Promotion & Advertisement report routes 
         Route::get('getPromotionAdvertisementSummaryReport', 'getPromotionAdvertisementSummaryReport');
         Route::get('printPromotionAdvertisementSummaryReport', 'printPromotionAdvertisementSummaryReport');
         Route::get('promotionAdvertisementDetailedReportPreview', 'promotionAdvertisementDetailedReportPreview');
         Route::get('promotionAdvertisementSummaryReportExport', 'promotionAdvertisementSummaryReportExport');
         Route::get('getPromotionAdvertisementSummaryCartesianReport', 'getPromotionAdvertisementSummaryCartesianReport');
     
     
         //start Disposal report routes 
         Route::get('getDisposalSummaryReport', 'getDisposalSummaryReport');
         Route::get('printDisposalSummaryReport', 'printDisposalSummaryReport');
         Route::get('disposalDetailedReportPreview', 'disposalDetailedReportPreview');
         Route::get('disposalSummaryReportExport', 'disposalSummaryReportExport');
         Route::get('getDisposalSummaryCartesianReport', 'getDisposalSummaryCartesianReport');
     
     
          //start Controlled Drugs routes 
          Route::get('getControlledDrugsImportPermitSummaryReport', 'getControlledDrugsImportPermitSummaryReport');
         Route::get('printControlledDrugsImportPermitSummaryReport', 'printControlledDrugsImportPermitSummaryReport');
         Route::get('controlledDrugsImportPermitDetailedReportPreview', 'controlledDrugsImportPermitDetailedReportPreview');
         Route::get('controlledDrugsImportPermitSummaryReportExport', 'controlledDrugsImportPermitSummaryReportExport');
         Route::get('getControlledDrugsImportPermitSummaryCartesianReport', 'getControlledDrugsImportPermitSummaryCartesianReport');
         //Order of supply and Approval Certificate
           Route::get('getCertificateOrderSummaryReport', 'getCertificateOrderSummaryReport');
         Route::get('printCertificateOrderSummaryReport', 'printCertificateOrderSummaryReport');
         Route::get('controlledDrugsDetailedReportPreview', 'controlledDrugsDetailedReportPreview');
         Route::get('certificateOrderSummaryReportExport', 'certificateOrderSummaryReportExport');
         Route::get('getCertificateOrderSummaryCartesianReport', 'getCertificateOrderSummaryCartesianReport');
         Route::get('getImportExportPermitType', 'getImportExportPermitType');
        
         Route::get('getPOEImportExportSummaryReport', 'getPOEImportExportSummaryReport');
         Route::get('printPOEImportExportSummary', 'printPOEImportExportSummary');
         Route::get('importExportPOESummaryReportExport', 'importExportPOESummaryReportExport');
        
        
    
        });

    });
});

// Route::group(['middleware' => 'web', 'prefix' => 'newreports', 'namespace' => 'App\\Modules\NewReports\Http\Controllers'], function()
// { 
//     Route::get('getControlledDrugsSubModules', 'NewReportsController@getControlledDrugsSubModules');
//      Route::get('getControlledDrugsPermitType', 'NewReportsController@getControlledDrugsPermitType');
//     Route::get('getPermitType', 'NewReportsController@getPermitType');
//     //product routes
//     Route::get('getProductSummaryReport', 'NewReportsController@getProductSummaryReport');
//     Route::get('printProductSummaryReport', 'NewReportsController@printProductSummaryReport');
//     Route::get('productDetailedReportPreview', 'NewReportsController@productDetailedReportPreview');
//     Route::get('exportDetailedReport', 'NewReportsController@exportDetailedReport');
//     Route::get('exportProductSummaryReport', 'NewReportsController@exportProductSummaryReport');
//     Route::get('printProductDetailedReport', 'NewReportsController@printProductDetailedReport');
//     Route::get('getProductSummaryCartesianReport', 'NewReportsController@getProductSummaryCartesianReport');

//     Route::get('getSectionParams', 'NewReportsController@getSectionParams');

//     //end product routes
//     //start premise routes
//      Route::get('getPremiseSummaryReport', 'NewReportsController@getPremiseSummaryReport');
//     Route::get('printPremiseSummaryReport', 'NewReportsController@printPremiseSummaryReport');
//     Route::get('premiseDetailedReportPreview', 'NewReportsController@premiseDetailedReportPreview');
//     Route::get('exportPremiseSummaryReport', 'NewReportsController@exportPremiseSummaryReport');
//     Route::get('getPremiseSummaryCartesianReport', 'NewReportsController@getPremiseSummaryCartesianReport');
//      //start import export routes 
//      Route::get('getImportExportSummaryReport', 'NewReportsController@getImportExportSummaryReport');
//     Route::get('printImportExportSummaryReport', 'NewReportsController@printImportExportSummaryReport');
//     Route::get('importExportDetailedReportPreview', 'NewReportsController@importExportDetailedReportPreview');
//     Route::get('importExportSummaryReportExport', 'NewReportsController@importExportSummaryReportExport');
//     Route::get('getImportExportSummaryCartesianReport', 'NewReportsController@getImportExportSummaryCartesianReport');
//      //start Gmp report routes 
//     Route::get('getGmpSummaryReport', 'NewReportsController@getGmpSummaryReport');
//     Route::get('printGmpSummaryReport', 'NewReportsController@printGmpSummaryReport');
//     Route::get('gmpDetailedReportPreview', 'NewReportsController@gmpDetailedReportPreview');
//     Route::get('gmpSummaryReportExport', 'NewReportsController@gmpSummaryReportExport');
//     Route::get('getGmpSummaryCartesianReport', 'NewReportsController@getGmpSummaryCartesianReport');

//     //start Clinical Trial report routes 
//     Route::get('getClinicalTrialSummaryReport', 'NewReportsController@getClinicalTrialSummaryReport');
//     Route::get('printClinicalTrialSummaryReport', 'NewReportsController@printClinicalTrialSummaryReport');
//     Route::get('clinicalTrialDetailedReportPreview', 'NewReportsController@clinicalTrialDetailedReportPreview');
//     Route::get('clinicalTrialSummaryReportExport', 'NewReportsController@clinicalTrialSummaryReportExport');
//     Route::get('getClinicalTrialSummaryCartesianReport', 'NewReportsController@getClinicalTrialSummaryCartesianReport');

//     //start Promotion & Advertisement report routes 
//     Route::get('getPromotionAdvertisementSummaryReport', 'NewReportsController@getPromotionAdvertisementSummaryReport');
//     Route::get('printPromotionAdvertisementSummaryReport', 'NewReportsController@printPromotionAdvertisementSummaryReport');
//     Route::get('promotionAdvertisementDetailedReportPreview', 'NewReportsController@promotionAdvertisementDetailedReportPreview');
//     Route::get('promotionAdvertisementSummaryReportExport', 'NewReportsController@promotionAdvertisementSummaryReportExport');
//     Route::get('getPromotionAdvertisementSummaryCartesianReport', 'NewReportsController@getPromotionAdvertisementSummaryCartesianReport');


//     //start Disposal report routes 
//     Route::get('getDisposalSummaryReport', 'NewReportsController@getDisposalSummaryReport');
//     Route::get('printDisposalSummaryReport', 'NewReportsController@printDisposalSummaryReport');
//     Route::get('disposalDetailedReportPreview', 'NewReportsController@disposalDetailedReportPreview');
//     Route::get('disposalSummaryReportExport', 'NewReportsController@disposalSummaryReportExport');
//     Route::get('getDisposalSummaryCartesianReport', 'NewReportsController@getDisposalSummaryCartesianReport');


//      //start Controlled Drugs routes 
//      Route::get('getControlledDrugsImportPermitSummaryReport', 'NewReportsController@getControlledDrugsImportPermitSummaryReport');
//     Route::get('printControlledDrugsImportPermitSummaryReport', 'NewReportsController@printControlledDrugsImportPermitSummaryReport');
//     Route::get('controlledDrugsImportPermitDetailedReportPreview', 'NewReportsController@controlledDrugsImportPermitDetailedReportPreview');
//     Route::get('controlledDrugsImportPermitSummaryReportExport', 'NewReportsController@controlledDrugsImportPermitSummaryReportExport');
//     Route::get('getControlledDrugsImportPermitSummaryCartesianReport', 'NewReportsController@getControlledDrugsImportPermitSummaryCartesianReport');
//     //Order of supply and Approval Certificate
//       Route::get('getCertificateOrderSummaryReport', 'NewReportsController@getCertificateOrderSummaryReport');
//     Route::get('printCertificateOrderSummaryReport', 'NewReportsController@printCertificateOrderSummaryReport');
//     Route::get('controlledDrugsDetailedReportPreview', 'NewReportsController@controlledDrugsDetailedReportPreview');
//     Route::get('certificateOrderSummaryReportExport', 'NewReportsController@certificateOrderSummaryReportExport');
//     Route::get('getCertificateOrderSummaryCartesianReport', 'NewReportsController@getCertificateOrderSummaryCartesianReport');
//     Route::get('getImportExportPermitType', 'NewReportsController@getImportExportPermitType');
   
//     Route::get('getPOEImportExportSummaryReport', 'NewReportsController@getPOEImportExportSummaryReport');
//     Route::get('printPOEImportExportSummary', 'NewReportsController@printPOEImportExportSummary');
//     Route::get('importExportPOESummaryReportExport', 'NewReportsController@importExportPOESummaryReportExport');
   
    
// });