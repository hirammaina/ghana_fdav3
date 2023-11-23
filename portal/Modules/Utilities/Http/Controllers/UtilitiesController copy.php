<?php

namespace Modules\Utilities\Http\Controllers;
use Modules\ImportExportApp\Traits\ImportexportpermitsTraits;
use Modules\PremisesRegistration\Traits\PremisesRegistrationTraits;
use Modules\ProductRegistration\Traits\ProductRegistrationTraits;
use Modules\Gmpinspection\Traits\GmpInspectionsTraits;
use Modules\ClinicalTrials\Traits\ClinicalTrialAppTraits;
use Modules\Promotionadverts\Traits\PromotionadvertsTraits;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
 
use Validator;
use App\User;

use Carbon\Carbon;

class UtilitiesController extends Controller
{
	use ImportexportpermitsTraits;
	use PremisesRegistrationTraits;
	use ProductRegistrationTraits;
	use GmpInspectionsTraits;
	use ClinicalTrialAppTraits;
	use PromotionadvertsTraits;
    public function __construct(){
       /* if (!Auth::guard('api')->check()) {
                $res = array(
                    'success' => false,
                    'message' => 'Invalid Token or failed authentication, login to proceed!!'
                );
                echo json_encode($res);
                exit();
        }
         */
    }
    public function getApplicationPreRejectionDetails(Request $req){
        try{
            $table_name = $req->table_name;
            $application_code = $req->application_code;
            $status_column = $req->status_column;
            //tra_online_queries
            $records = DB::connection('mis_db')->table($table_name.' as t1')
                          ->join('wb_statuses as t2',  $status_column, '=','t2.id')
                          ->join('wb_rejection_remarks as t3', 't1.application_code', '=','t3.application_code')
                          ->select('t1.tracking_no','t2.name as application_status' ,'t3.remark as rejection_remarks','t3.created_on as added_on', 't1.id' )  
                          ->where(array('t1.application_code'=>$application_code))
                          ->get();
              $res = array('success'=>true, 
                          'data'=>$records
                          );
      }
      catch (\Exception $e) {
          $res = array(
              'success' => false,
              'message' => $e->getMessage()
          );
      } catch (\Throwable $throwable) {
          $res = array(
              'success' => false,
              'message' => $throwable->getMessage()
          );
      }
      return response()->json($res);

        
    }
	function onMisApplicationIntraySubmit($req,$table_name,$application_code,$tracking_no){
       try{
            
            $status_id = $req->status_id;
            $trader_id = $req->trader_id;
            $remarks = $req->submission_comments;

            $is_fast_track = $req->is_fast_track;
            $paying_currency_id = $req->paying_currency_id;

            $traderemail_address = $req->traderemail_address;
            $data = array();
           
            $resp = false;
            $mansite_emails = '';
            if(validateIsNumeric($application_code)){
                $where_state = array('application_code' => $application_code);
            }
            else{
                $where_state = array('tracking_no' => $tracking_no);

            }
           $cc= '';
            $records = DB::table($table_name)
                        ->where($where_state)
                        ->first();
                      $prodclass_category_id = 0;
            if($records){
				
				
                    //delete functionality process_id
                    $previous_status_id = $records->application_status_id;
                    $trader_id = $records->trader_id;
                    $application_code = $records->application_code;
                    $last_query_ref_id = $records->last_query_ref_id;
                    $section_id = $records->section_id;
                    //$applicant_id = $records->applicant_id;
					$section_id = $records->section_id;
                    if($previous_status_id < 1){
                        $previous_status_id = 1;
                    }
                    $module_id = $records->module_id;
                    $current_status_id = getSingleRecordColValue('wb_processstatus_transitions', array('module_id' => $module_id,'current_status_id' => $previous_status_id ), 'next_status_id');
                  
                   if(validateisNumeric($current_status_id)){
					   
                        $status_type_id = getSingleRecordColValue('wb_statuses', array('id' => $current_status_id), 'status_type_id');

                            $app_data = array('application_status_id'=>$current_status_id,
                                                 'clinical_registrystatus_id'=>2,
                                                'altered_by'=>$traderemail_address,
                                                'dola'=>Carbon::now()
                                            );
                          
                           $where = array(
                                //'t1.module_id' => $records->module_id,
                                't1.sub_module_id' => $records->sub_module_id,
                                't1.section_id' => $records->section_id
                            );
							
							//code 
							 $qry = DB::connection('mis_db')->table('wf_tfdaprocesses as t1');
								if (validateIsNumeric($status_type_id) && ($status_type_id == 3)) {//manager query response
									$qry->join('wf_workflow_stages as t2', function ($join) {
										$join->on('t2.workflow_id', '=', 't1.workflow_id')
											->on('t2.is_manager_query_response', '=', DB::raw(1));
									});
									$qry->select('t1.id as process_id', 't2.id as current_stage', 't1.name as processName', 't2.name as currentStageName','t2.needs_responsible_user',
									't1.module_id', 't1.sub_module_id', 't1.section_id')
									->where($where);
									
								}else if (validateIsNumeric($status_type_id) && ($status_type_id == 2 )) {//manager query response
									$qry->join('wf_workflow_stages as t2', function ($join) {
										$join->on('t2.workflow_id', '=', 't1.workflow_id')
											->on('t2.is_manager_query_response', '=', DB::raw(1));
									});
									$qry->select('t1.id as process_id', 't2.id as current_stage', 't1.name as processName', 't2.name as currentStageName','t2.needs_responsible_user',
									't1.module_id', 't1.sub_module_id', 't1.section_id')
									->where($where);
									
								}else if (validateIsNumeric($status_type_id) && ($status_type_id == 5 )) {//manager query response
									$qry->join('wf_workflow_stages as t2', function ($join) {
										$join->on('t2.workflow_id', '=', 't1.workflow_id')
											->on('t2.is_screeningquery_response', '=', DB::raw(1));
									});
									$qry->select('t1.id as process_id', 't2.id as current_stage', 't1.name as processName', 't2.name as currentStageName','t2.needs_responsible_user',
									't1.module_id', 't1.sub_module_id', 't1.section_id')
									->where($where);
									
								}else if (validateIsNumeric($status_type_id) && ($status_type_id == 4)) {//manager query response
									$qry->join('wf_workflow_stages as t2', function ($join) {
										$join->on('t2.workflow_id', '=', 't1.workflow_id')
											->on('t2.is_manager_query_response', '=', DB::raw(1));
									});
									$qry->select('t1.id as process_id', 't2.id as current_stage', 't1.name as processName', 't2.name as currentStageName','t2.needs_responsible_user',
									't1.module_id', 't1.sub_module_id', 't1.section_id')
									->where($where);
									
								}else {//manager query response
									$qry->join('wf_workflow_stages as t2', function ($join) {
										 $join->on('t2.workflow_id', '=', 't1.workflow_id')
											->on('t2.is_portalapp_initialstage', '=', DB::raw(1));
									});
									$qry->select('t1.id as process_id', 't2.id as current_stage', 't1.name as processName', 't2.name as currentStageName','t2.needs_responsible_user',
									't1.module_id', 't1.sub_module_id', 't1.section_id')
									->where($where);
									
								}
								
								$rec = $qry->first();
								
							//code block details
                           /* $rec = DB::connection('mis_db')->table('wf_tfdaprocesses as t1')
                                            ->join('wf_workflow_stages as t2', 't1.workflow_id','=','t2.workflow_id')
                                            ->where($where)
                                            ->select('t2.id as current_stage','t1.id as process_id')
                                            ->where('is_portalapp_initialstage',1)
                                            ->first(); process_id
                            */
							$applicant_id = $trader_id;
							
							
                            $view_id = $this->generateApplicationViewID(); 
                            $tracking_no = $records->tracking_no;
							$zone_id = 2;
							if(isset($record->zone_id)){
								$zone_id = $record->zone_id;
							}
							
                            $onlinesubmission_data  = array('application_code'=>$records->application_code,
                                    'reference_no'=>$records->reference_no,
                                    'tracking_no'=>$records->tracking_no,
                                    'application_id'=>$records->id,
                                    'prodclass_category_id'=>$prodclass_category_id,
                                    'view_id'=>$view_id,
                                    'process_id'=>$rec->process_id,
                                    'current_stage'=>$rec->current_stage,
                                    'module_id'=>$records->module_id,
                                    'sub_module_id'=>$records->sub_module_id,
                                    'section_id'=>$records->section_id,
                                    'application_status_id'=>$records->application_status_id,
                                    'remarks'=>$remarks,
                                    'applicant_id'=>$applicant_id,
                                    'is_notified'=>0,
                                    'status_type_id'=>$status_type_id,
									'onlinesubmission_status_id'=>1,
									
									'zone_id'=>$zone_id,
                                    'date_submitted'=>Carbon::now(),
                                    'created_on'=>Carbon::now(),
                                    'created_by'=>$trader_id
                            );
                               
                            $previous_data = getPreviousRecords($table_name, $where_state,'mis_db');
                            
                            $resp = updateRecord($table_name, $previous_data, $where_state, $app_data, $traderemail_address,'mis_db');
                            $resp =  insertRecord('tra_onlinesubmissions', $onlinesubmission_data, $traderemail_address,'mis_db');
							$sub_module_id = $records->sub_module_id;
							$module_id = $records->module_id;
							//insert in the tra_submission too 
							  $onlinesubmission_data  = array('application_code'=>$records->application_code,
                                    'reference_no'=>$records->reference_no,
                                    'tracking_no'=>$records->tracking_no,
                                    'view_id'=>$view_id,
                                    'process_id'=>$rec->process_id,
                                    'current_stage'=>$rec->current_stage,
                                    'module_id'=>$records->module_id,
                                    'sub_module_id'=>$records->sub_module_id,
                                    'section_id'=>$records->section_id,
                                    'application_status_id'=>1,
                                    'remarks'=>'Online Submission: '.$remarks,
                                    'applicant_id'=>$applicant_id,
                                    'isDone'=>0,
                                    'isRead'=>0,
									'zone_id'=>$zone_id,
                                    'created_on'=>Carbon::now(),
                                    'created_by'=>$trader_id
                            );
							$next_stage = $rec->current_stage;
							$resp = insertRecord('tra_submissions', $onlinesubmission_data, $traderemail_address,'mis_db');
							
							
                           if($previous_status_id == 8 || $previous_status_id == 6 || $previous_status_id == 17 || $previous_status_id == 7){
                                 //update the query tracker table 
                                    $data = array('responded_on'=>Carbon::now(),                'responded_by'=>$traderemail_address,
                                        'queryref_status_id'=>2,
                                        'dola'=>Carbon::now()
                                        );
                              

                                        $where = array('application_code'=>$application_code,       'id'=>$last_query_ref_id);
                                        $previous_data = getPreviousRecords('tra_application_query_reftracker', $where,'mis_db');
                    
                                        $resp = updateRecord('tra_application_query_reftracker', $previous_data, $where, $data, $traderemail_address,'mis_db');
                           }
						   
						   //
						   if($module_id ==4 || $module_id == 12){
							    $this->funcImpApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id);
						   
						   }
						   else if($module_id == 2){
								$this->funcpremisesApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
						   
							   
						   } else if($module_id == 1){
							  
								$this->funcProductsApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
						   
							   
						   } else if($module_id ==3){
								$this->funcGmpApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
						   
							   
						   } else if($module_id == 7){
								$this->funcClinicalTriaApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
						   
							   
						   } else if($module_id == 15){
								$this->funcDisposaApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
						   
							   
						   }else if($module_id == 14){
								$this->funcPromotionaApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
						   
							   
						   }else if($module_id == 29){
								$this->funcPoorQualityProductRptSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
						   
							   
						   }
						   //if($records->module_id == 4 || $records->module_id == 12 || $module_id ==2  || $module_id == 3  || $module_id ==1  || $module_id ==7  || $module_id ==15 || $module_id ==14){
							
						 
                           $res = array('success'=>true, 'message'=>'Application has been submitted successfully.');
                   }else{
                        if($previous_status_id == 2){
						     $res = array('success'=>false, 'message'=>'Application has been submitted successfully.');
					   }
					   else{
						    $res = array('success'=>false, 'message'=>'Application status has not been set, contact the Authority for further guidance..');
					   }
                        
                   }
				   
            }   
      
		
    }
      catch (\Exception $e) {
          $res = array(
              'success' => false,
              'message' => $e->getMessage()
          );
      } catch (\Throwable $throwable) {
          $res = array(
              'success' => false,
              'message' => $throwable->getMessage()
          );
      }
	   
     return $res;
    }
	public function onMisPermitApplicationSubmit(Request $req){
        try{
            $tracking_no = $req->tracking_no;
            $application_code = $req->application_code;
            $status_id = $req->status_id;
            $trader_id = $req->trader_id;
            $remarks = $req->submission_comments;

            $is_fast_track = $req->is_fast_track;
            $paying_currency_id = $req->paying_currency_id;

            $traderemail_address = $req->traderemail_address;
            $data = array();
            //get the records module
            $table_name = $req->table_name;;
            $resp = false;
            $mansite_emails = '';
            if(validateIsNumeric($application_code)){
                $where_state = array('application_code' => $application_code);
            }
            else{
                $where_state = array('tracking_no' => $tracking_no);

            }
           $cc= '';
            $records = DB::connection('mis_db')->table($table_name)
                        ->where($where_state)
                        ->first();
                      $prodclass_category_id = 0;
            if($records){
                    //delete functionality
                    $previous_status_id = $records->application_status_id;
                    $application_code = $records->application_code;
                    $last_query_ref_id = $records->last_query_ref_id;
                    $section_id = $records->section_id;
                    $applicant_id = $records->applicant_id;
 $section_id = $records->section_id;
                    if($previous_status_id < 1){
                        $previous_status_id = 1;
                    }
                    $module_id = $records->module_id;
                    $current_status_id = getSingleRecordColValue('wb_processstatus_transitions', array('module_id' => $module_id,'current_status_id' => $previous_status_id ), 'next_status_id');
                    
                   if($current_status_id > 0){
                        $status_type_id = getSingleRecordColValue('wb_statuses', array('id' => $current_status_id), 'status_type_id');

                            $app_data = array('application_status_id'=>$current_status_id,
                                                 'clinical_registrystatus_id'=>2,
                                                'altered_by'=>$traderemail_address,
                                                'dola'=>Carbon::now()
                                            );
                          
                           $where = array(
                               // 't1.module_id' => $records->module_id,
                                't1.sub_module_id' => $records->sub_module_id,
                                't1.section_id' => $records->section_id
                            );

                            $rec = DB::connection('mis_db')->table('wf_tfdaprocesses as t1')
                                            ->join('wf_workflow_stages as t2', 't1.workflow_id','=','t2.workflow_id')
                                            ->where($where)
                                            ->select('t2.id as current_stage','t1.id as process_id')
                                            ->where('stage_status',1)
                                            ->first();
                            //get the process_id 
							
                            if(validateIsNumeric($trader_id)){
								//$//applicant_data = getTableData('wb_trader_account', array('id'=>$trader_id));
                                  //          $applicantidentification_no = $applicant_data->identification_no;
                                            $applicant  = getTableData('wb_trader_account', array('identification_no'=>$applicantidentification_no),'mis_db');
                                          
								//$applicant_id = $applicant->id;
							}else{
								$applicant_data  = getTableData('wb_trader_account', array('id'=>$applicant_id),'mis_db');
                                          
							}
							
                            $view_id = $this->generateApplicationViewID(); 
                            $tracking_no = $records->tracking_no;
							$zone_id = 2;
							if(isset($record->zone_id)){
								$zone_id = $record->zone_id;
							}
							
                            $onlinesubmission_data  = array('application_code'=>$records->application_code,
                                    'reference_no'=>$records->reference_no,
                                    'tracking_no'=>$records->tracking_no,
                                    'application_id'=>$records->id,
                                    'prodclass_category_id'=>$prodclass_category_id,
                                    'view_id'=>$view_id,
                                    'process_id'=>$rec->process_id,
                                    'current_stage'=>$rec->current_stage,
                                    'previous_stage'=>$rec->current_stage,
                                    'module_id'=>$records->module_id,
                                    'sub_module_id'=>$records->sub_module_id,
                                    'section_id'=>$records->section_id,
                                    'application_status_id'=>$records->application_status_id,
                                    'remarks'=>$remarks,
                                    'applicant_id'=>$applicant_id,
                                    'is_notified'=>0,
                                    'status_type_id'=>$status_type_id,
									'onlinesubmission_status_id'=>1,
									
									'zone_id'=>$zone_id,
                                    'date_submitted'=>Carbon::now(),
                                    'created_on'=>Carbon::now(),
                                    'created_by'=>$trader_id
                            );
                               
                            $previous_data = getPreviousRecords($table_name, $where_state,'mis_db');
                            
                            $resp = updateRecord($table_name, $previous_data, $where_state, $app_data, $traderemail_address,'mis_db');
                            $resp =  insertRecord('tra_onlinesubmissions', $onlinesubmission_data, $traderemail_address,'mis_db');
							
							//insert in the tra_submission too 
							  $onlinesubmission_data  = array('application_code'=>$records->application_code,
                                    'reference_no'=>$records->reference_no,
                                    'tracking_no'=>$records->tracking_no,
                                    'view_id'=>$view_id,
                                    'process_id'=>$rec->process_id,
                                    'current_stage'=>$rec->current_stage,
                                    'previous_stage'=>$rec->current_stage,
                                    'module_id'=>$records->module_id,
                                    'sub_module_id'=>$records->sub_module_id,
                                    'section_id'=>$records->section_id,
                                    'application_status_id'=>1,
                                    'remarks'=>'Online Submission: '.$remarks,
                                    'applicant_id'=>$applicant_id,
                                    'isDone'=>1,
                                    'isRead'=>1,
									'zone_id'=>$zone_id,
                                    'created_on'=>Carbon::now(),
                                    'created_by'=>$trader_id
                            );
							insertRecord('tra_submissions', $onlinesubmission_data, $traderemail_address,'mis_db');
							
							
                           if($previous_status_id == 8 || $previous_status_id == 6){
                                 //update the query tracker table 
                                    $data = array('responded_on'=>Carbon::now(),                'responded_by'=>$traderemail_address,
                                        'queryref_status_id'=>2,
                                        'dola'=>Carbon::now()
                                        );
                              

                                        $where = array('application_code'=>$application_code,       'id'=>$last_query_ref_id);
                                        $previous_data = getPreviousRecords('tra_application_query_reftracker', $where,'mis_db');
                    
                                        $resp = updateRecord('tra_application_query_reftracker', $previous_data, $where, $data, $traderemail_address,'mis_db');
                           }
                           
                   }else{
                        if($previous_status_id == 2){
						     $res = array('success'=>false, 'message'=>'Application has been submitted successfully.');
					   }
					   else{
						    $res = array('success'=>false, 'message'=>'Application status has not been set, contact the Authority for further guidance..');
					   }
                        
                   }
            }
        
            if($resp['success']){
                //send emails 
                $bcc = array();
                $app_description = "";
                $local_agent_id = '';
                $trader = 'Trader';
                
                $trader_emails =  $applicant_data->email;
                $registrant =  $applicant_data->name;
                $physical_address = $applicant_data->physical_address;
                if($module_id == 1){
                    $local_agent_id = $records->local_agent_id;
                    //get manufacturers details
                    $product_id = $records->product_id;
                    $products = getSingleRecord('wb_product_information', array('id'=>$product_id));
                     $manufacturers_id =  getRecordValFromWhere('wb_product_manufacturers', array('product_id'=>$product_id), 'man_site_id');
                     $manufacturers_id =  convertAssArrayToSimpleArray($manufacturers_id, 'man_site_id','mis_db');
                     //email addresses for manufacturer 
                     $mansite_records = (array)getRecordsWithIds('par_man_sites',$manufacturers_id,'email_address','mis_db');
                     $mansite_emails =  convertAssArrayToSimpleArray($mansite_records, 'email_address');
                     $bcc = array_merge($bcc,$mansite_emails);
                     $app_description = "Application details: Brand Name: ".$products->brand_name.", Registrant: ".$registrant.", Physical Address: ".$physical_address;
					 

                }
				else if($module_id == 2){
					$cc = array();
					$premise_id = $records->premise_id;
                    $premises_email =   getSingleRecordColValue('wb_premises', array('id'=>$premise_id),'email');
					 $cc[] = $premises_email;
				}
                else if($module_id == 3){
                    $local_agent_id = $records->local_agent_id;
                    $manufacturing_site_id = $records->manufacturing_site_id;
                    $site_name =   getSingleRecordColValue('wb_manufacturing_sites', array('id'=>$manufacturing_site_id),'name');
                    $app_description = "Manufacturing Site Name: ".$site_name;
                }
                if(validateIsNumeric($local_agent_id)){
					$cc = array();
                    $local_agent_email = getSingleRecordColValue('tra_premises', array('id' => $local_agent_id), 'email','mis_db');
                    $cc[] = $local_agent_email;

                }
               // $cc = '';
                if($previous_status_id == 1){
                    //send email to 
                    $template_id = 9;
                  //  $subject  = "NOTIFICATION FOR ONLINE APPLICATION SUBMISSION FOR TRACKING NO ".$tracking_no;
                    //send to manufacturers 
                    if(is_array($mansite_emails)){
                        $mansite_emails = implode(';',$mansite_emails);
                        $vars = array(
                            '{tracking_no}' => $tracking_no,
                            '{app_description}' => $app_description
                        );
                        $email_template = getEmailTemplateInfo(11, $vars);
                        $email_content = $email_template->body;
                        $subject = $email_template->subject;
                        $response=  sendMailNotification($trader, $trader_emails,$subject,$email_content,$mansite_emails);   
                    }
                } else if($previous_status_id == 6 || $previous_status_id == 7 || $previous_status_id == 8 || $previous_status_id == 9){
                    $template_id = 10;
                   // $subject  = "NOTIFICATION FOR ONLINE APPLICATION QUERY RESPONSE FOR TRACKING NO ".$tracking_no;

                }
                else{
                    $template_id = 9;
                 
                }
               
                $vars = array(
                    '{tracking_no}' => $tracking_no,
                    '{app_description}' => $app_description
                );
                $attachement = '';
                $subject = ' SELF SERVICE PORTAL APPLICATION SUBMISSION';
                $email_template = getEmailTemplateInfo($template_id, $vars);
                $email_content = $email_template->body;
                $subject = $email_template->subject;
                //$bcc = implode(';',$bcc);
                if(is_array($bcc)){
                    $bcc = implode(';',$bcc);
                }
                if(is_array($cc)){
                    $cc = implode(';',$cc);
                }
                //get the other notifications 
                $department_emails = $this->getDepartmentalUsersEmails($module_id,$section_id, 1);
                
                if($department_emails != ''){
                  
                   $department_emails = implode(';',$department_emails);
                   //concatenate 
                   $cc = $cc.';'.$department_emails;
                   
                   
        
                }
                
                $response=  sendMailNotification($trader, $trader_emails,$subject,$email_content,$cc,$bcc);
                $res = array('success'=>true, 
                             'message'=>'Application has been submitted Successfully for processing.');

            }   
            else{
                $res = array('success'=>false ,'message1'=>$resp ,'message'=>$resp['message']);
            }    
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
        
    
    }
    public function getApplicationPreQueriesDetails(Request $req){
        try{
            $table_name = $req->table_name;
            $application_code = $req->application_code;
            $status_column = $req->status_column;
            //tra_online_queries
            $data = array();
            $records = DB::table($table_name.' as t1')
                          ->leftJoin('wb_statuses as t2',  $status_column, '=','t2.id')
                          ->join('tra_online_queries as t3', 't1.application_code', '=','t3.application_code')
                          ->select('t1.tracking_no','t2.name as application_status','t3.application_section_id' ,'t3.query_txt as queries_remarks','t3.mis_created_on as added_on', 't3.id', 't3.response_txt')
                          ->where(array('t1.application_code'=>$application_code))
                          ->whereIn('status_id',[1,3])
                          ->get();
            foreach($records as $rec){
                $application_section = getSingleRecordColValue('par_application_sections', array('id'=>$rec->application_section_id), 'application_section','mis_db');

                    $data[] = array('tracking_no'=>$rec->tracking_no, 
                                    'application_status'=>$rec->application_status,
                                    'queries_remarks'=>$rec->queries_remarks,
                                    'added_on'=>$rec->added_on, 
                                    'id'=>$rec->id,
                                    'application_section_id'=>$rec->application_section_id,
                                    'application_section'=>$application_section,
                                     'response_txt'=>$rec->response_txt );
                                
            }
              $res = array('success'=>true, 
                          'data'=>$data
                          );
      }
      catch (\Exception $e) {
          $res = array(
              'success' => false,
              'message' => $e->getMessage()
          );
      } catch (\Throwable $throwable) {
          $res = array(
              'success' => false,
              'message' => $throwable->getMessage()
          );
      }
      return response()->json($res);

        
    }
    public function onsaveProductConfigData(Request $req){
        $name = $req->input('name');
        $description = $req->input('description');
        $atc_code_id = $req->input('atc_code_id');
        $section_id = $req->input('section_id');
        $table_name = $req->input('tablename');
        if($table_name == 'par_common_names'){
            $table_data = array('name'=>$name, 'description'=>$description,'section_id'=>$section_id,'atc_code_id'=>$atc_code_id);

        }
        else{
            $table_data = array('name'=>$name, 'description'=>$description,'section_id'=>$section_id);

        }
        try {
            $count = DB::connection('mis_db')
                    ->table($table_name)
                    ->where('name', 'like', '%' . $name . '%')
					->where('section_id',$section_id)
                    ->count();

            if ($count == 0) {
                $res = insertRecord($table_name, $table_data, '','mis_db');
                
            } else {
                $res = array('success'=>false,'message'=>'The record exists, search on the provided list');
              
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);

    }
    public function onSaveUniformConfigData(Request $req){
        $name = $req->input('name');
        $description = $req->input('description');
        $section_id = $req->input('section_id');
        $table_name = $req->input('tablename');
        $table_data = $req->all();
        unset($table_data['tablename']);
        unset($table_data['application_code']);

        unset($table_data['trader_email']);
        unset($table_data['trader_id']);

        try {
            $count = DB::connection('mis_db')
                    ->table($table_name)
                    ->where('name', 'like', '%' . $name . '%')
                    ->count();

            if ($count == 0) {
                $res = insertRecord($table_name, $table_data, '','mis_db');
                
            } else {
                $res = array('success'=>false,'message'=>'The record exists, search on the provided list');
              
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);

    }
    
    public function onSavePrecheckingqueryresponse(Request $req)
    {
        
        $trader_id = $req->trader_id;
        $id = $req->input('id');
        $response = $req->input('response_txt');
        $query_id = $req->input('query_id');
        $table_name = 'checklistitems_queryresponses';
        $where = array(
            'id' => $id
        );
        $table_data = array(
            'response' => $response,
            'created_by'=>$trader_id,
            'query_id'=>$query_id,
            'created_on'=>Carbon::now()
        );
        try {
            $prev_data = getPreviousRecords($table_name, $where, 'mis_db');
            if ($prev_data['success'] == true && validateisNumeric($id)) {
            
                $previous_data = $prev_data;
                $res = updateRecord($table_name, $previous_data, $where, $table_data, $trader_id,'mis_db');
                //update the 
                
            } else {
                $res = insertRecord($table_name, $table_data, $trader_id,'mis_db');
                DB::connection('mis_db')->table('checklistitems_queries')->where(array('id'=>$query_id))->update(array('status'=>2));
              
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }

    public function getAllApplicationQueriesData(Request $req)
    {
	
        $application_code = $req->application_code;
        try {
            //get the query ID
			$structuredQueries = convertStdClassObjToArray($this->getAllApplicationStructuredQueries($req));
            $unStructuredQueries = convertStdClassObjToArray($this->getAllApplicationNonStructuredQueries($req));
            $results = array_merge($structuredQueries, $unStructuredQueries);
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
            
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    public function validateApplicationQueryresponse(Request $req){
        
                                try {
                                    //get the query ID 
                                    $table_name  = $req->table_name;
                                    $application_code  = $req->application_code;
                                    $last_query_ref_id =  getSingleRecordColValue($table_name, array('application_code'=>$application_code), 'last_query_ref_id');
								if(validateisNumeric($last_query_ref_id)){
									$non_respondedquery = DB::connection('mis_db')->table('tra_application_query_reftracker as t1')    
                                                            ->join('tra_queries_referencing as t2', 't2.query_ref_id','=','t1.id')
                                                            ->join('checklistitems_queries as t3', 't3.id', '=','t2.query_id')
                                                            ->leftJoin('checklistitems_queryresponses as t5', 't3.id', '=', 't5.query_id')
                                                            ->where(array('t1.application_code'=>$application_code, 't1.id'=>$last_query_ref_id))
                                                            ->whereNull('t5.id')
                                                            ->count();
								}
								else{
									
									$non_respondedquery = DB::connection('mis_db')->table('checklistitems_queries as t3')
                                                            ->leftJoin('checklistitems_queryresponses as t5', 't3.id', '=', 't5.query_id')
                                                            ->where(array('t3.application_code'=>$application_code))
                                                            ->whereNull('t5.id')
                                                            ->count();
									
								}
                                    
                                    if($non_respondedquery > 0){
                                            $res = array('success'=>true,
                                                        'message'=>'Respond to all queries to proceed!!!');

                                    }
                                    else{
                                        $res = array('success'=>true,
                                                     'message'=>'All is well!!!');

                                    }
                                    
                                } catch (\Exception $exception) {
                                    $res = array(
                                        'success' => false,
                                        'message' => $exception->getMessage()
                                    );
                                } catch (\Throwable $throwable) {
                                    $res = array(
                                        'success' => false,
                                        'message' => $throwable->getMessage()
                                    );
                                }
                                return response()->json($res);                        

    }
    public function validateSampleProductDetails(Request $req){
        
        try {
            //get the query ID 
            $table_name  = $req->table_name;
            $application_code  = $req->application_code;
            $product_id =  getSingleRecordColValue('tra_product_applications', array('application_code' => $req->application_code), 'product_id','mis_db');
            
            
			$count = DB::connection('mis_db')->table($table_name.' as t1') 
                                    ->where(array('t1.product_id'=>$product_id))
                                    ->count();
            if($count > 0){
                    $res = array('success'=>true,
                             'message'=>'All is well!!!');
            }
            else{
                
$res = array('success'=>false,
                                'message'=>'Enter the Details to proceed!!');

            }
            
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);                        

}
    
    public function getAllApplicationStructuredQueries($req){
        $table_name  = $req->table_name;
        $application_code  = $req->application_code;
       $last_query_ref_id =  getSingleRecordColValue($table_name, array('application_code'=>$application_code), 'last_query_ref_id');
        $qry = DB::connection('mis_db')->table('checklistitems_queries as t3')
                            ->leftJoin('checklistitems_responses as t8', 't8.id', '=','t3.item_resp_id')
                            ->leftJoin('par_checklist_items as t4', 't8.checklist_item_id', '=', 't4.id')
                            ->leftJoin('checklistitems_queryresponses as t5', 't3.id', '=', 't5.query_id')
                            ->leftJoin('par_query_statuses as t6', 't3.status', '=', 't6.id')
                            ->leftJoin('par_application_sections as t7', 't3.application_section_id', '=', 't7.id')
                            ->select('t3.id as query_id','t5.id','t7.name as query_status', 't4.name as checklist_item', 't3.query as queries_remarks','t3.created_on as added_on', 't5.response as response_txt','t7.application_section')
                            ->where(array('t3.application_code'=>$application_code));

        $results = $qry->get();
        return $results;

        
    }
    public function getAllApplicationNonStructuredQueries($req){
        $table_name  = $req->table_name;
        $application_code  = $req->application_code;
       $last_query_ref_id =  getSingleRecordColValue($table_name, array('application_code'=>$application_code), 'last_query_ref_id');
     
       $qry = DB::connection('mis_db')->table('tra_application_query_reftracker as t1')
                            ->leftJoin('tra_queries_referencing as t2', 't2.query_ref_id','=','t1.id')
                            ->leftJoin('checklistitems_queries as t3', 't3.id', '=','t2.query_id')
                            ->leftJoin('par_checklist_items as t4', 't3.checklist_item_id', '=', 't4.id')
                            ->leftJoin('checklistitems_queryresponses as t5', 't3.id', '=', 't5.query_id')
                            ->leftJoin('par_query_statuses as t6', 't3.status', '=', 't6.id')
                            ->leftJoin('par_application_sections as t7', 't3.application_section_id', '=', 't7.id')
                            ->select('t3.id as query_id','t5.id','t7.name as query_status', 't4.name as checklist_item', 't3.query as queries_remarks','t3.created_on as added_on', 't5.response as response_txt','t7.application_section')
                            ->where(array('t1.application_code'=>$application_code))
                            ->whereNull('t3.item_resp_id');
//, 't1.id'=>$last_query_ref_id
        $results = $qry->get();

        return $results;

        
    }
    public function onSaveinitqueryresponse(Request $req){
        try{
            $response_txt = $req->response_txt;
            $application_code = $req->application_code;
            $id = $req->id;
            $trader_email = $req->trader_email;
            $data = array();
            //get the records 
            $table_name = 'tra_online_queries';
            $resp = false;
            $where_state = array('application_code' => $application_code,'id'=>$id);
            $records = DB::table($table_name)
                        ->where($where_state)
                        ->first();
            if($records){
                  
                    $query_data = array('response_txt'=>$response_txt,
                                            'response_by'=>$trader_email,
                                            'status_id'=>2,
                                            'responded_on'=>Carbon::now()
                                    );
                    
                    $previous_data = getPreviousRecords($table_name, $where_state);
                    $resp = updateRecord($table_name, $previous_data, $where_state, $query_data, $trader_email,'mysql');
                          
            }
          
            if($resp['success']){
                $res = array('success'=>true, 'message'=>'Query response record Saved successfully.');
    
            }   
            else{
                $res = array('success'=>false, 'message'=>' Application query response save failed, contact the system admin if this persists');
            }    
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
        
    }   
    function getProductLabelsRequiremnts ($document_type_id,$sub_module_id,$section_id){
        $qry = DB::connection('mis_db')->table('par_document_types as t1')
					->join('tra_documentupload_requirements as t2','t1.id','=','t2.document_type_id')
					->select(DB::raw("t2.id as document_requirement_id,
					 t1.name as document_type,t2.name as document_requirement"))
					
					->where(array('t1.id'=>$document_type_id,'sub_module_id'=>$sub_module_id,'section_id'=>$section_id));
					
            $data = $qry->get();
            return $data;
    }
    public function funcValidateApplicationLabels(Request $req){
        try{
            $application_code = $req->application_code;
            $document_type_id = $req->document_type_id;
            $status_id = $req->status_id;
            $id = $req->id;
            $trader_email = $req->trader_email;
            $data = array();
            //get the records 
         
			 $table_name = $req->table_name;
            $resp = false;
			
			$app_data = DB::table($table_name)->where(array('application_code'=>$application_code))->first();
            
			if($app_data){
				//filter documetns requirements 
				$section_id = $app_data->section_id;
				$sub_module_id = $app_data->sub_module_id;
				$status_id = $app_data->application_status_id;
				$product_id = $app_data->product_id;
				$document_reqdata = $this->getProductLabelsRequiremnts($document_type_id,$sub_module_id,	$section_id);
                
				if(count($document_reqdata) > 0){
					$where_state = array('portal_product_id' => $product_id);
					$records = DB::connection('mis_db')->table('tra_uploadedproduct_images')
								->where($where_state)
								->get();

					if(count($records) > 0){
						$res = array('success'=>true, 'message'=>'Upload Product Labels to Proceed.');
					}
					else{
						$res = array('success'=>true, 'message'=>'Upload Product Labels to Proceed.');
					}
					
				}
				else{
					$res = array('success'=>true, 'message'=>'Upload Product Labels to Proceed.');
					
				}
				
				
			}
			else{
				
			$res = array('success'=>false, 'message'=>'Check application Details.');	
			}
			
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);

    }
	public function validateApplicationDocQuerySubmissionarchive(Request $req){
        try{
            $application_code = $req->application_code;
            $prodclass_category_id = $req->prodclass_category_id;
            $section_id =0;
            $premise_type_id =0;
            $status_id = $req->status_id;
            $id = $req->id;
            $trader_email = $req->trader_email;
            $data = array();
            //get the records 
              $gmp_type_id = '';
              $premise_type_id = '';
              $importexport_permittype_id = '';
			 $table_name = $req->table_name;
            $resp = false;
			
			$app_data = DB::table($table_name)->where(array('application_code'=>$application_code))->first();
            
			if($app_data){
				//filter documetns requirements 
                if($app_data->module_id == 2){
                    $premise_id = $app_data->premise_id;
                    $premise_type_id = getSingleRecordColValue('wb_premises', array('id'=>$req->premise_id), 'premise_type_id');
                }else if($app_data->module_id == 1){
                    $section_id = $app_data->section_id;
                }
				if(isset($app_data->prodclass_category_id)){
                    $prodclass_category_id =$app_data->prodclass_category_id; 
                }
                if(isset($app_data->gmp_type_id)){
                    $gmp_type_id =$app_data->gmp_type_id; 
                } if(isset($app_data->importexport_permittype_id)){
                    $importexport_permittype_id =$app_data->importexport_permittype_id; 
                }
				$sub_module_id = $app_data->sub_module_id;
				$status_id = $app_data->application_status_id;
				
                $document_typedata = getApplicationApplicableDocuments(	$section_id,$sub_module_id,	$status_id,$prodclass_category_id,$gmp_type_id,$premise_type_id,$importexport_permittype_id);
			
					$doc_req = DB::connection('mis_db')->table('tra_documentupload_requirements as t1')
						->leftJoin('par_document_types as t2','t1.document_type_id','=','t2.id')
						->leftJoin('sub_modules as t4','t1.sub_module_id','=','t4.id')
						->leftJoin('modules as t3','t4.module_id','=','t3.id')
						->leftJoin('par_sections as t5','t1.section_id','=','t5.id')
						->select('t1.*','t2.name as document_type', 't1.id as document_requirement_id')
						->where(array('sub_module_id'=>$sub_module_id))
                        ->whereIn('document_type_id',$document_typedata);
                        
                       
						if(validateIsNumeric($gmp_type_id)){
								$doc_req->where('gmp_type_id',$gmp_type_id);
						}
						if(validateIsNumeric($premise_type_id)){
								$doc_req->where('premise_type_id',$premise_type_id);
						}
						if(validateIsNumeric($prodclass_category_id)){
								$doc_req->where('prodclass_category_id',$prodclass_category_id);
						}if(validateIsNumeric($importexport_permittype_id)){
								$doc_req->where('importexport_permittype_id',$importexport_permittype_id);
						}
                        $doc_req = $doc_req->get();
				if(count($doc_req) > 0){
					foreach ($doc_req as $rec) {
						$where_state = array('application_code' => $application_code,'document_requirement_id'=>$rec->document_requirement_id);
						$documents_record = DB::connection('mis_db')->table('tra_application_uploadeddocuments')
								->where($where_state)
								->count();
						if($documents_record == 0 && $rec->is_mandatory ==1){
							// $res = array('success'=>false, 'message'=>'Upload the following Mandatory Document to Proceed: '.$rec->name.$rec->document_requirement_id);

                            $res = array('success'=>false, 'message'=>'Upload the following Mandatory Document to Proceed: '.$rec->name);
							return response()->json($res, 200);
						}
					}
					$res = array('success'=>true, 'message'=>'The required documets have been upload, click next to proceed');
					
				}
				else{
					$res = array('success'=>true, 'message'=>'No Document to upload.');
					
				}
				
				if($status_id == 6 || $status_id == 17){
					
						$table_name = 'wb_query_remarks';
						//check if all th querys has been responded to 
						 $table_name = 'wb_query_remarks';
						$records = DB::table($table_name)
									->where(array('application_code' => $application_code, 'response_txt'=>''))
									->get();
						if(count($records) >0){
							$res = array('success'=>false, 'message'=>'Respond and update all queries before you proceed.');
						}
				}				
				
			}
			else{
				
			$res = array('success'=>false, 'message'=>'Check application Details.');	
			}
					
        }
        catch (\Exception $exception) {
				$res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

			} catch (\Throwable $throwable) {
				$res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
			}
			
			 return response()->json($res, 200);
    }
    
    public function validateApplicationDocQuerySubmission(Request $req){
        try{
            $application_code = $req->application_code;
            $prodclass_category_id = $req->prodclass_category_id;

            $status_id = $req->status_id;
            $id = $req->id;
            $trader_email = $req->trader_email;
            $data = array();
            //get the records 
         
			 $table_name = $req->table_name;
            $resp = false;
			
			$app_data = DB::table($table_name)->where(array('application_code'=>$application_code))->first();

			if($app_data){
				//filter documetns requirements 
				$section_id = $app_data->section_id;
				$sub_module_id = $app_data->sub_module_id;
				$module_id = $app_data->module_id;
			     $status_id = $app_data->application_status_id;
				
				
			if($module_id == 4 || $module_id == 12){
				$where_state = array('application_code' => $application_code);
						$records = DB::connection('mis_db')->table('tra_application_uploadeddocuments')
									->where($where_state)
									->get();


						if(count($records) > 0){

							$res = array('success'=>true, 'message'=>'Upload Application Documents to Proceed.');

						}
						else{
							$res = array('success'=>false, 'message'=>'Upload Application Documents to Proceed.');
						}
						
				
				
			}
			else{
				$where_state = array('application_code' => $application_code);
						$records = DB::connection('mis_db')->table('tra_application_uploadeddocuments')
									->where($where_state)
									->get();


						if(count($records) > 0){

							$res = array('success'=>true, 'message'=>'Upload Application Documents to Proceed.');

						}
						else{
							$res = array('success'=>false, 'message'=>'Upload Application Documents to Proceed.');
						}
				
				
			}
				
				
				if($status_id == 6 || $status_id == 17){
					
                    $table_name = 'wb_query_remarks';
						//check if all th querys has been responded to 
						 $table_name = 'wb_query_remarks';
						$records = DB::table($table_name)
									->where(array('application_code' => $application_code, 'response_txt'=>''))
									->get();
						if(count($records) >0){
							$res = array('success'=>false, 'message'=>'Respond and update all queries before you proceed.');
						}
				}				
				
			}
			else{
				
			$res = array('success'=>true, 'message'=>'Check application Details.');	
			}
			
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    
    public function validateClinicalTrialOtherDetails(Request $req){
        try{
            $application_id = $req->application_id;
            $status_id = $req->status_id;
            $id = $req->id;
            $trader_email = $req->trader_email;
            $data = array();
            //get the records 
            $table_name =  $req->table_name;
            $resp = false;

            $where_state = array('application_id' => $application_id);
            $records = DB::table($table_name)
                        ->where($where_state)
                        ->get();
            if(count($records) > 0){
                  
                $res = array('success'=>true, 'message'=>'All is well.');
         
            }
            else{

                $res = array('success'=>false, 'message'=>'Enter application details to proceed.');

            }
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    public function validateApplicationotherDetails(Request $req){
        try{
            $application_code = $req->application_code;
            $status_id = $req->status_id;
            $id = $req->id;
            $trader_email = $req->trader_email;
            $data = array();
            //get the records 
            $table_name =  $req->table_name;
            $resp = false;


            $where_state = array('application_code' => $application_code);
            $records = DB::table($table_name)
                        ->where($where_state)
                        ->get();
            if(count($records) > 0){
                  
                $res = array('success'=>true, 'message'=>'All is well.');
         
            }
            else{

                $res = array('success'=>false, 'message'=>'Enter application details to proceed.');

            }
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    
    public function onApplicationArchive(Request $req){
        try{
            $table_name = $req->table_name;
            $application_code = $req->application_code;
            $status_id = $req->status_id;
            $trader_id = $req->trader_id;
            $remarks = $req->remarks;
            $traderemail_address = $req->traderemail_address;
            $data = array();
            //get the records 
            $resp = false;
            $where_state = array('application_code' => $application_code);
            $records = DB::table($table_name)
                        ->where($where_state)
                        ->first();

            if($records && validateisNumeric($application_code)){
                    //delete functionality
                    $tracking_no = $records->tracking_no;
                    $previous_status_id = $records->application_status_id;
					if($previous_status_id == 12){
						$current_status_id = 1;
					}
					else{
						
						$current_status_id = 12;
					}
                    
                    $premise_data = array('application_status_id'=>$current_status_id,
                                        'altered_by'=>$traderemail_address,
                                        'dola'=>Carbon::now(),
                                        'submission_date'=>Carbon::now(),
                                    );
                    $submission_data = array('tracking_no'=>$tracking_no,
                                            'application_code'=>$records->application_code,
                                            'trader_id'=>$trader_id,
                                            'remarks'=>$remarks,
                                            'previous_status_id'=>$previous_status_id,
                                            'current_status_id'=>$current_status_id,
                                            'submission_date'=>Carbon::now(),
                                            'created_by'=>$traderemail_address,
                                            'created_on'=>Carbon::now(),
                                        );
                    
                    $previous_data = getPreviousRecords($table_name, $where_state);
                    $resp = updateRecord($table_name, $previous_data, $where_state, $premise_data, $traderemail_address,'mysql');
                    
                    $resp = insertRecord('wb_application_submissions', $submission_data, $traderemail_address,'mysql');
                                     
                    

                 
            }
            if($resp){
                $res = array('success'=>true, 'message'=>'Saved successfully.');
    
            }   
            else{
                $res = array('success'=>false, 'message'=>' Application Submission failed, contact the system admin if this persists');
            }    
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    public function  getapplicationVariationsrequests(Request $req){

        try{
            $data = array();
            $table_name = $req->table_name;
            $application_code = $req->application_code;
    
            $records = DB::table('wb_application_variationsdata as t1')
                        ->where(array('application_code'=>$application_code))
                        ->get();
                        foreach ($records as $rec) {
                            $type_of_variation = getSingleRecordColValue('par_typeof_variations', array('id' => $rec->variation_type_id), 'name','mis_db');
                            $variation_category = getSingleRecordColValue('par_variations_categories', array('id' => $rec->variation_category_id), 'name','mis_db');
                            $premisesvariation_type = getSingleRecordColValue('par_premisesvariation_types', array('id' => $rec->premisesvariation_type_id), 'name','mis_db');
                            //documents Upload 
                            $appuploaded_document_id = $rec->appuploaded_document_id;

                            $variation_data = array('id'=>$rec->id,
                                        'application_code'=>$rec->application_code,
                                        'variation_type_id'=>$rec->variation_type_id,
                                        'variation_category_id'=>$rec->variation_category_id,
                                        'premisesvariation_type_id'=>$rec->premisesvariation_type_id,
                                        'premisesvariation_type'=>$premisesvariation_type,
                                        'present_details'=>$rec->present_details,
                                        'proposed_variation'=>$rec->proposed_variation,
                                        'variation_background_information'=>$rec->variation_background_information,
                                        'name'=>$variation_category,
                                        'variation_category'=>$variation_category,
                                        'type_of_variation'=>$type_of_variation);
                            $document_records = DB::connection('mis_db')->table('tra_application_uploadeddocuments')
                                    ->where(array('application_code'=>$application_code, 'id'=>$appuploaded_document_id))
                                    ->first();

                            if($document_records){
                                $variation_data['node_ref'] = $document_records->node_ref;
                                $variation_data['uploaded_on'] = $document_records->uploaded_on;
                                $variation_data['uploaded_by'] = $document_records->uploaded_by;
                                $variation_data['initial_file_name'] = $document_records->initial_file_name;
                            }

                            $data[] = $variation_data;
                        }
    
        
                $res = array('success'=>true, 
                            'data'=>$data
                            );
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);

      }
      public function  getapplicationWithdrawalrequests(Request $req){

    
        try{
            $data = array();
            $table_name = $req->table_name;
            $application_code = $req->application_code;
    
            $records = DB::table('wb_application_withdrawaldetails as t1')
                        ->where(array('application_code'=>$application_code))
                        ->get();
                        foreach ($records as $rec) {
                            $withdrawal_category = getSingleRecordColValue('par_withdrawal_categories', array('id' => $rec->withdrawal_category_id), 'name','mis_db');
    
                            $data[] = array('id'=>$rec->id,
                                           'application_code'=>$rec->application_code,
                                           'withdrawal_category_id'=>$rec->withdrawal_category_id,
                                            'reason_for_withdrawal'=>$rec->reason_for_withdrawal,
                                            'withdrawal_category'=>$withdrawal_category);
                        }
    
        
                $res = array('success'=>true, 
                            'data'=>$data
                            );
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);

      }
      
      public function onsaveApplicationVariationsrequests(Request $req){
        try{
            $resp ="";
            $trader_id = $req->trader_id;
            $trader_email = $req->trader_email;
            $study_site_id = $req->study_site_id;
            $application_id = $req->application_id;
            $record_id = $req->id;
            $error_message = 'Error occurred, data not saved successfully';
    
            $table_name = 'wb_application_variationsdata';
    
             $data = array('variation_type_id'=>$req->variation_type_id, 
                            'variation_category_id'=>$req->variation_category_id,
                            'present_details'=>$req->present_details,
                            'premisesvariation_type_id'=>$req->premisesvariation_type_id,
                            'proposed_variation'=>$req->proposed_variation,
                            'variation_description_id'=>$req->variation_description_id, 'variationsummary_guidelinesconfig_id'=>$req->variationsummary_guidelinesconfig_id,
                            'variation_background_information'=>$req->variation_background_information,
                            'status_id'=>1,
                            'application_code'=>$req->application_code);
            
            if(validateIsNumeric($record_id)){
                $where = array('id'=>$record_id);
                if (recordExists($table_name, $where)) {
                                
                    $data['dola'] = Carbon::now();
                    $data['altered_by'] = $trader_email;
    
                    $previous_data = getPreviousRecords($table_name, $where);
                    
                    $resp = updateRecord($table_name, $previous_data, $where, $data, $trader_email);
                    
                }
            }
            else{
                //insert 
                $where = $data;
                $data['created_by'] = $trader_email;
                $data['created_on'] = Carbon::now();
                $data['date_added'] = Carbon::now();
                if (!recordExists($table_name, $where)) {
                    $resp = insertRecord($table_name, $data, $trader_email);
                  
                    $record_id = $resp['record_id'];           
                }
                else{
                    $error_message = "The Clinical Trial Variation Request has already been added!!";
                    
                }
            } 
            if($resp){
                $res =  array('success'=>true,
                'record_id'=>$record_id,
                'message'=>'Saved Successfully');
    
            }
            else{
                $res =  array('success'=>false,
                'message'=>$error_message);
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        } 
        return response()->json($res);
    
    
       }
       public function onsaveApplicationQueriesrequests(Request $req){
        try{
            $resp ="";
            $trader_id = $req->trader_id;
            $trader_email = $req->trader_email;
            $application_id = $req->application_id;
            $record_id = $req->id;
            $error_message = 'Error occurred, data not saved successfully';
    
            $table_name = 'checklistitems_queries';
    
             $data = array('query'=>$req->query_details,
                         'checklist_item_id'=>$req->checklist_item_id,'application_code'=>$req->application_code,
                            'status'=>1);
              
            if(validateIsNumeric($record_id)){
                $where = array('id'=>$record_id);
                if (recordExists($table_name, $where,'mis_db')) {
                                
                    $data['dola'] = Carbon::now();
                    //$data['altered_by'] = $trader_email;
    
                    $previous_data = getPreviousRecords($table_name, $where,'mis_db');
                    
                    $resp = updateRecord($table_name, $previous_data, $where, $data, $trader_email,'mis_db');
                    
                }
            }
            else{
                //insert 
                $where = $data;
               // $data['created_by'] = $trader_email;
                $data['created_on'] = Carbon::now();
               
                if (!recordExists($table_name, $where,'mis_db')) {
                    $resp = insertRecord($table_name, $data, $trader_email,'mis_db');
                  
                    $record_id = $resp['record_id'];           
                }
                else{
                    $error_message = "The Query Details has already been added!!";
                    
                }
            } 
            if($resp){
                $res =  array('success'=>true,
                'record_id'=>$record_id,
                'message'=>'Saved Successfully');
    
            }
            else{
                $res =  array('success'=>false,
                'message'=>$error_message);
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        } 
        return response()->json($res);
    
    
       }
       public function onSavesampleDetails(Request $req){
        try{

            $resp ="";
            $trader_id = $req->trader_id;

            $product_id = getSingleRecordColValue('wb_product_applications', array('application_code' => $req->application_code), 'product_id');

            $data = array('manufacturing_date'=>formatDate($req->manufacturing_date),
                        'expiry_date'=>formatDate($req->expiry_date),
                        
						'sample_tracking_no'=>$req->sample_tracking_no,
						'mode_of_delivery'=>$req->mode_of_delivery,
						'quantity'=>$req->quantity,
                        'quantity_unit_id'=>$req->quantity_unit_id,
                        'pack_size'=>0,
                        'pack_unit_id'=>0,
                        'sample_status_id'=>1,
                        'storage_id'=>$req->storage_id,
                        'batch_no'=>$req->batch_no,
                        'product_id'=>$product_id
                    );
            $record_id = $req->id;
            $error_message = 'Error occurred, data not saved successfully';
            
            $table_name = 'wb_sample_information';
            
            if(validateIsNumeric($record_id)){
                $where = array('id'=>$record_id);
                if (recordExists($table_name, $where)) {
                    $data['dola'] = Carbon::now();
                    $data['altered_by'] = $trader_id;
    
                    $previous_data = getPreviousRecords($table_name, $where);
                    $resp = updateRecord($table_name, $previous_data, $where, $data, $trader_id);
	
                }
            }
            else{
                //insert 
                $where = $data;
                $data['created_by'] = $trader_id;
                $data['created_on'] = Carbon::now();
                $data['submission_date'] = Carbon::now();
                if (!recordExists($table_name, $where)) {

                    $resp = insertRecord($table_name, $data, $trader_id);
                    
                    $record_id = $resp['record_id'];  

                }
                else{
                    $resp= array('success'=>false);
                    $error_message = "The Sample Details has already been added!!";
                    
                }
            } 

            if($resp['success']){
                $res =  array('success'=>true,
                'record_id'=>$resp,
                'message'=>'Saved Successfully');
    
            }
            else{
                $res =  array('success'=>false,
                'message'=>$error_message);
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        } 
        return response()->json($res);
    
    
       }
       
       public function onsaveApplicationWithdrawalrequests(Request $req){
        try{
            $resp ="";
            $trader_id = $req->trader_id;
            $trader_email = $req->trader_email;
            $study_site_id = $req->study_site_id;
            $application_id = $req->application_id;
            $record_id = $req->id;
            $error_message = 'Error occurred, data not saved successfully';
    
            $table_name = 'wb_application_withdrawaldetails';
    
             $data = array('withdrawal_category_id'=>$req->withdrawal_category_id, 
                            'reason_for_withdrawal'=>$req->reason_for_withdrawal,
                            'status_id'=>1,
                            'application_code'=>$req->application_code);
               
            if(validateIsNumeric($record_id)){
                $where = array('id'=>$record_id);
                if (recordExists($table_name, $where)) {
                                
                    $data['dola'] = Carbon::now();
                    $data['altered_by'] = $trader_email;
    
                    $previous_data = getPreviousRecords($table_name, $where);
                    
                    $resp = updateRecord($table_name, $previous_data, $where, $data, $trader_email);
                    
                }
            }
            else{
                //insert 
                $where = $data;
                $data['created_by'] = $trader_email;
                $data['created_on'] = Carbon::now();
                $data['date_added'] = Carbon::now();
                if (!recordExists($table_name, $where)) {
                    $resp = insertRecord($table_name, $data, $trader_email);
                  
                    $record_id = $resp['record_id'];           
                }
                else{
                    $error_message = "The Application withdrawal Reason has already been added!!";
                    
                }
            } 
            if($resp){
                $res =  array('success'=>true,
                'record_id'=>$record_id,
                'message'=>'Saved Successfully');
    
            }
            else{
                $res =  array('success'=>false,
                'message'=>$error_message);
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        } 
        return response()->json($res);
    
    
       }
       public function onDeleteUniformAppDetails(Request $req){
        try{
            $record_id = $req->record_id;
            $application_code = $req->application_code;
            $application_id = $req->application_id;
            $table_name = $req->table_name;
            $title = $req->title;
            $email_address = $req->email_address;
            $data = array();
            //get the records 
            $resp = false;
            
            $where_state = array( 'id'=>$record_id);
            if($table_name == 'checklistitems_queries'){
                    $connection = 'mis_db';
            }else{
                $connection = 'mysql';
            }
            $records = DB::connection($connection)->table($table_name)
                    ->where($where_state)
                    ->get();
            
            if(count($records) >0){
                    //delete functionality
                    $previous_data = getPreviousRecords($table_name, $where_state,$connection);
                    $resp = deleteRecordNoTransaction($table_name, $previous_data, $where_state,  $email_address,$connection);
                    if($table_name == 'wb_application_variationsdata'){
                            //unlink te documetns 
                            $appuploaded_document_id = $previous_data['results'][0]['appuploaded_document_id'];
                            $this->deleteDmsDocument($appuploaded_document_id,$application_code);

                    }
            }
            if($resp){
                $res = array('success'=>true, 'message'=>$title.' deleted successfully');
    
            }   
            else{
                $res = array('success'=>false, 'message'=>$title.' delete failed, contact the system admin if this persists');
            }

        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    function deleteDmsDocument($appuploaded_document_id,$application_code){
       
			$table_name = 'tra_application_uploadeddocuments';
            $where_state = array('application_code' => $application_code, 'id'=>$appuploaded_document_id);
			$records = DB::connection('mis_db')->table($table_name)
						->where($where_state)
						->first();
			if($records){
                        $node_ref = $records->node_ref;
                        
						$response = dmsDeleteAppRootNodesChildren($node_ref);
						if($response['success']){
							$previous_data = getPreviousRecords($table_name, $where_state,'mis_db');
							$resp = deleteRecordNoTransaction($table_name, $previous_data, $where_state,  '','mis_db');
						}
			}
    }
    function generateApplicationViewID()
    {
        $view_id = 'tfda' . str_random(10) . date('s');
        return $view_id;
    }
	
	 public function onGroupedPermitApplicationSubmit(Request $req){
        try{
            $group_tracking_no = $req->group_tracking_no;
            $group_application_code = $req->group_application_code;
            $status_id = $req->status_id;
            $trader_id = $req->trader_id;
            $remarks = $req->submission_comments;

            $is_fast_track = $req->is_fast_track;
            $paying_currency_id = $req->paying_currency_id;

            $traderemail_address = $req->traderemail_address;
            $data = array();
            //get the records process_id
            $table_name = $req->table_name;
			$zone_id = 2;

            $resp = false;
            $mansite_emails = '';
            if(validateIsNumeric($group_application_code)){
                $where_state = array('application_code' => $group_application_code);
            }
            else{
                $where_state = array('application_code' => $group_application_code);

            }
			//validate the invoice generation 
			$invoice_records = DB::connection('mis_db')->table('tra_uploadedpayments_details')
                                    ->where($where_state)
                                    ->get();
                            if($invoice_records->count() ==0){ 
                                $res = array('success'=>false, 'message'=>'Proforma Invoice has not been Generated, kindly generate and submit!!');
                                return response()->json($res);
                            }
			//provide the loop 
			 $cc= '';
           if(validateIsNumeric($group_application_code)){
                $where_state = array('group_application_code' => $group_application_code);
            }
            else{
                $where_state = array('group_application_code' => $group_application_code);
			}
			$group_apprecord = DB::table('wb_appsubmissions_typedetails')
									 ->where($where_state)
										->first();
			if($group_apprecord){
				$group_application_code = $group_apprecord->group_application_code;
				$module_id = $group_apprecord->module_id;
				 $where_state = array('group_application_code' => $group_application_code);
				$table_name = getSingleRecordColValue('modules', array('id' => $module_id ), 'portaltable_name','mis_db');
				 $data_records = DB::table($table_name)
                        ->where($where_state)
                        ->get();
				$prodclass_category_id = 0;

				foreach($data_records  as $records){
					
						$status_id=$records->application_status_id;
						$module_id=$records->module_id;
						$application_code =$records->application_code;
						$tracking_no =$records->tracking_no;
						//will change
						$where_state = array('application_code' => $application_code);
						$sub_module_id =$records->sub_module_id;
						$has_invoice_generation = getSingleRecordColValue('sub_modules', array('id' => $sub_module_id ), 'has_invoice_generation','mis_db');
						
						$mis_table_name = getSingleRecordColValue('modules', array('id' => $module_id ), 'table_name','mis_db');
							 
						//delete functionality
						$previous_status_id = $records->application_status_id;

						$application_code = $records->application_code;
						$last_query_ref_id = $records->last_query_ref_id;
						$section_id = $records->section_id;

						if($previous_status_id < 1){
							$previous_status_id = 1;
						}
						$module_id = $records->module_id;

						$current_status_id = getSingleRecordColValue('wb_processstatus_transitions', array('module_id' => $module_id,'current_status_id' => $previous_status_id ), 'next_status_id');

						if($current_status_id > 0){
							$status_type_id = getSingleRecordColValue('wb_statuses', 
							 array('id' => $current_status_id), 'status_type_id');   
							if($records->module_id == 1){
							   // $product_id = $records->product_id;
							  //  $product_data  = getTableData('wb_product_information', array('id'=>$product_id));
							  //  $prodclass_category_id = $product_data->prodclass_category_id;

							}
							$app_data = array('application_status_id'=>$current_status_id,
							  'altered_by'=>$traderemail_address,
								'dola'=>Carbon::now(),
								'submission_date'=>Carbon::now(),
													);

							if(validateIsNumeric($paying_currency_id)){
								$app_data['paying_currency_id'] = $paying_currency_id;
							}
							if(validateIsNumeric($is_fast_track)){
								$app_data['is_fast_track'] = $is_fast_track;
							}
							$submission_data = array('tracking_no'=>$tracking_no,
								'application_code'=>$records->application_code,
								'trader_id'=>$trader_id,
								'remarks'=>$remarks,
								'previous_status_id'=>$previous_status_id,
								'current_status_id'=>$current_status_id,
								'submission_date'=>Carbon::now(),
								'created_by'=>$traderemail_address,
								'created_on'=>Carbon::now(),
								);

									
							$previous_data = getPreviousRecords($table_name, $where_state);
									
							
								 
							insertRecord('wb_application_submissions', $submission_data, $traderemail_address,'mysql');
							 //insert into the other tab;e
							$where = array(
							   // 't1.module_id' => $records->module_id,
								't1.sub_module_id' => $records->sub_module_id,
								't1.section_id' => $records->section_id
								);

							$rec = DB::connection('mis_db')->table('wf_tfdaprocesses as t1')
								->join('wf_workflow_stages as t2', 't1.workflow_id','=','t2.workflow_id')
								->where($where)
								->select('t2.id as current_stage','t1.id as process_id')
								->where('stage_status',1)
								->first();
							//get the process_id 
								 
							$applicant_data = getTableData('wb_trader_account', array('id'=>$trader_id));
							$applicantidentification_no = $applicant_data->identification_no;
							$applicant  = getTableData('wb_trader_account', array('identification_no'=>$applicantidentification_no),'mis_db');
												  
							$view_id = $this->generateApplicationViewID(); 
							$tracking_no = $records->tracking_no;
							if(isset($records->zone_id)){
								$zone_id = $records->zone_id;
							}
							
							$onlinesubmission_data  = array('application_code'=>$records->application_code,
								'reference_no'=>$records->reference_no,
								'tracking_no'=>$records->tracking_no,
								'application_id'=>$records->id,
								'prodclass_category_id'=>$prodclass_category_id,
								'view_id'=>$view_id,
								'process_id'=>$rec->process_id,
							 //   'current_stage'=>$rec->current_stage,
							   // 'previous_stage'=>$rec->current_stage,
								'module_id'=>$records->module_id,
								'sub_module_id'=>$records->sub_module_id,
								'section_id'=>$records->section_id,
								'application_status_id'=>$current_status_id,
								'remarks'=>$remarks,
								'onlinesubmission_status_id'=>1,
								'applicant_id'=>$applicant->id,
								'is_notified'=>0,'zone_id'=>$zone_id,
								'status_type_id'=>$status_type_id,
								'is_fast_track'=>$records->is_fast_track,
								'date_submitted'=>Carbon::now(),
								'created_on'=>Carbon::now(),
								'created_by'=>$trader_id
								);//
								if($records->module_id == 4 || $records->module_id == 12 || $module_id ==2  || $module_id == 3  || $module_id ==1  || $module_id ==7  || $module_id ==15 || $module_id ==14){
									
									$res = $this->onMisApplicationIntraySubmit($req,$table_name,$records->application_code,$records->tracking_no);
									
									
								}else{
									  $res = insertRecord('tra_onlinesubmissions', $onlinesubmission_data, $traderemail_address,'mis_db');
										 
								}
							
								
							$resp = updateRecord($table_name, $previous_data, $where_state, $app_data, $traderemail_address,'mysql');
						
							
							if($previous_status_id == 8 || $previous_status_id == 6){
								//update the query tracker table 
								$data = array('responded_on'=>Carbon::now(),                
										'responded_by'=>$traderemail_address,
										'queryref_status_id'=>2,
										'dola'=>Carbon::now()
										);
									  

								$where = array('application_code'=>$application_code,       'id'=>$last_query_ref_id);
								$previous_data = getPreviousRecords('tra_application_query_reftracker', $where,'mis_db');
							
								$resp = updateRecord('tra_application_query_reftracker', $previous_data, $where, $data, $traderemail_address,'mis_db');

								$where_state = array('application_code'=>$req->application_code,'status'=>1);
											   DB::connection('mis_db')->table('checklistitems_queries')
												->where($where_state)
												->update(array('status'=>2,'dola'=>Carbon::now() ));
															
							}

							//mis_table_name
							$where_app = array('application_code'=>$application_code);
								
							$record = DB::connection('mis_db')->table($mis_table_name)->where($where_app)->first();
							if($record){
								$application_id = $record->id;
									DB::connection('mis_db')->table('tra_submissions')
												->where($where_app)
												->update(array('application_id'=>$application_id,'dola'=>Carbon::now() ));
										
							}
							
							saveApplicationSubmissionDetails($application_code,$table_name);   
							$res = array('success'=>true, 'message'=>'Application has been submitted successfully.');
							
						}else{                    
								$res = array('success'=>true, 'message'=>'Application has been submitted successfully.');
							
						}
						
						
					
					
					
				}
				
				
			}
           $where_state = array('group_application_code' => $group_application_code);
		   //update the grouped 
		   
            if($res['success']){
				$where_state = array('group_application_code' => $group_application_code);
		   //update the grouped 
		   $current_data = array('application_status_id'=>66, 'submission_date'=>Carbon::now(), 'dola'=>Carbon::now());
			   DB::table('wb_appsubmissions_typedetails')
				->where($where_state)
				->update($current_data);
				$group_apprecord =(array)$group_apprecord;
				$where_state = array('group_application_code' => $group_application_code);
				$chec_record = DB::connection('mis_db')->table('tra_appsubmissions_typedetails')->where($where_state)->first();
				
				if(!$chec_record){
					unset($group_apprecord['id']);
					DB::connection('mis_db')->table('tra_appsubmissions_typedetails')
                            ->insert($group_apprecord);
				}
				
				//insert in the tratable
				$applicant_data = getTableData('wb_trader_account', array('id'=>$trader_id));
                $bcc = array();
                $app_description = "";
                $local_agent_id = '';
                $trader = 'Trader';
                $trader_emails =  $applicant_data->email;
                $registrant =  $applicant_data->name;
                $physical_address = $applicant_data->physical_address;
				
				
                $app_description = "Grouped Application Submission: Tracking No : ".$group_tracking_no.", Registrant: ".$registrant.", Physical Address: ".$physical_address;
				
               // $cc = '';
                if($previous_status_id == 1){
                    //send email to 
                    $template_id = 9;
                  
                } else if($previous_status_id == 6 || $previous_status_id == 7 || $previous_status_id == 8 || $previous_status_id == 9){
                    $template_id = 10;
                  
                }
                else{
                    $template_id = 9;
                 
                }
               
                $vars = array(
                    '{group_tracking_no}' => $group_tracking_no,
                    '{app_description}' => $app_description
                );
                $attachement = '';
                $subject = 'RWANDA FDA SELF SERVICE PORTAL APPLICATION SUBMISSION';
                $email_template = getEmailTemplateInfo($template_id, $vars);
                $email_content = $email_template->body;
                $subject = $email_template->subject;
                //$bcc = implode(';',$bcc);
                if(is_array($bcc)){
                    $bcc = implode(';',$bcc);
                }
                if(is_array($cc)){
                    $cc = implode(';',$cc);
                }
                //get the other notifications 
                $department_emails = $this->getDepartmentalUsersEmails($module_id,$section_id, 1);
                
                if($department_emails != ''){
                  
                   $department_emails = implode(';',$department_emails);
                   //concatenate 
                   $cc = $cc.';'.$department_emails;
                
                }
                $response=  sendMailNotification($trader, $trader_emails,$subject,$email_content,$cc,$bcc);
               
				if($res['success']){
						
						 $res = array('success'=>true, 
                             'message'=>'Application has been submitted Successfully for processing.');
				}
						
            }   
            else{
				
					if($res['success']){
						
						$res = array('success'=>true ,
							'message1'=>$res,
							'message'=>$res['message']);
					}
									
            }
			
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
			
        return response()->json($res);
        
    
    }
	
	public function onSubPoorQualityReportDetails(Request $req){
        try{
            $tracking_no = $req->tracking_no;
            $application_code = $req->application_code;
            $status_id = $req->status_id;
            $trader_id = $req->trader_id;
            $remarks = $req->submission_comments;

            $is_fast_track = $req->is_fast_track;
            $paying_currency_id = $req->paying_currency_id;

            $traderemail_address = $req->traderemail_address;
            $data = array();
            //get the records process_id
            $table_name = $req->table_name;
$zone_id = 2;
				
            $resp = false;
            $mansite_emails = '';
            if(validateIsNumeric($application_code)){
                $where_state = array('application_code' => $application_code);
            }
            else{
                $where_state = array('tracking_no' => $tracking_no);

            }
           $cc= '';
            $records = DB::table($table_name)
                        ->where($where_state)
                        ->first();

            $prodclass_category_id = 0;
$total_invoice_amount = 0;
            if($records){
				$status_id=$records->application_status_id;
				$module_id=$records->module_id;
				
                $sub_module_id =$records->sub_module_id;
               
                $previous_status_id = $records->application_status_id;

                $application_code = $records->application_code;
                $section_id = $records->section_id;
	$mis_table_name = getSingleRecordColValue('modules', array('id' => $module_id ), 'table_name','mis_db');
							 
                if($previous_status_id < 1){
                    $previous_status_id = 1;
                }
                $module_id = $records->module_id;

                $current_status_id = getSingleRecordColValue('wb_processstatus_transitions', array('module_id' => $module_id,'current_status_id' => $previous_status_id ), 'next_status_id');

                if($current_status_id > 0){
					
                    $status_type_id = getSingleRecordColValue('wb_statuses', 
                     array('id' => $current_status_id), 'status_type_id');   
                   
                    $app_data = array('application_status_id'=>$current_status_id,
                      'altered_by'=>$traderemail_address,
                        'dola'=>Carbon::now(),
                        'submission_date'=>Carbon::now(),
                                            );

                    if(validateIsNumeric($paying_currency_id)){
                        $app_data['paying_currency_id'] = $paying_currency_id;
                    }
                    if(validateIsNumeric($is_fast_track)){
                        $app_data['is_fast_track'] = $is_fast_track;
                    }
                    $submission_data = array('tracking_no'=>$tracking_no,
                        'application_code'=>$records->application_code,
                        'trader_id'=>$trader_id,
                        'remarks'=>$remarks,
                        'previous_status_id'=>$previous_status_id,
                        'current_status_id'=>$current_status_id,
                        'submission_date'=>Carbon::now(),
                        'created_by'=>$traderemail_address,
                        'created_on'=>Carbon::now(),
                        );

                            
                    $previous_data = getPreviousRecords($table_name, $where_state);
                            
                    insertRecord('wb_application_submissions', $submission_data, $traderemail_address,'mysql');
                     //insert into the other tab;e mis_table_name trader_id
                    $where = array(
                       // 't1.module_id' => $records->module_id,
                        't1.sub_module_id' => $records->sub_module_id,
                        't1.section_id' => $records->section_id
                        );

                    $rec = DB::connection('mis_db')->table('wf_tfdaprocesses as t1')
                        ->join('wf_workflow_stages as t2', 't1.workflow_id','=','t2.workflow_id')
                        ->where($where)
                        ->select('t2.id as current_stage','t1.id as process_id')
                        ->where('stage_status',1)
                        ->first();
                     
                    $view_id = $this->generateApplicationViewID(); 
					$tracking_no = $records->tracking_no;
					
                    $onlinesubmission_data  = array('application_code'=>$records->application_code,
                        'reference_no'=>$records->reference_no,
                        'tracking_no'=>$records->tracking_no,
                        'application_id'=>$records->id,
                        'view_id'=>$view_id,
                        'process_id'=>$rec->process_id,
                        'module_id'=>$records->module_id,
                        'sub_module_id'=>$records->sub_module_id,
                        'section_id'=>$records->section_id,
                        'application_status_id'=>$current_status_id,
                        'remarks'=>$remarks,
						'onlinesubmission_status_id'=>1,
                      
                        'is_notified'=>0,
                        'status_type_id'=>$status_type_id,
                        'date_submitted'=>Carbon::now(),
                        'created_on'=>Carbon::now(),
                        'created_by'=>$trader_id
                        );//
						if($records->module_id == 29){
							
							
							$res = $this->onMisApplicationIntraySubmit($req,$table_name,$records->application_code,$records->tracking_no);
									
							
						
						
						}else{
							  $res = insertRecord('tra_onlinesubmissions', $onlinesubmission_data, $traderemail_address,'mis_db');
								 
						}
					
						
					$resp = updateRecord($table_name, $previous_data, $where_state, $app_data, $traderemail_address,'mysql');
					
						
					$where_app = array('application_code'=>$application_code);
						
					$record = DB::connection('mis_db')->table($mis_table_name)->where($where_app)->first();
					if($record){
						$application_id = $record->id;
							DB::connection('mis_db')->table('tra_submissions')
										->where($where_app)
										->update(array('application_id'=>$application_id,'dola'=>Carbon::now() ));
								
					}
					
                    saveApplicationSubmissionDetails($application_code,$table_name);   
					
                }else{                    
                    if($previous_status_id == 2){
						$res = array('success'=>false, 'message'=>'Application has been submitted successfully.');
					}
					else{
						$res = array('success'=>false, 'message'=>'Application status has not been set, contact the RWANDA FDA Authority for further guidance..');
					   }
                }
				
				
            }

            if($resp['success']){
                //send emails 

                $cc = array();
                $bcc = array();
                $app_description = "";
                $local_agent_id = '';
                $trader = 'Trader';
                $trader_emails =  $records->reporter_email_address;
                $registrant =  $records->name_of_reporter;
               
                $template_id = 37;
                  
                $vars = array(
                    '{tracking_no}' => $tracking_no,
                    '{brand_name}' => $records->brand_name,
                    '{complaint_description}' => $records->complaint_description
                );
                $attachement = '';
                $subject = 'Notification of Reporting of Suspected Poor Quality Product';
                $email_template = getEmailTemplateInfo($template_id, $vars);
                $email_content = $email_template->body;
                $subject = $email_template->subject;
                //$bcc = implode(';',$bcc);
                if(is_array($bcc)){
                    $bcc = implode(';',$bcc);
                }
                if(is_array($cc)){
                    $cc = implode(';',$cc);
                }
                //get the other notifications 
                $department_emails = $this->getDepartmentalUsersEmails($module_id,$section_id, 1);
                
                if($department_emails != ''){
                  
                   $department_emails = implode(';',$department_emails);
                   //concatenate 
                   $cc = $cc.';'.$department_emails;
                
                }
                $response=  sendMailNotification($trader, $trader_emails,$subject,$email_content,$cc,$bcc);
               
				if($res['success']){
						
						 $res = array('success'=>true, 
                             'message'=>'Application has been submitted Successfully for processing.');
					}
						
            }   
            else{
				
					if($res['success']){
						
						$res = array('success'=>true ,
							'message1'=>$res,
							'message'=>$res['message']);
					}
									
            }    
			
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
		
        return response()->json($res);
        
    
    }
	public function onSubmitPaymentNotification(Request $req){
			try{
				
				$records = DB::table('wb_trader_account as t1')
								->leftJoin('wb_traderauthorised_users as t2', 't1.id', 't2.trader_id')
								->leftJoin('wb_apppayment_notification as t3', 't1.id', 't3.trader_id')
								
								->select(DB::raw("t1.id as trader_id, t1.name as customer_name, t1.email,group_concat(t2.email SEPARATOR  ';') as users_emails "))
								->whereNotIn('t1.id', [25])
								->whereNull('t3.id')
								->groupBy('t1.id')
								->get();
				if($records){
					
						foreach($records  as $rec){
								$customer_name = $rec->customer_name;
								$customer_email = str_replace(' ','',$rec->email);
								$customer_email = rtrim($rec->email);
								$trader_id = $rec->trader_id;
								$users_emails = str_replace(',','',$rec->users_emails);
								$users_emails = str_replace(' ','',$rec->users_emails);
								$users_emails = ltrim($rec->users_emails, ';');
								$template_id = 38;
                  
								$vars = array(
									'{applicant_name}' => $customer_name
								);
								$attachement = '';
								
								$email_template = getEmailTemplateInfo($template_id, $vars);
								$email_content = $email_template->body;
								$subject = $email_template->subject;
								if($this->validateEmail($customer_email) != ''){
									
									 $res=  sendMailNotification($customer_name, trim($customer_email),$subject,$email_content,$users_emails);
										
									   if($res['success']){
										   $data = array('subject'=>$subject, 
															'message'=>$email_content,
															'sent_on'=>Carbon::now(),
															'trader_id'=>$trader_id,
															'email_address'=>$customer_email,
															'users_emails'=>$users_emails );
															
											DB::table('wb_apppayment_notification')->insert($data);
										   
									   }
									
								}
								
							  
						}
				}
				
				
			}
			catch (\Exception $e) {
				$res = array(
					'success' => false,
					'message' => $e->getMessage()
				);
			} catch (\Throwable $throwable) {
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			return response()->json($res);
			
		
		
		
		
	} function validateEmail($email_address){
		$email_address = preg_replace('/\s+/', '', $email_address);
		// Check the formatting is correct
		if(filter_var($email_address, FILTER_VALIDATE_EMAIL) === false){
			$email_address = '';
		}
		return $email_address;
		
    }
	
    public function onPermitApplicationSubmit(Request $req){
        try{
            $tracking_no = $req->tracking_no;
            $application_code = $req->application_code;
            $status_id = $req->status_id;
            $trader_id = $req->trader_id;
            $remarks = $req->submission_comments;

            $is_fast_track = $req->is_fast_track;
            $paying_currency_id = $req->paying_currency_id;

            $traderemail_address = $req->traderemail_address;
            $data = array();
            //get the records process_id
            $table_name = $req->table_name;
$zone_id = 2;
				
            $resp = false;
            $mansite_emails = '';
            if(validateIsNumeric($application_code)){
                $where_state = array('application_code' => $application_code);
            }
            else{
                $where_state = array('tracking_no' => $tracking_no);

            }
           $cc= '';
            $records = DB::table($table_name)
                        ->where($where_state)
                        ->first();

            $prodclass_category_id = 0;
$total_invoice_amount = 0;
            if($records){
				$status_id=$records->application_status_id;
				$module_id=$records->module_id;
				$trader_id=$records->trader_id;
                //will change
				 
                $sub_module_id =$records->sub_module_id;
                $has_invoice_generation = getSingleRecordColValue('sub_modules', array('id' => $sub_module_id ), 'has_invoice_generation','mis_db');
                
                $mis_table_name = getSingleRecordColValue('modules', array('id' => $module_id ), 'table_name','mis_db');
                     //validate is invoice generated  zone_id
                     if($status_id == 1 || $status_id ==17){
                         if($has_invoice_generation == 1){
                            $invoice_records = DB::connection('mis_db')->table('tra_application_invoices as t1')
											->where($where_state)
											->first();
									if(!$invoice_records){ 
										$res = array('success'=>false, 'message'=>'Proforma Invoice has not been Generated, kindly generate and submit!!');
										return response()->json($res);
									}else{
										$invoice_records = DB::connection('mis_db')->table('tra_application_invoices as t1')
											->join('tra_invoice_details as t2', 't1.id', 't2.invoice_id')
											->select(DB::raw("sum(total_element_amount) as total_invoice_amount"))
											->where($where_state)
											->first();
										
										
										$total_invoice_amount = $invoice_records->total_invoice_amount;
									}
									
                         }//check for proof of payment 
						
						 
						 if($has_invoice_generation == 1){
                            $uploadedpayment_records = DB::connection('mis_db')->table('tra_uploadedpayments_details')
                                    ->where($where_state)
                                    ->get();
							$payment_records = DB::connection('mis_db')->table('tra_payments')
                                    ->where($where_state)
                                    ->get();
									if($total_invoice_amount >0){
										
										  if($uploadedpayment_records->count() ==0 && $payment_records->count() ==0){ 
											$res = array('success'=>false, 'message'=>'Kindly Upload the proof of payments on the generated Proforma Invoice for processing purposes!!');
											return response()->json($res);
										}
									}
                          
                         }
                        
                    }
                //delete functionality
                $previous_status_id = $records->application_status_id;

                $application_code = $records->application_code;
                $last_query_ref_id = $records->last_query_ref_id;
                $section_id = $records->section_id;

                if($previous_status_id < 1){
                    $previous_status_id = 1;
                }
                $module_id = $records->module_id;

                $current_status_id = getSingleRecordColValue('wb_processstatus_transitions', array('module_id' => $module_id,'current_status_id' => $previous_status_id ), 'next_status_id');

                if($current_status_id > 0){
                    $status_type_id = getSingleRecordColValue('wb_statuses', 
                     array('id' => $current_status_id), 'status_type_id');   
                    if($records->module_id == 1){
                       // $product_id = $records->product_id; process_id
                      //  $product_data  = getTableData('wb_product_information', array('id'=>$product_id));
                      //  $prodclass_category_id = $product_data->prodclass_category_id;

                    }
                    $app_data = array('application_status_id'=>$current_status_id,
                      'altered_by'=>$traderemail_address,
                        'dola'=>Carbon::now(),
                        'submission_date'=>Carbon::now(),
                                            );

                    if(validateIsNumeric($paying_currency_id)){
                        $app_data['paying_currency_id'] = $paying_currency_id;
                    }
                    if(validateIsNumeric($is_fast_track)){
                        $app_data['is_fast_track'] = $is_fast_track;
                    }
                    $submission_data = array('tracking_no'=>$tracking_no,
                        'application_code'=>$records->application_code,
                        'trader_id'=>$trader_id,
                        'remarks'=>$remarks,
                        'previous_status_id'=>$previous_status_id,
                        'current_status_id'=>$current_status_id,
                        'submission_date'=>Carbon::now(),
                        'created_by'=>$traderemail_address,
                        'created_on'=>Carbon::now(),
                        );

                            
                    $previous_data = getPreviousRecords($table_name, $where_state);
                            
                    
                         
                    insertRecord('wb_application_submissions', $submission_data, $traderemail_address,'mysql');
                     //insert into the other tab;e
                    $where = array(
                       // 't1.module_id' => $records->module_id,
                        't1.sub_module_id' => $records->sub_module_id,
                        't1.section_id' => $records->section_id
                        );

                    $rec = DB::connection('mis_db')->table('wf_tfdaprocesses as t1')
                        ->join('wf_workflow_stages as t2', 't1.workflow_id','=','t2.workflow_id')
                        ->where($where)
                        ->select('t2.id as current_stage','t1.id as process_id')
                        ->where('stage_status',1)
                        ->first();
                    //get the process_id 
                       
                   $applicant_data = getTableData('wb_trader_account', array('id'=>$trader_id));
                   //$applicantidentification_no = $applicant_data->identification_no;
                   $applicant  = getTableData('wb_trader_account', array('id'=>$trader_id),'mis_db');
                        	  
                    $view_id = $this->generateApplicationViewID(); 
					$tracking_no = $records->tracking_no;
					if(isset($records->zone_id)){
						$zone_id = $records->zone_id;
					}
					
                    $onlinesubmission_data  = array('application_code'=>$records->application_code,
                        'reference_no'=>$records->reference_no,
                        'tracking_no'=>$records->tracking_no,
                        'application_id'=>$records->id,
                        'prodclass_category_id'=>$prodclass_category_id,
                        'view_id'=>$view_id,
                        'process_id'=>$rec->process_id,
                     //   'current_stage'=>$rec->current_stage,
                       // 'previous_stage'=>$rec->current_stage,
                        'module_id'=>$records->module_id,
                        'sub_module_id'=>$records->sub_module_id,
                        'section_id'=>$records->section_id,
                        'application_status_id'=>$current_status_id,
                        'remarks'=>$remarks,
						'onlinesubmission_status_id'=>1,
                        'applicant_id'=>$applicant->id,
                        'is_notified'=>0,'zone_id'=>$zone_id,
                        'status_type_id'=>$status_type_id,
                        'is_fast_track'=>$records->is_fast_track,
                        'date_submitted'=>Carbon::now(),
                        'created_on'=>Carbon::now(),
                        'created_by'=>$trader_id
                        );//
						if($records->module_id == 4 || $records->module_id == 12 || $module_id ==2  || $module_id == 3  || $module_id ==1  || $module_id ==7  || $module_id ==15 || $module_id ==14){
							
							
							$res = $this->onMisApplicationIntraySubmit($req,$table_name,$records->application_code,$records->tracking_no);
									
							
						
						
						}else{
							  $res = insertRecord('tra_onlinesubmissions', $onlinesubmission_data, $traderemail_address,'mis_db');
								 
						}
					
						
					$resp = updateRecord($table_name, $previous_data, $where_state, $app_data, $traderemail_address,'mysql');
					
					
                    if($previous_status_id == 8 || $previous_status_id == 6){
                        //update the query tracker table 
                        $data = array('responded_on'=>Carbon::now(),                
                                'responded_by'=>$traderemail_address,
                                'queryref_status_id'=>2,
                                'dola'=>Carbon::now()
                                );
                              

                        $where = array('application_code'=>$application_code,       'id'=>$last_query_ref_id);
                        $previous_data = getPreviousRecords('tra_application_query_reftracker', $where,'mis_db');
                    
                        $resp = updateRecord('tra_application_query_reftracker', $previous_data, $where, $data, $traderemail_address,'mis_db');

						$where_state = array('application_code'=>$req->application_code,'status'=>1);
									   DB::connection('mis_db')->table('checklistitems_queries')
										->where($where_state)
										->update(array('status'=>2,'dola'=>Carbon::now() ));
													
					}

					//mis_table_name
					$where_app = array('application_code'=>$application_code);
						
					$record = DB::connection('mis_db')->table($mis_table_name)->where($where_app)->first();
					if($record){
						$application_id = $record->id;
							DB::connection('mis_db')->table('tra_submissions')
										->where($where_app)
										->update(array('application_id'=>$application_id,'dola'=>Carbon::now() ));
								
					}
					
                    saveApplicationSubmissionDetails($application_code,$table_name);   
					
                }else{                    
                    if($previous_status_id == 2){
						$res = array('success'=>false, 'message'=>'Application has been submitted successfully.');
					}
					else{
						$res = array('success'=>false, 'message'=>'Application status has not been set, contact the RWANDA FDA Authority for further guidance..');
					   }
                }
				
				
            }

            if($resp['success']){
                //send emails 

                $bcc = array();
                $app_description = "";
                $local_agent_id = '';
                $trader = 'Trader';
                $trader_emails =  $applicant_data->email;
                $registrant =  $applicant_data->name;
                $physical_address = $applicant_data->physical_address;
                if($module_id == 1){
                    $local_agent_id = $records->local_agent_id;
                    //get manufacturers details
                    $product_id = $records->product_id;
                    $products = getSingleRecord('wb_product_information', array('id'=>$product_id));
                     $manufacturers_id =  getRecordValFromWhere('wb_product_manufacturers', array('product_id'=>$product_id), 'man_site_id');
                     $manufacturers_id =  convertAssArrayToSimpleArray($manufacturers_id, 'man_site_id','mis_db');
                     //email addresses for manufacturer 
                     $mansite_records = (array)getRecordsWithIds('par_man_sites',$manufacturers_id,'email_address','mis_db');
                     $mansite_emails =  convertAssArrayToSimpleArray($mansite_records, 'email_address');
                     $bcc = array_merge($bcc,$mansite_emails);
                     $app_description = "Application details: Brand Name: ".$products->brand_name.", Registrant: ".$registrant.", Physical Address: ".$physical_address;
					 

                }
                else if($module_id == 3){
                    $local_agent_id = $records->local_agent_id;
                    $manufacturing_site_id = $records->manufacturing_site_id;
                    $site_name =   getSingleRecordColValue('wb_manufacturing_sites', array('id'=>$manufacturing_site_id),'name');
                    $app_description = "Manufacturing Site Name: ".$site_name;
                }
                if(validateIsNumeric($local_agent_id)){
					$cc = array();
                    $local_agent_email = getSingleRecordColValue('tra_premises', array('id' => $local_agent_id), 'email','mis_db');
                    $cc[] = $local_agent_email;

                }
               // $cc = '';
                if($previous_status_id == 1){
                    //send email to 
                    $template_id = 9;
                  //  $subject  = "NOTIFICATION FOR ONLINE APPLICATION SUBMISSION FOR TRACKING NO ".$tracking_no;
                    //send to manufacturers 
                    if(is_array($mansite_emails)){
                        $mansite_emails = implode(';',$mansite_emails);
                        $vars = array(
                            '{tracking_no}' => $tracking_no,
                            '{app_description}' => $app_description
                        );
                        $email_template = getEmailTemplateInfo(11, $vars);
                        $email_content = $email_template->body;
                        $subject = $email_template->subject;
                      //  $response=  sendMailNotification($trader, $trader_emails,$subject,$email_content,$mansite_emails);   
                    }
                } else if($previous_status_id == 6 || $previous_status_id == 7 || $previous_status_id == 8 || $previous_status_id == 9){
                    $template_id = 10;
                   // $subject  = "NOTIFICATION FOR ONLINE APPLICATION QUERY RESPONSE FOR TRACKING NO ".$tracking_no;

                }
                else{
                    $template_id = 9;
                 
                }
               
                $vars = array(
                    '{tracking_no}' => $tracking_no,
                    '{app_description}' => $app_description
                );
                $attachement = '';
                $subject = 'RWANDA FDA  SELF SERVICE PORTAL APPLICATION SUBMISSION';
                $email_template = getEmailTemplateInfo($template_id, $vars);
                $email_content = $email_template->body;
                $subject = $email_template->subject;
                //$bcc = implode(';',$bcc);
                if(is_array($bcc)){
                    $bcc = implode(';',$bcc);
                }
                if(is_array($cc)){
                    $cc = implode(';',$cc);
                }
                //get the other notifications 
                $department_emails = $this->getDepartmentalUsersEmails($module_id,$section_id, 1);
                
                if($department_emails != ''){
                  
                   $department_emails = implode(';',$department_emails);
                   //concatenate 
                   $cc = $cc.';'.$department_emails;
                
                }
                $response=  sendMailNotification($trader, $trader_emails,$subject,$email_content,$cc,$bcc);
               
				if($res['success']){
						
						 $res = array('success'=>true, 
                             'message'=>'Application has been submitted Successfully for processing.');
					}
						
            }   
            else{
				
					if($res['success']){
						
						$res = array('success'=>true ,
							'message1'=>$res,
							'message'=>$res['message']);
					}
									
            }    
			
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
		
        return response()->json($res);
        
    
    }
    function getDepartmentalUsersEmails($module_id,$section_id, $notification_category_id = null){
        $emails = '';
        $records = DB::connection('mis_db')->table('tra_departmental_notifications')
                            ->select('*')
                            ->where(array('section_id'=>$section_id,'module_id'=>$module_id))
                            ->get();

        if($records){
            foreach($records as $rows){
                $groupArray=json_decode($rows->group_ids);
                $emails=$this->getStringFromTable($groupArray);

            }
        }
        return $emails;
    }
   
    public function getStringFromTable($IDarrays){
        $qry=DB::connection('mis_db')->table('users as t1')
            ->join('tra_user_group as t2','t1.id','t2.user_id')
            ->select(DB::raw('decrypt(t1.email) as email_address'))
            ->whereIn('t2.id',$IDarrays);

        $results=$qry->get();
        $res=array();
        foreach ($results as $result) {
            $res[]=$result->email_address;
        }
         return $res;
    }
    public function getTraderApplicationProcessing(Request $request)
    {
        $mistrader_id = $request->mistrader_id;
        try {
            $qry = DB::connection('mis_db')->table('tra_submissions as t1')
                ->join('wf_tfdaprocesses as t2', 't1.process_id', '=', 't2.id')
                ->join('wf_workflow_stages as t3', 't1.previous_stage', '=', 't3.id')
                ->join('wf_workflow_stages as t4', 't1.current_stage', '=', 't4.id')
                ->join('par_system_statuses as t5', 't1.application_status_id', '=', 't5.id')
                ->join('par_submission_urgencies as t6', 't1.urgency', '=', 't6.id')
                ->join('users as t7', 't1.usr_from', '=', 't7.id')
                ->join('users as t8', 't1.usr_to', '=', 't8.id')
                ->leftJoin('wb_trader_account as t9', 't1.applicant_id', '=', 't9.id')
                ->leftJoin('modules as t10', 't1.module_id', '=', 't10.id')
                ->leftJoin('sub_modules as t11', 't1.sub_module_id', '=', 't11.id')
                ->leftJoin('par_sections as t12', 't1.section_id', '=', 't12.id')
                ->select(DB::raw(" DISTINCT t1.application_code,t2.name as process_name,t1.reference_no,t1.tracking_no, t10.name as module_name, t11.name as application_type,t12.name as section,t1.id as ID,
                    t3.name as previous_process, t4.name as current_process, t1.date_received as processing_date"))
                ->where(array('applicant_id'=> $mistrader_id, 'isDone'=>0));
                $qry->groupBy('t1.application_code');
            $qry->orderBy('t1.id', 'DESC');
            
            $results = $qry->get();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }

    public function getDtaApplicationSubmissionData(Request $request){
        $application_status_id = $request->application_status_id;
        $trader_id = $request->trader_id;
        
        $where_state = array('trader_id' => $trader_id);
        if(  $application_status_id != 0){

        $application_status_id = explode(',',$application_status_id);
        }
        $data = array();
        try {
            $qry = DB::table('wb_onlinesubmissions as t1')
                ->join('wb_statuses as t2', 't1.application_status_id', '=', 't2.id')
                ->leftJoin('wb_tfdaprocesses as t4', function ($join) {
                    $join->on('t1.sub_module_id', '=', 't4.sub_module_id');
                    $join->on('t1.application_status_id', '=', 't4.status_id');
                })
                ->select(DB::raw("t1.*, t2.name as application_status,t4.router_link"))
                ->where($where_state)
				->orderBy('t2.id', 'desc');

                $qry->groupBy('t1.application_code');

                if(is_array($application_status_id) && count($application_status_id) >0 ){
                    $qry->whereIn('application_status_id',$application_status_id);
                }
            $qry->orderBy('t1.id', 'DESC');
            
            $results = $qry->get();
            foreach($results as $rows){
                //returnParamFromArray($sectionsData,$rec->section_id),
                
                $sectionsData = getParameterItems('par_sections','','mis_db');
                $ModuleData = getParameterItems('modules','','mis_db');
                $subModuleData = getParameterItems('sub_modules','','mis_db');
                $status_id = $rows->application_status_id;
                $action_defination = '';
                    if($status_id == 1){
                            $action_defination = 'Edit/Preview(Click to continue)';
                        
                    }
                    else if($status_id == 4){
                        $action_defination = 'Print Invoice';
                    }else if($status_id == 6 || $status_id == 7 || $status_id == 8 || $status_id == 9){
                        $action_defination = 'Click for Query Response';
                    }
                    else if($status_id == 10){
                        $action_defination = 'Preview Certificate/Permit';
                    }else if($status_id == 11){
                        $action_defination = 'Rejection Reason';
                    }
                    $data[]= array('application_code'=>$rows->application_code,
                                'application_id'=>$rows->application_id,
                                'ID'=>$rows->id,
                                'reference_no'=>$rows->reference_no,
                                'tracking_no'=>$rows->tracking_no,
                                'process_id'=>$rows->process_id,
                                'module_id'=>$rows->module_id,
                                'sub_module_id'=>$rows->sub_module_id,
                                'status_type_id'=>$rows->status_type_id,
                                'section_id'=>$rows->section_id,
                                'application_status_id'=>$rows->application_status_id,
                                'application_status'=>$rows->application_status,
                                'prodclass_category_id'=>$rows->prodclass_category_id,
                                'action_date'=>$rows->date_submitted,
                                'router_link'=>$rows->router_link,
                                'section'=>returnParamFromArray($sectionsData,$rows->section_id),
                                'module_name'=>returnParamFromArray($ModuleData,$rows->module_id),
                                'application_type'=>returnParamFromArray($subModuleData,$rows->sub_module_id),
                                'action_defination'=>$action_defination
                            );


            }
            $res = array(
                'success' => true,
                'data' => $data,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }
    public function getApplicationProcessing(Request $request)
    {
        $application_code = $request->application_code;
        try {
            $qry = DB::connection('mis_db')->table('tra_submissions as t1')
                ->join('wf_tfdaprocesses as t2', 't1.process_id', '=', 't2.id')
                ->leftJoin('wf_workflow_stages as t3', 't1.previous_stage', '=', 't3.id')
                ->leftJoin('wf_workflow_stages as t4', 't1.current_stage', '=', 't4.id')
                ->leftJoin('par_system_statuses as t5', 't1.application_status_id', '=', 't5.id')
                ->leftJoin('par_submission_urgencies as t6', 't1.urgency', '=', 't6.id')
                ->leftJoin('users as t7', 't1.usr_from', '=', 't7.id')
                ->leftJoin('users as t8', 't1.usr_to', '=', 't8.id')
                ->leftJoin('wb_trader_account as t9', 't1.applicant_id', '=', 't9.id')
                ->select(DB::raw("t1.id, t2.name as process_name,t1.reference_no,
                    t3.description as prev_stage, t4.description as current_stage, t1.date_received as processing_date"))
                ->where('application_code', $application_code);
           
            $qry->orderBy('t1.id', 'ASC');
            $results = $qry->get();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }
    public function getApplicationCounterDetails(Request $req){
        try{
            $trader_id = $req->trader_id;
            $sub_module_id = $req->sub_module_id;
            $table_name = $req->table_name.' as t1';
            $data = array();
            //get the records 
            $resp = false;
            $where_state = array('trader_id' => $trader_id);
			$where_statement = '';
			
            $records = DB::table($table_name. ' as t1')
                    ->select(DB::raw("count(application_status_id) as application_counter,t2.name as status_name, t2.id as status_id"))
                    ->join('wb_statuses as t2','t1.application_status_id','=','t2.id')
                    ->where($where_state);
				if(validateIsNumeric($sub_module_id)){
					
					$where_state['t1.sub_module_id'] = $sub_module_id;
					
					$records->where($where_state);
				}
				else{
					$sub_module_id = explode(',',$sub_module_id);
					$records->whereIn('t1.sub_module_id',$sub_module_id);
					
				}	 
				$records = $records->groupBy('t2.id')->get();
            if(count($records) >0){
                    //delete functionality
                    $res = array('success'=>true, 'data'=>$records);
            }else{
                $res = array('success'=>true, 'data'=>$data);
            }
               
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
        }
        function validateProductData($table_name,$product_id,$title){
                $res = '';
                $sql = DB::table($table_name)->where(array('product_id'=>$product_id))->get();
        
                if(count($sql) == 0){
                        $res = array('success'=>false, 'count'=>$table_name, 'message'=>$title);
                        
                }
        return $res;
        }
        public function onValidatePremisesOtherdetails(Request $req){
            try {

               $table_name = $req->table_name;
               $premise_id = $req->premise_id;
               $title = $req->title;
                $res = $this->validatPremisesOtherDetails($table_name,$premise_id,$title);
                
                if($res == ''){
                    $res = array(
                        'success' => true,
                        'message' => 'Data entry validated'
                    );
                }
        
             } catch (\Exception $e) {
                 $res = array(
                     'success' => false,
                     'message' => $e->getMessage()
                 );
             } catch (\Throwable $throwable) {
                 $res = array(
                     'success' => false,
                     'message' => $throwable->getMessage()
                 );
             }
             return response()->json($res);
        }
        function validatPremisesOtherDetails($table_name,$premise_id,$title){
            $res = '';
            $sql = DB::table($table_name)->where(array('premise_id'=>$premise_id))->get();

            if(count($sql) == 0){
                    $res = array('success'=>true, 'message'=>$title);
                 
            }
            return $res;
    
    }
	public function onApplicationDelete(Request $req){
        try{
            $table_name = $req->table_name;
            $application_code = $req->application_code;
            $status_id = $req->status_id;
            $trader_id = $req->trader_id;
            $remarks = $req->remarks;
            $traderemail_address = $req->traderemail_address;
            $data = array();
            //get the records 
            $resp = false;
            $where_state = array('application_code' => $application_code);
            $records = DB::table($table_name)
                        ->where($where_state)
                        ->first();

            if($records && validateisNumeric($application_code)){
                    //delete functionality
                    $tracking_no = $records->tracking_no;
                    $previous_status_id = $records->application_status_id;
                    $current_status_id = 12;
                    $premise_data = array('application_status_id'=>$current_status_id,
                                        'altered_by'=>$traderemail_address,
                                        'dola'=>Carbon::now(),
                                        'submission_date'=>Carbon::now(),
                                    );
                    $submission_data = array('tracking_no'=>$tracking_no,
                                            'application_code'=>$records->application_code,
                                            'trader_id'=>$trader_id,
                                            'remarks'=>$remarks,
                                            'previous_status_id'=>$previous_status_id,
                                            'current_status_id'=>$current_status_id,
                                            'submission_date'=>Carbon::now(),
                                            'created_by'=>$traderemail_address,
                                            'created_on'=>Carbon::now(),
                                        );
                    
                    $previous_data = getPreviousRecords($table_name, $where_state);
                   //check the Invoices 
				   $record = DB::connection('mis_db')->table('tra_application_invoices')->where('application_code',$application_code)->first();
				   if($record){
					   
					     $res = array('success'=>false, 'message'=>'The Application has an active Invoice and cannt be cancelled, cancel the Generated Invoice and Initial Cancellation');
				   }
				   else{
					    $record = DB::connection('mis_db')->table('tra_payments')->where('application_code',$application_code)->first();
					   if(!$record){
							$resp =  deleteRecordNoTransaction($table_name, $previous_data, $where_state, 0,'mysql');
							$resp = insertRecord('wb_application_submissions', $submission_data, $traderemail_address,'mysql');
											 
							$res = array('success'=>true, 'message'=>' Application has been archived successfully.');
							   
					   }else{
						   $res = array('success'=>false, 'message'=>'The Application has an active payment and cannt be cancelled, contact the aut');
					   }
				   }
					   
					   
				   //check if appliction has any payment 
				  
                    
                   

                 
            }else{
				$res = array('success'=>false, 'message'=>' Application Submission and cancallation failed');
			}
               
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
public function onfuncValidatePermitDetails(Request $req){
    try {
        
        $table_name =$req->table_name;
        $validation_title = $req->validation_title;
        $application_code = $req->application_code;
        $title = $req->title;
        $sql = DB::table($table_name)->where(array('application_code'=>$application_code))->get();

        if(count($sql) == 0){
				
                $res = array('success'=>false, 'message'=>$validation_title);
             
        }else{
			//check the sub_module_id
			$record = DB::table('wb_importexport_applications')->where('application_code',$application_code)->first();
			if($record){
				//
				$sub_module_id = $record->sub_module_id;
				if($sub_module_id == 12 || $sub_module_id == 86){
					$res = array(
						'success' => true,
						'message' => 'Data entry validated'
					);
					
				}else{
					
					$counter = DB::table($table_name)
								->where(array('application_code'=>$application_code))
								->where(function ($query) {
                        
									 $query->whereNull('product_batch_no')
											 ->orWhere('product_batch_no', '');

								 })
								->count();
					if($counter >0){
						$res = array(
							'success' => true,
							'message' => 'Update the Batch No/# for all the products before you proceed'
						);
					}
					else{
						$res = array(
						'success' => true,
						'message' => 'Data entry validated'
					);
					
						
						
					}
					
				}
			
				
			}else{
				$res = array(
						'success' => true,
						'message' => 'Data entry validated'
					);
			}
			
			

        }
       
 
      } catch (\Exception $e) {
          $res = array(
              'success' => false,
              'message' => $e->getMessage()
          );
      } catch (\Throwable $throwable) {
          $res = array(
              'success' => false,
              'message' => $throwable->getMessage()
          );
      }
      return response()->json($res);

}

    public function onValidateGMPOtherdetails(Request $req){
        try {

           $table_name = $req->table_name;
           $manufacturing_site_id = $req->manufacturing_site_id;
           $title = $req->title;
            $res = $this->validatGmpOtherDetails($table_name,$manufacturing_site_id,$title);
            
            if($res == ''){
                $res = array(
                    'success' => true,
                    'message' => 'Data entry validated'
                );
            }
    
         } catch (\Exception $e) {
             $res = array(
                 'success' => false,
                 'message' => $e->getMessage()
             );
         } catch (\Throwable $throwable) {
             $res = array(
                 'success' => false,
                 'message' => $throwable->getMessage()
             );
         }
         return response()->json($res);
    }
    function validatGmpOtherDetails($table_name,$manufacturing_site_id,$title){
        $res = '';
        $sql = DB::table($table_name)->where(array('manufacturing_site_id'=>$manufacturing_site_id))->get();

        if(count($sql) == 0){
                $res = array('success'=>false, 'message'=>$title);
             
        }
        return $res;

}
    
        public function onValidateProductOtherdetails(Request $req){
            try {

               $section_id = $req->section_id;
               $product_id = $req->product_id;
        
                if($section_id == 1){
                       $res =  $this->validateProductData('wb_product_ingredients',$product_id,'Add Product Ingredients Details to proceed');
                       if($res == ''){
                        $this->validateProductData('wb_product_packaging',$product_id,'Add Product Packaging Details to proceed');
                       }
                       if($res == ''){
                        $res =  $this->validateProductData('wb_product_manufacturers',$product_id,'Add Product Manufacturing Details Details to proceed');
                        }
                        if($res == ''){
                            $res =    $this->validateProductData('wb_product_nutrients',$product_id,'Add Product Nutrients Details to proceed');
                        }
                       
                }
                else if($section_id == 2 || $section_id == 7){
                    $res =  $this->validateProductData('wb_product_ingredients',$product_id,'Add Product Ingredients Details to proceed');
                    
                    if($res == ''){
                        $res =   $this->validateProductData('wb_product_packaging',$product_id,'Add Product Packaging Details to proceed');
                    }
                    if($res == ''){
                        $res =    $this->validateProductData('wb_product_manufacturers',$product_id,'Add Product Manufacturing Details to proceed');
                    }
                    
                }
                else if($section_id == 3){
                    $res =     $this->validateProductData('wb_product_ingredients',$product_id,'Add Product Ingredients Details to proceed');
                  
                    if($res == ''){
                       // $res =    $this->validateProductData('wb_product_packaging',$product_id,'Add Product Packaging Details to proceed');
                   
                    }
                    if($res == ''){
                        $res =     $this->validateProductData('wb_product_manufacturers',$product_id,'Add Product Manufacturing Details to proceed');
        
                    }
                
                }
                else{
                    //medical devices 
                    $res =   $this->validateProductData('wb_product_manufacturers',$product_id,'Add Product Manufacturing Details to proceed');
        
                }
                if($res == ''){
                    $res = array(
                        'success' => true,
                        'message' => 'Data entry validated'
                    );
                }
                
        
             } catch (\Exception $e) {
                 $res = array(
                     'success' => false,
                     'message' => $e->getMessage()
                 );
             } catch (\Throwable $throwable) {
                 $res = array(
                     'success' => false,
                     'message' => $throwable->getMessage()
                 );
             }
             return response()->json($res);
        }
        public function onValidateGmpProductOtherdetails(Request $req){
            try {

               $section_id = $req->section_id;
               $manufacturing_site_id = $req->manufacturing_site_id;
               
                $res = $this->validateGMPOtherDetails('wb_manufacturingsite_blocks',$manufacturing_site_id,'Add Manufacturing Sites Block details to proceed');
                if($res == ''){
                    $res =  $this->validateGMPOtherDetails('wb_gmp_productline_details',$manufacturing_site_id,'Add Manufacturing Product Line to proceed');
                }
                if($res == ''){
                   // $res =  $this->validateGMPOtherDetails('wb_product_gmpinspectiondetails',$manufacturing_site_id,'Add Manufacturing Site Product Applications to proceed');
                }
                if($res == ''){
                    $res = array(
                        'success' => true,
                        'message' => 'Data entry validated'
                    );
                }
        
             } catch (\Exception $e) {
                 $res = array(
                     'success' => false,
                     'message' => $e->getMessage()
                 );
             } catch (\Throwable $throwable) {
                 $res = array(
                     'success' => false,
                     'message' => $throwable->getMessage()
                 );
             }
             return response()->json($res);
        }
        function validateGMPOtherDetails($table_name,$manufacturing_site_id,$title){
            $res = '';
            $sql = DB::table($table_name)->where(array('manufacturing_site_id'=>$manufacturing_site_id))->get();

            if(count($sql) == 0){
                    $res = array('success'=>false, 'message'=>$title);
                 
            }
            return $res;
    
    }
    public function onSubmitApplicationDismissal(Request $req){
        try{
            $resp ="";
            $trader_id = $req->trader_id;
            $mistrader_id = $req->mistrader_id;
            $traderemail_address = $req->traderemail_address;
            $application_code = $req->application_code;
            $dismissal_remarks = $req->dismissal_remarks;
            $mistable_name = $req->mistable_name;
            $portaltable_name = $req->portaltable_name;
            

            
            //get the applicaiton details 

            $application_details = getTableData($mistable_name,array('application_code'=>$application_code),'mis_db');
            if($application_details){
                
                $dismissal_data = array('application_code'=>$application_code,
                'application_id'=>$application_details->id,
                'module_id'=>$application_details->module_id,
                'sub_module_id'=>$application_details->sub_module_id,
                'section_id'=>$application_details->section_id,
                'workflow_stage_id'=>$application_details->workflow_stage_id,
                'dismissal_reason_id'=>$req->dismissal_reason_id,
                'dismissal_remarks'=>$req->dismissal_remarks,

             );

                $resp = insertRecord('tra_dismissed_applications', $dismissal_data, $traderemail_address,'mis_db');

                DB::table($portaltable_name)
                ->where(array('application_code'=>$application_code))
                ->update(array('application_status_id'=>32));

                if($resp['success']){
                    $res =  array('success'=>true,
                                 'message'=>'Application Dismissal Request Submitted Successfully');
    
                }
                else{
                    $res =  array('success'=>false,
                                 'message'=>'Application dismissal Request Failed');
    
                }
            }
            else{
                $res = array('success'=>false,'message'=>'Application Details Not Found');
            }
            
            
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        } 
        
        return response()->json($res);
    


    }
    public function onAddUniformApplicantDataset(Request $req){
        try{
            $resp ="";
            $trader_id = $req->trader_id;
            $mistrader_id = $req->mistrader_id;
            $traderemail_address = $req->traderemail_address;
            $email = $req->email;
            $error_message = 'Error occurred, data not saved successfully';

            $data = $req->all();
            $table_name = $req->table_name;
            $record_id = $req->id;
            $product_id = $req->product_id;
            unset($data['table_name']);
            unset($data['traderemail_address']);
            unset($data['trader_id']);
            unset($data['product_id']);
            unset($data['mistrader_id']);

            if($table_name == 'tra_personnel_information'){
                $data['trader_id']  = $mistrader_id;
            }
            
            if(validateIsNumeric($record_id)){
                $where = array('id'=>$record_id);
                if (recordExists($table_name, $where,'mis_db')) {
                                
                    $data['dola'] = Carbon::now();
                    $data['altered_by'] = $traderemail_address;

                    $previous_data = getPreviousRecords($table_name, $where,'mis_db');
                    
                    $resp = updateRecord($table_name, $previous_data, $where, $data, $traderemail_address);
                    
                }
            }
            else{
                //insert 
                $data['created_by'] = $traderemail_address;
                $data['created_on'] = Carbon::now();
                $where = array('email'=>$email);

                $resp = insertRecord($table_name, $data, $traderemail_address,'mis_db');
                   
                $record_id = $resp['record_id'];   
               
            } 
            if($resp['success']){
                $res =  array('success'=>true,
                'record_id'=>$record_id,
                
                'message'=>'Saved Successfully');

            }
            else{
                $res =  array('success'=>false,'message1'=>$resp['message'],
                'message'=>$error_message);

            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,'message1'=>$resp['message'],
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        } 
        
        return response()->json($res);
    
    }
    public function getPersonnelDetails(Request $req){
        
        try{
             $table_name = $req->table_name;
              $records = DB::connection('mis_db')
                            ->table( $table_name.' as t1')
                            ->select('t1.*','t1.id', 't1.name', 't2.name as country', 't3.name as region','t4.name as district')
                            ->join('par_countries as t2', 't1.country_id', '=','t2.id')
                            ->join('par_regions as t3', 't1.region_id', '=','t3.id')
                            ->leftJoin('par_districts as t4', 't1.district_id', '=','t4.id')
                            ->orderBy('id', 'DESC')
                            ->get();
                $res = array('success'=>true, 
                            'data'=>$records
                            );
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);

    }

    public function onloadProductRetetentionDetails(Request $req){
        
        try{
            $trader_id = $req->mistrader_id;
            $section_id = $req->section_id;

            $retentionyear_from = $req->retentionyear_from;
            $retentionyear_to = $req->retentionyear_to;

            $records = DB::connection('mis_db')->table('tra_product_retentions as t1')
                                          ->select(DB::raw("t1.id, DATE_FORMAT(t1.retention_year,'%Y') as retention_year,t3.brand_name,t2.registration_no as certificate_no,t5.reference_no, t5.invoice_no,t6.total_element_amount as invoice_amount,t7.name as currency_name, (t6.total_element_amount*t6.paying_exchange_rate) as totalinvoice_amount, t9.name as cost_element"))
                                      ->join('tra_registered_products as t2', 't1.reg_product_id','=', 't2.id')
                                      ->join('tra_product_information  as t3', 't2.tra_product_id','=', 't3.id') 
                                      ->join('tra_application_invoices as t5', 't1.invoice_id', '=', 't5.id')
                                      ->join('tra_invoice_details as t6','t5.id','=','t6.invoice_id')
                                      ->join('par_currencies as  t7','t6.paying_currency_id','=','t7.id')
                                      ->join('tra_element_costs as t8','t6.element_costs_id','=','t8.id')
                                      ->join('par_cost_elements as t9','t8.element_id','=','t9.id')
									  ->where('t1.retention_status_id',1)
									  ->where('t2.registration_status_id',2)
                                      ->where(array('t5.applicant_id'=>$trader_id));
            if(validateIsNumeric($section_id)){
                $records = $records->where('t4.section_id',$section_id);   
            }
            if(validateIsNumeric($retentionyear_from)){
                $records = $records->whereRaw("DATE_FORMAT(t1.retention_year,'%Y') >= ".$retentionyear_from);   
            }
            if(validateIsNumeric($retentionyear_to)){
                $records = $records->whereRaw("DATE_FORMAT(t1.retention_year,'%Y') <= ".$retentionyear_to);   
            }
                                      $records = $records->groupBy('t1.id')->get();
          
           $res = array('success'=>true, 
                        'data'=>$records);
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);

    }

    public function onloadProductRetetentionPaymentsDetails(Request $req){
        
        try{
            $trader_id = $req->mistrader_id;
            $section_id = $req->section_id;

            $retentionyear_from = $req->retentionyear_from;
            $retentionyear_to = $req->retentionyear_to;


            $records = DB::connection('mis_db')->table('tra_product_retentions as t1')
                                          ->select(DB::raw("t1.id,t8.id as ID, DATE_FORMAT(t1.retention_year,'%Y') as retention_year,t3.brand_name,t2.registration_no as certificate_no,t4.reference_no, t5.invoice_no,t6.total_element_amount as invoice_amount,t7.name as currency_name, (t6.total_element_amount*t6.exchange_rate) as totalinvoice_amount,t9.name as payment_currency, t8.amount_paid , (t8.amount_paid *t8.exchange_rate) as amount_paidtshs"))
                                      ->join('tra_registered_products as t2', 't1.reg_product_id','=', 't2.id')
                                      ->join('tra_product_information  as t3', 't2.tra_product_id','=', 't3.id')
                                      ->join('tra_product_applications as t4', 't3.id','=','t4.product_id') 
                                      ->join('tra_application_invoices as t5', 't1.invoice_id', '=', 't5.id')
                                      ->join('tra_invoice_details as t6','t5.id','=','t6.invoice_id')
                                      ->join('par_currencies as  t7','t6.currency_id','=','t7.id')
                                      ->join('tra_payments as t8','t1.receipt_id','=','t8.id' )
                                      ->join('par_currencies as  t9','t8.currency_id','=','t9.id')
                                      ->where(array('t5.applicant_id'=>$trader_id));
            if(validateIsNumeric($section_id)){
                $records = $records->where('t4.section_id',$section_id);   
            }
            if(validateIsNumeric($retentionyear_from)){
                $records = $records->whereRaw("DATE_FORMAT(t1.retention_year,'%Y') >= ".$retentionyear_from);   
            }
            if(validateIsNumeric($retentionyear_to)){
                $records = $records->whereRaw("DATE_FORMAT(t1.retention_year,'%Y') <= ".$retentionyear_to);   
            }
                                      $records = $records->get();

           $res = array('success'=>true, 
                        'data'=>$records);
        }
        catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);

    }
    public function onLoadApplicationNotifications(Request $req){
        try{
            $take = $req->take;
            $skip = $req->skip;
            $searchValue = $req->searchValue;
            $trader_id = $req->trader_id;
    
            if($req->searchValue != 'undefined'){
                $searchValue = explode(',',$searchValue);
                $searchValue =  $searchValue[2];
            }
            else{
                $searchValue =  '';
            }

                            $qry = DB::table('wb_appnotification_details as t1')
                                            ->join('wb_trader_account as t2', 't1.identification_no','=','t2.identification_no')
                                            ->select('t1.*')
                                            ->where('t2.id',$trader_id)
                                            ->orderBy('id', 'desc');

                                    if($searchValue != ''){

                                        $whereClauses = array();
                                        $whereClauses[] = "t1.reference_no like '%" . ($searchValue) . "%'";
                                        $whereClauses[] = "t1.tracking_no like '%" . ($searchValue) . "%'";
                                        
                                        $whereClauses[] = "t1.subject like '%" . ($searchValue) . "%'";
                                        
                                        $whereClauses[] = "t1.message like '%" . ($searchValue) . "%'";
                                        $filter_string = implode(' OR ', $whereClauses);
                                        $qry->whereRAW($filter_string);
                                    }
                                    $records = $qry->skip($skip)->take($take)->get();

                                    $totalCount = $qry->count();
                                    $res = array('success' => true,
                                        'data' => $records,
                                        'totalCount'=>$totalCount 
                                    );
                }
                catch (\Exception $e) {
                    $res = array(
                        'success' => false,
                        'message' => $e->getMessage()
                    );
                } catch (\Throwable $throwable) {
                    $res = array(
                        'success' => false,
                        'message' => $throwable->getMessage()
                    );
                }
                return response()->json($res);


    }
    public function getModuleApplicationdetails(Request $req){
                try{
                        $module_id = $req->module_id;
                        $application_code = $req->application_code;
                        $table_name =  getSingleRecordColValue('modules', array('id'=>$module_id), 'table_name','mis_db');

                        $records = DB::connection('mis_db')->table($table_name)->where('application_code',$application_code)->first();

                        $res = array('success'=>true,
                                        'data'=>$records,
                                        'message'=>'');
                        
                }
                catch (\Exception $e) {
                    $res = array(
                        'success' => false,
                        'message' => $e->getMessage()
                    );
                } catch (\Throwable $throwable) {
                    $res = array(
                        'success' => false,
                        'message' => $throwable->getMessage()
                    );
                }
                return response()->json($res);



    }
    public function getApplicationPaymentsDetails(Request $request){
        $application_code = $request->input('application_code');
        $where = array(
            'application_code' => $application_code
        );
        try {
            $qry = DB::connection('mis_db')->table('tra_payments as t1')
                ->leftJoin('par_payment_modes as t2', 't1.payment_mode_id', '=', 't2.id')
                ->leftJoin('par_currencies as t3', 't1.currency_id', '=', 't3.id')
                ->leftJoin('par_receipt_types as t4', 't1.receipt_type_id', '=', 't4.id')
                ->select(DB::raw("t1.*,(amount_paid*exchange_rate) as equivalent_tshs, t2.name as payment_mode,t3.name as currency,t4.name as receipt_type,t1.id as receipt_id,
                    IF(t1.receipt_type_id=1,t1.receipt_no,t1.manual_receipt_no) as receipt_no"))
                ->where($where);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);

    }
    public function onLoadAssignedApplicationsAssignments(Request $request){
        $where = array(
            'external_user_id' => $request->mis_external_user_id,
            'isDOne'=>0
        );
        try {
            $qry = DB::connection('mis_db')->table('tra_submissions as t1')
                ->join('wf_tfdaprocesses as t2', 't1.process_id', '=', 't2.id')
                ->join('wf_workflow_stages as t3', 't1.previous_stage', '=', 't3.id')
                ->join('wf_workflow_stages as t4', 't1.current_stage', '=', 't4.id')
                ->leftJoin('par_system_statuses as t5', 't1.application_status_id', '=', 't5.id')
                ->leftJoin('par_submission_urgencies as t6', 't1.urgency', '=', 't6.id')
                ->join('users as t7', 't1.usr_from', '=', 't7.id')
                ->leftJoin('wb_trader_account as t9', 't1.applicant_id', '=', 't9.id')
                ->leftJoin('modules as t10', 't1.module_id', '=', 't10.id')
                ->leftJoin('sub_modules as t11', 't1.sub_module_id', '=', 't11.id')
                ->select(DB::raw("t1.*, t1.current_stage as workflow_stage, t1.date_received as assigned_on,t1.application_id as active_application_id, t2.name as process_name,
                    t3.name as prev_stage, t4.name as assigned_process,t4.is_general,t5.name as application_status,t6.name as urgencyName,t6.name as urgency_name,
                    CONCAT_WS(' ',decrypt(t7.first_name),decrypt(t7.last_name)) as assigned_by,TOTAL_WEEKDAYS(now(), date_received) as time_span, t10.name as module_name, t11.name as application_type,
                    t9.name as applicant_name"))
                ->where('t4.stage_status','<>',3)
                ->where($where);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }
    public function  onLoadMeetingInvitations(Request $request){
        
        try {
            $qry = DB::connection('mis_db')->table('tc_meeting_details as t1')
                ->join('tc_meeting_participants as t2', 't1.id','t2.meeting_id')
                ->join('users as t3', 't1.created_by', '=', 't3.id')
                ->select(DB::raw("t1.*, CONCAT_WS(' ',decrypt(t3.first_name),decrypt(t3.last_name)) as request_sent_by"))
                ->where('t2.external_user_id', $request->mis_external_user_id)
                ->groupBy('t1.id');
            $results = $qry->get();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }
    public function  getApplicationInformation(Request $request){
        
        try {
           $module_id = $request->module_id;
$application_code = $request->application_code;
           if($module_id == 1){

                $records = $this->getProductInformationDetails($application_code);

           }

            $res = array(
                'success' => true,
                'data' => $records,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }
    function getProductInformationDetails($application_code){
        $records = DB::table('wb_product_applications as t1')
                            ->select(DB::raw("t1.*,t5.name as local_agent_name, t2.*,t1.application_status_id as status_id, t3.name as status_name,t6.manufacturer_id, t1.trader_id as applicant_id,date_format(manufacturing_date, '%m/%d/%Y') as manufacturing_date, date_format(expiry_date, '%m/%d/%Y') as expiry_date"))
                            ->join('wb_product_information as t2', 't1.product_id','=','t2.id')
                            ->leftJoin('wb_statuses as t3', 't1.application_status_id','=','t3.id')
                            ->leftJoin('wb_tfdaprocesses as t4', function ($join) {
                                $join->on('t1.sub_module_id', '=', 't4.sub_module_id');
                                $join->on('t1.application_status_id', '=', 't4.status_id');
                                $join->on('t1.section_id', '=', 't4.section_id');
                                $join->on('t2.prodclass_category_id', '=', 't4.prodclass_category_id');
                            })
                            ->leftJoin('wb_trader_account as t5', 't1.local_agent_id','=','t5.id')
                            ->leftJoin('wb_product_manufacturers as t6', 't1.product_id','=','t6.product_id')
                            ->where(array('t1.application_code' => $application_code))
                            ->first();
                            $manufacturer_id = $records->manufacturer_id;
                            $manufacturing_site_name = getSingleRecordColValue('tra_manufacturers_information', array('id' => $manufacturer_id), 'name','mis_db');
                            //par_man_sites
                            $records->{"manufacturer_name"} = $manufacturing_site_name;

                            return $records;
    }
    public function getUnstructuredQueryChecklistItem(Request $request)
    {

        $sub_module_id = $request->sub_module_id;
        $section_id = $request->section_id;
        $filters = array('sub_module_id'=>$sub_module_id,'section_id'=>$section_id);

        try {

            $qry = DB::connection('mis_db')->table('par_checklist_items as t1')
                ->join('par_checklist_types as t2', 't1.checklist_type_id', '=', 't2.id')
                ->join('par_checklist_categories as t3', 't2.checklist_category_id', '=', 't3.id')
                ->select(DB::raw(" t1.*, t2.name as checklist_type, t3.name as checklist_category"));
            if (count((array)$filters) > 0) {
                $qry->where($filters);
            }

            $qry->where(array('is_query' => 1));
            $results = $qry->get();

            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }
    public function getapplicationQueriessrequests(Request $request){
        $application_code = $request->input('application_code');
        $status = $request->input('status');
        if ($status != '') {
            $status = explode(',', $status);
        }
        try {
            $qry = DB::connection('mis_db')->table('checklistitems_queries as t1')
                ->leftJoin('par_query_statuses as t2', 't1.status', '=', 't2.id')
                ->leftJoin('checklistitems_queryresponses as t3', 't1.id', '=', 't3.query_id')
                ->leftJoin('par_application_sections as t4', 't1.application_section_id', '=', 't4.id')
                ->select(DB::raw("t11.name as reference_details,t1.query as query_details,t1.*,t1.created_on as queried_on, t2.name as query_status, t3.response as last_response,t4.application_section,t6.id as query_type_id, t6.name as query_type,t7.name as query_category,t5.name as queried_item, decrypt(t8.first_name) as queried_by, t10.query_ref as query_reference_no,t6.sub_module_id,t6.module_id,t6.section_id,t1.application_code, t12.response as query_response"))
                ->leftJoin('par_checklist_items as t5', 't1.checklist_item_id', '=', 't5.id')
                ->leftJoin('par_checklist_types as t6', 't5.checklist_type_id', '=', 't6.id')
                ->leftJoin('par_checklist_categories as t7', 't6.checklist_category_id', '=', 't7.id')
                ->leftJoin('users as t8', 't1.created_by', '=', 't8.id')
                ->leftJoin('tra_queries_referencing as t9', 't1.id', '=', 't9.id')
                ->leftJoin('tra_application_query_reftracker as t10', 't9.query_ref_id', '=', 't10.id')
                ->leftJoin('par_query_guidelines_references as t11', 't1.reference_id', '=', 't11.id')
                ->leftJoin('checklistitems_queryresponses as t12', 't1.id', '=', 't12.query_id')
                ->where('t1.application_code', $application_code);
                

            $qry->where(array('is_query' => 1));
            if (is_array($status) && count($status) > 0) {
                $qry->whereIn('status', $status);
            }
            $results = $qry->get();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);


    }
    
    public function onValidateApplicationAssesmentReport(Request $req){
		$application_code = $req->input('application_code');
		$workflow_stage = $req->input('workflow_stage');
		$doc_type_id = $req->input('document_type_id');
		$portal_uploads = $req->input('portal_uploads');
		$portal_status_id = $req->input('portal_status_id');
		$section_id = $req->input('section_id');
		$module_id = $req->input('module_id');
		$sub_module_id = $req->input('sub_module_id');
		$prodclass_category_id = $req->input('prodclass_category_id');

		try {
				$where = array(
						'sub_module_id' => $sub_module_id,
						'section_id' => $section_id
				);
				$process_id = getSingleRecordColValue('wf_tfdaprocesses', $where, 'id', 'mis_db');
				//get applicable document types
				$qry1 = DB::connection('mis_db')->table('tra_proc_applicable_doctypes')
						->select('doctype_id');
				if (isset($process_id) && $process_id != '') {
						$qry1->where('process_id', $process_id);
				}
				if (isset($workflow_stage) && $workflow_stage != '') {
						$qry1->where('stage_id', $workflow_stage);
				}
				if (validateIsNumeric($doc_type_id)) {
						$qry1->where('doctype_id', $doc_type_id);
				}
				$docTypes = $qry1->get();
				$docTypes = convertStdClassObjToArray($docTypes);
				$docTypes = convertAssArrayToSimpleArray($docTypes, 'doctype_id');
                
				$qry = DB::connection('mis_db')->table('tra_documentupload_requirements as t1')
						->join('par_document_types as t2', 't1.document_type_id', '=', 't2.id')
						->select(DB::raw("t4.id as document_upload_id, t1.id as document_requirement_id"))
						->leftJoin('tra_application_uploadeddocuments as t4', function ($join) use ($application_code) {
								$join->on("t1.id", "=", "t4.document_requirement_id")
										 ->where("t4.application_code", "=", $application_code);
						})
						->leftJoin('users as t5', 't4.uploaded_by', '=', 't5.id')
						->where($where);
						if (validateIsNumeric($prodclass_category_id)) {
								$qry->where('t1.prodclass_category_id', $prodclass_category_id);
						}
						if (validateIsNumeric($doc_type_id)) {
								$qry->where('t1.document_type_id', $doc_type_id);
						} //else if(count($docTypes) > 0) {
								$qry->whereIn('t1.document_type_id', $docTypes);;
					 // }
                //check if exists 
                	
                $results = $qry->get();
                $res = array(
                        'success' => true,
                        'message' => 'All is well'
                );
               foreach($results as $rec){
                        if(!validateIsNumeric($rec->document_upload_id)){
                            $res = array(
                                    'success' => true,
                                    'message' => 'Upload the assessment Report to continue'
                            );
                        }
               }
				
		} catch (\Exception $e) {
				$res = array(
						'success' => false,
						'message' => $e->getMessage()
				);
		} catch (\Throwable $throwable) {
				$res = array(
						'success' => false,
						'message' => $throwable->getMessage()
				);
		}
		return response()->json($res);

    }
   
    public function getSubmissionActionData(Request $request)
    {
        $stage_id = $request->input('stage_id');
        $is_submission = $request->input('is_submission');

        try {
            $qry = DB::connection('mis_db')->table('wf_workflow_actions as t1')
                ->leftJoin('wf_workflow_stages as t2', 't1.stage_id', '=', 't2.id')
                ->leftJoin('wf_workflowaction_types as t3', 't1.action_type_id', '=', 't3.id')
                ->select('t1.*', 't2.name as stage_name', 't3.name as action_type')
                ->where('stage_id', $stage_id);
           
            $results = $qry->get();

            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    public function getSubmissionNextStageDetails(Request $request)
    {
        $current_stage = $request->input('stage_id');
        $action = $request->input('action');
        $where = array(
            't1.stage_id' => $current_stage,
            't1.action_id' => $action
        );
        try {
            $qry = DB::connection('mis_db')->table('wf_workflow_transitions as t1')
                ->join('wf_workflow_actions as t2', 't1.action_id', 't2.id')
                ->select('t1.*', 't2.is_external_usersubmission')
                ->where($where);
            $results = $qry->first();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }
    public function getApplicationTransitionStatus($prev_stage, $action, $next_stage, $static_status=null)
    {
        if (isset($static_status) && $static_status != '') {
            return $static_status;
        }
        $where = array(
            'stage_id' => $prev_stage,
            'action_id' => $action,
            'nextstage_id' => $next_stage
        );
        $status = DB::connection('mis_db')->table('wf_workflow_transitions')
            ->where($where)
            ->value('application_status_id');
        return $status;
    }
    public function onApplicationProcessSubmission(Request $request, $keep_status = false)
    {
        $application_code = $request->input('application_code');
       
        $prev_stage = $request->input('curr_stage_id');
        $action = $request->input('action');
        $to_stage = $request->input('next_stage');
        $module_id = $request->input('module_id');
        $user_id = 0;
        $table_name = getSingleRecordColValue('modules', array('id'=>$module_id), 'table_name', 'mis_db');
        
        DB::beginTransaction();
        try {
            //get application_details

            $application_details = DB::connection('mis_db')->table($table_name)
                ->where('application_code', $application_code)
                ->first();
            if (is_null($application_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while fetching application details!!'
                );
                echo json_encode($res);
                exit();
            }
            $application_status_id = $this->getApplicationTransitionStatus($prev_stage, $action, $to_stage);
            if ($keep_status == true) {//for approvals
                $application_status_id = $application_details->application_status_id;
            }
            $where = array(
                'application_code' => $application_code
            );
            $app_update = array(
                'workflow_stage_id' => $to_stage,
                'application_status_id' => $application_status_id
            );
            $prev_data = getPreviousRecords($table_name, $where,'mis_db');
            if ($prev_data['success'] == false) {
                echo json_encode($prev_data);
                exit();
            }
            $update_res = updateRecord($table_name, $prev_data, $where, $app_update, '','mis_db');

            if ($update_res['success'] == false) {
                echo json_encode($update_res);
                exit();
            }
            $this->updateApplicationSubmission($request, $application_details, $application_status_id,$table_name);

        } catch (\Exception $exception) {
            DB::rollBack();
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
            echo json_encode($res);
            exit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
            echo json_encode($res);
            exit();
        }
    }
    
    public function getApplicationWorkflowActionDetails($action_id)
    {
        $transition_details = DB::connection('mis_db')->table('wf_workflow_actions')
            ->where('id', $action_id)
            ->first();
        if (is_null($transition_details)) {
            $res = array(
                'success' => false,
                'message' => 'Problem encountered getting action details!!'
            );
            echo json_encode($res);
            exit();
        }
        return $transition_details;
    }  public function getActionTransitionDetails($action_id){
        $rec = DB::connection('mis_db')->table('wf_workflow_transitions as t1')
                ->select('t1.*')
                ->where(array('action_id'=>$action_id))
                ->first();
        return $rec;
    }public function updateApplicationSubmission($request, $application_details, $application_status_id,$table_name)
    {
        $application_id = $application_details->id;
        $process_id = $request->input('process_id');
        $action = $request->input('action');
        $user_id = 0;
        try {
            //get process other details
            $process_details = DB::connection('mis_db')->table('wf_tfdaprocesses')
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
            $remarks = $request->input('remarks');
            $directive_id = $request->input('directive_id');
            //application details
            $application_code = $application_details->application_code;
            $ref_no = $application_details->reference_no;
            $view_id = $application_details->view_id;
            $tracking_no = $application_details->tracking_no;
            $applicant_id = $application_details->applicant_id;
            //process other details
            $module_id = $process_details->module_id;
            $sub_module_id = $process_details->sub_module_id;
            $section_id = $process_details->section_id;
            //transitions
            //process inforamtion 
            $action_details = $this->getApplicationWorkflowActionDetails($action);
            $keep_status = $action_details->keep_status;
            $has_process_defination = $action_details->has_process_defination;
            $appprocess_defination_id = $action_details->appprocess_defination_id;

            $has_appdate_defination = $action_details->has_appdate_defination;
            $appdate_defination_id = $action_details->appdate_defination_id;
            $appdate_defination = getSingleRecordColValue('par_appprocess_definations', array('id'=>$appdate_defination_id),'code','mis_db');
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
            
            DB::connection('mis_db')->table('tra_applications_transitions')
                ->insert($transition_params);
            //submissions
            $submission_params = array(
                'application_id' => $application_id,
                'process_id' => $process_id,
                'view_id' => $view_id,
                'application_code' => $application_code,
                'reference_no' => $ref_no,
                'tracking_no' => $tracking_no,
                'usr_from' => $user_id,
                'previous_stage' => $from_stage,
                'current_stage' => $to_stage,
                'module_id' => $module_id,
                'sub_module_id' => $sub_module_id,
                'section_id' => $section_id,
                'application_status_id' => $application_status_id,
                'urgency' => 1,
                'applicant_id' => $applicant_id,
                'remarks' => $remarks,
                'directive_id' => $directive_id,
                'date_received' => Carbon::now(),
                'created_on' => Carbon::now(),
                'created_by' => $user_id
            );
            DB::connection('mis_db')->table('tra_submissions')
                ->insert($submission_params);
                if($has_appdate_defination == 1){

                    $appdate_defination = array($appdate_defination=>Carbon::now(),'dola'=>Carbon::now());
                    $app_update = DB::connection('mis_db')->table($table_name . ' as t1')
                                    ->where('application_code', $application_code)
                                    ->update($appdate_defination);
                }
                if(count($application_processdefdata) >0){
    
                    DB::connection('mis_db')->table('tra_applications_processdefinations')
                             ->insert($application_processdefdata);
    
                }
            
           $this->updateInTraySubmissions($application_id, $application_code, $from_stage, $user_id, 'mis_db');
            if($is_multi_submission >1){
                $submission_params['current_stage'] =  $multinextstage_id;
                $submission_params['usr_to'] =  '';
                DB::connection('mis_db')->table('tra_submissions')->insert($submission_params);
            }
        
            DB::commit();
            $res = array(
                'success' => true,
                'message' => 'Application Submitted Successfully!!'
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        echo json_encode($res);
        return true;
    }
  public  function updateInTraySubmissions($application_id, $application_code, $from_stage, $user_id)
    {
        try {
            $update = array(
                'isRead' => 1,
                'isDone' => 1,
                'date_released' => Carbon::now(),
                'altered_by' => $user_id,
                'released_by' => $user_id,
                'isComplete' => 1
            );
            DB::connection('mis_db')->table('tra_submissions')
                ->where('application_code', $application_code)
                ->where('current_stage', $from_stage)
                ->where('isDone', 0)
                ->update($update);

            $res = array(
                'success' => true,
                'message' => 'Update successful!!'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return $res;
    }
    public function getSubmissionWorkflowStages(Request $request)
    {
        $process_id = $request->input('process_id');
        try {
            $qry = DB::connection('mis_db')->table('wf_tfdaprocesses as t1')
                ->join('wf_workflows as t2', 't1.workflow_id', '=', 't2.id')
                ->join('wf_workflow_stages as t3', 't2.id', '=', 't3.workflow_id')
                ->select('t3.*')
                ->where('t1.id', $process_id);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    public function onLoadApplicationCounterDueforRenewal(Request $req){
        $mistrader_id = $req->input('mistrader_id');
        $module_id = $req->input('module_id');
        try {
            $app_renewalduenotifications = 0;
            $records = DB::connection('mis_db')->table('modules as t1')
                            ->leftJoin('par_expirynotification_timespan as t2', 't1.id', '=','t2.module_id')
                            ->select('t1.*', 't2.days_span')
                            ->where(array('is_application'=>1));

                            if(validateIsNumeric($module_id)){
                                $records->where(array('t1.id'=>$module_id));
                
                            }
                            $records = $records->get();
                            $now = Carbon::now();
             foreach($records as $row){
                    $table_name = $row->table_name;
                    $days_span = $row->days_span;
                    if(!validateIsNumeric($days_span)){
                            $days_span = 30;
                    }
                    DB::enableQueryLog();
                    $counter = DB::connection('mis_db')->table($table_name .' as t1')
                                                        ->join('tra_approval_recommendations as t2','t1.application_code', '=','t2.application_code')
                                                        ->whereRAW("DATEDIFF(t2.expiry_date,'$now') <= $days_span")
                                                        ->where(array('applicant_id'=>$mistrader_id))
                                                        ->count();
 
                $app_renewalduenotifications += $counter;

            }
            
            $res = array(
                'success' => true,
                'app_renewalduenotifications' => $app_renewalduenotifications,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
    public function onLoadApplicationDetailsDueforRenewal(Request $req){

        $mistrader_id = $req->input('mistrader_id');
        $module_id = $req->input('module_id');
        $app_renewalduenotificationsdetails = array();

        try {
            $app_renewalduenotifications = 0;
            $records = DB::connection('mis_db')->table('modules as t1')
                            ->leftJoin('par_expirynotification_timespan as t2', 't1.id', '=','t2.module_id')
                            ->select('t1.*', 't2.days_span')
                            ->where(array('is_application'=>1));

            if(validateIsNumeric($module_id)){
                $records->where(array('t1.id'=>$module_id));
            }

            $records = $records->get();

            $now = Carbon::now();
             foreach($records as $row){
                    $table_name = $row->table_name;
                    $module_id = $row->id;
                    $days_span = $row->days_span;
                    if(!validateIsNumeric($days_span)){
                            $days_span = 30;
                    }
                   
                        $app_records = DB::connection('mis_db')->table($table_name .' as t1')
                        ->join('tra_approval_recommendations as t2','t1.application_code', '=','t2.application_code')
                        ->join('modules as t3', 't1.module_id', '=', 't3.id')
                        ->whereRAW("DATEDIFF(t2.expiry_date,'$now') <= $days_span")
                        ->where(array('applicant_id'=>$mistrader_id))
                        ->select(DB::Raw("DATEDIFF(t2.expiry_date,'$now') as days_span,t1.reference_no,t2.expiry_date, t2.permit_no,t2.certificate_no,t1.application_code, t3.name as module_name"))
                        ->get();
                    
                    if($app_records){
                        foreach($app_records as $app_row){
                            $certificate_no = $app_row->certificate_no;
                            if($app_row->certificate_no != ''){
                                $certificate_no = $app_row->permit_no;
                            }
                            
                            $app_renewalduenotificationsdetails[] = array('reference_no'=>$app_row->reference_no, 
                                                                        'expiry_date'=>$app_row->expiry_date,
                                                                        'certificate_no'=>$certificate_no,
                                                                        'id'=>$app_row->application_code,
                                                                        'module_name'=>$app_row->module_name,
                                                                        'days_span'=>$app_row->days_span
                                                                     ); 
                        }
                    }
                    

            }
            
            $res = array(
                'success' => true,
                'app_renewalduenotificationsdetails' => $app_renewalduenotificationsdetails,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
   public function saveManufacturerSiteFulldetails(Request $req){
        try{
            $manufacturer_id = $req->manufacturer_id;

            $trader_email = $req->trader_email;
            if(!validateIsNumeric($manufacturer_id)){
                $man_data = array('name'=>$req->name,
                            'country_id'=>$req->country_id,
                            'region_id'=>$req->region_id,
                            'email_address'=>$req->email_address,
                            'postal_address'=>$req->postal_address,
                            'telephone_no'=>$req->telephone_no,
                            'physical_address'=>$req->physical_address);
                    $man_data['created_on'] = Carbon::now();
                    $man_data['created_by'] = $trader_email;
                    
                    $resp = insertRecord('tra_manufacturers_information', $man_data, $trader_email,'mis_db');
                  
                    $manufacturer_id = $resp['record_id']; 
            }
            //save the other details 
            $man_data = array('name'=>$req->mansite_name,
                    'country_id'=>$req->mansitecountry_id,
                    'region_id'=>$req->mansiteregion_id,
                    'email_address'=>$req->mansiteemail_address,
                    'postal_address'=>$req->mansitepostal_address,
                    'telephone_no'=>$req->mansitetelephone_no,
                    'manufacturer_id'=>$manufacturer_id,
                    'physical_address'=>$req->mansitephysical_address,
                    'contact_person'=>$req->contact_person);

            $resp = insertRecord('par_man_sites', $man_data, $trader_email,'mis_db');
        
           
            if($resp['success']){
                $man_site_id = $resp['record_id']; 
                $record =  $this->getManufacturingSite($man_site_id);
                $res = array('success'=>true, 'message'=>'Manufacturing Site Saved successfully', 'record'=>$record);
            }
            else{
                $res = array('success'=>false,'message'=>'Manufacturing Site Details not saved, try again or contact the system admin.');
            }

        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
   }
   function getManufacturingSite($man_site_id){
   
 $record = DB::connection('mis_db')
                ->table('par_man_sites as t1')
                ->select('t1.*','t1.id as man_site_id','t5.name as manufacturer_name', 't1.name as manufacturing_site_name', 't2.name as country', 't3.name as region','t4.name as district', 't1.email_address as email')
                ->leftJoin('par_countries as t2', 't1.country_id', '=','t2.id')
                ->leftJoin('par_regions as t3', 't1.region_id', '=','t3.id')
                ->leftJoin('par_districts as t4', 't1.district_id', '=','t4.id')
                ->leftJoin('tra_manufacturers_information as t5', 't1.manufacturer_id', '=','t5.id')
                ->where(array('t1.id'=>$man_site_id) )
                ->first();



    return $record;
   }
   public function testEMail(){
	    $response=  sendMailNotification('Hello TEST EMAIL', 'hiramwachira@gmail.com','IRIMS Portal Test Emails','Test Cover','hiramwachira@gmail.com','hiramwachira@gmail.com');
		
		print_r($response);
		
   }
   public function onApplicationInvoiceGeneration(Request $req){
        try{
            
                $application_code = $req->application_code;
                $module_id = $req->module_id;
                $fasttrack_option_id = $req->fasttrack_option_id;
                $trader_id = $req->trader_id; 
				$paying_currency_id = $req->paying_currency_id;
                $module_data = getTableData('modules', array('id'=>$module_id),'mis_db');
                $portaltable_name = $module_data->portaltable_name;
                $invoice_type_id = 1;
                $application_feetype_id = 1;
                 $submodule_id = $req->submodule_id;
                //check if invoice has been generated
                $record = DB::connection('mis_db')->table('tra_application_invoices as t1')
                            ->select('t1.*', 't1.id as invoice_id')           
                ->where(array('application_code'=>$application_code, 'invoice_type_id'=>$invoice_type_id))
                            ->first();
                                
                if($record){
                    $res = array(
                        'success' => true,
                        'invoice_data'=>$record,
                        'message' => 'Invoice Already Generated, print to proceed!!'
                    );
                    return response()->json($res);
                    
                }
                 $rec = DB::table($portaltable_name.' as t1')
                                ->join('wb_trader_account as t2', 't1.trader_id','t2.id')
                                ->select('t1.*','t2.id as trader_id', 't2.name as applicant_name', 't2.identification_no','t2.email')
                                ->where('application_code',$application_code)
                                ->first();
                if($rec){

                      $applicant_id =  $rec->trader_id;
                      $sub_module_id =  $rec->sub_module_id;
                      $email =  $rec->email;
					  
						$section_id = $rec->section_id;
						
						 $regulated_producttype_id =  getSingleRecordColValue('par_sections', array('id'=>$section_id), 'regulated_producttype_id','mis_db');

						$fees_formular =1;
						 $fees_config = getTableData('par_subprocesspercentagefees_config', array('regulated_producttype_id'=>$regulated_producttype_id, 'sub_module_id'=>$sub_module_id),'mis_db');
						 if($fees_config){
							  $sub_module_id = $fees_config->reference_submodule_id;
							  $fees_formular = $fees_config->fees_formular;
							  $fees_formular = $fees_formular/100;
						 }
					   
					  
					  
						if($module_id == 1){
							 $data_check = array('module_id'=>$module_id,'section_id'=>$rec->section_id,
                             'sub_module_id'=>$rec->sub_module_id);
						}else{
							 $data_check = array('module_id'=>$module_id,
                             'sub_module_id'=>$rec->sub_module_id);
						}
                     
                    
                    $module_data = getTableData('wb_applicationinvoicedata_queries', $data_check,'mis_db');
                    $data_query = $module_data->data_query;
                    

                    $invoice_feessql = DB::select(DB::raw($data_query.' where t1.application_code= '.$application_code));
                    if(is_array($invoice_feessql) && count($invoice_feessql) >0){
                        //$paying_currencydefination = 'costs_in_usd' or costs_in_zmw
                        $invoice_appfeearray = (array)$invoice_feessql[0];
						//$invoice_appfeearray['currency_id'] = $paying_currency_id;
                        $fees_formular = 1;
                        if($fasttrack_option_id == 1){
                           // $quantity = 2;

                        }
                        $invoice_appfeearray['t1.application_feetype_id'] = $application_feetype_id;
						//validate te manufacturng details 
						if($rec->module_id == 1 && ($rec->section_id == 1  || $rec->section_id == 19)){
								$is_manufactureredin_eastafrica = $invoice_appfeearray['is_manufactureredin_eastafrica'];
											$manufacturing_country_id = $invoice_appfeearray['manufacturing_country_id'];
												unset($invoice_appfeearray['manufacturing_country_id']);
											if($is_manufactureredin_eastafrica == 1 && $manufacturing_country_id != 126){
									unset($invoice_appfeearray['t1.is_manufactureredin_eastafrica']);
									$invoice_appfeearray['is_manufactureredin_eastafrica'] = 1;
									unset($invoice_appfeearray['product_category_id']);
									unset($invoice_appfeearray['classification_id']);
									
								}
								else{
									unset($invoice_appfeearray['is_manufactureredin_eastafrica']);
									$invoice_appfeearray['t1.is_manufactureredin_eastafrica'] = 2;
									
								}
							}
                        $fees_data = DB::connection('mis_db')->table('tra_appmodules_feesconfigurations as t1')
                                            ->join('tra_element_costs as t2', 't1.element_costs_id', 't2.id')
                                            ->select(DB::raw("t1.element_costs_id,t2.id, t2.*,(cost *$fees_formular)  as costs") )
                                            ->where($invoice_appfeearray)
                                            ->get();
                      if(($module_id ==4 && $sub_module_id != 81) || $sub_module_id == 61){
                               
                               $import_data =  $this->getImportInvoiceElementFeesData($module_id,$application_code,6,$paying_currency_id);
                         
                               if($import_data['success']){
                                   $importfees_data = $import_data['results'];

                                //    $fees_datas = $fees_data->merge($importfees_data);

                                    $fees_data = $importfees_data;//$fees_datas->all();
                                    $fees_datasave = $importfees_data;//$fees_datas->all();

                               }
                                
                           }else if($module_id == 3){
								 $gmpfees_data =  $this->getGMPAditionalElementFeesData($module_id,$application_code,6,$paying_currency_id);
							  
								   if($gmpfees_data['success']){
									   $gmpfees_data = $gmpfees_data['results'];

										$fees_datas = $fees_data->merge($gmpfees_data);

										$fees_data = $fees_datas->all();

								   }
									$fees_datasave = $fees_data;
									
							}
					
						   else{
							   $fees_datasave = $fees_data;
							   $fees_data = (array)$fees_data;
							   
						   }
                        if (count($fees_data) ==0) {
                            $res = array(
                                'success' => false,
                                'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                            );
                            return response()->json($res);
                        }

                       $res = $this->saveNormalApplicationInvoice($portaltable_name,$rec,$paying_currency_id,$invoice_type_id,$fasttrack_option_id,$fees_datasave);
					   
					   
                    }
                    else{
                        $res = array(
                            'success' => false,
                            'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                        );
                        return response()->json($res);
                    }
                }
        }
        catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);

  }
  
  function getGMPAditionalElementFeesData($module_id,$application_code,$application_feetype_id,$paying_currency_id){
		$application_feetype_id = 6;
		$data_query = "select sub_module_id, t1.gmp_type_id, t2.business_type_id, t3.gmpcountries_region_id from wb_gmp_applications t1 INNER JOIN wb_mansite_otherdetails t2 ON t1.manufacturing_site_id = t2.manufacturing_site_id LEFT JOIN rwanda_mis.par_countries t3 ON t2.country_id = t3.id";
	
	   $invoice_feessql = DB::select(DB::raw($data_query.' where t3.id >0 and  t1.application_code= '.$application_code));
          
		  $quantity =1;
            if(is_array($invoice_feessql) && count($invoice_feessql) >0){
                $invoice_appfeearray = (array)$invoice_feessql[0];
                $invoice_appfeearray['t1.application_feetype_id'] = $application_feetype_id;
				
				 $fees_data = DB::connection('mis_db')->table('tra_appmodules_feesconfigurations as t1')
                                    ->join('tra_element_costs as t2', 't1.element_costs_id', 't2.id')
                                    ->join('par_fee_types as t5', 't2.feetype_id', 't5.id')
                                    ->leftJoin('par_cost_categories as t6', 't2.cost_category_id', 't6.id')
                                    ->leftJoin('par_cost_elements as t7', 't2.element_id', 't7.id')
                                    ->join('par_currencies as t8', 't2.currency_id', 't8.id')
                                    ->select(DB::raw("t1.id, t2.id as element_costs_id ,'Quotation' as invoice_description,now() as date_of_invoicing, '' as invoice_number, t7.name as element_costs, sum(t2.cost*$quantity) as total_invoice_amount,sum(t2.cost*$quantity) as costs,t8.name as currency, t2.currency_id"))
                                    ->where($invoice_appfeearray)
                                    ->get();
				
				 $res = array('success'=>true, 'results'=>$fees_data);
                                        
                    if (!$fees_data) {
                        $res = array(
                            'success' => false,
                            'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                        );
                    }
            
				
			}else{
				 $res = array(
                            'success' => false,
                            'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                        );
				
			}
			return $res;
	  
	  
  }
 function getImportInvoiceElementFeesData($module_id,$application_code,$application_feetype_id,$paying_currency_id){
        $module_data = getTableData('modules', array('id'=>$module_id),'mis_db');
        $portaltable_name = $module_data->portaltable_name;
    
        $rec = DB::table($portaltable_name.' as t1')
            ->join('wb_permits_products as t2', 't1.application_code', 't2.application_code')
            ->select(DB::raw("t1.*,t2.currency_id, sum(t2.unit_price*t2.quantity) as fob_value"))
            ->where('t1.application_code',$application_code)
            ->first();
         
        $sub_module_id = $rec->sub_module_id;
		
        $currency_id = $rec->currency_id;
        $fasttrack_option_id = 1;
		
        $fob_value = $rec->fob_value;
		if(($paying_currency_id ==  $currency_id) && $paying_currency_id ==1){
			$local_currency_id = $paying_currency_id;
			$$currency_id = $paying_currency_id;
		}
		else{
			
			$local_currency_id = getSingleRecordColValue('par_currencies', array('is_local_currency' => 1), 'id', 'mis_db');
        
			
		}
        $quantity = 1;
        if($fasttrack_option_id == 1){
          $quantity = 1;
        }
      
            $data_check = array('module_id'=>$rec->module_id,
            'sub_module_id'=>$rec->sub_module_id);

        
        $module_data = getTableData('wb_applicationinvoicedata_queries', $data_check,'mis_db');
   
	  
        if($module_data && $rec->importexport_permittype_id != 4){
            $data_query = $module_data->data_query;
			$exchange_ratedata =1;
			if(!validateIsNumeric($fob_value)){
				$fob_value = 0;
			}
			$exchange_ratedata = getSingleRecordColValue('par_exchange_rates', array('currency_id' => $currency_id), 'exchange_rate', 'mis_db');
            
            if(!validateIsNumeric($exchange_ratedata)){

                $res = array(
                    'success' => false,
                    'message' => 'Currency Exchange Rate has not been set, contact the finance Department for configuration'
                );
                echo json_encode($res);
                exit();
            }
			$exchange_rate = $exchange_ratedata;
			if(($paying_currency_id ==  $currency_id) && $paying_currency_id ==1){
				$exchange_ratedata = 1;
			}
			
                $invoice_feessql = DB::select(DB::raw($data_query.' where t1.application_code= '.$application_code));
                
				
                if(is_array($invoice_feessql) && count((array)$invoice_feessql) >0){
					
                    $invoice_appfeearray = (array)$invoice_feessql[0];
                    //currency_id
                   // $invoice_appfeearray['t1.application_feetype_id'] = $application_feetype_id;
				  
                    $invoice_appfeearray['sub_module_id'] = $sub_module_id;
                    
                    $fees_data = DB::connection('mis_db')->table('tra_appmodules_feesconfigurations as t1')
                                        ->join('tra_element_costs as t2', 't1.element_costs_id', 't2.id')
                                        ->leftJoin('par_fee_types as t5', 't2.feetype_id', 't5.id')
                                        ->leftJoin('par_cost_categories as t6', 't2.cost_category_id', 't6.id')
                                        
                                        ->leftJoin('par_cost_sub_categories as t10', 't2.sub_cat_id', 't10.id')
                                        ->leftJoin('par_cost_elements as t7', 't2.element_id', 't7.id')
                                        ->leftJoin('par_currencies as t8', 't2.currency_id', 't8.id')
                                        ->leftJoin('par_applicationfee_types as t9', 't1.application_feetype_id', 't9.id')
                                        ->select(DB::raw("t1.id,$local_currency_id as currency_id, 'Quotation' as invoice_description,now() as date_of_invoicing,t10.name as sub_category, t9.name as cost_type,  '' as invoice_number,t5.name as fee_type,t6.name as cost_category,t7.name as element,t2.id as element_costs_id, concat(t5.name,'-', t6.name, '-', t7.name) as element_costs,ROUND((t2.cost/100*$fob_value*$exchange_ratedata),2) as total_invoice_amount,ROUND((t2.cost/100*$fob_value*$exchange_ratedata),2) as costs ,cost as formula_rate,$exchange_rate as exhange_rate,ROUND((t2.cost/100*$fob_value),2) as fob,t8.name as currency,$currency_id as permit_currency_id"))
                                        ->where($invoice_appfeearray)
                                        ->get();
                                     
                                        $res = array('success'=>true, 'results'=>$fees_data);
                                        
                    if (!$fees_data) {
                        $res = array(
                            'success' => false,
                            'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                        );
                    }
            
			
                }
                else{
                    $res = array(
                        'success' => false,
                        'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                    );
                
                }
        }
        else{
            $res = array(
                'success' => false,
                'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
            );
        }
       
        return $res;
      }
	   
public function onCheckGeneratedApplicationInvoice(Request $req){
    try{
            $application_code = $req->application_code;
            $group_application_code = $req->group_application_code;
            $record = DB::connection('mis_db')
                        ->table('tra_application_invoices')
                        ->where(array('application_code'=>$application_code))
                        ->count();
						
			if(validateIsNumeric($group_application_code)){
				$record = DB::connection('mis_db')
                        ->table('tra_application_invoices')
                        ->where(array('group_application_code'=>$group_application_code))
                        ->count();
				if($record){
					$res = array('success'=>true, 'message'=>'Invoice Already Generated');

				}
				else{

					$res = array('success'=>false, 'message'=>'Kindly Generate the Application Invoice(Click Generate Invoice) to proceed with the payment slip upload.');
				}
				
			}
			else{
				if($record){
					$res = array('success'=>true, 'message'=>'Invoice Already Generated');

				}
				else{

					$res = array('success'=>false, 'message'=>'Kindly Generate the Application Invoice(Click Generate Invoice) to proceed with the payment slip upload.');
				}
				
				
			}
            
    }
    catch (\Exception $exception) {
        $res = array(
            'success' => false,
            'message' => $exception->getMessage()
        );
    } catch (\Throwable $throwable) {
        $res = array(
            'success' => false,
            'message' => $throwable->getMessage()
        );
    }
    return response()->json($res);
}
  function getInvoiceElementFeesData($module_id,$application_code,$paying_currency_id,$application_feetype_id,$fasttrack_option_id){
	  
	  
    $module_data = getTableData('modules', array('id'=>$module_id),'mis_db');
    $portaltable_name = $module_data->portaltable_name;

    $rec = DB::table($portaltable_name.' as t1')
    ->select('t1.*')
    ->where('application_code',$application_code)
    ->first();

    $sub_module_id = $rec->sub_module_id;
    $section_id = $rec->section_id;
	
	$regulated_producttype_id =  getSingleRecordColValue('par_sections', array('id'=>$section_id), 'regulated_producttype_id','mis_db');

	$fees_formular =1;
	 $fees_config = getTableData('par_subprocesspercentagefees_config', array('regulated_producttype_id'=>$regulated_producttype_id, 'sub_module_id'=>$sub_module_id),'mis_db');
	 if($fees_config){
		  $sub_module_id = $fees_config->reference_submodule_id;
		  $fees_formular = $fees_config->fees_formular;
		  $fees_formular = $fees_formular/100;
	 }
   
   if($rec->module_id == 1){
							 $data_check = array('module_id'=>$module_id,'section_id'=>$rec->section_id,
                             'sub_module_id'=>$rec->sub_module_id);
						}else{
							 $data_check = array('module_id'=>$module_id,
                             'sub_module_id'=>$rec->sub_module_id);
						}
                
    $module_data = getTableData('wb_applicationinvoicedata_queries', $data_check,'mis_db');
					
	
    if($module_data){
        $data_query = $module_data->data_query;
 
            $invoice_feessql = DB::select(DB::raw($data_query.' where t1.application_code= '.$application_code));
          
		  
            if(is_array($invoice_feessql) && count($invoice_feessql) >0){
				
                $invoice_appfeearray = (array)$invoice_feessql[0];
                $invoice_appfeearray['t1.application_feetype_id'] = $application_feetype_id;
                //$invoice_appfeearray['fasttrack_option_id'] = $fasttrack_option_id;

                if($fasttrack_option_id == 1){
                  //  $quantity = 2;

                }
				//validate te manufacturng details 
				if($rec->module_id == 1 && ($rec->section_id == 1  || $rec->section_id == 19)){
					$is_manufactureredin_eastafrica = $invoice_appfeearray['is_manufactureredin_eastafrica'];
								$manufacturing_country_id = $invoice_appfeearray['manufacturing_country_id'];
									unset($invoice_appfeearray['manufacturing_country_id']);
								if($is_manufactureredin_eastafrica == 1 && $manufacturing_country_id != 126){
						unset($invoice_appfeearray['t1.is_manufactureredin_eastafrica']);
						$invoice_appfeearray['is_manufactureredin_eastafrica'] = 1;
						unset($invoice_appfeearray['product_category_id']);
						unset($invoice_appfeearray['classification_id']);
						
					}
					else{
						unset($invoice_appfeearray['is_manufactureredin_eastafrica']);
						$invoice_appfeearray['t1.is_manufactureredin_eastafrica'] = 2;
						
					}
				}
				//t6.name, '-', 
                $fees_data = DB::connection('mis_db')->table('tra_appmodules_feesconfigurations as t1')
                                    ->join('tra_element_costs as t2', 't1.element_costs_id', 't2.id')
                                    ->join('par_fee_types as t5', 't2.feetype_id', 't5.id')
                                    ->leftJoin('par_cost_categories as t6', 't2.cost_category_id', 't6.id')
                                    ->leftJoin('par_cost_elements as t7', 't2.element_id', 't7.id')
                                    ->join('par_currencies as t8', 't2.currency_id', 't8.id')
                                    ->select(DB::raw("t1.id,'Quotation' as invoice_description,now() as date_of_invoicing,t2.id as element_costs_id, '' as invoice_number, t7.name as element_costs, sum(t2.cost*$fees_formular) as total_invoice_amount,t8.name as currency"))
                                    ->where($invoice_appfeearray)
                                    ->get();
							if(($module_id ==4  && $sub_module_id != 81) || $sub_module_id ==61){
                               $fees_data = array();
                               $import_data =  $this->getImportInvoiceElementFeesData($module_id,$application_code,6,$paying_currency_id);
                          
                               if($import_data['success']){
                                   $importfees_data = $import_data['results'];

                                    //$fees_datas = $fees_data->merge($importfees_data);

                                    $fees_data = $importfees_data;//$fees_datas->all();

                               }
                                if (count($fees_data) ==0) {
									$res = array(
										'success' => false,
										'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
									);
								}else{
									$res = array('success'=>true, 'invoice_data'=>$fees_data);
								}
                            }
							else if($module_id == 3){
								 $gmpfees_data =  $this->getGMPAditionalElementFeesData($module_id,$application_code,6,$paying_currency_id);
							  
								   if($gmpfees_data['success']){
									   $gmpfees_data = $gmpfees_data['results'];

										$fees_datas = $fees_data->merge($gmpfees_data);

										$fees_data = $fees_datas->all();

								   }
									if (count($fees_data) ==0) {
										$res = array(
											'success' => false,
											'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
										);
									}else{
										$res = array('success'=>true, 'invoice_data'=>$fees_data);
									}
								
							}
							else{
								//get additonal fees for GMP 
								
								
								$res = array('success'=>true, 'invoice_data'=>$fees_data);
								 if ($fees_data->count() ==0) {
									$res = array(
										'success' => false,
										'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
									);
								}
							}
							
                  
            }
            else{
                $res = array(
                    'success' => false,
                    'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                );
            
            }
    }
    else{
        $res = array(
            'success' => false,
            'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
        );
    }
   

   
    return $res;
  }
  function getGeneratedInvoices($application_code){
        $invoice_data = DB::connection('mis_db')->table('tra_application_invoices as t1')
        ->leftJoin('tra_invoice_details as t2', 't1.id','t2.invoice_id')
        ->leftJoin('par_currencies as t3', 't2.paying_currency_id', 't3.id')
        ->leftJoin('tra_element_costs as t4', 't2.element_costs_id', 't4.id')
        ->leftJoin('par_fee_types as t5', 't4.feetype_id', 't5.id')
        ->leftJoin('par_cost_categories as t6', 't4.cost_category_id', 't6.id')
        ->leftJoin('par_cost_elements as t7', 't4.element_id', 't7.id')
        ->leftJoin('tra_iremboinvoices_information as t8', 't1.invoice_no', 't8.rfdaInvoiceNo')
        ->leftJoin('tra_payments as t9', 't1.id', 't9.invoice_id')
        ->select(DB::raw("t1.id,t1.invoice_no, t1.application_code,t8.iremboInvoiceNumber,t1.module_id, t1.id as invoice_id, t1.tracking_no, 'Proforma Invoice' as invoice_description,  t1.invoice_no as invoice_number,t1.tracking_no, t1.date_of_invoicing,concat(t5.name,'-', t6.name, '-', t7.name) as element_costs, (t2.total_element_amount) as total_invoice_amount,t3.name as currency,concat(if(t2.total_element_amount =0, 'Has Payment Exemption-','') ,if(t9.id >0, 'Paid', 'Not Paid')) as payment_status,t9.id as payment_id, t9.amount_paid"))
        ->where(array('t1.application_code'=>$application_code))
        ->get();
        return $invoice_data;

  }
  public function  onLoadGeneratedApplicationInvoice(Request $req){
    try{
        $application_code = $req->application_code;
        $invoice_data = $this->getGeneratedInvoices($application_code);
        $res = array('success'=>true, 'invoice_data'=>$invoice_data);
                      
        if($invoice_data->count() ==0){
            $res = array('success'=>false, 'message'=>'No Invoice Found, contact authority for enquiry.');
        }
    }
    catch (\Exception $exception) {
        $res = array(
            'success' => false,
            'message' => $exception->getMessage()
        );
    } catch (\Throwable $throwable) {
        $res = array(
            'success' => false,
            'message' => $throwable->getMessage()
        );
    }
    return response()->json($res);


  }
  public function onLoadApplicationInvoice(Request $req){
        try{
            $application_code = $req->application_code;
            $module_id = $req->module_id;
            $sub_module_id = $req->sub_module_id;
            $paying_currency_id = $req->paying_currency_id;
            $status_id = $req->status_id;
            $application_feetype_id = $req->application_feetype_id;
            $fasttrack_option_id = $req->fasttrack_option_id;
            //check if Invoice exists 
			$module_data = getTableData('modules', array('id'=>$module_id),'mis_db');
			$portaltable_name = $module_data->portaltable_name;
$trader_id = 0;
			$rec = DB::table($portaltable_name.' as t1')
			->select('t1.*')
			->where('application_code',$application_code)
			->first();
			if($rec){
				$trader_id = $rec->trader_id;
				$tracking_no = $rec->tracking_no;
			}
			
			if(validateIsNumeric($status_id) && $status_id == 52){
				 $application_feetype_id = 7;
				
			}
			else{
				 $application_feetype_id = 1;
			}
           $invoice_data = $this->getGeneratedInvoices($application_code);
            $res = array('success'=>true, 'invoice_data'=>$invoice_data);
               
            if($invoice_data->count() ==0){
               
                    $res = $this->getInvoiceElementFeesData($module_id,$application_code,$paying_currency_id,$application_feetype_id,$fasttrack_option_id);
				$res['has_generated_invoice'] = false;

            }else{
				$res['has_generated_invoice'] = true;
				$res['invoice_no'] = $invoice_data[0]->invoice_no;
				$res['iremboInvoiceNumber'] = $invoice_data[0]->iremboInvoiceNumber;
           
			}
			$res['publicKey'] = Config('constants.irembopay.irembopay_publickey');
			$res['is_authorised_manualpaymentproof'] = $this->getAuthorisationManProofSetup($application_code,$trader_id,$tracking_no);
           
        }
        catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);


  }  
  function getAuthorisationManProofSetup($application_code,$trader_id,$tracking_no){
			$is_authorised_manualpaymentproof = false;
			$record = DB::connection('mis_db')->table("tra_applicationuploadproof_authorisation")->where('applicant_id',$trader_id)->first();
			
			if($record){
				
				$is_authorised_manualpaymentproof = true;
			}
			else{
				$record = DB::connection('mis_db')->table("tra_applicationuploadproof_authorisation")->where('tracking_no',$tracking_no)->first();
				if($record){
					
					$is_authorised_manualpaymentproof = true;
				}
				
			}
		return $is_authorised_manualpaymentproof;
  }
  function saveNormalApplicationInvoice($table_name,$app_details,$paying_currency_id,$invoice_type_id,$fasttrack_option_id,$fees_data,$group_application_code=0){
           //detail
            $reference_no = $app_details->reference_no;
            $tracking_no = $app_details->tracking_no;
            $sub_module_id = $app_details->sub_module_id;
            $module_id = $app_details->module_id;
            $applicant_id = $app_details->trader_id;
            $zone_id = $app_details->zone_id;
            $applicant_name = $app_details->applicant_name;
            $application_code = $app_details->application_code;
            $user_id = $applicant_name;
            /*paying_currency_id
            $applicant_details = getTableData('wb_trader_account', array('identification_no' => $identification_no),'mis_db');
            if (is_null($applicant_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting applicant details!!'
                );
                return response()->json($res);
            } 
            $applicant_id = $applicant_details->id;
            */
            //update the app table email applicant_id currency_id
            DB::table($table_name)
            ->where('application_code', $application_code)
            ->update(array('fasttrack_option_id' => $fasttrack_option_id, 'paying_currency_id'=>$paying_currency_id));
            $due_date_counter = 90;
            $date_today = Carbon::now();
            $due_date = $date_today->addDays($due_date_counter);
            $invoicing_date = Carbon::now();
            $isLocked =0;
            $invoice_params = array(
                'applicant_id' => $applicant_id,
                'applicant_name' => $applicant_name,
                'reference_no'=>$reference_no,
                'group_application_code'=>$group_application_code,
                'module_id'=>$module_id,
                'zone_id'=>$zone_id,
                'sub_module_id'=>$sub_module_id,
                'tracking_no'=>$tracking_no,
                'isLocked' => $isLocked,
                'paying_currency_id'=>$paying_currency_id,
                'fasttrack_option_id'=>$fasttrack_option_id,
                'invoice_type_id'=>$invoice_type_id,
                'gepg_submission_status'=>2,
                'date_of_invoicing'=>$invoicing_date,
                'payment_terms' => 'Due in ' . $due_date_counter . ' Days',
                'created_on' => Carbon::now()
            );
            
            $invoice_params['prepared_by'] = $applicant_name;
            $invoice_params['due_date'] = $due_date;
         
                $invoice_no = generateInvoiceNo($user_id);
                $invoice_params['invoice_no'] = $invoice_no;

                $invoice_params['application_code'] = $application_code;
               
                $res = insertRecord('tra_application_invoices', $invoice_params, $user_id,'mis_db');
                
                if ($res['success'] == false) {
                    return \response()->json($res);
                }
                $invoice_id = $res['record_id'];
                $params = array();
                foreach ($fees_data as $fee) {
                    $exchange_ratedata = getTableData('par_exchange_rates', array('currency_id' => $fee->currency_id),'mis_db');
                    $invoice_amount = ceil($fee->costs);
                    $quantity = 1;
                    if($fasttrack_option_id == 1){
                        $quantity = 2;
                    }//manage the abover 
					
					if($paying_currency_id ==4 && $fee->currency_id ==4){
						$exchange_rate =1;
					}else if($paying_currency_id ==1 && $fee->currency_id ==4){
						$exchange_ratedata = getTableData('par_exchange_rates', array('currency_id' => $paying_currency_id),'mis_db');
						$exchange_rate = $exchange_ratedata->exchange_rate;
						//means that the amount needs to 
						$invoice_amount = $invoice_amount /$exchange_rate;
					}else if($paying_currency_id ==1 && $fee->currency_id ==1){
						$exchange_ratedata = getTableData('par_exchange_rates', array('currency_id' => $paying_currency_id),'mis_db');
						$exchange_rate = $exchange_ratedata->exchange_rate;
						//means that the amount needs to 
						$invoice_amount = $invoice_amount;
						
					}else if($paying_currency_id ==4 && $fee->currency_id ==1){
						$exchange_ratedata = getTableData('par_exchange_rates', array('currency_id' => $fee->currency_id),'mis_db');
						$exchange_rate = $exchange_ratedata->exchange_rate;
						//means that the amount needs to 
						$invoice_amount = $invoice_amount * $exchange_rate;
						$exchange_rate = 1;
						
					}else{
						$paying_currency_id =$fee->currency_id;
						$exchange_ratedata = getTableData('par_exchange_rates', array('currency_id' => $fee->currency_id),'mis_db');
						$exchange_rate = $exchange_ratedata->exchange_rate;
						//means that the amount needs to 
						$invoice_amount = $invoice_amount;
						
					}
					
						$params[] = array(
							'invoice_id' => $invoice_id,
							'element_costs_id' => $fee->element_costs_id,
							'element_amount' =>  $invoice_amount ,
							'currency_id' => $paying_currency_id,
							'paying_currency_id'=>$paying_currency_id,
							'exchange_rate' => $exchange_rate,
							'quantity' => $quantity,
							'paying_exchange_rate' => $exchange_rate,
							'total_element_amount'=> $invoice_amount ,
							'created_on' => Carbon::now()
						);
						
					
                    
                }
                if(count($params)){
                    DB::connection('mis_db')->table('tra_invoice_details')->insert($params);
                }
				$record = array();
            //iremebo Integration
			iremboFuncInvoiceSubmission($invoice_id);
			if(validateIsNumeric($invoice_id)){
				 $record = DB::connection('mis_db')->table('tra_application_invoices as t1')
                            ->select('t1.*', 't1.id as invoice_id')           
                ->where(array('id'=>$invoice_id))
                            ->first();
			}
                $res = array(
                    'success' => true,
                    'invoice_id' => $invoice_id,
                    'invoice_no' => $invoice_no,
					 'invoice_data'=>$record,
                    'message' => 'Invoice details saved successfully!!'
                );
                return $res;
  }
  function onSaveApplicationConfirmationRessponse($req,$table_name){
        $application_code = $req->application_code;
        $approvaldecision_response_id = $req->approvaldecision_response_id;

        $remarks  = $req->remarks;
        $trader_id = $req->trader_id;
        $trader_email = $req->trader_email;
        $table_data = array('application_code'=>$application_code, 
                      'approvaldecision_response_id'=>$approvaldecision_response_id,
                      'confirmed_by'=>$trader_email,
                      'confirmed_on'=>Carbon::now(),
                      'remarks'=>$remarks);
      //  $table_name = 'tra_certificate_confirmations';
        $where = array('application_code'=>$application_code);
        $record = DB::connection('mis_db')->table($table_name)
                    ->where($where)
                    ->first();
        if($record){
            $prev_data = getPreviousRecords($table_name, $where, 'mis_db');
            $previous_data = $prev_data;
            $table_data['dola'] = Carbon::now();
            $table_data['altered_by'] = $trader_email;
            
            $res = updateRecord($table_name, $previous_data, $where, $table_data, $trader_id,'mis_db');
        }
        else{
            $table_data['created_on'] = Carbon::now();
            $table_data['created_by'] = $trader_email;
            
            $res = insertRecord($table_name, $table_data, $trader_id,'mis_db');

        }
return $res;
  }public function onCancelGeneratedApplicationInvoice(Request $req){
    try{
        $application_code = $req->application_code;
        $invoice_id = $req->invoice_id;
       // tra_invoicecancellation_requests tra_invoicecancellation_requests
       
       $trader_id = $req->trader_id;
       $trader_email = $req->trader_email;
                     $cancellation_request = array('application_code'=>$application_code,
                                      'invoice_id'=>$invoice_id,  
                                      'requested_on'=>Carbon::now(),  
                                      'requested_trader_id'=>$trader_id,  
                                      'reason_for_cancellation'=>'Cancellation Request',  
                                      'remarks'=>'Cancellation Request',  
                                      'created_on'=>Carbon::now()
                                 );
                     $insert_res = insertRecord('tra_invoicecancellation_requests', $cancellation_request, $trader_email, 'mis_db');
					
                    $where_invoice = array('id'=>$invoice_id,'application_code'=>$application_code);
				
                    $invoice_rec = DB::connection('mis_db')->table('tra_application_invoices as t1')
					->select(DB::raw("id,invoice_no,prepared_by,tracking_no,reference_no,receipt_no,PayCntrNum,application_id,application_code,module_id,sub_module_id,section_id,date_of_invoicing,fob,invoice_amount,paying_exchange_rate,paying_currency_id"))
                    ->where($where_invoice)
                    ->first();
					//check if there is any ayment made 
					$where_payment = array('invoice_id'=>$invoice_id,'application_code'=>$application_code);
				
					$payment_record = DB::connection('mis_db')->table('tra_payments')->where($where_payment)->count();
					if($payment_record >0){
						$res = array('success'=>false,
						'message'=>'Payment for the said Invoice has already been effected, cancell payments and then invoices');
						
						 return \response()->json($res);
					}
					$application_code = $req->application_code;
					$invoice_no = $req->invoice_no;
                    $cancelled_invoicedata = convertStdClassObjToArray($invoice_rec);
                    
                    $insert_res = insertRecord('tra_application_invoicescancellation', $cancelled_invoicedata, $trader_email, 'mis_db');
					
                    $previous_data = getPreviousRecords('tra_application_invoices',$where_invoice, 'mis_db' );
					
                    $previous_data = $previous_data;
                    $res= deleteRecordNoTransaction('tra_application_invoices', $previous_data, $where_invoice, $trader_email, 'mis_db');
					//send notificaitons 
					//update the invoice to iremboe 
					funcInvoiceIremboUpateExpiryDate($invoice_id,$invoice_no, $application_code);
					
					
                    $res = array('success'=>true,'message1'=>$res, 'message'=>'The Invoice Has been cancelled Successfully, regenerate the Proforma Invoice and proceed.');

    }
    catch (\Exception $exception) {
        $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

    } catch (\Throwable $throwable) {
        $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
    }
    return response()->json($res, 200);

}
public function onLoadappUploadPaymentDeTailsData(Request $req){
    try{
            $application_code = $req->application_code;
            $record = DB::connection('mis_db')
                        ->table('tra_uploadedpayments_details as t1')
                        ->leftJoin('tra_application_uploadeddocuments as t2', 't1.document_upload_id','t2.id')
                        ->leftJoin('par_currencies as t3', 't1.currency_id','t3.id')
                        ->select('t1.*', 't2.*', 't3.name as currency')
                        ->where(array('t1.application_code'=>$application_code))
                        ->get();

            if($record){
                $res = array('success'=>true,'invoice_data'=>$record, 'message'=>'Invoice Already Generated');
				
            }
            else{
                $res = array('success'=>true, 'message'=>'Kindly Generate the Application Invoice(Click Generate Invoice) to proceed with the payment slip upload.');
            }
            
    }
    catch (\Exception $exception) {
        $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

    } catch (\Throwable $throwable) {
        $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
    }
     return response()->json($res, 200);

}

public function generateTraderIdentification_no(){
			$records = DB::table('wb_trader_account')
						->where('created_by', 'Migration')
						->get();
			if($records){
				
				foreach($records  as $rec){
					
						if(!validateIsNumeric($rec->identification_no)){
							$trader_id = $rec->id;
							$trader_id = $rec->id;
							
							$trader_no = generateTraderNo('wb_trader_account');
									
							DB::connection('mis_db')->table('wb_trader_account')->where(array('id'=>$trader_id))
									->update(array('identification_no'=>$trader_no));
								DB::table('wb_trader_account')->where(array('id'=>$trader_id))
									->update(array('identification_no'=>$trader_no));
									
							DB::table('wb_trader_account')->where(array('id'=>$trader_id))
									->update(array('identification_no'=>$trader_no));
									
							DB::table('wb_traderauthorised_users')->where(array('trader_id'=>$trader_id))
									->update(array('identification_no'=>$trader_no));
									
									print_r('record Updated Successfully');	
						}
					
					
				}
				
				
			}

}
public function addTraderAccountUsers(){
	
	$records = DB::table('wb_trader_account')
						->where('created_by', 'Migration')
					->get();
					
	if($records){
				
				foreach($records  as $rec){
					$trader_id = $rec->id;
					$email_address = $rec->email;
					$trader_no = $rec->identification_no;
					$user_rec = Db::table('wb_traderauthorised_users')->where('trader_id',$trader_id)->count();
					
					if($user_rec ==0 && $email_address != ''){
						$user_passwordData = str_random(8);
                   //had code for test
						$uuid = generateUniqID();//unique user ID
						$user_password = hashPwd($email_address, $uuid, $user_passwordData);
							$user_data = array('email'=> $email_address,
                                 'trader_id'=>$trader_id,
                                 'password'=>$user_password,
                                 'country_id'=>$rec->country_id,
                                 'telephone_no'=>$rec->telephone_no,
                                 'uuid'=>$uuid,
                                 'is_verified'=>1,
                                 'is_confirmed'=>1,
                                 'status_id'=>1,//as actve
                                 'account_roles_id'=>1,
                                 'created_by'=>'System',
                                 'identification_no'=>$trader_no,
                                 'created_on'=>date('Y-m-d H:i:s')
                            );
                    //the details //tin_no
						
							$resp = insertRecord('wb_traderauthorised_users', $user_data, 'Create Trader Users');
	print_r($resp);
						
							print_r('record Added Successfully');	
					}

					
					
				}
	}
	
				
				  
	
	
}public function onSaveinitCAPAresponses(Request $req)
    {
        
        $trader_id = $req->trader_id;
        $id = $req->input('id');
        $response = $req->input('response_txt');
        $table_name = 'tra_inspectioncapa_deficiencies';
        $where = array(
            'id' => $id
        );
        $res = array('success'=>false, 'message'=>'Record Not found');
        
        $table_data = array(
            'root_causeanalysis' => $req->root_causeanalysis,
            'corrective_actionssteps' => $req->corrective_actionssteps,
            'corrective_actions' => $req->corrective_actions,
            'completion_date' => $req->completion_date,
            'created_by'=>$trader_id,
            'created_on'=>Carbon::now()
        );
        try {
            $prev_data = getPreviousRecords($table_name, $where, 'mis_db');
            if (validateisNumeric($id)) {
            
                $previous_data = $prev_data;
                $res = updateRecord($table_name, $previous_data, $where, $table_data, $trader_id,'mis_db');
                //update the 
                
            } 
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }public function getapplicationCAPARequestsData12(Request $request)
    {
        try {
            $application_code = $request->application_code;
            $process_id = $request->process_id;
            $results = array();

            $qry = DB::connection('mis_db')->table('tra_appinspectioncapa_reftracker as t1')
                    ->leftJoin('par_query_types as t2', 't1.query_type_id', 't2.id')
                    ->leftJoin('par_checklist_categories as t3', 't1.checklist_category_id', 't3.id')
                    ->leftJoin('users as t4', 't1.queried_by', 't4.id')
                    ->leftJoin('par_query_statuses as t5', 't1.status_id', 't5.id')
                    ->leftJoin('tra_inspectioncapa_deficiencies as t6', 't1.id', 't6.inspection_capa_id')
                    ->select( 't1.id as query_id', 't1.queried_on as addedd_on', 't1.*', 't2.name as query_type', 't3.name as checklist_category', 't1.status_id as status', 't5.name as query_status',  DB::raw("CONCAT_WS(' ',decrypt(t4.first_name),decrypt(t4.last_name)) as queried_by,'t6.*'"))
                    ->groupBy('t1.id');
                    
            if(validateIsNumeric($application_code)){
                $qry->where('t1.application_code', $application_code);
            }

            if(validateIsNumeric($process_id)){
                $qry->where('t1.process_id', $process_id);
            }

            $results = $qry->get();
            
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
        }
        return response()->json($res);
    }public function getapplicationCAPARequestsData(Request $request)
{
    try {
        $application_code = $request->application_code;
        $process_id = $request->process_id;
        $results = array();

        $qry = DB::connection('mis_db')->table('tra_appinspectioncapa_reftracker as t1')
                ->leftJoin('par_query_types as t2', 't1.query_type_id', 't2.id')
                ->leftJoin('par_checklist_categories as t3', 't1.checklist_category_id', 't3.id')
                ->leftJoin('users as t4', 't1.queried_by', 't4.id')
                ->leftJoin('par_query_statuses as t5', 't1.status_id', 't5.id')
                ->leftJoin('tra_inspectioncapa_deficiencies as t6', 't1.id', 't6.inspection_capa_id')
                ->select( 't1.id as query_id', 't1.queried_on as addedd_on', 't1.*', 't2.name as query_type', 't3.name as checklist_category', 't1.status_id as status', 't5.name as query_status','t6.*',   DB::raw("CONCAT_WS(' ',decrypt(t4.first_name),decrypt(t4.last_name)) as queried_by"))
                ->groupBy('t1.id');
                
        if(validateIsNumeric($application_code)){
            $qry->where('t1.application_code', $application_code);
        }

        if(validateIsNumeric($process_id)){
            $qry->where('t1.process_id', $process_id);
        }

        $results = $qry->get();
        
        $res = array(
            'success' => true,
            'data' => $results,
            'message' => 'All is well'
        );
    } catch (\Exception $exception) {
        $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
    } catch (\Throwable $throwable) {
        $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
    }
    return response()->json($res);
}

    public function getapplicationReinspectionRequestsData(Request $request)
    {
        try {
            $application_code = $request->application_code;
            $process_id = $request->process_id;
            $results = array();

            $qry = DB::connection('mis_db')->table('tra_appreinspectionrequest_reftracker as t1')
                    ->leftJoin('par_query_types as t2', 't1.query_type_id', 't2.id')
                    ->leftJoin('par_checklist_categories as t3', 't1.checklist_category_id', 't3.id')
                    ->leftJoin('users as t4', 't1.queried_by', 't4.id')
                    ->leftJoin('par_query_statuses as t5', 't1.status_id', 't5.id')
                    ->leftJoin('reinspectiontitems_queries as t6', 't1.id', 't6.query_id')
                    ->select( 't1.id as query_id', 't1.queried_on as addedd_on','t1.*', 't2.name as query_type', 't3.name as checklist_category', 't1.status_id as status', 't5.name as query_status',  DB::raw("CONCAT_WS(' ',decrypt(t4.first_name),decrypt(t4.last_name)) as queried_by,  t6.*,t6.query as comments"))
                    ->groupBy('t1.id');
                    
            if(validateIsNumeric($application_code)){
                $qry->where('t1.application_code', $application_code);
            }

            if(validateIsNumeric($process_id)){
                $qry->where('t1.process_id', $process_id);
            }

            $results = $qry->get();
            
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
        }
        return response()->json($res);
    }
public function onCustomerAccountRegistrationSubmission(Request $req){
       
    $trader_id = $req->trader_id;
    //DB::beginTransaction();
     try{
            $db_name = DB::connection('mis_db')->getDatabaseName();
             $record_id = $trader_id;
             $data = array(
                 'traderaccounts_status_id'=>2
             );  
             $where_app = array('id'=>$record_id);
             $count = DB::connection('mis_db')->table('wb_trader_account')
                 ->where($where_app)
                 ->count();
             if($count){
                $previous_data = getPreviousRecords('wb_trader_account', $where_app,'mis_db');
                $trader_no = $previous_data['results'][0]['identification_no']; 
                $email_address = $previous_data['results'][0]['email']; 
                $resp=   updateRecord('wb_trader_account', $previous_data, $where_app, $data, '','mis_db');
                if(!$resp['success']){
                     return \response()->json(array('success'=>false,'message'=>$resp['message'])); 
                }

                $previous_data = getPreviousRecords('wb_trader_account', $where_app);
                $resp=   updateRecord('wb_trader_account', $previous_data, $where_app, $data, '');
                if(!$resp['success']){
                     return \response()->json(array('success'=>false,'message'=>$resp['message'])); 
                }
                
                 $subject = 'Completion of the Customer Account Registration';
                 
                             $email_content = "</br>.We wish to acknowledge the completion and submission of the Customer Account Registration Information</br>.";
                             $email_content = "</br>You can proceed an access the Authority's regulatory functions.</br>.";
                            
                             $email_content .= " - Trader Account No: ".$trader_no .".<br/>";
                             $email_content .= " - Account Email Address: ".$email_address .".<br/>";
                           
                             
              //   $res = sendMailNotification($trader_data->name, $email_address,$subject,$email_content);
                 
                 
                 $res = array('success'=>true,'trader_no'=> $trader_no,'message'=>'Account registration process has been completed successfully');
                 
             }else{
				  $res = array('success'=>true,'message'=>'Account registration process has been completed successfully');
                 
			 }
           
     } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
        }
         return response()->json($res, 200);
 }public function onloadAsessmentProcedureProductsSubmissionDta(Request $request){
		
		 $where = array(
            'external_user_id' => $request->mis_external_user_id,
            'isDOne'=>0
        );
		$module_id = 1;
		$sub_module_id = 7;
        try {
            $qry = DB::connection('mis_db')->table('tra_submissions as t1')
                ->join('wf_tfdaprocesses as t2', 't1.process_id', '=', 't2.id')
                ->join('wf_workflow_stages as t3', 't1.previous_stage', '=', 't3.id')
                ->join('wf_workflow_stages as t4', 't1.current_stage', '=', 't4.id')
                ->leftJoin('par_system_statuses as t5', 't1.application_status_id', '=', 't5.id')
                ->leftJoin('par_submission_urgencies as t6', 't1.urgency', '=', 't6.id')
                ->join('users as t7', 't1.usr_from', '=', 't7.id')
                ->leftJoin('wb_trader_account as t9', 't1.applicant_id', '=', 't9.id')
                ->leftJoin('modules as t10', 't1.module_id', '=', 't10.id')
                ->leftJoin('sub_modules as t11', 't1.sub_module_id', '=', 't11.id')
                ->leftJoin('tra_product_applications as t12', 't1.application_code', '=', 't12.application_code')
                ->leftJoin('tra_product_information as t13', 't12.product_id', '=', 't13.id')
                ->leftJoin('par_common_names as t14', 't13.common_name_id', '=', 't14.id')
                ->leftJoin('par_dosage_forms as t15', 't13.dosage_form_id', '=', 't15.id')
                ->leftJoin('wb_trader_account as t16', 't12.applicant_id', '=', 't16.id')
                ->select(DB::raw("t1.*,t15.name as dosage_form,t16.name as registrant, t14.name as generic_name, t13.brand_name, t1.current_stage as workflow_stage, t1.date_received as assigned_on,t1.application_id as active_application_id, t2.name as process_name,
                    t3.name as prev_stage, t4.name as assigned_process,t4.is_general,t5.name as application_status,t6.name as urgencyName,t6.name as urgency_name,
                    CONCAT_WS(' ',decrypt(t7.first_name),decrypt(t7.last_name)) as assigned_by,TOTAL_WEEKDAYS(now(), date_received) as time_span, t10.name as module_name, t11.name as application_type,
                    t9.name as applicant_name"))
					->where(array('t1.module_id'=>$module_id, 't1.sub_module_id'=>$sub_module_id))
                ->where('t4.stage_status','<>',3)
                ->where($where);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'data' => $results,
                'message' => 'All is well'
            );
        }  catch (\Exception $exception) {
				$res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

			} catch (\Throwable $throwable) {
				$res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
			}
			 return response()->json($res, 200);
		
	} public function onLoadGroupApplicationInvoice(Request $req){
    try{

            $group_application_code = $req->group_application_code;
            $module_id = $req->module_id;
            $sub_module_id = $req->sub_module_id;
            $paying_currency_id = $req->paying_currency_id;
            $application_feetype_id = $req->application_feetype_id;
            $fasttrack_option_id = $req->fasttrack_option_id;
            //check if Invoice exists 
			$tracking_no ='';
			$trader_id ='';
			$rec = DB::table('wb_appsubmissions_typedetails as t1')
				->select('t1.*')
				->where('group_application_code',$group_application_code)
				->first();
			if($rec){
				$trader_id = $rec->trader_id;
				$tracking_no = $rec->tracking_no;
			}
           $invoice_data = $this->getGroupedApGeneratedInvoices($group_application_code);

            $res = array('success'=>true, 'invoice_data'=>$invoice_data);
               
            if($invoice_data->count() ==0){
                    $application_feetype_id = 1;
                    $res = $this->getGoupedInvoiceElementFeesData($module_id,$group_application_code,$paying_currency_id,$application_feetype_id,$fasttrack_option_id);

                    $res['has_generated_invoice'] = false;

            }else{
				$record = DB::connection('mis_db')->table('tra_groupedapplication_invoices')
							->where(array('group_application_code'=>$group_application_code))
							->first();
                $res['has_generated_invoice'] = true;
                $res['group_invoice_no'] = $record->group_invoice_no;
                $res['iremboInvoiceNumber'] = $record->iremboInvoiceNumber;
            }
          $res['publicKey'] = Config('constants.irembopay.irembopay_publickey');
           $res['is_authorised_manualpaymentproof'] = $this->getAuthorisationManProofSetup($group_application_code,$trader_id,$tracking_no);
           
    }
    catch (\Exception $exception) {
        $res = array(
            'success' => false,
            'message' => $exception->getMessage()
        );
    } catch (\Throwable $throwable) {
        $res = array(
            'success' => false,
            'message' => $throwable->getMessage()
        );
    }
    return response()->json($res);

  }
//wb_appsubmissions_typedetails
function getGroupedApplicationPercentage($group_application_code){
	$percentage = 1;
	$record = DB::table('wb_appsubmissions_typedetails as t1')
	              ->join('wb_product_applications as t2', 't1.group_application_code', 't2.group_application_code')
				  ->select(DB::raw("count(t2.id) as number_of_applications,t1.product_origin_id, t1.section_id"))
				  ->where('t1.group_application_code',$group_application_code)
				  ->first();
				 
	if($record){
			
			$section_id = $record->section_id;
			$number_of_applications = $record->number_of_applications;
			$regulated_producttype_id =  getSingleRecordColValue('par_sections', array('id'=>$section_id), 'regulated_producttype_id','mis_db');

			$rec = DB::connection('mis_db')->table('par_groupedinvoice_percentiles')
						->where('regulated_producttype_id',$regulated_producttype_id)
						//->whereRaw("start_counter >= $number_of_applications and end_counter <= $number_of_applications")
						->whereRaw("$number_of_applications between start_counter  and end_counter")
						->first();
						
			if($rec){
				$cost_percentage = $rec->cost_percentage;
				if($cost_percentage < 100){
					$percentage = $cost_percentage/100;
				}
			}
		
		
	}
	return $percentage;
	
}
function getGoupedInvoiceElementFeesData($module_id,$group_application_code,$paying_currency_id,$application_feetype_id,$fasttrack_option_id){
	//get the gouping perfcentage 
	$percentage = $this->getGroupedApplicationPercentage($group_application_code);
    $module_data = getTableData('modules', array('id'=>$module_id),'mis_db');
    $portaltable_name = $module_data->portaltable_name;
    $application_feeData = array();
    $records = DB::table($portaltable_name.' as t1')
            ->select('t1.*')
            ->where('group_application_code',$group_application_code)
            ->get();
            foreach($records as $rec){
                $sub_module_id = $rec->sub_module_id;
                $application_code = $rec->application_code;
                $tracking_no = $rec->tracking_no;
            
               if($rec->module_id == 1){
                        $data_check = array('module_id'=>$rec->module_id,'section_id'=>$rec->section_id,
                                         'sub_module_id'=>$rec->sub_module_id);
                }else{
                        $data_check = array('module_id'=>$rec->module_id,
                                         'sub_module_id'=>$rec->sub_module_id);
                }
                                 
                $module_data = getTableData('wb_applicationinvoicedata_queries', $data_check,'mis_db');
                
                if($module_data){
                    $data_query = $module_data->data_query;
               
                        $invoice_feessql = DB::select(DB::raw($data_query.' where t1.application_code= '.$application_code));
                       
                        if(is_array($invoice_feessql) && count($invoice_feessql) >0){
                            $invoice_appfeearray = (array)$invoice_feessql[0];
                            $invoice_appfeearray['t1.application_feetype_id'] = $application_feetype_id;
                            //$invoice_appfeearray['fasttrack_option_id'] = $fasttrack_option_id;
                            $quantity = 1;
                            if($fasttrack_option_id == 1){
                              //  $quantity = 2;
            
                            }
							//validate te manufacturng details 
							if($rec->module_id == 1 && ($rec->section_id == 1  || $rec->section_id == 19)){
								$is_manufactureredin_eastafrica = $invoice_appfeearray['is_manufactureredin_eastafrica'];
											$manufacturing_country_id = $invoice_appfeearray['manufacturing_country_id'];
												unset($invoice_appfeearray['manufacturing_country_id']);
											if($is_manufactureredin_eastafrica == 1 && $manufacturing_country_id != 126){
									unset($invoice_appfeearray['t1.is_manufactureredin_eastafrica']);
									$invoice_appfeearray['is_manufactureredin_eastafrica'] = 1;
									unset($invoice_appfeearray['product_category_id']);
									unset($invoice_appfeearray['classification_id']);
									
								}
								else{
									unset($invoice_appfeearray['is_manufactureredin_eastafrica']);
									$invoice_appfeearray['t1.is_manufactureredin_eastafrica'] = 2;
									
								}
							}
                            $fees_datas = DB::connection('mis_db')->table('tra_appmodules_feesconfigurations as t1')
                                                ->join('tra_element_costs as t2', 't1.element_costs_id', 't2.id')
                                                ->join('par_fee_types as t5', 't2.feetype_id', 't5.id')
                                                ->leftJoin('par_cost_categories as t6', 't2.cost_category_id', 't6.id')
                                                ->leftJoin('par_cost_elements as t7', 't2.element_id', 't7.id')
                                                ->join('par_currencies as t8', 't2.currency_id', 't8.id')
                                                ->select(DB::raw("'$tracking_no' as tracking_no,t1.id,'Quotation' as invoice_description,now() as date_of_invoicing, '' as invoice_number, concat(t6.name, '-', t7.name) as element_costs, sum(t2.cost*$quantity * $percentage) as total_invoice_amount,t8.name as currency"))
                                                ->where($invoice_appfeearray)
                                                ->get();
                                        
                                             if ($fees_datas->count() ==0) {
                                                $res = array(
                                                    'success' => false,
                                                    'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                                                );
                                            }else{
                                                foreach($fees_datas as $fees_data){

                                                    $application_feeData[]=  $fees_data;
                                                }
                                                
                                                $res = array('success'=>true);
                                            }
                                        
                        }
                        else{
                            $res = array(
                                'success' => false,
                                'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                            );
                        
                        }
                }
                else{
                    $res = array(
                        'success' => false,
                        'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                    );
                }
               
            }
            if($res['success']){
                $res = array('success'=>true, 'invoice_data'=>$application_feeData);
            }
    return $res;
  }

   public function onGroupedApplicationInvoiceGeneration(Request $req){
    try{
        $applicant_id =  $req->trader_id;
            $application_code = $req->application_code;
            $group_application_code = $req->group_application_code;
            $module_id = $req->module_id;
            $fasttrack_option_id = $req->fasttrack_option_id;
            $trader_id = $req->trader_id;
			$paying_currency_id = $req->paying_currency_id;
            $module_data = getTableData('modules', array('id'=>$module_id),'mis_db');
            $portaltable_name = $module_data->portaltable_name;
            $invoice_type_id = 1;
            $application_feetype_id = 1;
             $submodule_id = $req->submodule_id;
            //check if invoice has been generated
			
            $record = DB::connection('mis_db')->table('tra_groupedapplication_invoices as t1')
                        ->select('t1.*', 't1.id as invoice_id')           
            ->where(array('group_application_code'=>$group_application_code))
                        ->first();
                            
            if($record){
                $res = array(
                    'success' => true,
                    'invoice_data'=>$record,
                    'message' => 'Grouped Application Invoice Already Generated, print to proceed!!'
                );

                return response()->json($res);
                
            }
$percentage = $this->getGroupedApplicationPercentage($group_application_code);
             $records = DB::table($portaltable_name.' as t1')
                            ->join('wb_trader_account as t2', 't1.trader_id','t2.id')
                            ->select('t1.*','t2.id as trader_id', 't2.name as applicant_name', 't2.identification_no','t2.email')
                            ->where('group_application_code',$group_application_code)
                            ->get();
               
            foreach($records as $rec){

                  $applicant_id =  $rec->trader_id;
                  $application_code =  $rec->application_code;
                  $sub_module_id =  $rec->sub_module_id;
                  $section_id =  $rec->section_id;
                  $email =  $rec->email;
                    if($rec->module_id == 1){
                         $data_check = array('module_id'=>$rec->module_id,'section_id'=>$rec->section_id,
                         'sub_module_id'=>$rec->sub_module_id);
                    }else{
                         $data_check = array('module_id'=>$rec->module_id,
                         'sub_module_id'=>$rec->sub_module_id);
                    }
                 
                
                $module_data = getTableData('wb_applicationinvoicedata_queries', $data_check,'mis_db');
                $data_query = $module_data->data_query;
                

                $invoice_feessql = DB::select(DB::raw($data_query.' where t1.application_code= '.$application_code));
                if(is_array($invoice_feessql) && count($invoice_feessql) >0){
                    $invoice_appfeearray = (array)$invoice_feessql[0];
                    $quantity = 1;
                    if($fasttrack_option_id == 1){
                        $quantity = 2;
                    }
                    $invoice_appfeearray['t1.application_feetype_id'] = $application_feetype_id;
                    //validate te manufacturng details 
							if($rec->module_id == 1 && ($rec->section_id == 1  || $rec->section_id == 19)){
								$is_manufactureredin_eastafrica = $invoice_appfeearray['is_manufactureredin_eastafrica'];
											$manufacturing_country_id = $invoice_appfeearray['manufacturing_country_id'];
												unset($invoice_appfeearray['manufacturing_country_id']);
											if($is_manufactureredin_eastafrica == 1 && $manufacturing_country_id != 126){
									unset($invoice_appfeearray['t1.is_manufactureredin_eastafrica']);
									$invoice_appfeearray['is_manufactureredin_eastafrica'] = 1;
									unset($invoice_appfeearray['product_category_id']);
									unset($invoice_appfeearray['classification_id']);
									
								}
								else{
									unset($invoice_appfeearray['is_manufactureredin_eastafrica']);
									$invoice_appfeearray['t1.is_manufactureredin_eastafrica'] = 2;
									
								}
							}
                    $fees_data = DB::connection('mis_db')->table('tra_appmodules_feesconfigurations as t1')
                                ->join('tra_element_costs as t2', 't1.element_costs_id', 't2.id')
                                ->select(DB::raw("t1.element_costs_id,t2.id, t2.*,(cost *$quantity *$percentage)  as costs") )
                                ->where($invoice_appfeearray)
                                ->get();
                  
                    $fees_datasave = $fees_data;
                    $fees_data = (array)$fees_data;
                        
                    if (count($fees_data) ==0) {
                        $res = array(
                            'success' => false,
                            'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                        );
                        return response()->json($res);
                    }

                   $res = $this->saveNormalApplicationInvoice($portaltable_name,$rec,$paying_currency_id,$invoice_type_id,$fasttrack_option_id,$fees_datasave,$group_application_code);
                }
                else{

                    $res = array(
                        'success' => false,
                        'message' => 'The application fees and charges have not been configured, contact the authority for action!!'
                    );
                    return response()->json($res);

                }
            }
            //save the grouped application details 
            if($res['success']){
                $invoice_record = DB::connection('mis_db')
                                        ->table('tra_application_invoices as t1')
                                        ->join('tra_invoice_details as t2', 't1.id', 't2.invoice_id')
                                        ->select(DB::raw("sum(t2.total_element_amount) as total_invoice_amount, t2.currency_id"))
                                        ->where('group_application_code',$group_application_code)
                                        ->first();

                    $group_invoice_no = date('Y').$this->generateRandomString();
                    $grouped_invoicedata = array('group_application_code'=>$group_application_code,
                                            'applicant_id'=>$applicant_id,
                                            'group_invoice_no'=>$group_invoice_no,
                                            'total_invoice_amount'=>$invoice_record->total_invoice_amount,
                                            'date_of_invoicing'=>Carbon::now(),
                                            'module_id'=>$module_id,
                                            'section_id'=>$section_id,
                                            'sub_module_id'=>$sub_module_id,
                                            'currency_id'=>$invoice_record->currency_id,
                                            'created_on'=>Carbon::now()
                                            );
                    $res = insertRecord('tra_groupedapplication_invoices', $grouped_invoicedata, 0,'mis_db');
					if($res['success']){
						$group_invoice_id =$res['record_id'];
						$where = array('group_application_code'=>$group_application_code);
						$data = array('group_invoice_id'=>$group_invoice_id);
						DB::connection('mis_db')
								->table('tra_application_invoices')
								->where($where)
								->update($data);
								
						iremboFuncGroupedInvoiceSubmission($group_invoice_id,$group_invoice_no,$group_application_code);
					}
					
					
            }

    }
    catch (\Exception $exception) {
        $res = array(
            'success' => false,
            'message' => $exception->getMessage()
        );
    } catch (\Throwable $throwable) {
        $res = array(
            'success' => false,
            'message' => $throwable->getMessage()
        );
    }
    return response()->json($res);

}function generateRandomString($length = 5) {
    return strtoupper(substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length));

}
public function onCancelGroupedGeneratedApplicationInvoice(Request $req){
    try{
        $group_application_code = $req->group_application_code;
        
       $trader_id = $req->trader_id;
       $trader_email = $req->trader_email;//invoice_id
                     $cancellation_request = array('group_application_code'=>$group_application_code,
                                      'requested_on'=>Carbon::now(),  
                                      'requested_trader_id'=>$trader_id,  
                                      'reason_for_cancellation'=>'Cancellation Request',  
                                      'remarks'=>'Cancellation Request',  
                                      'created_on'=>Carbon::now()
                                 );
                     $insert_res = insertRecord('tra_invoicecancellation_requests', $cancellation_request, $trader_email, 'mis_db');
					
                    $where_invoice = array('group_application_code'=>$group_application_code);
				
                    $invoice_records = DB::connection('mis_db')->table('tra_application_invoices as t1')
					->select(DB::raw("id, t1.id as invoice_id,invoice_no,prepared_by,tracking_no,reference_no,receipt_no,PayCntrNum,application_id,application_code,module_id,sub_module_id,section_id,date_of_invoicing,fob,invoice_amount,paying_exchange_rate,paying_currency_id"))
                    ->where($where_invoice)
                    ->get();

					//check if there is any ayment made 
                    foreach($invoice_records as $invoice_rec){
                        $where_payment = array('invoice_id'=>$invoice_rec->invoice_id,'application_code'=>$invoice_rec->application_code);
				
                        $payment_record = DB::connection('mis_db')->table('tra_payments')->where($where_payment)->count();
                        if($payment_record >0){
                            $res = array('success'=>false,
                            'message'=>'Payment for the said Invoice has already been effected, cancell payments and then invoices');
                            
                             return \response()->json($res);
                        }
                        
                        $cancelled_invoicedata = convertStdClassObjToArray($invoice_rec);
                        
                        $insert_res = insertRecord('tra_application_invoicescancellation', $cancelled_invoicedata, $trader_email, 'mis_db');
                        
                        $previous_data = getPreviousRecords('tra_application_invoices',$where_invoice, 'mis_db' );
                        
                        $res= deleteRecordNoTransaction('tra_application_invoices', $previous_data, $where_invoice, $trader_email, 'mis_db');
                        //send notificaitons 
                       

                    }
                    $where_groupinvoice = array('group_application_code'=>$group_application_code);
                    $previous_data = getPreviousRecords('tra_groupedapplication_invoices',$where_groupinvoice, 'mis_db' );
                    $res= deleteRecordNoTransaction('tra_groupedapplication_invoices', $previous_data, $where_groupinvoice, $trader_email, 'mis_db');

                    $res = array('success'=>true,'message1'=>$res, 'message'=>'The Invoice Has been cancelled Successfully, regenerate the Proforma Invoice and proceed.');
				
    }
    catch (\Exception $exception) {
        $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
    } catch (\Throwable $throwable) {
        $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
    }
    return response()->json($res, 200);
}
  function getGroupedApGeneratedInvoices($group_application_code){
    $invoice_data = DB::connection('mis_db')->table('tra_application_invoices as t1')
    ->join('tra_invoice_details as t2', 't1.id','t2.invoice_id')
    ->join('par_currencies as t3', 't2.paying_currency_id', 't3.id')
    ->join('tra_element_costs as t4', 't2.element_costs_id', 't4.id')
    ->join('par_fee_types as t5', 't4.feetype_id', 't5.id')
    ->leftJoin('par_cost_categories as t6', 't4.cost_category_id', 't6.id')
    ->leftJoin('par_cost_elements as t7', 't4.element_id', 't7.id')
    ->leftJoin('tra_payments as t8', 't1.id', 't8.invoice_id')
    ->select(DB::raw("t1.id,t1.group_application_code, t1.application_code, t1.id as invoice_id, t1.tracking_no, 'Proforma Invoice' as invoice_description,  t1.invoice_no as invoice_number,t1.tracking_no, t1.date_of_invoicing,concat(t5.name,'-', t6.name, '-', t7.name) as element_costs, (t2.total_element_amount) as total_invoice_amount,t3.name as currency,if(t8.id >0, 'Paid', 'Not Paid') as payment_status,t8.iremboInvoiceNumber,t1.module_id, t1.id as invoice_id,t8.id as payment_id, t8.amount_paid"))
    ->where(array('t1.group_application_code'=>$group_application_code))
    ->get();
    return $invoice_data;
}
public function iremboFuncGroupedInvoiceSubmission(Request $req){
	
	$response = iremboFuncGroupedInvoiceSubmission(3,'20122320171I1KVS',2320171);
	print_r($response);
	
}
public function onSaveNewRegsisteredCompanyLtr(Request $req){

        try{
            $trader_email = $req->trader_email;
            $trader_id = $req->trader_trader_idemail;
            $premises_infor = array('name'=>$req->premises_name,
                        'country_id'=>$req->country_id,
                        'region_id'=>$req->region_id,
                        'district_id'=>$req->district_id,
                        'managing_director_email'=>$req->managing_director_email,
                        'managing_director_telepone'=>$req->managing_director_telepone,
                        'managing_director'=>$req->managing_director,
                        'email'=>$req->email,
                        'postal_address'=>$req->postal_address,
                        'telephone'=>$req->telephone,
                        'physical_address'=>$req->physical_address,
                        'company_registration_no'=>$req->company_registration_no,
                    ); 
                    $premises_infor['created_on'] = Carbon::now();
                     $premises_infor['created_by'] = $trader_email;
                                    
                                $resp = insertRecord('tra_premises', $premises_infor, $trader_email, 'mis_db');
                               
                                $tracking_no= $req->company_registration_no.' - '.rand(0,1000);
                                $premise_id = $resp['record_id'];
                                
                                   
                                    $app_data = array('applicant_id'=>$trader_id,
                                                    'premise_id'=>$premise_id,
                                                    'tracking_no'=>$tracking_no,
                                                    'reference_no'=>$tracking_no,
                                                    'dateadded'=>Carbon::now(),
                                                    'created_by'=>$trader_email,
                                                    'created_on'=>Carbon::now()
                                            );
                                    
                                $resp = insertRecord('tra_rdbbusiness_applications', $app_data, $trader_email, 'mis_db');
                                if($resp['success']){
                                    $record_id = $resp['record_id'];
                                    $res = array('success'=>true, 
                                                'message'=>'Saved Successfully',
                                                'record_id'=>$premise_id);
                                }   
                                else{
                                    $res = array('success'=>false, 
                                    'message'=>$resp['message']);

                                }     
                                            

        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
        }
         return response()->json($res, 200);


 }
 
 public function  getapplicationProductVariationsrequests(Request $req){

    try{
        $data = array();
        $application_code = $req->application_code;

        $records = DB::table('wb_application_variationsdata as t1')
                    ->where(array('application_code'=>$application_code))
                    ->get();
                    foreach ($records as $rec) {
                        $variationsummary_guidelinesconfig_id = $rec->variationsummary_guidelinesconfig_id;
                        $variationsummary_guidelinesconfig = getSingleRecord('tra_variationsummary_guidelinesconfig', array('id'=>$variationsummary_guidelinesconfig_id),'mis_db');
                           
                            $variation_description ='';
                            $variation_subdescription ='';
                            $variation_description_id ='';
                        if($variationsummary_guidelinesconfig){
                            $variation_description = getSingleRecordColValue('par_variation_description', array('id' => $variationsummary_guidelinesconfig->variation_description_id), 'name','mis_db');
                            $variation_subdescription = getSingleRecordColValue('par_variation_subdescription', array('id' => $variationsummary_guidelinesconfig->variation_subdescription_id), 'name','mis_db');
                            $variation_description_id = $variationsummary_guidelinesconfig->variation_description_id;
                        }
                        $type_of_variation = getSingleRecordColValue('par_variation_reportingtypes', array('id' => $rec->variation_type_id), 'name','mis_db');
                       
                        //documents Upload 
                        $variationsummary_guidelinesconfigdata = $this->getOnProductSummaryVariationChanges($variationsummary_guidelinesconfig_id);
                        $variation_data = array('id'=>$rec->id,
                                    'application_code'=>$rec->application_code,
                                    'variation_description'=>$variation_description,
                                    'variation_subdescription'=>$variation_subdescription,
                                    'variation_description_id'=>$variation_description_id,
                                    'present_details'=>$rec->present_details,
                                    'proposed_variation'=>$rec->proposed_variation,
                                    'variation_background_information'=>$rec->variation_background_information,
                                    'type_of_variation'=>$type_of_variation,
                                    'variation_type_id'=>$rec->variation_type_id,
                                    'variationsummary_guidelinesconfig_id'=>$rec->variationsummary_guidelinesconfig_id,
                                    'variationsummary_guidelinesconfig'=>$variationsummary_guidelinesconfigdata
                                
                                );
                            
                        $data[] = $variation_data;


                    }
            $res = array('success'=>true, 
                        'data'=>$data,
                        );
    }
    catch (\Exception $e) {
        $res = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    } catch (\Throwable $throwable) {
        $res = array(
            'success' => false,
            'message' => $throwable->getMessage()
        );
    }
    return response()->json($res);

  }

  public function getOnProductSummaryVariationChanges($variationsummary_guidelinesconfig_id){
    
         
            $table_name = 'tra_variationsummary_guidelinesconfig';
            $results = array();
            $data = DB::connection('mis_db')->table($table_name.' as t1')
                            ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                            ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                            ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                            ->leftJoin('par_variation_reportingtypes as t5', 't1.variation_reportingtype_id', 't5.id')
                            ->leftJoin('par_variation_subdescription as t6', 't1.variation_subdescription_id', 't6.id')
                            ->leftJoin('par_variation_description as t7', 't1.variation_description_id', 't7.id')
                            ->leftJoin('par_variation_categories as t8', 't7.variation_category_id', 't8.id')
                            ->leftJoin('par_variation_subcategories as t9', 't7.variation_subcategory_id', 't9.id')
                            ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')

                            ->select('t1.*', 't1.variation_reportingtype_id as variation_type_id', 't8.name as variation_category','t7.variation_subcategory_id','t7.variation_category_id', 't9.name as variation_subcategory', 't1.id as variationsummary_guidelinesconfig_id', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name','t10.name as product_category', 't5.name as variation_reportingtype', 't6.name as variation_subdescription', 't7.name as variation_description', DB::raw("(SELECT group_concat(concat(code,': ', name)  SEPARATOR '<br/> <br/>') AS variationconditions_detail_id FROM tra_variationconfigconditions_details q left join par_variationconditions_details j on q.variationconditions_detail_id = j.id WHERE variationsummary_guidelinesconfig_id =t1.id) as variationconditions_detailsdata , (SELECT group_concat(concat(code,': ', name) SEPARATOR '<br/> <br/>') AS variationsupporting_datadoc_id  FROM tra_variationconfigsupporting_datadocs k left join par_variationsupporting_datadocs l on k.variationsupporting_datadoc_id =l.id WHERE variationsummary_guidelinesconfig_id =t1.id) as variationsupporting_datadocs"))
                            ->where('t1.id',$variationsummary_guidelinesconfig_id)
                            ->first();
                           
                        return $data   ;      

         
}
}
