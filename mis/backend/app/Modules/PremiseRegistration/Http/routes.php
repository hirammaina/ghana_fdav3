<?php

use App\Modules\PremiseRegistration\Http\Controllers\PremiseRegistrationController;
use App\Modules\PremiseRegistration\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::prefix('premiseregistration')->group(function () {
        Route::controller(PremiseRegistrationController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('uploadApplicationFile', 'uploadApplicationFile');
         
        
        
    
        });

    });
});




Route::middleware(['web'])->group( function () {
    Route::prefix('premiseregistration')->group(function () {
        Route::controller(ReportsController::class)->group(function () {
               //REPORTS
               Route::get('previewDoc', 'previewDoc');
               Route::get('printPremiseRegistrationCertificate', 'printPremiseRegistrationCertificate');
               Route::get('printPremiseBusinessPermit', 'printPremiseBusinessPermit');
               Route::get('getManagersReports', 'getManagersReports');
        
        
    
        });

    });
});

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('premiseregistration')->group(function () {
        Route::controller(PremiseRegistrationController::class)->group(function () {
            Route::get('getPremiseRegParamFromModel', 'getPremiseRegParamFromModel');
    Route::get('getApplicantsList', 'getApplicantsList');
    Route::get('getPremisesList', 'getPremisesList');
    Route::get('getAllPremisesList', 'getAllPremisesList');
	
    Route::get('getPremiseApplications', 'getPremiseApplications');
    Route::get('getPremiseApplicationsAtApproval', 'getPremiseApplicationsAtApproval');
    Route::get('getPremiseOtherDetails', 'getPremiseOtherDetails');
    Route::get('getPremisePersonnelDetails', 'getPremisePersonnelDetails');
    Route::post('savePremiseRegCommonData', 'savePremiseRegCommonData');
    Route::post('deletePremiseRegRecord', 'deletePremiseRegRecord');
    Route::post('softDeletePremiseRegRecord', 'softDeletePremiseRegRecord');
    Route::post('undoPremiseRegSoftDeletes', 'undoPremiseRegSoftDeletes');
    Route::post('savePremiseOtherDetails', 'savePremiseOtherDetails');
    Route::get('getQueryPrevResponses', 'getQueryPrevResponses');
    Route::post('saveApplicationInvoicingDetails', 'saveApplicationInvoicingDetails');
    Route::post('removeInvoiceCostElement', 'removeInvoiceCostElement');
    Route::get('getApplicationApplicantDetails', 'getApplicationApplicantDetails');
    Route::post('saveApplicationPaymentDetails', 'saveApplicationPaymentDetails');
    Route::post('removeApplicationPaymentDetails', 'removeApplicationPaymentDetails');
    Route::get('getManagerApplicationsGeneric', 'getManagerApplicationsGeneric');
    Route::get('getPremApplicationMoreDetails', 'getPremApplicationMoreDetails');
    Route::get('getApplicationComments', 'getApplicationComments');
    Route::get('getApplicationEvaluationTemplate', 'getApplicationEvaluationTemplate');
    Route::post('saveApplicationApprovalDetails', 'saveApplicationApprovalDetails');
    Route::post('deleteApplicationInvoice', 'deleteApplicationInvoice');
    Route::post('savePremisePersonnelDetails', 'savePremisePersonnelDetails');
    Route::post('savePremisePersonnelQualifications', 'savePremisePersonnelQualifications');
    Route::get('getPremisePersonnelQualifications', 'getPremisePersonnelQualifications');
    Route::post('deletePersonnelQualification', 'deletePersonnelQualification');
    Route::post('uploadPersonnelDocument', 'uploadPersonnelDocument');
    Route::get('getPersonnelDocuments', 'getPersonnelDocuments');
    Route::get('getTraderPersonnel', 'getTraderPersonnel');
    Route::post('savePremisePersonnelLinkageDetails', 'savePremisePersonnelLinkageDetails');
    Route::get('getInspectionDetails', 'getInspectionDetails');
    Route::get('getInspectionInspectors', 'getInspectionInspectors');
    Route::get('getInspectorsList', 'getInspectorsList');
    Route::post('saveInspectionInspectors', 'saveInspectionInspectors');
    Route::post('removeInspectionInspectors', 'removeInspectionInspectors');
    Route::post('saveNewReceivingBaseDetails', 'saveNewReceivingBaseDetails');
	Route::post('funcAddNewPremisesDetails', 'funcAddNewPremisesDetails');
	
	

    Route::post('saveRenewalReceivingBaseDetails', 'saveRenewalReceivingBaseDetails');
    Route::post('saveAlterationReceivingBaseDetails', 'saveAlterationReceivingBaseDetails');
    Route::post('saveRenewalAlterationReceivingBaseDetails', 'saveRenewalAlterationReceivingBaseDetails');

    Route::get('prepareNewPremiseReceivingStage', 'prepareNewPremiseReceivingStage');
    Route::get('prepareRenewalPremiseReceivingStage', 'prepareRenewalPremiseReceivingStage');
    Route::get('prepareNewPremiseInvoicingStage', 'prepareNewPremiseInvoicingStage');
    Route::get('prepareRenewalPremiseInvoicingStage', 'prepareRenewalPremiseInvoicingStage');
    Route::get('prepareNewPremisePaymentStage', 'prepareNewPremisePaymentStage');
    Route::get('prepareRenewalPremisePaymentStage', 'prepareRenewalPremisePaymentStage');
    Route::get('getManagerApplicationsRenewalGeneric', 'getManagerApplicationsRenewalGeneric');
    Route::get('getManagerInspectionApplications', 'getManagerInspectionApplications');
    Route::get('getAwaitingConceptNoteInspections', 'getAwaitingConceptNoteInspections');
	
    Route::get('prepareNewPremiseManagerInspectionStage', 'prepareNewPremiseManagerInspectionStage');
    Route::get('prepareNewPremiseEvaluationStage', 'prepareNewPremiseEvaluationStage');
    Route::get('prepareRenewalPremiseEvaluationStage', 'prepareRenewalPremiseEvaluationStage');
    Route::get('getOnlineApplicationQueries', 'getOnlineApplicationQueries');
    Route::post('saveOnlineQueries', 'saveOnlineQueries');
    Route::post('saveApplicationChecklistDetails', 'saveApplicationChecklistDetails');
    Route::post('syncAlterationAmendmentFormParts', 'syncAlterationAmendmentFormParts');
    Route::post('syncAlterationAmendmentOtherParts', 'syncAlterationAmendmentOtherParts');
    Route::post('getPremiseComparisonDetails', 'getPremiseComparisonDetails');
    Route::get('getApplicationUploadedDocs', 'getApplicationUploadedDocs');
    Route::get('getApplicationChecklistQueries', 'getApplicationChecklistQueries');
    Route::get('prepareNewOnlineReceivingStage', 'prepareNewOnlineReceivingStage');
    Route::post('saveNewAuditingChecklistDetails', 'saveNewAuditingChecklistDetails');
    Route::post('savePremiseInspectionDetails', 'savePremiseInspectionDetails');
    Route::get('getPremiseContactPersonDetails', 'getPremiseContactPersonDetails');
    Route::post('savePremiseCancellationReason', 'savePremiseCancellationReason');
    Route::get('getPremiseCancellationReasons', 'getPremiseCancellationReasons');
    Route::get('getPremiseWithdrawalApplicationsAtApproval', 'getPremiseWithdrawalApplicationsAtApproval');
    Route::get('getPremiseCompareDetails', 'getPremiseCompareDetails');
    Route::get('getDismissedPremiseApplications', 'getDismissedPremiseApplications');
    Route::post('savePremiseInspectionRecommendation', 'savePremiseInspectionRecommendation');
    Route::get('getManagerReviewApplications', 'getManagerReviewApplications');
    //Online Applications
    Route::get('getOnlineApplications', 'getOnlineApplications');
    Route::get('getOnlineAppPremiseOtherDetails', 'getOnlineAppPremiseOtherDetails');
    Route::get('getOnlineAppPremisePersonnelDetails', 'getOnlineAppPremisePersonnelDetails');
    Route::get('getOnlineApplicationUploads', 'getOnlineApplicationUploads');
    Route::post('saveOnlineApplicationDetails', 'saveOnlineApplicationDetails');
    Route::post('updateOnlineApplicationQueryResponse', 'updateOnlineApplicationQueryResponse');
    Route::post('rejectOnlineApplicationDetails', 'rejectOnlineApplicationDetails');
    Route::post('onOnlineApplicationActionQueries', 'onOnlineApplicationActionQueries');

    
    Route::post('savePremiseScheduleInspectionDetails', 'savePremiseScheduleInspectionDetails');
    Route::get('getRegisteredPremisesList', 'getPremisesList');
    Route::post('linkRegisteredPremisestoInpection', 'linkRegisteredPremisestoInpection');
    
    Route::get('getPremisesInspectionDetails', 'getPremisesInspectionDetails');
    Route::get('getPremisesinspectionschedulingDetails', 'getPremisesinspectionschedulingDetails');
    Route::get('getPremisesInspectionRecommendationDetails', 'getPremisesInspectionRecommendationDetails');
    Route::get('getPremisesinspectionreviewrecomdetails', 'getPremisesinspectionreviewrecomdetails');
     
    Route::get('getPremisesApprovedinspectiondetails', 'getPremisesApprovedinspectiondetails');
    Route::post('saveLegalityofStockprdRemarks', 'saveLegalityofStockprdRemarks');
    Route::post('savePremIllegalStockedProducts', 'savePremIllegalStockedProducts');
    Route::get('getPremisesIllegalPrdStockDetails', 'getPremisesIllegalPrdStockDetails');
      
    Route::get('getPremisesAppList', 'getPremisesAppList');
    Route::post('saveAppliationEditionBaseDetails', 'saveAppliationEditionBaseDetails');
     Route::post('savePrecheckingecommendationDetails', 'savePrecheckingecommendationDetails');
     Route::post('saveTCMeetingDetails', 'saveTCMeetingDetails');
     Route::get('preparePremiseRegMeetingStage', 'preparePremiseRegMeetingStage');
     Route::get('getPremiseTcReviewMeetingApplications', 'getPremiseTcReviewMeetingApplications');
     Route::get('getPremiseRegistrationMeetingApplications', 'getPremiseRegistrationMeetingApplications');
     Route::get('getPremisesInspectionLineDetails', 'getPremisesInspectionLineDetails');
     Route::post('saveGmpInspectionLineDetails', 'saveGmpInspectionLineDetails');
        
        
    
        });

    });
});




// //WEB Routes
// Route::group(['middleware' => 'web', 'prefix' => 'premiseregistration', 'namespace' => 'App\\Modules\PremiseRegistration\Http\Controllers'], function () {
//     Route::get('/', 'index');
//     Route::post('uploadApplicationFile', 'uploadApplicationFile');
//     //REPORTS
//     Route::get('previewDoc', 'ReportsController@previewDoc');
//     Route::get('printPremiseRegistrationCertificate', 'ReportsController@printPremiseRegistrationCertificate');
//     Route::get('printPremiseBusinessPermit', 'ReportsController@printPremiseBusinessPermit');
//     Route::get('getManagersReports', 'ReportsController@getManagersReports');
// });
// //API Routes
// Route::group(['middleware' => 'auth:api', 'prefix' => 'premiseregistration', 'namespace' => 'App\\Modules\PremiseRegistration\Http\Controllers'], function () {
//     Route::get('getPremiseRegParamFromModel', 'PremiseRegistrationController@getPremiseRegParamFromModel');
//     Route::get('getApplicantsList', 'PremiseRegistrationController@getApplicantsList');
//     Route::get('getPremisesList', 'PremiseRegistrationController@getPremisesList');
//     Route::get('getAllPremisesList', 'PremiseRegistrationController@getAllPremisesList');
	
//     Route::get('getPremiseApplications', 'PremiseRegistrationController@getPremiseApplications');
//     Route::get('getPremiseApplicationsAtApproval', 'PremiseRegistrationController@getPremiseApplicationsAtApproval');
//     Route::get('getPremiseOtherDetails', 'PremiseRegistrationController@getPremiseOtherDetails');
//     Route::get('getPremisePersonnelDetails', 'PremiseRegistrationController@getPremisePersonnelDetails');
//     Route::post('savePremiseRegCommonData', 'PremiseRegistrationController@savePremiseRegCommonData');
//     Route::post('deletePremiseRegRecord', 'PremiseRegistrationController@deletePremiseRegRecord');
//     Route::post('softDeletePremiseRegRecord', 'PremiseRegistrationController@softDeletePremiseRegRecord');
//     Route::post('undoPremiseRegSoftDeletes', 'PremiseRegistrationController@undoPremiseRegSoftDeletes');
//     Route::post('savePremiseOtherDetails', 'PremiseRegistrationController@savePremiseOtherDetails');
//     Route::get('getQueryPrevResponses', 'PremiseRegistrationController@getQueryPrevResponses');
//     Route::post('saveApplicationInvoicingDetails', 'PremiseRegistrationController@saveApplicationInvoicingDetails');
//     Route::post('removeInvoiceCostElement', 'PremiseRegistrationController@removeInvoiceCostElement');
//     Route::get('getApplicationApplicantDetails', 'PremiseRegistrationController@getApplicationApplicantDetails');
//     Route::post('saveApplicationPaymentDetails', 'PremiseRegistrationController@saveApplicationPaymentDetails');
//     Route::post('removeApplicationPaymentDetails', 'PremiseRegistrationController@removeApplicationPaymentDetails');
//     Route::get('getManagerApplicationsGeneric', 'PremiseRegistrationController@getManagerApplicationsGeneric');
//     Route::get('getPremApplicationMoreDetails', 'PremiseRegistrationController@getPremApplicationMoreDetails');
//     Route::get('getApplicationComments', 'PremiseRegistrationController@getApplicationComments');
//     Route::get('getApplicationEvaluationTemplate', 'PremiseRegistrationController@getApplicationEvaluationTemplate');
//     Route::post('saveApplicationApprovalDetails', 'PremiseRegistrationController@saveApplicationApprovalDetails');
//     Route::post('deleteApplicationInvoice', 'PremiseRegistrationController@deleteApplicationInvoice');
//     Route::post('savePremisePersonnelDetails', 'PremiseRegistrationController@savePremisePersonnelDetails');
//     Route::post('savePremisePersonnelQualifications', 'PremiseRegistrationController@savePremisePersonnelQualifications');
//     Route::get('getPremisePersonnelQualifications', 'PremiseRegistrationController@getPremisePersonnelQualifications');
//     Route::post('deletePersonnelQualification', 'PremiseRegistrationController@deletePersonnelQualification');
//     Route::post('uploadPersonnelDocument', 'PremiseRegistrationController@uploadPersonnelDocument');
//     Route::get('getPersonnelDocuments', 'PremiseRegistrationController@getPersonnelDocuments');
//     Route::get('getTraderPersonnel', 'PremiseRegistrationController@getTraderPersonnel');
//     Route::post('savePremisePersonnelLinkageDetails', 'PremiseRegistrationController@savePremisePersonnelLinkageDetails');
//     Route::get('getInspectionDetails', 'PremiseRegistrationController@getInspectionDetails');
//     Route::get('getInspectionInspectors', 'PremiseRegistrationController@getInspectionInspectors');
//     Route::get('getInspectorsList', 'PremiseRegistrationController@getInspectorsList');
//     Route::post('saveInspectionInspectors', 'PremiseRegistrationController@saveInspectionInspectors');
//     Route::post('removeInspectionInspectors', 'PremiseRegistrationController@removeInspectionInspectors');
//     Route::post('saveNewReceivingBaseDetails', 'PremiseRegistrationController@saveNewReceivingBaseDetails');
// 	Route::post('funcAddNewPremisesDetails', 'PremiseRegistrationController@funcAddNewPremisesDetails');
	
	

//     Route::post('saveRenewalReceivingBaseDetails', 'PremiseRegistrationController@saveRenewalReceivingBaseDetails');
//     Route::post('saveAlterationReceivingBaseDetails', 'PremiseRegistrationController@saveAlterationReceivingBaseDetails');
//     Route::post('saveRenewalAlterationReceivingBaseDetails', 'PremiseRegistrationController@saveRenewalAlterationReceivingBaseDetails');

//     Route::get('prepareNewPremiseReceivingStage', 'PremiseRegistrationController@prepareNewPremiseReceivingStage');
//     Route::get('prepareRenewalPremiseReceivingStage', 'PremiseRegistrationController@prepareRenewalPremiseReceivingStage');
//     Route::get('prepareNewPremiseInvoicingStage', 'PremiseRegistrationController@prepareNewPremiseInvoicingStage');
//     Route::get('prepareRenewalPremiseInvoicingStage', 'PremiseRegistrationController@prepareRenewalPremiseInvoicingStage');
//     Route::get('prepareNewPremisePaymentStage', 'PremiseRegistrationController@prepareNewPremisePaymentStage');
//     Route::get('prepareRenewalPremisePaymentStage', 'PremiseRegistrationController@prepareRenewalPremisePaymentStage');
//     Route::get('getManagerApplicationsRenewalGeneric', 'PremiseRegistrationController@getManagerApplicationsRenewalGeneric');
//     Route::get('getManagerInspectionApplications', 'PremiseRegistrationController@getManagerInspectionApplications');
//     Route::get('getAwaitingConceptNoteInspections', 'PremiseRegistrationController@getAwaitingConceptNoteInspections');
	
//     Route::get('prepareNewPremiseManagerInspectionStage', 'PremiseRegistrationController@prepareNewPremiseManagerInspectionStage');
//     Route::get('prepareNewPremiseEvaluationStage', 'PremiseRegistrationController@prepareNewPremiseEvaluationStage');
//     Route::get('prepareRenewalPremiseEvaluationStage', 'PremiseRegistrationController@prepareRenewalPremiseEvaluationStage');
//     Route::get('getOnlineApplicationQueries', 'PremiseRegistrationController@getOnlineApplicationQueries');
//     Route::post('saveOnlineQueries', 'PremiseRegistrationController@saveOnlineQueries');
//     Route::post('saveApplicationChecklistDetails', 'PremiseRegistrationController@saveApplicationChecklistDetails');
//     Route::post('syncAlterationAmendmentFormParts', 'PremiseRegistrationController@syncAlterationAmendmentFormParts');
//     Route::post('syncAlterationAmendmentOtherParts', 'PremiseRegistrationController@syncAlterationAmendmentOtherParts');
//     Route::post('getPremiseComparisonDetails', 'PremiseRegistrationController@getPremiseComparisonDetails');
//     Route::get('getApplicationUploadedDocs', 'PremiseRegistrationController@getApplicationUploadedDocs');
//     Route::get('getApplicationChecklistQueries', 'PremiseRegistrationController@getApplicationChecklistQueries');
//     Route::get('prepareNewOnlineReceivingStage', 'PremiseRegistrationController@prepareNewOnlineReceivingStage');
//     Route::post('saveNewAuditingChecklistDetails', 'PremiseRegistrationController@saveNewAuditingChecklistDetails');
//     Route::post('savePremiseInspectionDetails', 'PremiseRegistrationController@savePremiseInspectionDetails');
//     Route::get('getPremiseContactPersonDetails', 'PremiseRegistrationController@getPremiseContactPersonDetails');
//     Route::post('savePremiseCancellationReason', 'PremiseRegistrationController@savePremiseCancellationReason');
//     Route::get('getPremiseCancellationReasons', 'PremiseRegistrationController@getPremiseCancellationReasons');
//     Route::get('getPremiseWithdrawalApplicationsAtApproval', 'PremiseRegistrationController@getPremiseWithdrawalApplicationsAtApproval');
//     Route::get('getPremiseCompareDetails', 'PremiseRegistrationController@getPremiseCompareDetails');
//     Route::get('getDismissedPremiseApplications', 'PremiseRegistrationController@getDismissedPremiseApplications');
//     Route::post('savePremiseInspectionRecommendation', 'PremiseRegistrationController@savePremiseInspectionRecommendation');
//     Route::get('getManagerReviewApplications', 'PremiseRegistrationController@getManagerReviewApplications');
//     //Online Applications
//     Route::get('getOnlineApplications', 'PremiseRegistrationController@getOnlineApplications');
//     Route::get('getOnlineAppPremiseOtherDetails', 'PremiseRegistrationController@getOnlineAppPremiseOtherDetails');
//     Route::get('getOnlineAppPremisePersonnelDetails', 'PremiseRegistrationController@getOnlineAppPremisePersonnelDetails');
//     Route::get('getOnlineApplicationUploads', 'PremiseRegistrationController@getOnlineApplicationUploads');
//     Route::post('saveOnlineApplicationDetails', 'PremiseRegistrationController@saveOnlineApplicationDetails');
//     Route::post('updateOnlineApplicationQueryResponse', 'PremiseRegistrationController@updateOnlineApplicationQueryResponse');
//     Route::post('rejectOnlineApplicationDetails', 'PremiseRegistrationController@rejectOnlineApplicationDetails');
//     Route::post('onOnlineApplicationActionQueries', 'PremiseRegistrationController@onOnlineApplicationActionQueries');

    
//     Route::post('savePremiseScheduleInspectionDetails', 'PremiseRegistrationController@savePremiseScheduleInspectionDetails');
//     Route::get('getRegisteredPremisesList', 'PremiseRegistrationController@getPremisesList');
//     Route::post('linkRegisteredPremisestoInpection', 'PremiseRegistrationController@linkRegisteredPremisestoInpection');
    
//     Route::get('getPremisesInspectionDetails', 'PremiseRegistrationController@getPremisesInspectionDetails');
//     Route::get('getPremisesinspectionschedulingDetails', 'PremiseRegistrationController@getPremisesinspectionschedulingDetails');
//     Route::get('getPremisesInspectionRecommendationDetails', 'PremiseRegistrationController@getPremisesInspectionRecommendationDetails');
//     Route::get('getPremisesinspectionreviewrecomdetails', 'PremiseRegistrationController@getPremisesinspectionreviewrecomdetails');
     
//     Route::get('getPremisesApprovedinspectiondetails', 'PremiseRegistrationController@getPremisesApprovedinspectiondetails');
//     Route::post('saveLegalityofStockprdRemarks', 'PremiseRegistrationController@saveLegalityofStockprdRemarks');
//     Route::post('savePremIllegalStockedProducts', 'PremiseRegistrationController@savePremIllegalStockedProducts');
//     Route::get('getPremisesIllegalPrdStockDetails', 'PremiseRegistrationController@getPremisesIllegalPrdStockDetails');
      
//     Route::get('getPremisesAppList', 'PremiseRegistrationController@getPremisesAppList');
//     Route::post('saveAppliationEditionBaseDetails', 'PremiseRegistrationController@saveAppliationEditionBaseDetails');
//      Route::post('savePrecheckingecommendationDetails', 'PremiseRegistrationController@savePrecheckingecommendationDetails');
//      Route::post('saveTCMeetingDetails', 'PremiseRegistrationController@saveTCMeetingDetails');
//      Route::get('preparePremiseRegMeetingStage', 'PremiseRegistrationController@preparePremiseRegMeetingStage');
//      Route::get('getPremiseTcReviewMeetingApplications', 'PremiseRegistrationController@getPremiseTcReviewMeetingApplications');
//      Route::get('getPremiseRegistrationMeetingApplications', 'PremiseRegistrationController@getPremiseRegistrationMeetingApplications');
//      Route::get('getPremisesInspectionLineDetails', 'PremiseRegistrationController@getPremisesInspectionLineDetails');
//      Route::post('saveGmpInspectionLineDetails', 'PremiseRegistrationController@saveGmpInspectionLineDetails');
    
// });
