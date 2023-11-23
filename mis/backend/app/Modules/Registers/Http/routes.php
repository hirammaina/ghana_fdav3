<?php

use App\Modules\Registers\Http\Controllers\RegistersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('registers')->group(function () {
        Route::controller(RegistersController::class)->group(function () {
          Route::get('getProductRegister', 'getProductRegister');
          Route::get('exportProductRegister', 'exportProductRegister');
          Route::get('printProductGazzete', 'printProductGazzete');
          Route::get('checkPrintProductGazzete', 'checkPrintProductGazzete');
          
          //medicines routes
          Route::get('getMedicinesRegister', 'getMedicinesRegister');
          Route::get('exportMedicinesRegister', 'exportMedicinesRegister');
          Route::get('printMedicinesRegister', 'printMedicinesRegister');
      
          //Premises routes
          Route::get('getPremiseRegister', 'getPremiseRegister');
          Route::get('exportPremiseRegister', 'exportPremiseRegister');
          Route::get('printPremisesRegister', 'printPremisesRegister');
          Route::get('checkPrintPremisesRegister', 'checkPrintPremisesRegister');
      
          
      
          //gmp facility
          Route::get('getGmpRegister', 'getGmpRegister');
          Route::get('exportGmpRegister', 'exportGmpRegister');
          Route::get('printGmpRegister', 'printGmpRegister');
          Route::get('checkPrintGmpRegister', 'checkPrintGmpRegister');
      
      
          
      
          //clinicaltrial 
          Route::get('getClinicalTrialRegister', 'getClinicalTrialRegister');
          Route::get('printClinicalTrialRegister', 'printClinicalTrialRegister');
          Route::get('exportClinicalTrialRegister', 'exportClinicalTrialRegister');
           Route::get('checkPrintClinicalTrialRegister', 'checkPrintClinicalTrialRegister');
          
      
          
      
      
          //Disposal
          Route::get('getDisposalRegister', 'getDisposalRegister');
          Route::get('printDisposalRegister', 'printDisposalRegister');
          Route::get('exportDisposalRegister', 'exportDisposalRegister');
          Route::get('checkPrintDisposalRegister', 'checkPrintDisposalRegister');
      
       
          //Promotion & Advertisement
          Route::get('getPromotionAdvertisementRegister', 'getPromotionAdvertisementRegister');
          Route::get('printPromotionAdvertisementRegister', 'printPromotionAdvertisementRegister');
          Route::get('exportPromotionAdvertisementRegister', 'exportPromotionAdvertisementRegister');
          Route::get('checkPrintPromotionAdvertisementRegister', 'checkPrintPromotionAdvertisementRegister');
      
           
          //Clinical Trial
          Route::get('getImportExportRegister', 'getImportExportRegister');
          Route::get('printImportExportRegister', 'printImportExportRegister');
          Route::get('exportImportExportRegister', 'exportImportExportRegister');
          Route::get('checkPrintImportExportRegister', 'checkPrintImportExportRegister');
      
          
      
         //common route
       Route::get('exportallRegisters', 'exportallRegisters');
       Route::get('printControlledDrugsRegister', 'printControlledDrugsRegister');
       Route::get('exportControlledDrugsRegister', 'exportControlledDrugsRegister');
        Route::get('checkPrintControlledDrugsRegister', 'checkPrintControlledDrugsRegister');
      
        //product Revenue registere
        Route::get('getProductRevenueRegister', 'getProductRevenueRegister');
        Route::get('exportProductRevenueRegister', 'exportProductRevenueRegister');
        Route::get('printProductRevenueGazzete', 'printProductRevenueGazzete');
        Route::get('checkPrintProductRevenueGazzete', 'checkPrintProductRevenueGazzete');
    
        });

    });
});



// Route::group(['middleware' => 'web', 'prefix' => 'registers', 'namespace' => 'App\\Modules\Registers\Http\Controllers'], function()
// {

//     Route::get('getProductRegister', 'RegistersController@getProductRegister');
//     Route::get('exportProductRegister', 'RegistersController@exportProductRegister');
//     Route::get('printProductGazzete', 'RegistersController@printProductGazzete');
//     Route::get('checkPrintProductGazzete', 'RegistersController@checkPrintProductGazzete');
    
//     //medicines routes
//     Route::get('getMedicinesRegister', 'RegistersController@getMedicinesRegister');
//     Route::get('exportMedicinesRegister', 'RegistersController@exportMedicinesRegister');
//     Route::get('printMedicinesRegister', 'RegistersController@printMedicinesRegister');

//     //Premises routes
//     Route::get('getPremiseRegister', 'RegistersController@getPremiseRegister');
//     Route::get('exportPremiseRegister', 'RegistersController@exportPremiseRegister');
//     Route::get('printPremisesRegister', 'RegistersController@printPremisesRegister');
//     Route::get('checkPrintPremisesRegister', 'RegistersController@checkPrintPremisesRegister');

    

//     //gmp facility
//     Route::get('getGmpRegister', 'RegistersController@getGmpRegister');
//     Route::get('exportGmpRegister', 'RegistersController@exportGmpRegister');
//     Route::get('printGmpRegister', 'RegistersController@printGmpRegister');
//     Route::get('checkPrintGmpRegister', 'RegistersController@checkPrintGmpRegister');


    

//     //clinicaltrial 
//     Route::get('getClinicalTrialRegister', 'RegistersController@getClinicalTrialRegister');
//     Route::get('printClinicalTrialRegister', 'RegistersController@printClinicalTrialRegister');
//     Route::get('exportClinicalTrialRegister', 'RegistersController@exportClinicalTrialRegister');
//      Route::get('checkPrintClinicalTrialRegister', 'RegistersController@checkPrintClinicalTrialRegister');
    

    


//     //Disposal
//     Route::get('getDisposalRegister', 'RegistersController@getDisposalRegister');
//     Route::get('printDisposalRegister', 'RegistersController@printDisposalRegister');
//     Route::get('exportDisposalRegister', 'RegistersController@exportDisposalRegister');
//     Route::get('checkPrintDisposalRegister', 'RegistersController@checkPrintDisposalRegister');

 
//     //Promotion & Advertisement
//     Route::get('getPromotionAdvertisementRegister', 'RegistersController@getPromotionAdvertisementRegister');
//     Route::get('printPromotionAdvertisementRegister', 'RegistersController@printPromotionAdvertisementRegister');
//     Route::get('exportPromotionAdvertisementRegister', 'RegistersController@exportPromotionAdvertisementRegister');
//     Route::get('checkPrintPromotionAdvertisementRegister', 'RegistersController@checkPrintPromotionAdvertisementRegister');

     
//     //Clinical Trial
//     Route::get('getImportExportRegister', 'RegistersController@getImportExportRegister');
//     Route::get('printImportExportRegister', 'RegistersController@printImportExportRegister');
//     Route::get('exportImportExportRegister', 'RegistersController@exportImportExportRegister');
//     Route::get('checkPrintImportExportRegister', 'RegistersController@checkPrintImportExportRegister');

    

//    //common route
//  Route::get('exportallRegisters', 'RegistersController@exportallRegisters');
//  Route::get('printControlledDrugsRegister', 'RegistersController@printControlledDrugsRegister');
//  Route::get('exportControlledDrugsRegister', 'RegistersController@exportControlledDrugsRegister');
//   Route::get('checkPrintControlledDrugsRegister', 'RegistersController@checkPrintControlledDrugsRegister');

//   //product Revenue registere
//   Route::get('getProductRevenueRegister', 'RegistersController@getProductRevenueRegister');
//   Route::get('exportProductRevenueRegister', 'RegistersController@exportProductRevenueRegister');
//   Route::get('printProductRevenueGazzete', 'RegistersController@printProductRevenueGazzete');
//   Route::get('checkPrintProductRevenueGazzete', 'RegistersController@checkPrintProductRevenueGazzete');
  
 
// });
