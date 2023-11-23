<?php

namespace App\Modules\SampleAnalysis\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SampleAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function __construct(Request $req)
    {
        $is_mobile = $req->input('is_mobile');
        if (is_numeric($is_mobile) && $is_mobile > 0) {
            $this->user_id = $req->input('user_id');
        } else {
            $this->middleware(function ($request, $next) {
                if (!\Auth::check()) {
                    $res = array(
                        'success' => false,
                        'message' => '<p>NO SESSION, SERVICE NOT ALLOWED!!<br>PLEASE RELOAD THE SYSTEM!!</p>'
                    );
                    echo json_encode($res);
                    exit();
                }
                $this->user_id = \Auth::user()->id;
                $this->first_name = \Auth::user()->first_name;
                $this->last_name = \Auth::user()->last_name;
                $this->user_fullnames = aes_decrypt($this->first_name) . ' ' . aes_decrypt($this->last_name);

                return $next($request);
            });
        }
    }
    public function updateSampleAnalysisCode(Request $req){
            $records = DB::statement("SELECT sample_code FROM tra_surveillance_sample_details t1 INNER JOIN tra_sampleanalysis_requests t2 ON t1.id = t2.missample_id");
            foreach($records as $rec){
                    $data = array('code_ref_no'=>$rec->sample_code);
                    $where = array('sample_id'=>$rec->limssample_id);
                    DB::connection('lims_db')->table('sample_applications')->where($where)->update($data);
            }
            echo "records Updatd successfully";
    }
    //sample analysis rquests
    public function getsampleanalysistestrequests(Request $req)
    {
        try {
            $application_code = $req->input('application_code');
            $sample_application_code = $req->input('sample_application_code');
            if (isset($sample_application_code) && is_numeric($sample_application_code)) {
                $sample_app_code = $sample_application_code;
            } else {
                $sample_app_code = $application_code;
            }
            $section_id = $req->input('section_id');
            $module_id = $req->input('module_id');
            $data = array();
            $qry = DB::table('tra_sampleanalysis_requests as t1')
                ->join('par_sampleanalysis_status as t2', 't1.status_id', '=', 't2.id')
                ->join('users as t3', 't1.requested_by', '=', 't3.id')
                ->leftJoin('par_survsample_analysis_types as t4', 't1.analysis_type_id', '=', 't4.id')
                ->select(DB::raw("t1.*, t2.name as sample_analysis_status,CONCAT_WS(' ',decrypt(t3.first_name),decrypt(t3.last_name)) as request_by,t4.name as analysis_type"))
                ->where(array('application_code' => $sample_app_code));
            $results = $qry->get();
            
            foreach ($results as $rec) {
                $sample_analysis_status = $rec->sample_analysis_status;
                $requeststatus_id = $rec->status_id;
                $limssample_id = $rec->limssample_id;
                $application_code = $rec->application_code;
                $misproduct_id = $rec->misproduct_id;
                $limsreference_no = $rec->limsreference_no;
                $status_id = $rec->status_id;
                $requested_on = $rec->requested_on;
                $request_by = $rec->request_by;
                $sample_received_on = !empty($rec->sample_received_on) ? $rec->sample_received_on : 'N/A';
                $sample_testapproval_date = !empty($rec->sample_testapproval_date) ? $rec->sample_testapproval_date : 'N/A';

                $record = DB::table('tra_sample_applications as t1')
                    ->join('tra_samples_details as t2', 't1.sample_id', '=', 't2.id')
                    ->select(DB::raw("'$application_code' as application_code,'$misproduct_id' as misproduct_id, '$status_id' as status_id, '$requested_on' as requested_on,'$sample_analysis_status' as sample_analysis_status, '$sample_testapproval_date' as sample_testapproval_date, '$sample_received_on' as sample_received_on,'$requeststatus_id' as requeststatus_id, '$request_by' as request_by, 
                       t1.*, t2.*, t2.dosage_form as dosage_form_id,t2.product_form as product_form_id,t2.device_type_id,t2.classification_id as classification_id, t1.reference_no as laboratory_reference_no, t1.sample_id as limssample_id"))
                    ->where(array('t1.sample_id' => $limssample_id))
                    ->first();
                   
                if (!is_null($record)) {
                    $record->analysis_type_id = $rec->analysis_type_id;
                    $record->analysis_type = $rec->analysis_type;
                    $record->tracking_status_id = $rec->status_id;
                    $data[] = $record;
                }
            }
            $res = array(
                'success' => true,
                'results' => $data,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function getCostSubCategoryParameter(Request $req)
    {
        try {
            $filters = $req->filters;
            $cost_category_id = $req->cost_category_id;
            $connection = DB::connection('lims_db');
            $table_name = $req->table_name . ' as t1';
            $qry = $connection->table($table_name)
                ->select('t1.*')
                ->where(array('cost_category_id' => $cost_category_id));
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function getSampleAnalyisParameter(Request $req)
    {
        try {
            $filters = $req->filters;
            $has_filter = $req->has_filter;
            $connection = DB::connection('lims_db');
            if ($has_filter == 1) {
                $table_name = $req->table_name . ' as t1';
                $qry = $connection->table($table_name)
                    ->leftJoin('sections as t2', 't1.section_id', '=', 't2.id')
                    ->select('t1.*', 't2.name as section_name');
                if ($filters != '') {
                    $filters = (array)json_decode($filters);
                    $results = $qry->where($filters);
                }
            } else {
                $table_name = $req->table_name . ' as t1';
                $qry = $connection->table($table_name)
                    ->select('t1.*');
            }
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    //function
    public function saveSampleAnalysisRequestdetails(Request $req)
    {
        try {
            $user_id = $this->user_id;
            $limssample_id = $req->input('limssample_id');
            $missample_id = $req->input('mis_sample_id');
            $analysis_type_id = $req->input('analysis_type_id');
            $module_id = $req->input('module_id');
            $applicant_id = $req->input('applicant_id');
            $laboratory_id = $req->input('laboratory_id');
            $application_code = $req->input('application_code');
            $sample_application_code = $req->input('sample_application_code');
            $workflow_stage_id = $req->input('workflow_stage_id');
            $misproduct_id = $req->input('misproduct_id');
            $mis_process_id = $req->input('mis_process_id');
            $applications_table = 'tra_sample_applications';
            $samples_table = 'tra_samples_details';
           $sample_app_code = $application_code;
            
            $sample_data = array(
                'device_type_id' => $req->input('device_type_id'),
                'sealpackcondition_id' => 1,
                'brand_name' => $req->input('brand_name'),
                'product_form' => $req->input('product_form_id'),
                'dosage_form' => $req->input('dosage_form_id'),
                'classification_id' => $req->input('classification_id'),
                'common_name' => $req->input('common_name'),
                'batchno' => $req->input('batchno'),
                'expirydate' => $req->input('expirydate'),
                'manufacturedate' => $req->input('manufacturedate'),
                'section_id' => $req->input('section_id'),
                'pack_size' => $req->input('pack_size'),
                'pack_unit_id' => $req->input('pack_unit_id'),
                'quantity' => $req->input('quantity'),
                'manufacturer_id' => $req->input('manufacturer_id'),
                'quantity_unit_id' => $req->input('quantity_unit_id'),
                'product_desc' => '',
                'product_strength' => $req->input('product_strength'),
                'productstrength_unit' => $req->input('productstrength_unit')
            );
            //save applicant to LIMS system 
            
            $zone_id = 2;
            
            $app_data = array(
                'payment_mode_id' => $req->payment_mode_id,
                'requested_by' => strtoupper($req->user_name),
                'mis_process_id' => $mis_process_id,
                'can_subcontract' => $req->can_subcontract,
                'code_ref_no' => $req->code_ref_no,
                'reason_for_analysis' => $req->reason_for_analysis,
                'other_analysis_reason' => $req->other_analysis_reason,
                'submission_date' => $req->submission_date,
                'reference_no' => $req->reference_no,
                'contact_person' => $this->user_fullnames,
                'section_id' => $req->section_id,
                'sample_category_id' => $req->sample_category_id,
                'zone_id' => $zone_id,
                'applicant_id' => $applicant_id,
                'sample_purpose' => $req->sample_purpose,
                'applicationtype_id' => 1
            );
            $sub_module_id = 64;
            $app_data['module_id'] = 19;
            $app_data['sub_module_id'] = 64;

            if ($laboratory_id < 1) {
                $laboratory_id = 1;
            }
            if (validateIsNumeric($limssample_id)) {
                //update the details
                
                $where_app = array('sample_id' => $limssample_id);
                $app_details = array();
                if (recordExists($applications_table, $where_app, '')) {
                    $previous_data = getPreviousRecords($applications_table, $where_app);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    updateRecord($applications_table, $previous_data, $where_app, $app_details, $user_id, '');
                }
                $reference_no = $previous_data[0]['reference_no'];
                $where_sample = array(
                    'id' => $limssample_id
                );
                $sample_data['dola'] = Carbon::now();
                $sample_data['altered_by'] = $user_id;
                $previous_data = getPreviousRecords($samples_table, $where_sample, '');
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord($samples_table, $previous_data, $where_sample, $sample_data, $user_id, '');

                
            } else {
                if(!validateIsNumeric($sample_app_code)){
$sample_app_code = generateApplicationCode($sub_module_id, $applications_table);
                }
                if(!validateIsNumeric($applicant_id)){
                    $applicant_id = getSingleRecordColValue($table_name, array('application_code' => $sample_app_code), 'applicant_id');
                }
                $process_where = array('sub_module_id'=>64);

                $process_id = getSingleRecordColValue('wf_processes', $process_where, 'id');

                $sample_data['created_by'] = \Auth::user()->id;
                $sample_data['created_on'] = Carbon::now();

                $res = insertRecord('tra_samples_details', $sample_data, $user_id, '');
                
                $limssample_id = $res['record_id'];
               
                $reference_no = genLaboratoryReference_number($req->section_id, $zone_id, $req->sample_category_id, $laboratory_id, $req->device_type_id, $user_id,4);

                $view_id = generateApplicationViewID();
                $app_data['created_on'] = Carbon::now();
                $app_data['created_by'] = $user_id;
                $app_data['status_id'] = 1;
                $app_data['reference_no'] = $reference_no;
                $app_data['laboratory_id'] = $laboratory_id;
                $app_data['sample_id'] = $limssample_id;
                $app_data['applicant_id'] = $applicant_id;

                $app_data['view_id'] = $view_id;
                
                $app_data['process_id'] = $process_id;
                $app_data['application_code'] = $sample_app_code;
                $res = insertRecord('tra_sample_applications', $app_data, $user_id, '');
                if ($res['success'] == false) {
                    return response()->json($res);
                }
                $request_data = array(
                    'application_code' => $sample_app_code,
                    'analysis_type_id' => $analysis_type_id,
                    'limssample_id' => $limssample_id,
                    'misproduct_id' => $misproduct_id,
                    'missample_id' => $missample_id,
                    'limsreference_no' => $reference_no,
                    'status_id' => 1,
                    'requested_on' => Carbon::now(),
                    'requested_by' => $user_id,
                    'request_stage_id' => $workflow_stage_id,
                    'created_by' => $user_id,
                    'created_on' => Carbon::now(),
                    'module_id' => $module_id
                );
               
                $submission_params = array(
                    'application_id' => $res['record_id'],
                    'process_id' => $process_id,
                    'application_code' => $sample_app_code,
                    'usr_from' => $user_id,
                    'previous_stage' => $workflow_stage_id,
                    'current_stage' => $workflow_stage_id,
                    'module_id' => $module_id,'reference_no' => $reference_no,
                    'sub_module_id' => $sub_module_id,
                    'urgency' => 1,
                    'view_id'=>$view_id,
                    'applicant_id' => $applicant_id,
                    'remarks' => 'Initial Save',
                    'date_received' => Carbon::now(),
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );

                DB::table('tra_submissions')
                        ->insert($submission_params);
                $res = insertRecord('tra_sampleanalysis_requests', $request_data, $user_id);
               

            }
            if ($res['success']) {
                $res = array(
                    'success' => true,
                    'sample_id' => $limssample_id,
                    'laboratory_reference_no' => $reference_no,
                    'sample_application_code' => $sample_app_code,
                    'message' => 'Sample test Request saved successfully'
                );
            } else {
                $res = array(
                    'success' => false,
                    'message' => $res['message']
                );
            }

        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function syncSampleActiveIngredientsToLims($pms_sample_id, $lims_sample_id)
    {
        $qry = DB::table('tra_pmssample_ingredients')
            ->select(DB::raw("$lims_sample_id as sample_id,ingredient_id,specification_id as specificationtype_id,strength,si_unit_id"))
            ->where('sample_id', $pms_sample_id);
        $results = $qry->get();
        $results = convertStdClassObjToArray($results);
        $lims_db->table('activeingredients')
            ->insert($results);
    }

    public function getLimsSampleIngredients(Request $request)
    {
        try {
            $sample_id = $request->input('sample_id');
            $lims_db = DB::connection('mysql');

            $qry = $lims_db->table('tra_sample_activeingredients as t1')
                ->join('par_ingredients_details as t2', 't1.ingredient_id', '=', 't2.id')
                ->join('par_specification_types as t3', 't1.specificationtype_id', '=', 't3.id')
                ->join('par_si_units as t4', 't1.si_unit_id', '=', 't4.id')
                ->select(DB::raw("t1.*,t2.name as ingredient,CONCAT(t1.strength,t4.name) as strength_txt"))
                ->where('t1.sample_id', $sample_id);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => returnMessage($results)
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function onDeleteLabSampleOtherDetails(Request $req)
    {
        try {
            $record_id = $req->input('id');
            $table_name = $req->input('table_name');
            $user_id = \Auth::user()->id;
            $where = array(
                'id' => $record_id
            );
            $previous_data = getPreviousRecords($table_name, $where, 'lims_db');
            if ($previous_data['success'] == false) {
                return $previous_data;
            }
            $previous_data = $previous_data['results'];
            $res = deleteRecord($table_name, $previous_data, $where, $user_id, 'lims_db');
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function getsampleanalysistestParameters(Request $req)
    {
        try {
            $limssample_id = $req->limssample_id;
            $data = array();
            $section_id = $req->section_id;
            $module_id = $req->module_id;

            $data = DB::table('tra_sample_test_request as t1')
                ->join('par_cost_elements as t2', 't1.test_element_id', '=', 't2.id')
                ->leftJoin('analysis_techniques as t3', 't1.technique_id', '=', 't2.id')
                ->leftJoin('par_currencies as t4', 't1.currency_id', '=', 't4.id')
                ->select(DB::raw("t1.*,t3.name as technique_name,element_cost as parameter_cost, t2.name as test_parameter, t1.sample_id as limssampe_id, t4.name as currecy_name"))
                ->where(array('sample_id' => $limssample_id))
                ->get();

            $res = array(
                'success' => true,
                'results' => $data,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);

    }
    public function getsampleanalysistestAnalysisResults(Request $req)
    {
        try {
            $limssample_id = $req->limssample_id;
            $data = array();

            $data = DB::table('tra_sample_test_request as t1')
                ->join('par_cost_elements as t2', 't1.test_element_id', '=', 't2.id')
                ->leftJoin('analysis_techniques as t3', 't1.technique_id', '=', 't2.id')
                ->leftJoin('tra_sample_analysis_results as t4', 't1.id', '=', 't4.test_request_id')
                ->leftJoin('users as t5', 't4.analysts_id', '=', 't5.id')
                ->select(DB::raw("t1.*,t3.name as technique_name, t2.name as test_parameter, t1.sample_id as limssampe_id,t4.id as test_analysisresult_id, t4.specifications,t4.test_methods, t4.results,t4.analyst_remarks,t4.recommendation,t4.recommendation_comment,t4.analysis_comments,t4.analysts_id,t4.created_on as analysis_entered_on, CONCAT_WS(' ',decrypt(t5.first_name),decrypt(t5.last_name)) as analysed_by"))
                ->where(array('t1.sample_id' => $limssample_id))
                ->get();
                
            $res = array(
                'success' => true,
                'results' => $data,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);

    }
    
    public function getsampleanalysistestAnalysisResults1(Request $req)
    {
        try {
            $limssample_id = $req->limssample_id;
            $data = array();

            $results = DB::connection('lims_db')->select(DB::raw("select  t5.is_accredited, t4.is_subcontructed, t6.name as recommendation, t5.specifications,t5.results,t5.analyst_remarks,t5.analysts_id, t1.id as id,t2.assignement_status,t2.sample_id,t2.is_subcontracted,t2.test_request_id,t2.test_submission_id,t2.test_priority,date_format(t2.expected_end_date,'%Y-%m-%d') as expected_end_date , t2.reference_no,t3.name as test_parameter,test_priority from sample_test_request t1 inner join sample_test_schedulesdata t2 on t1.id = t2.test_request_id inner join testparameters t3 on t1.test_parameter_id = t3.id inner join sample_test_assignments t4 on t1.id = t4.test_request_id inner join sample_analysis_results t5 on t1.id = t5.test_request_id left join complyrecommendation_status t6 on t5.analyst_remarks = t6.id left join accredited_testparameters t7 on t1.test_parameter_id = t7.test_parameter_id where t1.sample_id = '" . $limssample_id . "' and t1.pass_status = 1 group by t5.id"));

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }


        return \response()->json($res);

    }

    public function getTestParametersDetails(Request $req)
    {
        try {
            $limssample_id = $req->limssample_id;
            $data = array();
            $section_id = $req->section_id;
            $sub_cat_id = $req->sub_cat_id;
            $cost_category_id = $req->cost_category_id;
            $where_statement = " ";
            if (is_numeric($sub_cat_id)) {
                $where_statement .= " and t2.sub_cat_id = $sub_cat_id ";
            }
            if (is_numeric($section_id)) {
                $where_statement .= "  and t1.section_id = $section_id ";
            }
            if (is_numeric($cost_category_id)) {
                $where_statement .= "  and t5.cost_category_id = $cost_category_id ";
            }

            $results = DB::select(DB::raw("select t6.name as technique_name,t7.name as currency, t5.name as cost_sub_category, t2.id as parameter_costs_id,t1.id as test_parameter_id,CONCAT_WS('-',t1.name,t6.name) as test_parameter ,t2.costs AS cost,t2.currency_id, t5.cost_category_id from par_cost_elements t1 inner join tra_element_costs t2 on t1.id = t2.element_id inner join par_cost_sub_categories t5 on t5.id = t2.sub_cat_id inner join par_cost_categories t4 on t4.id = t5.cost_category_id  left join analysis_techniques t6 on t2.technique_id = t6.id left join par_currencies t7 on t2.currency_id = t7.id where 1 $where_statement"));

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);

    }

    public function getSampleanalysistestrequestsprocesses(Request $req)
    {
        try {
            $reference_no = $req->input('reference_no');
            $results = DB::connection('lims_db')
                ->select(DB::raw("select t5.reference_no as laboratory_reference_no, t6.brand_name as sample_name, isDone as is_done,t1.id, t1.date_submitted as submitted_on,t1.date_released,t1.reference_no,t2.name as current_stage,t3.name as applicant_name,IF(t1.altered_by = '', t4.FullName, t1.altered_by) as usr_to,t5.laboratory_no, t6.brand_name from submissions t1 inner join menus t2 on t1.destination_process = t2.id left join companies t3 on t1.applicant_id = t3.id left join users t4 on t1.usr_to = t4.usr_id left join sample_applications t5 on t1.reference_no = t5.reference_no left join samples t6 on t5.sample_id = t6.id  where t1.reference_no = '" . $reference_no . "'"));
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function funcAddSampleTestParameters(Request $req)
    {
        try {

            $user_id = $this->user_id;
            $limssample_id = $req->limssample_id;
            $selected = $req->selected;
            $selected_ids = json_decode($selected);
            $table_name = 'tra_sample_test_request';
            foreach ($selected_ids as $selected_id) {
                $record = DB::table('tra_element_costs')
                    ->where(array('id' => $selected_id))
                    ->first();
                if ($record) {

                    $where_app = array('element_costs_id' => $selected_id, 'sample_id' => $limssample_id);
                    $data = array('sample_id' => $limssample_id,
                        'element_costs_id' => $selected_id,
                        'element_cost' => $record->costs,
                        'test_element_id' => $record->element_id,
                        'quantity' => 1,
                        'currency_id' => $record->currency_id,
                        'created_on' => Carbon::now(),
                        'created_by' => $user_id
                    );
                    if (!recordExists($table_name, $where_app, '')) {
                        $res = insertRecord($table_name, $data, $user_id, '');
                    }
                }
            }
            $res = array(
                'success' => true,
                'message' => 'Sample Test Parameters Saved Successfully'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);

    }

    public function funcSampleApplicationSubmissionWin(Request $req)
    {
        try {
            //get a value
            $user_id = $this->user_id;
            $limssample_id = $req->limssample_id;
            $application_code = $req->application_code;
            $remarks = $req->remarks;
            //check if the sample test have been added
            if (recordExists('tra_sample_test_request', array('sample_id' => $limssample_id))) {
                $record = DB::table('tra_sample_applications as  t1')
                    ->join('tra_samples_details as t2', 't1.sample_id', '=', 't2.id')
                    ->select('t1.*')
                    ->where(array('t1.sample_id' => $limssample_id))
                    ->first();
                if ($record) {
                    $section_id = $record->section_id;
                    $reference_no = $record->reference_no;
                    $applicant_id = $record->applicant_id; $sub_module_id = $record->sub_module_id;
                    $where_process = array('t4.sub_module_id'=>$sub_module_id,'stage_status'=>1);

                    $rec = DB::table('wf_workflow_transitions as t1')
                                ->join('wf_workflow_actions as t2', 't1.action_id','t2.id')
								->join('wf_workflow_stages as t3', 't1.stage_id','t3.id')
								->join('wf_processes as t4', 't1.workflow_id','t4.workflow_id')
                                ->select('t1.stage_id as current_stage','t1.nextstage_id', 't1.application_status_id as app_status_id')
							
                                ->where($where_process)
                                ->first();
                                if (is_null($rec)) {
                                    $res = array(
                                        'success' => false,
                                        'message' => 'Problem encountered while getting workflow details, consult System Admin!!'
                                    );
                                    return $res;
                                }
                    $submission_params = array(
                        'application_id' => $record->id,
                        'process_id' => $record->process_id,
                        'application_code' => $record->application_code,
                        'usr_from' => $user_id,
                        'previous_stage' => $rec->current_stage,
                        'current_stage' => $rec->current_stage,
                        'module_id' => $record->module_id,'reference_no' => $record->reference_no,
                        'sub_module_id' => $record->sub_module_id,
                        'application_status_id' => $rec->app_status_id,
                        'urgency' => 1,
                        'view_id'=>$record->view_id,
                        'applicant_id' => $record->applicant_id,
                        'remarks' => $remarks,
                        'date_received' => Carbon::now(),
                        'created_on' => Carbon::now(),
                        'created_by' => $user_id
                    );

                    DB::table('tra_submissions')
                            ->insert($submission_params);
                    
                    $where_sample = array('limssample_id' => $limssample_id, 'application_code' => $application_code);
                    $previous_data = getPreviousRecords('tra_sampleanalysis_requests', $where_sample);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res = updateRecord('tra_sampleanalysis_requests', $previous_data, $where_sample, array('status_id' => 2, 'altered_by' => $user_id, 'dola' => Carbon::now()), $user_id);
                } else {
                    $res = array(
                        'success' => false,
                        'message' => 'No sample application found!!'
                    );
                }
            } else {
                $res = array(
                    'success' => false,
                    'message' => 'Enter Analysis Test Request Parameter to submit'
                );
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return response()->json($res);
    }
    
    function get_Userto($hostProcess, $ref) {
        $record = DB::connection('lims_db')->table('submissions')
                ->where(array('reference_no'=>$ref, 'destination_process'=>$hostProcess,'isDone'=>0))
                ->first();
		$usr_from = '';
		if ($record) {
			$usr_from = $record -> usr_from;

		}
		else{
				$usr_from = '0';
		}
		return $usr_from;

	}

	function get_Nextstage($hostProcess, $section_id) {
        $record = DB::connection('lims_db')->table('process_accountreceivablestages')
                ->where(array('section_id'=>$section_id, 'account_stage'=>$hostProcess))
                ->select("process_to");
        $record2 = DB::connection('lims_db')->table('prereceiving_paymentstage')
                ->where(array('section_id'=>$section_id, 'payment_stage'=>$hostProcess))
                ->select("receiving_stage as process_to");
                $record2->union($record);
        $process_to = 0;
        $record2 = $record2->first();
		if ( $record2) {
			$process_to = $record2 -> process_to;
		}
		return $process_to;

	}
    function getPaymnetmodes($sample_id){
        
        $record = DB::connection('lims_db')->table('sample_invoicing_details')
                ->where(array('sample_id'=>$sample_id))
                ->first();
$payment_mode_id = 1;
        if($record){
            $payment_mode_id = $record->payment_mode_id;
            
        }
        return $payment_mode_id;
        
    }
	public function submitRegistrationToNextStage(Request $req) {
        try{
            $ref = $req->ref;
            $usr_id = $this->user_id;
            $hostProcess = $req->hostProcess;
            
            $record = DB::connection('lims_db')->table('sample_applications')
                    ->where(array('reference_no'=>$ref))
                    ->first();

            if ($record) {
                $row = $record;
                $applicant_id = $row -> applicant_id;
                $section_id = $row -> section_id;
                $sample_id = $row -> sample_id;
                $reference_no = $row -> reference_no;

                //get the next stage and the users to
                $usr_to = $this -> get_Userto($hostProcess, $reference_no);
                $next_stage = $this -> get_Nextstage($hostProcess, $section_id);

                $data = array('usr_to' => $usr_to, 'usr_from' => $usr_id, 'host_process' => $hostProcess, 'applicant_id' => $applicant_id, 'destination_process' => $next_stage, 'reference_no' => $reference_no, 'isRead' => 0, 'isDone' => 0, 'created_by' => 'Account Department', 'created_on' => date('Y-m-d H:i:s'), 'date_submitted' => date('Y-m-d H:i:s'));
                $payment_mode = $this->getPaymnetmodes($sample_id);
                                if($payment_mode== 2){
                                    $data['isDone'] = 1;
                                }
                DB::connection('lims_db')->table('submissions')->insert($data);
                $where = array('reference_no'=>$reference_no, 'destination_process'=>$hostProcess);
                $data = array('isDone' => 1, 'isRead' => 1, 'dola' => date('Y-m-d H:i:s'));
    
                DB::connection('lims_db')->table('submissions')->where($where)->update($data);
                $res = array('success' => true, 'message' => 'Submitted successfully');
                                
            }
            else{
                $res = array('success' => false, 'message' => 'Error occurred');
                
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
	}
    public function prepareLabServicesSamplePaymentPanel(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');
        try {
            $qry1 = DB::connection('lims_db')->table('sample_applications as t1')
                        ->join('companies as t2', 't1.applicant_id', 't2.id')
                        ->select(DB::raw("CONCAT_WS(',',t2.name,t2.postal_address) as applicant_details,t1.section_id,19 as module_id,'' as product_details,t1.applicant_id"))
                        ->first();
            $qry2 = DB::table('tra_application_invoices')->select('id as invoice_id', 'invoice_no')
            // ->where('application_code',$application_code)
            ->first();

            $invoice_id = '';
                $invoice_no = '';
            if($qry2){
                $invoice_id = $qry2->invoice_id;
                $invoice_no = $qry2->invoice_no;
            }
            $payment_details = getApplicationPaymentsRunningBalance($application_id, $application_code, $invoice_id);

            $results = array('applicant_details'=>$qry1->applicant_details,         'section_id'=>$qry1->section_id,
            'module_id'=>$qry1->module_id,
            'product_details'=>$qry1->product_details,
            'applicant_id'=>$qry1->applicant_id,
            'invoice_id'=>$invoice_id, 
            'invoice_no'=>$invoice_no );
           
            $res = array(
                'success' => true,
                'results' => $results,
                'balance' => formatMoney($payment_details['running_balance']),
                'invoice_amount' => formatMoney($payment_details['invoice_amount']),
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    
    public function prepareSampleReceiving(Request $req)
    {   
        $application_id = $req->input('application_id');
        $application_code = $req->input('application_code');
        $table_name = $req->input('table_name');
        try {
            $main_qry = DB::table('tra_sample_applications as t1')
                ->join('tra_samples_details as t2', 't1.sample_id', '=', 't2.id')
                ->where('t1.application_code', $application_code);

            $qry1 = clone $main_qry;
            $qry1->join('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                ->select('t1.*', 't1.id as active_application_id', 
                    't3.name as applicant_name', 't3.contact_person',
                    't3.tpin_no', 't3.country_id as app_country_id', 't3.region_id as app_region_id', 't3.district_id as app_district_id', 't3.physical_address as app_physical_address',
                    't3.postal_address as app_postal_address', 't3.telephone_no as app_telephone', 't3.fax as app_fax', 't3.email as app_email', 't3.website as app_website',
                    't2.*', 't2.id as limssample_id');

            $results = $qry1->first();

            $where = array(
                'module_id' => $results->module_id,
                'sub_module_id' => $results->sub_module_id
            );
			$results->process_name = getSingleRecordColValue('wf_processes', $where, 'name');
	
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );

        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getSampleAnalysisRequestDashboard(Request $request)
    {
        $module_id = $request->input('module_id');
        $section_id = $request->input('section_id');
        $sub_module_id = $request->input('sub_module_id');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $user_id = $this->user_id;
        $assigned_groups = getUserGroups($user_id);
        $is_super = belongsToSuperGroup($assigned_groups);
        try {
            $assigned_stages = getAssignedProcessStages($user_id, $module_id);

            $qry = DB::table('tra_sample_applications as t1')
                ->join('tra_submissions as t7', function ($join) {
                    $join->on('t1.application_code', '=', 't7.application_code');
                })
                ->join('tra_samples_details as t2', 't1.sample_id', '=', 't2.id')
                ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                ->leftJoin('wf_processes as t4', 't7.process_id', '=', 't4.id')
                ->leftJoin('wf_workflow_stages as t5', 't7.current_stage', '=', 't5.id')
                ->leftJoin('par_system_statuses as t6', 't1.application_status_id', '=', 't6.id')
                ->leftJoin('users as t8', 't7.usr_from', '=', 't8.id')
                ->leftJoin('users as t9', 't7.usr_to', '=', 't9.id')
                ->select(DB::raw("t7.date_received, CONCAT_WS(' ',decrypt(t8.first_name),decrypt(t8.last_name)) as from_user,CONCAT_WS(' ',decrypt(t9.first_name),decrypt(t9.last_name)) as to_user,  t1.id as active_application_id, t1.application_code, t4.module_id, t4.sub_module_id, t4.section_id, t2.brand_name as sample_name,t1.code_ref_no, 
                    t6.name as application_status, t3.name as applicant_name, t4.name as process_name, t5.name as workflow_stage,t5.id as workflow_stage_id, t5.is_general, t3.contact_person,
                    t3.tpin_no, t3.country_id as app_country_id, t3.region_id as app_region_id, t3.district_id as app_district_id, t3.physical_address as app_physical_address,
                    t3.postal_address as app_postal_address, t3.telephone_no as app_telephone, t3.fax as app_fax, t3.email as app_email, t3.website as app_website,
                    t2.*, t1.*"));
            $is_super ? $qry->whereRaw('1=1') : $qry->whereIn('t1.workflow_stage_id', $assigned_stages);
                
            if (validateIsNumeric($sub_module_id)) {
                $qry->where('t1.sub_module_id', $sub_module_id);
            }


            if (validateIsNumeric($workflow_stage_id)) {

                $qry->where('t7.current_stage', $workflow_stage_id);
            }

            $qry->where('t7.isDone', 0);
            $results = $qry->get();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);

    }
    
    public function sampleTestAnalysisResults(Request $request)
    {
        $application_code = $request->input('application_code');
        $test_parameters = $request->input('test_parameters');
        $test_parameters = json_decode($test_parameters);
        $table_name = 'tra_sample_analysis_results';
        $user_id = $this->user_id;
       
        try {
            $insert_params = array();
            foreach ($test_parameters as $test_parameter) {
                
                    $test_request_id = $test_parameter->test_request_id;
                    $test_analysisresult_id = $test_parameter->test_analysisresult_id;
                    $where = array('id'=>$test_analysisresult_id, 'test_request_id'=>$test_request_id);
                    $test_results = array(
                        'test_request_id' => $test_parameter->test_request_id,
                        'sample_id' => $test_parameter->limssample_id,
                        'test_methods' => $test_parameter->test_methods,
                        'specifications' => $test_parameter->specifications,
                        'analyst_remarks' => $test_parameter->analyst_remarks,
                        'results' => $test_parameter->results,
                        'analysis_comments' => $test_parameter->analysis_comments,
                        'recommendation_comment'=> $test_parameter->recommendation_comment,
                        'analysts_id' => $user_id
                       
                    );
                    
                    if(validateIsNumeric($test_analysisresult_id)){
                        $test_results['dola'] = Carbon::now();
                        $test_results['altered_by'] =$user_id;
                     
                        $prev_data = getPreviousRecords($table_name, $where);
                        updateRecord($table_name, $prev_data['results'], $where, $test_results, $user_id);
                    }
                    else{
                        $test_results['created_on'] = Carbon::now();
                        $test_results['created_by'] =$user_id;
                     
                        $res = insertRecord($table_name, $test_results, $user_id, '');
                    }
                    
                
            }
            
            $res = array(
                'success' => true,
                'message' => 'Sample test Analysis Results details saved successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveTestRequestParametersReview(Request $request)
    {
        $application_code = $request->input('application_code');
        $test_parameters = $request->input('test_parameters');
        $test_parameters = json_decode($test_parameters);
        $table_name = 'tra_sample_test_request';
        $user_id = $this->user_id;
       
        try {
            $insert_params = array();
            foreach ($test_parameters as $test_parameter) {
                
                     $test_request_id = $test_parameter->test_request_id;
                    $where = array('id'=>$test_request_id);
                    
                    $update_params = array(
                        'pass_status' => $test_parameter->pass_status,
                        'verification_comments' => $test_parameter->verification_comments,
                        'quantity' => $test_parameter->quantity,
                        'reviewed_by' => $user_id,
                        'review_date' => Carbon::now(),
                        'dola' => Carbon::now(),
                        'altered_by' => $user_id
                    );
                    $prev_data = getPreviousRecords($table_name, $where);
                    updateRecord($table_name, $prev_data['results'], $where, $update_params, $user_id);
                
            }
            
            $res = array(
                'success' => true,
                'message' => 'Sample test Request Review details saved successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function saveSampleanalysisReviewRecommendationDetails(Request $request)
    {
        
        $table_name = $request->input('table_name');
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $user_id = $this->user_id;
       
        $qry = DB::table($table_name.'  as t1')
            ->where('t1.application_code', $application_code);
        $res = array();
        $application_details = DB::table($table_name.'  as t1')
            ->where('t1.application_code', $application_code)->first();
        try { // 
            DB::transaction(function () use ($qry, $application_id, $application_code, $table_name, $request, $application_details, &$res) {
                $laboratory_no = $application_details->laboratory_no;
                $reference_no = $application_details->reference_no;
                $review_recommendation_id = $request->input('review_recommendation_id');
                $process_id = $request->input('process_id');
                $workflow_stage_id = $request->input('workflow_stage_id');

                $recommendation_id = $request->input('recommendation_id');
                $recommendation_comment = $request->input('recommendation_comment');
                $approved_by = $request->input('approved_by');
                $approval_date = formatDate($request->input('approval_date'));
                $expiry_date = $request->input('expiry_date');
				
                $permit_signatory = $request->input('permit_signatory');
                $user_id = $this->user_id;
                
                $sub_module_id = $application_details->sub_module_id;
                $module_id = $application_details->module_id;
                $section_id = $application_details->section_id;
				
                $params = array(
                    'application_id' => $application_id,
                    'application_code' => $application_code,
                    'recommendation_id' => $recommendation_id,
                    'recommendation_comment' => $recommendation_comment,
                    'approval_date' => $approval_date,
                    'prepared_by_id' => $user_id,
                    'approved_by' => $user_id,
                    'permit_signatory' => $permit_signatory
                );

                if (validateIsNumeric($review_recommendation_id)) {
                            //update
                            $where = array(
                                'id' => $review_recommendation_id
                            );
                            $params['dola'] = Carbon::now();
                            $params['altered_by'] = $user_id;
                            $prev_data = getPreviousRecords('tra_sampleanalysisreview_recommendation', $where);
                            if ($prev_data['success'] == false) {
                                return \response()->json($prev_data);
                            }
                            $prev_data_results = $prev_data['results'];
                            
                            $res = updateRecord('tra_sampleanalysisreview_recommendation', $prev_data['results'], $where, $params, $user_id);
                            
                        } else {
                            $params['created_on'] = Carbon::now();
                            $params['created_by'] = $user_id;
                            
                            $res = insertRecord('tra_sampleanalysisreview_recommendation', $params, $user_id);
                            
                        }
            }, 5);
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return $res;
    }

    public function getSampleAnalysisCertificateReleaseApplications(Request $req)
    {

        $table_name = $req->input('table_name');
        $workflow_stage = $req->input('workflow_stage_id');
		
		$table_name = 'tra_sample_applications';
        try {
			
            $qry = DB::table($table_name . ' as t1')
                ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                ->leftJoin('tra_samples_details as t5', 't1.sample_id', '=', 't5.id')
                ->join('tra_submissions as t6', 't1.application_code','=','t6.application_code')
                ->leftJoin('tra_sampleanalysisreview_recommendation as t7','t1.application_code', '=', 't7.application_code')
                ->leftJoin('par_anaysisresult_recommendation as t8', 't7.recommendation_id', '=', 't8.id')
                ->select('t1.*','t1.id as application_id', 't3.name as applicant_name','t5.brand_name as sample_name', 't7.id as release_recommendation_id','t8.description as release_recommendation',
                't1.id as active_application_id', 't8.name as recommendation')
                ->where(array('t6.current_stage' => $workflow_stage,'isDone'=>0))
                ->orderBy('t1.id','desc');
			
            $results = $qry->get();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );

        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);


    }
}
