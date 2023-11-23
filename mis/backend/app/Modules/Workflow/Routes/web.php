<?php

use App\Modules\Workflow\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group( function () {
    Route::prefix('workflow')->group(function () {
        Route::controller(WorkflowController::class)->group(function () {
            Route::get('getProcessApplicableChecklistTypes', 'getProcessApplicableChecklistTypes');
            Route::get('getProcessApplicableChecklistItems', 'getProcessApplicableChecklistItems');
        Route::get('getChecklistQueriesApplicableChecklistItems', 'getChecklistQueriesApplicableChecklistItems');
        
            
            Route::post('saveApplicationDataAmmendmentRequest', 'saveApplicationDataAmmendmentRequest');
        
    
        });

    });
});




Route::middleware(['web'])->group( function () {
    Route::prefix('workflow')->group(function () {
        Route::controller(WorkflowController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('saveWorkflowCommonData', 'saveWorkflowCommonData');
            Route::get('getWorkflowParamFromModel', 'getWorkflowParamFromModel');
            Route::get('getTfdaSystemProcesses', 'getTfdaSystemProcesses');
            Route::post('softDeleteWorkflowRecord', 'softDeleteWorkflowRecord');
            Route::post('undoWorkflowSoftDeletes', 'undoWorkflowSoftDeletes');
            Route::post('deleteWorkflowRecord', 'deleteWorkflowRecord');
            Route::get('getWorkflowStages', 'getWorkflowStages');
            Route::get('getWorkflowActions', 'getWorkflowActions');
            Route::get('getWorkflowTransitions', 'getWorkflowTransitions');
            Route::post('saveWorkflowStage', 'saveWorkflowStage');
            Route::post('saveWorkflowTransition', 'saveWorkflowTransition');
            Route::get('getMenuWorkflowLinkages', 'getMenuWorkflowLinkages');
            Route::post('saveMenuWorkflowLinkage', 'saveMenuWorkflowLinkage');
            Route::post('saveMenuWorkFlowsLinkage', 'saveMenuWorkFlowsLinkage');
            Route::get('showWorkflowDiagram', 'showWorkflowDiagram');
            Route::post('deleteMenuWorkflowLinkage', 'deleteMenuWorkflowLinkage');
            Route::post('deleteMenuWorkFlowsLinkage', 'deleteMenuWorkFlowsLinkage');
            Route::get('getBasicWorkflowDetails', 'getBasicWorkflowDetails');
            Route::get('getInitialWorkflowDetails', 'getInitialWorkflowDetails');
            Route::get('getAllWorkflowDetails', 'getAllWorkflowDetails');
            Route::get('getApplicationSubmissionDetails', 'getApplicationSubmissionDetails');
            Route::get('getProcessWorkflowStages', 'getProcessWorkflowStages');
            Route::get('getMenuWorkFlowsLinkages', 'getMenuWorkFlowsLinkages');
            Route::get('getStageGroups', 'getStageGroups');
            Route::get('getSystemSubModules', 'getSystemSubModules');
            Route::get('getProcessApplicableChecklistCategories', 'getProcessApplicableChecklistCategories');
            Route::get('getProcessEditableFormFields', 'getProcessEditableFormFields');
            Route::get('getProcessEditableOtherParts', 'getProcessEditableOtherParts');
            //Route::get('getProcessApplicableChecklistTypes', 'getProcessApplicableChecklistTypes');
            //Route::get('getProcessApplicableChecklistItems', 'getProcessApplicableChecklistItems');
            Route::post('syncProcessApplicableChecklistCategories', 'syncProcessApplicableChecklistCategories');
            Route::post('syncProcessAmendableParts', 'syncProcessAmendableParts');
            Route::get('getWorkflowStageResponsibleGroups', 'getWorkflowStageResponsibleGroups');
            Route::get('getSubmissionWorkflowStages', 'getSubmissionWorkflowStages');
            Route::get('getSubmissionNextStageDetails', 'getSubmissionNextStageDetails');
            Route::get('getSubmissionResponsibleUsers', 'getSubmissionResponsibleUsers');
            Route::get('getPremiseInspectionSubmissionResponsibleUsers', 'getPremiseInspectionSubmissionResponsibleUsers');
            Route::get('getWorkflowStagePossibleResponsibleGroups', 'getWorkflowStagePossibleResponsibleGroups');
            Route::post('syncWorkflowStageResponsibleGroups', 'syncWorkflowStageResponsibleGroups');
            Route::get('getWorkflowAssociatedMenus', 'getWorkflowAssociatedMenus');
            Route::post('handleApplicationSubmission', 'handleApplicationSubmission');
            Route::post('handleManagersApplicationSubmissions', 'handleManagersApplicationSubmissions');
            Route::post('submitApplication', 'submitApplication');
            Route::post('submitManagerApplicationsGeneric', 'submitManagerApplicationsGeneric');
            Route::post('submitApplicationReceiving', 'submitApplicationReceiving');
            Route::post('updateInTrayReading', 'updateInTrayReading');
            Route::get('getSubmissionRecommendations', 'getSubmissionRecommendations');
            Route::get('getApplicationStatuses', 'getApplicationStatuses');
            Route::get('getApplicationReturnDirectives', 'getApplicationReturnDirectives');
            Route::get('getApplicationTransitioning', 'getApplicationTransitioning');
            Route::get('getFormFieldsAuth', 'getFormFieldsAuth');
            Route::get('getProcessOtherPartsAuth', 'getProcessOtherPartsAuth');
            Route::get('getAlterationFormFieldsAuth', 'getAlterationFormFieldsAuth');
            Route::get('getAlterationOtherPartsAuth', 'getAlterationOtherPartsAuth');
            Route::get('getOnlineApplicationSubmissionDetails', 'getOnlineApplicationSubmissionDetails');
            Route::get('getApplicationAlterationFormFields', 'getApplicationAlterationFormFields');
            Route::get('getApplicationAlterationOtherParams', 'getApplicationAlterationOtherParams');
            Route::get('getApplicationAlterationForms', 'getApplicationAlterationForms');
            Route::post('receiveOnlineApplicationDetails', 'receiveOnlineApplicationDetails');
            Route::post('receiveMultipleOnlineApplicationDetails', 'receiveMultipleOnlineApplicationDetails');
            Route::post('getModulesTableData', 'getModulesTableData');
            Route::get('getProcessApplicableDocumentTypes', 'getProcessApplicableDocumentTypes');
            Route::post('syncProcessApplicableDocumentTypes', 'syncProcessApplicableDocumentTypes');
            Route::get('getPortalApplicationStatuses', 'getPortalApplicationStatuses');
            Route::get('getWorkflowDetails', 'getWorkflowDetails');
            Route::get('getWorkflowInterfacedetails', 'getWorkflowInterfacedetails');
            Route::get('getApplicationSubmissionDetailsFromSubmissionsTable', 'getApplicationSubmissionDetailsFromSubmissionsTable');
            Route::get('getOnlineProcessApplicableChecklistTypes', 'getOnlineProcessApplicableChecklistTypes');
            Route::get('getOnlineProcessApplicableChecklistItems', 'getOnlineProcessApplicableChecklistItems');
        
            Route::get('getInitialWorkflowDetailsNoProcess', 'getInitialWorkflowDetailsNoProcess');
             Route::get('getApplicableChecklistItemsHistory', 'getApplicableChecklistItemsHistory');
             
             Route::get('getRevenueApplicationSubmissionDetails', 'getRevenueApplicationSubmissionDetails');
             Route::get('getRevProcessSubmissionWorkflowStages', 'getRevProcessSubmissionWorkflowStages');
             Route::post('handleRevenueRequestApplicationSubmission', 'handleRevenueRequestApplicationSubmission');
             
             Route::post('unlinkWorkflowRecord', 'unlinkWorkflowRecord');
             
             Route::post('saveonlineapplicationreceiceinvoiceDetails', 'saveonlineapplicationreceiceinvoiceDetails');
             
             Route::get('getSubmissionWorkflowStages', 'getSubmissionWorkflowStages');
        
        
             //Route::get('getAllWorkflow', 'getAllWorkflow');
        
             Route::get('getGroupMappedWorkflowStages', 'getGroupMappedWorkflowStages');
        
        
             Route::post('saveRegistrationCancellationRequest', 'saveRegistrationCancellationRequest');
             Route::get('getCancelledRegistrationApplications', 'getCancelledRegistrationApplications');
             Route::get('getCancelledRegistrationApplicationDetails', 'getCancelledRegistrationApplicationDetails');
             Route::post('RevertRegistrationCancellation', 'RevertRegistrationCancellation');
        
             Route::get('getChecklistRevisionLogs', 'getChecklistRevisionLogs');
        Route::post('getApplicationNextStageActionDetails', 'getApplicationNextStageActionDetails');
        
    
        });

    });
});


Route::group(['middleware' => 'auth:api', 'prefix' => 'workflow', 'namespace' => 'App\\Modules\Workflow\Http\Controllers'], function () {
    Route::get('getProcessApplicableChecklistTypes', 'WorkflowController@getProcessApplicableChecklistTypes');
    Route::get('getProcessApplicableChecklistItems', 'WorkflowController@getProcessApplicableChecklistItems');
Route::get('getChecklistQueriesApplicableChecklistItems', 'WorkflowController@getChecklistQueriesApplicableChecklistItems');

    
    Route::post('saveApplicationDataAmmendmentRequest', 'WorkflowController@saveApplicationDataAmmendmentRequest');
     
});


Route::group(['middleware' => 'web', 'prefix' => 'workflow', 'namespace' => 'App\\Modules\Workflow\Http\Controllers'], function () {
    Route::get('/', 'WorkflowController@index');
    Route::post('saveWorkflowCommonData', 'WorkflowController@saveWorkflowCommonData');
    Route::get('getWorkflowParamFromModel', 'WorkflowController@getWorkflowParamFromModel');
    Route::get('getTfdaSystemProcesses', 'WorkflowController@getTfdaSystemProcesses');
    Route::post('softDeleteWorkflowRecord', 'WorkflowController@softDeleteWorkflowRecord');
    Route::post('undoWorkflowSoftDeletes', 'WorkflowController@undoWorkflowSoftDeletes');
    Route::post('deleteWorkflowRecord', 'WorkflowController@deleteWorkflowRecord');
    Route::get('getWorkflowStages', 'WorkflowController@getWorkflowStages');
    Route::get('getWorkflowActions', 'WorkflowController@getWorkflowActions');
    Route::get('getWorkflowTransitions', 'WorkflowController@getWorkflowTransitions');
    Route::post('saveWorkflowStage', 'WorkflowController@saveWorkflowStage');
    Route::post('saveWorkflowTransition', 'WorkflowController@saveWorkflowTransition');
    Route::get('getMenuWorkflowLinkages', 'WorkflowController@getMenuWorkflowLinkages');
    Route::post('saveMenuWorkflowLinkage', 'WorkflowController@saveMenuWorkflowLinkage');
    Route::post('saveMenuWorkFlowsLinkage', 'WorkflowController@saveMenuWorkFlowsLinkage');
    Route::get('showWorkflowDiagram', 'WorkflowController@showWorkflowDiagram');
    Route::post('deleteMenuWorkflowLinkage', 'WorkflowController@deleteMenuWorkflowLinkage');
    Route::post('deleteMenuWorkFlowsLinkage', 'WorkflowController@deleteMenuWorkFlowsLinkage');
    Route::get('getBasicWorkflowDetails', 'WorkflowController@getBasicWorkflowDetails');
    Route::get('getInitialWorkflowDetails', 'WorkflowController@getInitialWorkflowDetails');
    Route::get('getAllWorkflowDetails', 'WorkflowController@getAllWorkflowDetails');
    Route::get('getApplicationSubmissionDetails', 'WorkflowController@getApplicationSubmissionDetails');
    Route::get('getProcessWorkflowStages', 'WorkflowController@getProcessWorkflowStages');
    Route::get('getMenuWorkFlowsLinkages', 'WorkflowController@getMenuWorkFlowsLinkages');
    Route::get('getStageGroups', 'WorkflowController@getStageGroups');
    Route::get('getSystemSubModules', 'WorkflowController@getSystemSubModules');
    Route::get('getProcessApplicableChecklistCategories', 'WorkflowController@getProcessApplicableChecklistCategories');
    Route::get('getProcessEditableFormFields', 'WorkflowController@getProcessEditableFormFields');
    Route::get('getProcessEditableOtherParts', 'WorkflowController@getProcessEditableOtherParts');
    //Route::get('getProcessApplicableChecklistTypes', 'WorkflowController@getProcessApplicableChecklistTypes');
    //Route::get('getProcessApplicableChecklistItems', 'WorkflowController@getProcessApplicableChecklistItems');
    Route::post('syncProcessApplicableChecklistCategories', 'WorkflowController@syncProcessApplicableChecklistCategories');
    Route::post('syncProcessAmendableParts', 'WorkflowController@syncProcessAmendableParts');
    Route::get('getWorkflowStageResponsibleGroups', 'WorkflowController@getWorkflowStageResponsibleGroups');
    Route::get('getSubmissionWorkflowStages', 'WorkflowController@getSubmissionWorkflowStages');
    Route::get('getSubmissionNextStageDetails', 'WorkflowController@getSubmissionNextStageDetails');
    Route::get('getSubmissionResponsibleUsers', 'WorkflowController@getSubmissionResponsibleUsers');
    Route::get('getPremiseInspectionSubmissionResponsibleUsers', 'WorkflowController@getPremiseInspectionSubmissionResponsibleUsers');
    Route::get('getWorkflowStagePossibleResponsibleGroups', 'WorkflowController@getWorkflowStagePossibleResponsibleGroups');
    Route::post('syncWorkflowStageResponsibleGroups', 'WorkflowController@syncWorkflowStageResponsibleGroups');
    Route::get('getWorkflowAssociatedMenus', 'WorkflowController@getWorkflowAssociatedMenus');
    Route::post('handleApplicationSubmission', 'WorkflowController@handleApplicationSubmission');
    Route::post('handleManagersApplicationSubmissions', 'WorkflowController@handleManagersApplicationSubmissions');
    Route::post('submitApplication', 'WorkflowController@submitApplication');
    Route::post('submitManagerApplicationsGeneric', 'WorkflowController@submitManagerApplicationsGeneric');
    Route::post('submitApplicationReceiving', 'WorkflowController@submitApplicationReceiving');
    Route::post('updateInTrayReading', 'WorkflowController@updateInTrayReading');
    Route::get('getSubmissionRecommendations', 'WorkflowController@getSubmissionRecommendations');
    Route::get('getApplicationStatuses', 'WorkflowController@getApplicationStatuses');
    Route::get('getApplicationReturnDirectives', 'WorkflowController@getApplicationReturnDirectives');
    Route::get('getApplicationTransitioning', 'WorkflowController@getApplicationTransitioning');
    Route::get('getFormFieldsAuth', 'WorkflowController@getFormFieldsAuth');
    Route::get('getProcessOtherPartsAuth', 'WorkflowController@getProcessOtherPartsAuth');
    Route::get('getAlterationFormFieldsAuth', 'WorkflowController@getAlterationFormFieldsAuth');
    Route::get('getAlterationOtherPartsAuth', 'WorkflowController@getAlterationOtherPartsAuth');
    Route::get('getOnlineApplicationSubmissionDetails', 'WorkflowController@getOnlineApplicationSubmissionDetails');
    Route::get('getApplicationAlterationFormFields', 'WorkflowController@getApplicationAlterationFormFields');
    Route::get('getApplicationAlterationOtherParams', 'WorkflowController@getApplicationAlterationOtherParams');
    Route::get('getApplicationAlterationForms', 'WorkflowController@getApplicationAlterationForms');
    Route::post('receiveOnlineApplicationDetails', 'WorkflowController@receiveOnlineApplicationDetails');
    Route::post('receiveMultipleOnlineApplicationDetails', 'WorkflowController@receiveMultipleOnlineApplicationDetails');
    Route::post('getModulesTableData', 'WorkflowController@getModulesTableData');
    Route::get('getProcessApplicableDocumentTypes', 'WorkflowController@getProcessApplicableDocumentTypes');
    Route::post('syncProcessApplicableDocumentTypes', 'WorkflowController@syncProcessApplicableDocumentTypes');
    Route::get('getPortalApplicationStatuses', 'WorkflowController@getPortalApplicationStatuses');
    Route::get('getWorkflowDetails', 'WorkflowController@getWorkflowDetails');
    Route::get('getWorkflowInterfacedetails', 'WorkflowController@getWorkflowInterfacedetails');
    Route::get('getApplicationSubmissionDetailsFromSubmissionsTable', 'WorkflowController@getApplicationSubmissionDetailsFromSubmissionsTable');
    Route::get('getOnlineProcessApplicableChecklistTypes', 'WorkflowController@getOnlineProcessApplicableChecklistTypes');
    Route::get('getOnlineProcessApplicableChecklistItems', 'WorkflowController@getOnlineProcessApplicableChecklistItems');

    Route::get('getInitialWorkflowDetailsNoProcess', 'WorkflowController@getInitialWorkflowDetailsNoProcess');
     Route::get('getApplicableChecklistItemsHistory', 'WorkflowController@getApplicableChecklistItemsHistory');
     
     Route::get('getRevenueApplicationSubmissionDetails', 'WorkflowController@getRevenueApplicationSubmissionDetails');
     Route::get('getRevProcessSubmissionWorkflowStages', 'WorkflowController@getRevProcessSubmissionWorkflowStages');
     Route::post('handleRevenueRequestApplicationSubmission', 'WorkflowController@handleRevenueRequestApplicationSubmission');
     
     Route::post('unlinkWorkflowRecord', 'WorkflowController@unlinkWorkflowRecord');
     
     Route::post('saveonlineapplicationreceiceinvoiceDetails', 'WorkflowController@saveonlineapplicationreceiceinvoiceDetails');
     
     Route::get('getSubmissionWorkflowStages', 'WorkflowController@getSubmissionWorkflowStages');


     //Route::get('getAllWorkflow', 'WorkflowController@getAllWorkflow');

     Route::get('getGroupMappedWorkflowStages', 'WorkflowController@getGroupMappedWorkflowStages');


     Route::post('saveRegistrationCancellationRequest', 'WorkflowController@saveRegistrationCancellationRequest');
     Route::get('getCancelledRegistrationApplications', 'WorkflowController@getCancelledRegistrationApplications');
     Route::get('getCancelledRegistrationApplicationDetails', 'WorkflowController@getCancelledRegistrationApplicationDetails');
     Route::post('RevertRegistrationCancellation', 'WorkflowController@RevertRegistrationCancellation');

     Route::get('getChecklistRevisionLogs', 'WorkflowController@getChecklistRevisionLogs');
Route::post('getApplicationNextStageActionDetails', 'WorkflowController@getApplicationNextStageActionDetails');

     
     
     
});
