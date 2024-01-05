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

Route::group(['middleware' => 'api', 'prefix' => 'productregistration'], function()
{
    Route::get('/', 'ProductRegistrationController@index');
    Route::get('onSearchPublicRegisteredproducts', 'ProductRegistrationController@getSearchPublicRegisteredproducts');
    Route::get('onValidateProductOtherdetails', 'ProductRegistrationController@onValidateProductOtherdetails');
    
});

Route::group(['middleware' => 'auth:integration', 'prefix' => 'productregistration'], function()
{
    Route::get('getProductApplications', 'ProductRegistrationController@getProductApplications');
    Route::get('getLocaAgentProductApplications', 'ProductRegistrationController@getLocaAgentProductApplications');
    
    
});
//, 'namespace' => 'Modules\ProductRegistration\Http\Controllers'
Route::group(['middleware' => 'auth:api', 'prefix' => 'productregistration'], function()
{
    
    Route::post('onSaveProductApplication', 'ProductRegistrationController@onSaveProductApplication');
    Route::post('onSaveRenAltProductApplication', 'ProductRegistrationController@onSaveRenAltProductApplication');
    Route::post('onSaveWithdrawalProductApplication', 'ProductRegistrationController@onSaveWithdrawalProductApplication');
   
    Route::post('onSaveProductOtherDetails', 'ProductRegistrationController@onSaveProductOtherDetails');
    Route::post('onDeleteProductsDetails', 'ProductRegistrationController@onDeleteProductsDetails');
    Route::post('onAddManufacturingSite', 'ProductRegistrationController@onAddManufacturingSite');
    Route::post('onNewProductsApplicationSubmit', 'ProductRegistrationController@onNewProductsApplicationSubmit');
    Route::post('onSaveMedicalProductNotification', 'ProductRegistrationController@onSaveMedicalProductNotification');
    Route::get('getProductsNutrients', 'ProductRegistrationController@getProductsNutrients');
    
    Route::get('getProductsIngredients', 'ProductRegistrationController@getProductsIngredients');
    Route::get('getProductApplicationInformation', 'ProductRegistrationController@getProductApplicationInformation');
    Route::get('getProductsDrugsPackaging', 'ProductRegistrationController@getProductsDrugsPackaging');
    Route::get('getManufacturingSiteInformation', 'ProductRegistrationController@getManufacturingSiteInformation');
    Route::get('getManufacturersInformation', 'ProductRegistrationController@getManufacturersInformation');

    Route::get('getproductManufactureringData', 'ProductRegistrationController@getproductManufactureringData');
    Route::get('getAPIproductManufactureringData', 'ProductRegistrationController@getAPIproductManufactureringData');
    Route::get('getTraderInformationDetails', 'ProductRegistrationController@getTraderInformationDetails');
    Route::get('getProductsCounterDetails', 'ProductRegistrationController@getProductsCounterDetails');
    Route::get('getGmpInspectionsdetails', 'ProductRegistrationController@getGmpInspectionsdetails');
  
    Route::get('getProductsGMPInspectionDetails', 'ProductRegistrationController@getProductsGMPInspectionDetails');
  
    Route::get('onSearchRegisteredProductApplication', 'ProductRegistrationController@onSearchRegisteredProductApplication');
    Route::get('registeredProductsData', 'ProductRegistrationController@onSearchRegisteredProductApplication');
    Route::get('getProductSampleStageInformation', 'ProductRegistrationController@getProductSampleStageInformation');
    Route::get('getSampleSubmissionDetails', 'ProductRegistrationController@getSampleSubmissionDetails');
    
    Route::get('getproductNotificationsApps', 'ProductRegistrationController@getproductNotificationsApps');
    
    Route::get('getProductNotificationsInformation', 'ProductRegistrationController@getProductNotificationsInformation');
    
    Route::get('getgmpProductLineDatadetails', 'ProductRegistrationController@getgmpProductLineDatadetails');
    Route::get('onValidateBrandNameDetails', 'ProductRegistrationController@onValidateBrandNameDetails');
	
    Route::post('onSaveGroupedApplicationdetails', 'ProductRegistrationController@onSaveGroupedApplicationdetails');
    
     
    Route::get('getGroupedProductApplicationInformation', 'ProductRegistrationController@getGroupedProductApplicationInformation');
    Route::get('getGroupedProductApplicationsSub', 'ProductRegistrationController@getGroupedProductApplicationsSub');
    
	
	 
    Route::post('onSaveRenetionRequestApplication', 'ProductRegistrationController@onSaveRenetionRequestApplication');
    Route::get('getProductProductretentionRequests', 'ProductRegistrationController@getProductProductretentionRequests');
    Route::get('getAnnualretentionRegisteredProducts', 'ProductRegistrationController@getAnnualretentionRegisteredProducts');
    
    Route::post('saveProductRetentionSelection', 'ProductRegistrationController@saveProductRetentionSelection');
    Route::get('getretentionFeesProductsData', 'ProductRegistrationController@getretentionFeesProductsData');
    Route::post('onDeleteRetentionProductsDetails', 'ProductRegistrationController@onDeleteRetentionProductsDetails');
    Route::get('getOnProductSummaryVariationChanges', 'ProductRegistrationController@getOnProductSummaryVariationChanges');
  
    Route::get('onSearchFoodProductProductApplication', 'ProductRegistrationController@onSearchFoodProductProductApplication');
  

    
});
