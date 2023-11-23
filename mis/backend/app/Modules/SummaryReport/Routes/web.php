<?php

use App\Modules\SummaryReport\Http\Controllers\SummaryReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('summaryreport')->group(function () {
        Route::controller(SummaryReportController::class)->group(function () {
            Route::get('getSummaryReports', 'getSummaryReports');
            Route::get('GetChartProductApplications', 'GetChartProductApplications');
            Route::get('getGridRevenueReport', 'getGridRevenueReport');
            Route::get('getChatRevenueReport', 'getChatRevenueReport');
            Route::get('exportPaymentDetails', 'exportPaymentDetails');
            Route::get('getUploadedDocs', 'getUploadedDocumentDetails');
            Route::get('getAgeAnalysis', 'getAgeAnalysis');
            Route::get('exportSummaryAgeAnalysis', 'exportSummaryAgeAnalysis');
           
            
            //revenue report routes
            Route::get('getRevenueSummaryReports', 'getRevenueSummaryReports');
            Route::get('getDailyFinanceTrans', 'getDailyFinanceTrans');
            Route::get('getGLCodedRevenueReport', 'getGLCodedRevenueReport');
            Route::get('getPaymentReversalsSummaryReports', 'getPaymentReversalsSummaryReports');
            Route::get('ExportGLCodedReport', 'ExportGLCodedReport');
            Route::get('exportDailyTransactions', 'exportDailyTransactions');
            Route::get('printRevenueSummaryReport', 'printRevenueSummaryReport');
            Route::get('printGlSummaryReport', 'printGlSummaryReport');
        
            //new Reports
            Route::get('getProductRegistrationCartesianReport', 'getProductRegistrationCartesianReport');
            Route::get('getProductGridRegistrationReport', 'getProductGridRegistrationReport');
            Route::get('getProductRegistrationAgeAnalysisReport', 'getProductRegistrationAgeAnalysisReport');
        
            Route::get('getPremiseRegistrationCartesianReport', 'getPremiseRegistrationCartesianReport');
            Route::get('getPremiseGridRegistrationReport', 'getPremiseGridRegistrationReport');
            Route::get('getPremiseRegistrationAgeAnalysisReport', 'getPremiseRegistrationAgeAnalysisReport');
        
            Route::get('getGmpRegistrationCartesianReport', 'getGmpRegistrationCartesianReport');
            Route::get('getGmpGridRegistrationReport', 'getGmpGridRegistrationReport');
            Route::get('getGmpRegistrationAgeAnalysisReport', 'getGmpRegistrationAgeAnalysisReport');
        
        
            Route::get('getClinicalTrialRegistrationCartesianReport', 'getClinicalTrialRegistrationCartesianReport');
            Route::get('getClinicalTrialGridRegistrationReport', 'getClinicalTrialGridRegistrationReport');
            Route::get('getClinicalTrialRegistrationAgeAnalysisReport', 'getClinicalTrialRegistrationAgeAnalysisReport');
        
             Route::get('getImportExportRegistrationCartesianReport', 'getImportExportRegistrationCartesianReport');
            Route::get('getImportExportGridRegistrationReport', 'getImportExportGridRegistrationReport');
            Route::get('getImportExportRegistrationAgeAnalysisReport', 'getImportExportRegistrationAgeAnalysisReport');
        
            Route::get('getPromAdvertRegistrationCartesianReport', 'getPromAdvertRegistrationCartesianReport');
            Route::get('getPromAdvertGridRegistrationReport', 'getPromAdvertGridRegistrationReport');
            Route::get('getPromAdvertRegistrationAgeAnalysisReport', 'getPromAdvertRegistrationAgeAnalysisReport');
        
            Route::get('getDisposalRegistrationCartesianReport', 'getDisposalRegistrationCartesianReport');
            Route::get('getDisposalGridRegistrationReport', 'getDisposalGridRegistrationReport');
            Route::get('getDisposalRegistrationAgeAnalysisReport', 'getDisposalRegistrationAgeAnalysisReport');
        
            
            Route::get('exportProductDefinedColumns', 'exportProductDefinedColumns');
            Route::get('exportPremiseDefinedColumns', 'exportPremiseDefinedColumns');
            Route::get('exportGmpDefinedColumns', 'exportGmpDefinedColumns');
            Route::get('exportImportExportDefinedColumns', 'exportImportExportDefinedColumns');
            Route::get('exportClinicalTrialDefinedColumns', 'exportClinicalTrialDefinedColumns');
            Route::get('exportPromAdvertDefinedColumns', 'exportPromAdvertDefinedColumns');
            Route::get('exportDisposalDefinedColumns', 'exportDisposalDefinedColumns');
        
            Route::get('getPremiseZonalGridReports', 'getPremiseZonalGridReports');
            Route::get('getProductAssessmentGridReports', 'getProductAssessmentGridReports');
            Route::get('getProductClassificationGridReports', 'getProductClassificationGridReports');
            Route::get('getDisposalTypeGridReports', 'getDisposalTypeGridReports');
            Route::get('getImportExportPermitGridReports', 'getImportExportPermitGridReports');
            Route::get('getAllUploadedDocumentDetails', 'getAllUploadedDocumentDetails');
            
            //registered applicantions
            Route::get('getregisteredApplicationsGridReports', 'getregisteredApplicationsGridReports');
            Route::get('getRegisteredApplicationsCounterGridReports', 'getRegisteredApplicationsCounterGridReports');
            Route::get('getRegistrationApplicableModules', 'getRegistrationApplicableModules');
            
            //PMS
            Route::get('getAnnualPMSImplementationReport', 'getAnnualPMSImplementationReport');
            Route::get('getPMSManufacturerReport', 'getPMSManufacturerReport');
            Route::get('ExportPmsReport', 'ExportPmsReport');
            Route::get('ExportPmsManufacturerReport', 'ExportPmsManufacturerReport');
            
            Route::get('getApplicationReceiptsReport', 'getApplicationReceiptsReport');
            Route::get('generatedSystemReport', 'generatedSystemReport');
            Route::get('getPremiseRegisterReport', 'getPremiseRegisterReport');
            Route::get('getPremiseRegisterChart', 'getPremiseRegisterChart');
            Route::get('getBusinessTypeScaleReport', 'getBusinessTypeScaleReport');
            
        
            //general
            Route::get('exportData', 'exportData');
            Route::get('exportDefinedColumnData', 'exportDefinedColumnData');
            Route::get('printProductRegSummary', 'printProductRegSummary');
            Route::get('printPremiseRegistrationReport', 'printPremiseRegistrationReport');
            Route::get('printPremiseRegister', 'printPremiseRegister');
            Route::get('printIERegSummaryReport', 'printIERegSummaryReport');
            Route::get('printGMPRegSummaryReport', 'printGMPRegSummaryReport');
            Route::get('printCTRegSummaryReport', 'printCTRegSummaryReport');
            
            Route::get('printPromAdvertRegSummaryReport', 'printPromAdvertRegSummaryReport');
            Route::get('printPremiseZonalSummaryReport', 'printPremiseZonalSummaryReport');
            Route::get('exportPremiseZonalSummaryData', 'exportPremiseZonalSummaryData');
            Route::get('printDisposalSummaryReport', 'printDisposalSummaryReport');
            Route::get('getModuleRegReport', 'getModuleRegReport');
            Route::get('printModuleRegReport', 'printModuleRegReport');
            Route::get('exportModuleRegReportData', 'exportModuleRegReportData');
            Route::get('printSectionRegReport', 'printSectionRegReport');
            Route::get('getSectionRegReport', 'getSectionRegReport');
            Route::get('getRequestCreditNoteSummaryReport', 'getRequestCreditNoteSummaryReport');
            Route::get('getApprovedCreditNoteSummaryReport', 'getApprovedCreditNoteSummaryReport');
            
             Route::get('getPMSZonalReport', 'getPMSZonalReport');
             Route::get('printPMSZonalReport', 'printPMSZonalReport');
        
             Route::get('getApprovedPaymentsREversalsummaryReport', 'getApprovedPaymentsREversalsummaryReport');
        
        
    
        });

    });
});


// Route::group(['middleware' => 'web', 'prefix' => 'summaryreport', 'namespace' => 'App\\Modules\SummaryReport\Http\Controllers'], function()
// {
//     Route::get('getSummaryReports', 'SummaryReportController@getSummaryReports');
//     Route::get('GetChartProductApplications', 'SummaryReportController@GetChartProductApplications');
//     Route::get('getGridRevenueReport', 'SummaryReportController@getGridRevenueReport');
//     Route::get('getChatRevenueReport', 'SummaryReportController@getChatRevenueReport');
//     Route::get('exportPaymentDetails', 'SummaryReportController@exportPaymentDetails');
//     Route::get('getUploadedDocs', 'SummaryReportController@getUploadedDocumentDetails');
//     Route::get('getAgeAnalysis', 'SummaryReportController@getAgeAnalysis');
//     Route::get('exportSummaryAgeAnalysis', 'SummaryReportController@exportSummaryAgeAnalysis');
   
    
//     //revenue report routes
//     Route::get('getRevenueSummaryReports', 'SummaryReportController@getRevenueSummaryReports');
//     Route::get('getDailyFinanceTrans', 'SummaryReportController@getDailyFinanceTrans');
//     Route::get('getGLCodedRevenueReport', 'SummaryReportController@getGLCodedRevenueReport');
//     Route::get('getPaymentReversalsSummaryReports', 'SummaryReportController@getPaymentReversalsSummaryReports');
//     Route::get('ExportGLCodedReport', 'SummaryReportController@ExportGLCodedReport');
//     Route::get('exportDailyTransactions', 'SummaryReportController@exportDailyTransactions');
//     Route::get('printRevenueSummaryReport', 'SummaryReportController@printRevenueSummaryReport');
//     Route::get('printGlSummaryReport', 'SummaryReportController@printGlSummaryReport');

//     //new Reports
//     Route::get('getProductRegistrationCartesianReport', 'SummaryReportController@getProductRegistrationCartesianReport');
//     Route::get('getProductGridRegistrationReport', 'SummaryReportController@getProductGridRegistrationReport');
//     Route::get('getProductRegistrationAgeAnalysisReport', 'SummaryReportController@getProductRegistrationAgeAnalysisReport');

//     Route::get('getPremiseRegistrationCartesianReport', 'SummaryReportController@getPremiseRegistrationCartesianReport');
//     Route::get('getPremiseGridRegistrationReport', 'SummaryReportController@getPremiseGridRegistrationReport');
//     Route::get('getPremiseRegistrationAgeAnalysisReport', 'SummaryReportController@getPremiseRegistrationAgeAnalysisReport');

//     Route::get('getGmpRegistrationCartesianReport', 'SummaryReportController@getGmpRegistrationCartesianReport');
//     Route::get('getGmpGridRegistrationReport', 'SummaryReportController@getGmpGridRegistrationReport');
//     Route::get('getGmpRegistrationAgeAnalysisReport', 'SummaryReportController@getGmpRegistrationAgeAnalysisReport');


//     Route::get('getClinicalTrialRegistrationCartesianReport', 'SummaryReportController@getClinicalTrialRegistrationCartesianReport');
//     Route::get('getClinicalTrialGridRegistrationReport', 'SummaryReportController@getClinicalTrialGridRegistrationReport');
//     Route::get('getClinicalTrialRegistrationAgeAnalysisReport', 'SummaryReportController@getClinicalTrialRegistrationAgeAnalysisReport');

//      Route::get('getImportExportRegistrationCartesianReport', 'SummaryReportController@getImportExportRegistrationCartesianReport');
//     Route::get('getImportExportGridRegistrationReport', 'SummaryReportController@getImportExportGridRegistrationReport');
//     Route::get('getImportExportRegistrationAgeAnalysisReport', 'SummaryReportController@getImportExportRegistrationAgeAnalysisReport');

//     Route::get('getPromAdvertRegistrationCartesianReport', 'SummaryReportController@getPromAdvertRegistrationCartesianReport');
//     Route::get('getPromAdvertGridRegistrationReport', 'SummaryReportController@getPromAdvertGridRegistrationReport');
//     Route::get('getPromAdvertRegistrationAgeAnalysisReport', 'SummaryReportController@getPromAdvertRegistrationAgeAnalysisReport');

//     Route::get('getDisposalRegistrationCartesianReport', 'SummaryReportController@getDisposalRegistrationCartesianReport');
//     Route::get('getDisposalGridRegistrationReport', 'SummaryReportController@getDisposalGridRegistrationReport');
//     Route::get('getDisposalRegistrationAgeAnalysisReport', 'SummaryReportController@getDisposalRegistrationAgeAnalysisReport');

    
//     Route::get('exportProductDefinedColumns', 'SummaryReportController@exportProductDefinedColumns');
//     Route::get('exportPremiseDefinedColumns', 'SummaryReportController@exportPremiseDefinedColumns');
//     Route::get('exportGmpDefinedColumns', 'SummaryReportController@exportGmpDefinedColumns');
//     Route::get('exportImportExportDefinedColumns', 'SummaryReportController@exportImportExportDefinedColumns');
//     Route::get('exportClinicalTrialDefinedColumns', 'SummaryReportController@exportClinicalTrialDefinedColumns');
//     Route::get('exportPromAdvertDefinedColumns', 'SummaryReportController@exportPromAdvertDefinedColumns');
//     Route::get('exportDisposalDefinedColumns', 'SummaryReportController@exportDisposalDefinedColumns');

//     Route::get('getPremiseZonalGridReports', 'SummaryReportController@getPremiseZonalGridReports');
//     Route::get('getProductAssessmentGridReports', 'SummaryReportController@getProductAssessmentGridReports');
//     Route::get('getProductClassificationGridReports', 'SummaryReportController@getProductClassificationGridReports');
//     Route::get('getDisposalTypeGridReports', 'SummaryReportController@getDisposalTypeGridReports');
//     Route::get('getImportExportPermitGridReports', 'SummaryReportController@getImportExportPermitGridReports');
//     Route::get('getAllUploadedDocumentDetails', 'SummaryReportController@getAllUploadedDocumentDetails');
    
//     //registered applicantions
//     Route::get('getregisteredApplicationsGridReports', 'SummaryReportController@getregisteredApplicationsGridReports');
//     Route::get('getRegisteredApplicationsCounterGridReports', 'SummaryReportController@getRegisteredApplicationsCounterGridReports');
//     Route::get('getRegistrationApplicableModules', 'SummaryReportController@getRegistrationApplicableModules');
    
//     //PMS
//     Route::get('getAnnualPMSImplementationReport', 'SummaryReportController@getAnnualPMSImplementationReport');
//     Route::get('getPMSManufacturerReport', 'SummaryReportController@getPMSManufacturerReport');
//     Route::get('ExportPmsReport', 'SummaryReportController@ExportPmsReport');
//     Route::get('ExportPmsManufacturerReport', 'SummaryReportController@ExportPmsManufacturerReport');
    
//     Route::get('getApplicationReceiptsReport', 'SummaryReportController@getApplicationReceiptsReport');
//     Route::get('generatedSystemReport', 'SummaryReportController@generatedSystemReport');
//     Route::get('getPremiseRegisterReport', 'SummaryReportController@getPremiseRegisterReport');
//     Route::get('getPremiseRegisterChart', 'SummaryReportController@getPremiseRegisterChart');
//     Route::get('getBusinessTypeScaleReport', 'SummaryReportController@getBusinessTypeScaleReport');
    

//     //general
//     Route::get('exportData', 'SummaryReportController@exportData');
//     Route::get('exportDefinedColumnData', 'SummaryReportController@exportDefinedColumnData');
//     Route::get('printProductRegSummary', 'SummaryReportController@printProductRegSummary');
//     Route::get('printPremiseRegistrationReport', 'SummaryReportController@printPremiseRegistrationReport');
//     Route::get('printPremiseRegister', 'SummaryReportController@printPremiseRegister');
//     Route::get('printIERegSummaryReport', 'SummaryReportController@printIERegSummaryReport');
//     Route::get('printGMPRegSummaryReport', 'SummaryReportController@printGMPRegSummaryReport');
//     Route::get('printCTRegSummaryReport', 'SummaryReportController@printCTRegSummaryReport');
    
//     Route::get('printPromAdvertRegSummaryReport', 'SummaryReportController@printPromAdvertRegSummaryReport');
//     Route::get('printPremiseZonalSummaryReport', 'SummaryReportController@printPremiseZonalSummaryReport');
//     Route::get('exportPremiseZonalSummaryData', 'SummaryReportController@exportPremiseZonalSummaryData');
//     Route::get('printDisposalSummaryReport', 'SummaryReportController@printDisposalSummaryReport');
//     Route::get('getModuleRegReport', 'SummaryReportController@getModuleRegReport');
//     Route::get('printModuleRegReport', 'SummaryReportController@printModuleRegReport');
//     Route::get('exportModuleRegReportData', 'SummaryReportController@exportModuleRegReportData');
//     Route::get('printSectionRegReport', 'SummaryReportController@printSectionRegReport');
//     Route::get('getSectionRegReport', 'SummaryReportController@getSectionRegReport');
//     Route::get('getRequestCreditNoteSummaryReport', 'SummaryReportController@getRequestCreditNoteSummaryReport');
//     Route::get('getApprovedCreditNoteSummaryReport', 'SummaryReportController@getApprovedCreditNoteSummaryReport');
    
//      Route::get('getPMSZonalReport', 'SummaryReportController@getPMSZonalReport');
//      Route::get('printPMSZonalReport', 'SummaryReportController@printPMSZonalReport');

//      Route::get('getApprovedPaymentsREversalsummaryReport', 'SummaryReportController@getApprovedPaymentsREversalsummaryReport');
    
// });
