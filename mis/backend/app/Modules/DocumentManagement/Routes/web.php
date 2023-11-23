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

// Route::prefix('documentmanagement')->group(function() {
//     Route::get('/', 'DocumentManagementController@index');
// });
use Modules\DocumentManagement\Http\Controllers\DocumentManagementController;
//use Modules\DocumentManagement\Http\Controllers\DmsConfigurations;

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('documentmanagement')->group(function () {
        Route::controller(DocumentManagementController::class)->group(function () {
            // non json routes
            Route::post('uploadApplicationDocumentFile',  'uploadApplicationDocumentFile');
    Route::post('uploadunstructureddocumentuploads',  'uploadunstructureddocumentuploads');
    Route::post('resumableuploadApplicationDocumentFile',  'uploadLargeFiles');
    
	Route::get('onLoadApplicationDocumentsUploads',  'onLoadApplicationDocumentsUploads');
	Route::get('onLoadApplicationPrevDocumentsUploads',  'onLoadApplicationPrevDocumentsUploads');
    Route::get('onLoadProductImagesUploads',  'onLoadProductImagesUploads');
    Route::get('onLoadApplicationDocumentsRequirements',  'onLoadApplicationDocumentsRequirements');
    Route::get('getApplicationDocumentDownloadurl',  'getApplicationDocumentDownloadurl');
    Route::get('getApplicationDocumentPreviousVersions',  'getApplicationDocumentPreviousVersions');
    Route::get('getProcessApplicableDocTypes',  'getProcessApplicableDocTypes');
    Route::get('getProcessApplicableDocRequirements',  'getProcessApplicableDocRequirements');
    Route::get('onLoadApplicationDocumentsUploadsPortal',  'onLoadApplicationDocumentsUploadsPortal');
    Route::get('LoadAllApplicationUploadedDocuments',  'LoadAllApplicationUploadedDocuments');
    Route::get('onLoadOnlineProductImagesUploads',  'onLoadOnlineProductImagesUploads');
    Route::get('onLoadUnstructureApplicationDocumentsUploads',  'onLoadUnstructureApplicationDocumentsUploads');
    Route::get('getDocumentArchive',  'getDocumentArchive');
	
	  Route::post('uploadProductImages',  'uploadProductImages');
    Route::post('onApplicationDocumentDelete',  'onApplicationDocumentDelete');
    Route::post('onDeleteProductImages',  'onDeleteProductImages');
    Route::post('onDeleteNonStructureApplicationDocument',  'onDeleteNonStructureApplicationDocument');
    Route::post('saveUploadedApplicationPayments',  'saveUploadedApplicationPayments');



    Route::get('/', 'index');
    //post actions gmpproductsselectiongrid 
    
   

        
    
        });

    });
});



Route::middleware(['web'])->group( function () {
    Route::prefix('documentmanagement')->group(function () {
        Route::controller(AuditTrailController::class)->group(function () {
            Route::post('saveDocumentRepositoryStructure', 'saveDocumentRepositoryStructure');
            Route::post('saveDocumentRepositoryRootFolder', 'saveDocumentRepositoryRootFolder');
            Route::post('saveDMSSiteDefinationDetails', 'saveDMSSiteDefinationDetails');
            Route::post('saveDMSSectionDefinationDetails', 'saveDMSSectionDefinationDetails');
            Route::post('saveDMSSecModulesDefinationDetails', 'saveDMSSecModulesDefinationDetails');
            Route::post('saveDMSSecSubModulesDefinationDetails', 'saveDMSSecSubModulesDefinationDetails');
            Route::post('saveDMSModulesDocTypeDefinationDetails', 'saveDMSModulesDocTypeDefinationDetails');
            Route::post('saveDMSNoStructuredDocDefinationDetails', 'saveDMSNoStructuredDocDefinationDetails');
            
            
            //the configurations 
            Route::get('getDocumentsTypes', 'getDocumentsTypes');
            Route::get('getDocumentsSubTypes', 'getDocumentsSubTypes');
        
            
            Route::get('getParameterstableSchema', 'getParameterstableSchema');
            Route::get('getdocdefinationrequirementDetails', 'getdocdefinationrequirementDetails');
            Route::get('docdefinationrequirementfilterdetails', 'docdefinationrequirementfilterdetails');
            Route::get('getdocumentreposirotystructureDetails', 'getdocumentreposirotystructureDetails');
            Route::get('getdocumentsectionsrepstructure', 'getdocumentsectionsrepstructure');
            Route::get('getRepositoryrootfolderDetails', 'getRepositoryrootfolderDetails');
            Route::get('dmsAuthentication', 'dmsAuthentication');
           
            Route::get('getDMSSiteDefinationDetails', 'getDMSSiteDefinationDetails');
            Route::get('getDMSSectionsDefinationDetails', 'getDMSSectionsDefinationDetails');
            Route::get('getDMSSectionsModulesDefinationDetails', 'getDMSSectionsModulesDefinationDetails');
            Route::get('getDMSSectionsSubModulesDefinationDetails', 'getDMSSectionsSubModulesDefinationDetails');
            Route::get('getDMSModulesDocumentTypesDefinationDetails', 'getDMSModulesDocumentTypesDefinationDetails');
           
            Route::get('getSOPMasterListDetails', 'getSOPMasterListDetails');
           
            Route::get('getnonStructuredDocumentsDefination', 'getnonStructuredDocumentsDefination');
            
            //dms Configurations
            Route::get('getDmsParamFromModel', 'getDmsParamFromModel');
            
            Route::post('dmsUpdateAccountPassword', 'dmsUpdateAccountPassword');
        
        
    
        });

    });
});



// //non json routes
// Route::group(['prefix' => 'documentmanagement', 'middleware' => ['web']], function() {
//     Route::post('uploadApplicationDocumentFile', [DocumentManagementController::class, 'uploadApplicationDocumentFile']);
//     Route::post('uploadunstructureddocumentuploads', [DocumentManagementController::class, 'uploadunstructureddocumentuploads']);
//     Route::post('resumableuploadApplicationDocumentFile', [DocumentManagementController::class, 'uploadLargeFiles']);
    
// 	Route::get('onLoadApplicationDocumentsUploads', [DocumentManagementController::class, 'onLoadApplicationDocumentsUploads']);
// 	Route::get('onLoadApplicationPrevDocumentsUploads', [DocumentManagementController::class, 'onLoadApplicationPrevDocumentsUploads']);
//     Route::get('onLoadProductImagesUploads', [DocumentManagementController::class, 'onLoadProductImagesUploads']);
//     Route::get('onLoadApplicationDocumentsRequirements', [DocumentManagementController::class, 'onLoadApplicationDocumentsRequirements']);
//     Route::get('getApplicationDocumentDownloadurl', [DocumentManagementController::class, 'getApplicationDocumentDownloadurl']);
//     Route::get('getApplicationDocumentPreviousVersions', [DocumentManagementController::class, 'getApplicationDocumentPreviousVersions']);
//     Route::get('getProcessApplicableDocTypes', [DocumentManagementController::class, 'getProcessApplicableDocTypes']);
//     Route::get('getProcessApplicableDocRequirements', [DocumentManagementController::class, 'getProcessApplicableDocRequirements']);
//     Route::get('onLoadApplicationDocumentsUploadsPortal', [DocumentManagementController::class, 'onLoadApplicationDocumentsUploadsPortal']);
//     Route::get('LoadAllApplicationUploadedDocuments', [DocumentManagementController::class, 'LoadAllApplicationUploadedDocuments']);
//     Route::get('onLoadOnlineProductImagesUploads', [DocumentManagementController::class, 'onLoadOnlineProductImagesUploads']);
//     Route::get('onLoadUnstructureApplicationDocumentsUploads', [DocumentManagementController::class, 'onLoadUnstructureApplicationDocumentsUploads']);
//     Route::get('getDocumentArchive', [DocumentManagementController::class, 'getDocumentArchive']);
	
// 	  Route::post('uploadProductImages', [DocumentManagementController::class, 'uploadProductImages']);
//     Route::post('onApplicationDocumentDelete', [DocumentManagementController::class, 'onApplicationDocumentDelete']);
//     Route::post('onDeleteProductImages', [DocumentManagementController::class, 'onDeleteProductImages']);
//     Route::post('onDeleteNonStructureApplicationDocument', [DocumentManagementController::class, 'onDeleteNonStructureApplicationDocument']);
//     Route::post('saveUploadedApplicationPayments', [DocumentManagementController::class, 'saveUploadedApplicationPayments']);
   
// });

// Route::group(['prefix' => 'documentmanagement', 'middleware' => ['web'],'namespace' => 'App\\Modules\DocumentManagement\Http\Controllers'], function()
// {
//     Route::get('/', 'DocumentManagementController@index');
//     //post actions gmpproductsselectiongrid 
    
//     Route::post('saveDocumentRepositoryStructure', 'DmsConfigurations@saveDocumentRepositoryStructure');
//     Route::post('saveDocumentRepositoryRootFolder', 'DmsConfigurations@saveDocumentRepositoryRootFolder');
//     Route::post('saveDMSSiteDefinationDetails', 'DmsConfigurations@saveDMSSiteDefinationDetails');
//     Route::post('saveDMSSectionDefinationDetails', 'DmsConfigurations@saveDMSSectionDefinationDetails');
//     Route::post('saveDMSSecModulesDefinationDetails', 'DmsConfigurations@saveDMSSecModulesDefinationDetails');
//     Route::post('saveDMSSecSubModulesDefinationDetails', 'DmsConfigurations@saveDMSSecSubModulesDefinationDetails');
//     Route::post('saveDMSModulesDocTypeDefinationDetails', 'DmsConfigurations@saveDMSModulesDocTypeDefinationDetails');
//     Route::post('saveDMSNoStructuredDocDefinationDetails', 'DmsConfigurations@saveDMSNoStructuredDocDefinationDetails');
    
    
//     //the configurations 
//     Route::get('getDocumentsTypes', 'DmsConfigurations@getDocumentsTypes');
//     Route::get('getDocumentsSubTypes', 'DmsConfigurations@getDocumentsSubTypes');

    
//     Route::get('getParameterstableSchema', 'DmsConfigurations@getParameterstableSchema');
//     Route::get('getdocdefinationrequirementDetails', 'DmsConfigurations@getdocdefinationrequirementDetails');
//     Route::get('docdefinationrequirementfilterdetails', 'DmsConfigurations@docdefinationrequirementfilterdetails');
//     Route::get('getdocumentreposirotystructureDetails', 'DmsConfigurations@getdocumentreposirotystructureDetails');
//     Route::get('getdocumentsectionsrepstructure', 'DmsConfigurations@getdocumentsectionsrepstructure');
//     Route::get('getRepositoryrootfolderDetails', 'DmsConfigurations@getRepositoryrootfolderDetails');
//     Route::get('dmsAuthentication', 'DmsConfigurations@dmsAuthentication');
   
//     Route::get('getDMSSiteDefinationDetails', 'DmsConfigurations@getDMSSiteDefinationDetails');
//     Route::get('getDMSSectionsDefinationDetails', 'DmsConfigurations@getDMSSectionsDefinationDetails');
//     Route::get('getDMSSectionsModulesDefinationDetails', 'DmsConfigurations@getDMSSectionsModulesDefinationDetails');
//     Route::get('getDMSSectionsSubModulesDefinationDetails', 'DmsConfigurations@getDMSSectionsSubModulesDefinationDetails');
//     Route::get('getDMSModulesDocumentTypesDefinationDetails', 'DmsConfigurations@getDMSModulesDocumentTypesDefinationDetails');
   
//     Route::get('getSOPMasterListDetails', 'DmsConfigurations@getSOPMasterListDetails');
   
//     Route::get('getnonStructuredDocumentsDefination', 'DmsConfigurations@getnonStructuredDocumentsDefination');
    
//     //dms Configurations
//     Route::get('getDmsParamFromModel', 'DmsConfigurations@getDmsParamFromModel');
    
//     Route::post('dmsUpdateAccountPassword', 'DmsConfigurations@dmsUpdateAccountPassword');

    
    
// });



//already commented in incase of any reinstatment on route updates



//json routes
/*
Route::group(['prefix' => 'documentmanagement','middleware' => ['web']], function() {
Route::post('saveDocumentRepositoryStructure', [DmsConfigurations::class, 'saveDocumentRepositoryStructure']);
    Route::post('saveDocumentRepositoryRootFolder', [DmsConfigurations::class, 'saveDocumentRepositoryRootFolder']);
    Route::post('saveDMSSiteDefinationDetails', [DmsConfigurations::class, 'saveDMSSiteDefinationDetails']);
    Route::post('saveDMSSectionDefinationDetails', [DmsConfigurations::class, 'saveDMSSectionDefinationDetails']);
    Route::post('saveDMSSecModulesDefinationDetails', [DmsConfigurations::class, 'saveDMSSecModulesDefinationDetails']);
    Route::post('saveDMSSecSubModulesDefinationDetails', [DmsConfigurations::class, 'saveDMSSecSubModulesDefinationDetails']);
    Route::post('saveDMSModulesDocTypeDefinationDetails', [DmsConfigurations::class, 'saveDMSModulesDocTypeDefinationDetails']);
    Route::post('saveDMSNoStructuredDocDefinationDetails', [DmsConfigurations::class, 'saveDMSNoStructuredDocDefinationDetails']);
    
   
    Route::get('getDocumentsTypes', [DmsConfigurations::class, 'getDocumentsTypes']);
    Route::get('getDocumentsSubTypes', [DmsConfigurations::class, 'getDocumentsSubTypes']);
    Route::get('getParameterstableSchema', [DmsConfigurations::class, 'getParameterstableSchema']);
    Route::get('getdocdefinationrequirementDetails', [DmsConfigurations::class, 'getdocdefinationrequirementDetails']);
    Route::get('docdefinationrequirementfilterdetails', [DmsConfigurations::class, 'docdefinationrequirementfilterdetails']);
    Route::get('getdocumentreposirotystructureDetails', [DmsConfigurations::class, 'getdocumentreposirotystructureDetails']);
    Route::get('getdocumentsectionsrepstructure', [DmsConfigurations::class, 'getdocumentsectionsrepstructure']);
    Route::get('getRepositoryrootfolderDetails', [DmsConfigurations::class, 'getRepositoryrootfolderDetails']);
    Route::get('dmsAuthentication', [DmsConfigurations::class, 'dmsAuthentication']);
    Route::get('getDMSSiteDefinationDetails', [DmsConfigurations::class, 'getDMSSiteDefinationDetails']);
    Route::get('getDMSSectionsDefinationDetails', [DmsConfigurations::class, 'getDMSSectionsDefinationDetails']);
    Route::get('getDMSSectionsModulesDefinationDetails', [DmsConfigurations::class, 'getDMSSectionsModulesDefinationDetails']);
    Route::get('getDMSSectionsSubModulesDefinationDetails', [DmsConfigurations::class, 'getDMSSectionsSubModulesDefinationDetails']);
    Route::get('getDMSModulesDocumentTypesDefinationDetails', [DmsConfigurations::class, 'getDMSModulesDocumentTypesDefinationDetails']);
    Route::get('getSOPMasterListDetails', [DmsConfigurations::class, 'getSOPMasterListDetails']);
    Route::get('getnonStructuredDocumentsDefination', [DmsConfigurations::class, 'getnonStructuredDocumentsDefination']);
    Route::get('getDmsParamFromModel', [DmsConfigurations::class, 'getDmsParamFromModel']);
    
   // Route::post('dmsUpdateAccountPassword', [DmsConfigurations::class, 'dmsUpdateAccountPassword']);

    });

*/