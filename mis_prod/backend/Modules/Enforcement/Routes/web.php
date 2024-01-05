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
use Modules\Enforcement\Http\Controllers\EnforcementController;

Route::group(['middleware' => 'web', 'prefix' => 'enforcement'], function () {
    Route::get('/', 'EnforcementController@index');
});
Route::group(['middleware' => 'web', 'prefix' => 'enforcement'], function () {
    Route::get('getEnforcementApplications', 'EnforcementController@getEnforcementApplications');
    Route::get('getusers', 'EnforcementController@getusers');
    Route::post('saveNewEnforcementReceivingDetails', 'EnforcementController@saveNewEnforcementReceivingDetails');
    Route::get('prepareNewReportReceiving', 'EnforcementController@prepareNewReportReceiving');
    Route::get('prepareNewInvestigationReceiving', 'EnforcementController@prepareNewInvestigationReceiving');
    Route::get('prepareMonitoringComplianceDetails', 'EnforcementController@prepareMonitoringComplianceDetails');
    Route::post('onEnforcementDecision', 'EnforcementController@onEnforcementDecision');
    Route::get('getMonitoringApplications', 'EnforcementController@getMonitoringApplications');
    Route::get('getMonitoringOfficerReview', 'EnforcementController@getMonitoringOfficerReview');
    Route::get('getEnforcementActionApplications', 'EnforcementController@getEnforcementActionApplications');
    Route::post('saveMonitoringProductInformation', 'EnforcementController@saveMonitoringProductInformation');
    Route::post('saveMonitoringPremisePersonnel', 'EnforcementController@saveMonitoringPremisePersonnel');
    Route::get('getMonitoringProductInformation', 'EnforcementController@getMonitoringProductInformation');
    Route::get('getPremiseResponsibleProfessional', 'EnforcementController@getPremiseResponsibleProfessional');
    Route::get('getCompliantRegisterApplications', 'EnforcementController@getCompliantRegisterApplications');
    Route::post('saveEnforcementActionRecommendationDetails', 'EnforcementController@saveEnforcementActionRecommendationDetails');
    Route::get('getMonitoringPendingCases', 'EnforcementController@getMonitoringPendingCases');
    Route::post('saveMonitoringRecommendationDetails', 'EnforcementController@saveMonitoringRecommendationDetails');

    Route::get('prepareHealthAssesmentDetails', 'EnforcementController@prepareHealthAssesmentDetails');
    Route::get('getStageEnforcementApplications', 'EnforcementController@getStageEnforcementApplications');
    Route::post('saveUpdateSuspectedOffence', 'EnforcementController@saveUpdateSuspectedOffence');
    Route::get('getSuspectedOffenceDetails', 'EnforcementController@getSuspectedOffenceDetails');
    Route::post('genericDeleteRecord', 'EnforcementController@genericDeleteRecord');
    Route::post('onSaveInvestigationDecision', 'EnforcementController@onSaveInvestigationDecision');
    Route::get('getEnforcementApplicationMoreDetails', 'EnforcementController@getEnforcementApplicationMoreDetails');
    Route::get('getMonitoringApplicationMoreDetails', 'EnforcementController@getMonitoringApplicationMoreDetails');
    Route::get('getMonitoringComplianceData', 'EnforcementController@getMonitoringComplianceData');
    Route::post('savePrescribingComplianceDetails', 'EnforcementController@savePrescribingComplianceDetails');
    Route::post('saveDispensingComplianceDetails', 'EnforcementController@saveDispensingComplianceDetails');
    Route::get('getManagerInvestigationApplications', 'EnforcementController@getManagerInvestigationApplications');
    Route::get('getManagerEvaluationApplications', 'EnforcementController@getManagerEvaluationApplications');
    Route::get('getApprovedInvestigationApplications', 'EnforcementController@getApprovedInvestigationApplications');
    Route::get('onApprovedInvestigationDetails', 'EnforcementController@onApprovedInvestigationDetails');
    Route::post('saveInvetsigationReceivingDetails', 'EnforcementController@saveInvetsigationReceivingDetails');
    Route::get('getWorkplanApplications', 'EnforcementController@getWorkplanApplications');
    Route::get('prepareWorkplan', 'EnforcementController@prepareWorkplan');
    Route::post('saveWorkplanApplicationDetails', 'EnforcementController@saveWorkplanApplicationDetails');
    Route::post('saveCaseCharges', 'EnforcementController@saveCaseCharges');
    Route::post('saveCaseWitnessDetails', 'EnforcementController@saveCaseWitnessDetails');
    Route::get('getCaseCharges', 'EnforcementController@getCaseCharges');
    Route::get('getCaseWitnessDetails', 'EnforcementController@getCaseWitnessDetails');
    Route::post('saveInvestigationDairy', 'EnforcementController@saveInvestigationDairy');
    Route::get('getInvestigationDiaryDetails', 'EnforcementController@getInvestigationDiaryDetails');
    Route::get('prepareEnforcementRegMeetingStage', 'EnforcementController@prepareEnforcementRegMeetingStage');
    Route::post('saveTCMeetingDetails', 'EnforcementController@saveTCMeetingDetails');
    Route::get('getApprovedApplication', 'EnforcementController@getApprovedApplication');
    Route::get('getRegisteredProduct', 'EnforcementController@getRegisteredProduct');
    Route::get('getApprovedPremises', 'EnforcementController@getApprovedPremises');
    Route::post('saveCaseDecisions', 'EnforcementController@saveCaseDecisions');
    Route::get('getCaseComments', 'EnforcementController@getCaseComments');
    Route::post('saveUpdateAnnualWorkplanDetails', 'EnforcementController@saveUpdateAnnualWorkplanDetails');
    Route::get('getAnnualWorkplanDetails', 'EnforcementController@getAnnualWorkplanDetails');
    Route::get('getCaseOffenceCharges', 'EnforcementController@getCaseOffenceCharges');
    Route::post('saveSeizureInspectionDetails', 'EnforcementController@saveSeizureInspectionDetails');
    Route::post('onSaveSeizureConfirmation', 'EnforcementController@onSaveSeizureConfirmation');
    Route::post('saveSeizureWitnessDetails', 'EnforcementController@saveSeizureWitnessDetails');
    Route::get('getSeizureWitnessDetails', 'EnforcementController@getSeizureWitnessDetails');
    Route::post('onSaveProductReleaseConfirmation', 'EnforcementController@onSaveProductReleaseConfirmation');
    Route::post('saveExhibitRequisitionDetails', 'EnforcementController@saveExhibitRequisitionDetails');
    Route::get('prepareExhibitRequest', 'EnforcementController@prepareExhibitRequest');
    Route::post('onSaveExhibitionRequestApproval', 'EnforcementController@onSaveExhibitionRequestApproval');
    Route::get('prepareInvestigationDairy', 'EnforcementController@prepareInvestigationDairy');
    Route::get('getAnnualWorkplanDetails', 'EnforcementController@getAnnualWorkplanDetails');    
    Route::get('getMonitoringComplianceApplications', 'EnforcementController@getMonitoringComplianceApplications');
    Route::get('getPrescribingComplianceInformation', 'EnforcementController@getPrescribingComplianceInformation');
    Route::get('savePrescribingComplianceInformation', 'EnforcementController@savePrescribingComplianceInformation');
    Route::get('getDispensingComplianceInformation', 'EnforcementController@getDispensingComplianceInformation');
    Route::get('getControlledDispensingInformation', 'EnforcementController@getControlledDispensingInformation');
    Route::get('getMonitoringPremisePeronnel', 'EnforcementController@getMonitoringPremisePeronnel');
    Route::get('MystreyShoppingEvaluationApplications', 'EnforcementController@MystreyShoppingEvaluationApplications');
    Route::get('getMystreyShoppingApplications', 'EnforcementController@getMystreyShoppingApplications');
    Route::get('onMonitoringComplianceDetails', 'EnforcementController@onMonitoringComplianceDetails');
    Route::post('saveMonitoringReceivingDetails', 'EnforcementController@saveMonitoringReceivingDetails');
    Route::get('getMystreyShoppingLogApplication', 'EnforcementController@getMystreyShoppingLogApplication');
    Route::get('getMonitoringRecommendationLogs', 'EnforcementController@getMonitoringRecommendationLogs');
    Route::get('getPeerRecommendationLogs', 'EnforcementController@getPeerRecommendationLogs');
    Route::post('genericSaveUpdate', 'EnforcementController@genericSaveUpdate');
    Route::get('getseizureProductsListGrid', 'EnforcementController@getseizureProductsListGrid');
    Route::post('saveSeizureProductDetails', 'EnforcementController@saveSeizureProductDetails');
    Route::post('saveSeizureWorkPlanDetails', 'EnforcementController@saveSeizureWorkPlanDetails');
    Route::get('prepareSeizureWorkPlan', 'EnforcementController@prepareSeizureWorkPlan');
    Route::get('prepareSeizurePlanDetails', 'EnforcementController@prepareSeizurePlanDetails');
    Route::post('saveDestructionPlanDetails', 'EnforcementController@saveDestructionPlanDetails');
    Route::get('prepareProductDestruction', 'EnforcementController@prepareProductDestruction');
    Route::post('onSaveDestructionConfirmation', 'EnforcementController@onSaveDestructionConfirmation');
    Route::get('getJointOperationsApplications', 'EnforcementController@getJointOperationsApplications');
    Route::post('saveJointOperationsWorkPlanDetails', 'EnforcementController@saveJointOperationsWorkPlanDetails');
    Route::post('saveNewDairyitem', 'EnforcementController@saveNewDairyitem');
    Route::get('getNewDiaryItem', 'EnforcementController@getNewDiaryItem');
    Route::post('saveTimelineDetails', 'EnforcementController@saveTimelineDetails');
    Route::get('getTimelineDetails', 'EnforcementController@getTimelineDetails');
    Route::get('getRegisteredCases', 'EnforcementController@getRegisteredCases');
    Route::post('saveNewWorkPlanDetails', 'EnforcementController@saveNewWorkPlanDetails');
    Route::get('getCaseOffences', 'EnforcementController@getCaseOffences');
    Route::post('onSaveManagerApprovalDecisions', 'EnforcementController@onSaveManagerApprovalDecisions');
    Route::get('getJointOperativeActivities', 'EnforcementController@getJointOperativeActivities');
    Route::get('getJointOperationLogistics', 'EnforcementController@getJointOperationLogistics');
    Route::post('saveJointOperationReceivingDetails', 'EnforcementController@saveJointOperationReceivingDetails');
    Route::get('prepareJointOperationReceiving', 'EnforcementController@prepareJointOperationReceiving');
    Route::post('saveActivityDetails', 'EnforcementController@saveActivityDetails');
    Route::get('getApprovedJointOperations', 'EnforcementController@getApprovedJointOperations');
    Route::post('saveJointDetectedOffence', 'EnforcementController@saveJointDetectedOffence');
    Route::get('getjointOperationOffenceDetails', 'EnforcementController@getjointOperationOffenceDetails');
    Route::post('saveOperatives', 'EnforcementController@saveOperatives');
    Route::get('getOperatives', 'EnforcementController@getOperatives');
    Route::post('saveOperationSummary', 'EnforcementController@saveOperationSummary');
    Route::get('getOperationSummary', 'EnforcementController@getOperationSummary');
    Route::post('saveJointProducts', 'EnforcementController@saveJointProducts');
    Route::get('getjointSeizedProducts', 'EnforcementController@getjointSeizedProducts');
    Route::get('getManufacturingSites', 'EnforcementController@getManufacturingSites');
    Route::post('saveInquiryDetails', 'EnforcementController@saveInquiryDetails');
    Route::get('getInquiryDetails', 'EnforcementController@getInquiryDetails');
    Route::post('saveInvestigationComments', 'EnforcementController@saveInvestigationComments');
    Route::get('getInvestigationsComments', 'EnforcementController@getInvestigationsComments');
    Route::get('getStageJointOperationsApplications', 'EnforcementController@getStageJointOperationsApplications');
    Route::get('getOfficerAssignedActivity', 'EnforcementController@getOfficerAssignedActivity');
    Route::get('getJointOperationDetectedFacilities', 'EnforcementController@getJointOperationDetectedFacilities');
    Route::get('submitPlannedActivities', 'EnforcementController@submitPlannedActivities');   
    Route::get('submitJointReportsForInvestigation', 'EnforcementController@submitJointReportsForInvestigation'); 
    Route::post('saveOfficerSignatureDetails', 'EnforcementController@saveOfficerSignatureDetails');
    Route::get('submitApplicationForInvestigation', 'EnforcementController@submitApplicationForInvestigation');
    Route::get('getJointReportInvestigationMoreDetails', 'EnforcementController@getJointReportInvestigationMoreDetails');
    Route::get('getjointInvestigationReportedProducts', 'EnforcementController@getjointInvestigationReportedProducts');
    Route::get('prepareExecutionDetails', 'EnforcementController@prepareExecutionDetails');
    Route::post('saveMonitoringPlanRegions', 'EnforcementController@saveMonitoringPlanRegions');
    Route::get('getMonitoringPlanFacilityInformation', 'EnforcementController@getMonitoringPlanFacilityInformation');
    Route::get('getMonitoringPlanInspectorsInformation', 'EnforcementController@getMonitoringPlanInspectorsInformation');
    Route::get('getMonitoringPlanRegionsInformation', 'EnforcementController@getMonitoringPlanRegionsInformation');
    Route::get('listMonitoringComplianceFacilities', 'EnforcementController@listMonitoringComplianceFacilities');
    
});
