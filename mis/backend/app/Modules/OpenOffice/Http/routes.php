<?php

use App\Modules\OpenOffice\Http\Controllers\OpenOfficeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('openoffice')->group(function () {
        Route::controller(OpenOfficeController::class)->group(function () {
          //products
        Route::get('getProductsApplicationColumns','getProductsApplicationColumns');
        Route::get('getPoeApplicationDetails','getPoeApplicationDetails');
        Route::get('getProductIngredients','getProductIngredients');
        Route::get('getProductNutrients','getProductNutrients');
        Route::get('getProductPackaging','getProductPackaging');
        Route::get('getproductimage','getproductimage');
        Route::get('getManInfo','getManInfo');
        Route::get('getInspectionInfo','getInspectionInfo');
        Route::get('getSampleInfo','getSampleInfo');
        //premise
        Route::get('getPremiseApplicationColumns','getPremiseApplicationColumns');
        Route::get('getPremisebsnInfo','getPremisebsnInfo'); 
        Route::get('getPremisePersonnelInfo','getPremisePersonnelInfo');
        //gmp
        Route::get('getGmpSpreadSheet','getGmpSpreadSheet'); 
        Route::get('getgmpmanblock','getgmpmanblock'); 
        Route::get('getGmpManLine','getGmpManLine');
        Route::get('getGmpManSite','getGmpManSite');
        Route::get('getGmpBsnDetails','getGmpBsnDetails');
        //import export
        Route::get('getIESpreadSheet','getIESpreadSheet');
        Route::get('getIEproducts','getIEproducts');
        Route::get('getIESections','getIESections');
        Route::get('getIEPermitSpreadSheet','getIEPermitSpreadSheet');
        //Clinical trial
        Route::get('getClinicalTrialsSpreadsheet','getClinicalTrialsSpreadsheet');
        Route::get('getClinicalTrialsStudySite','getClinicalTrialsStudySite');
        Route::get('getClinicalTrialsInvestigators','getClinicalTrialsInvestigators');
        Route::get('getClinicalTrialsIMPProducts','getClinicalTrialsIMPProducts');
         //product notification
        Route::get('getDeviceNotificationSpreadsheet','getDeviceNotificationSpreadsheet');
        //promtion and advertisement
        Route::get('getPromAdvertSpreadsheet','getPromAdvertSpreadsheet');
        Route::get('getProductPaticulars','getProductPaticulars'); 
        Route::get('getPromotionMaterialDetails','getPromotionMaterialDetails'); 

        //disposal product
        Route::get('getDisposalSpreadsheetColumns','getDisposalSpreadsheetColumns');
        Route::get('getdisposalproductdetails','getDisposalProductDetails'); 
       

        Route::get('getSubmissionEnquiriesCounter','getSubmissionEnquiriesCounters'); 
        Route::get('getSubmissionEnquiriesApplications','getSubmissionEnquiriesApplications'); 
        Route::get('getOnlineSubmissionStatuses','getOnlineSubmissionStatuses'); 
        
        Route::get('getUploadedDocumentPerApplication','getUploadedDocumentPerApplication'); 

        //survelliance
        Route::get('getSurvellianceSpreadsheetApplications','getSurvellianceSpreadsheetApplications'); 
        Route::get('getSurvellianceSampleandProductDetails','getSurvellianceSampleandProductDetails'); 
        Route::get('getSampleDetails','getSampleDetails'); 
        Route::get('getSurvellianceSampleSpreadsheetApplications','getSurvellianceSampleSpreadsheetApplications'); 

        Route::post('assignUsertoEnquiryApplication','assignUsertoEnquiryApplication'); 
        Route::get('getGMPInspectionTeam','getGMPInspectionTeam'); 
        Route::get('getProductManufacturers','getProductManufacturers'); 
        
        
        

        //excell export
        Route::post('exportall','exportall');
        Route::get('exportall','exportData');


        Route::get('getProductsReport','getProductsReport');
        Route::get('getEnquiries','getEnquiries');
        Route::get('test','test');
        Route::get('getApprovalDetails','getApprovalDetails');
        

        
    
        });

    });
});


// Route::group(['middleware' => 'web', 'prefix' => 'openoffice', 'namespace' => 'App\\Modules\OpenOffice\Http\Controllers'], function()
// {
//         //products
//         Route::get('getProductsApplicationColumns','OpenOfficeController@getProductsApplicationColumns');
//         Route::get('getPoeApplicationDetails','OpenOfficeController@getPoeApplicationDetails');
//         Route::get('getProductIngredients','OpenOfficeController@getProductIngredients');
//         Route::get('getProductNutrients','OpenOfficeController@getProductNutrients');
//         Route::get('getProductPackaging','OpenOfficeController@getProductPackaging');
//         Route::get('getproductimage','OpenOfficeController@getproductimage');
//         Route::get('getManInfo','OpenOfficeController@getManInfo');
//         Route::get('getInspectionInfo','OpenOfficeController@getInspectionInfo');
//         Route::get('getSampleInfo','OpenOfficeController@getSampleInfo');
//         //premise
//         Route::get('getPremiseApplicationColumns','OpenOfficeController@getPremiseApplicationColumns');
//         Route::get('getPremisebsnInfo','OpenOfficeController@getPremisebsnInfo'); 
//         Route::get('getPremisePersonnelInfo','OpenOfficeController@getPremisePersonnelInfo');
//         //gmp
//         Route::get('getGmpSpreadSheet','OpenOfficeController@getGmpSpreadSheet'); 
//         Route::get('getgmpmanblock','OpenOfficeController@getgmpmanblock'); 
//         Route::get('getGmpManLine','OpenOfficeController@getGmpManLine');
//         Route::get('getGmpManSite','OpenOfficeController@getGmpManSite');
//         Route::get('getGmpBsnDetails','OpenOfficeController@getGmpBsnDetails');
//         //import export
//         Route::get('getIESpreadSheet','OpenOfficeController@getIESpreadSheet');
//         Route::get('getIEproducts','OpenOfficeController@getIEproducts');
//         Route::get('getIESections','OpenOfficeController@getIESections');
//         Route::get('getIEPermitSpreadSheet','OpenOfficeController@getIEPermitSpreadSheet');
//         //Clinical trial
//         Route::get('getClinicalTrialsSpreadsheet','OpenOfficeController@getClinicalTrialsSpreadsheet');
//         Route::get('getClinicalTrialsStudySite','OpenOfficeController@getClinicalTrialsStudySite');
//         Route::get('getClinicalTrialsInvestigators','OpenOfficeController@getClinicalTrialsInvestigators');
//         Route::get('getClinicalTrialsIMPProducts','OpenOfficeController@getClinicalTrialsIMPProducts');
//          //product notification
//         Route::get('getDeviceNotificationSpreadsheet','OpenOfficeController@getDeviceNotificationSpreadsheet');
//         //promtion and advertisement
//         Route::get('getPromAdvertSpreadsheet','OpenOfficeController@getPromAdvertSpreadsheet');
//         Route::get('getProductPaticulars','OpenOfficeController@getProductPaticulars'); 
//         Route::get('getPromotionMaterialDetails','OpenOfficeController@getPromotionMaterialDetails'); 

//         //disposal product
//         Route::get('getDisposalSpreadsheetColumns','OpenOfficeController@getDisposalSpreadsheetColumns');
//         Route::get('getdisposalproductdetails','OpenOfficeController@getDisposalProductDetails'); 
       

//         Route::get('getSubmissionEnquiriesCounter','OpenOfficeController@getSubmissionEnquiriesCounters'); 
//         Route::get('getSubmissionEnquiriesApplications','OpenOfficeController@getSubmissionEnquiriesApplications'); 
//         Route::get('getOnlineSubmissionStatuses','OpenOfficeController@getOnlineSubmissionStatuses'); 
        
//         Route::get('getUploadedDocumentPerApplication','OpenOfficeController@getUploadedDocumentPerApplication'); 

//         //survelliance
//         Route::get('getSurvellianceSpreadsheetApplications','OpenOfficeController@getSurvellianceSpreadsheetApplications'); 
//         Route::get('getSurvellianceSampleandProductDetails','OpenOfficeController@getSurvellianceSampleandProductDetails'); 
//         Route::get('getSampleDetails','OpenOfficeController@getSampleDetails'); 
//         Route::get('getSurvellianceSampleSpreadsheetApplications','OpenOfficeController@getSurvellianceSampleSpreadsheetApplications'); 

//         Route::post('assignUsertoEnquiryApplication','OpenOfficeController@assignUsertoEnquiryApplication'); 
//         Route::get('getGMPInspectionTeam','OpenOfficeController@getGMPInspectionTeam'); 
//         Route::get('getProductManufacturers','OpenOfficeController@getProductManufacturers'); 
        
        
        

//         //excell export
//         Route::post('exportall','OpenOfficeController@exportall');
//         Route::get('exportall','OpenOfficeController@exportData');


//         Route::get('getProductsReport','OpenOfficeController@getProductsReport');
//         Route::get('getEnquiries','OpenOfficeController@getEnquiries');
//         Route::get('test','OpenOfficeController@test');
//         Route::get('getApprovalDetails','OpenOfficeController@getApprovalDetails');
        

        

// });
