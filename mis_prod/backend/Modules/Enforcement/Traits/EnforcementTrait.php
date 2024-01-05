<?php


namespace Modules\Enforcement\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

trait EnforcementTrait {
    public function processEnforcementManagersApplicationSubmission($request)
    {
        
         //get workflow action details
         $action = $request->input('action');
         $action_details = $this->getApplicationWorkflowActionDetails($action);
         $keep_status = $action_details->keep_status;
         $action_type = $action_details->action_type_id;
         $approval_submission = $action_details->is_approval_submission;
    


         if ($approval_submission == 1) {
            $this->processNewApprovalApplicationSubmission($request, $keep_status);
        } else if ($action_type == 4) {//manager query to customer
            $this->submitApplicationFromManagerQueryToCustomer($request);
        } else if ($action_type == 5) {//manager query normal submission
            $this->processManagerQueryReturnApplicationSubmission($request);
        } else {
            $this->processNormalManagersApplicationSubmission($request, $keep_status);
        }    
    }
    public function processManagerInvestigationApplicationSubmission(Request $request, $keep_status = false)
    {

        $application_id = $request->input('application_id');
        $table_name = $request->input('table_name');
        $module_id = $request->input('module_id');
        $prev_stage = $request->input('curr_stage_id');
        $action = $request->input('action');
        $to_stage = $request->input('next_stage');
        $user_id = \Auth::user()->id;
        $is_dataammendment_request = $request->input('is_dataammendment_request');
        $is_inspection_submission = $request->input('is_inspection_submission');
        $user_id = $this->user_id;
        DB::beginTransaction();

        try {
            //get application_details

            $module_id = $request->input('module_id');
            if($table_name == ''){
                $table_name = getSingleRecordColValue('par_modules', array('id' => $module_id), 'tablename');
            }
            if($table_name == 'tra_product_notifications'){
                $table_name = 'tra_product_applications';
            }
            $application_details = DB::table($table_name)
                ->where('id', $application_id)
                ->first();
            if (is_null($application_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while fetching application details!!'
                );
                echo json_encode($res);
                exit();
            }
            
            $application_status_id = getApplicationTransitionStatus($prev_stage, $action, $to_stage);
            if ($keep_status == true) {//for approvals
                $application_status_id = $application_details->application_status_id;
            }
            $where = array(
                'id' => $application_id
            );
            if($is_dataammendment_request != 1){
                $app_update = array(
                    'workflow_stage_id' => $to_stage,
                    'application_status_id' => $application_status_id
                );
                $prev_data = getPreviousRecords($table_name, $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];
                 $update_res = updateRecord($table_name, $previous_data, $where, $app_update, $user_id);

                if ($update_res['success'] == false) {
                    echo json_encode($update_res);
                    exit();
                }
            }   

            $this->updateInvestigationApplicationSubmission($request, $application_details, $application_status_id);


        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
            echo json_encode($res);
            exit();
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
            echo json_encode($res);
            exit();
        }
    }
    public function updateInvestigationApplicationSubmission($request, $application_details, $application_status_id)
    {
        $application_id = $request->input('application_id');
        $process_id = $request->input('process_id');
        $action = $request->input('action');
        $table_name = $request->input('table_name');
        $external_user_id= $request->input('external_user_id');
        $additionalpayment_type_id = $request->additionalpayment_type_id;
        $sub_module_id= $request->input('sub_module_id');
        $user_id = $this->user_id;
        try { 
            //get process other details
            $process_details = DB::table('wf_tfdaprocesses')
                ->where('id', $process_id)
                ->first();
            if (is_null($process_details)) {
                DB::rollBack();
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while fetching process details!!'
                );
                echo json_encode($res);
                exit();
            }
            

            $from_stage = $request->input('curr_stage_id');
            $to_stage = $request->input('next_stage');
            $responsible_user = $request->input('responsible_user');
            $responsible_users =json_decode($responsible_user);
            $co_investigator = $request->input('co_investigator');
            $co_investigators = json_decode($co_investigator);
            //$no_of_coinvestigators = count($co_investigators);
            $no_of_coinvestigators = 1;
            $all_responsible_users =array_add($co_investigators,$no_of_coinvestigators,$responsible_users);
           
            $remarks = $request->input('remarks');
            $urgency = $request->input('urgency');
            $directive_id = $request->input('directive_id');
            //application details
            $application_code = $application_details->application_code;
            $ref_no = $application_details->reference_no;
            $view_id = $application_details->view_id;
            $tracking_no = $application_details->tracking_no;
            $applicant_id = $application_details->applicant_id;
            $branch_id = (isset($application_details->branch_id) && $process_details->module_id == 18)?$application_details->branch_id:2;
            $sub_module_id = $application_details->sub_module_id;
            //process other details
            $module_id = $process_details->module_id;
            // $sub_module_id = $process_details->sub_module_id;
            $section_id = $process_details->section_id;
            /*
             ------------switch for process specific key in intray------------
            */
            $prodclass_category_id = 0;
            $premise_type_id = 0;
            $importexport_permittype_id = 0;
            $enforcement_id = 0;

            if($module_id == 1){
                $prodclass_category_id = $application_details->prodclass_category_id;
            }
            if($module_id == 2){
                $premise_type_id = $application_details->premise_type_id;
            }
            if($module_id == 4 || $module_id == 9 || $module_id == 12){
                $importexport_permittype_id = $application_details->importexport_permittype_id;
            }

            //----------------------------------------//
            //transitions
            //process inforamtion
            $action_details = $this->getApplicationWorkflowActionDetails($action);
            $keep_status = $action_details->keep_status;
            $has_process_defination = $action_details->has_process_defination;
            $appprocess_defination_id = $action_details->appprocess_defination_id;

            $has_appdate_defination = $action_details->has_appdate_defination;
            $appdate_defination_id = $action_details->appdate_defination_id;
            $has_email_notification = $action_details->has_email_notification;
            //for inspection submissions
            $is_inspection_submission = 0;
            if(isset($action_details->is_inspection_submission)){
                $is_inspection_submission = $action_details->is_inspection_submission;
            }
            $appdate_defination = getSingleRecordColValue('par_appprocess_definations', array('id'=>$appdate_defination_id),'code');
            $application_processdefdata = array();
            if($has_appdate_defination == 1){
                $application_processdefdata =   array('application_code'=>$application_code,
                    'appprocess_defination_id'=>$appprocess_defination_id,
                    'process_date'=>Carbon::NOW(),
                    'created_by'=>$user_id,
                    'created_on'=>Carbon::NOW());
            }
            $processtransition_data = $this->getActionTransitionDetails($action);
            $is_multi_submission = $processtransition_data->is_multi_submission;
            $multinextstage_id = $processtransition_data->multinextstage_id;

            //end
            $transition_params = array(
                'application_id' => $application_id,
                'application_code' => $application_code,
                'application_status_id' => $application_status_id,
                'process_id' => $process_id,
                'from_stage' => $from_stage,
                'to_stage' => $to_stage,
                'author' => $user_id,
                'remarks' => $remarks,
                'directive_id' => $directive_id,
                'created_on' => Carbon::now(),
                'created_by' => $user_id
            );
            insertRecord('tra_applications_transitions', $transition_params);
            // DB::table('tra_applications_transitions')
            //     ->insert($transition_params);
            //submissions

            foreach($all_responsible_users as $responsible_user){
                
                $submission_params = array(
                    'application_id' => $application_id,
                    'process_id' => $process_id,
                    'view_id' => $view_id,
                    'application_code' => $application_code,
                    'reference_no' => $ref_no,
                    'tracking_no' => $tracking_no,
                    'usr_from' => $user_id,
                    'usr_to' => $responsible_user,
                    'prodclass_category_id' => $prodclass_category_id,
                    'premise_type_id' => $premise_type_id,
                    'importexport_permittype_id' => $importexport_permittype_id,
                    'additionalpayment_type_id' => $additionalpayment_type_id,
                    'previous_stage' => $from_stage,
                    'current_stage' => $to_stage,
                    'module_id' => $module_id,
                    'external_user_id'=>$external_user_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_status_id' => $application_status_id,
                    'urgency' => $urgency,
                    'applicant_id' => $applicant_id,
                    'branch_id' => $branch_id,
                    'remarks' => $remarks,
                    'directive_id' => $directive_id,
                    'date_received' => Carbon::now(),
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );
                insertMultipleRecords('tra_submissions',$submission_params,$user_id);
            
                $submission_params[] = $submission_params;

                if($is_multi_submission == 1){
                    $submission_params['current_stage'] =  $multinextstage_id;
                    $submission_params['usr_to'] =  '';
                    $multisubmission_params[] = $submission_params;
                }
            }
       
            if(validateIsNumeric($external_user_id)){
                $submission_params['usr_to'] = $external_user_id;
                //send and email to the Extrenal user
                $module_name = getSingleRecordColValue('par_modules', array('id'=>$module_id), 'name');
                $process_name = getSingleRecordColValue('wf_processes', array('id'=>$process_id), 'name');
                $process_stage = getSingleRecordColValue('wf_workflow_stages', array('id'=>$to_stage), 'name');
                $email_address = getSingleRecordColValue('users', array('id'=>$external_user_id), 'email');
                $vars = array(
                    '{module_name}' => $module_name,
                    '{process_name}' => $process_name,
                    '{process_stage}' => $process_stage,
                );
                sendTemplatedApplicationNotificationEmail(16, $email_address,$vars);
                //send an email to the rest of the users

            }

            // if($has_email_notification == 1){
            //     if($module_id == 8){
            //         $email_address = DB::table('tra_enforcement_information', array('id'=>$application_details->enforcement_id), 'app_email');
            //         $vars = array(
            //              '{application_no}' => $tracking_no.': Application No '.$ref_no
            //         );
            //         sendTemplatedApplicationNotificationEmail(10, $email_address,$vars);
            //         //send an email to the rest of the users
            //     }
            // }

            // if ($action_details->has_submission_notification == 1 && validateIsNumeric($responsible_user)) {

            //     $module_name = getSingleRecordColValue('par_modules', array('id'=>$module_id), 'name');
            //     $process_name = getSingleRecordColValue('wf_processes', array('id'=>$process_id), 'name');
            //     $process_stage = getSingleRecordColValue('wf_workflow_stages', array('id'=>$to_stage), 'name');
            //     $email_address = getSingleRecordColValue('users', array('id'=>$responsible_user), 'email');
            //     $vars = array(
            //         '{module_name}' => $module_name,
            //         '{process_name}' => $process_name,
            //         '{process_stage}' => $process_stage,
            //         '{application_no}' => $tracking_no.': Application No '.$ref_no,
            //     );

            //     sendTemplatedApplicationNotificationEmail(23, $email_address,$vars);
            //     //send an email to the rest of the users

            // }
            if($has_appdate_defination == 1){

                $appdate_defination = array($appdate_defination=>Carbon::now(),'dola'=>Carbon::now());
                /* $app_update = DB::table($table_name . ' as t1')
                                 ->where('application_code', $application_code)
                                 ->update($appdate_defination);
                                 */
            }
            if(count($application_processdefdata) >0){
                insertRecord('tra_applications_processdefinations', $application_processdefdata, 1);
            }

            //check if Application is from inspection Submission
            $this->setIsDoneIFInspectionApplicationSubmission($application_code, $from_stage);

            updateInTraySubmissions($application_id, $application_code, $from_stage, $user_id);


            DB::commit();
            $res = array(
                'success' => true,
                'message' => 'Application Submitted Successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        echo json_encode($res);
        return true;
    }
    // public function processManagerInvestigationApplicationSubmission(Request $request, $keep_status = false,$action_type= 0)
    // {
    //     $application_id = $request->input('application_id');
    //     $process_id = $request->input('process_id');
    //     $table_name = $request->input('table_name');
    //     $application_code = $request->input('application_code');
    //     $module_id = $request->input('module_id');
    //     $responsible_user = $request->input('responsible_user');
    //     $responsible_users =json_decode($responsible_user);
    //     $co_investigator = $request->input('co_investigator');
    //     $co_investigators = json_decode($co_investigator);
    //     $user_id = $this->user_id;
    //     $no_of_coinvestigators = count($co_investigators);
    //     $all_responsible_users =array_add($co_investigators,$no_of_coinvestigators,$responsible_users);

    //     DB::beginTransaction();
    //     if($table_name == ''){
    //         $table_name = getSingleRecordColValue('par_modules', array('id' => $module_id), 'tablename');
    //         $request->table_name = $table_name;

    //     }
      
    //     try {
    //         //get application_details
    //         $application_details = DB::table($table_name)
    //             ->where('id', $application_id)
    //             ->first();
    //         if (is_null($application_details)) {
    //             $res = array(
    //                 'success' => false,
    //                 'message' => 'Problem encountered while fetching application details!!'
    //             );
    //             echo json_encode($res);
    //             exit();
    //         }
            
    //         //get process other details
    //         $process_details = DB::table('wf_processes')
    //             ->where('id', $process_id)
    //             ->first();
    //         if (is_null($process_details)) {
    //             $res = array(
    //                 'success' => false,
    //                 'message' => 'Problem encountered while fetching process details!!'
    //             );
    //             echo json_encode($res);
    //             exit();
    //         }
           
    //         // DB::transaction(function () use ($request, $selected_ids, &$res, $table_name, $user_id, $application_id, $process_id, $application_details, $process_details) {
    //         $application_codes = array();
    //         $is_manager_submission = $request->input('is_manager_submission');
    //         $process_type_id = $request->input('process_type_id');
    //         $expected_start_date = $request->input('expected_start_date');
    //         $expected_end_date = $request->input('expected_end_date');
    //         $external_user_id = $request->input('external_user_id');
    //         $is_dataammendment_request = $request->input('is_dataammendment_request');
    //         $from_stage = $request->input('curr_stage_id');
    //         $action = $request->input('action');
    //         $to_stage = $request->input('next_stage');

    //         $responsible_user = $request->input('responsible_user');
    //         $co_investigator = $request->input('co_investigator');
    //         $remarks = $request->input('remarks');
    //         $directive_id = $request->input('directive_id');
    //         $urgency = $request->input('urgency');
    //         $transition_params = array();
    //         $submission_params = array();
    //         //process other details
    //         $module_id = $process_details->module_id;
    //         $sub_module_id = $process_details->sub_module_id;
    //         $section_id = $process_details->section_id;
    //         $application_status_id = getApplicationTransitionStatus($from_stage, $action, $to_stage);

    //         //todo get workflow action details
    //         $action_details = $this->getApplicationWorkflowActionDetails($action);
           
    //         $keep_status = $action_details->keep_status;
    //         $has_process_defination = $action_details->has_process_defination;
    //         $appprocess_defination_id = $action_details->appprocess_defination_id;
    //         //meeting inivitation
    //         $has_technicalmeeting_notification = $action_details->has_technicalmeeting_notification;
    //         $technicalmeetinemail_msg_id = $action_details->technicalmeetinemail_msg_id;

    //         $has_preminsp_notification = $action_details->has_preminsp_notification;
    //         $preminspmail_msg_id = $action_details->preminspmail_msg_id;
    //         $has_email_notification = $action_details->has_email_notification;
    //         $email_message_id = $action_details->email_message_id;

    //         $has_appdate_defination = $action_details->has_appdate_defination;
    //         $appdate_defination_id = $action_details->appdate_defination_id;
    //         $is_inspection_submission = 0;
    //         if(isset($action_details->is_inspection_submission)){
    //             $is_inspection_submission = $action_details->is_inspection_submission;
    //         }
            
    //         $appdate_defination = getSingleRecordColValue('par_appprocess_definations', array('id'=>$appdate_defination_id),'code');

    //         $processtransition_data = $this->getActionTransitionDetails($action);
    //         $is_multi_submission = $processtransition_data->is_multi_submission;
    //         $multinextstage_id = $processtransition_data->multinextstage_id;

    //         // $portal_table = getPortalApplicationsTable($module_id);

    //         $application_processdefdata = array();
    //         $multisubmission_params = array();
    //         $inspectors = array();

    //         /*--------------------------------
    //             confirm the co_investigators is an array or collection
    //         ----------------------------------*/
    //         if($all_responsible_users instanceof Collection || is_array($all_responsible_users)){
    //             //is okay to proceed
    //         }else{
    //             $all_responsible_users = array($all_responsible_users);
    //         }
    //         //application details
           
    //         foreach ($application_details as $key => $application_detail) {

    //             foreach($all_responsible_users as $responsible_user){

    //                 if ($keep_status == true) {
    //                     $application_status_id = $application_detail->application_status_id;
    //                 }
                    
    //                 //transitions
    //                 $transition_params[] = array(
    //                     'application_id' => $application_details->id,
    //                     'application_code' => $application_details->application_code,
    //                     'application_status_id' => $application_status_id,
    //                     'process_id' => $process_id,
    //                     'from_stage' => $from_stage,
    //                     'to_stage' => $to_stage,
    //                     'author' => $user_id,
    //                     'directive_id' => $directive_id,
    //                     'remarks' => $remarks,
    //                     'created_on' => Carbon::now(),
    //                     'created_by' => $user_id
    //                 );
                   
    //                 //submissions
    //                 if($action_type == 11){
    //                     $responsible_user = $this->getApplicationInspEvaUsers($application_detail->application_code);
    //                 }
                    
    //                 $application_code = $application_details->application_code;
    //                 /*--------------------------------------//
    //                     switch to include the key distinct
    //                     */
    //                 $prodclass_category_id = 0;
    //                 $premise_type_id = 0;
    //                 $importexport_permittype_id = 0;
                    
    //                 // if($module_id == 1){
    //                 //     $prodclass_category_id = $application_detail->prodclass_category_id;
    //                 // }
    //                 // if($module_id == 2){
    //                 //     $premise_type_id = $application_detail->premise_type_id;
    //                 // }
    //                 // if($module_id == 4 || $module_id == 12|| $module_id == 9){
    //                 //     $importexport_permittype_id = $application_detail->importexport_permittype_id;
    //                 // }
    //                 //---------------------------//
                    
    //                 $usersubmission_data = array(
    //                     'application_id' => $application_details->id,
    //                     'view_id' => $application_details->view_id,
    //                     'process_id' => $process_id,
    //                     'application_code' => $application_details->application_code,
    //                     'prodclass_category_id' => $prodclass_category_id,
    //                     'premise_type_id' => $premise_type_id,
    //                     'importexport_permittype_id' => $importexport_permittype_id,
    //                     'reference_no' => $application_details->reference_no,
    //                     'tracking_no' => $application_details->tracking_no,
    //                     'usr_from' => $user_id,
    //                     'usr_to' => $responsible_user,
    //                     'previous_stage' => $from_stage,
    //                     'current_stage' => $to_stage,
    //                     'module_id' => $module_id,
    //                     'sub_module_id' => $application_details->sub_module_id,
    //                     'section_id' => $section_id,
    //                     'application_status_id' => $application_status_id,
    //                     'urgency' => $urgency,
    //                     'applicant_id' => $application_details->applicant_id,
    //                     'remarks' => $remarks,
    //                     'directive_id' => $directive_id,
    //                     'external_user_id' => $external_user_id,
    //                     'date_received' => Carbon::now(),
    //                     'created_on' => Carbon::now(),
    //                     'created_by' => $user_id
    //                 );
                   
                    
    //                 if(validateIsNumeric($external_user_id)){
    //                     $usersubmission_data['usr_to'] = $external_user_id;
    //                     //send and email to the Extrenal user
    //                     $module_name = getSingleRecordColValue('par_modules', array('id'=>$module_id), 'name');
    //                     $process_name = getSingleRecordColValue('wf_processes', array('id'=>$process_id), 'name');
    //                     $process_stage = getSingleRecordColValue('wf_workflow_stages', array('id'=>$to_stage), 'name');
    //                     $email_address = getSingleRecordColValue('users', array('id'=>$external_user_id), 'email');
    //                     $vars = array(
    //                         '{module_name}' => $module_name,
    //                         '{process_name}' => $process_name,
    //                         '{process_stage}' => $process_stage,
    //                     );
    //                    sendTemplatedApplicationNotificationEmail(16, $email_address,$vars);
    //                     //send an email to the rest of the users

    //                 }
                    
    //                 if($is_manager_submission == 1){
    //                     $usersubmission_data['expected_start_date'] = $expected_start_date;
    //                     $usersubmission_data['expected_end_date'] = $expected_end_date;
    //                     /*--------------------------------------------
    //                         log assignment
    //                     -----------------------------------------------*/
    //                     $assignment_log = array(
    //                         'application_code' =>$application_detail->application_code,
    //                         'reference_no'=>$application_detail->reference_no,
    //                         'tracking_no'=>$application_detail->tracking_no,
    //                         'assigned_by'=>$user_id,
    //                         'assigned_to' =>$responsible_user,
    //                         'assigned_on'=>Carbon::now(),
    //                         'expected_start_date'=>$expected_start_date,
    //                         'expected_end_date'=>$expected_end_date,
    //                         'process_type_id'=>$process_type_id,
    //                         'sub_module_id'=>$application_detail->sub_module_id,
    //                         'module_id'=>$module_id

    //                     );
    //                     insertRecord('tra_manager_assignements_logs', $assignment_log, $user_id);
    //                 }
                    
    //                 if($has_appdate_defination == 1){
    //                     $application_processdefdata[] =   array('application_code'=>$application_code,
    //                         'appprocess_defination_id'=>$appprocess_defination_id,
    //                         'process_date'=>Carbon::NOW(),
    //                         'created_by'=>$user_id,
    //                         'created_on'=>Carbon::NOW());
    //                 }

                    
    //                 $submission_params[] = $usersubmission_data;
    //                 $application_codes[] = array($application_details->application_code);
                    
    //                 if($is_multi_submission == 1){
    //                     $usersubmission_data['current_stage'] =  $multinextstage_id;
    //                     $usersubmission_data['usr_to'] =  '';
    //                     $multisubmission_params[] = $usersubmission_data;
    //                 }

    //                 if($is_inspection_submission == 1){
    //                     //get Inspectors
    //                     $inspectors[] = $this->getInspectorsIDList($module_id, $application_details->application_code);
    //                 }

    //                 //check if Application is from inspection Submission
    //                 $this->setIsDoneIFInspectionApplicationSubmission($application_details->application_code, $from_stage);

    //                 // $submission_data[] = $usersubmission_data;

    //             }
               
    //         }

    //         //application update
    //         $update_params = array(
    //             'workflow_stage_id' => $to_stage,
    //             'application_status_id' => $application_status_id,
    //             'dola'=>Carbon::now()
    //         );
           
    //         if($has_appdate_defination == 1){
    //             $appdate_defination = array($appdate_defination=>Carbon::now(),'dola'=>Carbon::now());
    //              $app_update = DB::table($table_name . ' as t1')
    //                              ->whereIn('application_code', $selected_appCodes)
    //                              ->update($appdate_defination);//dd($app_update);
    //         }
           
    //         if(count($application_processdefdata) >0){
    //             dd($application_processdefdata);
    //             DB::table('tra_applications_processdefinations')
    //                 ->insert($application_processdefdata);

    //         }
            
    //         //transitions update
    //         $res_transitions = insertRecord('tra_applications_transitions',$transition_params);

    //         //submissions update
    //         if($is_inspection_submission == 1){
    //             //loop through while updating submissions data
    //             foreach ($inspectors as $inspector_array) {
    //                 foreach ($inspector_array as $inspector) {
    //                     foreach ($submission_params as $submission_param) {
    //                         //change usr_to
    //                         $submission_param['usr_to'] = $inspector->inspector_id;
    //                         //update submissions
    //                         DB::table('tra_submissions')->insert($submission_param);
    //                     }
    //                 }
    //             }
    //         } else {

    //             $rest=insertMultipleRecords('tra_submissions',$submission_params);

    //         }

    //         updateInTraySubmissionsBatch($selected_ids, $application_codes, $from_stage, $user_id);

    //         if(count($multisubmission_params) >0){

    //            $res_multisubmission= insertRecord('tra_submissions',$multisubmission_params);
    //             //print_r(($multisubmission_params));

    //         }
    //         //for the email notification and more so the meeting details
    //         //  $has_technicalmeeting_notification $has_email_notification $email_message_id

    //         if($has_preminsp_notification == 1){
    //             $application_code = $application_detail->application_code;
    //             //get the inspectors email
    //             $inspection_details = $this->getPremisesInspectionDetails($application_code);
    //             $inspectors_email = $this->getPremInspectorsEmail($application_code);

    //             $vars = array(
    //                 '{start_date}'=>$inspection_details->start_date,
    //                 '{end_date}'=>$inspection_details->end_date,
    //                 '{description}'=>$inspection_details->description,
    //                 '{lead_inspector}'=>$inspection_details->lead_inspector
    //             );
    //             sendTemplatedApplicationNotificationEmail($preminspmail_msg_id, $inspectors_email,$vars);

    //         }
    //         if($has_technicalmeeting_notification == 1){
    //             //get the emails

    //             //meeting participants emails
    //             if(validateIsNumeric($application_detail->application_code)){
    //                 $app_description = '';
    //                 $application_code = $application_detail->application_code;
    //                 //var_dump($application_detail);
    //                 $meeting_details = $this->getMeetingDetails($application_code);

    //                 //var_dump($meeting_details);exit;
    //                 $meeting_attendantsemail = $this->getMeetingAttendantsEmails($application_code);
    //                 $directorate_details = $this->getDirectorateInformation($section_id);
    //                 $meeting_id = $meeting_details->id;
    //                 //->select(DB::raw("t2.name as directorate_name, CONCAT_WS(' ',decrypt(t4.first_name),decrypt(t4.last_name)) as director_name"))
    //                 $directorate_name = $directorate_details->directorate_name;
    //                 $director_name = $directorate_details->director_name;
    //                 $section_name = $directorate_details->section_name;
    //                 $module_name = getSingleRecordColValue('par_modules', array('id'=>$module_id), 'name');

    //                 $vars = array(
    //                     '{meeting_name}' => $meeting_details->meeting_name,
    //                     '{app_description}' => $app_description,
    //                     '{meeting_time}' => $meeting_details->meeting_time,
    //                     '{date_requested}' => $meeting_details->date_requested,
    //                     '{meeting_venue}' => $meeting_details->meeting_venue,
    //                     '{directorate_name}' => $directorate_name,
    //                     '{director_name}' => $director_name,
    //                     '{section_name}' => $section_name,
    //                     '{module_name}' => $module_name
    //                 );
    //                 //check for the external users and
    //                 //sendTemplatedApplicationNotificationEmail($technicalmeetinemail_msg_id, $meeting_attendantsemail,$vars);
    //                 /*$participantEmails = explode(';',$meeting_attendantsemail);
    //                 foreach($participantEmails as $participantEmail){
    //                     sendTemplatedApplicationNotificationEmail($technicalmeetinemail_msg_id, $participantEmail,$vars);
    //                }*/
    //                 //send an email to the rest of the users
    //                 $records = DB::table('tc_meeting_participants')
    //                     ->select('*')
    //                     ->where(array('meeting_id'=>$meeting_id))
    //                     ->get();


    //                 if($records){

    //                     foreach ($records as $rec) {

    //                         $user_id = $rec->user_id;
    //                         $email_address = $rec->email;
    //                         $participant_id = $rec->id;
    //                         //print_r($user_id);

    //                         /*if(!validateIsNumeric($user_id)){

    //                             $participant_id = $rec->id;
    //                             //users details
    //                             $table_data = array(
    //                                 'first_name' => $rec->participant_name,
    //                                 'mobile' => $rec->phone,
    //                                 'phone' => $rec->phone,
    //                                 'user_category_id' => 2
    //                             );
    //                             $this->createExternalUserAccountDetails($table_data,$email_address,$participant_id,$vars);
    //                         }*///endif

    //                         $table_data = array(
    //                             'first_name' => $rec->participant_name,
    //                             'mobile' => $rec->phone,
    //                             'phone' => $rec->phone,
    //                             'user_category_id' => 2
    //                         );
    //                         $this->createExternalUserAccountDetails($table_data,$email_address,$participant_id,$vars);


    //                     }//endforeach
    //                 }//endif

    //             }


    //         }
    //         DB::commit();
    //         $res = array(
    //             'success' => true,
    //             'message' => 'Application Submitted Successfully!!'
    //         );
    //     } catch (\Exception $exception) {
    //         $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
    //     } catch (\Throwable $throwable) {
    //         $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
    //     }
    //     echo json_encode($res);
    //     exit();
    // }
    public function saveEnforcementApplicationRecommendationDetails(Request $request)
    {
        $table_name = $request->input('table_name');
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');

        $qry = DB::table($table_name)
            ->where('application_code', $application_code);
        $app_details = $qry->first();

        // if (($app_details)) {
        //     $res = array(
        //         'success' => false,
        //         'message' => 'Problem encountered while getting application details!!'
        //     );
        //     return $res;
        // }
        $res = array();
        try {
            DB::transaction(function () use ($qry, $application_id, $application_code, $table_name, $request, $app_details, &$res) {
                $UpdateParams = array();
                $id = $request->input('recommendation_id');
                $process_id = $request->input('process_id');
                $workflow_stage_id = $request->input('workflow_stage_id');
                $decision_id = $request->input('decision_id');
                $comment = $request->input('comment');
                $approved_by = $request->input('approved_by');
                $approval_date = $request->input('approval_date');
                $expiry_date = $request->input('expiry_date');
                $dg_signatory = $request->input('dg_signatory');
                $signatory = $request->input('permit_signatory');
				$sub_module_id = $app_details->sub_module_id;

                $user_id = $this->user_id;

                $update_activity_data = array(
                    'is_manager_approved'=>1
                );
                $where_activity= array(
                    'application_code'=>$application_code
                );
                $where_summary = array(
                    'active_application_code'=>$application_code
                );
                if ($dg_signatory == 1) {
                    $permit_signatory = getPermitSignatory($process_id);
                } else {
                    $permit_signatory = $signatory;
                }
                $params = array(
                    'application_id' => $application_id,
                    'application_code' => $application_code,
                    'workflow_stage_id' => $workflow_stage_id,
                    'decision_id' => $decision_id,
                    'comment' => $comment,
                    'approval_date' => $approval_date,
                    'expiry_date' => $expiry_date,
                    'approved_by' => $approved_by,
                    'dg_signatory' => $dg_signatory,
                    'permit_signatory' => $permit_signatory
                );
                $UpdateParams['permit_issue_date'] = $approval_date;
                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'reference_type_id' => 2), 'reference_format_id');
                if (validateIsNumeric($id)) {
                    //update
                    $where = array(
                        'id' => $id
                    );
                    $params['dola'] = Carbon::now();
                    $params['altered_by'] = $user_id;
                    $prev_data = getPreviousRecords('tra_approval_recommendations', $where);
                    if ($prev_data['success'] == false) {
                        return \response()->json($prev_data);
                    }
                    $prev_data_results = $prev_data['results'];
                    $prev_decision_id = $prev_data_results[0]['decision_id'];
                    $prev_data_results[0]['record_id'] = $id;
                    $prev_data_results[0]['update_by'] = $user_id;
                    $prev_data_results[0]['recommendation_id'] = $prev_data_results[0]['id'];
                    unset($prev_data_results[0]['id']);
                    DB::table('tra_approval_recommendations_log')
                        ->insert($prev_data_results);
                    if ($decision_id == 1) {
						$UpdateParams['reference_no'] = $app_details->reference_no;
                        $validity_status_id = 2;
                        $registration_status_id = 2;
                        $qry->update(array('application_status_id' => 6));
                        //permit
                        // if ($prev_decision_id != 1) {
                        //     $certificate_no = generatePremisePermitNo($app_details->zone_id, $app_details->section_id, $table_name, $user_id, $ref_id,$sub_module_id);
                        //     $params['certificate_no'] = $certificate_no;
                        // }
                    } else {
                        $UpdateParams['reference_no'] = null;
                        $validity_status_id = 3;
                        $registration_status_id = 3;
                        $qry->update(array('application_status_id' => 7));
                        $params['certificate_no'] = '';
                        $params['certificate_no'] = null;
                    }
                    $previous_data = getPreviousRecords('tra_approval_recommendations', $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res = updateRecord('tra_approval_recommendations', $previous_data, $where, $params, $user_id);
                    
                } else {
                    //insert
                    $params['created_on'] = Carbon::now();
                    $params['created_by'] = $user_id;
                    if ($decision_id == 1) {
                        $UpdateParams['reference_no'] = $app_details->reference_no;
                        $validity_status_id = 2;
                        $registration_status_id = 2;
                        //permits
                        // $certificate_no = generatePremisePermitNo($app_details->zone_id, $app_details->section_id, $table_name, $user_id, $ref_id,$sub_module_id);
                        // $params['certificate_no'] = $certificate_no;
                        $qry->update(array('application_status_id' => 6));
                    } else {
                        $UpdateParams['reference_no'] = null;
                        $validity_status_id = 3;
                        $registration_status_id = 3;
                        $qry->update(array('application_status_id' => 7));
                        $params['certificate_no'] = '';
                        $params['expiry_date'] = null;
                    }
                    $res = insertRecord('tra_approval_recommendations', $params, $user_id);
                   // dd($res);
                    $id = $res['record_id'];
                }

				$UpdateParams['validity_status'] = $validity_status_id;
				$UpdateParams['registration_status'] = $registration_status_id;

                    if($decision_id == 1){
                        $portal_status_id = 10;
                        //dd($workflow_stage_id);
                        if($sub_module_id==106){
                            if ($workflow_stage_id==1296){

                                $previous_data = getPreviousRecords('par_joint_activities_details', $where_activity);
                                if ($previous_data['success'] == false) {
                                    return $previous_data;
                                }
                                $previous_data = $previous_data['results'];
                                $res2 = updateRecord('par_joint_activities_details', $previous_data, $where_activity, $update_activity_data, $user_id);
                                
                            }
                            else if($workflow_stage_id==1299){
                                $previous_data = getPreviousRecords('par_operation_summary', $where_summary);
                                if ($previous_data['success'] == false) {
                                    return $previous_data;
                                }
                                $previous_data = $previous_data['results'];
                                $res3 = updateRecord('par_operation_summary', $previous_data, $where_summary, $update_activity_data, $user_id);
                              //  dd($res3);
                            }
                            else{
                            }
                           
                        }
                        else{

                        }
                    }
                    else{
                        $portal_status_id = 11;
                    }
                    

            }, 5);

        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return $res;
	}

}