<?php

use Illuminate\Support\Facades\Route;
use Modules\Parameters\Http\Controllers\CommonParameter;
use Modules\Parameters\Http\Controllers\OrganizationParameter;
use Modules\Parameters\Http\Controllers\PremiseRegistration;

Route::middleware(['web'])->group( function () {
    Route::prefix('parameters')->group(function () {
        Route::controller(CommonParameter::class)->group(function () {
            Route::post('/{entity}', 'saveParameter');
    Route::put('/{entity}', 'saveParameter');
    Route::put('{entity}/merge', 'merge');
    Route::get('/{entity}', 'getParameters');
    Route::delete('/{entity}/{id}/{action}', 'deleteParameter')
        -> where(
            [
                'id' => '[0-9]+',
                'action' => 'actual|soft|enable'
            ]
        );
        
    
        });

    });
});

Route::middleware(['web'])->group( function () {
    Route::prefix('premiseregistration/parameters')->group(function () {
        Route::controller(PremiseRegistration::class)->group(function () {
            Route::post('/{entity}', 'saveParameter');
            Route::put('/{entity}', 'saveParameter');
            Route::put('{entity}/merge', 'merge');
            Route::get('/{entity}', 'getParameters');
            Route::delete('/{entity}/{id}/{action}', 'deleteParameter')
                -> where(
                    [
                        'id' => '[0-9]+',
                        'action' => 'actual|soft|enable'
                    ]
                );
        
    
        });

    });
});

Route::middleware(['web'])->group( function () {
    Route::prefix('organization/parameters')->group(function () {
        Route::controller(OrganizationParameter::class)->group(function () {
            Route::post('/{entity}', 'saveParameter');
    Route::put('/{entity}', 'saveParameter');
    Route::put('{entity}/merge', 'merge');
    Route::get('/{entity}', 'getParameters');
    Route::delete('/{entity}/{id}/{action}', 'deleteParameter')
        -> where(
            [
                'id' => '[0-9]+',
                'action' => 'actual|soft|enable'
            ]
        );
        
        
    
        });

    });
});

Route::middleware(['web'])->group( function () {
    Route::prefix('commonparam')->group(function () {
        Route::controller(CommonParameter::class)->group(function () {
            //model_name:model_name, as a parameter
    Route::get('getCommonParamFromModel', 'getCommonParamFromModel');
    Route::get('getCommonParamFromTable', 'getCommonParamFromTable');
    Route::get('deleteParameters', 'deleteParameters');


    Route::get('getelementcost','getelementcost');
    Route::get('getcostCategories','getcostCategories');
    Route::get('getcostSubCategories','getcostSubCategories');
    Route::get('getProductTypes','getProductTypes');
    Route::get('getOrgBankAccounts','getOrgBankAccounts');
    Route::get('getBankBranches','getBankBranches');
    
    Route::get('getDeviceTypes','getDeviceTypes');
    Route::get('getglaccounts','getglaccounts');
    Route::get('getAgeAnalysisDaysSpanParam','getAgeAnalysisDaysSpanParam');
    Route::get('saveCommonParameter','saveCommonParameter');
    Route::get('getUserGroupsdetails','getUserGroupsdetails');
    Route::get('getCountriesByStateRegions','getCountriesByStateRegions');

    
    //notification configurations
    Route::get('getDirectorateNotificationsConfig','getDirectorateNotificationsConfig');
    Route::get('getDepartmentalNotificationsConfig','getDepartmentalNotificationsConfig');
    Route::get('getVariationsRequestConfiguration','getVariationsRequestConfiguration');
	
	 Route::get('getVariationSupportingDataDetails', 'getVariationSupportingDataDetails');
    Route::get('getVariationConditionsDetails', 'getVariationConditionsDetails');
        
    
        });

    });
});

// Route::group(['middleware' => 'web', 'prefix' => 'parameters', 'namespace' => 'Modules\Parameters\Http\Controllers'], function()
// {
//     Route::post('/{entity}', 'CommonParameter@saveParameter');
//     Route::put('/{entity}', 'CommonParameter@saveParameter');
//     Route::put('{entity}/merge', 'CommonParameter@merge');
//     Route::get('/{entity}', 'CommonParameter@getParameters');
//     Route::delete('/{entity}/{id}/{action}', 'CommonParameter@deleteParameter')
//         -> where(
//             [
//                 'id' => '[0-9]+',
//                 'action' => 'actual|soft|enable'
//             ]
//         );
// });

// Route::group(['middleware' => 'web', 'prefix' => 'premiseregistration/parameters', 'namespace' => 'Modules\Parameters\Http\Controllers'], function()
// {
//     Route::post('/{entity}', 'PremiseRegistration@saveParameter');
//     Route::put('/{entity}', 'PremiseRegistration@saveParameter');
//     Route::put('{entity}/merge', 'PremiseRegistration@merge');
//     Route::get('/{entity}', 'PremiseRegistration@getParameters');
//     Route::delete('/{entity}/{id}/{action}', 'PremiseRegistration@deleteParameter')
//         -> where(
//             [
//                 'id' => '[0-9]+',
//                 'action' => 'actual|soft|enable'
//             ]
//         );
// });


// Route::group(['middleware' => 'web', 'prefix' => 'organization/parameters', 'namespace' => 'Modules\Parameters\Http\Controllers'], function()
// {
//     Route::post('/{entity}', 'OrganizationParameter@saveParameter');
//     Route::put('/{entity}', 'OrganizationParameter@saveParameter');
//     Route::put('{entity}/merge', 'OrganizationParameter@merge');
//     Route::get('/{entity}', 'OrganizationParameter@getParameters');
//     Route::delete('/{entity}/{id}/{action}', 'OrganizationParameter@deleteParameter')
//         -> where(
//             [
//                 'id' => '[0-9]+',
//                 'action' => 'actual|soft|enable'
//             ]
//         );
// });

// //Added by KIP
// Route::group(['middleware' => 'web', 'prefix' => 'commonparam', 'namespace' => 'Modules\Parameters\Http\Controllers'], function()
// {
//     //model_name:model_name, as a parameter
//     Route::get('getCommonParamFromModel', 'CommonParameter@getCommonParamFromModel');
//     Route::get('getCommonParamFromTable', 'CommonParameter@getCommonParamFromTable');
//     Route::get('deleteParameters', 'CommonParameter@deleteParameters');


//     Route::get('getelementcost','CommonParameter@getelementcost');
//     Route::get('getcostCategories','CommonParameter@getcostCategories');
//     Route::get('getcostSubCategories','CommonParameter@getcostSubCategories');
//     Route::get('getProductTypes','CommonParameter@getProductTypes');
//     Route::get('getOrgBankAccounts','CommonParameter@getOrgBankAccounts');
//     Route::get('getBankBranches','CommonParameter@getBankBranches');
    
//     Route::get('getDeviceTypes','CommonParameter@getDeviceTypes');
//     Route::get('getglaccounts','CommonParameter@getglaccounts');
//     Route::get('getAgeAnalysisDaysSpanParam','CommonParameter@getAgeAnalysisDaysSpanParam');
//     Route::get('saveCommonParameter','CommonParameter@saveCommonParameter');
//     Route::get('getUserGroupsdetails','CommonParameter@getUserGroupsdetails');
//     Route::get('getCountriesByStateRegions','CommonParameter@getCountriesByStateRegions');

    
//     //notification configurations
//     Route::get('getDirectorateNotificationsConfig','CommonParameter@getDirectorateNotificationsConfig');
//     Route::get('getDepartmentalNotificationsConfig','CommonParameter@getDepartmentalNotificationsConfig');
//     Route::get('getVariationsRequestConfiguration','CommonParameter@getVariationsRequestConfiguration');
	
// 	 Route::get('getVariationSupportingDataDetails', 'CommonParameter@getVariationSupportingDataDetails');
//     Route::get('getVariationConditionsDetails', 'CommonParameter@getVariationConditionsDetails');
   
   
// });