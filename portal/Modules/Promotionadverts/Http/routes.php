<?php
Route::group(['middleware' => 'auth:api','prefix' => 'promotionadverts', 'namespace' => 'Modules\Promotionadverts\Http\Controllers'], function()
{
    Route::get('/', 'PromotionadvertsController@index');

    Route::get('getPromotionalProductParticulars', 'PromotionadvertsController@getPromotionalProductParticulars');
    Route::post('savePromotionalAdvertapplication', 'PromotionadvertsController@savePromotionalAdvertapplication');

    Route::get('getPromotionAlderrApplication', 'PromotionadvertsController@getPromotionAlderrApplication');
    Route::get('getPromotionalAdvertDetails', 'PromotionadvertsController@getPromotionalAdvertDetails');
    Route::post('OnSavePromotionalProductParticulars', 'PromotionadvertsController@OnSavePromotionalProductParticulars');
    Route::post('onDeleteOtherApplicationsDetails', 'PromotionadvertsController@onDeleteOtherApplicationsDetails');
    Route::post('onSavepromotionalMaterialsDetails', 'PromotionadvertsController@onSavepromotionalMaterialsDetails');
    Route::get('getApppromMaterialsDetailData', 'PromotionadvertsController@getApppromMaterialsDetailData');
    Route::get('getApplicationsCounter', 'PromotionadvertsController@getApplicationsCounter');

    
});
