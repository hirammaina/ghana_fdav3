<?php
/**
 * @Author: Job.Murumba
 * @Date:   2023-11-22 17:01:27
 * @Last Modified by:   Job.Murumba
 * @Last Modified time: 2023-11-24 11:28:43
 */



use App\Modules\Importexportpermits\Http\Controllers\ImportexportpermitsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {


    Route::prefix('importexportpermits')->group(function () {
        Route::controller(ImportexportpermitsController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('getonlineimportexportappdetails', 'getonlineimportexportappdetails');
            Route::get('getpermitinspectionbookingdashboardstr', 'getpermitinspectionbookingdashboardstr');
            Route::get('getInspectedPermitsProducts', 'getInspectedPermitsProducts');
            Route::get('prepareOnlineImportExporPermitReceivingStage', 'prepareOnlineImportExporPermitReceivingStage');
            Route::get('prepareImportExporPermitReceivingStage', 'prepareImportExporPermitReceivingStage');

            Route::get('getManagerEvaluationApplications', 'getManagerEvaluationApplications');
            Route::get('getDisposalManagerEvaluationApplications', 'getDisposalManagerEvaluationApplications');
            Route::get('prepareReceivingpoeinspectionswizard', 'prepareReceivingpoeinspectionswizard');


            Route::get('getOnlineImportexportpermitsproductsDetails', 'getOnlineImportexportpermitsproductsDetails');
            Route::get('getImportexportpermitsapps', 'getImportexportpermitsapps');
            Route::get('getDeclaredImportExportPermitsApps', 'getDeclaredImportExportPermitsApps');

            Route::get('getImportExportPermitDeclarations', 'getImportExportPermitDeclarations');


            Route::get('getPreviousImportExportDocuploads', 'getPreviousImportExportDocuploads');
            Route::get('getPreviousPreviousScreeningData', 'getPreviousPreviousScreeningData');



            Route::get('getImportexportpermitsproductsDetails', 'getImportexportpermitsproductsDetails');

            Route::get('getRegisteredNonRegisteredProddetails', 'getRegisteredNonRegisteredProddetails');
            Route::get('getSenderreceiverinformation', 'getSenderreceiverinformation');
            Route::get('getTraderRegisteredPremisesDetails', 'getTraderRegisteredPremisesDetails');
            Route::get('prepapareImportpermitUniformStage', 'prepapareImportpermitUniformStage');
            Route::get('prepareImportInvoicingStage', 'prepareImportInvoicingStage');

            Route::get('getImportsInvoicingOtherDetails', 'getImportsInvoicingOtherDetails');
            Route::get('prepareNewImportExportPaymentStage', 'prepareNewImportExportPaymentStage');
            Route::get('getImportExportApprovedPermit', 'getImportExportApprovedPermit');

            Route::post('saveDisposalApplicationDetails', 'saveDisposalApplicationDetails');
            Route::post('updateDisposalApplicationDetails', 'updateDisposalApplicationDetails');

            Route::post('saveControlDrugsReceivingBaseDetails', 'saveControlDrugsReceivingBaseDetails');
            Route::post('saveImportPermittReceivingBaseDetails', 'saveImportPermittReceivingBaseDetails');
            Route::post('updateonlineImportPermittReceivingBaseDetails', 'updateonlineImportPermittReceivingBaseDetails');


            Route::post('onSavePermitProductsDetails', 'onSavePermitProductsDetails');

            Route::post('onSaveDisposalPermitProductsDetails', 'onSaveDisposalPermitProductsDetails');
            Route::post('onSaveImportPermitPremisesData', 'onSaveImportPermitPremisesData');

            Route::post('onSaveImportPermitSenderReceiverData', 'onSaveImportPermitSenderReceiverData');
            Route::post('onSavePermitinformation', 'onSavePermitinformation');
            Route::post('getPermitsApplicationMoreDetails', 'getPermitsApplicationMoreDetails');



            Route::get('getImportExportManagerReviewApplications', 'getImportExportManagerReviewApplications');
            Route::get('getNarcoticsPermitsManagerReviewApplications', 'getNarcoticsPermitsManagerReviewApplications');



            Route::get('getISpecialmportExportApprovalApplications', 'getISpecialmportExportApprovalApplications');
            Route::get('getOnlineDisposalApplications', 'getOnlineDisposalApplications');

            Route::get('getOnlineDisposalpermitsproductsDetails', 'getOnlineDisposalpermitsproductsDetails');
            Route::get('prepapreDisposalOnlineReceiving', 'prepapreDisposalOnlineReceiving');

            Route::get('getDisposalApplications', 'getDisposalApplications');
            Route::get('getDisposalpermitsproductsDetails', 'getDisposalpermitsproductsDetails');

            Route::get('getControlledImpproductsDetails', 'getControlledImpproductsDetails');


            Route::get('getDisposalPermitsApplicationMoreDetails', 'getDisposalPermitsApplicationMoreDetails');
            Route::get('getDisposalInspectors', 'getDisposalInspectors');
            Route::post('deleteDisposalInspectors', 'deleteDisposalInspectors');
            Route::post('onSaveDisposalinternalsupervisors', 'onSaveDisposalinternalsupervisors');

            Route::post('saveDisposalDestructionDetails', 'saveDisposalDestructionDetails');
            Route::get('getDisposalPermitApprovalApplications', 'getDisposalPermitApprovalApplications');
            Route::get('funcDrugsContentsCalculations', 'funcDrugsContentsCalculations');

            Route::get('getSummaryReports', 'getSummaryReports');

            Route::get('searchProductsInformation', 'searchProductsInformation');
            Route::get('prepareDisposalPermitReceivingStage', 'prepareDisposalPermitReceivingStage');
            Route::get('prepareNarcoticsPermitReceivingStage', 'prepareNarcoticsPermitReceivingStage');

            Route::get('getDisposalInvoicingOtherDetails', 'getDisposalInvoicingOtherDetails');
            Route::get('prepareDisposalPermitsInvoicingStage', 'prepareDisposalPermitsInvoicingStage');
            Route::post('saveSpecialpermitApprovalRecommendation', 'saveSpecialpermitApprovalRecommendation');

            Route::get('getImportExportApprovedPermitDetails', 'getImportExportApprovedPermitDetails');
            Route::get('getNarcoticsDrugsPermitRelease', 'getNarcoticsDrugsPermitRelease');


            Route::get('getPoeInspectionPermitsProducts', 'getPoeInspectionPermitsProducts');
            Route::get('getPoePreviousPermitsInspection', 'getPoePreviousPermitsInspection');
            Route::post('savePOEInspectionPermitDetails', 'savePOEInspectionPermitDetails');
            Route::get('getPoeinspectionprocessdetails', 'getPoeinspectionprocessdetails');

            Route::post('savePOEPermitProductDetails', 'savePOEPermitProductDetails');
            Route::post('savePOEPermitRecommendations', 'savePOEPermitRecommendations');

            Route::get('getInspectedPoeinspectionprocessdetails', 'getInspectedPoeinspectionprocessdetails');
            Route::get('getImportexportpermitsapps', 'getImportexportpermitsapps');

            Route::get('getHospitalPermitsNarcoticsApps', 'getHospitalPermitsNarcoticsApps');


            Route::get('getNarcoticImportPermitsApps', 'getNarcoticImportPermitsApps');


            Route::get('getNarcoticspermitsproductsDetails', 'getNarcoticspermitsproductsDetails');

            Route::post('onSaveNarcoticsPermitProductsDetails', 'onSaveNarcoticsPermitProductsDetails');

            Route::get('getAllImportExportAppsDetails', 'getAllImportExportAppsDetails');
            Route::post('saveImportExportEditionBaseDetails', 'saveImportExportEditionBaseDetails');
            Route::get('getSenderReceiverList', 'getSenderReceiverList');
            Route::get('getConsigneedetails', 'getConsigneedetails');


            Route::post('saveImportExportExtensionBaseDetails', 'saveImportExportExtensionBaseDetails');

            Route::post('savePOEPermitVerificationRecommendations', 'savePOEPermitVerificationRecommendations');
            Route::get('getImportExportPersonalUsePermits', 'getImportExportPersonalUsePermits');
            Route::post('savePersonalUsePermitReceivingBaseDetails', 'savePersonalUsePermitReceivingBaseDetails');
            Route::get('getPersonalUsepermitsproductsDetails', 'getPersonalUsepermitsproductsDetails');
            Route::get('prepaprePersonalPermitsReceiving', 'prepaprePersonalPermitsReceiving');


            Route::post('onSavePersonalUsePermitProductsDetails', 'onSavePersonalUsePermitProductsDetails');

            Route::get('getDeclaredOnlImportexportpermitsproductsDetails', 'getDeclaredOnlImportexportpermitsproductsDetails');
            Route::get('prepapreDeclaredImportExportOnlineReceivingStage', 'prepapreDeclaredImportExportOnlineReceivingStage');
            Route::get('getImportExportPermitDeclarations', 'getImportExportPermitDeclarations');

            Route::post('savepermitReleaseRecommendation', 'savepermitReleaseRecommendation');
            Route::post('onSaveControlledDrugsPermitProductsDetails', 'onSaveControlledDrugsPermitProductsDetails');
            Route::get('getOnlineControlDrugsImpermitsproductsDetails', 'getOnlineControlDrugsImpermitsproductsDetails');
            Route::post('updatesPermitsProductsrodrecommendtion', 'updatesPermitsProductsrodrecommendtion');
            Route::get('getApprovedVisaApplicationDetails', 'getApprovedVisaApplicationDetails');
            Route::post('onIntiateLicenseApplication', 'onIntiateLicenseApplication');

            Route::post('getOnlineImportExportManagerReviewApplications', 'getOnlineImportExportManagerReviewApplications');



            Route::get('getApprovedImportLicenseApplicationDetails', 'getApprovedImportLicenseApplicationDetails');
            Route::get('getInspectedPoeinspectionProductsdetails', 'getInspectedPoeinspectionProductsdetails');

            Route::post('updateInspectionProductsrodrecommendtion', 'updateInspectionProductsrodrecommendtion');
            Route::post('onIntiateLicenseInspectionApplication', 'onIntiateLicenseInspectionApplication');
        });
    });
});

// Route::group(['middleware' => 'auth:api', 'prefix' => 'importexportpermits', 'namespace' => 'App\\Modules\Importexportpermits\Http\Controllers'], function()
// {
//     Route::get('/', 'ImportexportpermitsController@index');
//     Route::get('getonlineimportexportappdetails', 'ImportexportpermitsController@getonlineimportexportappdetails');
//     Route::get('prepareOnlineImportExporPermitReceivingStage', 'ImportexportpermitsController@prepareOnlineImportExporPermitReceivingStage');
//     Route::get('prepareImportExporPermitReceivingStage', 'ImportexportpermitsController@prepareImportExporPermitReceivingStage');
     
//     Route::get('getManagerEvaluationApplications', 'ImportexportpermitsController@getManagerEvaluationApplications');
//     Route::get('getDisposalManagerEvaluationApplications', 'ImportexportpermitsController@getDisposalManagerEvaluationApplications');
//     Route::get('prepareReceivingpoeinspectionswizard', 'ImportexportpermitsController@prepareReceivingpoeinspectionswizard');
    
    
//     Route::get('getOnlineImportexportpermitsproductsDetails', 'ImportexportpermitsController@getOnlineImportexportpermitsproductsDetails');
//     Route::get('getImportexportpermitsapps', 'ImportexportpermitsController@getImportexportpermitsapps');
//     Route::get('getDeclaredImportExportPermitsApps', 'ImportexportpermitsController@getDeclaredImportExportPermitsApps');

//     Route::get('getImportExportPermitDeclarations', 'ImportexportpermitsController@getImportExportPermitDeclarations');

    
//     Route::get('getPreviousImportExportDocuploads', 'ImportexportpermitsController@getPreviousImportExportDocuploads');
//     Route::get('getPreviousPreviousScreeningData', 'ImportexportpermitsController@getPreviousPreviousScreeningData');
    
    
    
//     Route::get('getImportexportpermitsproductsDetails', 'ImportexportpermitsController@getImportexportpermitsproductsDetails');
    
//     Route::get('getRegisteredNonRegisteredProddetails', 'ImportexportpermitsController@getRegisteredNonRegisteredProddetails');
//     Route::get('getSenderreceiverinformation', 'ImportexportpermitsController@getSenderreceiverinformation');
//     Route::get('getTraderRegisteredPremisesDetails', 'ImportexportpermitsController@getTraderRegisteredPremisesDetails');
//     Route::get('prepapareImportpermitUniformStage', 'ImportexportpermitsController@prepapareImportpermitUniformStage');
//     Route::get('prepareImportInvoicingStage', 'ImportexportpermitsController@prepareImportInvoicingStage');
    
//     Route::get('getImportsInvoicingOtherDetails', 'ImportexportpermitsController@getImportsInvoicingOtherDetails');
//     Route::get('prepareNewImportExportPaymentStage', 'ImportexportpermitsController@prepareNewImportExportPaymentStage');
//     Route::get('getImportExportApprovedPermit', 'ImportexportpermitsController@getImportExportApprovedPermit');
    
//     Route::post('saveDisposalApplicationDetails', 'ImportexportpermitsController@saveDisposalApplicationDetails');
//     Route::post('updateDisposalApplicationDetails', 'ImportexportpermitsController@updateDisposalApplicationDetails');
    
//     Route::post('saveControlDrugsReceivingBaseDetails', 'ImportexportpermitsController@saveControlDrugsReceivingBaseDetails');
//     Route::post('saveImportPermittReceivingBaseDetails', 'ImportexportpermitsController@saveImportPermittReceivingBaseDetails');
//     Route::post('updateonlineImportPermittReceivingBaseDetails', 'ImportexportpermitsController@updateonlineImportPermittReceivingBaseDetails');

    
//     Route::post('onSavePermitProductsDetails', 'ImportexportpermitsController@onSavePermitProductsDetails');
    
//     Route::post('onSaveDisposalPermitProductsDetails', 'ImportexportpermitsController@onSaveDisposalPermitProductsDetails');
//     Route::post('onSaveImportPermitPremisesData', 'ImportexportpermitsController@onSaveImportPermitPremisesData');
    
//     Route::post('onSaveImportPermitSenderReceiverData', 'ImportexportpermitsController@onSaveImportPermitSenderReceiverData');
//     Route::post('onSavePermitinformation', 'ImportexportpermitsController@onSavePermitinformation');
//     Route::post('getPermitsApplicationMoreDetails', 'ImportexportpermitsController@getPermitsApplicationMoreDetails');
   

    
//     Route::get('getImportExportManagerReviewApplications', 'ImportexportpermitsController@getImportExportManagerReviewApplications');
//     Route::get('getNarcoticsPermitsManagerReviewApplications', 'ImportexportpermitsController@getNarcoticsPermitsManagerReviewApplications');
    

    
//     Route::get('getISpecialmportExportApprovalApplications', 'ImportexportpermitsController@getISpecialmportExportApprovalApplications');
//     Route::get('getOnlineDisposalApplications', 'ImportexportpermitsController@getOnlineDisposalApplications');

//     Route::get('getOnlineDisposalpermitsproductsDetails', 'ImportexportpermitsController@getOnlineDisposalpermitsproductsDetails');
//     Route::get('prepapreDisposalOnlineReceiving', 'ImportexportpermitsController@prepapreDisposalOnlineReceiving');
   
//     Route::get('getDisposalApplications', 'ImportexportpermitsController@getDisposalApplications');
//     Route::get('getDisposalpermitsproductsDetails', 'ImportexportpermitsController@getDisposalpermitsproductsDetails');

//  Route::get('getControlledImpproductsDetails', 'ImportexportpermitsController@getControlledImpproductsDetails');

    
//     Route::get('getDisposalPermitsApplicationMoreDetails', 'ImportexportpermitsController@getDisposalPermitsApplicationMoreDetails');
//     Route::get('getDisposalInspectors', 'ImportexportpermitsController@getDisposalInspectors');
//     Route::post('deleteDisposalInspectors', 'ImportexportpermitsController@deleteDisposalInspectors');
//     Route::post('onSaveDisposalinternalsupervisors', 'ImportexportpermitsController@onSaveDisposalinternalsupervisors');
    
//     Route::post('saveDisposalDestructionDetails', 'ImportexportpermitsController@saveDisposalDestructionDetails');
//     Route::get('getDisposalPermitApprovalApplications', 'ImportexportpermitsController@getDisposalPermitApprovalApplications');
//     Route::get('funcDrugsContentsCalculations', 'ImportexportpermitsController@funcDrugsContentsCalculations');
    
//     Route::get('getSummaryReports', 'ImportexportpermitsController@getSummaryReports');
    
//     Route::get('searchProductsInformation', 'ImportexportpermitsController@searchProductsInformation');
//     Route::get('prepareDisposalPermitReceivingStage', 'ImportexportpermitsController@prepareDisposalPermitReceivingStage');
//     Route::get('prepareNarcoticsPermitReceivingStage', 'ImportexportpermitsController@prepareNarcoticsPermitReceivingStage');
    
//     Route::get('getDisposalInvoicingOtherDetails', 'ImportexportpermitsController@getDisposalInvoicingOtherDetails');
//     Route::get('prepareDisposalPermitsInvoicingStage', 'ImportexportpermitsController@prepareDisposalPermitsInvoicingStage');
//     Route::post('saveSpecialpermitApprovalRecommendation', 'ImportexportpermitsController@saveSpecialpermitApprovalRecommendation');
   
//     Route::get('getImportExportApprovedPermitDetails', 'ImportexportpermitsController@getImportExportApprovedPermitDetails');
//     Route::get('getNarcoticsDrugsPermitRelease', 'ImportexportpermitsController@getNarcoticsDrugsPermitRelease');

    
//     Route::get('getPoeInspectionPermitsProducts', 'ImportexportpermitsController@getPoeInspectionPermitsProducts');
//     Route::get('getPoePreviousPermitsInspection', 'ImportexportpermitsController@getPoePreviousPermitsInspection');
//     Route::post('savePOEInspectionPermitDetails', 'ImportexportpermitsController@savePOEInspectionPermitDetails');
//     Route::get('getPoeinspectionprocessdetails', 'ImportexportpermitsController@getPoeinspectionprocessdetails');

//     Route::post('savePOEPermitProductDetails', 'ImportexportpermitsController@savePOEPermitProductDetails');
//     Route::post('savePOEPermitRecommendations', 'ImportexportpermitsController@savePOEPermitRecommendations');
    
//     Route::get('getInspectedPoeinspectionprocessdetails', 'ImportexportpermitsController@getInspectedPoeinspectionprocessdetails');
//     Route::get('getImportexportpermitsapps', 'ImportexportpermitsController@getImportexportpermitsapps');
    
//     Route::get('getHospitalPermitsNarcoticsApps', 'ImportexportpermitsController@getHospitalPermitsNarcoticsApps');
    

//     Route::get('getNarcoticImportPermitsApps', 'ImportexportpermitsController@getNarcoticImportPermitsApps');

    
//     Route::get('getNarcoticspermitsproductsDetails', 'ImportexportpermitsController@getNarcoticspermitsproductsDetails');

//     Route::post('onSaveNarcoticsPermitProductsDetails', 'ImportexportpermitsController@onSaveNarcoticsPermitProductsDetails');

//      Route::get('getAllImportExportAppsDetails', 'ImportexportpermitsController@getAllImportExportAppsDetails');
//      Route::post('saveImportExportEditionBaseDetails', 'ImportexportpermitsController@saveImportExportEditionBaseDetails');
//      Route::get('getSenderReceiverList', 'ImportexportpermitsController@getSenderReceiverList');
//      Route::get('getConsigneedetails', 'ImportexportpermitsController@getConsigneedetails');

     
//     Route::post('saveImportExportExtensionBaseDetails', 'ImportexportpermitsController@saveImportExportExtensionBaseDetails');

//     Route::post('savePOEPermitVerificationRecommendations', 'ImportexportpermitsController@savePOEPermitVerificationRecommendations');
//     Route::get('getImportExportPersonalUsePermits', 'ImportexportpermitsController@getImportExportPersonalUsePermits');
//     Route::post('savePersonalUsePermitReceivingBaseDetails', 'ImportexportpermitsController@savePersonalUsePermitReceivingBaseDetails');
//     Route::get('getPersonalUsepermitsproductsDetails', 'ImportexportpermitsController@getPersonalUsepermitsproductsDetails');
//     Route::get('prepaprePersonalPermitsReceiving', 'ImportexportpermitsController@prepaprePersonalPermitsReceiving');
   
    
//     Route::post('onSavePersonalUsePermitProductsDetails', 'ImportexportpermitsController@onSavePersonalUsePermitProductsDetails');
    
//     Route::get('getDeclaredOnlImportexportpermitsproductsDetails', 'ImportexportpermitsController@getDeclaredOnlImportexportpermitsproductsDetails');
//     Route::get('prepapreDeclaredImportExportOnlineReceivingStage', 'ImportexportpermitsController@prepapreDeclaredImportExportOnlineReceivingStage');
//     Route::get('getImportExportPermitDeclarations', 'ImportexportpermitsController@getImportExportPermitDeclarations');

//     Route::post('savepermitReleaseRecommendation', 'ImportexportpermitsController@savepermitReleaseRecommendation');
//     Route::post('onSaveControlledDrugsPermitProductsDetails', 'ImportexportpermitsController@onSaveControlledDrugsPermitProductsDetails');
// Route::get('getOnlineControlDrugsImpermitsproductsDetails', 'ImportexportpermitsController@getOnlineControlDrugsImpermitsproductsDetails');
// Route::post('updatesPermitsProductsrodrecommendtion', 'ImportexportpermitsController@updatesPermitsProductsrodrecommendtion');
// Route::get('getApprovedVisaApplicationDetails', 'ImportexportpermitsController@getApprovedVisaApplicationDetails');
// Route::post('onIntiateLicenseApplication', 'ImportexportpermitsController@onIntiateLicenseApplication');
   
// Route::post('getOnlineImportExportManagerReviewApplications', 'ImportexportpermitsController@getOnlineImportExportManagerReviewApplications');



// Route::get('getApprovedImportLicenseApplicationDetails', 'ImportexportpermitsController@getApprovedImportLicenseApplicationDetails');
// Route::get('getInspectedPoeinspectionProductsdetails', 'ImportexportpermitsController@getInspectedPoeinspectionProductsdetails');

// Route::post('updateInspectionProductsrodrecommendtion', 'ImportexportpermitsController@updateInspectionProductsrodrecommendtion');
// Route::post('onIntiateLicenseInspectionApplication', 'ImportexportpermitsController@onIntiateLicenseInspectionApplication');

// });
