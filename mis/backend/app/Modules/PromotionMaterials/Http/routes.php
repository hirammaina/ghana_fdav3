<?php

use App\Modules\PromotionMaterials\Http\Controllers\PromotionMaterialsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('promotionmaterials')->group(function () {
        Route::controller(PromotionMaterialsController::class)->group(function () {
			Route::get('/', 'index');
	
			Route::post('/saveApplicantDetails','saveApplicantDetails');
			Route::get('/getPromotionMaterialsApplications','getPromotionMaterialsApplications');
			Route::get('/getPromotionMaterialsProductParticular','getPromotionMaterialsProductParticular');
			
			Route::get('/getOnlinePromotionMaterialsProductParticular','getOnlinePromotionMaterialsProductParticular');
			Route::get('/getOnlinePromotionMaterialsDetails','getOnlinePromotionMaterialsDetails');
			Route::get('/prepareOnlinePromotionalAppReceiving','prepareOnlinePromotionalAppReceiving');
			Route::get('/preparePromotionalAppDetailsReceiving','preparePromotionalAppDetailsReceiving');
		
		
			Route::post('/insertUpdateProductParticulars','insertUpdateProductParticulars');
			Route::post('/genericDeleteRecord','genericDeleteRecord');//
			//Route::post('/insertUpdateProductIngredStren','insertUpdateProductIngredinetsStrength');//
			
			
			
			Route::get('/getPromotionMaterialsDetails','getPromotionMaterialsDetails');
			
			Route::get('/preparePromotionAndAdvertPaymentStage','preparePromotionAndAdvertPaymentStage');
			Route::get('/getManagerApplicationsGeneric','getManagerApplicationsGeneric');
			Route::get('/prepareForPromotionAndAdvertsEvaluation','prepareForPromotionAndAdvertsEvaluation');
			
			Route::get('/getPromotionAndAdvertsApplicationsAtApproval','getPromotionAndAdvertsApplicationsAtApproval');
			Route::post('/insertUpdatePromoAdvertComments','insertUpdatePromoAdvertComments');//
			Route::get('/getSponsorsList','getSponsorsList');
			
			Route::post('/insertUpdateSponsorDetails','insertUpdateSponsorDetails');
			Route::get('/getProductIngredientStrengthDetails','getProductIngredientStrengthDetails');
			Route::post('/insertUpdateProductIngredinetsStrength','insertUpdateProductIngredinetsStrength');
			
			
			//online
			
		   Route::get('/getPromoAdvertsOnlineApplications','getPromoAdvertsOnlineApplications');
		   Route::get('/preparePromotionAdvertInvoicingStage','preparePromotionAdvertInvoicingStage');
				Route::get('/getRegisteredPromotionMaterialsApps','getRegisteredPromotionMaterialsApps');
		   
			 Route::get('/onRegisteredPromotionalSearchdetails','onRegisteredPromotionalSearchdetails');
			 Route::post('/savePromotionalApplicationRenewalsDetails','savePromotionalApplicationRenewalsDetails');
		  
        
        
    
        });

    });
});



// Route::group(['middleware' => 'web', 'prefix' => 'promotionmaterials', 'namespace' => 'App\\Modules\PromotionMaterials\Http\Controllers'], function()
// {
//     Route::get('/', 'PromotionMaterialsController@index');
	
// 	Route::post('/saveApplicantDetails','PromotionMaterialsController@saveApplicantDetails');
// 	Route::get('/getPromotionMaterialsApplications','PromotionMaterialsController@getPromotionMaterialsApplications');
// 	Route::get('/getPromotionMaterialsProductParticular','PromotionMaterialsController@getPromotionMaterialsProductParticular');
	
// 	Route::get('/getOnlinePromotionMaterialsProductParticular','PromotionMaterialsController@getOnlinePromotionMaterialsProductParticular');
// 	Route::get('/getOnlinePromotionMaterialsDetails','PromotionMaterialsController@getOnlinePromotionMaterialsDetails');
// 	Route::get('/prepareOnlinePromotionalAppReceiving','PromotionMaterialsController@prepareOnlinePromotionalAppReceiving');
// 	Route::get('/preparePromotionalAppDetailsReceiving','PromotionMaterialsController@preparePromotionalAppDetailsReceiving');


// 	Route::post('/insertUpdateProductParticulars','PromotionMaterialsController@insertUpdateProductParticulars');
// 	Route::post('/genericDeleteRecord','PromotionMaterialsController@genericDeleteRecord');//
// 	//Route::post('/insertUpdateProductIngredStren','PromotionMaterialsController@insertUpdateProductIngredinetsStrength');//
	
	
	
// 	Route::get('/getPromotionMaterialsDetails','PromotionMaterialsController@getPromotionMaterialsDetails');
	
// 	Route::get('/preparePromotionAndAdvertPaymentStage','PromotionMaterialsController@preparePromotionAndAdvertPaymentStage');
// 	Route::get('/getManagerApplicationsGeneric','PromotionMaterialsController@getManagerApplicationsGeneric');
// 	Route::get('/prepareForPromotionAndAdvertsEvaluation','PromotionMaterialsController@prepareForPromotionAndAdvertsEvaluation');
	
// 	Route::get('/getPromotionAndAdvertsApplicationsAtApproval','PromotionMaterialsController@getPromotionAndAdvertsApplicationsAtApproval');
// 	Route::post('/insertUpdatePromoAdvertComments','PromotionMaterialsController@insertUpdatePromoAdvertComments');//
// 	Route::get('/getSponsorsList','PromotionMaterialsController@getSponsorsList');
	
// 	Route::post('/insertUpdateSponsorDetails','PromotionMaterialsController@insertUpdateSponsorDetails');
// 	Route::get('/getProductIngredientStrengthDetails','PromotionMaterialsController@getProductIngredientStrengthDetails');
// 	Route::post('/insertUpdateProductIngredinetsStrength','PromotionMaterialsController@insertUpdateProductIngredinetsStrength');
	
	
// 	//online
	
//    Route::get('/getPromoAdvertsOnlineApplications','PromotionMaterialsController@getPromoAdvertsOnlineApplications');
//    Route::get('/preparePromotionAdvertInvoicingStage','PromotionMaterialsController@preparePromotionAdvertInvoicingStage');
//    	 Route::get('/getRegisteredPromotionMaterialsApps','PromotionMaterialsController@getRegisteredPromotionMaterialsApps');
   
// 	 Route::get('/onRegisteredPromotionalSearchdetails','PromotionMaterialsController@onRegisteredPromotionalSearchdetails');
// 	 Route::post('/savePromotionalApplicationRenewalsDetails','PromotionMaterialsController@savePromotionalApplicationRenewalsDetails');
  
	 
// });
