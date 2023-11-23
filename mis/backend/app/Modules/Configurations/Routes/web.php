<?php

use App\Modules\Configurations\Http\Controllers\ConfigurationsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
Route::prefix('configurations')->group(function () {
    Route::controller(ConfigurationsController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('saveConfigCommonData', 'saveConfigCommonData');
    Route::post('saveProofUpAuthorisationConfigCommonData', 'saveProofUpAuthorisationConfigCommonData');
    Route::post('saveSystemModuleData', 'saveSystemModuleData');
    
    Route::get('getConfigParamFromModel', 'getConfigParamFromModel');
    Route::post('deleteConfigRecord', 'deleteConfigRecord');
    Route::post('softDeleteConfigRecord', 'softDeleteConfigRecord');
    Route::post('undoConfigSoftDeletes', 'undoConfigSoftDeletes');
    Route::get('getChecklistTypes', 'getChecklistTypes');
    Route::get('getChecklistItems', 'getChecklistItems');
    Route::get('getAllApplicationStatuses', 'getAllApplicationStatuses');
    Route::get('getAlterationParameters', 'getAlterationParameters');

    Route::get('getConfigParamFromTable', 'getConfigParamFromTable');
    Route::get('getproductApplicationParameters', 'getproductApplicationParameters');
    Route::get('getproductSubCategoryParameters', 'getproductSubCategoryParameters');
    Route::get('getRegistrationApplicationParameters', 'getRegistrationApplicationParameters');

    Route::get('getCertificateConditionsApplicationParameters','getCertificateConditionsApplicationParameters');
    Route::get('getproductGeneraicNameParameters', 'getproductGeneraicNameParameters');
    Route::get('getsystemSubModules', 'getsystemSubModules');
    Route::get('getsystemModules', 'getsystemModules');
    Route::get('getRefnumbersformats', 'getRefnumbersformats');
    Route::get('getregistrationexpirytime_span', 'getregistrationexpirytime_span');
    Route::get('getVariationCategoriesParameters', 'getVariationCategoriesParameters');
    
    Route::get('getPayingCurrency', 'getPayingCurrency');
    Route::get('getNonrefParameter', 'getNonrefParameter');
    Route::get('getSubmoduleRefFormats', 'getSubmoduleRefFormats');
    Route::get('getApplicationSections', 'getApplicationSections');
     
    Route::get('getUnstructuredQueryChecklistItem', 'getUnstructuredQueryChecklistItem');
    Route::get('getUnstructuredQueryChecklistTypes', 'getUnstructuredQueryChecklistTypes');
    Route::get('getproductGenericApplicationParameters', 'getproductGenericApplicationParameters');
    Route::get('getGenericNamesAtcCodes', 'getGenericNamesAtcCodes');
  
    Route::get('getPersonnelDetails', 'getPersonnelDetails');
    Route::get('getProductClassRules', 'getProductClassRules');
    Route::get('getClassRulesParameters', 'getClassRulesParameters');
    Route::get('getManRolesParameters', 'getManRolesParameters');
    Route::get('getPortalAppsInitialStatuses', 'getPortalAppsInitialStatuses');
    Route::post('saveEditedConfigCommonData', 'saveEditedConfigCommonData');

    Route::get('getApplicationAmmendment', 'getApplicationAmmendment');
    Route::get('getConfigDirectors', 'getConfigDirectors');


    Route::get('getProductInvoiceChargesConfig', 'getProductInvoiceChargesConfig');
    Route::get('getPremiseInvoiceChargesConfig', 'getPremiseInvoiceChargesConfig');
    Route::get('getRetentionChargesConfig', 'getRetentionChargesConfig');
    Route::get('getElementCostWithCurrency', 'getElementCostWithCurrency');

    
    

    Route::post('saveDocDefinationrequirement', 'saveDocDefinationrequirement');
    Route::get('getDirectoratesUnits', 'getDirectoratesUnits');
    Route::post('saveDocumentMasterListConfig', 'saveDocumentMasterListConfig');
    //dynamic config
    Route::get('getParameterGridColumnsConfig', 'getParameterGridColumnsConfig');
    Route::get('getParameterGridConfig', 'getParameterGridConfig');
    Route::get('getParameterFormColumnsConfig', 'getParameterFormColumnsConfig');

    Route::get('getCountryMappedProcedures', 'getCountryMappedProcedures');
    Route::post('mapProcedureToCountry', 'mapProcedureToCountry');
    Route::get('getOnlineApplicationStatus', 'getOnlineApplicationStatus');
    Route::post('saveConfigPortalCommonData', 'saveConfigPortalCommonData');
    Route::get('getConfigParamFromPortalTable', 'getConfigParamFromPortalTable');

   Route::post('saveModuleFeeConfigCommonData', 'saveModuleFeeConfigCommonData');
    Route::post('getNewInvoiceQuotation', 'getNewInvoiceQuotation');
	Route::get('getAppModuleFeeConfig', 'getAppModuleFeeConfig');
	
	Route::post('saveConfigVariationsRequestDetails', 'saveConfigVariationsRequestDetails');
        
        
    
        });

    });
});

// Route::group(['middleware' => 'web', 'prefix' => 'configurations', 'namespace' => 'App\\Modules\Configurations\Http\Controllers'], function()
// {
//     Route::get('/', 'ConfigurationsController@index');
//     Route::post('saveConfigCommonData', 'ConfigurationsController@saveConfigCommonData');
//     Route::post('saveProofUpAuthorisationConfigCommonData', 'ConfigurationsController@saveProofUpAuthorisationConfigCommonData');
//     Route::post('saveSystemModuleData', 'ConfigurationsController@saveSystemModuleData');
    
//     Route::get('getConfigParamFromModel', 'ConfigurationsController@getConfigParamFromModel');
//     Route::post('deleteConfigRecord', 'ConfigurationsController@deleteConfigRecord');
//     Route::post('softDeleteConfigRecord', 'ConfigurationsController@softDeleteConfigRecord');
//     Route::post('undoConfigSoftDeletes', 'ConfigurationsController@undoConfigSoftDeletes');
//     Route::get('getChecklistTypes', 'ConfigurationsController@getChecklistTypes');
//     Route::get('getChecklistItems', 'ConfigurationsController@getChecklistItems');
//     Route::get('getAllApplicationStatuses', 'ConfigurationsController@getAllApplicationStatuses');
//     Route::get('getAlterationParameters', 'ConfigurationsController@getAlterationParameters');

//     Route::get('getConfigParamFromTable', 'ConfigurationsController@getConfigParamFromTable');
//     Route::get('getproductApplicationParameters', 'ConfigurationsController@getproductApplicationParameters');
//     Route::get('getproductSubCategoryParameters', 'ConfigurationsController@getproductSubCategoryParameters');
//     Route::get('getRegistrationApplicationParameters', 'ConfigurationsController@getRegistrationApplicationParameters');

//     Route::get('getCertificateConditionsApplicationParameters','ConfigurationsController@getCertificateConditionsApplicationParameters');
//     Route::get('getproductGeneraicNameParameters', 'ConfigurationsController@getproductGeneraicNameParameters');
//     Route::get('getsystemSubModules', 'ConfigurationsController@getsystemSubModules');
//     Route::get('getsystemModules', 'ConfigurationsController@getsystemModules');
//     Route::get('getRefnumbersformats', 'ConfigurationsController@getRefnumbersformats');
//     Route::get('getregistrationexpirytime_span', 'ConfigurationsController@getregistrationexpirytime_span');
//     Route::get('getVariationCategoriesParameters', 'ConfigurationsController@getVariationCategoriesParameters');
    
//     Route::get('getPayingCurrency', 'ConfigurationsController@getPayingCurrency');
//     Route::get('getNonrefParameter', 'ConfigurationsController@getNonrefParameter');
//     Route::get('getSubmoduleRefFormats', 'ConfigurationsController@getSubmoduleRefFormats');
//     Route::get('getApplicationSections', 'ConfigurationsController@getApplicationSections');
     
//     Route::get('getUnstructuredQueryChecklistItem', 'ConfigurationsController@getUnstructuredQueryChecklistItem');
//     Route::get('getUnstructuredQueryChecklistTypes', 'ConfigurationsController@getUnstructuredQueryChecklistTypes');
//     Route::get('getproductGenericApplicationParameters', 'ConfigurationsController@getproductGenericApplicationParameters');
//     Route::get('getGenericNamesAtcCodes', 'ConfigurationsController@getGenericNamesAtcCodes');
  
//     Route::get('getPersonnelDetails', 'ConfigurationsController@getPersonnelDetails');
//     Route::get('getProductClassRules', 'ConfigurationsController@getProductClassRules');
//     Route::get('getClassRulesParameters', 'ConfigurationsController@getClassRulesParameters');
//     Route::get('getManRolesParameters', 'ConfigurationsController@getManRolesParameters');
//     Route::get('getPortalAppsInitialStatuses', 'ConfigurationsController@getPortalAppsInitialStatuses');
//     Route::post('saveEditedConfigCommonData', 'ConfigurationsController@saveEditedConfigCommonData');

//     Route::get('getApplicationAmmendment', 'ConfigurationsController@getApplicationAmmendment');
//     Route::get('getConfigDirectors', 'ConfigurationsController@getConfigDirectors');


//     Route::get('getProductInvoiceChargesConfig', 'ConfigurationsController@getProductInvoiceChargesConfig');
//     Route::get('getPremiseInvoiceChargesConfig', 'ConfigurationsController@getPremiseInvoiceChargesConfig');
//     Route::get('getRetentionChargesConfig', 'ConfigurationsController@getRetentionChargesConfig');
//     Route::get('getElementCostWithCurrency', 'ConfigurationsController@getElementCostWithCurrency');

    
    

//     Route::post('saveDocDefinationrequirement', 'ConfigurationsController@saveDocDefinationrequirement');
//     Route::get('getDirectoratesUnits', 'ConfigurationsController@getDirectoratesUnits');
//     Route::post('saveDocumentMasterListConfig', 'ConfigurationsController@saveDocumentMasterListConfig');
//     //dynamic config
//     Route::get('getParameterGridColumnsConfig', 'ConfigurationsController@getParameterGridColumnsConfig');
//     Route::get('getParameterGridConfig', 'ConfigurationsController@getParameterGridConfig');
//     Route::get('getParameterFormColumnsConfig', 'ConfigurationsController@getParameterFormColumnsConfig');

//     Route::get('getCountryMappedProcedures', 'ConfigurationsController@getCountryMappedProcedures');
//     Route::post('mapProcedureToCountry', 'ConfigurationsController@mapProcedureToCountry');
//     Route::get('getOnlineApplicationStatus', 'ConfigurationsController@getOnlineApplicationStatus');
//     Route::post('saveConfigPortalCommonData', 'ConfigurationsController@saveConfigPortalCommonData');
//     Route::get('getConfigParamFromPortalTable', 'ConfigurationsController@getConfigParamFromPortalTable');

//    Route::post('saveModuleFeeConfigCommonData', 'ConfigurationsController@saveModuleFeeConfigCommonData');
//     Route::post('getNewInvoiceQuotation', 'ConfigurationsController@getNewInvoiceQuotation');
// 	Route::get('getAppModuleFeeConfig', 'ConfigurationsController@getAppModuleFeeConfig');
	
// 	Route::post('saveConfigVariationsRequestDetails', 'ConfigurationsController@saveConfigVariationsRequestDetails');

    
// });
