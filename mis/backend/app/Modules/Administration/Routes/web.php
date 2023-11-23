<?php

// Route::group(['middleware' => 'web', 'prefix' => 'administration', 'namespace' => 'App\\Modules\Administration\Http\Controllers'], function()
// {

use App\Modules\Administration\Http\Controllers\AdministrationController;
use Illuminate\Support\Facades\Route;

    Route::middleware(['web'])->group( function () {
    Route::prefix('administration')->group(function () {
        Route::controller(AdministrationController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('getSystemNavigationMenuItems', 'getSystemNavigationMenuItems');
            Route::get('getSystemMenus', 'getSystemMenus');
            Route::get('getParentMenus', 'getParentMenus');
            Route::get('getChildMenus', 'getChildMenus');
            Route::post('saveMenuItem', 'saveMenuItem');
            Route::post('deleteAdminRecord', 'deleteAdminRecord');
            Route::post('softDeleteAdminRecord', 'softDeleteAdminRecord');
            Route::post('undoAdminSoftDeletes', 'undoAdminSoftDeletes');
            //Route::get('getAdminParamFromModel', 'getAdminParamFromModel');
            Route::post('saveAdminCommonData', 'saveAdminCommonData');
            Route::get('getSystemRoles', 'getSystemRoles');
            Route::post('updateSystemNavigationAccessRoles', 'updateSystemNavigationAccessRoles');
            Route::post('updateSystemPermissionAccessRoles', 'updateSystemPermissionAccessRoles');
            Route::get('getNonMenuItems','getNonMenuItems');
            Route::get('getNonMenuItemsSystemRoles','getNonMenuItemsSystemRoles');
            Route::get('getMenuProcessesRoles','getMenuProcessesRoles');
            Route::post('removeSelectedUsersFromGroup','removeSelectedUsersFromGroup');
            Route::post('addSelectedUsersFromGroup','addSelectedUsersFromGroup');
            Route::get('getSystemUserGroups','getSystemUserGroups');
            Route::get('getFormFields','getFormFields');
            Route::post('testApi','test');
            Route::post('saveExternalUsersDetails','saveExternalUsersDetails');
            Route::post('mapGroupToStage','mapGroupToStage');
            Route::get('getApplicationAssignmentProcessList','getApplicationAssignmentProcessList');
            Route::post('mapApplicationAssignmentSetup','mapApplicationAssignmentSetup');
            Route::get('getTablescolumns','getTablescolumns');
            Route::post('saveParameterConfig','saveParameterConfig');
            Route::get('getParameterConfig','getParameterConfig');
            Route::get('checkParamMenuDefination','checkParamMenuDefination');
            Route::get('getParameterGridColumnsConfig','getParameterGridColumnsConfig');
            Route::get('getParameterFormColumnsConfig','getParameterFormColumnsConfig');
            Route::get('getParameterGridConfig','getParameterGridConfig');
        });
       
    // Route::get('/', 'AdministrationController@index');
    // Route::get('getSystemNavigationMenuItems', 'AdministrationController@getSystemNavigationMenuItems');
    // Route::get('getSystemMenus', 'AdministrationController@getSystemMenus');
    // Route::get('getParentMenus', 'AdministrationController@getParentMenus');
    // Route::get('getChildMenus', 'AdministrationController@getChildMenus');
    // Route::post('saveMenuItem', 'AdministrationController@saveMenuItem');
    // Route::post('deleteAdminRecord', 'AdministrationController@deleteAdminRecord');
    // Route::post('softDeleteAdminRecord', 'AdministrationController@softDeleteAdminRecord');
    // Route::post('undoAdminSoftDeletes', 'AdministrationController@undoAdminSoftDeletes');
    // //Route::get('getAdminParamFromModel', 'AdministrationController@getAdminParamFromModel');
    // Route::post('saveAdminCommonData', 'AdministrationController@saveAdminCommonData');
    // Route::get('getSystemRoles', 'AdministrationController@getSystemRoles');
    // Route::post('updateSystemNavigationAccessRoles', 'AdministrationController@updateSystemNavigationAccessRoles');
    // Route::post('updateSystemPermissionAccessRoles', 'AdministrationController@updateSystemPermissionAccessRoles');
    // Route::get('getNonMenuItems','AdministrationController@getNonMenuItems');
    // Route::get('getNonMenuItemsSystemRoles','AdministrationController@getNonMenuItemsSystemRoles');
    // Route::get('getMenuProcessesRoles','AdministrationController@getMenuProcessesRoles');
    // Route::post('removeSelectedUsersFromGroup','AdministrationController@removeSelectedUsersFromGroup');
    // Route::post('addSelectedUsersFromGroup','AdministrationController@addSelectedUsersFromGroup');
    // Route::get('getSystemUserGroups','AdministrationController@getSystemUserGroups');
    // Route::get('getFormFields','AdministrationController@getFormFields');
    // Route::post('testApi','AdministrationController@test');
    // Route::post('saveExternalUsersDetails','AdministrationController@saveExternalUsersDetails');

    
    // Route::post('mapGroupToStage','AdministrationController@mapGroupToStage');
    // Route::get('getApplicationAssignmentProcessList','AdministrationController@getApplicationAssignmentProcessList');
    // Route::post('mapApplicationAssignmentSetup','AdministrationController@mapApplicationAssignmentSetup');
    // Route::get('getTablescolumns','AdministrationController@getTablescolumns');
    // Route::post('saveParameterConfig','AdministrationController@saveParameterConfig');
    // Route::get('getParameterConfig','AdministrationController@getParameterConfig');
    // Route::get('checkParamMenuDefination','AdministrationController@checkParamMenuDefination');
    // Route::get('getParameterGridColumnsConfig','AdministrationController@getParameterGridColumnsConfig');
    // Route::get('getParameterFormColumnsConfig','AdministrationController@getParameterFormColumnsConfig');
    // Route::get('getParameterGridConfig','AdministrationController@getParameterGridConfig');
    

    


});

});
// Route::group(['middleware' => 'auth:api', 'prefix' => 'administration', 'namespace' => 'App\\Modules\Administration\Http\Controllers'], function () {
//     Route::get('getAdminParamFromModel', 'AdministrationController@getAdminParamFromModel');
// });

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('administration')->group(function () {
        Route::controller(AdministrationController::class)->group(function () {
            Route::get('getAdminParamFromModel', 'getAdminParamFromModel');
        });

    });
});