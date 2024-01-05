<?php
namespace Modules\PremisesRegistration\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

trait PremisesRegistrationTraits
{
               
    public function funcpremisesApplicationSubmission($application_code,$sub_module_id,$module_id,$request,$view_id,$next_stage){
        $res = array();
        $app_status_id = '';
        
        $status_type_id = $request->input('status_type_id');
        $app_exists = recordExists('tra_premises_applications', array('application_code' => $application_code), 'mis_db');
        
        if (!$app_exists) {
            $is_invoiceprocess = true;
           
            $res = $this->saveInitialPremiseOnlineApplicationDetails($request,$application_code,$sub_module_id,$module_id,$view_id,$next_stage);
        
            
            
        }else{
            //update the details 
            $is_portal_update = 0;
            $res = $this->updatePremiseOnlineApplicationDetailsOnMIS($request,$application_code,$sub_module_id,$module_id,$view_id,$next_stage); 
            
            
            
        }
    
    return   $res ; 

    }
    public function updatePremiseOnlineApplicationDetailsOnMIS($request,$application_code,$sub_module_id,$module_id,$view_id,$next_stage)
    {
       
        $user_id = 0;
        DB::beginTransaction();
        try {
            $qry = DB::table('wb_premises_applications as t1')
                ->where('application_code', $application_code);
            $results = $qry->first();
            if (is_null($results)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting portal application details, consult System Admin!!'
                );
                return $res;
            }
            $portal_application_id = $results->id;
            //MIS results
            $mis_results = DB::connection('mis_db')->table('tra_premises_applications')
                ->where('application_code', $application_code)
                ->first();
            if (is_null($mis_results)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting MIS application details, consult System Admin!!'
                );
                return $res;
            }
            $mis_application_id = $mis_results->id;
            $mis_premise_id = $mis_results->premise_id;
            $ref_no = $mis_results->reference_no;
            //process/workflow details
            $where = array(
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'section_id' => $results->section_id
            );
            $process_details = getTableData('wf_tfdaprocesses', $where,'mis_db');
            if (is_null($process_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting process details, consult System Admin!!'
                );
                return $res;
            }
           
            $tracking_no = $results->tracking_no;
            $sub_module_id = $results->sub_module_id;
            $view_id = $mis_results->view_id;
            $application_code = $results->application_code;
            //applicant details
            $applicant_id = $results->trader_id;
            
            //premise main details
            $premise_details = DB::table('wb_premises')
                ->where('id', $results->premise_id)
                ->first();
            if (is_null($premise_details)) {
                DB::rollBack();
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting premise details, consult System Admin!!'
                );
                return $res;
            }

            $premise_details = convertStdClassObjToArray($premise_details);
           
            $premise_details['altered_by'] = $user_id;
            $premise_details['dola'] = Carbon::now();
            unset($premise_details['id']);
            unset($premise_details['applicant_id']);
            unset($premise_details['trader_id']);
            unset($premise_details['mis_dola']);
            unset($premise_details['mis_altered_by']);
            unset($premise_details['init_premise_id']);
            unset($premise_details['registered_id']);
            DB::connection('mis_db')->table('tra_premises')
                ->where('id', $mis_premise_id)
                ->update($premise_details);
            $premise_id = $mis_premise_id;
            //premise other details...delete insert



            $premise_otherdetails = DB::table('wb_premises_otherdetails')
                ->where('premise_id', $results->premise_id)
                ->select(DB::raw("id as portal_id,$premise_id as premise_id,business_type_id,product_category_id, product_subcategory_id,business_type_detail_id,product_details,manufacturing_activities, $user_id as created_by"))
                ->get();
            $premise_otherdetails = convertStdClassObjToArray($premise_otherdetails);
           $premise_otherdetails = unsetPrimaryIDsInArray($premise_otherdetails, 'id');
            DB::connection('mis_db')->table('tra_premises_otherdetails')
                ->where('premise_id', $mis_premise_id)
                ->delete();
            DB::connection('mis_db')->table('tra_premises_otherdetails')
                ->insert($premise_otherdetails);
            //premise personnel details...delete insert
            
              $premise_personneldetails = DB::table('wb_premises_personnel')
                ->where('premise_id', $results->premise_id)
                ->select(DB::raw("id as portal_id,personnel_name,telephone_no, email_address,$premise_id as premise_id,personnel_id,personnel_identification_no,identification_type_id,position_id,qualification_id,start_date,end_date,status_id,professional_board,$user_id as created_by,
                         registration_no,study_field,institution"))
                ->get();
            $premise_personneldetails = convertStdClassObjToArray($premise_personneldetails);
           $premise_personneldetails = unsetPrimaryIDsInArray($premise_personneldetails, 'id');
            DB::connection('mis_db')->table('tra_premises_personnel')
                ->where('premise_id', $mis_premise_id)
                ->delete();
            DB::connection('mis_db')->table('tra_premises_personnel')
                ->insert($premise_personneldetails);
                
            //Premise propriators

              $premise_propriator = DB::table('wb_premises_proprietors')
                ->where('premise_id', $results->premise_id)
                ->select(DB::raw("name,telephone_no, email_address,$premise_id as premise_id,qualification_id,shares,country_id,$user_id as trader_id,$user_id as created_by"))
                ->get();
            $premise_propriator = convertStdClassObjToArray($premise_propriator);
           $premise_propriator = unsetPrimaryIDsInArray($premise_propriator, 'id');
            DB::connection('mis_db')->table('tra_premises_proprietors')
                ->where('premise_id', $mis_premise_id)
                ->delete();
            DB::connection('mis_db')->table('tra_premises_proprietors')
                ->insert($premise_propriator);

                //nearest Drugshop
              $nearestdrugshopsdetails = DB::table('wb_drugshop_storelocation')
                ->where('premise_id', $results->premise_id)
                ->select(DB::raw("name,street, distance,$premise_id as premise_id,country_id,district_id,region_id,$user_id as created_by"))
                ->get();
            $nearestdrugshopsdetails = convertStdClassObjToArray($nearestdrugshopsdetails);
           $nearestdrugshopsdetails = unsetPrimaryIDsInArray($nearestdrugshopsdetails, 'id');
            DB::connection('mis_db')->table('tra_drugshop_storelocation')
                ->where('premise_id', $mis_premise_id)
                ->delete();
            DB::connection('mis_db')->table('tra_drugshop_storelocation')
                ->insert($nearestdrugshopsdetails);

                    //Nearest pharmacy
              $nearestdrugshopsdetails = DB::table('wb_premises_storelocation')
                ->where('premise_id', $results->premise_id)
                ->select(DB::raw("name,street, distance,$premise_id as premise_id,country_id,district_id,region_id,$user_id as created_by"))
                ->get();
            $nearestdrugshsdetails = convertStdClassObjToArray($nearestdrugshopsdetails);
           $nearestdrugshopsdetails = unsetPrimaryIDsInArray($nearestdrugshopsdetails, 'id');
            DB::connection('mis_db')->table('tra_premises_storelocation')
                ->where('premise_id', $mis_premise_id)
                ->delete();
            DB::connection('mis_db')->table('tra_premises_storelocation')
                ->insert($nearestdrugshopsdetails);

                    //External Store
            $premise_storedetails = DB::table('wb_premises_externalstore')
                ->where('premise_id', $results->premise_id)
                ->select(DB::raw("country_id,region_id, district_id,county_id,sub_county_id,$premise_id as premise_id,street,longitude,latitude,village,physical_address,$user_id as created_by"))
                ->get();
            $premise_storedetails = convertStdClassObjToArray($premise_storedetails);
            $premise_storedetails = unsetPrimaryIDsInArray($premise_storedetails, 'id');
            DB::connection('mis_db')->table('tra_premises_externalstore')
                ->where('premise_id', $mis_premise_id)
                ->delete();
                if(count($premise_storedetails)>0){
                $premise_storedetails = unsetPrimaryIDsInArray($premise_storedetails, 'id');
            DB::connection('mis_db')->table('tra_premises_externalstore')
                ->insert($premise_storedetails);    
            }
            
            
            if ($sub_module_id == 3) {//Alteration
                

                $this->syncPremisesApplicationOnlineVariationRequests($application_code);
            }
            if ($sub_module_id == 4) {//Withdrawal
                // $this->syncApplicationOnlineWithdrawalReasons($application_code);
            }
            //application details
            //$app_status_id = 4;//$app_status->status_id;
            $zone_id = $results->zone_id;
            
            $application_details = array(
                'applicant_id' => $applicant_id,
                'application_code' => $application_code,
                'premise_id' => $premise_id,
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'zone_id' => $results->zone_id,
                'section_id' => $results->section_id,
                'process_id' => $process_details->id,
              //  'workflow_stage_id' => $workflow_stage_id,
              //  'application_status_id' => $app_status_id,
                'portal_id' => $portal_application_id,
                'date_received' => Carbon::now(),
                'received_by' => $user_id,
                'paying_currency_id' => $results->paying_currency_id,
                'is_fast_track' => $results->is_fast_track
            );
            DB::connection('mis_db')->table('tra_premises_applications')
                ->where('id', $mis_application_id)
                ->update($application_details);
            $portal_where = array(
                'id' => $portal_application_id
            );
            
           
            $details = array(
                'application_id' => $mis_application_id,
                'mis_application_id' => $mis_application_id,
                'application_code' => $application_code,
                    'is_fast_track' => $results->is_fast_track,
                'tracking_no' => $tracking_no,
                
                'process_id' => $process_details->id,
                'process_name' => $process_details->name,
                
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'section_id' => $results->section_id,
                'premise_id' => $premise_id,
                'applicant_id' => $applicant_id
            );
            //submissions
            
            DB::commit();
            //send email
            $vars = array(
                '{tracking_no}' => $tracking_no
            );
            
            $res = array(
                'success' => true,
                'details' => $details,
                'message' => 'Application submitted successfully!!'
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
        
        return $res;
        
    }

     public function saveInitialPremiseOnlineApplicationDetails( $request,$application_code,$sub_module_id,$module_id,$view_id,$next_stage )
    {
        DB::beginTransaction();
        try {
            $user_id = 0;
            $qry = DB::table('wb_premises_applications as t1')
                ->where('application_code', $application_code);
            $results = $qry->first();
        
            if (is_null($results)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting portal application details, consult System Admin!!'
                );
                return $res;
            }
            $portal_application_id = $results->id;
            $section_id = $results->section_id;
            //process/workflow details
            $where = array(
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'section_id' => $results->section_id,
                'region_id'=>$results->region_id
            );

            $process_details = getTableData('wf_tfdaprocesses', $where,'mis_db');
            if (is_null($process_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting process details, consult System Admin!!'
                );
                return $res;
            }
           
            //$ref_no = $results->reference_no;
            $tracking_no = $results->tracking_no;
            $sub_module_id = $results->sub_module_id;
            $trader_id = $results->trader_id;
            $applicant_id = $trader_id;
            $application_code = $results->application_code;
           
            $applicant_email = getSingleRecordColValue('wb_trader_account', array('id' => $applicant_id), 'email');
           
            $premise_details = DB::table('wb_premises')
                ->where('id', $results->premise_id)
                ->first();
        
            if (is_null($premise_details)) {
                DB::rollBack();
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting premise details, consult System Admin!!'
                );
                return $res;
            }

            $reg_premise_id = $premise_details->registered_id;
            $premise_details->portal_id = $results->premise_id;
            $premise_details->created_by = $user_id;
            $premise_details = convertStdClassObjToArray($premise_details);

            unset($premise_details['id']);
            unset($premise_details['applicant_id']);
            unset($premise_details['trader_id']);
            unset($premise_details['mis_dola']);
            unset($premise_details['mis_altered_by']);
            $prem_insert = insertRecord('tra_premises', $premise_details, $user_id,'mis_db');
            if ($prem_insert['success'] == false) {
                DB::rollBack();
                return $prem_insert;
            }
            $premise_id = $prem_insert['record_id'];
            //premise other details

                $premise_directorsdetails = DB::table('wb_premises_proprietors')
                ->where('premise_id', $results->premise_id)
                ->get();

                foreach($premise_directorsdetails as $premise_directorsdetail)
                {  
                   $data=get_object_vars($premise_directorsdetail);
                   unset($data['premise_id']);
                   unset($data['id']);
                   $data['premise_id'] = $premise_id;
                   DB::connection('mis_db')->table('tra_premises_proprietors')->insert($data);
                   
                }

                 $premise_personnelsdetails =  DB::table('wb_premises_personnel')
                ->where('premise_id', $results->premise_id)
                ->get();
                foreach($premise_personnelsdetails as $premise_personnelsdetail)
                {  
                   $data=get_object_vars($premise_personnelsdetail);
                   unset($data['premise_id']);
                   unset($data['id']);
                   $data['premise_id'] = $premise_id;
                   DB::connection('mis_db')->table('tra_premises_personnel')->insert($data);
                   
                }

            
                $nearestdrugshopsdetails = DB::table('wb_drugshop_storelocation')
                ->where('premise_id', $results->premise_id)
                ->get();
                foreach($nearestdrugshopsdetails as $nearestdrugshopsdetail)
                {  
                   $data=get_object_vars($nearestdrugshopsdetail);
                   unset($data['premise_id']);
                   unset($data['id']);
                   $data['premise_id'] = $premise_id;
                   DB::connection('mis_db')->table('tra_drugshop_storelocation')->insert($data);
                   
                }

               
                $nearestpremisedetails =  DB::table('wb_premises_storelocation')
                ->where('premise_id', $results->premise_id)
                ->get();
                foreach($nearestpremisedetails as $nearestpremisedetail)
                {  
                   $data=get_object_vars($nearestpremisedetail);
                   unset($data['premise_id']);
                   unset($data['id']);
                   $data['premise_id'] = $premise_id;
                   DB::connection('mis_db')->table('tra_premises_storelocation')->insert($data);
                   
                }
                $other_premisesdetails =  DB::table('wb_other_premises')
                ->where('premise_id', $results->premise_id)
                ->get();
                foreach($other_premisesdetails as $other_premisesdetail)
                {  
                   $data=get_object_vars($other_premisesdetail);
                   unset($data['premise_id']);
                   unset($data['id']);
                   $data['premise_id'] = $premise_id;
                   DB::connection('mis_db')->table('tra_other_premises')->insert($data);
                   
             }
                //External Store

               $premise_storedetails =  DB::table('wb_premises_externalstore')
                ->where('premise_id', $results->premise_id)
                ->get();
                foreach($premise_storedetails as $premise_storedetail)
                {  
                   $data=get_object_vars($premise_storedetail);
                   unset($data['premise_id']);
                   unset($data['id']);
                   $data['premise_id'] = $premise_id;
                   DB::connection('mis_db')->table('tra_premises_externalstore')->insert($data);
                   
             }
            
            if ($sub_module_id == 3 || $sub_module_id == 111 ) {//Alteration
                $this->syncPremisesApplicationOnlineVariationRequests($application_code);
            }
            if ($sub_module_id == 4) {//Withdrawal
                $this->syncApplicationOnlineWithdrawalReasons($application_code);
            }
            //application details
            $app_status_id =1;
            $zone_id = $results->zone_id;
            $application_status = getSingleRecordColValue('par_system_statuses', array('id' => $app_status_id), 'name', 'mis_db');
            $application_details = array(
                //'reference_no' => $ref_no,
                'tracking_no' => $tracking_no,
                'view_id' => $view_id,
                'applicant_id' => $applicant_id,
                'application_code' => $application_code,
                'premise_id' => $premise_id,
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'zone_id' => $results->zone_id,
                'region_id'=>$results->region_id,
                'section_id' => $results->section_id,
                'process_id' => $process_details->id,
                'workflow_stage_id' => $next_stage,
                'application_status_id' => $app_status_id,
                'date_received' => Carbon::now(),
                'received_by' => $user_id,
                'portal_id' => $portal_application_id,
                'investment_capital'=>$results->investment_capital,
                'paying_currency_id' => $results->paying_currency_id,
                'is_fast_track' => $results->is_fast_track
            );
            
            $application_insert = insertRecord('tra_premises_applications', $application_details, $user_id, 'mis_db');
            if ($application_insert['success'] == false) {
                DB::rollBack();
                return $application_insert;
            }
            $mis_application_id = $application_insert['record_id'];
            
            $portal_status_id = 3;
            $rec = DB::connection('mis_db')->table('wf_workflow_transitions as t1')
                                        ->join('wf_workflow_actions as t2', 't1.action_id','t2.id')
                                        ->join('wf_workflow_stages as t3', 't1.stage_id','t3.id')
                                        ->select('portal_status_id')
                                        ->where(array('t1.stage_id'=>$next_stage,'t3.stage_status'=>1) )
                                        ->first();
                                         if($rec){
                                            $portal_status_id = $rec->portal_status_id;
                                        }
    
                     
            $portal_where = array(
                'id' => $portal_application_id
            );
            $portal_params = array(
                'application_status_id' => $portal_status_id 
            );
            DB::table('wb_premises_applications')
            ->where($portal_where)
            ->update($portal_params);
            $details = array(
                'application_id' => $application_insert['record_id'],
                'mis_application_id' => $application_insert['record_id'],
                'application_code' => $application_code,
                 'is_fast_track' => $results->is_fast_track,
                'tracking_no' => $tracking_no,
                'application_status' => $application_status,
                'process_id' => $process_details->id,
                'process_name' => $process_details->name,
                'workflow_stage_id' => $next_stage,
                'application_status_id' => $app_status_id,
               // 'workflow_stage' => $workflow_details->name,
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'section_id' => $results->section_id,
                'premise_id' => $premise_id,
                'applicant_id' => $applicant_id
            );
            
            DB::commit();
            //send email
           
            $res = array(
                'success' => true,
                'details' => $details,
                'message' => 'Application saved successfully in the MIS!!'
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
        
        return $res;
    }public function syncPremisesApplicationOnlineVariationRequests($application_code)
    {
        $user_id = 0;
        //$portal_db = DB::connection('portal_db');
        $variations = DB::table('wb_application_variationsdata')
            ->where('application_code', $application_code)
            ->select(DB::raw("id as portal_id,appuploaded_document_id,premisesvariation_type_id, application_code,variation_type_id,variation_category_id,present_details,proposed_variation,
                        variation_background_information,status_id,$user_id as created_by"))
            ->get();
        $variations = convertStdClassObjToArray($variations);
        DB::connection('mis_db')->table('tra_application_variationsdata')
            ->insert($variations);
            
    }
    
    
}

?>