<?php

Route::group(['middleware' => ['api'], 'prefix' => 'publicaccess', 'namespace' => 'Modules\PublicAccess\Http\Controllers'], function()
{
    Route::get('onSearchPublicRegisteredpremises', 'PublicAccessController@onSearchPublicRegisteredpremises');
    Route::get('onSearchPublicRegisteredproducts', 'PublicAccessController@onSearchPublicRegisteredproducts');
    Route::get('onSearchPublicGmpComplaints', 'PublicAccessController@onSearchPublicGmpComplaints');
    Route::get('onSearchPublicRegisteredclinicaltrials', 'PublicAccessController@onSearchPublicRegisteredclinicaltrials');

    Route::post('onSavePoorQualityReportDetails', 'PublicAccessController@onSavePoorQualityReportDetails');
    Route::get('onLoadsuspectedProdReportingData', 'PublicAccessController@onLoadsuspectedProdReportingData');
    Route::get('getImportExpPermitsApplicationLoading', 'PublicAccessController@getImportExpPermitsApplicationLoading');
   
    Route::post('saveImportExportApplication', 'PublicAccessController@saveImportExportApplication');
   
    
    Route::get('getDeclaredImpExpApplicationsData', 'PublicAccessController@getDeclaredImpExpApplicationsData');
   
});