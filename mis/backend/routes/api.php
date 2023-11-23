<?php

use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth@handleLogin');
});*/
//Route::group(['middleware' => 'auth:api'], function () {
    Route::middleware(['auth:api'])->group( function () {
    Route::controller(CommonController::class)->group(function () {
        Route::post("/{user_id}/addresses", "saveAddress");
        Route::put("/{user_id}/addresses/{id}", "saveAddress");
        Route::delete("/{user_id}/addresses/{id}", "deleteAddress");

        Route::post('saveApplicationChecklistDetails', 'saveApplicationChecklistDetails');
        Route::post('saveCommonData', 'saveCommonData');
        Route::post('deleteCommonRecord', 'deleteCommonRecord');
        Route::get('getApplicationInvoiceDetails', 'getApplicationInvoiceDetails');
        Route::get('getElementCosts', 'getElementCosts');
        Route::get('getApplicationPaymentDetails', 'getApplicationPaymentDetails');
        Route::get('getOnlineApplicationRejections', 'getOnlineApplicationRejections');
        Route::get('getApplicationWithdrawalReasons', 'getApplicationWithdrawalReasons');
        Route::post('saveApplicationWithdrawalReasons', 'saveApplicationWithdrawalReasons');
        Route::get('getSystemNotifications', 'getSystemNotifications');
        Route::get('getApplicationVariationRequests', 'getApplicationVariationRequests');


        Route::get('getApplicationDataAmmendmentRequests', 'getApplicationDataAmmendmentRequests');
    
    Route::get('checkReviewREcommendationDEtails', 'checkReviewREcommendationDEtails');

    Route::get('checkApprovalREcommendationDEtails', 'checkApprovalREcommendationDEtails');
    Route::get('validateDocumentsSubmissonRecRecommendation', 'validateDocumentsSubmissonRecRecommendation');
    Route::get('checkPrecheckingrecommendation', 'checkPrecheckingrecommendation');

    
    
    Route::get('checkApplicationRaisedQueries', 'checkApplicationRaisedQueries');
    Route::get('getAllApplicationQueries', 'getAllApplicationQueries');
    Route::post('saveApplicationDismissalDetails', 'saveApplicationDismissalDetails');
    Route::get('getApplicationChecklistQueries', 'getApplicationChecklistQueries');
    Route::get('checkApplicationUnstructuredQueries', 'checkApplicationUnstructuredQueries');
    Route::post('closeApplicationQuery', 'closeApplicationQuery');
    Route::post('saveApplicationReQueryDetails', 'saveApplicationReQueryDetails');
    Route::get('checkApplicationRespondedUnclosedQueries', 'checkApplicationRespondedUnclosedQueries');
    Route::get('checkSampleSubmisisonDetails', 'checkSampleSubmisisonDetails');
    Route::get('checkOnlineApplicationChecklistDetails', 'checkOnlineApplicationChecklistDetails');
    Route::get('checkGeneratedInvoiceDetails', 'checkGeneratedInvoiceDetails');

    Route::get('checkApplicationChecklistUploadDetails', 'checkApplicationChecklistUploadDetails');
    Route::post('appDataAmmendmentStatusUpdate', 'appDataAmmendmentStatusUpdate');
    Route::get('getApplicationDetailsAlterationSetup', 'getApplicationDetailsAlterationSetup');
    Route::get('getApplicationDataAppealRequests', 'getApplicationDataAppealRequests');
    Route::post('saveApplicatioAppealReasons', 'saveApplicatioAppealReasons');
    Route::post('saveChecklistApplicationQuery', 'saveChecklistApplicationQuery');
    Route::get('checkApplicationEvaluationOverralRecom', 'checkApplicationEvaluationOverralRecom');
    Route::get('getUploadedApplicationPaymentDetails', 'getUploadedApplicationPaymentDetails');
    Route::post('deleteChecklistRaisedQuery', 'deleteChecklistRaisedQuery');
    
    Route::get('checkIfHasGeneratedInvoiceDEtails', 'checkIfHasGeneratedInvoiceDEtails');
    
    Route::get('validateHasUploadedDocumentsDetils', 'validateHasUploadedDocumentsDetils');
    
    Route::get('validateHasImportExportProductDetils', 'validateHasImportExportProductDetils');
    Route::get('checkSampleAnalysisReviewRecommendationDEtails', 'checkSampleAnalysisReviewRecommendationDEtails');
      Route::get('checkUserAccountActivities', 'checkUserAccountActivities');
    Route::get('getUploadedApplicationPaymentDetails', 'getUploadedApplicationPaymentDetails');
    Route::get('validateproductInformationdetails', 'validateproductInformationdetails');
    
    Route::get('checkApplicationChecklistDetails', 'checkApplicationChecklistDetails');
   
    Route::get('getAllInspectionsCaparequests', 'getAllInspectionsCaparequests');
    Route::get('getAllReinspectionInspectionsRequests', 'getAllReinspectionInspectionsRequests');

    
    Route::post('closeApplicationCAPA', 'closeApplicationCAPA');


    Route::post('saveChecklistApplicationCAPA', 'saveChecklistApplicationCAPA');
    Route::post('saveApplicationReinspectionREquests', 'saveApplicationReinspectionREquests');


 Route::get('getInspectionCapaFindingChecklists', 'getInspectionCapaFindingChecklists');
 Route::get('getAllReinspectionInspectionsRequests', 'getAllReinspectionInspectionsRequests');
 Route::get('getreinspectionsrequestsitems', 'getreinspectionsrequestsitems');
 Route::post('saveReinspectionchecklistitems', 'saveReinspectionchecklistitems');

 Route::get('getApplicationRejectiondetails', 'getApplicationRejectiondetails');

 Route::post('saveApplicationRejectionDetails', 'saveApplicationRejectionDetails');
 

    });
//     Route::post('saveApplicationChecklistDetails', 'CommonController@saveApplicationChecklistDetails');
//     Route::post('saveCommonData', 'CommonController@saveCommonData');
//     Route::post('deleteCommonRecord', 'CommonController@deleteCommonRecord');
//     Route::get('getApplicationInvoiceDetails', 'CommonController@getApplicationInvoiceDetails');
//     Route::get('getElementCosts', 'CommonController@getElementCosts');
//     Route::get('getApplicationPaymentDetails', 'CommonController@getApplicationPaymentDetails');
//     Route::get('getOnlineApplicationRejections', 'CommonController@getOnlineApplicationRejections');
//     Route::get('getApplicationWithdrawalReasons', 'CommonController@getApplicationWithdrawalReasons');
//     Route::post('saveApplicationWithdrawalReasons', 'CommonController@saveApplicationWithdrawalReasons');
//     Route::get('getSystemNotifications', 'CommonController@getSystemNotifications');
//     Route::get('getApplicationVariationRequests', 'CommonController@getApplicationVariationRequests');
    
//     Route::get('getApplicationDataAmmendmentRequests', 'CommonController@getApplicationDataAmmendmentRequests');
    
//     Route::get('checkReviewREcommendationDEtails', 'CommonController@checkReviewREcommendationDEtails');

//     Route::get('checkApprovalREcommendationDEtails', 'CommonController@checkApprovalREcommendationDEtails');
//     Route::get('validateDocumentsSubmissonRecRecommendation', 'CommonController@validateDocumentsSubmissonRecRecommendation');
//     Route::get('checkPrecheckingrecommendation', 'CommonController@checkPrecheckingrecommendation');

    
    
//     Route::get('checkApplicationRaisedQueries', 'CommonController@checkApplicationRaisedQueries');
//     Route::get('getAllApplicationQueries', 'CommonController@getAllApplicationQueries');
//     Route::post('saveApplicationDismissalDetails', 'CommonController@saveApplicationDismissalDetails');
//     Route::get('getApplicationChecklistQueries', 'CommonController@getApplicationChecklistQueries');
//     Route::get('checkApplicationUnstructuredQueries', 'CommonController@checkApplicationUnstructuredQueries');
//     Route::post('closeApplicationQuery', 'CommonController@closeApplicationQuery');
//     Route::post('saveApplicationReQueryDetails', 'CommonController@saveApplicationReQueryDetails');
//     Route::get('checkApplicationRespondedUnclosedQueries', 'CommonController@checkApplicationRespondedUnclosedQueries');
//     Route::get('checkSampleSubmisisonDetails', 'CommonController@checkSampleSubmisisonDetails');
//     Route::get('checkOnlineApplicationChecklistDetails', 'CommonController@checkOnlineApplicationChecklistDetails');
//     Route::get('checkGeneratedInvoiceDetails', 'CommonController@checkGeneratedInvoiceDetails');

//     Route::get('checkApplicationChecklistUploadDetails', 'CommonController@checkApplicationChecklistUploadDetails');
//     Route::post('appDataAmmendmentStatusUpdate', 'CommonController@appDataAmmendmentStatusUpdate');
//     Route::get('getApplicationDetailsAlterationSetup', 'CommonController@getApplicationDetailsAlterationSetup');
//     Route::get('getApplicationDataAppealRequests', 'CommonController@getApplicationDataAppealRequests');
//     Route::post('saveApplicatioAppealReasons', 'CommonController@saveApplicatioAppealReasons');
//     Route::post('saveChecklistApplicationQuery', 'CommonController@saveChecklistApplicationQuery');
//     Route::get('checkApplicationEvaluationOverralRecom', 'CommonController@checkApplicationEvaluationOverralRecom');
//     Route::get('getUploadedApplicationPaymentDetails', 'CommonController@getUploadedApplicationPaymentDetails');
//     Route::post('deleteChecklistRaisedQuery', 'CommonController@deleteChecklistRaisedQuery');
    
//     Route::get('checkIfHasGeneratedInvoiceDEtails', 'CommonController@checkIfHasGeneratedInvoiceDEtails');
    
//     Route::get('validateHasUploadedDocumentsDetils', 'CommonController@validateHasUploadedDocumentsDetils');
    
//     Route::get('validateHasImportExportProductDetils', 'CommonController@validateHasImportExportProductDetils');
//     Route::get('checkSampleAnalysisReviewRecommendationDEtails', 'CommonController@checkSampleAnalysisReviewRecommendationDEtails');
//       Route::get('checkUserAccountActivities', 'CommonController@checkUserAccountActivities');
//     Route::get('getUploadedApplicationPaymentDetails', 'CommonController@getUploadedApplicationPaymentDetails');
//     Route::get('validateproductInformationdetails', 'CommonController@validateproductInformationdetails');
    
//     Route::get('checkApplicationChecklistDetails', 'CommonController@checkApplicationChecklistDetails');
   
//     Route::get('getAllInspectionsCaparequests', 'CommonController@getAllInspectionsCaparequests');
//     Route::get('getAllReinspectionInspectionsRequests', 'CommonController@getAllReinspectionInspectionsRequests');

    
//     Route::post('closeApplicationCAPA', 'CommonController@closeApplicationCAPA');


//     Route::post('saveChecklistApplicationCAPA', 'CommonController@saveChecklistApplicationCAPA');
//     Route::post('saveApplicationReinspectionREquests', 'CommonController@saveApplicationReinspectionREquests');


//  Route::get('getInspectionCapaFindingChecklists', 'CommonController@getInspectionCapaFindingChecklists');
//  Route::get('getAllReinspectionInspectionsRequests', 'CommonController@getAllReinspectionInspectionsRequests');
//  Route::get('getreinspectionsrequestsitems', 'CommonController@getreinspectionsrequestsitems');
//  Route::post('saveReinspectionchecklistitems', 'CommonController@saveReinspectionchecklistitems');

//  Route::get('getApplicationRejectiondetails', 'CommonController@getApplicationRejectiondetails');

//  Route::post('saveApplicationRejectionDetails', 'CommonController@saveApplicationRejectionDetails');
 
});
