<?php

use App\Modules\UserManagement\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('usermanagement')->group(function () {
        Route::controller(UserManagementController::class)->group(function () {
            Route::get('/', 'index');
    Route::get('getUserParamFromModel', 'getUserParamFromModel');
    Route::post('saveUserCommonData', 'saveUserCommonData');
    Route::post('deleteUserRecord', 'deleteUserRecord');
    Route::post('softDeleteUserRecord', 'softDeleteUserRecord');
    Route::post('undoUserSoftDeletes', 'undoUserSoftDeletes');
    Route::get('getActiveSystemUsers', 'getActiveSystemUsers');
    Route::get('getOpenUserRoles', 'getOpenUserRoles');
    Route::get('getAssignedUserRoles', 'getAssignedUserRoles');
    Route::get('getOpenUserGroups', 'getOpenUserGroups');
    Route::get('getAssignedUserGroups', 'getAssignedUserGroups');
    Route::post('saveUserImage', 'saveUserImage');
    Route::post('saveUserInformation', 'saveUserInformation');
    Route::post('resetUserPassword', 'resetUserPassword');
    Route::post('updateUserPassword', 'updateUserPassword');
    Route::post('blockSystemUser', 'blockSystemUser');
    Route::get('getBlockedSystemUsers', 'getBlockedSystemUsers');
    Route::post('unblockSystemUser', 'unblockSystemUser');
    Route::get('getUnBlockedSystemUsers', 'getUnBlockedSystemUsers');
    Route::get('getUserSignatures', 'getUserSignatures');
    Route::get('getUserDigitalSignatures', 'getUserDigitalSignatures');
    Route::post('uploadUserSignature', 'uploadUserSignature');
    //api users
    Route::get('getApiSystemUsers', 'getApiSystemUsers');
    Route::post('saveApiUserInformation', 'saveApiUserInformation');
    Route::get('activateSystemApiUser', 'activateSystemApiUser');
    Route::post('blockSystemApiUser', 'blockSystemApiUser');

    //external Users
    Route::get('getExternalSystemUsers', 'getExternalSystemUsers');
    Route::post('saveExternalUserInformation', 'saveExternalUserInformation');
    Route::get('activateSystemExternalUser', 'activateSystemExternalUser');
    Route::post('blockSystemExternalUser', 'blockSystemExternalUser');

    Route::post('saveExternalUsersDetails', 'saveExternalUsersDetails');


    Route::get('getResubmissionApplications', 'getResubmissionApplications');
    Route::get('getUserList', 'getUserList');
    //Route::post('applicationResubmissionVisibleRequest', 'applicationResubmissionVisibleRequest');
     Route::get('applicationResubmissionVisibleRequest', 'applicationResubmissionVisibleRequest');
    //Route::post('applicationResubmissionHideRequest', 'applicationResubmissionHideRequest');
     Route::get('applicationResubmissionHideRequest', 'applicationResubmissionHideRequest');
    Route::get('getTaskReassignmentApplications', 'getTaskReassignmentApplications');
    Route::post('doReassignApplicationTask', 'doReassignApplicationTask');
//online resubmision
    Route::get('getOnlineResubmissionApplications', 'getOnlineResubmissionApplications');
    Route::get('onlineResubmissionRequest', 'onlineResubmissionRequest');

    Route::get('getActingUsersPositionDetails', 'getActingUsersPositionDetails');

//integration users
    Route::get('getRegionalIntegrationUsers', 'getRegionalIntegrationUsers');
    Route::post('saveRegionalIntegrationUsers', 'saveRegionalIntegrationUsers');
    Route::get('getPortalAppSubmissions', 'getPortalAppSubmissions');
    Route::get('showHideonlineResubmissionRequest', 'showHideonlineResubmissionRequest');
    Route::get('getUserPasswordResetLogs', 'getUserPasswordResetLogs');
    Route::get('getUserDetailsUpdateLogs', 'getUserDetailsUpdateLogs');

    
  Route::post('functInitiateDigitalSignature', 'functInitiateDigitalSignature');
Route::post('saveDigitalSignatureConfSign', 'saveDigitalSignatureConfSign');
Route::post('updateDigitalSignatureConfSign', 'updateDigitalSignatureConfSign');





        
    
        });

    });
});

// Route::group(['middleware' => 'web', 'prefix' => 'usermanagement', 'namespace' => 'App\\Modules\UserManagement\Http\Controllers'], function () {
//     Route::get('/', 'UserManagementController@index');
//     Route::get('getUserParamFromModel', 'UserManagementController@getUserParamFromModel');
//     Route::post('saveUserCommonData', 'UserManagementController@saveUserCommonData');
//     Route::post('deleteUserRecord', 'UserManagementController@deleteUserRecord');
//     Route::post('softDeleteUserRecord', 'UserManagementController@softDeleteUserRecord');
//     Route::post('undoUserSoftDeletes', 'UserManagementController@undoUserSoftDeletes');
//     Route::get('getActiveSystemUsers', 'UserManagementController@getActiveSystemUsers');
//     Route::get('getOpenUserRoles', 'UserManagementController@getOpenUserRoles');
//     Route::get('getAssignedUserRoles', 'UserManagementController@getAssignedUserRoles');
//     Route::get('getOpenUserGroups', 'UserManagementController@getOpenUserGroups');
//     Route::get('getAssignedUserGroups', 'UserManagementController@getAssignedUserGroups');
//     Route::post('saveUserImage', 'UserManagementController@saveUserImage');
//     Route::post('saveUserInformation', 'UserManagementController@saveUserInformation');
//     Route::post('resetUserPassword', 'UserManagementController@resetUserPassword');
//     Route::post('updateUserPassword', 'UserManagementController@updateUserPassword');
//     Route::post('blockSystemUser', 'UserManagementController@blockSystemUser');
//     Route::get('getBlockedSystemUsers', 'UserManagementController@getBlockedSystemUsers');
//     Route::post('unblockSystemUser', 'UserManagementController@unblockSystemUser');
//     Route::get('getUnBlockedSystemUsers', 'UserManagementController@getUnBlockedSystemUsers');
//     Route::get('getUserSignatures', 'UserManagementController@getUserSignatures');
//     Route::get('getUserDigitalSignatures', 'UserManagementController@getUserDigitalSignatures');
//     Route::post('uploadUserSignature', 'UserManagementController@uploadUserSignature');
//     //api users
//     Route::get('getApiSystemUsers', 'UserManagementController@getApiSystemUsers');
//     Route::post('saveApiUserInformation', 'UserManagementController@saveApiUserInformation');
//     Route::get('activateSystemApiUser', 'UserManagementController@activateSystemApiUser');
//     Route::post('blockSystemApiUser', 'UserManagementController@blockSystemApiUser');

//     //external Users
//     Route::get('getExternalSystemUsers', 'UserManagementController@getExternalSystemUsers');
//     Route::post('saveExternalUserInformation', 'UserManagementController@saveExternalUserInformation');
//     Route::get('activateSystemExternalUser', 'UserManagementController@activateSystemExternalUser');
//     Route::post('blockSystemExternalUser', 'UserManagementController@blockSystemExternalUser');

//     Route::post('saveExternalUsersDetails', 'UserManagementController@saveExternalUsersDetails');


//     Route::get('getResubmissionApplications', 'UserManagementController@getResubmissionApplications');
//     Route::get('getUserList', 'UserManagementController@getUserList');
//     //Route::post('applicationResubmissionVisibleRequest', 'UserManagementController@applicationResubmissionVisibleRequest');
//      Route::get('applicationResubmissionVisibleRequest', 'UserManagementController@applicationResubmissionVisibleRequest');
//     //Route::post('applicationResubmissionHideRequest', 'UserManagementController@applicationResubmissionHideRequest');
//      Route::get('applicationResubmissionHideRequest', 'UserManagementController@applicationResubmissionHideRequest');
//     Route::get('getTaskReassignmentApplications', 'UserManagementController@getTaskReassignmentApplications');
//     Route::post('doReassignApplicationTask', 'UserManagementController@doReassignApplicationTask');
// //online resubmision
//     Route::get('getOnlineResubmissionApplications', 'UserManagementController@getOnlineResubmissionApplications');
//     Route::get('onlineResubmissionRequest', 'UserManagementController@onlineResubmissionRequest');

//     Route::get('getActingUsersPositionDetails', 'UserManagementController@getActingUsersPositionDetails');

// //integration users
//     Route::get('getRegionalIntegrationUsers', 'UserManagementController@getRegionalIntegrationUsers');
//     Route::post('saveRegionalIntegrationUsers', 'UserManagementController@saveRegionalIntegrationUsers');
//     Route::get('getPortalAppSubmissions', 'UserManagementController@getPortalAppSubmissions');
//     Route::get('showHideonlineResubmissionRequest', 'UserManagementController@showHideonlineResubmissionRequest');
//     Route::get('getUserPasswordResetLogs', 'UserManagementController@getUserPasswordResetLogs');
//     Route::get('getUserDetailsUpdateLogs', 'UserManagementController@getUserDetailsUpdateLogs');

    
//   Route::post('functInitiateDigitalSignature', 'UserManagementController@functInitiateDigitalSignature');
// Route::post('saveDigitalSignatureConfSign', 'UserManagementController@saveDigitalSignatureConfSign');
// Route::post('updateDigitalSignatureConfSign', 'UserManagementController@updateDigitalSignatureConfSign');





// });
