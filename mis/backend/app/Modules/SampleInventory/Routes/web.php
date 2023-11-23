<?php

use App\Modules\SampleInventory\Http\Controllers\SampleInventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('sampleinventory')->group(function () {
        Route::controller(SampleInventoryController::class)->group(function () {
            Route::get('getReceivedSampleInventory', 'getReceivedSampleInventory');
            Route::get('getSampledProductList', 'getSampledProductList');
            Route::get('getIssuedSampleInventory', 'getIssuedSampleInventory');
            Route::get('getInventoryDashboard', 'getInventoryDashboard');
            Route::post('saveInventoryItemData', 'saveInventoryItemData');
            Route::get('getConfigFormDetails', 'getConfigFormDetails');
            Route::post('doSubmitInventoryIssueFormDetails', 'doSubmitInventoryIssueFormDetails');
            Route::get('getStockOutflowInventory', 'getStockOutflowInventory');
            Route::get('getStockInflowInventory', 'getStockInflowInventory');
            Route::get('getrequestedItems', 'getrequestedItems');
            Route::post('doSubmitRequestedInventoryIssueFormDetails', 'doSubmitRequestedInventoryIssueFormDetails');
            Route::get('getDisposalApprovalRequests', 'getDisposalApprovalRequests');
            Route::post('saveDisposalRequest', 'saveDisposalRequest');
            Route::get('getDisposalItems', 'getDisposalItems');
            Route::get('getNewDisposalRequests', 'getNewDisposalRequests');
            Route::get('getDisposalApprovalRequestsItems', 'getDisposalApprovalRequestsItems');
            Route::post('ApproveItemDisposalRequest', 'ApproveItemDisposalRequest');
            Route::get('getDisposalApprovedRequests', 'getDisposalApprovedRequests');
            Route::get('getDisposalRequestDetails', 'getDisposalRequestDetails');
            Route::post('removeDisposalItemEntry', 'removeDisposalItemEntry');
            
        
        
    
        });

    });
});



// Route::group(['middleware' => 'web', 'prefix' => 'sampleinventory', 'namespace' => 'App\\Modules\SampleInventory\Http\Controllers'], function()
// {
//     Route::get('getReceivedSampleInventory', 'SampleInventoryController@getReceivedSampleInventory');
//     Route::get('getSampledProductList', 'SampleInventoryController@getSampledProductList');
//     Route::get('getIssuedSampleInventory', 'SampleInventoryController@getIssuedSampleInventory');
//     Route::get('getInventoryDashboard', 'SampleInventoryController@getInventoryDashboard');
//     Route::post('saveInventoryItemData', 'SampleInventoryController@saveInventoryItemData');
//     Route::get('getConfigFormDetails', 'SampleInventoryController@getConfigFormDetails');
//     Route::post('doSubmitInventoryIssueFormDetails', 'SampleInventoryController@doSubmitInventoryIssueFormDetails');
//     Route::get('getStockOutflowInventory', 'SampleInventoryController@getStockOutflowInventory');
//     Route::get('getStockInflowInventory', 'SampleInventoryController@getStockInflowInventory');
//     Route::get('getrequestedItems', 'SampleInventoryController@getrequestedItems');
//     Route::post('doSubmitRequestedInventoryIssueFormDetails', 'SampleInventoryController@doSubmitRequestedInventoryIssueFormDetails');
//     Route::get('getDisposalApprovalRequests', 'SampleInventoryController@getDisposalApprovalRequests');
//     Route::post('saveDisposalRequest', 'SampleInventoryController@saveDisposalRequest');
//     Route::get('getDisposalItems', 'SampleInventoryController@getDisposalItems');
//     Route::get('getNewDisposalRequests', 'SampleInventoryController@getNewDisposalRequests');
//     Route::get('getDisposalApprovalRequestsItems', 'SampleInventoryController@getDisposalApprovalRequestsItems');
//     Route::post('ApproveItemDisposalRequest', 'SampleInventoryController@ApproveItemDisposalRequest');
//     Route::get('getDisposalApprovedRequests', 'SampleInventoryController@getDisposalApprovedRequests');
//     Route::get('getDisposalRequestDetails', 'SampleInventoryController@getDisposalRequestDetails');
//     Route::post('removeDisposalItemEntry', 'SampleInventoryController@removeDisposalItemEntry');
    


//     //reports
//     Route::get('getInventoryStockReportgrid', 'SampleInventoryController@getInventoryStockReport');
//     Route::get('getInventoryStockReportchart', 'SampleInventoryController@getInventoryStockReport');

    



    

// });
