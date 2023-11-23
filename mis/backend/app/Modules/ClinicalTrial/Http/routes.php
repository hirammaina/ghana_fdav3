<?php

use App\Modules\ClinicalTrial\Http\Controllers\ClinicalTrialController;
use Illuminate\Support\Facades\Route;
Route::middleware(['web'])->group( function () {
    Route::prefix('clinicaltrial')->group(function () {
        Route::controller(ClinicalTrialController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('printApplicationMeetingDetails', 'printApplicationMeetingDetails');
        
    
        });

    });
});


Route::middleware(['auth:api'])->group( function () {
    Route::prefix('clinicaltrial')->group(function () {
        Route::controller(AuditTrailController::class)->group(function () {
            Route::get('getClinicalTrialApplications', 'getClinicalTrialApplications');
            Route::post('saveClinicalTrialCommonData', 'saveClinicalTrialCommonData');
            Route::get('getClinicalTrialParamFromModel', 'getClinicalTrialParamFromModel');
            Route::post('deleteClinicalTrialRecord', 'deleteClinicalTrialRecord');
            Route::post('softDeleteClinicalTrialRecord', 'softDeleteClinicalTrialRecord');
            Route::get('getStudySitesList', 'getStudySitesList');
            Route::post('saveNewReceivingBaseDetails', 'saveNewReceivingBaseDetails');
            Route::post('saveProgressReportingBaseDetails', 'saveProgressReportingBaseDetails');
        
            
            Route::post('saveNewApplicationClinicalTrialDetails', 'saveNewApplicationClinicalTrialDetails');
            Route::post('saveNewApplicationClinicalTrialOtherDetails', 'saveNewApplicationClinicalTrialOtherDetails');
            Route::get('prepareNewClinicalTrialReceivingStage', 'prepareNewClinicalTrialReceivingStage');
            Route::get('prepareNewClinicalTrialInvoicingStage', 'prepareNewClinicalTrialInvoicingStage');
            Route::get('prepareNewClinicalTrialPaymentStage', 'prepareNewClinicalTrialPaymentStage');
            Route::get('prepareNewClinicalTrialAssessmentStage', 'prepareNewClinicalTrialAssessmentStage');
            Route::get('prepareCtrProgressReportAssessment', 'prepareCtrProgressReportAssessment');
        
            
            Route::get('prepareNewClinicalTrialManagerMeetingStage', 'prepareNewClinicalTrialManagerMeetingStage');
            Route::post('addClinicalStudySite', 'addClinicalStudySite');
            Route::get('getClinicalStudySites', 'getClinicalStudySites');
            Route::get('getClinicalTrialPersonnelList', 'getClinicalTrialPersonnelList');
            Route::post('addApplicationOtherInvestigators', 'addApplicationOtherInvestigators');
            Route::get('getClinicalTrialOtherInvestigators', 'getClinicalTrialOtherInvestigators');
            Route::get('getClinicalTrialMonitors', 'getClinicalTrialMonitors');
        
            
            Route::get('getImpProducts', 'getImpProducts');
            Route::get('getImpProductIngredients', 'getImpProductIngredients');
            Route::get('getClinicalTrialManagerApplicationsGeneric', 'getClinicalTrialManagerApplicationsGeneric');
            Route::get('getstrProgressReportingManagerApplicationsGeneric', 'getstrProgressReportingManagerApplicationsGeneric');
           
            
            Route::get('getClinicalTrialManagerMeetingApplications', 'getClinicalTrialManagerMeetingApplications');
            Route::get('getClinicalTrialRecommReviewApplications', 'getClinicalTrialRecommReviewApplications');
            Route::get('getClinicalTrialApplicationsAtApproval', 'getClinicalTrialApplicationsAtApproval');
            Route::post('saveTCMeetingDetails', 'saveTCMeetingDetails');
            Route::post('syncTcMeetingParticipants', 'syncTcMeetingParticipants');
            Route::get('getTcMeetingParticipants', 'getTcMeetingParticipants');
            Route::get('getTcMeetingAgendas', 'getTcMeetingAgendas');
            
            Route::get('getExternalAssessorDetails', 'getExternalAssessorDetails');
            Route::get('getTcMeetingDetails', 'getTcMeetingDetails');
            Route::get('getClinicalTrialApplicationMoreDetails', 'getClinicalTrialApplicationMoreDetails');
            Route::get('getClinicalTrialsList', 'getClinicalTrialsList');
            Route::post('saveAmendmentReceivingBaseDetails', 'saveAmendmentReceivingBaseDetails');
            Route::get('getOnlineApplications', 'getOnlineApplications');
            Route::get('prepareOnlineClinicalTrialPreview', 'prepareOnlineClinicalTrialPreview');
            Route::get('prepareOnlineClinicalTrialRegistryPreview', 'prepareOnlineClinicalTrialRegistryPreview');
            Route::get('getCtrRegistryInterventions', 'getCtrRegistryInterventions');
            Route::get('getClinicalOutcomesDetails', 'getClinicalOutcomesDetails');
            Route::get('getClinicalRecruiptmentDetails', 'getClinicalRecruiptmentDetails');
            Route::get('getClinicalEthicsApprovalDetails', 'getClinicalEthicsApprovalDetails');
            Route::get('getClinicaltrailSponsorsData', 'getClinicaltrailSponsorsData');
            Route::get('getClinicalContactPersonsDetails', 'getClinicalContactPersonsDetails');
            
            
            Route::get('getOnlineClinicalStudySites', 'getOnlineClinicalStudySites');
            Route::get('getOnlineClinicalTrialOtherInvestigators', 'getOnlineClinicalTrialOtherInvestigators');
            Route::get('getOnlineImpProducts', 'getOnlineImpProducts');
            Route::get('getOnlineImpProductIngredients', 'getOnlineImpProductIngredients');
            Route::get('getClinicalTrialCompareDetails', 'getClinicalTrialCompareDetails');
            Route::get('getDismissedClinicalTrialApplications', 'getDismissedClinicalTrialApplications');
        
            Route::get('getOnlineClinicalTrialMonitors', 'getOnlineClinicalTrialMonitors');
            Route::get('prepareOnlineClinicalTrialProgressRptPreview', 'prepareOnlineClinicalTrialProgressRptPreview');
        
            Route::get('getClinicalTrialCtrgcpInspectionscheduleDetails', 'getClinicalTrialCtrgcpInspectionscheduleDetails');
        
            Route::post('saveGcpScheduleInspectionDetails', 'saveGcpScheduleInspectionDetails');
        
            Route::get('prepareClinicalTrialGCPInspectionStage', 'prepareClinicalTrialGCPInspectionStage');
            Route::post('saveGcpClinicalApplicationdetails', 'saveGcpClinicalApplicationdetails');
            Route::get('getClinicalTrialCtrgcpInspectionDetails', 'getClinicalTrialCtrgcpInspectionDetails');
            Route::get('getGCPInspectionRecommendationDetails', 'getGCPInspectionRecommendationDetails');
        
            Route::post('saveGcpInspectionRecommendation', 'saveGcpInspectionRecommendation');
            Route::post('saveGcpInspectionApproval', 'saveGcpInspectionApproval');
            Route::get('getAllClinicalTrialApplications', 'getAllClinicalTrialApplications');
            Route::get('getApprovedClinicalTrialApplications', 'getApprovedClinicalTrialApplications');
        
            Route::post('saveEditAppBaseDetails', 'saveEditAppBaseDetails');
         
            Route::post('save_clinicalregdetails', 'save_clinicalregdetails');
            Route::post('save_clinicalseconaryids', 'save_clinicalseconaryids');
            Route::post('save_clinicalstudyDesign', 'save_clinicalstudyDesign');
            Route::post('save_clinicaleligibilitycriteria', 'save_clinicaleligibilitycriteria');
            Route::post('save_clinicalfundingsource', 'save_clinicalfundingsource');
            Route::post('save_clinicalcollaborators', 'save_clinicalcollaborators');
         
            Route::post('onSaveInterventionDetails', 'onSaveInterventionDetails');
            Route::post('onSaveOutcomesDetails', 'onSaveOutcomesDetails');
            Route::post('onSaverecruitmentCenter', 'onSaverecruitmentCenter');
            Route::post('onsaveclinicaltSponsorDetails', 'onsaveclinicaltSponsorDetails');
            Route::post('onSaveContactPersonDetails', 'onSaveContactPersonDetails');
            Route::post('onSaveethicsApproval', 'onSaveethicsApproval');
            Route::get('getPharmacoVigilanceApps', 'getClinicalTrialApplications');
        
    
        });

    });
});




// Route::group(['middleware' => 'web', 'prefix' => 'clinicaltrial', 'namespace' => 'App\\Modules\ClinicalTrial\Http\Controllers'], function () {
//     Route::get('/', 'ClinicalTrialController@index');
//     Route::get('printApplicationMeetingDetails', 'ClinicalTrialController@printApplicationMeetingDetails');
// });

// Route::group(['middleware' => 'auth:api', 'prefix' => 'clinicaltrial', 'namespace' => 'App\\Modules\ClinicalTrial\Http\Controllers'], function () {
//     Route::get('getClinicalTrialApplications', 'ClinicalTrialController@getClinicalTrialApplications');
//     Route::post('saveClinicalTrialCommonData', 'ClinicalTrialController@saveClinicalTrialCommonData');
//     Route::get('getClinicalTrialParamFromModel', 'ClinicalTrialController@getClinicalTrialParamFromModel');
//     Route::post('deleteClinicalTrialRecord', 'ClinicalTrialController@deleteClinicalTrialRecord');
//     Route::post('softDeleteClinicalTrialRecord', 'ClinicalTrialController@softDeleteClinicalTrialRecord');
//     Route::get('getStudySitesList', 'ClinicalTrialController@getStudySitesList');
//     Route::post('saveNewReceivingBaseDetails', 'ClinicalTrialController@saveNewReceivingBaseDetails');
//     Route::post('saveProgressReportingBaseDetails', 'ClinicalTrialController@saveProgressReportingBaseDetails');

    
//     Route::post('saveNewApplicationClinicalTrialDetails', 'ClinicalTrialController@saveNewApplicationClinicalTrialDetails');
//     Route::post('saveNewApplicationClinicalTrialOtherDetails', 'ClinicalTrialController@saveNewApplicationClinicalTrialOtherDetails');
//     Route::get('prepareNewClinicalTrialReceivingStage', 'ClinicalTrialController@prepareNewClinicalTrialReceivingStage');
//     Route::get('prepareNewClinicalTrialInvoicingStage', 'ClinicalTrialController@prepareNewClinicalTrialInvoicingStage');
//     Route::get('prepareNewClinicalTrialPaymentStage', 'ClinicalTrialController@prepareNewClinicalTrialPaymentStage');
//     Route::get('prepareNewClinicalTrialAssessmentStage', 'ClinicalTrialController@prepareNewClinicalTrialAssessmentStage');
//     Route::get('prepareCtrProgressReportAssessment', 'ClinicalTrialController@prepareCtrProgressReportAssessment');

    
//     Route::get('prepareNewClinicalTrialManagerMeetingStage', 'ClinicalTrialController@prepareNewClinicalTrialManagerMeetingStage');
//     Route::post('addClinicalStudySite', 'ClinicalTrialController@addClinicalStudySite');
//     Route::get('getClinicalStudySites', 'ClinicalTrialController@getClinicalStudySites');
//     Route::get('getClinicalTrialPersonnelList', 'ClinicalTrialController@getClinicalTrialPersonnelList');
//     Route::post('addApplicationOtherInvestigators', 'ClinicalTrialController@addApplicationOtherInvestigators');
//     Route::get('getClinicalTrialOtherInvestigators', 'ClinicalTrialController@getClinicalTrialOtherInvestigators');
//     Route::get('getClinicalTrialMonitors', 'ClinicalTrialController@getClinicalTrialMonitors');

    
//     Route::get('getImpProducts', 'ClinicalTrialController@getImpProducts');
//     Route::get('getImpProductIngredients', 'ClinicalTrialController@getImpProductIngredients');
//     Route::get('getClinicalTrialManagerApplicationsGeneric', 'ClinicalTrialController@getClinicalTrialManagerApplicationsGeneric');
//     Route::get('getstrProgressReportingManagerApplicationsGeneric', 'ClinicalTrialController@getstrProgressReportingManagerApplicationsGeneric');
   
    
//     Route::get('getClinicalTrialManagerMeetingApplications', 'ClinicalTrialController@getClinicalTrialManagerMeetingApplications');
//     Route::get('getClinicalTrialRecommReviewApplications', 'ClinicalTrialController@getClinicalTrialRecommReviewApplications');
//     Route::get('getClinicalTrialApplicationsAtApproval', 'ClinicalTrialController@getClinicalTrialApplicationsAtApproval');
//     Route::post('saveTCMeetingDetails', 'ClinicalTrialController@saveTCMeetingDetails');
//     Route::post('syncTcMeetingParticipants', 'ClinicalTrialController@syncTcMeetingParticipants');
//     Route::get('getTcMeetingParticipants', 'ClinicalTrialController@getTcMeetingParticipants');
//     Route::get('getTcMeetingAgendas', 'ClinicalTrialController@getTcMeetingAgendas');
    
//     Route::get('getExternalAssessorDetails', 'ClinicalTrialController@getExternalAssessorDetails');
//     Route::get('getTcMeetingDetails', 'ClinicalTrialController@getTcMeetingDetails');
//     Route::get('getClinicalTrialApplicationMoreDetails', 'ClinicalTrialController@getClinicalTrialApplicationMoreDetails');
//     Route::get('getClinicalTrialsList', 'ClinicalTrialController@getClinicalTrialsList');
//     Route::post('saveAmendmentReceivingBaseDetails', 'ClinicalTrialController@saveAmendmentReceivingBaseDetails');
//     Route::get('getOnlineApplications', 'ClinicalTrialController@getOnlineApplications');
//     Route::get('prepareOnlineClinicalTrialPreview', 'ClinicalTrialController@prepareOnlineClinicalTrialPreview');
//     Route::get('prepareOnlineClinicalTrialRegistryPreview', 'ClinicalTrialController@prepareOnlineClinicalTrialRegistryPreview');
//     Route::get('getCtrRegistryInterventions', 'ClinicalTrialController@getCtrRegistryInterventions');
//     Route::get('getClinicalOutcomesDetails', 'ClinicalTrialController@getClinicalOutcomesDetails');
//     Route::get('getClinicalRecruiptmentDetails', 'ClinicalTrialController@getClinicalRecruiptmentDetails');
//     Route::get('getClinicalEthicsApprovalDetails', 'ClinicalTrialController@getClinicalEthicsApprovalDetails');
//     Route::get('getClinicaltrailSponsorsData', 'ClinicalTrialController@getClinicaltrailSponsorsData');
//     Route::get('getClinicalContactPersonsDetails', 'ClinicalTrialController@getClinicalContactPersonsDetails');
    
    
//     Route::get('getOnlineClinicalStudySites', 'ClinicalTrialController@getOnlineClinicalStudySites');
//     Route::get('getOnlineClinicalTrialOtherInvestigators', 'ClinicalTrialController@getOnlineClinicalTrialOtherInvestigators');
//     Route::get('getOnlineImpProducts', 'ClinicalTrialController@getOnlineImpProducts');
//     Route::get('getOnlineImpProductIngredients', 'ClinicalTrialController@getOnlineImpProductIngredients');
//     Route::get('getClinicalTrialCompareDetails', 'ClinicalTrialController@getClinicalTrialCompareDetails');
//     Route::get('getDismissedClinicalTrialApplications', 'ClinicalTrialController@getDismissedClinicalTrialApplications');

//     Route::get('getOnlineClinicalTrialMonitors', 'ClinicalTrialController@getOnlineClinicalTrialMonitors');
//     Route::get('prepareOnlineClinicalTrialProgressRptPreview', 'ClinicalTrialController@prepareOnlineClinicalTrialProgressRptPreview');

//     Route::get('getClinicalTrialCtrgcpInspectionscheduleDetails', 'ClinicalTrialController@getClinicalTrialCtrgcpInspectionscheduleDetails');

//     Route::post('saveGcpScheduleInspectionDetails', 'ClinicalTrialController@saveGcpScheduleInspectionDetails');

//     Route::get('prepareClinicalTrialGCPInspectionStage', 'ClinicalTrialController@prepareClinicalTrialGCPInspectionStage');
//     Route::post('saveGcpClinicalApplicationdetails', 'ClinicalTrialController@saveGcpClinicalApplicationdetails');
//     Route::get('getClinicalTrialCtrgcpInspectionDetails', 'ClinicalTrialController@getClinicalTrialCtrgcpInspectionDetails');
//     Route::get('getGCPInspectionRecommendationDetails', 'ClinicalTrialController@getGCPInspectionRecommendationDetails');

//     Route::post('saveGcpInspectionRecommendation', 'ClinicalTrialController@saveGcpInspectionRecommendation');
//     Route::post('saveGcpInspectionApproval', 'ClinicalTrialController@saveGcpInspectionApproval');
//     Route::get('getAllClinicalTrialApplications', 'ClinicalTrialController@getAllClinicalTrialApplications');
//     Route::get('getApprovedClinicalTrialApplications', 'ClinicalTrialController@getApprovedClinicalTrialApplications');

//     Route::post('saveEditAppBaseDetails', 'ClinicalTrialController@saveEditAppBaseDetails');
 
//     Route::post('save_clinicalregdetails', 'ClinicalTrialController@save_clinicalregdetails');
//     Route::post('save_clinicalseconaryids', 'ClinicalTrialController@save_clinicalseconaryids');
//     Route::post('save_clinicalstudyDesign', 'ClinicalTrialController@save_clinicalstudyDesign');
//     Route::post('save_clinicaleligibilitycriteria', 'ClinicalTrialController@save_clinicaleligibilitycriteria');
//     Route::post('save_clinicalfundingsource', 'ClinicalTrialController@save_clinicalfundingsource');
//     Route::post('save_clinicalcollaborators', 'ClinicalTrialController@save_clinicalcollaborators');
 
//     Route::post('onSaveInterventionDetails', 'ClinicalTrialController@onSaveInterventionDetails');
//     Route::post('onSaveOutcomesDetails', 'ClinicalTrialController@onSaveOutcomesDetails');
//     Route::post('onSaverecruitmentCenter', 'ClinicalTrialController@onSaverecruitmentCenter');
//     Route::post('onsaveclinicaltSponsorDetails', 'ClinicalTrialController@onsaveclinicaltSponsorDetails');
//     Route::post('onSaveContactPersonDetails', 'ClinicalTrialController@onSaveContactPersonDetails');
//     Route::post('onSaveethicsApproval', 'ClinicalTrialController@onSaveethicsApproval');
//     Route::get('getPharmacoVigilanceApps', 'ClinicalTrialController@getClinicalTrialApplications');
 
    
// });
