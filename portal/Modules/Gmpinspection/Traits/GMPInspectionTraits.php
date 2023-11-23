<?php
namespace Modules\Gmpinspection\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

trait GmpInspectionsTraits
{
		 public function funcGmpApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage)
    {
       
        
        $res = array();
        $app_status_id = '';
        $is_portalupdate = 0;
        $app_exists = recordExists('tra_gmp_applications', array('application_code' => $application_code), 'mis_db');
        if ($app_exists) {//update
            $res = $this->updateGmpOnlineApplicationDetailsOnMIS($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
			
        } else {//insertion
            
            $res = $this->saveInitialGmpOnlineApplicationDetails($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
			
        }

		DB::commit();
        return $res;
    }		
	public function updateGmpOnlineApplicationDetailsOnMIS($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage)
    {
       
        $user_id = 0;
        DB::beginTransaction();
        try {

            $qry = DB::table('wb_gmp_applications as t1')
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
            $sub_module_id = $results->sub_module_id;
            //MIS results
            $mis_results = DB::table('tra_gmp_applications')
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
            $mis_site_id = $mis_results->manufacturing_site_id;
            $reg_site_id = $mis_results->reg_site_id;
            //process/workflow details
            $where = array(
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'section_id' => $results->section_id
            );
            $process_details = getTableData('wf_tfdaprocesses', $where, 'mis_db');
            if (is_null($process_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting process details, consult System Admin!!'
                );
                return $res;
            }
			
            $tracking_no = $results->tracking_no;
            $view_id = $mis_results->view_id;
            $application_code = $results->application_code;
            
            $applicant_id = $results->trader_id;
				
            $site_details = DB::table('wb_manufacturing_sites')
                ->where('id', $results->manufacturing_site_id)
                ->first();
            if (is_null($site_details)) {
                DB::rollBack();
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting site details, consult System Admin!!'
                );
                return $res;
            }	
            $ltr_id =$results->local_agent_id;;

            $site_details->portal_id = $results->manufacturing_site_id;
            $site_details->applicant_id = $applicant_id;
            $site_details->ltr_id = $ltr_id;
            $site_details->created_by = $this->user_id;
            $site_details = convertStdClassObjToArray($site_details);
            unset($site_details['id']);
            unset($site_details['premise_id']);
            unset($site_details['mis_dola']);
            unset($site_details['mis_altered_by']);

            DB::connection('mis')->table('tra_manufacturing_sites')
                ->where('id', $mis_site_id)
                ->update($site_details);
            $site_id = $mis_site_id;
            //site other details..delete insert
            $site_otherdetails = DB::table('wb_mansite_otherdetails')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,business_type_id,business_type_detail_id,$user_id as created_by"))
                ->get();
            $site_otherdetails = convertStdClassObjToArray($site_otherdetails);
            DB::connection('mis')->table('tra_mansite_otherdetails')
                ->where('manufacturing_site_id', $mis_site_id)
                ->delete();
            DB::connection('mis')->table('tra_mansite_otherdetails')
                ->insert($site_otherdetails);
            //site block details
            $site_blockdetails = DB::table('wb_manufacturingsite_blocks')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,name,activities,$user_id as created_by"))
                ->get();
            $site_blockdetails = convertStdClassObjToArray($site_blockdetails);
            DB::connection('mis')->table('tra_manufacturing_sites_blocks')
                ->where('manufacturing_site_id', $mis_site_id)
                ->delete();
            DB::connection('mis')->table('tra_manufacturing_sites_blocks')
                ->insert($site_blockdetails);
            //site personnel details
            $site_personneldetails = DB::table('wb_manufacturing_sites_personnel')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,personnel_id,position_id,qualification_id,start_date,end_date,status_id,$user_id as created_by,
                         registration_no,study_field_id,institution"))
                ->get();
            $site_personneldetails = convertStdClassObjToArray($site_personneldetails);
            DB::connection('mis')->table('tra_manufacturing_sites_personnel')
                ->where('manufacturing_site_id', $mis_site_id)
                ->delete();
            DB::connection('mis')->table('tra_manufacturing_sites_personnel')
                ->insert($site_personneldetails);
            //product line details
            $site_productdetails = DB::table('wb_gmp_productline_details')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,manufacturingsite_block_id,product_line_id,category_id,prodline_description,$user_id as created_by"))
                ->get();
            foreach ($site_productdetails as $key => $site_productdetail) {
                $site_productdetails[$key]->manufacturingsite_block_id = getSingleRecordColValue('tra_manufacturing_sites_blocks', array('portal_id' => $site_productdetail->manufacturingsite_block_id), 'id');
            }
            $site_productdetails = convertStdClassObjToArray($site_productdetails);
            DB::connection('mis')->table('gmp_productline_details')
                ->where('manufacturing_site_id', $mis_site_id)
                ->delete();
            DB::connection('mis')->table('gmp_productline_details')
                ->insert($site_productdetails);
            //GMP product details
		
            $gmp_productdetails = DB::table('wb_product_gmpinspectiondetails')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,product_id,reg_product_id,$reg_site_id as reg_site_id,gmp_productline_id,
                    $user_id as created_by,NOW() as created_on"))
                ->get();
            foreach ($gmp_productdetails as $key => $gmp_productdetail) {
                $gmp_productdetails[$key]->gmp_productline_id = getSingleRecordColValue('gmp_productline_details', array('portal_id' => $gmp_productdetail->gmp_productline_id), 'id');
            }
            $gmp_productdetails = convertStdClassObjToArray($gmp_productdetails);
            DB::connection('mis')->table('tra_product_gmpinspectiondetails')
                ->where('manufacturing_site_id', $mis_site_id)
                ->delete();
            DB::connection('mis')->table('tra_product_gmpinspectiondetails')
                ->insert($gmp_productdetails);
            if ($sub_module_id == 39) {//Withdrawal
                $this->syncApplicationOnlineWithdrawalReasons($application_code);
            }
            if ($sub_module_id == 40) {//Alteration
                $this->syncApplicationOnlineVariationRequests($application_code);
            }
			
			
            $application_details = array(
                'applicant_id' => $applicant_id,
                'application_code' => $application_code,
                'manufacturing_site_id' => $site_id,
                'gmp_type_id' => $results->gmp_type_id,
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'zone_id' => $results->zone_id,
                'section_id' => $results->section_id,
                'process_id' => $process_details->id,
                'portal_id' => $portal_application_id,
                'date_received' => Carbon::now(),
                'received_by' => $user_id,
                'paying_currency_id' => $results->paying_currency_id,
                'is_fast_track' => $results->is_fast_track
            );
            DB::connection('mis')->table('tra_gmp_applications')
                ->where('id', $mis_application_id)
                ->update($application_details);
			
            DB::commit();
            //send email
            
            $res = array(
                'success' => true,
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

	public function saveInitialGmpOnlineApplicationDetails($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage)
    {
       
        $user_id = 0;
        
        DB::beginTransaction();
        try {
          
            $qry = DB::table('wb_gmp_applications as t1')
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
            $sub_module_id = $results->sub_module_id;
            //process/workflow details
            $where = array(
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'section_id' => $results->section_id
            );
            $process_details = getTableData('wf_tfdaprocesses', $where);
            if (is_null($process_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting process details, consult System Admin!!'
                );
                return $res;
            }
           
            $tracking_no = $results->tracking_no;
            $application_code = $results->application_code;
           
            $applicant_id = $results->trader_id;
           
		   
            $site_details = DB::table('wb_manufacturing_sites')
                ->where('id', $results->manufacturing_site_id)
                ->first();
            if (is_null($site_details)) {
                DB::rollBack();
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting site details, consult System Admin!!'
                );
                return $res;
            }
            $reg_site_id = $site_details->registered_id;
            
            $ltr_id = $results->local_agent_id;

            $site_details->portal_id = $results->manufacturing_site_id;
            $site_details->applicant_id = $applicant_id;
            $site_details->ltr_id = $ltr_id;
            $site_details->created_by = $user_id;
            $site_details = convertStdClassObjToArray($site_details);
            unset($site_details['id']);
            unset($site_details['premise_id']);
            unset($site_details['mis_dola']);
            unset($site_details['mis_altered_by']);
            $site_insert = insertRecord('tra_manufacturing_sites', $site_details, $user_id,'mis_db');
            if ($site_insert['success'] == false) {
                DB::rollBack();
                return $site_insert;
            }
            $site_id = $site_insert['record_id'];
            //site other details
            $site_otherdetails = DB::table('wb_mansite_otherdetails')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,business_type_id,business_type_detail_id,$user_id as created_by"))
                ->get();
            $site_otherdetails = convertStdClassObjToArray($site_otherdetails);
            DB::connection('mis')->table('tra_mansite_otherdetails')
                ->insert($site_otherdetails);
            //site personnel details
            $site_personneldetails = DB::table('wb_manufacturing_sites_personnel')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,personnel_id,position_id,qualification_id,start_date,end_date,status_id,$user_id as created_by,
                         registration_no,study_field_id,institution"))
                ->get();
            $site_personneldetails = convertStdClassObjToArray($site_personneldetails);
            DB::connection('mis')->table('tra_manufacturing_sites_personnel')
                ->insert($site_personneldetails);
            //site block details
            $site_blockdetails = DB::table('wb_manufacturingsite_blocks')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,name,activities,$user_id as created_by"))
                ->get();
            $site_blockdetails = convertStdClassObjToArray($site_blockdetails);
            DB::connection('mis')->table('tra_manufacturing_sites_blocks')
                ->insert($site_blockdetails);
            //product line details
            $site_productdetails = DB::table('wb_gmp_productline_details')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,manufacturingsite_block_id,product_line_id,category_id,prodline_description,$user_id as created_by"))
                ->get();
            foreach ($site_productdetails as $key => $site_productdetail) {
                $site_productdetails[$key]->manufacturingsite_block_id = getSingleRecordColValue('tra_manufacturing_sites_blocks', array('portal_id' => $site_productdetail->manufacturingsite_block_id), 'id');
            }
            $site_productdetails = convertStdClassObjToArray($site_productdetails);
            DB::connection('mis')->table('gmp_productline_details')
                ->insert($site_productdetails);
            //GMP product details
            /* $gmp_productdetails = $portal_db->table('wb_product_gmpinspectiondetails')
                 ->where('manufacturing_site_id', $results->manufacturing_site_id)
                 ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,product_id,reg_product_id,reg_site_id,gmp_productline_id,
                     $user_id as created_by,NOW() as created_on"))
                 ->get();
             foreach ($gmp_productdetails as $key => $gmp_productdetail) {
                 $gmp_productdetails[$key]->gmp_productline_id = getSingleRecordColValue('gmp_productline_details', array('portal_id' => $gmp_productdetail->gmp_productline_id), 'id');
             }
             $gmp_productdetails = convertStdClassObjToArray($gmp_productdetails);
             DB::table('tra_product_gmpinspectiondetails')
                 ->insert($gmp_productdetails);*/

            if ($sub_module_id == 39) {//Withdrawal
                $this->syncApplicationOnlineWithdrawalReasons($application_code);
            }
            if ($sub_module_id == 40) {//Alteration
                $this->syncApplicationOnlineVariationRequests($application_code);
            }
            //application details
           
            $app_status = getApplicationInitialStatus($results->module_id, $results->sub_module_id);
            $app_status_id = $app_status->status_id;
            
            $application_status = getSingleRecordColValue('par_system_statuses', array('id' => $app_status_id), 'name','mis_db');
            $application_details = array(
                'view_id' => $view_id,
                'tracking_no' => $tracking_no,
                'applicant_id' => $applicant_id,
                'application_code' => $application_code,
                'manufacturing_site_id' => $site_id,
                'gmp_type_id' => $results->gmp_type_id,
                'assessment_type_id' => $results->assessment_type_id,
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
                'zone_id' => $results->zone_id,
                'section_id' => $results->section_id,
                'process_id' => $process_details->id,
                'workflow_stage_id' => $next_stage,
                'application_status_id' => $app_status_id,
                'portal_id' => $portal_application_id,
                'date_received' => Carbon::now(),
                'received_by' => $user_id,
                'paying_currency_id' => $results->paying_currency_id,
                'is_fast_track' => $results->is_fast_track
            );
            $application_insert = insertRecord('tra_gmp_applications', $application_details, $user_id,'mis_db');
            if ($application_insert['success'] == false) {
                DB::rollBack();
                return $application_insert;
            }
			
            $mis_application_id = $application_insert['record_id'];
            if ($sub_module_id == 5) {
                $reg_params = array(
                    'tra_site_id' => $site_id,
                    'registration_status_id' => 1,
                    'validity_status_id' => 1,
                    'created_by' => $user_id
                );
                //should apply only to new applications
                $reg_site_id = createInitialRegistrationRecord('registered_manufacturing_sites', 'tra_gmp_applications', $reg_params, $mis_application_id, 'reg_site_id');
            } else {
                DB::connection('mis_db')->table('tra_gmp_applications')
                    ->where('id', $mis_application_id)
                    ->update(array('reg_site_id' => $reg_site_id));
            }
            //GMP product details
            $gmp_productdetails = DB::table('wb_product_gmpinspectiondetails')
                ->where('manufacturing_site_id', $results->manufacturing_site_id)
                ->select(DB::raw("id as portal_id,$site_id as manufacturing_site_id,product_id,reg_product_id,$reg_site_id as reg_site_id,gmp_productline_id,
                    $user_id as created_by,NOW() as created_on"))
                ->get();
            foreach ($gmp_productdetails as $key => $gmp_productdetail) {
                $gmp_productdetails[$key]->gmp_productline_id = getSingleRecordColValue('gmp_productline_details', array('portal_id' => $gmp_productdetail->gmp_productline_id), 'id');
            }
            $gmp_productdetails = convertStdClassObjToArray($gmp_productdetails);
            DB::connection('mis_db')->table('tra_product_gmpinspectiondetails')
                ->insert($gmp_productdetails);
           
            $res = array(
                'success' => true,
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
    }
}
?>