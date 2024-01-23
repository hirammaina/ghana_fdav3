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

Route::prefix('importexportapp')->group(function () {
    Route::get('/', 'ImportExportAppController@index');
});

// Route::group(['middleware' => 'web', 'prefix' => 'importexportapp', 'namespace' => 'Modules\ImportExportApp\Http\Controllers'], function()
// {
//     Route::get('/', 'ImportExportAppController@index');

//, 'namespace' => 'Modules\ImportExportApp\Http\Controllers'
// });

Route::group(['middleware' => 'web', 'prefix' => 'importexportapp'], function () {
    Route::get('/', 'ImportExportAppController@index');
});
Route::group(['middleware1' => 'auth:api', 'prefix' => 'importexportapp'], function () {
    Route::post('saveImportExportApplication', 'ImportExportAppController@saveImportExportApplication');

    Route::post('onAddUniformApplicantDataset', 'ImportExportAppController@onAddUniformApplicantDataset');

    Route::post('savePermitProductdetails', 'ImportExportAppController@savePermitProductdetails');
    Route::post('onDeletePermitdetails', 'ImportExportAppController@onDeletePermitdetails');
    Route::post('onPermitApplicationSubmit', 'ImportExportAppController@onPermitApplicationSubmit');
    Route::post('onAddNewProductinformation', 'ImportExportAppController@onAddNewProductinformation');
    Route::post('saveControlledDrugsImptPermitApplication', 'ImportExportAppController@saveControlledDrugsImptPermitApplication');

    Route::post('saveControlDrugsLicensedetails', 'ImportExportAppController@saveControlDrugsLicensedetails');

    Route::get('getApplicationCounterDetails', 'ImportExportAppController@getApplicationCounterDetails');

    Route::get('getControlledImportPermitsApplicationLoading', 'ImportExportAppController@getControlledImportPermitsApplicationLoading');

    Route::get('getApprovedControlledLicensesApplicationLoading', 'ImportExportAppController@getApprovedControlledLicensesApplicationLoading');


    Route::get('getControlledDrugsLicensesProdDetails', 'ImportExportAppController@getControlledDrugsLicensesProdDetails');

    Route::get('getImportExpPermitsApplicationLoading', 'ImportExportAppController@getImportExpPermitsApplicationLoading');
    Route::get('getPermitsApplicationDetails', 'ImportExportAppController@getPermitsApplicationDetails');

    Route::get('getSenderreceiversDetails', 'ImportExportAppController@getSenderreceiversDetails');
    Route::get('getRegisteredNonRegisteredProducts', 'ImportExportAppController@getRegisteredNonRegisteredProducts');
    Route::get('getPermitProductsDetails', 'ImportExportAppController@getPermitProductsDetails');
    Route::get('getregulatedProductsPermitData', 'ImportExportAppController@getregulatedProductsPermitData');
    Route::get('getAuthorisedProductsApplications', 'ImportExportAppController@getAuthorisedProductsApplications');

    Route::get('onfuncValidatePermitDetails', 'ImportExportAppController@onfuncValidatePermitDetails');

    Route::get('getDisposalApplicationsLoading', 'ImportExportAppController@getDisposalApplicationsLoading');
    Route::post('saveDisposalApplication', 'ImportExportAppController@saveDisposalApplication');
    Route::post('saveDisposalPermitProductdetails', 'ImportExportAppController@saveDisposalPermitProductdetails');

    Route::get('getAllRegisteredNonRedProducts', 'ImportExportAppController@getAllRegisteredNonRedProducts');
    Route::get('getDisposalPermitProductsDetails', 'ImportExportAppController@getDisposalPermitProductsDetails');

    // Route::get('getOnlineDisposalpermitsproductsDetails', 'ImportExportAppController@getOnlineDisposalpermitsproductsDetails');
    Route::get('initiateRequestforPermitAmmendment', 'ImportExportAppController@initiateRequestforPermitAmmendment');
    Route::get('funcInitiateLicenseApplication', 'ImportExportAppController@funcInitiateLicenseApplication');
    Route::get('funcInitiateInspectionBooking', 'ImportExportAppController@funcInitiateInspectionBooking');


    Route::post('saveOneYearAuthorisationApplication', 'ImportExportAppController@saveOneYearAuthorisationApplication');

    Route::get('getPersonalOneYearImportAuthorisationapplication', 'ImportExportAppController@getPersonalOneYearImportAuthorisationapplication');


    Route::post('savepersonalAuthorisedProductsData', 'ImportExportAppController@savepersonalAuthorisedProductsData');
    Route::get('getpersonalAuthorisedProductsData', 'ImportExportAppController@getpersonalAuthorisedProductsData');


    Route::get('getPersonalisedApprovedAuthorisedProducts', 'ImportExportAppController@getPersonalisedApprovedAuthorisedProducts');
    Route::get('getPersonalImportPermitsApplicationLoading', 'ImportExportAppController@getImportExpPermitsApplicationLoading');


    Route::get('getApprrovedVisaProducts', 'ImportExportAppController@getApprrovedVisaProducts');
    Route::get('getApprrovedLicensesProducts', 'ImportExportAppController@getApprrovedLicensesProducts');
    Route::get('getPermitUploadedProductsDetails', 'ImportExportAppController@getPermitUploadedProductsDetails');
    Route::post('onDeletePermitUploadedProductsDetails', 'ImportExportAppController@onDeletePermitUploadedProductsDetails');
    Route::post('onSynchronisedUploadedProducts', 'ImportExportAppController@onSynchronisedUploadedProducts');
    Route::get('getClinicalTrialPersonnelDetails', 'ImportExportAppController@getClinicalTrialPersonnelDetails');
    Route::get('funcControlledDrugsInitiateLicenseApplication', 'ImportExportAppController@funcControlledDrugsInitiateLicenseApplication');
    Route::get('getLicensedImportExpPermitsApplicationLoading', 'ImportExportAppController@getLicensedImportExpPermitsApplicationLoading');
});
