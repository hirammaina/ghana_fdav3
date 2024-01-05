<?php

namespace Modules\PublicAccess\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
 
class PublicAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
     PRIVATE $connection;
    public function __construct(){
         $this->connection = DB::connection('mis_db');

    }
   
    function returnpremisesFilters($rec){
        $whereClauses = array();
        $filter_string = '';
        if($rec->registration_no != ''){
            $whereClauses[] = "t1.premise_reg_no like '%" . ($rec->registration_no) . "%'";

        }
        if($rec->premises_name != ''){
            $whereClauses[] = "t1.name like '%" . ($rec->premises_name) . "%'";
            
        }
        if($rec->registrant){

            $whereClauses[] = "t3.name like '%" . ($rec->registrant) . "%'";
        }
        if (!empty($whereClauses)) {
            $filter_string = implode(' AND ', $whereClauses);
        }
        
        return $filter_string;
    }
	
	
    
    function returnproductssFilters($rec){
        $whereClauses = array();
        $filter_string = '';
        if($rec->registration_no != ''){
            $whereClauses[] = "t4.certificate_no like '%" . ($rec->registration_no) . "%'";

        }
        if($rec->brand_name != ''){
            $whereClauses[] = "t2.brand_name like '%" . ($rec->brand_name) . "%'";
            
        }
        if($rec->market_authorisation_holder){

            $whereClauses[] = "t10.name like '%" . ($rec->market_authorisation_holder) . "%'";
        }if($rec->section_id){

            $whereClauses[] = "t3.section_id like '%" . ($rec->section_id) . "%'";
        }
        if (!empty($whereClauses)) {
            $filter_string = implode(' AND ', $whereClauses);
        }
        
        return $filter_string;
    }
	 function returngmpFilters($rec){
        $whereClauses = array();
        $filter_string = '';
        if($rec->registration_no != '' && $rec->registration_no != null){
            $whereClauses[] = "t7.permit_no like '%" . ($rec->registration_no) . "%'";

        }
        if($rec->premises_name != ''  && $rec->premises_name != null){
            $whereClauses[] = "t2.name like '%" . ($rec->premises_name) . "%'";
            
        }
        if($rec->registrant  && $rec->registrant != null){

            $whereClauses[] = "t6.name like '%" . ($rec->registrant) . "%'";
        }
        if (!empty($whereClauses)) {
            $filter_string = implode(' AND ', $whereClauses);
        }
        
        return $filter_string;
    }
    public function onSearchPublicGmpComplaints(Request $rec){
        try{

            $skip = $rec->skip;
            $take = $rec->take;
             $filter_records = '';
            $filter_records = '';
          //  $qry = $this->connection->table('vw_gmp_complaincedetails as t1');
			$qry = DB::connection('mis_db')->table('tra_gmp_applications as t1')
			->join('tra_manufacturing_sites as t2', 't1.manufacturing_site_id', 't2.id')
			->join('par_countries as t3', 't2.country_id', 't3.id')
			->leftJoin('par_regions as t4', 't2.region_id', 't4.id')
			->leftJoin('par_gmplocation_details as t5', 't1.gmp_type_id', 't5.id')
			->leftJoin('wb_trader_account as t6', 't1.applicant_id', 't6.id')
			->leftJoin('tra_approval_recommendations as t7', 't1.application_code', 't7.application_code')
			->leftJoin('par_districts as t8', 't2.district_id', 't8.id')
			->leftJoin('tra_mansite_otherdetails as t10', 't2.id', 't10.manufacturing_site_id')
			->leftJoin('par_business_type_details as t11', 't11.id', 't10.business_type_detail_id')
			->select(DB::raw("Distinct t2.id, t11.name as business_type_details, t2.postal_address AS postal_address,t2.email AS email,t2.id AS id,t2.physical_address AS physical_address,t2.name AS gmp_facility_name,t8.name AS district_name,t7.permit_no AS certificate_no,t7.approval_date AS date_of_registration,t3.name AS country_name,t4.name AS region_name,t5.name AS gmp_location,t6.name AS registrant_name"))
			->whereIn('decision_id',[1])
			->whereRaw("t7.expiry_date >= now()");
			
			
            if(validateIsNumeric($rec->section_id)){
                $qry->where('t1.section_id',$rec->section_id);
            }
    
            $filter_string = $this->returngmpFilters($rec);
    
            if ($filter_string != '') {
                $qry->whereRAW($filter_string);
            }
    
            $total_rows = $qry->count();
            $data = $qry->get();
			//$data = $qry->groupBy('t2.id')->skip($skip)->take($take)->get();

			//$data = $qry->groupBy('t2.id')->skip($skip)->take($take)->get();

            $res = array('success'=>true, 'data'=>$data, 'totalCount'=>$total_rows);
    
         //   $res = array('success'=>true, 'data'=>$data);
    
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
    public function onSearchPublicRegisteredpremises(Request $rec){
        try{

           $skip = $rec->skip;
           $take = $rec->take;
           $section_id = $rec->section_id;
            $filter_records = '';
            $qry = $this->connection->table('vw_registered_premisesdetails as t1');
    
            if(validateIsNumeric($rec->section_id)){
                $qry->where('t1.section_id',$section_id);
            }
            $extra_params = json_decode($rec->extra_paramsdata);

            $filter_string = $this->returnpremisesFilters($extra_params);
         
            if ($filter_string != '') {
                $qry->whereRAW($filter_string);
            }
            //$data = $qry->get();
            $total_rows = $qry->count();
            $data = $qry->skip($skip)->take($take)->get();

            $res = array('success'=>true, 'data'=>$data, 'totalCount'=>$total_rows);
    
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
public function onSearchPublicRegisteredproducts(Request $rec){
    try{
        $skip = $rec->skip;
           $take = $rec->take;
        $filter_records = '';
	
     //   $qry = $this->connection->table('vw_registered_productsdetails as t1');
        $qry = $this->connection->table('tra_product_information as t2')
                                ->leftJoin('tra_registered_products as t1', 't1.tra_product_id','=','t2.id')
                                ->join('tra_product_applications as t3', 't3.product_id', '=', 't2.id')
                                ->join('tra_approval_recommendations as t4', 't3.application_code', '=','t4.application_code')
                                ->leftJoin('par_common_names as t5', 't2.common_name_id', '=','t5.id')
                                ->leftJoin('par_classifications as t6', 't2.classification_id', '=','t6.id')
                                ->leftJoin('par_validity_statuses as t7', 't1.validity_status_id', '=','t7.id')
                                ->leftJoin('par_registration_statuses as t8', 't1.registration_status_id', '=','t8.id')
                                ->leftJoin('par_sections as t9', 't3.section_id', '=','t9.id')
                                ->leftJoin('wb_trader_account as t10', 't3.applicant_id', '=','t10.id')
                                ->leftJoin('wb_trader_account as t12', 't3.local_agent_id', '=','t12.id')
                                ->leftJoin('par_dosage_forms as t13', 't2.dosage_form_id', '=','t13.id')
                                ->leftJoin('par_countries as t14', 't10.country_id', '=','t14.id')
                                ->leftJoin('tra_product_manufacturers as t15', function ($join) {
                                    $join->on('t15.product_id', '=', 't2.id')
                                        ->where('t15.manufacturer_type_id', '=', 1);
                                })
								->leftJoin('tra_manufacturers_information as t16', 't15.manufacturer_id', '=', 't16.id')
                                ->leftJoin('par_countries as t17', 't16.country_id', '=','t17.id')
                                ->select(DB::raw("DISTINCT ON (t3.product_id) t3.product_id, t16.name as manufacturer, t17.name as manufacturer_country,t10.name as registrant,t12.name as localtechnical_representative,t13.name as dosage_form,(t4.certificate_issue_date) as certificate_issue_date, (t4.expiry_date) as app_expiry_Date, t1.id as reg_product_id,t4.certificate_no,t2.id as product_id,t2.*,t2.brand_name,t14.name as registrant_country ,t5.name as generic_name,t9.name as section_name, t6.name as classification_name, t7.name as validity_status, t1.validity_status_id,t8.name as registration_status"));
								
								$qry->where(array('validity_status_id'=>2, 'registration_status_id'=>2));
								
                $qrycount =  $this->connection->table('tra_product_information as t2')
                                ->leftJoin('tra_registered_products as t1', 't1.tra_product_id','=','t2.id')
                                ->join('tra_product_applications as t3', 't3.product_id', '=', 't2.id')
                                ->join('tra_approval_recommendations as t4', 't3.application_code', '=','t4.application_code')
                                ->leftJoin('par_common_names as t5', 't2.common_name_id', '=','t5.id')
                                ->leftJoin('par_classifications as t6', 't2.classification_id', '=','t6.id')
                                ->leftJoin('par_validity_statuses as t7', 't1.validity_status_id', '=','t7.id')
                                ->leftJoin('par_registration_statuses as t8', 't1.registration_status_id', '=','t8.id')
                                ->leftJoin('par_sections as t9', 't3.section_id', '=','t9.id')
                                ->leftJoin('wb_trader_account as t10', 't3.applicant_id', '=','t10.id')
                                ->leftJoin('wb_trader_account as t12', 't3.local_agent_id', '=','t12.id')
                                ->leftJoin('par_dosage_forms as t13', 't2.dosage_form_id', '=','t13.id')
                                ->leftJoin('par_countries as t14', 't10.country_id', '=','t14.id')
                                ->leftJoin('tra_product_manufacturers as t15', function ($join) {
                                  $join->on('t15.product_id', '=', 't2.id')
                                         ->where('t15.manufacturer_role_id', '=', 1);
                                })
								->leftJoin('tra_manufacturers_information as t16', 't15.manufacturer_id', '=', 't16.id')
                                ->leftJoin('par_countries as t17', 't16.country_id', '=','t17.id')
                                ->select('t3.product_id');
								
			$qrycount->where(array('validity_status_id'=>2, 'registration_status_id'=>2)) ;
			
			if($rec->sub_modulesin != ''){
				$sub_modulesin = explode(',',$rec->sub_modulesin);
				 $qry->whereIn('t3.sub_module_id',$sub_modulesin);
				 $qrycount->whereIn('t3.sub_module_id',$sub_modulesin);
		  
			}
        if(validateIsNumeric($rec->section_id)){
          
		   $qry->where('t3.section_id',$rec->section_id);
		    $qrycount->where('t3.section_id',$rec->section_id);
		  
		   
        } 
        
        $extra_params = json_decode($rec->extra_paramsdata);


       /* $filter_string = returnproductssFilters($extra_params);

        if ($filter_string != '') {
            $qry->whereRAW($filter_string);
			$qrycount->whereRAW($filter_string);
        }
        */
        $total_rows = $qrycount->count();
		
        //$data = $qry->skip($skip)->take($take)->get();
        $data = $qry->get();

        $res = array('success'=>true, 'data'=>$data,'totalCount'=>$total_rows);

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

function returnClinicalTrailFilters($rec){
    $whereClauses = array();
    $filter_string = '';
    if($rec->registration_no != ''){
        $whereClauses[] = "t1.cerificate_no like '%" . ($rec->registration_no) . "%'";

    }
    if($rec->applicant_name != ''){
        $whereClauses[] = "t1.registrant_name like '%" . ($rec->applicant_name) . "%'";
        
    }
    if($rec->registrantcountry_id){

        $whereClauses[] = "t1.registrantcountry_id = '" . ($rec->registrantcountry_id) . "'";
    }
    if($rec->study_title){

        $whereClauses[] = "t1.study_title like '%" . ($rec->registrant) . "%'";
    }
    if($rec->study_site_id){

        $whereClauses[] = "t1.name like '%" . ($rec->registrant) . "%'";
    }
    if($rec->principal_investigator){

        $whereClauses[] = "t1.principal_investigator like '%" . ($rec->principal_investigator) . "%'";
    }
    if($rec->clinical_trial_sponsor){

        $whereClauses[] = "t1.clinical_trialsponsor like '%" . ($rec->clinical_trial_sponsor) . "%'";
    }
    if (!empty($whereClauses)) {
        $filter_string = implode(' AND ', $whereClauses);
    }
    
    return $filter_string;
}
public function onSearchPublicRegisteredclinicaltrials(Request $req){
    try{
        $search_value = $req->search_value;
        $mistrader_id = $req->mistrader_id;
        $sub_module_id = 56;
       // 'applicant_id'=>$mistrader_id,
         $filter_string = '';
		 $whereClauses = array();
       if($req->certificate_no != ''){
        $whereClauses[] = "(t1.tracking_no like '%" . ($req->certificate_no) . "%' or t1.reference_no like '%" . ($req->certificate_no) . "%')";

    }
    if($req->applicant_name != ''){
        $whereClauses[] = "t7.name like '%" . ($req->applicant_name) . "%'";
        
    }
    if(validateisNumeric($req->registrantcountry_id)){

        $whereClauses[] = "t7.country_id = '" . ($req->registrantcountry_id) . "'";
    }
    if($req->public_title !=''){

        $whereClauses[] = "t1.public_title like '%" . ($req->public_title) . "%'";
    }
	 if($req->study_title !=''){

        $whereClauses[] = "t1.study_title like '%" . ($req->study_title) . "%'";
    }
 if($req->purpose_of_trial !=''){

        $whereClauses[] = "t1.purpose_of_trial like '%" . ($req->study_title) . "%'";
    }
if($req->trial_design !=''){

        $whereClauses[] = "t1.trial_design like '%" . ($req->study_title) . "%'";
    }
    if (isset($whereClauses[0])) {
        $filter_string = implode(' AND ', $whereClauses);
    }
$where_statement = array();
       $where_statement= array('sub_module_id'=>$sub_module_id);

       if(validateIsNumeric($mistrader_id)){
            $where_statement['applicant_id'] = $mistrader_id;
       }
	    if(validateIsNumeric($req->phase_id)){
            $where_statement['phase_id'] = $req->phase_id;
       }
	     if(validateIsNumeric($req->recruitment_status_id)){
            $where_statement['recruitment_status_id'] = $req->recruitment_status_id;
       }
        $qry = DB::connection('mis_db')->table('tra_clinical_trial_applications as t1')
                    ->leftJoin('par_clinicaltrial_designs as t2','t1.phase_id','t2.id')
                    ->leftJoin('par_clinical_phases as t3','t1.phase_id','t3.id')
                    ->leftJoin('par_clinical_studypurposes as t4','t1.phase_id','t4.id')
                    ->leftJoin('par_clinical_studypurposes as t5','t1.phase_id','t5.id')
                    ->leftJoin('par_clinical_registrystatuses as t6','t1.phase_id','t6.id')
                    
                    ->leftJoin('wb_trader_account as t7', 't1.applicant_id','t7.id')
                    ->leftJoin('par_countries as t8', 't7.country_id','t8.id')
					->leftJoin('tra_approval_recommendations as t9', 't1.application_code','t9.application_code')
					->leftJoin('tra_clinicaltrial_contactpersons as t10',function($join){
                        $join->on('t10.application_id', '=', 't1.id');

                    })
					
                    ->leftJoin('par_countries as t11', 't10.country_id','t11.id')
                    ->select(DB::raw("DISTINCT t1.id,t1.*,t2.name as trial_design, t10.name as registrant,t7.identification_no,  t3.name as clinical_study_phase,t4.name as purpose_of_trial, t5.name as recruitment_status, t11.name as registrant_country,'Clinical Trial Registered' as application_status, '' as completion_date"))
					->where(function($query) use ($sub_module_id) {
							$query->where('t9.decision_id', '=',1)
							->orWhere('t1.clinical_registrystatus_id', '=',4);
					})
                    ->where($where_statement);
					//
                if ($filter_string != '') {
                      //  $qry->whereRAW($filter_string);
                    }  
                $data = $qry->get();
                    $res =array('success'=>true,'data'=> $data);
                    
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
//other datasets 
public function onSavePoorQualityReportDetails(Request $req){
        try{
            $application_code = $req->application_code;
            $trader_id = '';
            $trader_email = $req->reporter_email_address;
            $section_id = $req->section_id;
            $module_id = $req->module_id;
            $sub_module_id = $req->sub_module_id;
            $productdesc_complaints_id = $req->productdesc_complaints_id;

            $app_data = array(
                    'application_code'=>$req->application_code,
                    'section_id'=>$req->section_id,
                    'product_category_id'=>$req->product_category_id,
                    'other_product_category'=>$req->other_product_category,
                    'brand_name'=>$req->brand_name,
                    'generic_name'=>$req->generic_name,
                    'batch_no'=>$req->batch_no,
                    'manufacturing_date'=>$req->manufacturing_date,
                    'expiry_date'=>$req->expiry_date,
                    'date_of_receipt'=>$req->date_of_receipt,
                    'name_of_manufacturer'=>$req->name_of_manufacturer,
                    'manufacturerphysical_address'=>$req->manufacturerphysical_address,
                    'dosage_form_id'=>$req->dosage_form_id,
                    'other_product_formulation'=>$req->other_product_formulation,
                    'country_of_origin'=>$req->country_of_origin,
                    'name_of_distributor'=>$req->name_of_distributor,
                    'distributor_physical_address'=>$req->distributor_physical_address,
                    'distributor_region_id'=>$req->distributor_region_id,
                    'distributor_country_id'=>$req->distributor_country_id,
                    'complaint_description'=>$req->complaint_description,
                    'needs_refrigeration'=>$req->needs_refrigeration,
                    'needs_protectionfromlight'=>$req->needs_protectionfromlight,
                    'needs_protectionfrommoisture'=>$req->needs_protectionfrommoisture,
                    'conforms_tostorage_guidelines'=>$req->conforms_tostorage_guidelines,
                    'other_storage_details'=>$req->other_storage_details,
                    'detection_ofpoorquality_id'=>$req->detection_ofpoorquality_id,
                    'other_detection_ofpoorquality'=>$req->other_detection_ofpoorquality,
                    'detections_actionstaken_id'=>$req->detections_actionstaken_id,
                    'otherdetections_actionstakens'=>$req->otherdetections_actionstakens,
                    'has_experiencedadverse_event'=>$req->has_experiencedadverse_event,
                    'reporter_category_id'=>$req->reporter_category_id,
                    'name_of_reporter'=>$req->name_of_reporter,
                    'reporter_qualification'=>$req->reporter_qualification,
                    'reporter_phone_number'=>$req->reporter_phone_number,
                    'health_facility'=>$req->health_facility,
                    'facility_district_id'=>$req->facility_district_id,
                    'facility_region_id'=>$req->facility_region_id,
                    'facility_contact_person'=>$req->facility_contact_person,
                    'facility_contactpersons_details'=>$req->facility_contactpersons_details,
                    'facility_country_id'=>$req->facility_country_id,
                    'reporter_email_address'=>$req->reporter_email_address,
                    'reporter_telephone_no'=>$req->reporter_telephone_no,
                    'submission_comments'=>$req->submission_comments,
                    'reporting_date'=>$req->process_id,
                    'module_id'=>$req->module_id,
                    'sub_module_id'=>$req->sub_module_id

            );
            $table_name = 'wb_poorqualityproduct_reports';
            if(validateIsNumeric($application_code)){
                   
                   $where_app = array('application_code'=>$application_code);

                    if (recordExists($table_name, $where_app)) {
                        
                        $app_data['altered_by'] = $trader_email;
                        $app_data['dola'] = Carbon::now();
                       
                        $previous_data = getPreviousRecords($table_name, $where_app);
                        
                        $tracking_no = $previous_data['results'][0]['tracking_no'];
                        $resp =   updateRecord($table_name, $previous_data, $where_app, $app_data, $trader_email);
                       
                }
                
            }
            else{
                $process_id = getSingleRecordColValue('wf_tfdaprocesses',array('module_id'=>$module_id, 'section_id'=>$section_id,'sub_module_id'=>$sub_module_id), 'id','mis_db');
                   
                    $app_data['created_on'] = Carbon::now();
                    
                    $app_data['date_added'] = Carbon::now();
                    $app_data['created_by'] = $trader_email;
                    $app_data['process_id'] = $process_id;
                    
                    $app_data['reporting_date'] = Carbon::now();
                    
                    $apptype_code = getSingleRecordColValue('sub_modules', array('id' => $sub_module_id), 'code','mis_db');
                    
                  
                    $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'module_id' => $module_id, 'reference_type_id' => 1), 'reference_format_id','mis_db');
                    $application_code = generateApplicationCode($sub_module_id, $table_name);
                    $codes_array = array(
                        'apptype_code' => $apptype_code
                    );
                    $tracking_no = generateApplicationRefNumber($ref_id, $codes_array, date('Y'), $process_id, 0, $trader_id);
                    if (!validateIsNumeric($ref_id )) {
                        //return \response()->json(array('success'=>false, 'message'=>'Reference No Format has not been set, contact the system administrator'));
                    }
                    else if( $tracking_no == ''){
                        //return \response()->json(array('success'=>false,'tracking_no'=>$tracking_no, 'message'=>$tracking_no));
                    }
                    $app_data['reference_no'] =   $tracking_no;
                   $app_data['tracking_no'] =   $tracking_no;
                   $app_data['application_status_id'] =   1;
                   $app_data['application_code'] =   $application_code;
                   $app_data['date_added'] =  Carbon::now();
                            
                     $resp = insertRecord($table_name, $app_data, $trader_email);

                                $record_id = $resp['record_id'];
                                $application_id = $record_id;
                               
                   // 'productdesc_complaints_id'=>$req->productdesc_complaints_id,

                        //update the application code_no
            }
            //
            if($resp['success']){
                $productdesc_complaints = array();
						if(is_array($productdesc_complaints_id)){
							foreach($productdesc_complaints_id as $productdesc_complaint_id){
                                            
                                    $productdesc_complaints[] = array('application_code'=>$application_code, 
                                                    'productdesc_complaint_id'=>$productdesc_complaint_id, 
                                                    'created_by'=>$trader_id, 
                                                    'created_on'=>Carbon::now());

                            }
							if(count($productdesc_complaints)){
								 DB::table('wb_productdesc_complaints')->where('application_code',$application_code)->delete();
								  DB::table('wb_productdesc_complaints')->insert($productdesc_complaints);
							}
							
							
						}
                $res = array('tracking_no'=>$tracking_no,
                            'application_id'=>$application_id,
                            'application_code'=>$application_code,
                             'module_id'=>$module_id,
                             'sub_module_id'=>$sub_module_id,
                             'success'=>true,
                             'message'=>'Form Filled & Saved Successfully, with Tracking No: '.$tracking_no);
                            
             }
             else{
                $res = array(
                'success'=>false,
                'message'=>'Error Occurred Clinical trial Application not saved, it this persists contact the system Administrator');
             }


        } catch (\Exception $exception) {
            $res = array(
                'success' => false,'resp'=>$resp,
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
public function onLoadsuspectedProdReportingData(Request $req){
        try{
            $reference_no = $req->reference_no;
            $reporter_email_address = $req->reporter_email_address;
            
            $records = DB::table('wb_poorqualityproduct_reports as t1')
                            ->join('wb_statuses as t2', 't1.application_status_id', 't2.id')
                            ->select('t1.*', 't2.name as application_status');

            if($reference_no != ''){
                    $records->where('reference_no',$reference_no);
            }
            if($reporter_email_address != ''){
                $records->where('reporter_email_address',$reporter_email_address);
            }
            $records = $records->get();

            if($records->count() >0){

                $res = array('success'=>true, 
                        'data'=>$records,
                        'message'=>'record Found'
                );
            }
            else{

                $res = array('success'=>false, 
                            'message'=>'The Is not Report found under the said criteria, try again of contact the system administrator'
                            );
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


public function getImportExpPermitsApplicationLoading(Request $req){
    try{
        $license_no = $req->license_no;
      
        $importexport_name = $req->importexport_name;
      
        $data = array();
        //get the records 
        $records = DB::table('wb_importexport_applications as t1')
            ->select('t1.*','t7.name as action_name','t7.iconCls','t7.action', 't3.name as status', 't3.name as status_name','t4.router_link','t4.name as process_title')
            ->leftJoin('wb_statuses as t3', 't1.application_status_id','=','t3.id')
            ->leftJoin('wb_tfdaprocesses as t4', function ($join) {
                $join->on('t1.sub_module_id', '=', 't4.sub_module_id');
                $join->on('t1.application_status_id', '=', 't4.status_id');
            })
            ->leftJoin('wb_processstatus_actions as t6',function($join){
                $join->on('t1.application_status_id', '=', 't6.status_id')
                     ->on('t6.is_default_action', '=', DB::raw(1));

            })
            ->leftJoin('wb_statuses_actions as t7', 't6.action_id','t7.id')
            ->leftJoin('wb_trader_account as t8', 't1.trader_id','t8.id')
        ->whereIn('t1.application_status_id', [33,39,26])
            ->orderBy('t1.date_added','desc');
            if($license_no != ''){
                    $records->whereRaw("t1.reference_no like '%".$importexport_name."%' or t1.tracking_no like '%".$importexport_name."%'");
            }
            if($importexport_name != ''){
                    $records->whereRaw("t8.name like '%".$importexport_name."%'");
            }
            //the ilters 
            if($license_no != '' or $importexport_name != ''){
                $records = $records->groupBy('t1.application_code')->get();

                $data = $this->getPermitApplications($records);
                if($records->count() >0){

                    $res =array('success'=>true,'data'=> $data);
                }else{

                    $data = array();
                    $res =array('success'=>false,'data'=> $data, 'message'=>'No Imprt/Export Data Found on the search creteria, enter new searh details or contact the authority for support.');
                }
            }
            else{
            $data = array();
                $res =array('success'=>false,'data'=> $data, 'message'=>'Missing search details');

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


public function getDeclaredImpExpApplicationsData(Request $req){
    try{
        $license_no = $req->license_no;
      
        $importexport_name = $req->importexport_name;
        $supplier_name = $req->supplier_name;
        $proforma_invoice_no = $req->proforma_invoice_no;
      
        $data = array();
        //get the records 
        $records = DB::connection('mis_db')->table('tra_importexport_applications as t1')
            ->select('t1.*','t3.name as status', 't3.name as status_name')
            ->leftJoin('par_system_statuses as t3', 't1.application_status_id','=','t3.id')
            ->leftJoin('wf_tfdaprocesses as t4', 't1.process_id','=','t4.id')
            ->leftJoin('wb_trader_account as t8', 't1.applicant_id','t8.id')
            ->whereIn('t1.module_id', [20])
            ->orderBy('t1.date_added','desc');
            if($license_no != ''){
                    $records->whereRaw("t1.reference_no like '%".$importexport_name."%' or t1.tracking_no like '%".$importexport_name."%'");
            }
            if($importexport_name != ''){
                    $records->whereRaw("t8.name like '%".$importexport_name."%'");
            }
            if($supplier_name != ''){
                    $records->whereRaw("t8.name like '%".$supplier_name."%'");
            }
            if($proforma_invoice_no != ''){
                    $records->whereRaw("t1.proforma_invoice_no like '%".$proforma_invoice_no."%'");
            }
            //the ilters 
            if($license_no != '' or $importexport_name != '' or $supplier_name != '' or $proforma_invoice_no != ''){
                $records = $records->groupBy('t1.application_code')->get();

                $data = $this->getDeclaredPermitApplications($records);
                if($records->count() >0){

                    $res =array('success'=>true,'data'=> $data);
                }else{

                    $data = array();
                    $res =array('success'=>false,'data'=> $data, 'message'=>'No Imprt/Export Data Found on the search creteria, enter new searh details or contact the authority for support.');
                }
            }
            else{
            $data = array();
                $res =array('success'=>false,'data'=> $data, 'message'=>'Missing search details');

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

function getDeclaredPermitApplications($records){
        
    $actionColumnData = returnContextMenuActions();
    $data = array();

    $subModuleData = getParameterItems('sub_modules','','mis_db');
    $sectionsData = getParameterItems('par_sections','','mis_db');
    
    $permitCategoryData = getParameterItems('par_permit_category','','mis_db');

    $permitReasonData = getParameterItems('par_permit_reasons','','mis_db');
    
    foreach ($records as $rec) {
       $section = returnParamFromArray($sectionsData,$rec->section_id);
       $premises_name = getSingleRecordColValue('tra_premises', array('id' => $rec->premise_id), 'name','mis_db');
       $sender_receiver = getSingleRecordColValue('tra_permitsenderreceiver_data', array('id' => $rec->sender_receiver_id), 'name','mis_db');
       $currency_name = getSingleRecordColValue('par_currencies', array('id' => $rec->proforma_currency_id), 'name','mis_db');
       
       $data[] = array('reference_no'=>$rec->reference_no,
                       'applicant_id'=>$rec->applicant_id,
                       'supplier_name'=>$rec->supplier_name,
                       'country_of_origin'=>$rec->country_of_origin,
                       'section_id'=>$rec->section_id,
                       'mode_oftransport_id'=>$rec->mode_oftransport_id,
                       'application_id'=>$rec->id,
                       'id'=>$rec->id,
                       'date_added'=>$rec->date_added,
                       'sub_module_id'=>$rec->sub_module_id,
                       'module_id'=>$rec->module_id,
                       'application_status_id'=>$rec->application_status_id,
                       'application_type'=>returnParamFromArray($subModuleData,$rec->sub_module_id).' Application',
                       'section'=>$section,
                       'created_by'=>$rec->created_by,
                       'submission_date'=>$rec->submission_date,
                       'permit_category'=>returnParamFromArray($permitCategoryData,$rec->permit_category_id),
                       'permit_reason'=>returnParamFromArray($permitReasonData,$rec->permit_reason_id),
                       'consignment_value'=>$rec->consignment_value,
                                    'custom_declaration_no'=>$rec->custom_declaration_no,
                       'clearing_agent'=>$rec->clearing_agent,
                       'currency_name'=>$currency_name,
                       'proposed_inspection_date'=>$rec->proposed_inspection_date,
                       'shipment_date'=>$rec->shipment_date,
                       'proforma_currency_id'=>$rec->proforma_currency_id,
                        'permit_category_id'=>$rec->permit_category_id,
                       'product_category_id'=>$rec->product_category_id,
                       'import_typecategory_id'=>$rec->import_typecategory_id,
                       'permit_reason_id'=>$rec->permit_reason_id,
                       'proforma_invoice_no'=>$rec->proforma_invoice_no,
                       'proforma_invoice_date'=>$rec->proforma_invoice_date,
                     
                       'paying_currency_id'=>$rec->paying_currency_id,
                       'sender_receiver_id'=>$rec->sender_receiver_id,
                       'sender_receiver'=>$sender_receiver,
                       'section_name'=>$section,
                       'zone_id'=>$rec->zone_id,
                       'port_id'=>$rec->port_id,
                       'added_by'=>$rec->created_by,
                       'tracking_no'=>$rec->tracking_no,
                       'application_code'=>$rec->application_code,
                       
                       
                   );

    }
    return $data;


}
function getPermitApplications($records){
        
    $actionColumnData = returnContextMenuActions();
    $data = array();

    $subModuleData = getParameterItems('sub_modules','','mis_db');
    $sectionsData = getParameterItems('par_sections','','mis_db');
    
    $permitCategoryData = getParameterItems('par_permit_category','','mis_db');

    $permitReasonData = getParameterItems('par_permit_reasons','','mis_db');
    
    foreach ($records as $rec) {
       $section = returnParamFromArray($sectionsData,$rec->section_id);
       $premises_name = getSingleRecordColValue('tra_premises', array('id' => $rec->premise_id), 'name','mis_db');
       $sender_receiver = getSingleRecordColValue('tra_permitsenderreceiver_data', array('id' => $rec->sender_receiver_id), 'name','mis_db');
       $consignee_name = getSingleRecordColValue('tra_consignee_data', array('id' => $rec->consignee_id), 'name','mis_db');
       
       $data[] = array('reference_no'=>$rec->reference_no,
                       'trader_id'=>$rec->trader_id,
                       'premise_id'=>$rec->premise_id,
                       'section_id'=>$rec->section_id,
                       'mode_oftransport_id'=>$rec->mode_oftransport_id,
                       'application_id'=>$rec->id,
                       'id'=>$rec->id,
                       'date_added'=>$rec->date_added,
                       'sub_module_id'=>$rec->sub_module_id,
                       'module_id'=>$rec->module_id,
                       'mode_oftransport_id'=>$rec->mode_oftransport_id,
                       'application_status_id'=>$rec->application_status_id,
                       'application_type'=>returnParamFromArray($subModuleData,$rec->sub_module_id).' Application',
                       'section'=>$section,
                       'created_by'=>$rec->created_by,
                       'submission_date'=>$rec->submission_date,
                       'permit_category'=>returnParamFromArray($permitCategoryData,$rec->permit_category_id),
                       'permit_reason'=>returnParamFromArray($permitReasonData,$rec->permit_reason_id),
                        'has_registered_outlets'=>$rec->has_registered_outlets,
                        
                                'eligible_importersdoctype_id' => $rec->eligible_importersdoctype_id,
                                'eligible_importerscategory_id' => $rec->eligible_importerscategory_id,
                                'document_upload_id' => $rec->document_upload_id,
                                    'custom_declaration_no'=>$rec->custom_declaration_no,
                       'clearing_agent'=>$rec->clearing_agent,
                       'proposed_inspection_date'=>$rec->proposed_inspection_date,
                       'shipment_date'=>$rec->shipment_date,
                                'reason_fornonregister_outlet'=>$rec->reason_fornonregister_outlet,
                       'proforma_currency_id'=>$rec->proforma_currency_id,
                        'permit_category_id'=>$rec->permit_category_id,
                       'product_category_id'=>$rec->product_category_id,
                       'import_typecategory_id'=>$rec->import_typecategory_id,
                       'permit_reason_id'=>$rec->permit_reason_id,
                       'proforma_invoice_no'=>$rec->proforma_invoice_no,
                       'proforma_invoice_date'=>$rec->proforma_invoice_date,
                       'premise_id'=>$rec->premise_id,
                       'premises_name'=>$premises_name,
                       'paying_currency_id'=>$rec->paying_currency_id,
                       'sender_receiver_id'=>$rec->sender_receiver_id,
                       'sender_receiver'=>$sender_receiver,
                       'section_name'=>$section,
                       'zone_id'=>$rec->zone_id,
                       'port_id'=>$rec->port_id,
                       'consignee_options_id'=>$rec->consignee_options_id,
                       'consignee_id'=>$rec->consignee_id,
                       'consignee_name'=>$consignee_name,
                       'pay_currency_id'=>$rec->pay_currency_id,
                       'added_by'=>$rec->created_by,
                       'tracking_no'=>$rec->tracking_no,
                       'status_name'=>$rec->status_name,
                       'router_link'=>$rec->router_link,
                       'process_title'=>$rec->process_title,
                       'action_name'=>$rec->action_name,
                       'action'=>$rec->action,
                       'iconCls'=>$rec->iconCls,
                       'application_code'=>$rec->application_code,
                       
                       'ordered_by'=>$rec->ordered_by,
                       'qualifications'=>$rec->qualifications,
                       'qualification_license_no'=>$rec->qualification_license_no,
                       
                       'has_apppliedctrdrugs_license'=>$rec->has_apppliedctrdrugs_license,
                       'permit_productscategory_id'=>$rec->permit_productscategory_id,

                       'license_application_code'=>$rec->license_application_code,
                       'controlled_drugslicense_no'=>$rec->controlled_drugslicense_no,

                       'approximate_dateof_arrival'=>$rec->approximate_dateof_arrival,
                       'patients_email_address'=>$rec->patients_email_address,
                       'has_medical_prescription'=>$rec->has_medical_prescription,
                       'patients_fullnames'=>$rec->patients_fullnames,
                       'patients_identification_no'=>$rec->patients_identification_no,
                       'patients_phone_no'=>$rec->patients_phone_no,
                       'patients_physical_address'=>$rec->patients_physical_address,
                       'patientscountry_id'=>$rec->patientscountry_id,
                       'patientsdistrict_id'=>$rec->patientsdistrict_id,
                       'patientsregion_id'=>$rec->patientsregion_id,
                       'hospital_address'=>$rec->hospital_address,
                       'prescribing_doctor'=>$rec->prescribing_doctor,
                       'prescribling_hospital'=>$rec->prescribling_hospital,
                       'prescription_date'=>formatDate($rec->prescription_date),
                       'prescription_no'=>$rec->prescription_no,
                       'reason_for_authorisation'=>$rec->reason_for_authorisation,
                       
                       'contextMenu'=>returnActionColumn($rec->application_status_id,$actionColumnData)
                   );

    }
    return $data;


}
public function saveImportExportApplication(Request $req){
    try {
        $application_id = $req->application_id;
        $trader_id = $req->trader_id;
        $email = $req->email;
        $trader_email = 'Public Function Access';
        $section_id = $req->section_id;
        $module_id = 20;
        $sub_module_id = $req->sub_module_id;
        $proforma_currency_id = $req->proforma_currency_id;

        $tracking_no = $req->tracking_no;
        $zone_id = $req->zone_id;
        $device_type_id = $req->device_type_id;
        
        $application_code = $req->application_code;
        $import_typecategory_id = $req->import_typecategory_id;
        //dms get sub module flder getSubModuleNodereference() 731
        $where_app = array('application_code'=>$application_code);
                        if (!recordExists('tra_application_documentsdefination', $where_app,'mis_db')) {
                        //	initializeApplicationDMS(7, $module_id, $sub_module_id, $application_code, 'Applicatio'.rand(0,1000), '');
                        }
        $process_id = getSingleRecordColValue('wf_tfdaprocesses',array('module_id'=>$module_id, 'section_id'=>$section_id,'sub_module_id'=>$sub_module_id), 'id','mis_db');
        $applicant_data = (object)array('email'=>$req->applicant_email_address, 
                                'name'=>$req->applicant_name,
                                'telephone_no'=>$req->applicant_telephone_no
                                );
        
       $applicant_id =  $this->saveTraderInformationDetails($applicant_data);

        $app_data = array('section_id'=>$req->section_id,
                                'sub_module_id'=>$req->sub_module_id,
                                'module_id'=>$req->module_id,
                                'process_id'=>$process_id,
                                'permit_category_id'=>$req->permit_category_id,
                                'import_typecategory_id'=>$req->import_typecategory_id,
                                'permit_reason_id'=>$req->permit_reason_id,
                                'applicant_id'=>$applicant_id,
                                'producttype_defination_id'=>$req->producttype_defination_id,
                                'mode_oftransport_id'=>$req->mode_oftransport_id,
                                'port_id' => $req->port_id,
                                'proforma_invoice_no'=>$req->proforma_invoice_no,
                                'proforma_invoice_date'=>formatDate($req->proforma_invoice_date),
                                'paying_currency_id'=>$req->paying_currency_id,'proforma_currency_id'=>$req->proforma_currency_id,
                                'supplier_name'=>$req->supplier_name,
                                'country_of_origin'=>$req->country_of_origin,
                                'consignment_value'=>$req->consignment_value
                    );
               
                    /** Already Saved */ 
                    $table_name = 'tra_importexport_applications';
                    $sub_module_id = $req->sub_module_id;
              
                    if(validateIsNumeric($application_id)){
                           
                           $where_app = array('id'=>$application_id);

                            if (recordExists('tra_importexport_applications', $where_app, 'mis_db')) {
                                
                                $app_data['altered_by'] = $trader_email;
                                $app_data['dola'] = Carbon::now();
                               
                                $previous_data = getPreviousRecords('tra_importexport_applications', $where_app, 'mis_db');
                                $reference_no = $previous_data['results'][0]['reference_no'];
                                $application_code = $previous_data['results'][0]['application_code'];
                                
                                $resp =   updateRecord('tra_importexport_applications', $previous_data, $where_app, $app_data, $trader_email, 'mis_db');
                               
                               
                        }
                    }
                    else{
                            $record = '';
                      
                            $app_data['created_on'] = Carbon::now();
                            
                            $app_data['date_added'] = Carbon::now();
                            $app_data['created_by'] = $trader_email;
                            
                            $zone_code = getSingleRecordColValue('par_zones', array('id' => $zone_id), 'zone_code','mis_db');
                            $section_code = getSingleRecordColValue('par_sections', array('id' => $section_id), 'code','mis_db');
                            
                            $apptype_code = getSingleRecordColValue('sub_modules', array('id' => $sub_module_id), 'code','mis_db');
                            
                            $apptype_categorycode = getSingleRecordColValue('par_permit_typecategories', array('id' => $import_typecategory_id), 'code','mis_db');
                            
                            
                            $deviceTypecode = getSingleRecordColValue('par_device_types', array('id' => $device_type_id), 'code','mis_db');
                            $ref_id = 0;
                            
                            if($section_id == 4){
                                   
                                    $codes_array = array(
                                        'section_code' => $section_code,
                                        'zone_code' => $zone_code,
                                        'apptype_code'=>$apptype_code,
                                        'device_typecode'=>$deviceTypecode,
                                        'app_typecategory'=>$apptype_categorycode
                                    );
                           }
                           else{
                                    $codes_array = array(
                                        'section_code' => $section_code,
                                        'zone_code' => $zone_code,
                                        'apptype_code'=>$apptype_code
                                    );
                           }
                         
                            $application_code = generateApplicationCode($sub_module_id, 'tra_importexport_applications', 'mis_db');


                            $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'reference_type_id' => 1), 'reference_format_id','mis_db');
                         
                            $tracking_no = generateApplicationRefNumber($ref_id, $codes_array, date('Y'), $process_id, $zone_id, $trader_id);
                            
                            if (!validateIsNumeric($ref_id )) {
                                return \response()->json(array('success'=>false, 'message'=>'Reference No Format has not been set, contact the system administrator'));
                            }
                            else if( $tracking_no == ''){
                                return \response()->json(array('success'=>false,'tracking_no'=>$tracking_no, 'message'=>$tracking_no));
                            }
                           $app_data['tracking_no'] =   $tracking_no; 
                           $app_data['reference_no'] =   $tracking_no;
                           $app_data['application_status_id'] =   1;
                           $app_data['application_code'] =   $application_code;
                                
                           $resp = insertRecord('tra_importexport_applications', $app_data, $trader_email, 'mis_db');
                          
                           $record_id = $resp['record_id'];
                           
                           $application_id = $record_id;
                           if($resp['success']){
                                //  initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no, $trader_id);
                                  //  saveApplicationSubmissionDetails($application_code,$table_name);  
                            }
                           
                    }
                    if($resp['success']){
                            $res = array('tracking_no'=>$tracking_no,
                                        'application_id'=>$application_id,
                                        'application_code'=>$application_code,
                                        'module_id'=>$module_id,
                                        'sub_module_id'=>$sub_module_id,
                                        'section_id'=>$section_id,
                                        'success'=>true,
                                        'message'=>'Permit Application Saved Successfully, with Tracking No: '.$tracking_no);
                                        

                                      
                     }
                     else{
                            $res = array(
                                'success'=>false,
                                'message'=>'Error Occurred Permit Application not saved, it this persists contact the system Administrator');
                     }
 
                    
    } catch (\Exception $exception) {
        $res = array(
            'success' => false,//'resp'=>$resp,
            'message' => $exception->getMessage()
        );
    } catch (\Throwable $throwable) {
        $res = array(
            'success' => false,
            //'data'=>$resp,
            'message' => $throwable->getMessage()
        );
    }
    
    return response()->json($res);   
}public function generateTraderNo($table_name){
    $trader_no = mt_rand(1000, 99999);
    //check if it exists 
    $where = array('identification_no'=>$trader_no);
    $check = recordExists($table_name, $where);
    if($check){
        return $this->generateTraderNo($table_name);
    }
    else{
        return $trader_no;
    }
}
function saveTraderInformationDetails($rec){
	$data_check = $rec;
    if($rec->email != ''){
        $check = DB::table('wb_trader_account')->where('email',$rec->email)->first();

    }
    else{
        $check = DB::table('wb_trader_account')->where('name',$rec->name)->first();

    }
  
    if(!$check){
        
        $trader_no = $this->generateTraderNo('wb_trader_account');
        
        $uuid = generateUniqID();//unique user ID
        $user_passwordData = str_random(8);
        $user_password = hashPwd($rec->email, $uuid, $user_passwordData);
       // echo $rec->email;
	   
        $user_data =  array('email'=> $rec->email,
                        'password'=>$user_password,
                        'uuid'=>$uuid,
                        'identification_no'=>$trader_no,
                        'telephone_no'=>$rec->telephone_no,
                        'status_id'=>5,//as actve
                        'account_roles_id'=>1,
                        'country_id'=>126,
                        'fullnames'=>$rec->name,
                        'created_by'=>'System',
                        'created_on'=>date('Y-m-d H:i:s')
                );
                $rec = (array)$rec;
                $resp =  insertRecord('wb_trader_account', $rec, 'Migration');
                if($resp['success']){
                    $trader_id = $resp['record_id'];
                }
                else{
                    print_r($resp);
                    exit();
                }
                $rec['id'] =  $trader_id;

                DB::connection('mis_db')->table('wb_trader_account')->insert($rec);
				   if($data_check->email != ''){
					    DB::table('wb_traderauthorised_users')->insert($user_data);

				   }
               
    }
    else{
      $trader_id = $check->id;
    }
   
    return $trader_id;
}
public function onLoadRegulatedServicesCharges(Request $req)
    {
		 $skip = $req->skip;
           $take = $req->take;
        $module_id = $req->module_id;
        try{
            $qry = DB::connection('mis_db')->table('tra_appmodules_feesconfigurations as t1')
                ->leftJoin('modules as t2', 't1.sub_module_id', 't2.id')
                ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                ->leftJoin('par_assessmentprocedure_types as t5', 't1.assessmentprocedure_type_id', 't5.id')
                ->leftJoin('par_prodclass_categories as t6', 't1.prodclass_category_id', 't6.id')
                ->leftJoin('par_product_subcategories as t7', 't1.product_subcategory_id', 't7.id')
                ->leftJoin('par_product_origins as t9', 't1.product_origin_id', 't9.id')
                ->leftJoin('par_applicationfee_types as t10', 't1.application_feetype_id', 't10.id')
                ->leftJoin('par_classifications as t11', 't1.classification_id', 't11.id')
                ->leftJoin('tra_element_costs as t12', 't1.element_costs_id', 't12.id')
                ->leftJoin('par_currencies as t14', 't12.currency_id', 't14.id')
                ->leftJoin('par_fee_types as t15', 't12.feetype_id', 't15.id')
                ->leftJoin('par_cost_categories as t16', 't12.cost_category_id', 't16.id')
                ->leftJoin('par_cost_sub_categories as t17', 't12.sub_cat_id', 't17.id')
                ->leftJoin('par_cost_elements as t18', 't12.element_id', 't18.id')
                ->leftJoin('par_business_types as t20', 't1.business_type_id', 't20.id')
                ->leftJoin('par_gmplocation_details as t21', 't1.gmp_type_id', 't21.id')
                ->leftJoin('par_permitsproduct_categories as t22', 't1.permit_productscategory_id', 't22.id')
                ->leftJoin('par_advertisement_types as t23', 't1.advertisement_type_id', 't23.id')
                ->leftJoin('par_investigationprod_classifications as t24', 't1.investigationprod_classification_id', 't24.id')
                ->leftJoin('par_product_categories as t25', 't1.product_category_id', 't25.id')
                ->leftJoin('par_clincialtrialfunding_sources as t26', 't1.clincialtrialfunding_source_id', 't26.id')
                ->leftJoin('par_clincialtrialfields_types as t27', 't1.clincialtrialfields_type_id', 't27.id')
                ->leftJoin('par_sections as t28', 't1.section_id', 't28.id')
                ->leftJoin('par_permit_category as t29', 't1.permit_category_id', 't29.id')
              
                ->select('t12.*', 't2.name as module','t29.name as permit_use', 't25.name as product_category','t27.name as fields_types', 't26.name as clinical_trial_fundingsource', 't20.name as business_type', 't3.name as sub_process', 't4.name as section_name','t4.name as section','t12.cost as element_cost',
				't5.name as assessment_proceduretype', 't6.name as prodclass_category','t24.name as investigationprod_classification', 't18.name as cost_element', 't7.name as product_subcategory', 't9.name as product_origin', 't10.name as applicationfeetype', 't1.*', 't11.name as classification_name','t15.name as fee_types','t16.name as cost_category','t17.name as cost_subcategory', DB::raw("CONCAT(t12.cost,t14.name) as element_cost"),'t20.name as premise_type', 't21.name as gmp_type','t23.name as advertisement_type', 't22.name as importexport_permittype');
            if(validateIsNumeric($module_id)){
                $qry->where('t1.module_id', $module_id);
            }
			 $extra_params = json_decode($req->extra_paramsdata);
			 $sub_module_id = $extra_params->sub_module_id;
			 $section_id = $extra_params->section_id;
			 $regulated_producttype_id = $extra_params->regulated_producttype_id;
			 if(validateIsNumeric($sub_module_id)){
                $qry->where('t1.sub_module_id', $sub_module_id);
            }
			 if(validateIsNumeric($section_id)){
                $qry->where('t1.section_id', $section_id);
            }
			  if(validateIsNumeric($regulated_producttype_id)){
                $qry->where('t28.regulated_producttype_id', $regulated_producttype_id);
            }
			
			$total_rows = $qry->count();
			if(validateIsNumeric($skip)){
				$qry->skip($skip)->take($take);
			}
			if(validateIsNumeric($skip)){
				$qry->skip($skip)->take($take);
			}
			$data = $qry->get();
			
			$res = array('success'=>true, 'data'=>$data,'totalCount'=>$total_rows);
          
          
        }
        catch (\Exception $exception) {
                $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), 0);
            } 
        catch (\Throwable $throwable) {
                   $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), 0);
            }
        return response()->json($res);
		
    }
	
}
