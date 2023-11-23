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

use App\Http\Controllers\Auth;
use App\Http\Controllers\Init;
use App\Http\Controllers\UploadsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Route::get('/', 'Init@index');
Route::get('/', [Init::class, 'index']);
Route::get('/hey', function(){
    dd("hey");
});

//Route::group(['middleware' => ['web']], function () {
    Route::middleware(['web'])->group( function () {
    Route::controller(Auth::class)->group(function () {
        Route::post('login', 'handleLogin');
        Route::post('logout', 'logout');
        Route::post('forgotPassword', 'forgotPasswordHandler');
        Route::get('resetPassword', 'passwordResetLoader');
        Route::post('saveNewPassword', 'passwordResetHandler');
        Route::post('updatePassword', 'updateUserPassword');
        Route::get('authenticateUserSession', 'authenticateUserSession');
        Route::post('reValidateUser', 'reValidateUser');
        Route::get('createAdminPwd/{username}/{uuid}/{pwd}', 'createAdminPwd');


    });
    Route::controller(CommonController::class)->group(function () {

        Route::get('getCommonParamFromModel', 'getCommonParamFromModel');
        Route::post('saveApplicationApprovalDetails', 'saveApplicationApprovalDetails');
        Route::post('saveAppTcRecommendationDetails', 'saveAppTcRecommendationDetails');
        
        Route::post('saveApplicationPaymentDetails', 'saveApplicationPaymentDetails');
        Route::post('submitQueriedOnlineApplication', 'submitQueriedOnlineApplication');
        Route::post('submitRejectedOnlineApplication', 'submitRejectedOnlineApplication');
        Route::post('onlineApplicationManagerRejectionAction', 'onlineApplicationManagerRejectionAction');
        Route::get('getApplicationApprovalDetails', 'getApplicationApprovalDetails');
        Route::post('saveApplicationInvoicingDetails', 'saveApplicationInvoicingDetails');
        Route::post('removeInvoiceCostElement', 'removeInvoiceCostElement');
        Route::get('getApplicationApplicantDetails', 'getApplicationApplicantDetails');
        Route::get('getApplicationComments', 'getApplicationComments');
        Route::get('checkInvoicePaymentsLimit', 'checkInvoicePaymentsLimit');
        Route::post('submitStructuredQueriedOnlineApplication', 'submitStructuredQueriedOnlineApplication');
        Route::get('prepareApplicationTCMeetingSchedulingStage', 'prepareApplicationTCMeetingSchedulingStage');
        Route::get('getApplicationunstructuredqueries', 'getApplicationunstructuredqueries');
    
        Route::post('saveUnstructuredApplicationQuery', 'saveUnstructuredApplicationQuery');
        Route::get('getImporPermitApplicationApprovalDetails', 'getImporPermitApplicationApprovalDetails');
        Route::post('funcsubmitRejectedApplication', 'funcsubmitRejectedApplication');
        //delete
       
        Route::get('getInspectionApplicationApprovalDetails', 'getInspectionApplicationApprovalDetails');
        Route::get('getPermitReleaseRecommendationDetails', 'getPermitReleaseRecommendationDetails');

    });
    Route::controller(UploadsController::class)->group(function () {
        Route::get('upload', 'upload');
        Route::get('uploadFile', 'resumableUpload');
        Route::post('uploadFile', 'resumableUpload');
    

    });
    // //Authentication
    // Route::post('login', 'Auth@handleLogin');
    // Route::post('logout', 'Auth@logout');
    // Route::post('forgotPassword', 'Auth@forgotPasswordHandler');
    // Route::get('resetPassword', 'Auth@passwordResetLoader');
    // Route::post('saveNewPassword', 'Auth@passwordResetHandler');
    // Route::post('updatePassword', 'Auth@updateUserPassword');
    // Route::get('authenticateUserSession', 'Auth@authenticateUserSession');
    // Route::post('reValidateUser', 'Auth@reValidateUser');
    // Route::get('createAdminPwd/{username}/{uuid}/{pwd}', 'Auth@createAdminPwd');
    // //Common controller
    // Route::get('getCommonParamFromModel', 'CommonController@getCommonParamFromModel');
    // Route::post('saveApplicationApprovalDetails', 'CommonController@saveApplicationApprovalDetails');
    // Route::post('saveAppTcRecommendationDetails', 'CommonController@saveAppTcRecommendationDetails');
    
    // Route::post('saveApplicationPaymentDetails', 'CommonController@saveApplicationPaymentDetails');
    // Route::post('submitQueriedOnlineApplication', 'CommonController@submitQueriedOnlineApplication');
    // Route::post('submitRejectedOnlineApplication', 'CommonController@submitRejectedOnlineApplication');
    // Route::post('onlineApplicationManagerRejectionAction', 'CommonController@onlineApplicationManagerRejectionAction');
    // Route::get('getApplicationApprovalDetails', 'CommonController@getApplicationApprovalDetails');
    // Route::post('saveApplicationInvoicingDetails', 'CommonController@saveApplicationInvoicingDetails');
    // Route::post('removeInvoiceCostElement', 'CommonController@removeInvoiceCostElement');
    // Route::get('getApplicationApplicantDetails', 'CommonController@getApplicationApplicantDetails');
    // Route::get('getApplicationComments', 'CommonController@getApplicationComments');
    // Route::get('checkInvoicePaymentsLimit', 'CommonController@checkInvoicePaymentsLimit');
    // Route::post('submitStructuredQueriedOnlineApplication', 'CommonController@submitStructuredQueriedOnlineApplication');
    // Route::get('prepareApplicationTCMeetingSchedulingStage', 'CommonController@prepareApplicationTCMeetingSchedulingStage');
    // Route::get('getApplicationunstructuredqueries', 'CommonController@getApplicationunstructuredqueries');

    // Route::post('saveUnstructuredApplicationQuery', 'CommonController@saveUnstructuredApplicationQuery');
    // Route::get('getImporPermitApplicationApprovalDetails', 'CommonController@getImporPermitApplicationApprovalDetails');
    // Route::post('funcsubmitRejectedApplication', 'CommonController@funcsubmitRejectedApplication');
    // //delete
    // Route::get('upload', 'UploadsController@upload');
    // Route::get('uploadFile', 'UploadsController@resumableUpload');
    // Route::post('uploadFile', 'UploadsController@resumableUpload');

    // Route::get('getInspectionApplicationApprovalDetails', 'CommonController@getInspectionApplicationApprovalDetails');
    // Route::get('getPermitReleaseRecommendationDetails', 'CommonController@getPermitReleaseRecommendationDetails');
    
});
Route::controller(Auth::class)->group(function () {
Route::post('authenticateApiUser', 'authenticateApiUser');
Route::post('authenticateMisMobileUser', 'authenticateMisMobileUser');
Route::get('logoutMisMobileUser', 'logoutMisMobileUser');

Route::post('authApiUsers', 'Auth@authApiUsers');
});
// Route::post('authenticateApiUser', 'Auth@authenticateApiUser');
// Route::post('authenticateMisMobileUser', 'Auth@authenticateMisMobileUser');
// Route::get('logoutMisMobileUser', 'Auth@logoutMisMobileUser');

// Route::post('authApiUsers', 'Auth@authApiUsers');