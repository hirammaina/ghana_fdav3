<?php

use App\Modules\ControlDocumentsMng\Http\Controllers\ControlDocumentsMngController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('controldocumentsmng')->group(function () {
        Route::controller(ControlDocumentsMngController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/saveNewControlDocumentDetails', 'saveNewControlDocumentDetails');
            Route::post('/saveReviewedControlDocumentDetails', 'saveReviewedControlDocumentDetails');
        
            
            Route::get('/getControlDocumentApplications', 'getControlDocumentApplications');
            
            Route::get('/prepareNewControlDocumentRequest', 'prepareNewControlDocumentRequest');
        
            Route::get('/validateDocumentUploadExists', 'validateDocumentUploadExists');
            Route::get('/getControlDocumentsreglist', 'getControlDocumentsreglist');
            Route::get('/getControlDocumentsAccessDetails', 'getControlDocumentsAccessDetails');
            Route::post('/saveControlDocumentsAccessDetails', 'saveControlDocumentsAccessDetails');
            Route::post('/saveApprovalControlDocumentDetails', 'saveApprovalControlDocumentDetails');
            Route::get('/getApprovedControlDocumentRelease', 'getApprovedControlDocumentRelease');
            Route::get('/getPreviousControlDocumentVersions', 'getPreviousControlDocumentVersions');
            Route::get('/getDocDirectiveBasedUsersList', 'getDocDirectiveBasedUsersList');
            Route::post('/addSelectedUserstoUnit', 'addSelectedUserstoUnit');
            Route::get('/getDocDirectiveUsers', 'getDocDirectiveUsers');
            Route::post('/removeSelectedUsersFromUnits', 'removeSelectedUsersFromUnits');
            Route::post('/saveDocumentDistributionUserList', 'saveDocumentDistributionUserList');
            Route::get('/getDocumentDistributionUsersList', 'getDocumentDistributionUsersList');
            Route::get('/revokeDistributionUserList', 'revokeDistributionUserList');
            Route::get('/getAccessControlDetails', 'getAccessControlDetails');
        
        
    
        });

    });
});

// Route::group(['middleware' => 'auth:api','prefix' => 'controldocumentsmng', 'namespace' => 'App\\Modules\ControlDocumentsMng\Http\Controllers'], function()
// {
//     Route::get('/', 'ControlDocumentsMngController@index');
//     Route::post('/saveNewControlDocumentDetails', 'ControlDocumentsMngController@saveNewControlDocumentDetails');
//     Route::post('/saveReviewedControlDocumentDetails', 'ControlDocumentsMngController@saveReviewedControlDocumentDetails');

    
//     Route::get('/getControlDocumentApplications', 'ControlDocumentsMngController@getControlDocumentApplications');
    
//     Route::get('/prepareNewControlDocumentRequest', 'ControlDocumentsMngController@prepareNewControlDocumentRequest');

//     Route::get('/validateDocumentUploadExists', 'ControlDocumentsMngController@validateDocumentUploadExists');
//     Route::get('/getControlDocumentsreglist', 'ControlDocumentsMngController@getControlDocumentsreglist');
//     Route::get('/getControlDocumentsAccessDetails', 'ControlDocumentsMngController@getControlDocumentsAccessDetails');
//     Route::post('/saveControlDocumentsAccessDetails', 'ControlDocumentsMngController@saveControlDocumentsAccessDetails');
//     Route::post('/saveApprovalControlDocumentDetails', 'ControlDocumentsMngController@saveApprovalControlDocumentDetails');
//     Route::get('/getApprovedControlDocumentRelease', 'ControlDocumentsMngController@getApprovedControlDocumentRelease');
//     Route::get('/getPreviousControlDocumentVersions', 'ControlDocumentsMngController@getPreviousControlDocumentVersions');
//     Route::get('/getDocDirectiveBasedUsersList', 'ControlDocumentsMngController@getDocDirectiveBasedUsersList');
//     Route::post('/addSelectedUserstoUnit', 'ControlDocumentsMngController@addSelectedUserstoUnit');
//     Route::get('/getDocDirectiveUsers', 'ControlDocumentsMngController@getDocDirectiveUsers');
//     Route::post('/removeSelectedUsersFromUnits', 'ControlDocumentsMngController@removeSelectedUsersFromUnits');
//     Route::post('/saveDocumentDistributionUserList', 'ControlDocumentsMngController@saveDocumentDistributionUserList');
//     Route::get('/getDocumentDistributionUsersList', 'ControlDocumentsMngController@getDocumentDistributionUsersList');
//     Route::get('/revokeDistributionUserList', 'ControlDocumentsMngController@revokeDistributionUserList');
//     Route::get('/getAccessControlDetails', 'ControlDocumentsMngController@getAccessControlDetails');
    
// });
