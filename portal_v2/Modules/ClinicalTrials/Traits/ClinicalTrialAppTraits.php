<?php
namespace Modules\ClinicalTrials\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

trait ClinicalTrialAppTraits
{
	public function funcClinicalTriaApplicationSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage)
    {
       
            $app_exists = recordExists('tra_clinical_trial_applications', array('application_code' => $application_code), 'mis_db');

            if ($app_exists) {//update
             
                 $res = $this->updateClinicalTrialOnlineApplicationDetailsOnMIS($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
                   
            } else {//insertion
              
                $res = $this->saveInitialClinicalTrialOnlineApplicationDetails($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
            }
			
			return $res;
       
    }	
public function funcPoorQualityProductRptSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage)
    {
       
            $app_exists = recordExists('tra_poorqualityproduct_reports', array('application_code' => $application_code), 'mis_db');
            if ($app_exists) {//update
             
                 $res = $this->updatePoorQualityProductRptSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
                   
            } else {//insertion
              
                $res = $this->savePoorQualityProductRptSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage);
            }
		
			return $res;
       
    }	
	function savePoorQualityProductRptSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage){
	
       
        $user_id = 0;
		//process_id
      
        try {
            $qry = DB::table('wb_poorqualityproduct_reports as t1')
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
            $process_details = getTableData('wf_tfdaprocesses', $where, 'mis_db');
            if (is_null($process_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting process details, consult System Admin!!'
                );
                return $res;
            }
			
            $workflow_details = getTableData('wf_workflow_stages', array('id' => $next_stage),'mis_db');
            if (is_null($workflow_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting workflow details, consult System Admin!!'
                );
                return $res;
            }
            $tracking_no = $results->tracking_no;
            $application_code = $results->application_code;
           
			  $application_details = array(
                    'application_code'=>$results->application_code,
                    'section_id'=>$results->section_id,
                    'product_category_id'=>$results->product_category_id,
                    'other_product_category'=>$results->other_product_category,
                    'brand_name'=>$results->brand_name, 'reference_no'=>$results->reference_no,
                    'tracking_no'=>$results->tracking_no,
                    'generic_name'=>$results->generic_name,
                    'batch_no'=>$results->batch_no,
                    'manufacturing_date'=>$results->manufacturing_date,
                    'expiry_date'=>$results->expiry_date,
                    'date_of_receipt'=>$results->date_of_receipt,
                    'name_of_manufacturer'=>$results->name_of_manufacturer,
                    'manufacturerphysical_address'=>$results->manufacturerphysical_address,
                    'dosage_form_id'=>$results->dosage_form_id,
                    'other_product_formulation'=>$results->other_product_formulation,
                    'country_of_origin'=>$results->country_of_origin,
                    'name_of_distributor'=>$results->name_of_distributor,
                    'distributor_physical_address'=>$results->distributor_physical_address,
                    'distributor_region_id'=>$results->distributor_region_id,
                    'distributor_country_id'=>$results->distributor_country_id,
                    'complaint_description'=>$results->complaint_description,
                    'needs_refrigeration'=>$results->needs_refrigeration,
                    'needs_protectionfromlight'=>$results->needs_protectionfromlight,
                    'needs_protectionfrommoisture'=>$results->needs_protectionfrommoisture,
                    'conforms_tostorage_guidelines'=>$results->conforms_tostorage_guidelines,
                    'other_storage_details'=>$results->other_storage_details,
                    'detection_ofpoorquality_id'=>$results->detection_ofpoorquality_id,
                    'other_detection_ofpoorquality'=>$results->other_detection_ofpoorquality,
                    'detections_actionstaken_id'=>$results->detections_actionstaken_id,
                    'otherdetections_actionstakens'=>$results->otherdetections_actionstakens,
                    'has_experiencedadverse_event'=>$results->has_experiencedadverse_event,
                    'reporter_category_id'=>$results->reporter_category_id,
                    'name_of_reporter'=>$results->name_of_reporter,
                    'reporter_qualification'=>$results->reporter_qualification,
                    'reporter_phone_number'=>$results->reporter_phone_number,
                    'health_facility'=>$results->health_facility,
                    'facility_district_id'=>$results->facility_district_id,
                    'facility_region_id'=>$results->facility_region_id,
                    'facility_contact_person'=>$results->facility_contact_person,
                    'facility_contactpersons_details'=>$results->facility_contactpersons_details,
                    'facility_country_id'=>$results->facility_country_id,
                    'reporter_email_address'=>$results->reporter_email_address,
                    'reporter_telephone_no'=>$results->reporter_telephone_no,
                    'submission_comments'=>$results->submission_comments,
                    'reporting_date'=>$results->process_id,
                    'module_id'=>$results->module_id,
                    'sub_module_id'=>$results->sub_module_id,
					'submission_date'=>Carbon::now()

            );
			
            $application_insert = insertRecord('tra_poorqualityproduct_reports', $application_details, $user_id,'mis_db');
			
			
            if ($application_insert['success'] == false) {
                DB::rollBack();
                
                return $application_insert;
            }
            $mis_application_id = $application_insert['record_id'];
              $productdesc_complaints = DB::table('wb_productdesc_complaints')
                    ->select(DB::raw("application_code,productdesc_complaint_id,
                    NOW() as created_on,$user_id as created_by"))
                    ->where('application_code', $results->application_code)
                    ->get();
                $productdesc_complaints = convertStdClassObjToArray($productdesc_complaints);
                //$site_details = unsetPrimaryIDsInArray($site_details);
                DB::connection('mis_db')->table('tra_productdesc_complaints')
                    ->where('application_code', $results->application_code)
                    ->delete();
                DB::connection('mis_db')->table('tra_productdesc_complaints')
                    ->insert($productdesc_complaints);
	
           
            
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
	public function updatePoorQualityProductRptSubmission($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage)
    {
		$app_status_id =3;
        $user_id = 0;
        try {
            $qry = DB::table('wb_poorqualityproduct_reports as t1')
                ->where('application_code', $application_code);
            $results = $qry->first();
            if (is_null($results)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting portal application details, consult System Admin!!'
                );
                return $res;
            }
			$sub_module_id = $results->sub_module_id;
            $portal_application_id = $results->id;
            $application_code = $results->application_code;
            //MIS results
            $mis_results = DB::connection('mis_db')->table('tra_poorqualityproduct_reports')
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
            $view_id = $mis_results->view_id;
            $ref_no = $mis_results->reference_no;
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
			
             $application_details = array(
                    'application_code'=>$results->application_code,
                    'section_id'=>$results->section_id,
                    'product_category_id'=>$results->product_category_id,
                    'reference_no'=>$results->reference_no,
                    'tracking_no'=>$results->tracking_no,
                    'other_product_category'=>$results->other_product_category,
                    'brand_name'=>$results->brand_name,
                    'generic_name'=>$results->generic_name,
                    'batch_no'=>$results->batch_no,
                    'manufacturing_date'=>$results->manufacturing_date,
                    'expiry_date'=>$results->expiry_date,
                    'date_of_receipt'=>$results->date_of_receipt,
                    'name_of_manufacturer'=>$results->name_of_manufacturer,
                    'manufacturerphysical_address'=>$results->manufacturerphysical_address,
                    'dosage_form_id'=>$results->dosage_form_id,
                    'other_product_formulation'=>$results->other_product_formulation,
                    'country_of_origin'=>$results->country_of_origin,
                    'name_of_distributor'=>$results->name_of_distributor,
                    'distributor_physical_address'=>$results->distributor_physical_address,
                    'distributor_region_id'=>$results->distributor_region_id,
                    'distributor_country_id'=>$results->distributor_country_id,
                    'complaint_description'=>$results->complaint_description,
                    'needs_refrigeration'=>$results->needs_refrigeration,
                    'needs_protectionfromlight'=>$results->needs_protectionfromlight,
                    'needs_protectionfrommoisture'=>$results->needs_protectionfrommoisture,
                    'conforms_tostorage_guidelines'=>$results->conforms_tostorage_guidelines,
                    'other_storage_details'=>$results->other_storage_details,
                    'detection_ofpoorquality_id'=>$results->detection_ofpoorquality_id,
                    'other_detection_ofpoorquality'=>$results->other_detection_ofpoorquality,
                    'detections_actionstaken_id'=>$results->detections_actionstaken_id,
                    'otherdetections_actionstakens'=>$results->otherdetections_actionstakens,
                    'has_experiencedadverse_event'=>$results->has_experiencedadverse_event,
                    'reporter_category_id'=>$results->reporter_category_id,
                    'name_of_reporter'=>$results->name_of_reporter,
                    'reporter_qualification'=>$results->reporter_qualification,
                    'reporter_phone_number'=>$results->reporter_phone_number,
                    'health_facility'=>$results->health_facility,
                    'facility_district_id'=>$results->facility_district_id,
                    'facility_region_id'=>$results->facility_region_id,
                    'facility_contact_person'=>$results->facility_contact_person,
                    'facility_contactpersons_details'=>$results->facility_contactpersons_details,
                    'facility_country_id'=>$results->facility_country_id,
                    'reporter_email_address'=>$results->reporter_email_address,
                    'reporter_telephone_no'=>$results->reporter_telephone_no,
                    'submission_comments'=>$results->submission_comments,
                    'reporting_date'=>$results->process_id,
                    'module_id'=>$results->module_id,
                    'sub_module_id'=>$results->sub_module_id

            );
			
            DB::connection('mis_db')->table('tra_poorqualityproduct_reports')
                ->where('application_code', $application_code)
                ->update($application_details);
            
                $productdesc_complaints = DB::table('wb_productdesc_complaints')
                    ->select(DB::raw("application_code,productdesc_complaint_id,
                    NOW() as created_on,$user_id as created_by"))
                    ->where('application_code', $results->application_code)
                    ->get();
                $productdesc_complaints = convertStdClassObjToArray($productdesc_complaints);
                //$site_details = unsetPrimaryIDsInArray($site_details);
                DB::connection('mis_db')->table('tra_productdesc_complaints')
                    ->where('application_code', $results->application_code)
                    ->delete();
                DB::connection('mis_db')->table('tra_productdesc_complaints')
                    ->insert($productdesc_complaints);
	
           
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
	
	public function updateClinicalTrialOnlineApplicationDetailsOnMIS($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage)
    {
		$app_status_id =3;
        $user_id = 0;
        try {
            $qry = DB::table('wb_clinical_trial_applications as t1')
                ->where('application_code', $application_code);
            $results = $qry->first();
            if (is_null($results)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting portal application details, consult System Admin!!'
                );
                return $res;
            }
			$sub_module_id = $results->sub_module_id;
            $portal_application_id = $results->id;
            $application_code = $results->application_code;
            //MIS results
            $mis_results = DB::connection('mis_db')->table('tra_clinical_trial_applications')
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
            $view_id = $mis_results->view_id;
            $ref_no = $mis_results->reference_no;
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
			
            $applicant_id = $results->trader_id;
            $application_details = array(
                'view_id' => $view_id,
                'tracking_no' => $results->tracking_no,
                'applicant_id' => $results->applicant_id,
                'reg_clinical_trial_id' => $results->reg_clinical_trial_id,
                'application_code' => $results->application_code,
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
               //  'zone_id' => $results->zone_id,
                'rec_no' => $results->rec_no,
                'uncst_no' => $results->uncst_no,
                'intervention_duration' => $results->intervention_duration,
                'clincialtrialfields_type_id' => $results->clincialtrialfields_type_id,
                'clincialtrialfunding_source_id' => $results->clincialtrialfunding_source_id,
                'participant_no' => $results->participant_no,
                'enrolled_worldwide_no' => $results->enrolled_worldwide_no,
                'enrolled_uganda_no' => $results->enrolled_uganda_no,
                'sites_no' => $results->sites_no,
                'intended_no' => $results->intended_no,
                //'publication_url' => $results->publication_url,
                'is_clinicaltrialin_uganda' => $results->is_clinicaltrialin_uganda,
                'clinicalin_otheruganda_sites' => $results->clinicalin_otheruganda_sites,
                'data_management_process'=>$results->data_management_process,

                'sample_size'=>$results->sample_size,
                'planned_analyses'=>$results->planned_analyses,
                'analysis_sets'=>$results->analysis_sets,
                'is_clinicaltrialin_othercountry' => $results->is_clinicaltrialin_othercountry,
                'clinicalin_othercountries_sites' => $results->clinicalin_othercountries_sites,
                'first_final_duration' => $results->first_final_duration,
                'screening_period' => $results->screening_period,
                'screening_duration'=>$results->screening_duration,
                'follow_up_period' => $results->follow_up_period,
                'follow_up_duration' => $results->follow_up_duration,
                'intervention_period' => $results->intervention_period,
                'clinicaltrial_identification_no' => $results->clinicaltrial_identification_no,
                'short_study_title' => $results->short_study_title,
                'ctrethics_committee_id' => $results->ctrethics_committee_id,
                'trial_design' => $results->trial_design,
                'clinicaltrialprimary_objective' => $results->clinicaltrialprimary_objective,
                'clinicaltrialsecondary_objective' => $results->clinicaltrialsecondary_objective,
                'exclusion_criteria' => $results->exclusion_criteria,
                'inclusion_criteria' => $results->inclusion_criteria,
                'purpose_of_trial' => $results->purpose_of_trial,
                'clinicaltrial_description' => $results->clinicaltrial_description,
                'primary_endpoints' => $results->primary_endpoints,
                'secondary_endpoints' => $results->secondary_endpoints,
                'study_start_date' => $results->study_start_date,
                'clinicaltrial_registry_id' => $results->clinicaltrial_registry_id,
                'other_study'=>$results->other_study,
                'safety_monitoring_plan'=> $results->safety_monitoring_plan,
                'system_used'=> $results->system_used,
                'action_seriousadverse_event'=> $results->action_seriousadverse_event,
                'safety_monitoring_board'=> $results->safety_monitoring_board,
                'interim_report_date'=> $results->interim_report_date,
                'estimated_due_report_date'=> $results->estimated_due_report_date,
                'tertiary_endpoints'=> $results->tertiary_endpoints,
                'tertiary_objectives'=> $results->tertiary_objectives,
                'section_id' => $results->section_id,
                'process_id' => $process_details->id,
                'primary_pharmacodynamics'=>$results->primary_pharmacodynamics, 
                'secondary_pharmacodynamics'=>$results->secondary_pharmacodynamics,
                'safety_pharmacology'=>$results->safety_pharmacology,
                'pharmacodynamic_drug_interactions'=>$results->pharmacodynamic_drug_interactions, 
                'pharmacokinetics'=>$results->pharmacokinetics,
                'toxicology'=>$results->toxicology, 
                'First_in_human_trials'=>$results->First_in_human_trials,
                'glp_aspects'=>$results->glp_aspects,
                'brief_description' => $results->brief_description,
                'meeting_date' => $results->meeting_date,
                'meeting_time' => $results->meeting_time,
                'meeting_type_id' => $results->meeting_type_id,
                'meeting_invitation_details' => $results->meeting_invitation_details,
                'meeting_venue' => $results->meeting_venue,
                'workflow_stage_id' => $workflow_details->id,
                'application_status_id' => $app_status_id,
                'portal_id' =>$results->id,
                'date_received' => Carbon::now(),
                'sponsor_id' => $results->sponsor_id,
                'investigator_id' => $results->investigator_id,
                'study_title' => $results->study_title,
                'protocol_no' => $results->protocol_no,
                'version_no' => $results->version_no,
                'date_of_protocol' => $results->date_of_protocol,
                'clearance_no' => $results->clearance_no,
                'study_duration' => $results->study_duration,
                'duration_desc' => $results->duration_desc,
                'created_by' => $user_id,
                'paying_currency_id' => $results->paying_currency_id,
                'clinical_prodsection_id' => $results->clinical_prodsection_id,
                'phase_id' => $results->phase_id,
                'is_fast_track'=>$results->is_fast_track,
                'duration_stimate'=>$results->duration_stimate,
                'study_end_date'=>$results->study_end_date,
                'individual_duration'=>$results->individual_duration,
                );             
            DB::connection('mis_db')->table('tra_clinical_trial_applications')
                ->where('application_code', $application_code)
                ->update($application_details);
            //study sites details
            if($sub_module_id == 23){
                //insert the clinical trial reporting details 
                $ctr_reporting_data = DB::table('wb_clinicaltrial_progressreports')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,reporting_start_date,reporting_end_date,clinicalreport_type_id,actualstudy_date,screen_participants,target_sample_size,enrolled_participants,dateof_first_enrollment,number_of_dropouts,number_lost_tofollow_ups,inclusion_criteria,exclusion_criteria,number_of_saes,events_of_medialimportance,protocol_deviations,clinicalstudy_status_id,study_site_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();

                $ctr_reporting_data = convertStdClassObjToArray($ctr_reporting_data);

                DB::connection('mis_db')->table('tra_clinicaltrial_progressreports')
                    ->where('application_id', $mis_application_id)
                    ->delete();
                DB::connection('mis_db')->table('tra_clinicaltrial_progressreports')
                    ->insert($ctr_reporting_data);

            }  else if($sub_module_id===102){
                //insert the clinical trial reporting details 
                $ctr_reporting_data = DB::table('wb_clinicaltrial_saereports')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,reporting_start_date,reporting_end_date,clinicalreport_type_id,actualstudy_date,target_sample_size,enrolled_participants,dateof_first_enrollment,number_of_dropouts,number_lost_tofollow_ups,inclusion_criteria,exclusion_criteria,number_of_saes,events_of_medialimportance,protocol_deviations,clinicalstudy_status_id,study_site_id,
                NOW() as created_on, $user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();

        
                $ctr_reporting_data = convertStdClassObjToArray($ctr_reporting_data);

                DB::connection('mis_db')->table('tra_clinicaltrial_saereports')
                    ->where('application_id', $mis_application_id)
                    ->delete();
                DB::connection('mis_db')->table('tra_clinicaltrial_saereports')
                    ->insert($ctr_reporting_data);

            }else if($sub_module_id==103||$sub_module_id===103){
                //insert the clinical trial reporting details 
                $ctr_reporting_data = DB::table('wb_clinicaltrial_otherreports')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,reporting_start_date,reporting_end_date,clinicalreport_type_id,actualstudy_date,brief_description,clinicalstudy_status_id,study_site_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();

                $ctr_reporting_data = convertStdClassObjToArray($ctr_reporting_data);

                DB::connection('mis_db')->table('tra_clinicaltrial_otherreports')
                    ->where('application_id', $mis_application_id)
                    ->delete();
                DB::connection('mis_db')->table('tra_clinicaltrial_otherreports')
                    ->insert($ctr_reporting_data);

             

            }
            else {
                $site_details = DB::table('wb_clinical_trial_sites')
                    ->select(DB::raw("id as portal_id,$mis_application_id as application_id,study_site_id,
                    NOW() as created_on,$user_id as created_by"))
                    ->where('application_id', $results->id)
                    ->get();
                $site_details = convertStdClassObjToArray($site_details);
                //$site_details = unsetPrimaryIDsInArray($site_details);
                DB::connection('mis_db')->table('clinical_trial_sites')
                    ->where('application_id', $mis_application_id)
                    ->delete();
                DB::connection('mis_db')->table('clinical_trial_sites')
                    ->insert($site_details);

            }
           
            //investigators
            $investigator_details = DB::table('wb_clinical_trial_investigators')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,category_id,investigator_id,
                study_site_id,NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();
            $investigator_details = convertStdClassObjToArray($investigator_details);
            //$investigator_details = unsetPrimaryIDsInArray($investigator_details);
            DB::connection('mis_db')->table('clinical_trial_investigators')
                ->where('application_id', $mis_application_id)
                ->delete();
            DB::connection('mis_db')->table('clinical_trial_investigators')
                ->insert($investigator_details);
           $portal_application_id = $results->id;
           $portal_application_id = $results->id;
		   
		   $this->saveClinicalTrialProductsDetails($portal_application_id,$mis_application_id,$user_id);
           
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
	function saveClinicalTrialProductsDetails($portal_application_id,$mis_application_id,$user_id){
		 $product_details = DB::table('wb_clinical_trial_products')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,product_category_id,brand_name,
                registration_no,registration_date,identification_mark,product_desc,market_location_id,dosage_form_id,country_id,
                common_name_id,manufacturer_id,routes_of_admin_id,product_strength,si_unit_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $portal_application_id)
                ->get();
            $product_details = convertStdClassObjToArray($product_details);
            //$product_details = unsetPrimaryIDsInArray($product_details);
            DB::connection('mis_db')->table('clinical_trial_products')
                ->where('application_id', $mis_application_id)
                ->delete();
            foreach ($product_details as $product_detail) {
                $mis_product_id = DB::connection('mis_db')->table('clinical_trial_products')
                    ->insertGetId($product_detail);

            }
			$product_details = DB::table('wb_clinical_placebaproducts')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,product_category_id,brand_name,
                registration_no,registration_date,identification_mark,product_desc,market_location_id,dosage_form_id,country_id,
                common_name_id,manufacturer_id,routes_of_admin_id,product_strength,si_unit_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id',$portal_application_id)
                ->get();
            $product_details = convertStdClassObjToArray($product_details);
            //$product_details = unsetPrimaryIDsInArray($product_details);
            DB::connection('mis_db')->table('clinical_placebaproducts')
                ->where('application_id', $mis_application_id)
                ->delete();
            foreach ($product_details as $product_detail) {
                $mis_product_id = DB::connection('mis_db')->table('clinical_placebaproducts')
                    ->insertGetId($product_detail);
               
            }
			$product_details = DB::table('wb_clinical_comparatorproducts')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,product_category_id,brand_name,
                registration_no,registration_date,identification_mark,product_desc,market_location_id,dosage_form_id,country_id,
                common_name_id,manufacturer_id,routes_of_admin_id,product_strength,si_unit_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $portal_application_id)
                ->get();
            $product_details = convertStdClassObjToArray($product_details);
			
            //$product_details = unsetPrimaryIDsInArray($product_details);
            DB::connection('mis_db')->table('clinical_comparatorproducts')
                ->where('application_id', $mis_application_id)
                ->delete();
            foreach ($product_details as $product_detail) {
                $mis_product_id = DB::connection('mis_db')->table('clinical_comparatorproducts')
                    ->insertGetId($product_detail);
               
            }
		
		
	}

function saveInitialClinicalTrialOnlineApplicationDetails($application_code,$sub_module_id,$module_id,$req,$view_id,$next_stage)
    {
       
        $user_id = 0;
		//process_id
        $app_status_id = 2;

        try {
            $qry = DB::table('wb_clinical_trial_applications as t1')
                ->where('application_code', $application_code);
            $results = $qry->first();
            if (is_null($results)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting portal application details, consult System Admin!!'
                );
                return $res;
            }
            $portal_application_id =$results->id;
            $sub_module_id = $results->sub_module_id;
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
			
            $workflow_details = getTableData('wf_workflow_stages', array('id' => $next_stage),'mis_db');
            if (is_null($workflow_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while getting workflow details, consult System Admin!!'
                );
                return $res;
            }
            $application_details = array(
                'view_id' => $view_id,
                'tracking_no' => $results->tracking_no,
                'applicant_id' => $results->applicant_id,
                'reg_clinical_trial_id' => $results->reg_clinical_trial_id,
                'application_code' => $results->application_code,
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id,
               //  'zone_id' => $results->zone_id,
                'rec_no' => $results->rec_no,
                'uncst_no' => $results->uncst_no,
                'intervention_duration' => $results->intervention_duration,
                'clincialtrialfields_type_id' => $results->clincialtrialfields_type_id,
                'clincialtrialfunding_source_id' => $results->clincialtrialfunding_source_id,
                'participant_no' => $results->participant_no,
                'enrolled_worldwide_no' => $results->enrolled_worldwide_no,
                'enrolled_uganda_no' => $results->enrolled_uganda_no,
                'sites_no' => $results->sites_no,
                'intended_no' => $results->intended_no,
                //'publication_url' => $results->publication_url,
                'is_clinicaltrialin_uganda' => $results->is_clinicaltrialin_uganda,
                'clinicalin_otheruganda_sites' => $results->clinicalin_otheruganda_sites,
                'data_management_process'=>$results->data_management_process,

                'sample_size'=>$results->sample_size,
                'planned_analyses'=>$results->planned_analyses,
                'analysis_sets'=>$results->analysis_sets,
                'is_clinicaltrialin_othercountry' => $results->is_clinicaltrialin_othercountry,
                'clinicalin_othercountries_sites' => $results->clinicalin_othercountries_sites,
                'first_final_duration' => $results->first_final_duration,
                'screening_period' => $results->screening_period,
                'screening_duration'=>$results->screening_duration,
                'follow_up_period' => $results->follow_up_period,
                'follow_up_duration' => $results->follow_up_duration,
                'intervention_period' => $results->intervention_period,
                'clinicaltrial_identification_no' => $results->clinicaltrial_identification_no,
                'short_study_title' => $results->short_study_title,
                'ctrethics_committee_id' => $results->ctrethics_committee_id,
                'trial_design' => $results->trial_design,
                'clinicaltrialprimary_objective' => $results->clinicaltrialprimary_objective,
                'clinicaltrialsecondary_objective' => $results->clinicaltrialsecondary_objective,
                'exclusion_criteria' => $results->exclusion_criteria,
                'inclusion_criteria' => $results->inclusion_criteria,
                'purpose_of_trial' => $results->purpose_of_trial,
                'clinicaltrial_description' => $results->clinicaltrial_description,
                'primary_endpoints' => $results->primary_endpoints,
                'secondary_endpoints' => $results->secondary_endpoints,
                'study_start_date' => $results->study_start_date,
                'clinicaltrial_registry_id' => $results->clinicaltrial_registry_id,
                'other_study'=>$results->other_study,
                'safety_monitoring_plan'=> $results->safety_monitoring_plan,
                'system_used'=> $results->system_used,
                'action_seriousadverse_event'=> $results->action_seriousadverse_event,
                'safety_monitoring_board'=> $results->safety_monitoring_board,
                'interim_report_date'=> $results->interim_report_date,
                'estimated_due_report_date'=> $results->estimated_due_report_date,
                'tertiary_endpoints'=> $results->tertiary_endpoints,
                'tertiary_objectives'=> $results->tertiary_objectives,
                'section_id' => $results->section_id,
                'process_id' => $process_details->id,
                'primary_pharmacodynamics'=>$results->primary_pharmacodynamics, 
                'secondary_pharmacodynamics'=>$results->secondary_pharmacodynamics,
                'safety_pharmacology'=>$results->safety_pharmacology,
                'pharmacodynamic_drug_interactions'=>$results->pharmacodynamic_drug_interactions, 
                'pharmacokinetics'=>$results->pharmacokinetics,
                'toxicology'=>$results->toxicology, 
                'First_in_human_trials'=>$results->First_in_human_trials,
                'glp_aspects'=>$results->glp_aspects,
                'brief_description' => $results->brief_description,
                'meeting_date' => $results->meeting_date,
                'meeting_time' => $results->meeting_time,
                'meeting_type_id' => $results->meeting_type_id,
                'meeting_invitation_details' => $results->meeting_invitation_details,
                'meeting_venue' => $results->meeting_venue,
                'workflow_stage_id' => $workflow_details->id,
                'application_status_id' => $app_status_id,
                'portal_id' =>$results->id,
                'date_received' => Carbon::now(),
                'sponsor_id' => $results->sponsor_id,
                'investigator_id' => $results->investigator_id,
                'study_title' => $results->study_title,
                'protocol_no' => $results->protocol_no,
                'version_no' => $results->version_no,
                'date_of_protocol' => $results->date_of_protocol,
                'clearance_no' => $results->clearance_no,
                'study_duration' => $results->study_duration,
                'duration_desc' => $results->duration_desc,
                'created_by' => $user_id,
                'paying_currency_id' => $results->paying_currency_id,
                'clinical_prodsection_id' => $results->clinical_prodsection_id,
                'phase_id' => $results->phase_id,
                'is_fast_track'=>$results->is_fast_track,
                'duration_stimate'=>$results->duration_stimate,
                'study_end_date'=>$results->study_end_date,
                'individual_duration'=>$results->individual_duration,

                'reporting_start_date'=>$results->reporting_start_date,
                'reporting_end_date'=>$results->reporting_end_date,
                'clinicalreport_type_id'=>$results->clinicalreport_type_id,
                'actualstudy_date'=>$results->actualstudy_date,
                'screen_participants'=>$results->screen_participants,
                'dateof_first_screening'=>$results->dateof_first_screening,
                'target_sample_size'=>$results->target_sample_size,
                'enrolled_participants'=>$results->enrolled_participants,
                'dateof_first_enrollment'=>$results->dateof_first_enrollment,
                'number_of_dropouts'=>$results->number_of_dropouts,
                'number_lost_tofollow_ups'=>$results->number_lost_tofollow_ups,
                'number_of_saes'=>$results->number_of_saes,
                'events_of_medialimportance'=>$results->events_of
            );             
 

            $application_insert = insertRecord('tra_clinical_trial_applications', $application_details, $user_id,'mis_db');

			
            if ($application_insert['success'] == false) {
                DB::rollBack();
                
                return $application_insert;
            }
            $mis_application_id = $application_insert['record_id'];
            if ($sub_module_id == 10) {
                $reg_params = array(
                    'tra_clinical_trial_id' => $mis_application_id,
                    'registration_status' => 1,
                    'validity_status' => 1,
                    'created_by' => $user_id
                );
                //should apply only to new applications
              //  createInitialRegistrationRecord('registered_clinical_trials', 'tra_clinical_trial_applications', $reg_params, $mis_application_id, 'reg_clinical_trial_id');
            }
            //study sites details
            $site_details = DB::table('wb_clinical_trial_sites')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,study_site_id,approving_instution,responsible_ethics_committee,approval_date,application_reference_no,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();
			
            $site_details = convertStdClassObjToArray($site_details);
				
           // $site_details = unsetPrimaryIDsInArray($site_details);
            DB::connection('mis_db')->table('clinical_trial_sites')
                ->insert($site_details);

                //Staff personnel
            $personnel_details = DB::table('wb_clinical_trial_personnel')
                ->select(DB::raw("$mis_application_id as application_id,personnel_name,email_address,position_id,qualification_id,trader_id,telephone_no,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();
            
            $personnel_details = convertStdClassObjToArray($personnel_details);
                
            DB::connection('mis_db')->table('clinical_trial_personnel')
                ->insert($personnel_details);

            $imp_handling = DB::table('wb_clinicaltrial_producthandling')
                ->select(DB::raw("$mis_application_id as application_id,investigator_id,shipping_delivery_distribution,storage_requirements_arrangements,dispensing_trial_medicines,no_of_units,no_of_packs,common_name_id,container_type_id,container_id,container_material_id,si_unit_id,trader_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();
            
            $imp_handling = convertStdClassObjToArray($imp_handling);
                
            DB::connection('mis_db')->table('tra_clinicaltrial_producthandling')
                ->insert($imp_handling);




                //Toxicology dose
        $toxicology_dose = DB::table('wb_clinicaltrial_toxicitydosage')
                ->select(DB::raw("$mis_application_id as application_id,dosage_type_id,species,dose_route,mntd,major_findings,trader_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();
            
            $toxicology_dose = convertStdClassObjToArray($toxicology_dose);
                
            DB::connection('mis_db')->table('tra_clinicaltrial_toxicitydosage')
                ->insert($toxicology_dose);

            //investigators
            $investigator_details = DB::table('wb_clinical_trial_investigators')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,category_id,investigator_id,
                study_site_id,NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();
            $investigator_details = convertStdClassObjToArray($investigator_details);
         //   $investigator_details = unsetPrimaryIDsInArray($investigator_details);
            DB::connection('mis_db')->table('clinical_trial_investigators')
                ->insert($investigator_details);
            //IMP products section_id
            //monitors
            $monitors_details = DB::table('wb_clinical_trial_monitors')
            ->select(DB::raw("id as portal_id,$mis_application_id as application_id,monitor_id,
            NOW() as created_on,$user_id as created_by"))
            ->where('application_id', $results->id)
            ->get();
            $monitors_details = convertStdClassObjToArray($monitors_details);
         //   $monitors_details = unsetPrimaryIDsInArray($monitors_details);
            DB::connection('mis_db')->table('tra_clinical_trial_monitors')
                ->insert($monitors_details);
            //end monitors

             $this->saveClinicalTrialProductsDetails($portal_application_id,$mis_application_id,$user_id);
           
            
           if($sub_module_id == 23){
                //insert the clinical trial reporting details 
                $ctr_reporting_data = DB::table('wb_clinicaltrial_progressreports')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,reporting_start_date,reporting_end_date,clinicalreport_type_id,actualstudy_date,screen_participants,target_sample_size,enrolled_participants,dateof_first_enrollment,number_of_dropouts,number_lost_tofollow_ups,inclusion_criteria,exclusion_criteria,number_of_saes,events_of_medialimportance,protocol_deviations,clinicalstudy_status_id,study_site_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();

                $ctr_reporting_data = convertStdClassObjToArray($ctr_reporting_data);

                DB::connection('mis_db')->table('tra_clinicaltrial_progressreports')
                    ->where('application_id', $mis_application_id)
                    ->delete();
                DB::connection('mis_db')->table('tra_clinicaltrial_progressreports')
                    ->insert($ctr_reporting_data);

            }   
            if($sub_module_id===102){
                //insert the clinical trial reporting details 
                $ctr_reporting_data = DB::table('wb_clinicaltrial_saereports')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,reporting_start_date,reporting_end_date,clinicalreport_type_id,actualstudy_date,target_sample_size,enrolled_participants,dateof_first_enrollment,number_of_dropouts,number_lost_tofollow_ups,inclusion_criteria,exclusion_criteria,number_of_saes,events_of_medialimportance,protocol_deviations,clinicalstudy_status_id,study_site_id,
                NOW() as created_on, $user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();

        
                $ctr_reporting_data = convertStdClassObjToArray($ctr_reporting_data);

                DB::connection('mis_db')->table('tra_clinicaltrial_saereports')
                    ->where('application_id', $mis_application_id)
                    ->delete();
                DB::connection('mis_db')->table('tra_clinicaltrial_saereports')
                    ->insert($ctr_reporting_data);

            }           
             if($sub_module_id==103||$sub_module_id===103){
                //insert the clinical trial reporting details 
                $ctr_reporting_data = DB::table('wb_clinicaltrial_otherreports')
                ->select(DB::raw("id as portal_id,$mis_application_id as application_id,reporting_start_date,reporting_end_date,clinicalreport_type_id,actualstudy_date,brief_description,clinicalstudy_status_id,study_site_id,
                NOW() as created_on,$user_id as created_by"))
                ->where('application_id', $results->id)
                ->get();

                $ctr_reporting_data = convertStdClassObjToArray($ctr_reporting_data);

                DB::connection('mis_db')->table('tra_clinicaltrial_otherreports')
                    ->where('application_id', $mis_application_id)
                    ->delete();
                DB::connection('mis_db')->table('tra_clinicaltrial_otherreports')
                    ->insert($ctr_reporting_data);

             

            }
            
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