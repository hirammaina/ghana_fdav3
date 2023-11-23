<?php

Route::group(['middleware' => 'web', 'prefix' => 'reports', 'namespace' => 'Modules\Reports\Http\Controllers'], function()
{
    Route::get('/', 'ReportsController@index');
    Route::get('generateReport','ReportsController@generateReport');
    Route::get('generateApplicationInvoice','ReportsController@generateApplicationInvoice');
    Route::get('generateApplicationReceipt','ReportsController@generateApplicationReceipt');
    Route::get('generatePremiseCertificate','ReportsController@generatePremiseCertificate');
    Route::get('generatePremisePermit','ReportsController@generatePremisePermit');

    
    Route::get('generateProductRegCertificate','ReportsController@generateProductRegCertificate');
    Route::get('generateProductsApplicationRpt','ReportsController@generateProductsApplicationRpt');
    Route::get('generateProductRejectionLetter','ReportsController@generateProductRejectionLetter');
    Route::get('generateApplicationReceipts','ReportsController@generateApplicationReceipts');
    Route::get('generateRetentionStatements','ReportsController@generateRetentionStatements');
    Route::get('onExportRegisteredproducts','ReportsController@onExportRegisteredproducts');
    Route::get('generateProductApplicationReport','ReportsController@generateProductApplicationReport');
    Route::get('generateProductsNotificationRpt','ReportsController@generateProductsNotificationRpt');

    Route::get('funcDownloadApprovVisaProductsProducts','ReportsController@funcDownloadApprovVisaProductsProducts');
    
});
