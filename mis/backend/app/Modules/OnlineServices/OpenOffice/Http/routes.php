<?php

Route::group(['middleware' => 'web', 'prefix' => 'openoffice', 'namespace' => 'App\\Modules\OpenOffice\Http\Controllers'], function()
{
        //products
        Route::get('getProductsApplicationColumns','OpenOfficeController@getProductsApplicationColumns');
        Route::get('getPoeApplicationDetails','OpenOfficeController@getPoeApplicationDetails');
        Route::get('getProductIngredients','OpenOfficeController@getProductIngredients');
        Route::get('getProductNutrients','OpenOfficeController@getProductNutrients');
        Route::get('getProductPackaging','OpenOfficeController@getProductPackaging');
        Route::get('getproductimage','OpenOfficeController@getproductimage');
        Route::get('getManInfo','OpenOfficeController@getManInfo');
        Route::get('getInspectionInfo','OpenOfficeController@getInspectionInfo');
        Route::get('getSampleInfo','OpenOfficeController@getSampleInfo');
        //premise
        Route::get('getPremiseApplicationColumns','OpenOfficeController@getPremiseApplicationColumns');
        Route::get('getPremisebsnInfo','OpenOfficeController@getPremisebsnInfo'); 
        Route::get('getPremisePersonnelInfo','OpenOfficeController@getPremisePersonnelInfo');
        //gmp
        Route::get('getGmpSpreadSheet','OpenOfficeController@getGmpSpreadSheet'); 
        Route::get('getgmpmanblock','OpenOfficeController@getgmpmanblock'); 
        Route::get('getGmpManLine','OpenOfficeController@getGmpManLine');
        Route::get('getGmpManSite','OpenOfficeController@getGmpManSite');
        Route::get('getGmpBsnDetails','OpenOfficeController@getGmpBsnDetails');
        //import export
        Route::get('getIESpreadSheet','OpenOfficeController@getIESpreadSheet');
        Route::get('getIEproducts','OpenOfficeController@getIEproducts');
        Route::get('getIESections','OpenOfficeController@getIESections');
        Route::get('getIEPermitSpreadSheet','OpenOfficeController@getIEPermitSpreadSheet');
        //Clinical trial
        Route::get('getClinicalTrialsSpreadsheet','OpenOfficeController@getClinicalTrialsSpreadsheet');
        Route::get('getClinicalTrialsStudySite','OpenOfficeController@getClinicalTrialsStudySite');
        Route::get('getClinicalTrialsInvestigators','OpenOfficeController@getClinicalTrialsInvestigators');
        Route::get('getClinicalTrialsIMPProducts','OpenOfficeController@getClinicalTrialsIMPProducts');
         //product notification
        Route::get('getDeviceNotificationSpreadsheet','OpenOfficeController@getDeviceNotificationSpreadsheet');
        //promtion and advertisement
        Route::get('getPromAdvertSpreadsheet','OpenOfficeController@getPromAdvertSpreadsheet');
        Route::get('getProductPaticulars','OpenOfficeController@getProductPaticulars'); 
        Route::get('getPromotionMaterialDetails','OpenOfficeController@getPromotionMaterialDetails'); 

        //disposal product
        Route::get('getDisposalSpreadsheetColumns','OpenOfficeController@getDisposalSpreadsheetColumns');
        Route::get('getdisposalproductdetails','OpenOfficeController@getDisposalProductDetails'); 
       

        Route::get('getSubmissionEnquiriesCounter','OpenOfficeController@getSubmissionEnquiriesCounters'); 
        Route::get('getSubmissionEnquiriesApplications','OpenOfficeController@getSubmissionEnquiriesApplications'); 
        Route::get('getOnlineSubmissionStatuses','OpenOfficeController@getOnlineSubmissionStatuses'); 
        
        Route::get('getUploadedDocumentPerApplication','OpenOfficeController@getUploadedDocumentPerApplication'); 

        //survelliance
        Route::get('getSurvellianceSpreadsheetApplications','OpenOfficeController@getSurvellianceSpreadsheetApplications'); 
        Route::get('getSurvellianceSampleandProductDetails','OpenOfficeController@getSurvellianceSampleandProductDetails'); 
        Route::get('getSampleDetails','OpenOfficeController@getSampleDetails'); 
        Route::get('getSurvellianceSampleSpreadsheetApplications','OpenOfficeController@getSurvellianceSampleSpreadsheetApplications'); 

        Route::post('assignUsertoEnquiryApplication','OpenOfficeController@assignUsertoEnquiryApplication'); 
        Route::get('getGMPInspectionTeam','OpenOfficeController@getGMPInspectionTeam'); 
        Route::get('getProductManufacturers','OpenOfficeController@getProductManufacturers'); 
        
        
        

        //excell export
        Route::post('exportall','OpenOfficeController@exportall');
        Route::get('exportall','OpenOfficeController@exportData');


        Route::get('getProductsReport','OpenOfficeController@getProductsReport');
        Route::get('getEnquiries','OpenOfficeController@getEnquiries');
        Route::get('test','OpenOfficeController@test');
        

        

});
