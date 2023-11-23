<?php
//'middleware' => 'auth:integration',  'middleware' => 'auth:integration',

use App\Modules\APIIntegrations\Http\Controllers\APIIntegrationsController;
use App\Modules\APIIntegrations\Http\Controllers\AuthenticationController;
use App\Modules\APIIntegrations\Http\Controllers\EACHarmonizationController;
use App\Modules\APIIntegrations\Http\Controllers\IremboPaymentsIntController;
use App\Modules\APIIntegrations\Http\Controllers\PaymentsIntegrationController;
use App\Modules\APIIntegrations\Http\Controllers\RRAPaymentsIntController;
use Illuminate\Support\Facades\Route;

    Route::prefix('api/v2/eacintegrations')->group(function () {
		Route::get('/',[APIIntegrationsController::class,"index"]);
        Route::controller(EACHarmonizationController::class)->group(function () {
			Route::get('getLtrsDetails', 'getLTRTradersWithLimit');
			Route::get('getLtrDetails/{id}', 'showLTR');
			//post 
			Route::post('postDrugApplicationDetailsSrv', 'postDrugApplicationDetailsSrv');
		
			Route::post('createInvoiceService', 'funcCreateInvoiceService');
		
			Route::get('getInvoiceService', 'funcGetInvoiceService');
			Route::get('getPaymentService', 'funcGetPaymentService');
			//Sample Submission Service
			Route::post('postSampleSubmissionService', 'postSampleSubmissionService');
			
			Route::post('postAssessmentSchedulesNotification', 'postAssessmentSchedulesNotification');
			Route::post('postAssessmentSchedulesNotification', 'postAssessmentSchedulesNotification');
			//Evaluation Report Pull Service
			Route::get('evaluation', 'getFirstAssessmentReportPullService');
		//	Route::get('getSecondAssessmentReportPullService', 'getSecondAssessmentReportPullService');
			//Plenary Report Service
			Route::post('getPlenaryReportService', 'getPlenaryReportService');
			//approval notificaiton 
			
			Route::get('getMarketAuthorisationService', 'getMarketAuthorisationService');
			Route::get('getStatusChangeNotificationService', 'getStatusChangeNotificationService');
		
		Route::get('getEACJointAssessmentMedicinesSubmissions', 'getEACJointAssessmentMedicinesSubmissions');
			Route::get('getEACJointAssessmentMedicinesSubmissions', 'getEACJointAssessmentMedicinesSubmissions');
			
				Route::get('getMalRecallInformationSharingSrv', 'getMalRecallInformationSharingSrv');
					Route::get('getMalInformationSharingSrv', 'getMalInformationSharingSrv');
					Route::get('getPremisesInformationSharingSrv', 'getPremisesInformationSharingSrv');
					Route::get('getGmpInformationSharingSrv', 'getGmpInformationSharingSrv');
        });

    });
	Route::prefix('api/info/eacintegrations')->group(function () {
        Route::controller(EACHarmonizationController::class)->group(function () {
            Route::get('getMalRecallInformationSharingSrv', 'getMalRecallInformationSharingSrv');
			Route::get('getMalInformationSharingSrv', 'getMalInformationSharingSrv');
			Route::get('getPremisesInformationSharingSrv', 'getPremisesInformationSharingSrv');
			Route::get('getGmpInformationSharingSrv', 'getGmpInformationSharingSrv');
        });

    });
	Route::prefix('api/v23')->group(function () {
        Route::controller(AuthenticationController::class)->group(function () {
			Route::post('auth','authenticateAPIUser');
			Route::get('logout','logoutAPIUser');
        });

    });
	Route::prefix('api')->group(function () {
        Route::controller(PaymentsIntegrationController::class)->group(function () {
			Route::get('gepgBillSubReq', 'postBillSubmissionRequest');
	
	Route::get('gepgBillCanclReq', 'gepgBillCanclReq');

	Route::post('gepgBillSubResp', 'gepgBillSubResp');
	Route::post('gepgPmtSpInfo', 'gepgPmtSpInfo');
	
	Route::get('gepgReconcReq', 'gepgReconcReq');

	Route::post('gepgReconcResp', 'gepgReconcResp');
	Route::get('submitPaymentNextProcessAutoSubmissions', 'submitPaymentNextProcessAutoSubmissions');
        });

    });

	Route::prefix('api/tesws')->group(function () {
        Route::controller(TeswsIntegrationController::class)->group(function () {
            Route::get('permitApprovalNotification ', 'permitApprovalNotification');
		Route::get('processDeclaredImportExportapps ', 'processDeclaredImportExportapps');
	
        });

    });
	Route::prefix('api/rragateway')->group(function () {
        Route::controller(RRAPaymentsIntController::class)->group(function () {
			Route::get('rraPaymentGatewayAuth', 'rraPaymentGatewayAuth');
			Route::get('rraPaymentGatewayGetDocumentNo', 'rraPaymentGatewayGetDocumentNo');
			Route::get('rraPaymentGatewayGetPayments', 'rraPaymentGatewayGetPayments');
        });

    });
	Route::prefix('api/iremeboypay')->group(function () {
        Route::controller(IremboPaymentsIntController::class)->group(function () {
			Route::get('iremboPaymentGatewayAuth', 'iremboPaymentGatewayAuth');
			Route::get('iremboFuncInvoiceSubmission', 'iremboFuncInvoiceSubmission');
			Route::get('iremboFuncGetInvoiceSubmission', 'iremboFuncGetInvoiceSubmission');
	
			Route::post('iremboGetPaymentNotifications', 'iremboGetPaymentNotifications');
			Route::get('onApplicationInvoicePaymentConfirmation', 'onApplicationInvoicePaymentConfirmation');
			Route::get('onGroupApplicationInvoicePaymentConfirmation', 'onGroupApplicationInvoicePaymentConfirmation');
        });

    });

	

// Route::group([ 'prefix' => 'api/v2/eacintegrations', 'namespace' => 'App\\Modules\APIIntegrations\Http\Controllers'], function()
// {
//     Route::get('/', 'APIIntegrationsController@index');

// 	Route::get('getLtrsDetails', 'EACHarmonizationController@getLTRTradersWithLimit');
// 	Route::get('getLtrDetails/{id}', 'EACHarmonizationController@showLTR');
// 	//post 
// 	Route::post('postDrugApplicationDetailsSrv', 'EACHarmonizationController@postDrugApplicationDetailsSrv');

// 	Route::post('createInvoiceService', 'EACHarmonizationController@funcCreateInvoiceService');

// 	Route::get('getInvoiceService', 'EACHarmonizationController@funcGetInvoiceService');
// 	Route::get('getPaymentService', 'EACHarmonizationController@funcGetPaymentService');
// 	//Sample Submission Service
// 	Route::post('postSampleSubmissionService', 'EACHarmonizationController@postSampleSubmissionService');
	
// 	Route::post('postAssessmentSchedulesNotification', 'EACHarmonizationController@postAssessmentSchedulesNotification');
// 	Route::post('postAssessmentSchedulesNotification', 'EACHarmonizationController@postAssessmentSchedulesNotification');
// 	//Evaluation Report Pull Service
// 	Route::get('evaluation', 'EACHarmonizationController@getFirstAssessmentReportPullService');
// //	Route::get('getSecondAssessmentReportPullService', 'EACHarmonizationController@getSecondAssessmentReportPullService');
// 	//Plenary Report Service
// 	Route::post('getPlenaryReportService', 'EACHarmonizationController@getPlenaryReportService');
// 	//approval notificaiton 
	
// 	Route::get('getMarketAuthorisationService', 'EACHarmonizationController@getMarketAuthorisationService');
// 	Route::get('getStatusChangeNotificationService', 'EACHarmonizationController@getStatusChangeNotificationService');

// Route::get('getEACJointAssessmentMedicinesSubmissions', 'EACHarmonizationController@getEACJointAssessmentMedicinesSubmissions');
// 	Route::get('getEACJointAssessmentMedicinesSubmissions', 'EACHarmonizationController@getEACJointAssessmentMedicinesSubmissions');
	
// 		Route::get('getMalRecallInformationSharingSrv', 'EACHarmonizationController@getMalRecallInformationSharingSrv');
// 			Route::get('getMalInformationSharingSrv', 'EACHarmonizationController@getMalInformationSharingSrv');
// 			Route::get('getPremisesInformationSharingSrv', 'EACHarmonizationController@getPremisesInformationSharingSrv');
// 			Route::get('getGmpInformationSharingSrv', 'EACHarmonizationController@getGmpInformationSharingSrv');
// });

// Route::group([ 'prefix' => 'api/info/eacintegrations', 'namespace' => 'App\\Modules\APIIntegrations\Http\Controllers'], function()
// {
// 			Route::get('getMalRecallInformationSharingSrv', 'EACHarmonizationController@getMalRecallInformationSharingSrv');
// 			Route::get('getMalInformationSharingSrv', 'EACHarmonizationController@getMalInformationSharingSrv');
// 			Route::get('getPremisesInformationSharingSrv', 'EACHarmonizationController@getPremisesInformationSharingSrv');
// 			Route::get('getGmpInformationSharingSrv', 'EACHarmonizationController@getGmpInformationSharingSrv');

// });

//  Route::group(['prefix' => 'api/v23', 'namespace' => 'App\\Modules\APIIntegrations\Http\Controllers'], function()
// {
// 	Route::post('auth','AuthenticationController@authenticateAPIUser');
// 	Route::get('logout','AuthenticationController@logoutAPIUser');
	
// });
// Route::group(['middleware' => 'auth:integration', 'prefix' => 'api/eacintegrations', 'namespace' => 'App\\Modules\APIIntegrations\Http\Controllers'], function()
// {


// });
// Route::group(['prefix' => 'api', 'namespace' => 'App\\Modules\APIIntegrations\Http\Controllers'], function()
// {
// //function based authentication 
// 	Route::get('gepgBillSubReq', 'PaymentsIntegrationController@postBillSubmissionRequest');
	
// 	Route::get('gepgBillCanclReq', 'PaymentsIntegrationController@gepgBillCanclReq');

// 	Route::post('gepgBillSubResp', 'PaymentsIntegrationController@gepgBillSubResp');
// 	Route::post('gepgPmtSpInfo', 'PaymentsIntegrationController@gepgPmtSpInfo');
	
// 	Route::get('gepgReconcReq', 'PaymentsIntegrationController@gepgReconcReq');

// 	Route::post('gepgReconcResp', 'PaymentsIntegrationController@gepgReconcResp');
// 	Route::get('submitPaymentNextProcessAutoSubmissions', 'PaymentsIntegrationController@submitPaymentNextProcessAutoSubmissions');

// });


//  Route::group(['prefix' => 'api/tesws', 'namespace' => 'App\\Modules\APIIntegrations\Http\Controllers'], function()
// {
// 		Route::get('permitApprovalNotification ', 'TeswsIntegrationController@permitApprovalNotification');
// 		Route::get('processDeclaredImportExportapps ', 'TeswsIntegrationController@processDeclaredImportExportapps');
	
// });

//  Route::group(['prefix' => 'api/rragateway', 'namespace' => 'App\\Modules\APIIntegrations\Http\Controllers'], function()
// {
// 		Route::get('rraPaymentGatewayAuth', 'RRAPaymentsIntController@rraPaymentGatewayAuth');
// 		Route::get('rraPaymentGatewayGetDocumentNo', 'RRAPaymentsIntController@rraPaymentGatewayGetDocumentNo');
// 		Route::get('rraPaymentGatewayGetPayments', 'RRAPaymentsIntController@rraPaymentGatewayGetPayments');
	
// });

//  Route::group(['prefix' => 'api/iremeboypay', 'namespace' => 'App\\Modules\APIIntegrations\Http\Controllers'], function()
// {
// 		Route::get('iremboPaymentGatewayAuth', 'IremboPaymentsIntController@iremboPaymentGatewayAuth');
// 		Route::get('iremboFuncInvoiceSubmission', 'IremboPaymentsIntController@iremboFuncInvoiceSubmission');
// 		Route::get('iremboFuncGetInvoiceSubmission', 'IremboPaymentsIntController@iremboFuncGetInvoiceSubmission');

// 		Route::post('iremboGetPaymentNotifications', 'IremboPaymentsIntController@iremboGetPaymentNotifications');
// 		Route::get('onApplicationInvoicePaymentConfirmation', 'IremboPaymentsIntController@onApplicationInvoicePaymentConfirmation');
// 		Route::get('onGroupApplicationInvoicePaymentConfirmation', 'IremboPaymentsIntController@onGroupApplicationInvoicePaymentConfirmation');
		
	
// });