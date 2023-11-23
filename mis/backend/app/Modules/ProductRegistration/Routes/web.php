<?php

use App\Modules\ProductRegistration\Http\Controllers\ProductRegistrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('productregistration')->group(function () {
        Route::controller(ProductRegistrationController::class)->group(function () {
            Route::post('/saveNewProductReceivingBaseDetails', 'saveNewProductReceivingBaseDetails');
            Route::post('/saveRenAltProductReceivingBaseDetails', 'saveRenAltProductReceivingBaseDetails');
            Route::post('/onSaveProductOtherDetails', 'onSaveProductOtherDetails');
            Route::post('/onSaveProductinformation', 'onSaveProductinformation');
            
            Route::post('saveApplicationInvoicingDetails', 'saveApplicationInvoicingDetails');
           
            Route::get('/applications', 'getProductApplications');
            Route::get('/getElementCosts', 'getElementCosts');
            
            Route::get('/getManagerEvaluationApplications', 'getManagerEvaluationApplications');
            Route::get('/getManagerProductDataAmmendApps', 'getManagerProductDataAmmendApps');
        
            
            Route::get('/getManagerAuditingApplications', 'getManagerAuditingApplications');
            Route::get('/getGrantingDecisionApplications', 'getGrantingDecisionApplications');
            Route::get('/getApplicationUploadedDocs', 'getApplicationUploadedDocs');
            Route::get('/getApplicationUploadedDocs', 'getApplicationUploadedDocs');
            
            Route::get('/prepareNewProductReceivingStage', 'prepareNewProductReceivingStage');
            Route::get('/prepareNewProductAmmendMentReceivingStage', 'prepareNewProductAmmendMentReceivingStage');
        
            
            Route::get('/prepareOnlineProductReceivingStage', 'prepareOnlineProductReceivingStage');
        
            Route::get('/prepareProductsInvoicingStage', 'prepareProductsInvoicingStage');
            Route::get('/prepareNewProductPaymentStage', 'prepareNewProductPaymentStage');
            Route::get('/prepareProductsRegMeetingStage', 'prepareProductsRegMeetingStage');
        
            
            Route::post('/saveTCMeetingDetails', 'saveTCMeetingDetails');
            Route::post('/saveUpload', 'saveUpload');
            Route::post('/saveSample', 'saveSample');
            Route::post('/uploadApplicationFile', 'uploadApplicationFile');
        
            Route::post('/onDeleteProductOtherDetails', 'onDeleteProductOtherDetails');
            
            Route::post('/deleteUploadedProductImages', 'deleteUploadedProductImages');
            
            Route::post('/saveManufacturerDetails', 'saveManufacturerDetails');
            Route::post('/saveProductGmpApplicationDetails', 'saveProductGmpApplicationDetails');
            Route::post('/saveProductRegistrationComments', 'saveProductRegistrationComments');
        
            
            //products other details 
            
            
            Route::get('/onLoadproductNutrients', 'onLoadproductNutrients');
            Route::get('/onLoadOnlineproductNutrients', 'onLoadOnlineproductNutrients');
            Route::get('/onLoadproductIngredients', 'onLoadproductIngredients');
            Route::get('/onLoadproductPackagingDetails', 'onLoadproductPackagingDetails');
            Route::get('/onLoaddrugsMaximumResidueLimitsGrid', 'onLoaddrugsMaximumResidueLimitsGrid');
            Route::get('/onLoadManufacturersDetails', 'onLoadManufacturersDetails');
            Route::get('/onLoadManufacturingSitesDetails', 'onLoadManufacturingSitesDetails');
        
            
            Route::get('/onLoadproductManufacturer', 'onLoadproductManufacturer');
            Route::get('/onLoadproductApiManufacturer', 'onLoadproductApiManufacturer');
            Route::get('/onLoadproductGmpInspectionDetailsStr', 'onLoadproductGmpInspectionDetailsStr');
           
            Route::get('/getGMPproductLinesDetails', 'getGMPproductLinesDetails');
            Route::get('/getProductActiveIngredients', 'getProductActiveIngredients');
            Route::get('/onLoadgmpInspectionApplicationsDetails', 'onLoadgmpInspectionApplicationsDetails');
            Route::get('onLoadProductsSampledetails', 'onLoadProductsSampledetails');
            Route::get('getTcMeetingParticipants', 'getTcMeetingParticipants');
            Route::get('getProductRegistrationMeetingApplications', 'getProductRegistrationMeetingApplications');
            Route::get('getProductTcReviewMeetingApplications', 'getProductTcReviewMeetingApplications');
           
            Route::get('getProductApprovalApplications', 'getProductApprovalApplications');
            Route::get('getProductApprovalApplicationsNonTc', 'getProductApprovalApplicationsNonTc');
            
            Route::get('getproductregistrationAppsApproval', 'getproductregistrationAppsApproval');
           
            Route::get('getProductApplicationMoreDetails', 'getProductApplicationMoreDetails');
           
            Route::get('getEValuationComments', 'getEValuationComments');
            
            Route::get('getAuditingComments', 'getAuditingComments');
            
            
            Route::get('getOnlineApplications', 'getOnlineApplications');
        
            Route::get('onLoadOnlineproductIngredients', 'onLoadOnlineproductIngredients');
            Route::get('onLoadOnlineproductPackagingDetails', 'onLoadOnlineproductPackagingDetails');
            Route::get('onLoadOnlineproductManufacturer', 'onLoadOnlineproductManufacturer');
           
            Route::get('onLoadOnlineproductApiManufacturer', 'onLoadOnlineproductApiManufacturer');
            Route::get('onLoadOnlinegmpInspectionApplicationsDetails', 'onLoadOnlinegmpInspectionApplicationsDetails');
            Route::get('getRegisteredProductsAppsDetails', 'getRegisteredProductsAppsDetails');
            Route::get('getProductSampleDetails', 'getProductSampleDetails');
            Route::post('savedocumentssubmissionrecommendation', 'savedocumentssubmissionrecommendation');
            
            Route::get('onRegisteredProductsSearchdetails', 'onRegisteredProductsSearchdetails');
            Route::get('onRegisteredProductsSearchdetails', 'onRegisteredProductsSearchdetails');
            Route::post('saveOnlineProductRegistrationReceiving', 'saveOnlineProductRegistrationReceiving');
            Route::get('prepareProductsUniformStage', 'prepareProductsUniformStage');
            
            //export
            Route::get('ExportMeetingReport', 'ExportMeetingReport');
        
            Route::post('saveProductDataAmmendmentRequest', 'saveProductDataAmmendmentRequest');
            Route::get('getProductAppealApprovalApplications', 'getProductAppealApprovalApplications');
            Route::get('getAllProductsAppsDetails', 'getAllProductsAppsDetails');
            Route::post('saveProductEditionBaseDetails', 'saveProductEditionBaseDetails');
            
            Route::get('getdocumentssubmissionrecommendation', 'getdocumentssubmissionrecommendation');
            
           //connection('portal_db')->
        
    
        });

    });
});



// Route::group(['middleware' => 'auth:api','prefix' => 'productregistration', 'namespace' => 'App\\Modules\ProductRegistration\Http\Controllers'], function()
// {
    
//     Route::post('/saveNewProductReceivingBaseDetails', 'ProductRegistrationController@saveNewProductReceivingBaseDetails');
//     Route::post('/saveRenAltProductReceivingBaseDetails', 'ProductRegistrationController@saveRenAltProductReceivingBaseDetails');
//     Route::post('/onSaveProductOtherDetails', 'ProductRegistrationController@onSaveProductOtherDetails');
//     Route::post('/onSaveProductinformation', 'ProductRegistrationController@onSaveProductinformation');
    
//     Route::post('saveApplicationInvoicingDetails', 'ProductRegistrationController@saveApplicationInvoicingDetails');
   
//     Route::get('/applications', 'ProductRegistrationController@getProductApplications');
//     Route::get('/getElementCosts', 'ProductRegistrationController@getElementCosts');
    
//     Route::get('/getManagerEvaluationApplications', 'ProductRegistrationController@getManagerEvaluationApplications');
//     Route::get('/getManagerProductDataAmmendApps', 'ProductRegistrationController@getManagerProductDataAmmendApps');

    
//     Route::get('/getManagerAuditingApplications', 'ProductRegistrationController@getManagerAuditingApplications');
//     Route::get('/getGrantingDecisionApplications', 'ProductRegistrationController@getGrantingDecisionApplications');
//     Route::get('/getApplicationUploadedDocs', 'ProductRegistrationController@getApplicationUploadedDocs');
//     Route::get('/getApplicationUploadedDocs', 'ProductRegistrationController@getApplicationUploadedDocs');
    
//     Route::get('/prepareNewProductReceivingStage', 'ProductRegistrationController@prepareNewProductReceivingStage');
//     Route::get('/prepareNewProductAmmendMentReceivingStage', 'ProductRegistrationController@prepareNewProductAmmendMentReceivingStage');

    
//     Route::get('/prepareOnlineProductReceivingStage', 'ProductRegistrationController@prepareOnlineProductReceivingStage');

//     Route::get('/prepareProductsInvoicingStage', 'ProductRegistrationController@prepareProductsInvoicingStage');
//     Route::get('/prepareNewProductPaymentStage', 'ProductRegistrationController@prepareNewProductPaymentStage');
//     Route::get('/prepareProductsRegMeetingStage', 'ProductRegistrationController@prepareProductsRegMeetingStage');

    
//     Route::post('/saveTCMeetingDetails', 'ProductRegistrationController@saveTCMeetingDetails');
//     Route::post('/saveUpload', 'ProductRegistrationController@saveUpload');
//     Route::post('/saveSample', 'ProductRegistrationController@saveSample');
//     Route::post('/uploadApplicationFile', 'ProductRegistrationController@uploadApplicationFile');

//     Route::post('/onDeleteProductOtherDetails', 'ProductRegistrationController@onDeleteProductOtherDetails');
    
//     Route::post('/deleteUploadedProductImages', 'ProductRegistrationController@deleteUploadedProductImages');
    
//     Route::post('/saveManufacturerDetails', 'ProductRegistrationController@saveManufacturerDetails');
//     Route::post('/saveProductGmpApplicationDetails', 'ProductRegistrationController@saveProductGmpApplicationDetails');
//     Route::post('/saveProductRegistrationComments', 'ProductRegistrationController@saveProductRegistrationComments');

    
//     //products other details 
    
    
//     Route::get('/onLoadproductNutrients', 'ProductRegistrationController@onLoadproductNutrients');
//     Route::get('/onLoadOnlineproductNutrients', 'ProductRegistrationController@onLoadOnlineproductNutrients');
//     Route::get('/onLoadproductIngredients', 'ProductRegistrationController@onLoadproductIngredients');
//     Route::get('/onLoadproductPackagingDetails', 'ProductRegistrationController@onLoadproductPackagingDetails');
//     Route::get('/onLoaddrugsMaximumResidueLimitsGrid', 'ProductRegistrationController@onLoaddrugsMaximumResidueLimitsGrid');
//     Route::get('/onLoadManufacturersDetails', 'ProductRegistrationController@onLoadManufacturersDetails');
//     Route::get('/onLoadManufacturingSitesDetails', 'ProductRegistrationController@onLoadManufacturingSitesDetails');

    
//     Route::get('/onLoadproductManufacturer', 'ProductRegistrationController@onLoadproductManufacturer');
//     Route::get('/onLoadproductApiManufacturer', 'ProductRegistrationController@onLoadproductApiManufacturer');
//     Route::get('/onLoadproductGmpInspectionDetailsStr', 'ProductRegistrationController@onLoadproductGmpInspectionDetailsStr');
   
//     Route::get('/getGMPproductLinesDetails', 'ProductRegistrationController@getGMPproductLinesDetails');
//     Route::get('/getProductActiveIngredients', 'ProductRegistrationController@getProductActiveIngredients');
//     Route::get('/onLoadgmpInspectionApplicationsDetails', 'ProductRegistrationController@onLoadgmpInspectionApplicationsDetails');
//     Route::get('onLoadProductsSampledetails', 'ProductRegistrationController@onLoadProductsSampledetails');
//     Route::get('getTcMeetingParticipants', 'ProductRegistrationController@getTcMeetingParticipants');
//     Route::get('getProductRegistrationMeetingApplications', 'ProductRegistrationController@getProductRegistrationMeetingApplications');
//     Route::get('getProductTcReviewMeetingApplications', 'ProductRegistrationController@getProductTcReviewMeetingApplications');
   
//     Route::get('getProductApprovalApplications', 'ProductRegistrationController@getProductApprovalApplications');
//     Route::get('getProductApprovalApplicationsNonTc', 'ProductRegistrationController@getProductApprovalApplicationsNonTc');
    
//     Route::get('getproductregistrationAppsApproval', 'ProductRegistrationController@getproductregistrationAppsApproval');
   
//     Route::get('getProductApplicationMoreDetails', 'ProductRegistrationController@getProductApplicationMoreDetails');
   
//     Route::get('getEValuationComments', 'ProductRegistrationController@getEValuationComments');
    
//     Route::get('getAuditingComments', 'ProductRegistrationController@getAuditingComments');
    
    
//     Route::get('getOnlineApplications', 'ProductRegistrationController@getOnlineApplications');

//     Route::get('onLoadOnlineproductIngredients', 'ProductRegistrationController@onLoadOnlineproductIngredients');
//     Route::get('onLoadOnlineproductPackagingDetails', 'ProductRegistrationController@onLoadOnlineproductPackagingDetails');
//     Route::get('onLoadOnlineproductManufacturer', 'ProductRegistrationController@onLoadOnlineproductManufacturer');
   
//     Route::get('onLoadOnlineproductApiManufacturer', 'ProductRegistrationController@onLoadOnlineproductApiManufacturer');
//     Route::get('onLoadOnlinegmpInspectionApplicationsDetails', 'ProductRegistrationController@onLoadOnlinegmpInspectionApplicationsDetails');
//     Route::get('getRegisteredProductsAppsDetails', 'ProductRegistrationController@getRegisteredProductsAppsDetails');
//     Route::get('getProductSampleDetails', 'ProductRegistrationController@getProductSampleDetails');
//     Route::post('savedocumentssubmissionrecommendation', 'ProductRegistrationController@savedocumentssubmissionrecommendation');
    
// 	Route::get('onRegisteredProductsSearchdetails', 'ProductRegistrationController@onRegisteredProductsSearchdetails');
//     Route::get('onRegisteredProductsSearchdetails', 'ProductRegistrationController@onRegisteredProductsSearchdetails');
//     Route::post('saveOnlineProductRegistrationReceiving', 'ProductRegistrationController@saveOnlineProductRegistrationReceiving');
//     Route::get('prepareProductsUniformStage', 'ProductRegistrationController@prepareProductsUniformStage');
    
// 	//export
//     Route::get('ExportMeetingReport', 'ProductRegistrationController@ExportMeetingReport');

//     Route::post('saveProductDataAmmendmentRequest', 'ProductRegistrationController@saveProductDataAmmendmentRequest');
//     Route::get('getProductAppealApprovalApplications', 'ProductRegistrationController@getProductAppealApprovalApplications');
//     Route::get('getAllProductsAppsDetails', 'ProductRegistrationController@getAllProductsAppsDetails');
//     Route::post('saveProductEditionBaseDetails', 'ProductRegistrationController@saveProductEditionBaseDetails');
    
//     Route::get('getdocumentssubmissionrecommendation', 'ProductRegistrationController@getdocumentssubmissionrecommendation');
    
//    //connection('portal_db')->
// });
