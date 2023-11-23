<?php


Route::group(['middleware' => 'api', 'prefix' => 'tradermanagement', 'namespace' => 'Modules\TraderManagement\Http\Controllers'], function()
{
    Route::get('/', 'TraderManagementController@index');
    Route::post('onAccountRegistration', 'TraderManagementController@onAccountRegistration');
    Route::post('onSaveTradersApplicationInformation', 'TraderManagementController@onSaveTradersApplicationInformation');
    Route::get('onLoadTradersApplicationInformation', 'TraderManagementController@onLoadTradersApplicationInformation');
    Route::get('onValidateAccountEmail', 'TraderManagementController@onValidateAccountEmail');
    Route::get('getTraderInformation', 'TraderManagementController@getTraderInformation');

    Route::get('gettraderUsersAccountsManagementDetails', 'TraderManagementController@gettraderUsersAccountsManagementDetails');
    Route::post('onAccountRegistration', 'TraderManagementController@onAccountRegistration');
    Route::post('onAccountUsersRegistration', 'TraderManagementController@onAccountUsersRegistration');

    Route::get('onValidateAdminAccess', 'TraderManagementController@onValidateAdminAccess');
   
    Route::post('onUpdateTraderAccountDetails', 'TraderManagementController@onUpdateTraderAccountDetails');
   
    
});
