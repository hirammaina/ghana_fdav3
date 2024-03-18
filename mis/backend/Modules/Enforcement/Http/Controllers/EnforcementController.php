<?php

namespace Modules\Enforcement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EnforcementController extends Controller
{
    protected $user_id;

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
                return $next($request);
            });
        }
    }
    public function getusers(Request $request)
    {
        $filters = $request->filters;
        $qry = DB::table('users as t1')
            ->leftjoin('par_departments as t2', 't1.department_id', '=', 't2.id')
            ->select(DB::raw("t1.id,CONCAT_WS(' ',t2.name,decrypt(t1.first_name),decrypt(t1.last_name)) as name,t2.name as department"));



        $results = $qry->get();
        if ($filters != '') {
            $filters = (array)json_decode($filters);
            $user_id = $filters['id'];
            $res = $qry->where(array('t1.id' => $user_id));
            $results = $res->get();
        }
        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is well'
        );
        return \response()->json($res);
    }
    public function getApprovedPremises(Request $req)
    {
        $reg_premise_id = $req->input('reg_premise_id');
        try {
            if ($reg_premise_id != '') {
                $qry = DB::table('tra_premises_applications as t1')
                    ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                    ->leftjoin('tra_premises as t7', 't1.premise_id', '=', 't7.id')
                    ->leftJoin('par_premises_types as t8', 't7.premise_type_id', '=', 't8.id')
                    ->leftJoin('tra_approval_recommendations as t11', 't1.application_code', '=', 't11.application_code')
                    ->join('tra_registered_premises as t12', 't12.tra_premise_id', '=', 't7.id')
                    ->leftJoin('par_validity_statuses as t4', 't12.validity_status_id', '=', 't4.id')
                    ->leftJoin('tra_premises_personnel as t5', 't1.premise_id', '=', 't5.premise_id')
                    ->leftJoin('tra_personnel_information as t6', 't5.personnel_id', '=', 't6.id')
                    ->select(DB::raw("t1.reg_premise_id,t7.id as tra_premise_id,t7.name as premise_name,t8.name as premise_type,t8.id as premise_type_id,t11.certificate_no,t6.name as responsible_personnel"));
                $qry->where('t1.reg_premise_id', $reg_premise_id);
                $results = $qry->get();
                $res = array(
                    'success' => true,
                    'results' => $results,
                    'message' => 'All is well'
                );
            } else {
                $qry = DB::table('tra_premises_applications as t1')
                    ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                    ->join('tra_premises as t7', 't1.premise_id', '=', 't7.id')
                    ->leftJoin('par_premises_types as t8', 't7.premise_type_id', '=', 't8.id')
                    ->leftJoin('tra_approval_recommendations as t11', 't1.application_code', '=', 't11.application_code')
                    ->join('registered_premises as t12', 't12.tra_premise_id', '=', 't7.id')
                    ->leftJoin('par_validity_statuses as t4', 't12.validity_status_id', '=', 't4.id')
                    ->select(DB::raw("t1.*,t1.id as active_application_id, t1.reg_premise_id,t3.name as applicant_name,t3.contact_person as contact_person,
            t4.name as application_status,t7.id as tra_premise_id,t7.name as name,t7.telephone,t7.country_id,t7.region_id,t7.city_id as district_id,
            t7.physical_address,t7.postal_address,t8.name as premise_type, t11.certificate_no,
            t12.approval_date,t12.expiry_date as expiry_date,t12.validity_status_id as decision_id"));
                $qry->where('t12.registration_status_id', 2);
                $results = $qry->get();
                $res = array(
                    'success' => true,
                    'results' => $results,
                    'message' => 'All is well'
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
        return \response()->json($res);
    }
    public function getEnforcementApplications(Request $request)
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
            $qry = DB::table('tra_enforcement_applications as t1')
                ->join('tra_submissions as t7', function ($join) {
                    $join->on('t1.application_code', '=', 't7.application_code')
                        ->on('t1.workflow_stage_id', '=', 't7.current_stage');
                })
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->join('wf_tfdaprocesses as t4', 't7.process_id', '=', 't4.id')
                ->leftJoin('wf_workflow_stages as t5', 't7.current_stage', '=', 't5.id')
                ->leftJoin('par_system_statuses as t6', 't1.application_status_id', '=', 't6.id')
                ->leftJoin('users as t8', 't7.usr_from', '=', 't8.id')
                ->leftJoin('users as t9', 't7.usr_to', '=', 't9.id')
                ->leftJoin('users as t10', 't3.created_by', '=', 't10.id')
                ->leftJoin('par_report_type as t11', 't3.report_type_id', '=', 't11.id')
                ->select(DB::raw("t7.date_received, CONCAT(decrypt(t8.first_name),decrypt(t8.last_name)) as from_user,CONCAT(decrypt(t9.first_name),decrypt(t9.last_name)) as to_user,  
                    t1.id as active_application_id, t1.application_code, t4.module_id, t4.sub_module_id, t4.section_id,t6.name as application_status, t4.name as process_name, t5.name as workflow_stage, t5.is_general,CONCAT_WS(' ',decrypt(t10.first_name),decrypt(t10.last_name))  as reported_by_name,t3.created_on as reported_day ,t1.*,t11.name as report_type_name"))
                ->where('t5.stage_status', '<>', 3)
                ->where('isDone', 0);

            $is_super ? $qry->whereRaw('1=1') : $qry->whereIn('t1.workflow_stage_id', $assigned_stages);
            if (validateIsNumeric($section_id)) {
                $qry->where('t1.section_id', $section_id);
            }
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
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareNewReportReceiving(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');
        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_report_type as t2', 't1.report_type_id', '=', 't2.id')
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->leftJoin('par_entity_types as t4', 't3.entity_type_id', '=', 't4.id')
                ->where('t1.id', $application_id)
                ->select(
                    't1.*',
                    't3.*',
                    't3.id as enforcement_id',
                    't1.reference_no',
                    't4.name as suspected_entity',
                    't3.product_section_id as product_type',
                    't3.suspect_country_id as country_id',
                    't3.suspect_region_id as region_id',
                    't3.suspect_district_id as district_id',
                    't3.is_product_registered as is_registred'
                );

            $results = $main_qry->first();
            // dd($results);
            // $qry2 = clone $main_qry;
            // $qry2->join('wb_trader_account as t3', 't1.local_agent_id', '=', 't3.id')
            //     ->select('t3.id as applicant_id', 't3.name as applicant_name', 't3.contact_person',
            //         't3.tpin_no', 't3.country_id as app_country_id', 't3.region_id as app_region_id', 't3.district_id as app_district_id', 't3.physical_address as app_physical_address',
            //         't3.postal_address as app_postal_address', 't3.telephone_no as app_telephone', 't3.fax as app_fax', 't3.email as app_email', 't3.website as app_website');
            // $ltrDetails = $qry2->first();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareMonitoringComplianceDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        $table_name = $request->input('table_name');
        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_monitoring_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->where('t1.id', $application_id)
                ->select('t1.*', 't2.*', 't1.enforcement_id', 't2.reg_premise_id', 't2.premise_id');

            $results = $main_qry->first();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareHealthAssesmentDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');
        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_monitoring_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftJoin('par_prescribing_compliance_data as t3', 't1.application_code', '=', 't3.id')
                ->leftJoin('par_dispensing_compliance_data as t4', 't1.application_code', '=', 't4.id')
                ->where('t1.id', $application_id)
                ->select('t1.*', 't2.*', 't3.*', 't4.*');

            $results = $main_qry->first();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function savePrescribingComplianceDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $prescribing_details = $request->input('prescribing_details');
        $prescribing_details = json_decode($prescribing_details);
        $table_name = 'par_prescribing_compliance_data';
        $user_id = $this->user_id;

        try {
            $insert_params = array();
            DB::beginTransaction();
            foreach ($prescribing_details as $prescribing_detail) {
                $medicine_name = '';
                $created_by = '';
                $altered_by = '';
                $altered_on = '';
                $created_on = '';
                if (property_exists($prescribing_detail, 'medicine_name')) {
                    $medicine_name = $prescribing_detail->medicine_name;
                    $created_on = Carbon::now();
                    $altered_by = $user_id;
                    $altered_on = Carbon::now();
                }

                $product_id = $prescribing_detail->product_id;
                if (isset($product_id) && $product_id != '') {
                    $where = array(
                        'product_id' => $product_id,
                        'application_code' => $application_code
                    );

                    $previous_data = getPreviousRecords($table_name, $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }

                    DB::table('par_prescribing_compliance_data')
                        ->where(array('product_id' => $product_id, 'application_code' => $application_code))
                        ->delete();

                    $medicine_name = $prescribing_detail->medicine_name;
                    $patient_particulars = $prescribing_detail->patient_particulars;
                    $medicine_details = $prescribing_detail->medicine_details;
                    $prescriber_details = $prescribing_detail->prescriber_details;
                    $prescription_date = $prescribing_detail->prescription_date;
                    $facility_stamp = $prescribing_detail->facility_stamp;

                    //calculating the avarage
                    $prescribing_average = array(
                        $patient_particulars,
                        $medicine_details,
                        $prescriber_details,
                        $prescription_date,
                        $facility_stamp
                    );

                    $calculated_average = array_sum($prescribing_average) / count($prescribing_average);
                    $calculated_average = round($calculated_average * 100, 1);

                    $insert_params[] = array(
                        'application_id' => $application_id,
                        'application_code' => $application_code,
                        'product_id' => $prescribing_detail->product_id,
                        'medicine_name' => $medicine_name,
                        'patient_particulars' => $patient_particulars,
                        'medicine_details' => $medicine_details,
                        'prescriber_details' => $prescriber_details,
                        'prescription_date' => $prescription_date,
                        'facility_stamp' => $facility_stamp,
                        'calculated_average' => $calculated_average,
                        'created_on' => Carbon::now(),
                        'created_by' => $user_id,
                    );
                } else {

                    $medicine_name = $prescribing_detail->medicine_name;
                    $patient_particulars = $prescribing_detail->patient_particulars;
                    $medicine_details = $prescribing_detail->medicine_details;
                    $prescriber_details = $prescribing_detail->prescriber_details;
                    $prescription_date = $prescribing_detail->prescription_date;
                    $facility_stamp = $prescribing_detail->facility_stamp;

                    //calculating the avarage
                    $prescribing_average = array(
                        $patient_particulars,
                        $medicine_details,
                        $prescriber_details,
                        $prescription_date,
                        $facility_stamp
                    );

                    $calculated_average = array_sum($prescribing_average) / count($prescribing_average);
                    $calculated_average = round($calculated_average * 100, 1);

                    $insert_params[] = array(
                        'application_id' => $application_id,
                        'application_code' => $application_code,
                        'product_id' => $prescribing_detail->product_id,
                        'medicine_name' => $medicine_name,
                        'patient_particulars' => $patient_particulars,
                        'medicine_details' => $medicine_details,
                        'prescriber_details' => $prescriber_details,
                        'prescription_date' => $prescription_date,
                        'facility_stamp' => $facility_stamp,
                        'calculated_average' => $calculated_average,
                        'created_on' => Carbon::now(),
                        'created_by' => $user_id,
                    );
                }
            }
            if (count($insert_params) > 0) {
                $res = insertMultipleRecords($table_name, $insert_params);
                if (!isset($res['success']) || $res['success'] == false) {
                    DB::rollback();
                    return $res;
                }
            }

            DB::commit();
            $res = array(
                'success' => true,
                'message' => 'Prescribing Data saved successfully!!'
            );
        } catch (\Exception $exception) {
            DB::rollback();
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            DB::rollback();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveDispensingComplianceDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $dispensing_details = $request->input('dispensing_details');
        $dispensing_details = json_decode($dispensing_details);
        $table_name = 'par_dispensing_compliance_data';
        $user_id = $this->user_id;

        try {
            $insert_params = array();
            DB::beginTransaction();
            foreach ($dispensing_details as $dispensing_detail) {
                $dispensing_name = '';
                $created_by = '';
                $altered_by = '';
                $altered_on = '';
                $created_on = '';
                if (property_exists($dispensing_detail, 'dispensing_name')) {
                    $dispensing_name = $dispensing_detail->dispensing_name;
                    $created_on = Carbon::now();
                    $altered_by = $user_id;
                    $altered_on = Carbon::now();
                }

                $personnel_id = $dispensing_detail->personnel_id;

                if (isset($personnel_id) && $personnel_id != '') {

                    $where = array(
                        'personnel_id' => $personnel_id,
                        'application_code' => $application_code
                    );

                    $previous_data = getPreviousRecords($table_name, $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }

                    DB::table('par_dispensing_compliance_data')
                        ->where(array('personnel_id' => $personnel_id, 'application_code' => $application_code))
                        ->delete();

                    $patient_name = $dispensing_detail->patient_name;
                    $dispensing_date = $dispensing_detail->dispensing_date;
                    $dispensed_packsize = $dispensing_detail->dispensed_packsize;
                    $dispenser_name_signature = $dispensing_detail->dispenser_name_signature;

                    //calculating the avarage
                    $dispensing_average = array(
                        $patient_name,
                        $dispensing_date,
                        $dispensed_packsize,
                        $dispenser_name_signature,
                    );

                    $calculated_average = array_sum($dispensing_average) / count($dispensing_average);
                    $calculated_average = round($calculated_average * 100, 1);

                    $insert_params[] = array(
                        'application_id' => $application_id,
                        'application_code' => $application_code,
                        'personnel_id' => $dispensing_detail->personnel_id,
                        'dispensing_name' => $dispensing_detail->dispensing_name,
                        'reg_number' => $dispensing_detail->reg_number,
                        'invoice_no' => $dispensing_detail->invoice_no,
                        'patient_name' => $patient_name,
                        'dispensing_date' => $dispensing_date,
                        'dispensed_packsize' => $dispensed_packsize,
                        'dispenser_name_signature' => $dispenser_name_signature,
                        'calculated_average' => $calculated_average,
                        'created_on' => Carbon::now(),
                        'created_by' => $user_id,
                    );
                }
            }
            if (count($insert_params) > 0) {
                $res = insertMultipleRecords($table_name, $insert_params);
                if (!isset($res['success']) || $res['success'] == false) {
                    DB::rollback();
                    return $res;
                }
            }
            DB::commit();
            $res = array(
                'success' => true,
                'message' => 'Dispensing Data saved successfully!!'
            );
        } catch (\Exception $exception) {
            DB::rollback();
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            DB::rollback();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareWorkplan(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');
        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_enforcement_workplan_details as t2', 't1.application_code', '=', 't2.application_code')
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->select('t1.*', 't2.*')
                ->where('t1.application_code', $application_code);
            $results = $main_qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareNewInvestigationReceiving(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_report_type as t2', 't1.report_type_id', '=', 't2.id')
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->leftJoin('tra_enforcement_workplan_details as t4', 't1.application_code', '=', 't4.application_code')
                ->leftJoin('par_entity_types as t5', 't3.entity_type_id', '=', 't5.id')
                ->where('t1.id', $application_id)
                ->select(
                    't1.*',
                    't3.*',
                    't3.id as enforcement_id',
                    't3.suspect_country_id as country_id',
                    't3.suspect_region_id as region_id',
                    't3.suspect_district_id as district_id',
                    't3.suspect_physical_address',
                    't3.suspect_postal_address',
                    't3.suspect_telephone'
                );

            $results = $main_qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function saveNewEnforcementReceivingDetails(Request $request, $inCall = 0)
    {
        $active_application_id = $request->input('active_application_id');
        $process_id = $request->input('process_id');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $section_id = $request->input('section_id');
        $module_id = $request->input('module_id');
        $sub_module_id = $request->input('sub_module_id');
        $applicant_id = $request->input('applicant_id');
        $user_id = $this->user_id;
        $report_type_id = $request->input('report_type_id');
        $enforcement_id = $request->input('enforcement_id');
        $date = \Carbon\Carbon::parse($request->input('date'));
        $suspect_name = $request->input('suspect_name');
        // $suspect_omang = $request->input('suspect_omang');
        $suspect_telephone = $request->input('suspect_telephone');
        // $suspect_address = $request->input('suspect_address');
        // $suspect_occupation = $request->input('suspect_occupation');
        // $place_of_offence = $request->input('place_of_offence');
        // $car_reg_no = $request->input('car_reg_no');
        $entity_type_id = $request->input('entity_type_id');
        $is_product_registered = $request->input('is_product_registered');
        $certificate_no = $request->input('certificate_no');
        $brand_name = $request->input('brand_name');
        $common_name = $request->input('common_name');
        $product_section_id = $request->input('product_section_id');
        $prodclass_category_id = $request->input('prodclass_category_id');
        $batch_number = $request->input('batch_number');
        $expiry_date = \Carbon\Carbon::parse($request->input('expiry_date'));
        $is_facility_registered = $request->input('is_facility_registered');
        $permit_no = $request->input('permit_no');
        $premise_name = $request->input('premise_name');
        $premise_type = $request->input('premise_type');
        $country_id = $request->input('suspect_country_id');
        $region_id = $request->input('suspect_region_id');
        $district_id = $request->input('suspect_district_id');
        $suspect_physical_address = $request->input('suspect_physical_address');
        $suspect_postal_address = $request->input('suspect_postal_address');
        $suspect_telephone = $request->input('suspect_telephone');
        $other_details = $request->input('other_details');

        $enforcement_data = array(
            'report_type_id' => $request->report_type_id,
            'section_id' => $request->section_id,
            //internal
            'reported_by_id' => $request->reported_by_id,
            'department' => $request->department,
            'reporter_designation' => $request->reporter_designation,
            'fullnames' => $request->fullnames,
            'department_name' => $request->department_name,
            //    'approved_by_id'=>$request->approved_by_id,
            // 'approver_designation'=>$request->approver_designation,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'age' => $request->age,
            'country' => $request->country,
            'date' => $request->date,
            'id_no' => $request->id_no,
            //external
            'applicant_name' => $request->applicant_name,
            'captured_by_department' => $request->captured_by_department,
            'country_name' => $request->country_name,
            'app_physical_address' => $request->app_physical_address,
            'app_email' => $request->app_email,
            'complainant_gender' => $request->complainant_gender,
            'app_telephone' => $request->app_telephone,
            'complainant_age' => $request->complainant_age,
            'applicant_id' => $applicant_id,
            //suspect info
            'suspect_name' => $suspect_name,
            // 'suspect_omang'=>$suspect_omang,
            // 'suspect_address'=>$suspect_address,
            // 'suspect_occupation'=>$suspect_occupation,
            // 'place_of_offence'=>$place_of_offence,
            // 'car_reg_no'=>$car_reg_no,
            'entity_type_id' => $entity_type_id,
            'is_product_registered' => $is_product_registered,
            'certificate_no' => $certificate_no,
            'brand_name' => $brand_name,
            'common_name' => $common_name,
            'product_section_id' => $product_section_id,
            'prodclass_category_id' => $prodclass_category_id,
            'batch_number' => $batch_number,
            'expiry_date' => $expiry_date,
            'is_facility_registered' => $is_facility_registered,
            'permit_no' => $permit_no,
            'premise_name' => $premise_name,
            'premise_type' => $premise_type,
            'suspect_country_id' => $country_id,
            'suspect_region_id' => $region_id,
            'suspect_district_id' => $district_id,
            'suspect_physical_address' => $suspect_physical_address,
            'suspect_postal_address' => $suspect_postal_address,
            'suspect_telephone' => $suspect_telephone,
            'other_details' => $other_details,
            'batch_number' => $request->batch_number,
            'expiry_date' => $request->expiry_date,
        );


        try {
            DB::beginTransaction();
            $applications_table = 'tra_enforcement_applications';
            $enforcement_table = 'tra_enforcement_information';

            $where_app = array(
                'id' => $active_application_id
            );
            //Edit enforcement Application
            if (isset($active_application_id) && $active_application_id != "") {


                $application_params = array(
                    'applicant_id' => $applicant_id
                );

                $where_app = array(
                    'id' => $active_application_id
                );

                $where_enforcement = array(
                    'id' => $enforcement_id
                );

                if (recordExists($applications_table, $where_app)) {

                    $app_details = getPreviousRecords($applications_table, $where_app);

                    if ($app_details['success'] == false) {
                        DB::rollBack();
                        return $app_details;
                    }
                    $app_details = $app_details['results'];


                    $application_params = updateRecord($applications_table, $app_details, $where_app, $application_params, $user_id);
                    if ($application_params['success'] == false) {
                        DB::rollBack();
                        return $application_params;
                    }
                }
                $application_code = $app_details[0]['application_code']; //$app_details->application_code;
                $tracking_no = $app_details[0]['tracking_no'];
                $reference_no = $app_details[0]['reference_no'];
                // EDIT
                if (recordExists($enforcement_table, $where_enforcement)) {
                    // DD($where_enforcement);
                    $enforcement_data['dola'] = Carbon::now();
                    $enforcement_data['altered_by'] = $user_id;
                    $previous_data = getPreviousRecords($enforcement_table, $where_enforcement);
                    if ($previous_data['success'] == false) {
                        DB::rollBack();
                        return $previous_data;
                    }

                    $previous_data = $previous_data['results'];
                }


                $res = updateRecord($enforcement_table, $previous_data, $where_enforcement, $enforcement_data, $user_id);

                initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(10, 100), $user_id);

                $res['active_application_id'] = $active_application_id;
                $res['active_application_code'] = $application_code;
                $res['enforcement_id'] = $enforcement_id;
                $res['reference_no'] = $reference_no;
                $res['tracking_no'] = $tracking_no;
                DB::commit();
            } else {
                //Insert

                $enforcement_res = insertRecord('tra_enforcement_information', $enforcement_data, $user_id);
                //dd('here');
                if ($enforcement_res['success'] == false) {
                    DB::rollBack();
                    return $enforcement_res;
                }

                //tracking the application
                $enforcement_id = $enforcement_res['record_id'];

                //Application_create
                $codes_array =  $this->getEnforcementApplicationReferenceCodes($request);
                $view_id = generateApplicationViewID();
                $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, 1, $user_id);
                $application_code = generateApplicationCode($sub_module_id, $applications_table);
                $application_status = getApplicationInitialStatus($module_id, $sub_module_id);

                if ($tracking_details['success'] == false) {
                    DB::rollBack();
                    return \response()->json($tracking_details);
                }

                $tracking_no = $tracking_details['tracking_no'];
                $reference_no = $tracking_details['tracking_no'];
                $reference_no = str_replace("TRC", 'BMR', $reference_no);

                if ($sub_module_id == 86) {
                    $tracking_no = $tracking_details['tracking_no'];
                    $reference_no = $tracking_details['tracking_no'];
                    $reference_no = str_replace("INV", 'INV', $reference_no);
                } else {
                    $tracking_no = $tracking_details['tracking_no'];
                    $reference_no = $tracking_details['tracking_no'];
                    $reference_no = str_replace("TRC", 'BMR', $reference_no);
                }

                $application_params = array(
                    'view_id' => $view_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_code' => $application_code,
                    'applicant_id' => $applicant_id,
                    'process_id' => $process_id,
                    'workflow_stage_id' => $workflow_stage_id,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'enforcement_id' => $enforcement_id,
                    'application_status_id' => $application_status->status_id,
                    "date_added" => Carbon::now(),
                    "created_by" => \Auth::user()->id,
                    "created_on" => Carbon::now()
                );

                $res = insertRecord($applications_table, $application_params, $user_id);

                if (!isset($res['record_id'])) {
                    DB::rollBack();
                    return $res;
                }
                $active_application_id = $res['record_id'];

                // createInitialRegistrationRecord('tra_enforcement_applications', $applications_table, $reg_params, $application_id, 'reg_premise_id');
                //DMS
                // initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no, $user_id);
                // add to submissions table
                $submission_params = array(
                    'application_id' => $active_application_id,
                    'view_id' => $view_id,
                    'process_id' => $process_id,
                    'application_code' => $application_code,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'usr_from' => $user_id,
                    'usr_to' => $user_id,
                    'previous_stage' => $workflow_stage_id,
                    'current_stage' => $workflow_stage_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_status_id' => $application_status->status_id,
                    'urgency' => 1,
                    'remarks' => 'Initial save of the application',
                    'date_received' => Carbon::now(),
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );
                DB::table('tra_submissions')
                    ->insert($submission_params);

                $product_params = array(
                    'application_id' => $active_application_id,
                    'application_code' => $application_code,
                    'tracking_no' => $tracking_no,
                    'batch_number' => $request->batch_number,
                    'expiry_date' => $request->expiry_date,
                    'entity_type_id' => $entity_type_id,
                    'brand_name' => $brand_name,
                    'common_name' => $common_name,
                    'product_type' => $request->product_type,
                    'prodclass_category_id' => $prodclass_category_id,
                    'premise_name' => $premise_name,
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );
                DB::table('tra_reported_products')
                    ->insert($product_params);
            }
            DB::commit();

            $res['active_application_id'] = $active_application_id;
            $res['process_id'] = $process_id;
            $res['application_code'] = $application_code;
            $res['tracking_no'] = $tracking_no;
            $res['reference_no'] = $reference_no;
            $res['enforcement_id'] = $enforcement_id;
            $res['msg'] = 'Record Saved Successfully';
            $res['success'] = true;
        } catch (\Exception $exception) {
            DB::rollBack();
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        if ($inCall == 1) {
            return $res;
        }
        return \response()->json($res);
    }
    public function getManagerEvaluationApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        $table_name = 'tra_enforcement_applications';

        try {
            $qry = DB::table($table_name . ' as t1')
                ->leftJoin('tra_enforcement_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftJoin('par_report_type as t3', 't2.report_type_id', '=', 't3.id')
                ->leftJoin('par_system_statuses as t4', 't1.application_status_id', 't4.id')
                ->leftJoin('tra_submissions as t5', 't1.application_code', '=', 't5.application_code')
                ->leftJoin('wb_trader_account as t6', 't2.applicant_id', '=', 't6.id')
                ->leftJoin('wf_workflow_stages as t7', 't5.current_stage', 't7.id')
                ->leftJoin('par_entity_types as t8', 't2.entity_type_id', 't8.id')
                ->select(
                    't1.*',
                    't1.id as active_application_id',
                    't2.suspect_address',
                    't3.name as nature_of_report',
                    't4.name as application_status',
                    't4.name as application_status',
                    't5.date_received as submitted_on',
                    't5.date_received as submitted_on',
                    't6.name as applicant_name',
                    't7.stage_category_id',
                    't8.name as suspected_entity',
                    DB::raw("CASE WHEN t2.fullnames is NULL THEN t2.applicant_name WHEN t2.applicant_name is NULL THEN t2.fullnames END reporter")
                )
                ->where(array('t5.current_stage' => $workflow_stage, 'isDone' => 0));

            $results = $qry->orderBy('t5.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function getStageEnforcementApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        //$table_name = getTableName($module_id);
        $table_name = 'tra_enforcement_applications';
        try {
            $qry = DB::table($table_name . ' as t1')
                ->leftJoin('par_system_statuses as t4', 't1.application_status_id', 't4.id')
                ->leftJoin('tra_approval_recommendations as t5', 't5.application_code', '=', 't1.application_code')
                ->leftJoin('tra_submissions as t9', 't9.application_code', '=', 't1.application_code')
                ->leftJoin('tra_enforcement_information as t15', 't1.enforcement_id', '=', 't15.id')
                ->leftJoin('par_report_type as t16', 't15.report_type_id', '=', 't16.id')
                ->leftJoin('users as t10', 't9.usr_from', '=', 't10.id')
                //->leftJoin('users as t19', 't15.applicant_id', '=', 't17.id')
                ->leftJoin('wb_trader_account as t17', 't15.applicant_id', '=', 't17.id')
                ->leftJoin('tra_investigation_approvals as t18', 't1.application_code', 't18.application_code')
                ->leftJoin('tra_released_product_applications as t20', 't1.application_code', 't20.application_code')
                ->leftJoin('tra_exhibit_request_approvals as t21', 't1.application_code', 't21.application_code')
                ->leftJoin('tra_product_seizure_applications as t22', 't1.application_code', 't22.application_code')
                ->leftJoin('wf_workflow_stages as t12', 't9.current_stage', 't12.id')
                ->leftJoin('tra_evaluation_recommendations as t13', function ($join) use ($workflow_stage) {
                    $join->on('t1.application_code', '=', 't13.application_code')
                        ->on('t12.stage_category_id', '=', 't13.stage_category_id')
                        ->where('t9.current_stage', $workflow_stage);
                })
                ->leftJoin('tra_approval_recommendations as t14', 't1.application_code', 't14.application_code')
                ->leftJoin('par_entity_types as t19', 't15.entity_type_id', 't19.id')
                // ->leftJoin('par_common_names as t23', 't15.common_name_id', 't23.id')
                //DB::raw("CONCAT(decrypt(t17.first_name,".getDecryptFunParams()."),' ',decrypt(t17.last_name,".getDecryptFunParams().")) 

                ->select(
                    't1.*',
                    't17.name as submitted_by',
                    't9.date_received as submitted_on',
                    't4.name as application_status',
                    't1.id as active_application_id',
                    't19.name as suspected_entity',
                    't15.premise_name as premise_name',
                    't15.brand_name as brand_name',
                    't15.suspect_address as suspect_address',
                    't18.investigation_decision_id as investigation_decision_id',
                    't20.released_confirmation_id as released_confirmation_id',
                    't22.seizure_confirmation_id as seizure_confirmation_id',
                    't21.exhibit_request_approval_id as exhibit_request_approval_id',
                    't16.name as nature_of_report',
                    't12.stage_category_id',
                    //'t13.stage_category_id',Job on 07/03/24
                    't13.id as recommendation_record_id',
                    't13.recommendation_id',
                    't13.remarks',
                    't14.decision_id as approval_decision_id',
                    DB::raw("CASE WHEN t15.fullnames is NULL THEN t15.applicant_name WHEN t15.applicant_name is NULL THEN t15.fullnames END reporter")
                )
                ->where(array('t9.current_stage' => $workflow_stage, 'isDone' => 0));
            //->where(array('t9.current_stage'=>$workflow_stage) );

            $results = $qry->orderBy('t9.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function onSaveInvestigationDecision(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $sub_module_id = $req->sub_module_id;
            $module_id = $req->module_id;
            $investigation_decision_id = $req->investigation_decision_id;
            $remarks = $req->remarks;
            $approval_id = $req->approval_id;
            $user_id = $this->user_id;
            $app_data = array(
                'module_id' => $req->module_id,
                'application_code' => $req->application_code,
                'investigation_decision_id' => $req->investigation_decision_id,
                'comment' => $req->remarks,
                'approved_by' => $req->user_id
            );
            if (validateIsNumeric($approval_id)) {
                $app_data = array(
                    'investigation_decision_id' => $req->investigation_decision_id,
                    'comment' => $req->remarks,
                    'approved_by' => $user_id
                );
                $where = array(
                    'application_code' => $application_code
                );
                $previous_data = getPreviousRecords('tra_investigation_approvals', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('tra_investigation_approvals', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('tra_investigation_approvals', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getEnforcementApplicationReferenceCodes($application_details)
    {
        $section_code = getSingleRecordColValue('par_sections', array('id' => $application_details->section_id), 'code');

        $codes_array = array(
            'section_code' => $section_code,
        );

        return $codes_array;
    }
    function saveUpdateSuspectedOffence(Request $request)
    {
        $id = $request->input('id');
        $data = $request->all();

        $user_id = $this->user_id;
        $table = $data['table_name'];
        unset($data['_token']);
        unset($data['table_name']);
        unset($data['id']);

        try {
            if (validateIsNumeric($id)) {
                $where_app = array('id' => $id);

                $previous_data = getPreviousRecords($table, $where_app);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                unset($data['id']);

                $data['altered_by'] = $user_id;
                $res = updateRecord($table, $previous_data, $where, $data, $user_id);
            } else {

                $data['created_on'] = Carbon::now();
                $data['created_by'] = $user_id;
                $res = insertRecord($table, $data, $user_id);
                if ($res['success'] == false) {
                    return \response()->json($res);
                }
            }

            $res = array(
                'success' => true,
                'message' => "Saved Successfully"
            );
        } catch (\Exception $exception) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }

        return \response()->json($res);
    }


    public function getSuspectedOffenceDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        try {
            $qry = DB::table('par_suspected_offence as t1')

                ->join('par_offence_types  as t2', 't1.offence_type_id', '=', 't2.id')
                ->select(DB::raw('t1.*,t2.name as offennce_type'))
                ->where('t1.application_id', $application_id);

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => returnMessage($results)
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

    function saveUpdateAnnualWorkplanDetails(Request $request)
    {
        $id = $request->input('id');
        $data = $request->all();

        $user_id = $this->user_id;
        $table = $data['table_name'];
        unset($data['_token']);
        unset($data['table_name']);
        unset($data['id']);


        try {
            if (validateIsNumeric($id)) {
                $where_app = array('id' => $id);

                $previous_data = getPreviousRecords($table, $where_app);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                unset($data['id']);

                $data['altered_by'] = $user_id;

                $res = updateRecord($table, $previous_data, $where_app, $data, $user_id);
            } else {

                $data['created_on'] = Carbon::now();
                $data['created_by'] = $user_id;
                $res = insertRecord($table, $data, $user_id);
                if ($res['success'] == false) {
                    return \response()->json($res);
                }
            }

            $res = array(
                'success' => true,
                'message' => "Saved Successfully"
            );
        } catch (\Exception $exception) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }

        return \response()->json($res);
    }
    public function getAnnualWorkplanDetails(Request $request)
    {
        // $application_id=$request->input('application_id');
        $annual_workplan_id = $request->input('annual_workplan_id');
        try {
            if ($annual_workplan_id != '') {
                $results = DB::table('par_annual_workplan_details as t1')
                    ->select('t1.id', 't1.name as workplan_name')
                    ->where('t1.id', $annual_workplan_id);

                $res = array(
                    'success' => true,
                    'results' => $results->get(),
                    'message' => 'All is well'
                );
            } else {
                $results = DB::table('par_annual_workplan_details as t1')
                    ->select('t1.*');

                $res = array(
                    'success' => true,
                    'results' => $results->get(),
                    'message' => 'All is well'
                );
            }
        } catch (\Exception $exception) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($throwable->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }


        return \response()->json($res);
    }

    function genericSaveUpdate(Request $request)
    {
        $id = $request->input('id');
        $data = $request->all();
        $user_id = $this->user_id;
        $table = $data['table_name'];
        unset($data['_token']);
        unset($data['table_name']);
        unset($data['id']);
        try {
            if (validateIsNumeric($id)) {
                $where_app = array('id' => $id);

                $previous_data = getPreviousRecords($table, $where_app);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                unset($data['id']);

                $data['altered_by'] = $user_id;
                $res = updateRecord($table, $previous_data, $where_app, $data, $user_id);
            } else {

                $data['created_on'] = Carbon::now();
                $data['created_by'] = $user_id;
                $res = insertRecord($table, $data, $user_id);
                if ($res['success'] == false) {
                    return \response()->json($res);
                }
            }

            $res = array(
                'success' => true,
                'message' => "Saved Successfully"
            );
        } catch (\Exception $exception) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }

        return \response()->json($res);
    }
    public function genericDeleteRecord(Request $req)
    {
        try {
            $record_id = $req->input('id');
            $table_name = $req->input('table_name');
            $user_id = \Auth::user()->id;
            $where = array(
                'id' => $record_id
            );

            if ($table_name == 'par_monitoring_product_information') {
                $previous_data = getPreviousRecords($table_name, $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }


                $data_previous = $previous_data['results'];
                $res = deleteRecord($table_name, $data_previous, $where, $user_id);
                if ($res['success'] == false) {
                    return $res;
                }

                //delete product in prescribing
                $product_id = $previous_data['results'][0]['id'];
                $where_product = array(
                    'product_id' => $product_id
                );
                $delete_product_prescribing = deleteRecord('par_prescribing_compliance_data', $data_previous, $where_product, $user_id);
                if ($delete_product_prescribing['success'] == false) {
                    return $delete_product_prescribing;
                }

                $res = array(
                    'success' => true,
                    'message' => "Deleted Successfully"
                );
            } else if ($table_name == 'par_monitoring_premise_personnel') {
                $previous_data = getPreviousRecords($table_name, $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $data_previous = $previous_data['results'];
                $res = deleteRecord($table_name, $data_previous, $where, $user_id);
                if ($res['success'] == false) {
                    return $res;
                }

                //delete personnel in dispensing
                $personnel_id = $previous_data['results'][0]['id'];
                $where_personnel = array(
                    'personnel_id' => $personnel_id
                );

                $delete_personnel_dispensing = deleteRecord('par_dispensing_compliance_data', $data_previous, $where_personnel, $user_id);
                if ($delete_personnel_dispensing['success'] == false) {
                    return $delete_personnel_dispensing;
                }

                $res = array(
                    'success' => true,
                    'message' => "Deleted Successfully"
                );
            } else {

                $previous_data = getPreviousRecords($table_name, $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = deleteRecord($table_name, $previous_data, $where, $user_id);

                $res = array(
                    'success' => true,
                    'message' => "Deleted Successfully"
                );
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return response()->json($res);
    }

    function savePrescribingComplianceInformation(Request $request)
    {
        dd('here');
        $id = $request->input('id');
        $data = $request->all();
        $user_id = $this->user_id;
        $table = $data['table_name'];
        unset($data['_token']);
        unset($data['table_name']);
        unset($data['id']);
        try {
            if (validateIsNumeric($id)) {
                $where_app = array('id' => $id);

                $previous_data = getPreviousRecords($table, $where_app);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                unset($data['id']);

                $data['altered_by'] = $user_id;

                $res = updateRecord($table, $previous_data, $where_app, $data, $user_id);
            } else {

                $data['created_on'] = Carbon::now();
                $data['created_by'] = $user_id;
                $res = insertRecord($table, $data, $user_id);
                if ($res['success'] == false) {
                    return \response()->json($res);
                }
            }

            $res = array(
                'success' => true,
                'message' => "Saved Successfully"
            );
        } catch (\Exception $exception) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }

        return \response()->json($res);
    }
    public function getEnforcementApplicationMoreDetails(Request $request)
    {
        $application_code = $request->input('application_code');

        try {
            $qry1 = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_enforcement_information as t2', 't1.enforcement_id', 't2.id')
                ->leftJoin('par_suspected_offence as t3', 't1.id', 't3.application_id')
                ->where(array('t1.application_code' => $application_code, 't1.sub_module_id' => 104))
                ->select('t1.*', 't2.*', 't3.*');
            $enforcement_details = $qry1->first();

            $qry2 = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_enforcement_information as t2', 't1.enforcement_id', 't2.id')
                ->leftJoin('par_suspected_offence as t3', 't1.id', 't3.application_id')
                ->where(array('t1.application_code' => $application_code, 't1.sub_module_id' => 105))
                ->select('t1.*', 't2.*', 't3.*');
            $investigation_details = $qry2->first();

            $qry3 = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_jointOperation_information as t2', 't1.joint_operation_id', 't2.id')
                ->leftJoin('par_joint_activities_details as t3', 't1.application_code', 't3.application_code')
                ->leftJoin('par_joint_logistics_details as t4', 't1.application_code', 't4.application_code')
                ->where(array('t1.application_code' => $application_code, 't1.sub_module_id' => 89))
                ->select('t1.*', 't2.*', 't2.scope', 't2.activity as activity', 't2.objective', 't2.external_operative', 't2.internal_operative', 't2.organizing_officer_title as title', 't2.organizing_officer as fullnames', 't4.*');

            $joint_Operation_details = $qry3->first();

            $res = array(
                'success' => true,
                'enforcement_details' => $enforcement_details,
                'investigation_details' => $investigation_details,
                'joint_Operation_details' => $joint_Operation_details,
                'branch_id' => 1,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMonitoringApplicationMoreDetails(Request $request)
    {
        $application_code = $request->input('application_code');

        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_monitoring_information as t2', 't1.enforcement_id', 't2.id')
                ->select('t1.*', 't2.*')
                ->where('t1.application_code', $application_code);

            $enforcement_details = $qry->first();

            $res = array(
                'success' => true,
                'enforcement_details' => $enforcement_details,
                'branch_id' => 1,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMonitoringComplianceData(Request $request)
    {
        $application_code = $request->input('application_code');

        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_prescribing_compliance_data as t2', 't1.application_code', 't2.application_code')
                ->leftJoin('par_dispensing_compliance_data as t3', 't1.application_code', 't3.application_code')
                ->select('t1.id as application_id', 't2.*', 't3.*')
                ->where('t1.application_code', $application_code);

            $enforcement_details = $qry->first();

            $res = array(
                'success' => true,
                'enforcement_details' => $enforcement_details,
                'branch_id' => 1,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getManagerInvestigationApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');

        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_system_statuses as t2', 't1.application_status_id', 't2.id')
                ->leftJoin('tra_submissions as t3', 't3.application_code', '=', 't1.application_code')
                ->leftJoin('tra_enforcement_information as t4', 't1.enforcement_id', '=', 't4.id')
                ->leftJoin('par_report_type as t5', 't4.report_type_id', '=', 't5.id')
                ->leftJoin('users as t6', 't3.usr_from', '=', 't6.id')
                ->leftJoin('users as t7', 't4.reported_by_id', '=', 't7.id')
                ->leftJoin('wf_workflow_stages as t8', 't3.current_stage', 't8.id')
                ->leftJoin('tra_investigation_approvals as t9', 't1.application_code', 't9.application_code')
                ->leftJoin('par_suspected_offence as t10', 't1.id', 't10.application_id')
                ->leftJoin('par_offence_types as t11', 't10.offence_type_id', 't11.id')
                ->leftJoin('wb_trader_account as t12', 't1.applicant_id', '=', 't12.id')
                ->leftJoin('par_entity_types as t13', 't4.entity_type_id', '=', 't13.id')
                ->select(
                    't1.*',
                    DB::raw("CONCAT_WS(' ',decrypt(t7.first_name),decrypt(t7.last_name)) as submitted_by"),
                    't3.date_received as submitted_on',
                    't2.name as application_status',
                    't1.id as active_application_id',
                    't5.name as nature_of_report',
                    't8.stage_category_id',
                    't11.name as offence_type',
                    't13.name as suspected_entity',
                    DB::raw("CASE WHEN t4.fullnames is NULL THEN t4.applicant_name WHEN t4.applicant_name is NULL THEN t4.fullnames END reporter")
                )
                ->where(array('t3.current_stage' => $workflow_stage, 'isDone' => 0, 't9.investigation_decision_id' => 1));



            $results = $qry->orderBy('t9.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getApprovedInvestigationApplications(Request $request)
    {
        $user_id = $this->user_id;
        $tracking_no = $request->input('tracking_no');
        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_system_statuses as t2', 't1.application_status_id', 't2.id')
                ->leftJoin('tra_submissions as t3', 't1.application_code', '=', 't3.application_code')
                ->leftJoin('tra_enforcement_information as t4', 't1.enforcement_id', '=', 't4.id')
                ->leftJoin('par_report_type as t5', 't4.report_type_id', '=', 't5.id')
                ->leftJoin('users as t6', 't3.usr_from', '=', 't6.id')
                ->leftJoin('users as t7', 't4.reported_by_id', '=', 't7.id')
                ->leftJoin('wf_workflow_stages as t8', 't3.current_stage', 't8.id')
                ->leftJoin('tra_investigation_approvals as t9', 't1.application_code', 't9.application_code')
                ->leftJoin('wb_trader_account as t10', 't1.applicant_id', '=', 't10.id')
                ->leftJoin('par_case_status as t11', 't1.case_status_id', '=', 't11.id')
                ->leftJoin('par_entity_types as t12', 't4.entity_type_id', '=', 't12.id')
                ->select(
                    't1.*',
                    DB::raw("CONCAT_WS(' ',decrypt(t7.first_name),decrypt(t7.last_name)) as submitted_by"),
                    't3.date_received as submitted_on',
                    't2.name as application_status',
                    't1.id as active_application_id',
                    't4.suspect_address as suspect_address',
                    't5.name as report_type',
                    't8.stage_category_id',
                    't12.name as suspected_entity',
                    DB::raw("CASE WHEN t4.fullnames is NULL THEN t4.applicant_name WHEN t4.applicant_name is NULL THEN t4.fullnames END reporter")
                )
                ->where('t1.sub_module_id', '!=', 105)
                //->where(array('t9.investigation_decision_id' => 1,'t3.usr_to' => $user_id,'isDone'=>0));
                ->where(array('t9.investigation_decision_id' => 1));
            if ($tracking_no != '') {
                $qry->where('t1.tracking_no', 'LIKE', '%' . $tracking_no . '%');
                $qry->orWhere('t1.reference_no', 'LIKE', '%' . $tracking_no . '%');
            }


            $results = $qry->orderBy('t3.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMonitoringComplianceApplications(Request $request)
    {
        // $user_id = $this->user_id;
        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_system_statuses as t2', 't1.application_status_id', 't2.id')
                ->leftJoin('tra_submissions as t3', 't1.application_code', '=', 't3.application_code')
                ->leftJoin('tra_enforcement_information as t4', 't1.enforcement_id', '=', 't4.id')
                ->leftJoin('par_report_type as t5', 't4.report_type_id', '=', 't5.id')
                ->leftJoin('users as t6', 't3.usr_from', '=', 't6.id')
                ->leftJoin('users as t7', 't4.reported_by_id', '=', 't7.id')
                ->leftJoin('wf_workflow_stages as t8', 't3.current_stage', 't8.id')
                ->leftJoin('tra_investigation_approvals as t9', 't1.application_code', 't9.application_code')
                ->leftJoin('wb_trader_account as t10', 't1.applicant_id', '=', 't10.id')
                ->leftJoin('par_case_status as t11', 't1.case_status_id', '=', 't11.id')
                ->leftJoin('par_entity_types as t12', 't4.entity_type_id', '=', 't12.id')
                ->leftJoin('tra_approval_recommendations as t13', 't1.application_code', '=', 't13.application_code')
                ->leftJoin('par_case_decisions as t14', 't13.decision_id', '=', 't14.id')
                ->select(
                    't1.*',
                    DB::raw("CONCAT_WS(' ',decrypt(t7.first_name),decrypt(t7.last_name)) as submitted_by"),
                    't3.date_received as submitted_on',
                    't4.fullnames as internal_reporter',
                    't2.name as application_status',
                    't1.id as active_application_id',
                    't4.suspect_address as suspect_address',
                    't5.name as report_type',
                    't8.stage_category_id',
                    't10.name as applicant_name',
                    't12.name as suspected_entity',
                    't13.approval_date as case_closed',
                    't14.id as decision_id'
                )
                ->where('t1.sub_module_id', '!=', 85)
                ->where('t1.sub_module_id', '!=', 88)
                ->where(array('t13.decision_id' => 2, 'isDone' => 0));

            $results = $qry->orderBy('t3.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function onApprovedInvestigationDetails(Request $req)
    {
        $enforcement_id = $req->input('enforcement_id');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->join('tra_enforcement_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftJoin('par_report_type as t4', 't2.report_type_id', '=', 't4.id')
                ->select(
                    't1.*',
                    't2.*',
                    't1.id as active_application_id',
                    't1.enforcement_id as enforcement_id',
                    //'t2.applicant_name as applicant_name',
                    //'t2.fullnames as applicant_name'
                    't4.name as report_type'
                )
                ->where('t2.id', $enforcement_id);

            // $qry1 = clone $main_qry;

            // $qry1->join('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
            //     ->leftJoin('par_report_type as t4', 't2.report_type_id', '=', 't4.id')
            //     ->select('t1.*','t2.*','t1.id as active_application_id','t2.suspect_country_id as country_id','t2.suspect_region_id as region_id',
            //     't2.suspect_district_id as district_id','t3.name as applicant_name','t4.name as report_type');

            $results = $main_qry->first();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function saveCaseCharges(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $application_id = $req->application_id;
            $enforcement_id = $req->enforcement_id;
            $Facts_alleged = $req->Facts_alleged;
            $charge_details = $req->charge_details;
            $charge_regulation = $req->charge_regulation;
            $charge_section = $req->charge_section;
            $charge_elements = $req->charge_elements;
            //$approval_id = $req->approval_id;
            $user_id = $this->user_id;
            $app_data = array(
                'enforcement_id' => $req->enforcement_id,
                'application_id' => $req->application_id,
                'Facts_alleged' => $req->Facts_alleged,
                'charge_details' => $req->charge_details,
                'charge_regulation' => $req->charge_regulation,
                'charge_section' => $req->charge_section,
                'charge_elements' => $req->charge_elements,
                'charged_by' => $req->user_id
            );
            // if(validateIsNumeric($approval_id)){
            //     $app_data = array(
            //         'investigation_decision_id' => $req->investigation_decision_id,
            //         'comment' => $req->remarks,
            //         'approved_by' => $user_id
            //     );
            //     $where = array(
            //         'application_code'=>$application_code
            //     );
            //     $res = updateRecord('tra_investigation_approvals', $where, $app_data);
            // }else{
            //     $res = insertRecord('par_case_charges', $app_data);
            // }
            $res = insertRecord('par_case_charges', $app_data);
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveInvetsigationReceivingDetails(Request $request, $inCall = 0)
    {
        $active_application_id = $request->input('active_application_id');
        $process_id = $request->input('process_id');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $section_id = $request->input('section_id');
        $module_id = $request->input('module_id');
        $sub_module_id = $request->input('sub_module_id');
        $applicant_id = $request->input('applicant_id');
        $suspect_name = $request->input('suspect_name');
        $suspect_omang = $request->input('suspect_omang');
        $suspect_telephone = $request->input('suspect_telephone');
        $suspect_address = $request->input('suspect_address');
        $user_id = $this->user_id;
        $report_type_id = $request->input('report_type_id');
        $init_enforcement_id = $request->input('enforcement_id');
        $ref_no = $request->input('reference_no');
        $date = \Carbon\Carbon::parse($request->input('date'));
        $suspect_name = $request->input('suspect_name');
        $suspect_omang = $request->input('suspect_omang');
        $suspect_telephone = $request->input('suspect_telephone');
        $suspect_address = $request->input('suspect_address');
        $suspect_occupation = $request->input('suspect_occupation');
        $place_of_offence = $request->input('place_of_offence');
        $car_reg_no = $request->input('car_reg_no');
        $entity_type_id = $request->input('entity_type_id');
        $brand_name = $request->input('brand_name');
        $common_name = $request->input('common_name');
        $product_type = $request->input('product_type');
        $prodclass_category_id = $request->input('prodclass_category_id');
        $premise_name = $request->input('premise_name');
        $premise_type = $request->input('premise_type');
        $country_id = $request->input('country_id');
        $region_id = $request->input('region_id');
        $district_id = $request->input('district_id');
        $suspect_physical_address = $request->input('suspect_physical_address');
        $suspect_postal_address = $request->input('suspect_postal_address');
        $suspect_telephone = $request->input('suspect_telephone');
        $other_details = $request->input('other_details');
        $enforcement_data = array(
            'report_type_id' => $request->report_type_id,
            'section_id' => $request->section_id,
            //internal
            'reported_by_id' => $request->reported_by_id,
            'department' => $request->department,
            'reporter_designation' => $request->reporter_designation,
            'fullnames' => $request->fullnames,
            'department_name' => $request->department_name,
            'approved_by_id' => $request->approved_by_id,
            'approver_designation' => $request->approver_designation,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'age' => $request->age,
            'country' => $request->country,
            'date' => $request->date,
            'id_no' => $request->id_no,
            //external
            'applicant_name' => $request->applicant_name,
            'captured_by_department' => $request->captured_by_department,
            'country_name' => $request->country_name,
            'app_physical_address' => $request->app_physical_address,
            'app_email' => $request->app_email,
            'complainant_gender' => $request->complainant_gender,
            'app_telephone' => $request->app_telephone,
            'complainant_age' => $request->complainant_age,
            'applicant_id' => $applicant_id,
            //suspect info
            'suspect_name' => $suspect_name,
            'suspect_omang' => $suspect_omang,
            'suspect_address' => $suspect_address,
            'suspect_occupation' => $suspect_occupation,
            'place_of_offence' => $place_of_offence,
            'car_reg_no' => $car_reg_no,
            'entity_type_id' => $entity_type_id,
            'brand_name' => $brand_name,
            'common_name' => $common_name,
            'product_type' => $request->product_type,
            'prodclass_category_id' => $prodclass_category_id,
            'premise_name' => $premise_name,
            'premise_type' => $premise_type,
            'suspect_country_id' => $country_id,
            'suspect_region_id' => $region_id,
            'suspect_district_id' => $district_id,
            'suspect_physical_address' => $suspect_physical_address,
            'suspect_postal_address' => $suspect_postal_address,
            'suspect_telephone' => $suspect_telephone,
            'other_details' => $other_details,
            'batch_number' => $request->batch_number,
            'expiry_date' => $request->expiry_date,
        );

        try {
            DB::beginTransaction();
            $applications_table = 'tra_enforcement_applications';
            $enforcement_table = 'tra_enforcement_information';

            $where_enforcement = array(
                'id' => $init_enforcement_id
            );


            //Edit enforcement Application
            if (isset($active_application_id) && $active_application_id != "") {
                $enforcement_id = $init_enforcement_id;
                $where_app = array(
                    'id' => $active_application_id
                );
                $application_params = array(
                    'applicant_id' => $applicant_id
                );
                $app_details = array();
                if (recordExists($applications_table, $where_app)) {
                    $app_details = getPreviousRecords($applications_table, $where_app);
                    if ($app_details['success'] == false) {
                        DB::rollBack();
                        // return $app_details;
                        return "Failed to search application details";
                    }
                    $app_details = $app_details['results'];
                    $app_res = updateRecord($applications_table, $app_details, $where_app, $application_params, $user_id);

                    if ($app_res['success'] == false) {
                        DB::rollBack();
                        // return $app_res;
                        return "Failed to update application detais";
                    }
                }


                $application_code = $app_details[0]['application_code']; //$app_details->application_code;
                $tracking_no = $app_details[0]['tracking_no'];
                $reference_no = $app_details[0]['reference_no'];

                // EDIT
                if (recordExists($enforcement_table, $where_enforcement)) {
                    $enforcement_data['dola'] = Carbon::now();
                    $enforcement_data['altered_by'] = $user_id;

                    $previous_data = getPreviousRecords($enforcement_table, $where_enforcement);
                    if ($previous_data['success'] == false) {
                        DB::rollBack();
                        // return $previous_data;
                        return "Failed to fetch enforcement details";
                    }
                    $previous_data = $previous_data['results'];


                    $res = updateRecord($enforcement_table, $previous_data, $where_enforcement, $enforcement_data, $user_id);

                    unset($enforcement_data['created_by']);
                    unset($enforcement_data['created_on']);
                    unset($enforcement_data['dola']);
                    unset($enforcement_data['altered_by']);

                    $enforcement_data['mis_dola'] = Carbon::now();
                    $enforcement_data['mis_altered_by'] = $user_id;
                    // initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no.rand(10,100), $user_id);
                    $res['active_application_id'] = $active_application_id;
                    $res['active_application_code'] = $application_code;
                    $res['enforcement_id'] = $enforcement_id;
                    $res['reference_no'] = $reference_no;
                    $res['tracking_no'] = $tracking_no;
                    // DB::commit();
                    // initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no, $user_id);

                } else {
                    DB::rollBack();
                    $res = ['success' => false, 'message' => 'Failed to find enforcement details'];
                }
            } else {
                //Insert

                $anyOngoingApps = checkForOngoingApplications($init_enforcement_id, $applications_table, 'enforcement_id', $process_id);
                if ($anyOngoingApps['exists'] == true) {
                    DB::rollBack();
                    $res = array(
                        'success' => false,
                        'message' => 'There is an applicaiton ongoing for the selected premise of Tracking Number ' . $anyOngoingApps['tracking_no'] . ' and Reference Number ' . $anyOngoingApps['ref_no']
                    );
                    return \response()->json($res);
                }

                $init_enforcement_params = getTableData($enforcement_table, $where_enforcement);


                //dd($init_enforcement_params);
                if (is_null($init_enforcement_params)) {
                    DB::rollBack();
                    $res = array(
                        'success' => false,
                        'message' => 'Problem encountered while fetching target enforcement details!!'
                    );
                    return \response()->json($res);
                }
                $enforcement_data = $init_enforcement_params;
                $enforcement_data = convertStdClassObjToArray($enforcement_data);
                $enforcement_data['init_enforcement_id'] = $init_enforcement_id;
                $enforcement_data['created_on'] = Carbon::now();
                $enforcement_data['created_by'] = $user_id;


                unset($enforcement_data['id']);

                $enforcement_res = insertRecord($enforcement_table, $enforcement_data, $user_id);
                //dd($enforcement_res);
                if ($enforcement_res['success'] == false) {
                    DB::rollBack();
                    return \response()->json("failed to save enforcement details");
                    exit();
                }

                //tracking the application
                $enforcement_id = $enforcement_res['record_id'];


                //Application_create
                $view_id = generateApplicationViewID();
                $codes_array =  $this->getEnforcementApplicationReferenceCodes($request);
                $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, 1, $user_id);
                $application_code = generateApplicationCode($sub_module_id, $applications_table);
                $application_status = getApplicationInitialStatus($module_id, $sub_module_id);

                if ($tracking_details['success'] == false) {
                    DB::rollBack();
                    return \response()->json($tracking_details);
                }


                $init_enforcement = DB::table('tra_enforcement_applications as t1')
                    ->select(DB::raw("t1.*"))
                    ->where('t1.enforcement_id', $init_enforcement_id)
                    ->get();
                $init_enforcement = convertStdClassObjToArray($init_enforcement);

                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'module_id' => $module_id, 'reference_type_id' => 1), 'reference_format_id');

                //  $where_statement = array('sub_module_id' => 85, 't1.enforcement_id' => $enforcement_id);
                $where_statement = array('t1.enforcement_id' => $enforcement_id);

                //$primary_reference_no = getProductPrimaryReferenceNo($where_statement, 'tra_enforcement_applications');

                $primary_reference_no  =  DB::table('tra_enforcement_applications as t1')

                    ->where($where_statement)->first();
                //->value('reference_no');



                $codes_array = array(
                    'ref_no' => $primary_reference_no
                );
                $ref_number = generateProductsSubRefNumber(null, $applications_table, $ref_id, $codes_array, $sub_module_id, $user_id);

                if (!validateIsNumeric($ref_id)) {
                    return \response()->json(array('success' => false, 'message' => 'Reference No Format has not been set, contact the system administrator'));
                } else if ($ref_number == '') {
                    return \response()->json(array('success' => false, 'tracking_no' => $ref_number, 'message' => $ref_number));
                }
                $tracking_no = $tracking_details['tracking_no'];
                $reference_no = $init_enforcement[0]['reference_no'];
                $application_id = $init_enforcement[0]['id'];


                $application_params = array(
                    'view_id' => $view_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_code' => $application_code,
                    'applicant_id' => $applicant_id,
                    'process_id' => $process_id,
                    'workflow_stage_id' => $workflow_stage_id,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'enforcement_id' => $enforcement_id,
                    'application_status_id' => $application_status->status_id,
                    'case_status_id' => 1,
                    "case_opened" => Carbon::now(),
                    "date_added" => Carbon::now(),
                    "created_by" => \Auth::user()->id,
                    "created_on" => Carbon::now()
                );

                $where_app = array(
                    'id' => $application_id
                );
                $prev_data = getPreviousRecords($applications_table, $where_app);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];
                $update_res = updateRecord($applications_table, $previous_data, $where_app, $application_params, $user_id);


                // $res = insertRecord($applications_table, $application_params, $user_id);

                if (!isset($update_res['record_id'])) {
                    DB::rollBack();
                    return $update_res;
                }
                $active_application_id = $update_res['record_id'];

                // createInitialRegistrationRecord('tra_enforcement_applications', $applications_table, $reg_params, $application_id, 'reg_premise_id');
                //DMS
                // initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no, $user_id);
                // add to submissions table
                $submission_params = array(
                    'application_id' => $active_application_id,
                    'view_id' => $view_id,
                    'process_id' => $process_id,
                    'application_code' => $application_code,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'usr_from' => $user_id,
                    'usr_to' => $user_id,
                    'previous_stage' => $workflow_stage_id,
                    'current_stage' => $workflow_stage_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_status_id' => $application_status->status_id,
                    'urgency' => 1,
                    'remarks' => 'Initial save of the application',
                    'date_received' => Carbon::now(),
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );

                DB::table('tra_submissions')
                    ->insert($submission_params);

                $product_params = array(
                    'application_id' => $active_application_id,
                    'application_code' => $application_code,
                    'tracking_no' => $tracking_no,
                    'batch_number' => $request->batch_number,
                    'expiry_date' => $request->expiry_date,
                    'entity_type_id' => $entity_type_id,
                    'brand_name' => $brand_name,
                    'common_name' => $common_name,
                    'product_type' => $request->product_type,
                    'prodclass_category_id' => $prodclass_category_id,
                    'premise_name' => $premise_name,
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );
                DB::table('tra_reported_products')
                    ->insert($product_params);
            }

            $res['active_application_id'] = $active_application_id;
            // $res['process_id'] = $process_id;
            $res['application_code'] = $application_code;
            $res['tracking_no'] = $tracking_no;
            $res['reference_no'] = $reference_no;
            $res['enforcement_id'] = $enforcement_id;
            $res['msg'] = 'Record Saved Successfully';
            $res['success'] = true;
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $res = array("line" => $exception->getLine(), "message" => $exception->getMessage());
            //$res = sys_error_handler($exception->getMessage(),  2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        if ($inCall == 1) {
            return $res;
        }
        return \response()->json($res);
    }
    public function getWorkplanApplications(Request $request)
    {
        $table_name = 'tra_enforcement_applications';
        $workflow_stage = $request->input('workflow_stage_id');
        $workplan_id = $request->input('workplan_id');

        try {
            $qry = DB::table($table_name . ' as t1')
                ->join('tra_enforcement_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                ->leftJoin('par_system_statuses as t4', 't1.application_status_id', '=', 't4.id')
                ->leftJoin('tra_submissions as t5', 't1.application_code', '=', 't5.application_code')
                ->leftJoin('par_report_type as t6', 't2.report_type_id', '=', 't6.id')
                // ->leftJoin('tra_enforcement_workplan_details as t7', function ($join) use ($workplan_id) {
                //     $join->on('t1.application_code', '=', 't7.application_code')
                //         ->where('t7.id', $workplan_id);
                // })
                ->leftJoin('tra_enforcement_workplan_details as t7', 't1.application_code', '=', 't7.id')
                // ->leftJoin('par_premises_types as t10', 't2.premise_type_id', '=', 't10.id')
                // ->whereNotIn('t1.application_code', function ($query) use ($table_name, $workflow_stage, $inspection_id) {
                //     $query->select(DB::raw('t8.application_code'))
                //         ->from('tra_premiseinspection_applications as t8')
                //         ->join($table_name . ' as t9', 't8.application_code', '=', 't9.application_code')
                //         ->join('tra_premise_inspection_details as t10', 't8.inspection_id', '=', 't10.id')
                //         ->where('t9.workflow_stage_id', $workflow_stage)
                //         ->where('t8.inspection_id', '<>', $inspection_id)
                //         ->where('t10.status', 1);
                // })
                ->select(
                    't1.*',
                    't1.id as active_application_id',
                    't1.report_type_id',
                    't2.applicant_name',
                    't2.app_email',
                    't2.app_physical_address',
                    't6.name as report_type',
                    't4.name as application_status',
                    't7.id as workplan_id'
                )
                ->where(array('t5.current_stage' => $workflow_stage, 'isDone' => 0));
            $results = $qry->get();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getCaseCharges(Request $request)
    {
        $user_id = $this->user_id;

        try {
            $qry = DB::table('par_case_charges as t1')
                ->leftJoin('tra_enforcement_applications as t2', 't1.application_id', '=', 't2.id')
                ->leftJoin('users as t3', 't1.created_by', '=', 't3.id')
                ->select('t1.*', DB::raw("CONCAT_WS(' ',decrypt(t3.first_name),decrypt(t3.last_name)) as charged_by"));


            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveCaseWitnessDetails(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $application_id = $req->application_id;
            $enforcement_id = $req->enforcement_id;
            $current_source = $req->current_source;
            $current_information = $req->current_information;
            $current_relevance = $req->current_relevance;
            $witness_name = $req->witness_name;
            $witness_information = $req->witness_information;
            $witness_relevance = $req->witness_relevance;
            $inquiry_name = $req->inquiry_name;
            $inquiry_information = $req->inquiry_information;
            $inquiry_relevance = $req->inquiry_relevance;
            $active_application_code = $req->active_application_code;
            $offence_id = $req->offence_id;
            $record_id = $req->id;
            $user_id = $this->user_id;
            $app_data = array(
                'enforcement_id' => $enforcement_id,
                'application_id' => $application_id,
                'current_source' => $current_source,
                'current_information' => $current_information,
                'current_relevance' => $current_relevance,
                'witness_name' => $witness_name,
                'witness_information' => $witness_information,
                'witness_relevance' => $witness_relevance,
                'inquiry_name' => $inquiry_name,
                'inquiry_information' => $inquiry_information,
                'inquiry_relevance' => $inquiry_relevance,
                'active_application_code' => $active_application_code,
                'offence_id' => $offence_id,
            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );

                $previous_data = getPreviousRecords('par_case_witness_details', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_case_witness_details', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_case_witness_details', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveWorkplanApplicationDetails(Request $request)
    {

        $workpan_id = $request->input('workpan_id');
        $matter_name = $request->input('matter_name');
        $matter_details = $request->input('matter_details');
        $investigation_subject = $request->input('investigation_subject');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $investigation_type = $request->input('investigation_type');
        $description = $request->input('remarks');
        $selected = $request->input('selected');
        $selected_codes = json_decode($selected);
        $user_id = $this->user_id;

        try {
            foreach ($selected_codes as $selected_code) {
                $application_code = $selected_code->application_code;
                $enforcement_id = $selected_code->enforcement_id;
            }
            $params = array(
                'enforcement_id' => $enforcement_id,
                'application_code' => $application_code,
                'created_by' => $this->user_id,
                'matter_name' => $matter_name,
                'matter_details' => $matter_details,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'investigation_subject' => $investigation_subject,
                'investigation_type' => $investigation_type,
                'description' => $description
            );

            if (isset($workpan_id) && $workpan_id != '') {
                $params['altered_by'] = $user_id;
                DB::table('tra_enforcement_workplan_details')
                    ->where('id', $workpan_id)
                    ->update($params);
                DD($params);
            } else {
                $params['created_by'] = $user_id;

                $insert_res = insertRecord('tra_enforcement_workplan_details', $params, $user_id);
                $workpan_id = $insert_res['record_id'];
            }
            $res = array(
                'success' => true,
                'workpan_id' => $workpan_id,
                'message' => 'Details saved successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getCaseWitnessDetails(Request $request)
    {
        $user_id = $this->user_id;
        $application_id = $request->input('application_id');
        $application_code = $request->input('active_application_code');
        try {
            $qry = DB::table('par_case_witness_details as t1')
                ->leftJoin('tra_enforcement_applications as t2', 't1.application_id', '=', 't2.id')
                ->leftJoin('par_suspected_offence as t3', 't1.offence_id', '=', 't3.id')
                ->leftJoin('par_offence_types as t4', 't3.offence_type_id', '=', 't4.id')
                ->leftJoin('users as t5', 't1.created_by', '=', 't5.id')
                ->select('t1.*', 't4.name as offence_charged', DB::raw("CONCAT_WS(' ',decrypt(t5.first_name),decrypt(t5.last_name)) as charged_by"))
                ->where('t1.active_application_code', $application_code);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveInvestigationDairy(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $application_id = $req->application_id;
            $active_application_code = $req->active_application_code;
            $enforcement_id = $req->enforcement_id;
            $offence_id = $req->offence_id;
            $current_source = $req->current_source;
            $current_information = $req->current_information;
            $current_relevance = $req->current_relevance;
            $witness_name = $req->witness_name;
            $witness_information = $req->witness_information;
            $witness_relevance = $req->witness_relevance;
            $inquiry_name = $req->inquiry_name;
            $inquiry_information = $req->inquiry_information;
            $inquiry_relevance = $req->inquiry_relevance;
            $Facts_alleged = $req->Facts_alleged;
            $charge_details = $req->charge_details;
            $charge_regulation = $req->charge_regulation;
            $charge_section = $req->charge_section;
            $charge_elements = $req->charge_elements;
            $record_id = $req->id;
            $date = $req->date;
            $action = $req->action;
            $action_duration = $req->action_duration;
            $remarks = $req->remarks;
            $user_id = $this->user_id;
            //$approval_id = $req->approval_id;    
            $app_data = array(
                'enforcement_id' => $enforcement_id,
                'application_id' => $application_id,
                'active_application_code' => $active_application_code,
                'current_source' => $current_source,
                'current_information' => $current_information,
                'current_relevance' => $current_relevance,
                'witness_name' => $witness_name,
                'witness_information' => $witness_information,
                'witness_relevance' => $witness_relevance,
                'inquiry_name' => $inquiry_name,
                'inquiry_information' => $inquiry_information,
                'inquiry_relevance' => $inquiry_relevance,
                'date' => $date,
                'action' => $action,
                'action_duration' => $action_duration,
                'remarks' => $remarks,
                'Facts_alleged' => $req->Facts_alleged,
                'charge_details' => $req->charge_details,
                'charge_regulation' => $req->charge_regulation,
                'charge_section' => $req->charge_section,
                'charge_elements' => $req->charge_elements,
                'charged_by' => $req->user_id,
                'offence_id' => $offence_id,
                'offennce_type' => $req->offennce_type
            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );
                $previous_data = getPreviousRecords('par_investigation_diary', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_investigation_diary', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_investigation_diary', $app_data);
            }
            // $res = insertRecord('par_investigation_diary', $app_data);
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getInvestigationDiaryDetails(Request $request)
    {
        $user_id = $this->user_id;
        $application_id = $request->input('application_id');
        $active_application_code = $request->input('active_application_code');
        try {
            $qry = DB::table('par_investigation_diary as t1')
                ->leftJoin('tra_enforcement_applications as t2', 't1.active_application_code', '=', 't2.application_code')
                ->leftJoin('par_system_statuses as t4', 't2.application_status_id', 't4.id')
                ->leftJoin('par_suspected_offence as t5', 't1.offence_id', 't5.id')
                ->leftJoin('par_offence_types as t6', 't5.offence_type_id', 't6.id')
                ->leftJoin('users as t3', 't1.created_by', '=', 't3.id')
                ->select('t1.*', 't4.name as application_status', 't6.name as offence_charged', DB::raw(" CONCAT_WS(' ',decrypt(t3.first_name),decrypt(t3.last_name)) as officer"))
                ->where('t1.active_application_code', $active_application_code);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function getApprovedApplication(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        $table_name = getTableName($module_id);

        try {
            $qry = DB::table($table_name . ' as t1')
                ->leftJoin('tra_enforcement_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftJoin('par_report_type as t3', 't2.report_type_id', '=', 't3.id')
                ->leftJoin('par_system_statuses as t4', 't1.application_status_id', 't4.id')
                ->leftJoin('tra_submissions as t5', 't1.application_code', '=', 't5.application_code')
                ->leftJoin('wb_trader_account as t6', 't2.applicant_id', '=', 't6.id')
                ->leftJoin('wf_workflow_stages as t7', 't5.current_stage', 't7.id')
                ->leftJoin('tra_approval_recommendations as t8', 't8.application_code', '=', 't1.application_code')
                ->leftJoin('tra_case_recommendations as t9', 't1.application_code', '=', 't9.application_code')
                // ->leftJoin('par_approval_decisions as t10', 't8.decision_id', '=', 't10.id')
                ->leftJoin('par_case_decisions as t11', 't8.decision_id', '=', 't11.id')
                ->leftJoin('par_entity_types as t12', 't2.entity_type_id', 't12.id')
                ->leftJoin('tra_manager_approved_reports as t14', 't1.application_code', 't14.application_code')
                ->select(
                    't1.*',
                    't1.id as active_application_id',
                    't2.fullnames as internal_reporter',
                    't2.suspect_address',
                    't3.name as nature_of_report',
                    't4.name as application_status',
                    't4.name as application_status',
                    't5.date_received as submitted_on',
                    't5.date_received as submitted_on',
                    't6.name as applicant_name',
                    't8.decision_id',
                    't14.manager_approval_id as manager_approval_id',
                    't7.stage_category_id',
                    't12.name as suspected_entity'
                )
                ->where(array('t5.current_stage' => $workflow_stage, 'isDone' => 0));

            $results = $qry->orderBy('t5.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveTCMeetingDetails(Request $request)
    {
        $id = $request->input('id');
        $application_code = $request->input('application_code');
        $meeting_name = $request->input('meeting_name');
        $meeting_desc = $request->input('meeting_desc');
        $date_requested = $request->input('date_requested');

        $workflow_stage_id = $request->input('workflow_stage_id');

        $meeting_time = $request->input('meeting_time');

        $meeting_venue = $request->input('meeting_venue');

        $module_id = $request->input('module_id');

        $sub_module_id = $request->input('sub_module_id');

        $section_id = $request->input('section_id');
        $selected = $request->input('selected');
        $selected_codes = json_decode($selected);
        $user_id = $this->user_id;
        if (!validateIsNumeric($workflow_stage_id)) {
            return array('success' => false, 'message' => 'No workflow information was shared');
        }
        $stage_data = getTableData('wf_workflow_stages', array('id' => $workflow_stage_id));
        $stage_category_id = $stage_data->stage_category_id;
        try {
            DB::beginTransaction();
            $params = array(
                'meeting_name' => $meeting_name,
                'meeting_desc' => $meeting_desc,
                'meeting_time' => $meeting_time,
                'meeting_venue' => $meeting_venue,
                'module_id' => $module_id,
                'sub_module_id' => $sub_module_id,
                'meeting_venue' => $meeting_venue,
                'date_requested' => $date_requested

            );
            if (validateIsNumeric($id)) {
                $params['altered_by'] = $user_id;
                // DB::table('tc_meeting_details')
                //     ->where('id', $id)
                //     ->update($params);  
                // updateRecord('tc_meeting_details', ['id' => $id], $params);


                $previous_data = getPreviousRecords('tc_meeting_details', ['id' => $id]);
                // if ($previous_data['success'] == false) {
                //     return $previous_data;
                // }
                $previous_data = $previous_data['results'];
                $res = updateRecord('tc_meeting_details', $previous_data, ['id' => $id], $params, $user_id);
            } else {
                $params['created_by'] = $user_id;
                $insert_res = insertRecord('tc_meeting_details', $params, $user_id);
                if (!isset($insert_res['success']) && $insert_res['success'] == false) {
                    DB::rollback();
                    return $insert_res;
                }
                $id = $insert_res['record_id'];
                // $app_meeting = array(
                //     'application_code' => $application_code,
                //     'meeting_id' => $id,
                //     'stage_category_id' => $stage_category_id,
                //     'created_by' => $user_id
                // );
                // $meet_res = insertRecord('tc_meeting_applications', $app_meeting, $user_id);
                // if(!isset($meet_res['success']) && $meet_res['success'] == false){
                //     DB::rollback();
                //     return $meet_res;
                // }

            }
            $params2 = array();
            foreach ($selected_codes as $selected_code) {
                $params2[] = array(
                    'meeting_id' => $id,
                    'application_code' => $selected_code,
                    'stage_category_id' => $stage_category_id,
                    'created_by' => $this->user_id
                );

                // DB::table('tc_meeting_applications')
                //     ->where('meeting_id', $id)
                //     ->delete();
                // $res =  insertRecord('tc_meeting_applications', $params2);

            }
            DB::table('tc_meeting_applications')
                ->where('meeting_id', $id)
                ->delete();
            // DB::table('tc_meeting_applications')
            //     ->insert($params2);
            insertMultipleRecords('tc_meeting_applications', $params2); //Job on 12/03/24
            //insertRecord('tc_meeting_applications', $params2);

            $res = array(
                'success' => true,
                'record_id' => $id,
                'message' => 'Details saved successfully!!'
            );
            DB::commit();
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareEnforcementRegMeetingStage(Request $request)
    {
        $application_id = $request->input('application_id');
        $meeting_id = $request->input('meeting_id');
        $application_code = $request->input('application_code');
        $module_id = $request->input('module_id');
        $table_name = $request->input('table_name');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $stage_category_id = '';
        if (validateIsNumeric($module_id)) {
            $table_name = getSingleRecordColValue('par_modules', array('id' => $module_id), 'tablename');
        }
        if (validateIsNumeric($workflow_stage_id)) {
            $stage_data = getTableData('wf_workflow_stages', array('id' => $workflow_stage_id));
            $stage_category_id = $stage_data->stage_category_id;
        }


        try {
            if (validateIsNumeric($meeting_id)) {
                $qry = DB::table('tc_meeting_details as t3')
                    ->select(DB::raw("t3.*"));
                $qry->where('t3.id', $meeting_id);
            } else {
                $qry = DB::table($table_name . ' as t1')
                    ->leftjoin('tc_meeting_applications as t2', function ($join) use ($application_code) {
                        $join->on('t1.application_code', '=', 't2.application_code');
                    })
                    ->join('tc_meeting_details as t3', 't2.meeting_id', '=', 't3.id')
                    ->select(DB::raw("t3.*"));
                $qry->where('t1.id', $application_id);
            }
            if ($stage_category_id == 6 || $stage_category_id == 7) {
                $qry->whereRaw("(stage_category_id = 6 OR stage_category_id = 7)");
            }
            if ($stage_category_id == 9) {
                $qry->whereRaw("(stage_category_id = 9)");
            }
            $results = $qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function saveCaseDecisions(Request $req)
    {
        try {
            $user_id = \Auth::user()->id;
            $post_data = $req->all();
            $table_name = 'tra_case_recommendations';
            $id = $post_data['id'];
            $workflow_stage_id = $post_data['workflow_stage_id'];
            //unset unnecessary values
            unset($post_data['_token']);
            unset($post_data['table_name']);
            unset($post_data['model']);
            unset($post_data['id']);
            unset($post_data['unset_data']);
            $unsetData = $req->input('unset_data');
            if (isset($unsetData)) {
                $unsetData = explode(",", $unsetData);
                $post_data = unsetArrayData($post_data, $unsetData);
            }

            $table_data = $post_data;
            //add extra params
            $table_data['created_on'] = Carbon::now();
            $table_data['created_by'] = $user_id;
            $where = array(
                'id' => $id
            );
            $res = array();
            //check stage category
            if (!validateIsNumeric($workflow_stage_id)) {
                return array('success' => false, 'message' => "Faild to fetch stage details");
            }
            $stage_data = getTableData('wf_workflow_stages', array('id' => $workflow_stage_id));
            $stage_category_id = $stage_data->stage_category_id;
            $table_data['stage_category_id'] = $stage_category_id;
            if (isset($id) && $id != "") {
                if (recordExists($table_name, $where)) {
                    unset($table_data['created_on']);
                    unset($table_data['created_by']);
                    $table_data['dola'] = Carbon::now();
                    $table_data['altered_by'] = $user_id;
                    $previous_data = getPreviousRecords($table_name, $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res = updateRecord($table_name, $previous_data, $where, $table_data, $user_id);
                }
            } else {
                $res = insertRecord($table_name, $table_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return response()->json($res);
    }
    public function getCaseComments(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('active_application_code');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $comment_type_id = $request->input('comment_type_id');
        $user_id = $this->user_id;

        try {
            $qry = DB::table('tra_case_recommendations as t1')
                ->leftJoin('users as t2', 't1.created_by', '=', 't2.id')
                ->leftJoin('wf_workflow_stages as t3', 't1.workflow_stage_id', '=', 't3.id')
                ->leftJoin('par_recommendations as t4', 't1.recommendation_id', '=', 't4.id')
                ->leftJoin('par_case_decisions as t5', 't1.case_decision_id', '=', 't5.id')
                ->where('t1.application_code', $application_code)
                ->select(DB::raw("t1.*,CONCAT_WS(' ',decrypt(t2.first_name),decrypt(t2.last_name)) as author,t3.name as stage_name, t5.name as decision,t4.name as recommendation,t2.id as author_id"));

            // ->select(DB::raw("t1.*,CONCAT_WS(' ',decrypt(t2.first_name),decrypt(t2.last_name)) as author,t3.name as stage_name, t5.name as decision,t4.name as recommendation,t2.id as author_id,$user_id as current_user"));

            if (isset($workflow_stage_id) && $workflow_stage_id != '') {
                //get stage category
                $stage_data = getTableData('wf_workflow_stages', array('id' => $workflow_stage_id));
                $stage_category_id = $stage_data->stage_category_id;
                $qry->where('t1.stage_category_id', $stage_category_id);
            }
            if (isset($comment_type_id) && $comment_type_id != '') {
                //  $qry->where('t1.comment_type_id', $comment_type_id);
            }
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getRegisteredProduct(Request $req)
    {
        $reg_product_id = $req->input('reg_product_id');
        $tra_product_id = $req->input('tra_product_id');
        try {
            if ($tra_product_id != '') {
                $qry = DB::table('tra_product_applications as t1')
                    ->join('tra_product_information as t2', 't1.product_id', '=', 't2.id')
                    ->leftJoin('par_common_names as t3', 't2.common_name_id', '=', 't3.id')
                    ->leftJoin('tra_approval_recommendations as t4', 't1.application_code', '=', 't4.application_code')
                    ->select(DB::raw("t2.brand_name, t3.name as common_name,t4.certificate_no"));
                $qry->where('t2.id', $tra_product_id);
                $results = $qry->get();
                $res = array(
                    'success' => true,
                    'results' => $results,
                    'message' => 'All is well'
                );
            } else {
                $qry = DB::table('tra_product_applications as t1')
                    ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                    ->join('tra_product_information as t7', 't1.product_id', '=', 't7.id')
                    ->leftJoin('par_common_names as t8', 't7.common_name_id', '=', 't8.id')
                    ->leftJoin('wb_trader_account as t9', 't1.local_agent_id', '=', 't9.id')
                    ->leftJoin('par_classifications as t10', 't7.classification_id', '=', 't10.id')
                    ->leftJoin('tra_approval_recommendations as t11', 't1.application_code', '=', 't11.application_code')
                    ->join('tra_registered_products as t12', 't12.tra_product_id', '=', 't7.id')
                    ->leftJoin('par_validity_statuses as t4', 't12.validity_status_id', '=', 't4.id')
                    ->leftJoin('tra_product_manufacturers as t14', function ($join) {
                        $join->on('t7.id', '=', 't14.product_id')
                            ->on('t14.manufacturer_type_id', '=', DB::raw(1));
                    })
                    ->leftJoin('par_dosage_forms as t15', 't7.dosage_form_id', '=', 't15.id')
                    ->select(DB::raw("DISTINCT t7.id,t7.*, t1.*, t1.id as active_application_id, t1.reg_product_id, t4.name as application_status,t1.section_id as product_section_id,
            t7.storage_condition, t7.brand_name, t7.id as tra_product_id, t8.name as common_name,t8.id as common_name_id, t10.name as classification_name, t11.certificate_no, t12.expiry_date,t7.physical_description as product_description, t14.manufacturer_id, t15.name as dosage_form"));
                $qry->where('t12.registration_status_id', 2);
                $results = $qry->get();
                $res = array(
                    'success' => true,
                    'results' => $results,
                    'message' => 'All is well'
                );
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function onSaveProductReleaseConfirmation(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $sub_module_id = $req->sub_module_id;
            $module_id = $req->module_id;
            $product_id = $req->product_id;
            $released_confirmation_id = $req->released_confirmation_id;
            $remarks = $req->remarks;
            $approval_id = $req->approval_id;
            $user_id = $this->user_id;
            $app_data = array(
                'module_id' => $req->module_id,
                'product_id' => $req->product_id,
                'application_code' => $req->application_code,
                'released_confirmation_id' => $req->released_confirmation_id,
                'comment' => $req->remarks,
                'approved_by' => $req->user_id
            );
            if (validateIsNumeric($approval_id)) {
                $app_data = array(
                    'released_confirmation_id' => $req->released_confirmation_id,
                    'comment' => $req->remarks,
                    'approved_by' => $user_id
                );
                $where = array(
                    'application_code' => $application_code
                );

                $previous_data = getPreviousRecords('tra_released_product_applications', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('tra_released_product_applications', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('tra_released_product_applications', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function onSaveSeizureConfirmation(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $sub_module_id = $req->sub_module_id;
            $module_id = $req->module_id;
            $seizure_confirmation_id = $req->seizure_confirmation_id;
            $product_id = $req->product_id;
            $remarks = $req->remarks;
            $approval_id = $req->approval_id;
            $user_id = $this->user_id;
            $app_data = array(
                'module_id' => $req->module_id,
                'application_code' => $req->application_code,
                'seizure_confirmation_id' => $req->seizure_confirmation_id,
                'product_id' => $req->product_id,
                'comment' => $req->remarks,
                'approved_by' => $req->user_id
            );
            if (validateIsNumeric($approval_id)) {
                $app_data = array(
                    'seizure_confirmation_id' => $req->seizure_confirmation_id,
                    'product_id' => $req->product_id,
                    'comment' => $req->remarks,
                    'approved_by' => $user_id
                );
                $where = array(
                    'application_code' => $application_code
                );

                $previous_data = getPreviousRecords('tra_product_seizure_applications', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('tra_product_seizure_applications', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('tra_product_seizure_applications', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function onSaveExhibitionRequestApproval(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $sub_module_id = $req->sub_module_id;
            $module_id = $req->module_id;
            $product_id = $req->product_id;
            $exhibit_request_approval_id = $req->exhibit_request_approval_id;
            $remarks = $req->remarks;
            $approval_id = $req->approval_id;
            $user_id = $this->user_id;
            $app_data = array(
                'module_id' => $req->module_id,
                'application_code' => $req->application_code,
                'product_id' => $req->product_id,
                'exhibit_request_approval_id' => $req->exhibit_request_approval_id,
                'comment' => $req->remarks,
                'approved_by' => $req->user_id
            );
            if (validateIsNumeric($approval_id)) {
                $app_data = array(
                    'exhibit_request_approval_id' => $req->exhibit_request_approval_id,
                    'comment' => $req->remarks,
                    'approved_by' => $user_id
                );
                $where = array(
                    'application_code' => $application_code
                );

                $previous_data = getPreviousRecords('tra_exhibit_request_approvals', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('tra_exhibit_request_approvals', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('tra_exhibit_request_approvals', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveSeizureWitnessDetails(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $application_id = $req->application_id;
            $application_code = $req->active_application_code;
            $witness_designation = $req->witness_designation;
            $record_id = $req->id;
            $witness_name = $req->witness_name;
            $responsible_person = $req->responsible_person;
            $user_id = $this->user_id;
            //$approval_id = $req->approval_id;    
            $app_data = array(
                'application_id' => $application_id,
                'application_code' => $application_code,
                'witness_designation' => $witness_designation,
                'witness_name' => $witness_name,
                'responsible_person' => $responsible_person,
            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );
                $previous_data = getPreviousRecords('par_seizure_witness_details', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_seizure_witness_details', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_seizure_witness_details', $app_data);
            }
            // $res = insertRecord('par_investigation_diary', $app_data);
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareInvestigationDairy(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_report_type as t2', 't1.report_type_id', '=', 't2.id')
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->leftJoin('tra_enforcement_workplan_details as t4', 't1.application_code', '=', 't4.application_code')
                ->select('t1.*', 't3.*', 't4.*')
                ->where('t1.id', $application_id);


            $results = $main_qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getSeizureWitnessDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_seizure_witness_details as t1')
                ->where('t1.application_code', $application_code)
                ->select(DB::raw("t1.*"));

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareExhibitRequest(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_report_type as t2', 't1.report_type_id', '=', 't2.id')
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->leftJoin('tra_exhibit_request_details as t4', 't1.application_code', '=', 't4.application_code')
                ->where('t1.id', $application_id)
                ->select('t1.*', 't3.*', 't4.*');

            $results = $main_qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveExhibitRequisitionDetails(Request $request)
    {
        // $request_id = $request->input('request_id');
        $requisitioning_officer = $request->input('requisitioning_officer');
        $department = $request->input('department');
        $Exhibit_destination = $request->input('Exhibit_destination');
        $return_date = $request->input('return_date');
        $requisition_reason = $request->input('requisition_reason');
        $needed_date = $request->input('needed_date');
        $selected = $request->input('selected');
        $selected_codes = json_decode($selected);
        $user_id = $this->user_id;

        try {
            foreach ($selected_codes as $selected_code) {
                $application_code = $selected_code->application_code;
                //$enforcement_id = $selected_code->enforcement_id;
            }
            $params = array(
                //'enforcement_id' => $enforcement_id,
                'application_code' => $application_code,
                'created_by' => $this->user_id,
                'requisitioning_officer' => $requisitioning_officer,
                'department' => $department,
                'Exhibit_destination' => $Exhibit_destination,
                'return_date' => $return_date,
                'requisition_reason' => $requisition_reason,
            );

            if (isset($request_id) && $request_id != '') {
                $params['altered_by'] = $user_id;
                DB::table('tra_exhibit_request_details')
                    ->where('id', $request_id)
                    ->update($params);
                DD($params);
            } else {
                $params['created_by'] = $user_id;

                $insert_res = insertRecord('tra_exhibit_request_details', $params, $user_id);
                $request_id = $insert_res['record_id'];
            }
            $res = array(
                'success' => true,
                'request_id' => $request_id,
                'message' => 'Details saved successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function MystreyShoppingEvaluationApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        $table_name = getTableName($module_id);

        try {
            $qry = DB::table($table_name . ' as t1')
                ->leftJoin('tra_enforcement_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftJoin('par_report_type as t3', 't2.report_type_id', '=', 't3.id')
                ->leftJoin('par_system_statuses as t4', 't1.application_status_id', 't4.id')
                ->leftJoin('tra_submissions as t5', 't1.application_code', '=', 't5.application_code')
                ->leftJoin('wb_trader_account as t6', 't2.applicant_id', '=', 't6.id')
                ->leftJoin('wf_workflow_stages as t7', 't5.current_stage', 't7.id')
                ->leftJoin('par_entity_types as t8', 't2.entity_type_id', 't8.id')
                ->select(
                    't1.*',
                    't1.id as active_application_id',
                    't2.fullnames as internal_reporter',
                    't2.suspect_address',
                    't3.name as nature_of_report',
                    't4.name as application_status',
                    't4.name as application_status',
                    't5.date_received as submitted_on',
                    't5.date_received as submitted_on',
                    't6.name as applicant_name',
                    't7.stage_category_id',
                    't8.name as suspected_entity'
                )
                ->where(array('t5.current_stage' => $workflow_stage, 'isDone' => 0));

            $results = $qry->orderBy('t5.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMystreyShoppingApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        $table_name = getTableName($module_id);

        try {
            $qry = DB::table($table_name . ' as t1')
                ->leftJoin('tra_enforcement_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftJoin('par_report_type as t3', 't2.report_type_id', '=', 't3.id')
                ->leftJoin('par_system_statuses as t4', 't1.application_status_id', 't4.id')
                ->leftJoin('tra_submissions as t5', 't1.application_code', '=', 't5.application_code')
                ->leftJoin('wb_trader_account as t6', 't2.applicant_id', '=', 't6.id')
                ->leftJoin('wf_workflow_stages as t7', 't5.current_stage', 't7.id')
                ->leftJoin('par_entity_types as t8', 't2.entity_type_id', 't8.id')
                ->select(
                    't1.*',
                    't1.id as active_application_id',
                    't2.fullnames as internal_reporter',
                    't2.suspect_address',
                    't3.name as nature_of_report',
                    't4.name as application_status',
                    't4.name as application_status',
                    't5.date_received as submitted_on',
                    't5.date_received as submitted_on',
                    't6.name as applicant_name',
                    't7.stage_category_id',
                    't8.name as suspected_entity'
                )
                ->where(array('t5.current_stage' => $workflow_stage, 'isDone' => 0));

            $results = $qry->orderBy('t5.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function onMonitoringComplianceDetails(Request $req)
    {
        $enforcement_id = $req->input('enforcement_id');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftjoin('tra_enforcement_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftjoin('par_entity_types as t3', 't2.entity_type_id', '=', 't3.id')
                ->select(
                    't1.*',
                    't2.*',
                    't2.id as enforcement_id',
                    't1.id as active_application_id',
                    't2.suspect_country_id as country_id',
                    't2.suspect_region_id as region_id',
                    't2.suspect_district_id as district_id',
                    't3.name as suspected_entity'
                )
                ->where('t2.id', $enforcement_id);
            $results = $main_qry->first();
            // $qry1 = clone $main_qry;

            // $qry1->join('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
            //     ->leftJoin('par_report_type as t4', 't2.report_type_id', '=', 't4.id')
            //     ->select('t1.*','t2.*','t1.id as active_application_id','t2.suspect_country_id as country_id','t2.suspect_region_id as region_id',
            //     't2.suspect_district_id as district_id','t3.name as applicant_name','t4.name as report_type');

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveMonitoringReceivingDetails(Request $request, $inCall = 0)
    {
        $active_application_id = $request->input('active_application_id');
        $process_id = $request->input('process_id');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $section_id = $request->input('section_id');
        $module_id = $request->input('module_id');
        $sub_module_id = $request->input('sub_module_id');
        $user_id = $this->user_id;
        $enforcement_id = $request->input('enforcement_id');


        $enforcement_data = array(
            'section_id' => $request->section_id,
            'enforcement_id' => $request->enforcement_id,
            'is_facility_registered' => $request->is_facility_registered,
            'is_facility_registered' => $request->is_facility_registered,
            'reg_premise_id' => $request->reg_premise_id,
            'premise_id' => $request->premise_id,
            'permit_no' => $request->permit_no,
            'premise_name' => $request->premise_name,
            'premise_type' => $request->premise_type,
            'country_id' => $request->country_id,
            'region_id' => $request->region_id,
            'district_id' => $request->district_id,
            'region_id' => $request->region_id,
            'physical_address' => $request->physical_address,
            'postal_address' => $request->postal_address,
            'telephone' => $request->telephone,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        );

        try {
            DB::beginTransaction();
            $applications_table = 'tra_enforcement_applications';
            $enforcement_table = 'tra_monitoring_information';

            $where_app = array(
                'id' => $active_application_id
            );
            //Edit enforcement Application
            if (isset($active_application_id) && $active_application_id != "") {

                $application_params = array(
                    'enforcement_id' => $enforcement_id
                );
                $where_app = array(
                    'id' => $active_application_id
                );

                $where_enforcement = array(
                    'id' => $enforcement_id
                );

                if (recordExists($applications_table, $where_app)) {

                    $app_details = getPreviousRecords($applications_table, $where_app);

                    if ($app_details['success'] == false) {
                        DB::rollBack();
                        return $app_details;
                    }
                    $app_details = $app_details['results'];
                    $app_res = updateRecord($applications_table, $app_details, $where_app, $application_params, $user_id);

                    if ($app_res['success'] == false) {
                        DB::rollBack();
                        return $app_res;
                    }
                }

                $application_code = $app_details[0]['application_code']; //$app_details->application_code;
                $tracking_no = $app_details[0]['tracking_no'];
                $reference_no = $app_details[0]['reference_no'];

                // EDIT

                if (recordExists($enforcement_table, $where_enforcement)) {
                    $enforcement_data['dola'] = Carbon::now();
                    $enforcement_data['altered_by'] = $user_id;
                    $previous_data = getPreviousRecords($enforcement_table, $where_enforcement);
                    if ($previous_data['success'] == false) {
                        DB::rollBack();
                        return $previous_data;
                    }

                    $previous_data = $previous_data['results'];

                    $res = updateRecord($enforcement_table, $previous_data, $where_enforcement, $enforcement_data, $user_id);
                    if ($res['success'] == false) {
                        DB::rollBack();
                        return $res;
                    }

                    //delete existing responsible user and insert the new
                    $monitoring_personnel_table = 'par_monitoring_premise_personnel';
                    $is_facility_registered = $enforcement_data['is_facility_registered'];
                    $premise_id = $enforcement_data['premise_id'];
                    $where_application = array(
                        'application_code' => $application_code
                    );

                    if (recordExists($monitoring_personnel_table, $where_application)) {
                        $previous_data = getPreviousRecords($monitoring_personnel_table, $where_application);
                        if ($previous_data['success'] == false) {
                            return $previous_data;
                        }
                        $previous_data = $previous_data['results'];
                        $delete_personnel = deleteRecord($monitoring_personnel_table, $previous_data, $previous_data, $where_application, $user_id);
                        if ($delete_personnel['success'] == false) {
                            DB::rollBack();
                            return $delete_personnel;
                        }
                    }



                    if ($is_facility_registered == 1) {
                        //if registred duplicate personnels to monitoring personnel table
                        $reg_personnel = DB::table('tra_premises_personnel as t1')
                            ->join('tra_personnel_information as t2', 't1.personnel_id', 't2.id')
                            ->where('t1.premise_id', $premise_id)
                            ->select(
                                't1.id',
                                't1.registration_no',
                                't1.position_id',
                                't1.qualification_id',
                                't1.start_date',
                                't1.end_date',
                                't2.name',
                                't2.telephone_no as telephone',
                                't2.email_address as email'
                            );
                        $reg_personnels = $reg_personnel->get();

                        foreach ($reg_personnels as $personnel) {
                            $registration_no = $personnel->registration_no;
                            $position_id = $personnel->position_id;
                            $qualification_id = $personnel->qualification_id;
                            $start_date = $personnel->start_date;
                            $end_date = $personnel->end_date;
                            $name = $personnel->name;
                            $telephone = $personnel->telephone;
                            $email = $personnel->email;

                            $insert_personnel = array(
                                'application_id' => $active_application_id,
                                'application_code' => $application_code,
                                'reg_number' => $registration_no,
                                'position_id' => $position_id,
                                'qualification_id' => $qualification_id,
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'name' => $name,
                                'telephone' => $telephone,
                                'email' => $email,
                            );

                            $my_res = insertRecord('par_monitoring_premise_personnel', $insert_personnel, $user_id);


                            $personnel_id = $my_res['record_id'];
                            $dispensing_data = array(
                                'personnel_id' => $personnel_id,
                                'dispensing_name' => $name,
                                'reg_number' => $registration_no,
                                'application_code' => $application_code,
                                'created_on' => Carbon::now(),
                                'created_by' => $user_id,
                            );

                            $update_dispensing_data = insertRecord('par_dispensing_compliance_data', $dispensing_data, $user_id);
                        }
                        if ($my_res['success'] == false) {
                            DB::rollBack();
                            return $my_res;
                        }
                    }

                    // initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no.rand(10,100), $user_id);
                    $res['active_application_id'] = $active_application_id;
                    $res['active_application_code'] = $application_code;
                    $res['enforcement_id'] = $enforcement_id;
                    $res['reference_no'] = $reference_no;
                    $res['tracking_no'] = $tracking_no;
                    DB::commit();
                }
            } else {
                //Insert
                $enforcement_res = insertRecord($enforcement_table, $enforcement_data, $user_id);

                // dd($enforcement_res);
                if ($enforcement_res['success'] == false) {
                    DB::rollBack();
                    return $enforcement_res;
                }
                //tracking the application
                $enforcement_id = $enforcement_res['record_id'];

                //Application_create
                $codes_array =  $this->getEnforcementApplicationReferenceCodes($request);
                $view_id = generateApplicationViewID();
                $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, 1, $user_id);
                $application_code = generateApplicationCode($sub_module_id, $applications_table);
                $application_status = getApplicationInitialStatus($module_id, $sub_module_id);

                if ($tracking_details['success'] == false) {
                    DB::rollBack();
                    return \response()->json($tracking_details);
                }

                $tracking_no = $tracking_details['tracking_no'];
                $reference_no = $tracking_details['tracking_no'];
                $reference_no = str_replace("TRC", 'BMR', $reference_no);


                $application_params = array(
                    'view_id' => $view_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_code' => $application_code,
                    'process_id' => $process_id,
                    'workflow_stage_id' => $workflow_stage_id,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'enforcement_id' => $enforcement_id,
                    'application_status_id' => $application_status->status_id,
                    'is_fast_track' => $request->fasttrack_option_id,
                    "date_added" => Carbon::now(),
                    "created_by" => \Auth::user()->id,
                    "created_on" => Carbon::now()
                );

                $res = insertRecord($applications_table, $application_params, $user_id);
                //dd($res);
                if (!isset($res['record_id'])) {
                    DB::rollBack();
                    return $res;
                }

                $active_application_id = $res['record_id'];

                //Insert personnel registred to thats entity 
                $is_facility_registered = $enforcement_data['is_facility_registered'];
                $premise_id = $enforcement_data['premise_id'];

                if ($is_facility_registered == 1) {
                    //if registred duplicate personnels to monitoring personnel table
                    $reg_personnel = DB::table('tra_premises_personnel as t1')
                        ->join('tra_personnel_information as t2', 't1.personnel_id', 't2.id')
                        ->where('t1.premise_id', $premise_id)
                        ->select(
                            't1.id',
                            't1.registration_no',
                            't1.position_id',
                            't1.qualification_id',
                            't1.start_date',
                            't1.end_date',
                            't2.name',
                            't2.telephone_no as telephone',
                            't2.email_address as email'
                        );
                    $reg_personnels = $reg_personnel->get();



                    foreach ($reg_personnels as $personnel) {
                        $registration_no = $personnel->registration_no;
                        $position_id = $personnel->position_id;
                        $qualification_id = $personnel->qualification_id;
                        $start_date = $personnel->start_date;
                        $end_date = $personnel->end_date;
                        $name = $personnel->name;
                        $telephone = $personnel->telephone;
                        $email = $personnel->email;

                        $insert_personnel = array(
                            'application_id' => $active_application_id,
                            'application_code' => $application_code,
                            'reg_number' => $registration_no,
                            'position_id' => $position_id,
                            'qualification_id' => $qualification_id,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'name' => $name,
                            'telephone' => $telephone,
                            'email' => $email,
                        );

                        $my_res = insertRecord('par_monitoring_premise_personnel', $insert_personnel, $user_id);
                        // dd($my_res);
                        $personnel_id = $my_res['record_id'];
                        $dispensing_data = array(
                            'personnel_id' => $personnel_id,
                            'dispensing_name' => $name,
                            'reg_number' => $registration_no,
                            'application_code' => $application_code,
                            'created_on' => Carbon::now(),
                            'created_by' => $user_id,
                        );

                        $update_dispensing_data = insertRecord('par_dispensing_compliance_data', $dispensing_data, $user_id);
                        // dd($update_dispensing_data);
                        // $my_res = insertMultipleRecords('par_monitoring_premise_personnel', $insert_personnel);
                    }

                    if (isset($my_res) &&  $my_res['success'] == false) {
                        DB::rollBack();
                        return $my_res['success'];
                    }
                }

                // createInitialRegistrationRecord('tra_enforcement_applications', $applications_table, $reg_params, $application_id, 'reg_premise_id');
                //DMS

                //initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no, $user_id);

                // add to submissions table
                $submission_params = array(
                    'application_id' => $active_application_id,
                    'view_id' => $view_id,
                    'process_id' => $process_id,
                    'application_code' => $application_code,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'usr_from' => $user_id,
                    'usr_to' => $user_id,
                    'previous_stage' => $workflow_stage_id,
                    'current_stage' => $workflow_stage_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_status_id' => $application_status->status_id,
                    'urgency' => 1,
                    'remarks' => 'Initial save of the application',
                    'date_received' => Carbon::now(),
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );
                DB::table('tra_submissions')
                    ->insert($submission_params);
            }
            DB::commit();
            $my_res['msg'] = 'Responsible Professionalls Loaded!!';

            $res['active_application_id'] = $active_application_id;
            $res['process_id'] = $process_id;
            $res['application_code'] = $application_code;
            $res['tracking_no'] = $tracking_no;
            $res['reference_no'] = $reference_no;
            $res['enforcement_id'] = $enforcement_id;
            $res['msg'] = 'Record Saved Successfully';
            $res['success'] = true;
        } catch (\Exception $exception) {
            DB::rollBack();
            $res = array("message" => $exception->getMessage(), "line" => $exception->getLine());

            // $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        if ($inCall == 1) {
            return $res;
        }
        return \response()->json($res);
    }
    public function getMystreyShoppingLogApplication(Request $request)
    {
        $application_code = $request->input('application_code');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_mystrey_shopping_details as t1')
                ->where('t1.application_code', $application_code)
                ->select(DB::raw("t1.*"));

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getseizureProductsListGrid(Request $request)
    {
        $application_code = $request->input('application_code');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $user_id = $this->user_id;
        try {
            // $qry = DB::table('tra_enforcement_applications as t1')
            $qry = DB::table('tra_reported_products as t1')
                //->leftJoin('par_common_names as t3', 't1.common_name_id', 't3.id')
                ->leftJoin('tra_product_seizure_applications as t4', 't1.id', 't4.product_id')
                //->leftJoin('tra_premises as t5', 't1.premise_name', 't5.id')
                ->leftJoin('tra_exhibit_request_approvals as t5', 't1.id', 't5.product_id')
                ->leftJoin('tra_released_product_applications as t6', 't1.id', 't6.product_id')
                ->leftJoin('tra_destroyed_products as t8', 't1.id', 't8.product_id')
                ->leftJoin('tra_exhibit_request_details as t7', 't1.application_code', 't7.application_code')
                ->where('t1.application_code', $application_code)
                ->select('t1.*', 't7.requisitioning_officer as requisitioning_officer', 't4.seizure_confirmation_id as seizure_confirmation_id', 't8.destruction_id as destruction_id', 't5.exhibit_request_approval_id as exhibit_request_approval_id', 't6.released_confirmation_id as released_confirmation_id');

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveSeizureProductDetails(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $application_id = $req->application_id;
            $application_code = $req->active_application_code;
            $common_name = $req->common_name;
            $premise_name = $req->premise_name;
            $brand_name = $req->brand_name;
            $expiry_date = $req->expiry_date;
            $entity_type_id = $req->entity_type_id;
            $quantity = $req->quantity;
            $batch_number = $req->batch_number;
            $prodclass_category_id = $req->prodclass_category_id;
            $premise_type_id = $req->premise_type_id;
            $record_id = $req->id;
            $user_id = $this->user_id;
            //$approval_id = $req->approval_id;    
            $app_data = array(
                'application_id' => $application_id,
                'application_code' => $application_code,
                'common_name' => $common_name,
                'premise_name' => $premise_name,
                'brand_name' => $brand_name,
                'expiry_date' => $expiry_date,
                'entity_type_id' => $entity_type_id,
                'quantity' => $quantity,
                'batch_number' => $batch_number,
                'prodclass_category_id' => $prodclass_category_id,
                'premise_type_id' => $premise_type_id,
            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );
                $prev_data = getPreviousRecords('tra_reported_products', $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];

                $res = updateRecord('tra_reported_products', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('tra_reported_products', $app_data);
            }
            // $res = insertRecord('par_investigation_diary', $app_data);
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveSeizureWorkPlanDetails(Request $request)
    {
        //$seizureplan_id = $request->input('seizureplan_id');
        $lead_inspector = $request->input('lead_inspector');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $remarks = $request->input('remarks');
        $selected = $request->input('selected');
        $selected_codes = json_decode($selected);
        $user_id = $this->user_id;

        try {
            foreach ($selected_codes as $selected_code) {
                $application_code = $selected_code->application_code;
                $enforcement_id = $selected_code->enforcement_id;
            }
            $params = array(
                'enforcement_id' => $enforcement_id,
                'application_code' => $application_code,
                'created_by' => $this->user_id,
                'lead_inspector' => $lead_inspector,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'remarks' => $remarks,
            );

            if (isset($seizureplan_id) && $seizureplan_id != '') {
                $params['altered_by'] = $user_id;
                DB::table('tra_seizure_workplan_details')
                    ->where('id', $seizureplan_id)
                    ->update($params);
                DD($params);
            } else {
                $params['created_by'] = $user_id;

                $insert_res = insertRecord('tra_seizure_workplan_details', $params, $user_id);
                $seizureplan_id = $insert_res['record_id'];
            }
            $res = array(
                'success' => true,
                'seizureplan_id' => $seizureplan_id,
                'message' => 'Details saved successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareSeizureWorkPlan(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_report_type as t2', 't1.report_type_id', '=', 't2.id')
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->leftJoin('tra_seizure_workplan_details as t4', 't1.application_code', '=', 't4.application_code')
                ->where('t1.id', $application_id)
                ->select('t1.*', 't3.*', 't4.*');

            $results = $main_qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareSeizurePlanDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_report_type as t2', 't1.report_type_id', '=', 't2.id')
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->leftJoin('tra_seizure_workplan_details as t4', 't1.application_code', '=', 't4.application_code')
                ->select('t1.*', 't3.*', 't4.*')
                ->where('t1.id', $application_id);


            $results = $main_qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveDestructionPlanDetails(Request $request)
    {
        // $request_id = $request->input('request_id');
        $inspector = $request->input('inspector');
        $pharmacist = $request->input('pharmacist');
        $police = $request->input('police');
        $date = $request->input('date');
        $remarks = $request->input('remarks');
        $selected = $request->input('selected');
        $selected_codes = json_decode($selected);
        $user_id = $this->user_id;

        try {
            foreach ($selected_codes as $selected_code) {
                $application_code = $selected_code->application_code;
                $product_id = $selected_code->product_id;
            }
            $params = array(
                'product_id' => $product_id,
                'application_code' => $application_code,
                'created_by' => $this->user_id,
                'inspector' => $inspector,
                'pharmacist' => $pharmacist,
                'police' => $police,
                'date' => $date,
                'remarks' => $remarks,
            );

            if (isset($request_id) && $request_id != '') {
                $params['altered_by'] = $user_id;
                DB::table('tra_product_destruction_plan')
                    ->where('id', $request_id)
                    ->update($params);
                DD($params);
            } else {
                $params['created_by'] = $user_id;

                $insert_res = insertRecord('tra_product_destruction_plan', $params, $user_id);
                $request_id = $insert_res['record_id'];
            }
            $res = array(
                'success' => true,
                'request_id' => $request_id,
                'message' => 'Details saved successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareProductDestruction(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $table_name = $request->input('table_name');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_report_type as t2', 't1.report_type_id', '=', 't2.id')
                ->leftJoin('tra_enforcement_information as t3', 't1.enforcement_id', '=', 't3.id')
                ->leftJoin('tra_product_destruction_plan as t4', 't1.application_code', '=', 't4.application_code')
                ->select('t1.*', 't3.*', 't4.*')
                ->where('t1.id', $application_id);


            $results = $main_qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function onSaveDestructionConfirmation(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $sub_module_id = $req->sub_module_id;
            $module_id = $req->module_id;
            $product_id = $req->product_id;
            $destruction_id = $req->destruction_id;
            $remarks = $req->remarks;
            $approval_id = $req->approval_id;
            $user_id = $this->user_id;
            $app_data = array(
                'module_id' => $req->module_id,
                'application_code' => $req->application_code,
                'product_id' => $req->product_id,
                'destruction_id' => $req->destruction_id,
                'comment' => $req->remarks,
                'approved_by' => $req->user_id
            );
            if (validateIsNumeric($approval_id)) {
                $app_data = array(
                    'destruction_id' => $req->destruction_id,
                    'comment' => $req->remarks,
                    'approved_by' => $user_id
                );
                $where = array(
                    'application_code' => $application_code
                );
                $prev_data = getPreviousRecords('tra_destroyed_products', $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];
                $res = updateRecord('tra_destroyed_products', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('tra_destroyed_products', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getJointOperationsApplications(Request $request)
    //getWorkplanApplications
    {
        $workflow_stage = $request->input('workflow_stage_id');
        $workplan_id = $request->input('workplan_id');

        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->join('tra_jointOperation_information as t2', 't1.joint_operation_id', '=', 't2.id')
                ->leftJoin('tra_submissions as t3', 't1.application_code', '=', 't3.application_code')
                ->leftJoin('par_system_statuses as t4', 't1.application_status_id', '=', 't4.id')
                ->leftJoin('tra_approval_recommendations as t8', 't8.application_code', '=', 't1.application_code')
                ->leftJoin('par_departments as t5', 't2.department_id', '=', 't5.id')
                ->leftJoin('tra_manager_approved_operations as t6', 't1.application_code', '=', 't6.application_code')
                ->leftJoin('tra_evaluation_recommendations as t9', 't1.application_code', '=', 't9.application_code')
                ->select(
                    't1.*',
                    't1.id as active_application_id',
                    't6.comments',
                    't8.decision_id',
                    't9.recommendation_id',
                    't6.id as approval_id',
                    't6.manager_approval_id as manager_approval_id',
                    't2.scope',
                    't2.external_operative',
                    't2.internal_operative',
                    't2.department_name',
                    't2.organizing_officer',
                    't2.objective',
                    't2.activity',
                    't2.start_date',
                    't2.end_date',
                    't4.name as application_status',
                    't5.name as department_id'
                )
                ->where(array('t3.current_stage' => $workflow_stage, 'isDone' => 0));
            $results = $qry->get();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getJointOperativeActivities(Request $request)
    {
        $application_code = $request->input('application_code');
        try {
            $qry = DB::table('par_joint_activities_details as t1')
                ->leftjoin('users as t2', 't1.officer', '=', 't2.id')
                ->select(DB::raw("t1.*,CONCAT_WS(' ',decrypt(t2.first_name),decrypt(t2.last_name)) as officer"))
                ->where('t1.application_code', $application_code);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'Saved Successfully'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getJointOperationLogistics(Request $request)
    {
        $application_code = $request->input('active_application_code');
        try {
            $qry = DB::table('par_joint_logistics_details as t1')
                ->select('t1.*')
                ->where('t1.application_code', $application_code);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveJointOperationsWorkPlanDetails(Request $request)
    {

        $workpan_id = $request->input('workpan_id');
        $matter_name = $request->input('matter_name');
        $matter_details = $request->input('matter_details');
        $investigation_subject = $request->input('investigation_subject');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $investigation_type = $request->input('investigation_type');
        $description = $request->input('remarks');
        $selected = $request->input('selected');
        $selected_codes = json_decode($selected);
        $user_id = $this->user_id;

        try {
            foreach ($selected_codes as $selected_code) {
                $application_code = $selected_code->application_code;
                $enforcement_id = $selected_code->enforcement_id;
            }
            $params = array(
                'enforcement_id' => $enforcement_id,
                'application_code' => $application_code,
                'created_by' => $this->user_id,
                'matter_name' => $matter_name,
                'matter_details' => $matter_details,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'investigation_subject' => $investigation_subject,
                'investigation_type' => $investigation_type,
                'description' => $description
            );

            if (isset($workpan_id) && $workpan_id != '') {
                $params['altered_by'] = $user_id;
                DB::table('tra_enforcement_workplan_details')
                    ->where('id', $workpan_id)
                    ->update($params);
                DD($params);
            } else {
                $params['created_by'] = $user_id;

                $insert_res = insertRecord('tra_enforcement_workplan_details', $params, $user_id);
                $workpan_id = $insert_res['record_id'];
            }
            $res = array(
                'success' => true,
                'workpan_id' => $workpan_id,
                'message' => 'Details saved successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveNewDairyitem(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $application_id = $req->application_id;
            $active_application_code = $req->active_application_code;
            $enforcement_id = $req->enforcement_id;
            $record_id = $req->id;
            $date = $req->date;
            $action = $req->action;
            $action_time = $req->action_time;
            $remarks = $req->remarks;
            $user_id = $this->user_id;
            $app_data = array(
                'enforcement_id' => $enforcement_id,
                'application_id' => $application_id,
                'active_application_code' => $active_application_code,
                'date' => $date,
                'action' => $action,
                'action_time' => $action_time,
                'remarks' => $remarks,
            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );
                $prev_data = getPreviousRecords('par_new_diary', $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];
                $res = updateRecord('par_new_diary', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_new_diary', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getNewDiaryItem(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_new_diary as t1')
                ->where('t1.active_application_code', $application_code)
                ->select(DB::raw("t1.*"));

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveTimelineDetails(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $application_id = $req->application_id;
            $active_application_code = $req->active_application_code;
            $enforcement_id = $req->enforcement_id;
            $record_id = $req->id;
            $date = $req->date;
            $action = $req->action;
            $action_time = $req->action_time;
            $assigned_officer = $req->assigned_officer;
            $action_duration = $req->action_duration;
            $user_id = $this->user_id;
            $app_data = array(
                'enforcement_id' => $enforcement_id,
                'application_id' => $application_id,
                'active_application_code' => $active_application_code,
                'date' => $date,
                'action' => $action,
                'action_time' => $action_time,
                'assigned_officer' => $assigned_officer,
                'action_duration' => $action_duration,
            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );
                $prev_data = getPreviousRecords('par_investigation_timeline', $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];
                $res = updateRecord('par_investigation_timeline', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_investigation_timeline', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getTimelineDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_investigation_timeline as t1')
                ->leftJoin('users as t2', 't1.assigned_officer', '=', 't2.id')
                ->where('t1.active_application_code', $application_code)
                ->select(DB::raw("t1.*,CONCAT_WS(' ',decrypt(t2.first_name),decrypt(t2.last_name)) as officer"));


            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getRegisteredCases(Request $request)
    {
        $user_id = $this->user_id;
        $tracking_no = $request->input('tracking_no');
        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_system_statuses as t2', 't1.application_status_id', 't2.id')
                ->leftJoin('tra_submissions as t3', 't1.application_code', '=', 't3.application_code')
                ->leftJoin('tra_enforcement_information as t4', 't1.enforcement_id', '=', 't4.id')
                ->leftJoin('par_report_type as t5', 't4.report_type_id', '=', 't5.id')
                ->leftJoin('users as t6', 't3.usr_from', '=', 't6.id')
                ->leftJoin('users as t7', 't4.reported_by_id', '=', 't7.id')
                ->leftJoin('wf_workflow_stages as t8', 't3.current_stage', 't8.id')
                ->leftJoin('tra_investigation_approvals as t9', 't1.application_code', 't9.application_code')
                ->leftJoin('wb_trader_account as t10', 't1.applicant_id', '=', 't10.id')
                ->leftJoin('par_case_status as t11', 't1.case_status_id', '=', 't11.id')
                ->leftJoin('par_entity_types as t12', 't4.entity_type_id', '=', 't12.id')
                ->leftJoin('users as t13', 't3.usr_to', '=', 't13.id')
                ->select(
                    't1.*',
                    DB::raw("CONCAT_WS(' ',decrypt(t7.first_name),decrypt(t7.last_name)) as submitted_by,CONCAT_WS(' ',decrypt(t13.first_name),decrypt(t13.last_name)) as investigator"),
                    't3.date_received as submitted_on',
                    't2.name as application_status',
                    't1.id as active_application_id',
                    't4.suspect_address as suspect_address',
                    't5.name as report_type',
                    't8.stage_category_id',
                    't12.name as suspected_entity',
                    DB::raw("CASE WHEN t4.fullnames is NULL THEN t4.applicant_name WHEN t4.applicant_name is NULL THEN t4.fullnames END reporter")
                )
                ->where('t1.sub_module_id', '=', 105);
            if ($tracking_no != '') {
                $qry->where('t1.tracking_no', 'LIKE', '%' . $tracking_no . '%');
                $qry->orWhere('t1.reference_no', 'LIKE', '%' . $tracking_no . '%');
            }
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveNewWorkPlanDetails(Request $request)
    {
        try {
            $table_name = $request->table_name;
            $record_id = $request->input('id');
            $matter_name = $request->input('matter_name');
            $application_code = $request->input('active_application_code');
            $matter_details = $request->input('matter_details');
            $investigation_subject = $request->input('investigation_subject');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $investigation_type = $request->input('investigation_type');
            $description = $request->input('remarks');
            $facts_alleged = $request->input('facts_alleged');
            $workpan_id = $request->workplan_id;
            $user_id = $this->user_id;
            $app_data = array(
                'application_code' => $application_code,
                'created_by' => $this->user_id,
                'matter_name' => $matter_name,
                'matter_details' => $matter_details,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'investigation_subject' => $investigation_subject,
                'investigation_type' => $investigation_type,
                'description' => $description,
                'facts_alleged' => $facts_alleged
            );
            // dd($record_id);
            if (validateIsNumeric($workpan_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $workpan_id
                );
                $prev_data = getPreviousRecords('tra_enforcement_workplan_details', $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];
                $res = updateRecord('tra_enforcement_workplan_details', $previous_data, $where, $app_data, $user_id);

                // dd($res);
            } else {
                $res = insertRecord('tra_enforcement_workplan_details', $app_data);
                //dd($res);
                //$record_id = $res['record_id'];
            }
            // $res = insertRecord('tra_enforcement_workplan_details', $app_data);
            // $res = insertRecord('par_investigation_diary', $app_data);
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getCaseOffences(Request $request)
    {
        $application_id = $request->application_id;
        $qry = DB::table('par_suspected_offence as t1')
            ->leftjoin('par_offence_types as t2', 't1.offence_type_id', '=', 't2.id')
            ->select('t1.*', 't2.name')
            ->where(array('t1.application_id' => $application_id));
        //    $results=$qry->get();
        //     if ($filters != '') {
        //         $filters = (array)json_decode($filters);
        //         $application_id = $filters['application_id'];
        //         $res= $qry->where(array('t1.application_id' => $application_id));
        //       $results=$res->get();
        //     }
        $results = $qry->get();
        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is well'
        );
        return \response()->json($res);
    }
    public function onSaveManagerApprovalDecisions(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $sub_module_id = $req->sub_module_id;
            $module_id = $req->module_id;
            $manager_approval_id = $req->manager_approval_id;
            $comments = $req->comments;
            $approval_id = $req->approval_id;
            $user_id = $this->user_id;
            $app_data = array(
                'module_id' => $module_id,
                'application_code' => $application_code,
                'manager_approval_id' => $manager_approval_id,
                'comments' => $comments,
                'approved_by' => $user_id
            );
            $update_activity_data = array(
                'is_manager_approved' => 1
            );
            $where = array(
                'application_code' => $application_code
            );
            if (validateIsNumeric($approval_id)) {
                $app_data = array(
                    'manager_approval_id' => $manager_approval_id,
                    'comments' => $comments,
                    'approved_by' => $user_id
                );

                $prev_data = getPreviousRecords('tra_manager_approved_operations', $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];
                $res = updateRecord('tra_manager_approved_operations', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('tra_manager_approved_operations', $app_data);
            }
            if ($manager_approval_id == 1) {
                $prev_data = getPreviousRecords('par_joint_activities_details', $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $previous_data = $prev_data['results'];
                $res1 = updateRecord('par_joint_activities_details', $previous_data, $where, $update_activity_data, $user_id);
            } else {
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveJointOperationReceivingDetails(Request $request, $inCall = 0)
    {
        $active_application_id = $request->input('active_application_id');
        $process_id = $request->input('process_id');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $section_id = $request->input('section_id');
        $module_id = $request->input('module_id');
        $sub_module_id = $request->input('sub_module_id');
        $user_id = $this->user_id;
        // $enforcement_id = $request->input('enforcement_id');
        $joint_operation_id = $request->input('joint_operation_id');
        $enforcement_data = array(
            //'enforcement_id'=>$request->enforcement_id,
            'joint_operation_id' => $request->joint_operation_id,
            'department_name' => $request->department_name,
            'organizing_officer' => $request->fullnames,
            'organizing_officer_title' => $request->title,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'activity' => $request->activity,
            'objective' => $request->objective,
            'scope' => $request->scope,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'internal_operative' => $request->internal_operative,
            'external_operative' => $request->external_operative,
        );

        try {
            DB::beginTransaction();
            $applications_table = 'tra_enforcement_applications';
            $enforcement_table = 'tra_jointOperation_information';

            $where_app = array(
                'id' => $active_application_id
            );
            //Edit enforcement Application
            if (isset($active_application_id) && $active_application_id != "") {

                $application_params = array(
                    'applicant_id' => 25 // $applicant_id job to reisnsttate
                );
                $where_app = array(
                    'id' => $active_application_id
                );

                $where_enforcement = array(
                    //'id' => $enforcement_id
                    'id' => $joint_operation_id

                );

                if (recordExists($applications_table, $where_app)) {

                    $app_details = getPreviousRecords($applications_table, $where_app);

                    if ($app_details['success'] == false) {
                        DB::rollBack();
                        return $app_details;
                    }
                    $app_details = $app_details['results'];

                    $app_res = updateRecord($applications_table, $app_details, $where_app, $application_params, $user_id);
                    if ($app_res['success'] == false) {
                        DB::rollBack();
                        return $app_res;
                    }
                }

                $application_code = $app_details[0]['application_code']; //$app_details->application_code;
                $tracking_no = $app_details[0]['tracking_no'];
                $reference_no = $app_details[0]['reference_no'];

                // EDIT
                if (recordExists($enforcement_table, $where_enforcement)) {
                    $enforcement_data['dola'] = Carbon::now();
                    $enforcement_data['altered_by'] = $user_id;
                    $previous_data = getPreviousRecords($enforcement_table, $where_enforcement);
                    if ($previous_data['success'] == false) {
                        DB::rollBack();
                        return $previous_data;
                    }

                    $previous_data = $previous_data['results'];

                    $res = updateRecord($enforcement_table, $previous_data, $where_enforcement, $enforcement_data, $user_id);

                    initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(10, 100), $user_id);
                    $res['active_application_id'] = $active_application_id;
                    $res['active_application_code'] = $application_code;
                    // $res['enforcement_id'] = $enforcement_id;
                    $res['joint_operation_id'] = $joint_operation_id;
                    $res['reference_no'] = $reference_no;
                    $res['tracking_no'] = $tracking_no;
                    DB::commit();
                }
            } else {
                //Insert
                $enforcement_res = insertRecord($enforcement_table, $enforcement_data, $user_id);

                if ($enforcement_res['success'] == false) {
                    DB::rollBack();
                    return $enforcement_res;
                }

                //tracking the application
                // $enforcement_id=$enforcement_res['record_id'];
                $joint_operation_id = $enforcement_res['record_id'];

                //Application_create
                $codes_array =  $this->getEnforcementApplicationReferenceCodes($request);
                $view_id = generateApplicationViewID();
                $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, 1, $user_id);
                $application_code = generateApplicationCode($sub_module_id, $applications_table);
                $application_status = getApplicationInitialStatus($module_id, $sub_module_id);

                if ($tracking_details['success'] == false) {
                    DB::rollBack();
                    return \response()->json($tracking_details);
                }

                $tracking_no = $tracking_details['tracking_no'];
                $reference_no = $tracking_details['tracking_no'];
                $reference_no = str_replace("TRC", 'BMR', $reference_no);


                $application_params = array(
                    'view_id' => $view_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_code' => $application_code,
                    'process_id' => $process_id,
                    'workflow_stage_id' => $workflow_stage_id,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    //'enforcement_id' => $enforcement_id,
                    'joint_operation_id' => $joint_operation_id,
                    'application_status_id' => $application_status->status_id,
                    "date_added" => Carbon::now(),
                    "created_by" => \Auth::user()->id,
                    "created_on" => Carbon::now()
                );

                $res = insertRecord($applications_table, $application_params, $user_id);

                if (!isset($res['record_id'])) {
                    DB::rollBack();
                    return $res;
                }

                $active_application_id = $res['record_id'];

                // createInitialRegistrationRecord('tra_enforcement_applications', $applications_table, $reg_params, $application_id, 'reg_premise_id');
                //DMS

                //   initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no, $user_id);
                // add to submissions table
                $submission_params = array(
                    'application_id' => $active_application_id,
                    'view_id' => $view_id,
                    'process_id' => $process_id,
                    'application_code' => $application_code,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'usr_from' => $user_id,
                    'usr_to' => $user_id,
                    'previous_stage' => $workflow_stage_id,
                    'current_stage' => $workflow_stage_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_status_id' => $application_status->status_id,
                    'urgency' => 1,
                    'remarks' => 'Initial save of the application',
                    'date_received' => Carbon::now(),
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );
                DB::table('tra_submissions')
                    ->insert($submission_params);
            }
            DB::commit();

            $res['active_application_id'] = $active_application_id;
            $res['process_id'] = $process_id;
            $res['application_code'] = $application_code;
            $res['tracking_no'] = $tracking_no;
            $res['reference_no'] = $reference_no;
            $res['joint_operation_id'] = $joint_operation_id;
            $res['msg'] = 'Record Saved Successfully';
            $res['success'] = true;
        } catch (\Exception $exception) {
            DB::rollBack();
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        if ($inCall == 1) {
            return $res;
        }
        return \response()->json($res);
    }
    public function prepareJointOperationReceiving(Request $request)
    {
        $application_id = $request->input('application_id');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_jointOperation_information as t2', 't1.joint_operation_id', '=', 't2.id')
                ->where('t1.id', $application_id)
                ->select('t1.*', 't2.*', 't2.organizing_officer as fullnames', 't2.organizing_officer_title as title');

            $results = $main_qry->first();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveJointOperatiionsParticipants(Request $request)
    {
        $selected = $request->input('selected');
        $application_code = $request->input('application_code');
        $selected_users = json_decode($selected);
        $where = array(
            'application_code' => $application_code
        );

        try {
            DB::transaction(function () use ($selected_users, $application_code, $where) {
                $params = array();
                foreach ($selected_users as $selected_user) {
                    $check = array(
                        'user_id' => $selected_user->user_id,
                        'application_code' => $application_code
                    );
                    if (
                        DB::table('par_jointOperation_participants')
                        ->where($check)->count() == 0
                    ) {
                        $params[] = array(
                            'application_code' => $application_code,
                            'user_id' => $selected_user->user_id,
                            'participant_name' => $selected_user->participant_name,
                            'phone' => $selected_user->phone,
                            'email' => $selected_user->email,
                            'created_by' => $this->user_id
                        );
                    }
                }
                insertMultipleRecords('par_jointOperation_participants', $params);
                //DB::table('tc_meeting_participants')
                //   ->insert($params);
            }, 5);
            $res = array(
                'success' => true,
                'message' => 'Participants saved successfully!!'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    function saveActivityDetails(Request $request)
    {
        $id = $request->input('id');
        $data = $request->all();
        $application_code = $request->input('application_code');
        $application_id = $request->input('application_id');
        $activity = $request->input('activity');
        $objective = $request->input('objective');
        $scope = $request->input('scope');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $officers = $request->input('officer');
        $selected_officers = json_decode($officers);
        $other_details = $request->input('other_details');
        $user_id = $this->user_id;
        $table = $data['table_name'];

        unset($data['_token']);
        unset($data['id']);

        $where = array(
            'application_code' => $application_code
        );

        try {
            if (validateIsNumeric($id)) {
                $where_app = array('id' => $id);

                $previous_data = getPreviousRecords($table, $where_app);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                unset($data['id']);

                $data['altered_by'] = $user_id;

                $res = updateRecord($table, $previous_data, $where_app, $data, $user_id);
            } else {
                foreach ($selected_officers as $selected_officer) {
                    $params = array(
                        'application_code' => $application_code,
                        'officer' => $selected_officer,
                        'application_id' => $application_id,
                        'activity' => $activity,
                        'objective' => $objective,
                        'scope' => $scope,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'other_details' => $other_details,
                        'created_by' => $this->user_id
                    );

                    $activity_res = insertMultipleRecords('par_joint_activities_details', $params);
                    if ($activity_res['success'] == false) {
                        DB::rollBack();
                        return $activity_res;
                    }
                }
            }
            $res = array(
                'success' => true,
                'message' => "All is well"
            );
        } catch (\Exception $exception) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }

        return \response()->json($res);
    }
    public function getApprovedJointOperations(Request $req)
    {
        $tracking_no = $req->input('tracking_no');
        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_jointOperation_information as t2', 't1.joint_operation_id', '=', 't2.id')
                ->leftJoin('par_departments as t3', 't2.department_id', '=', 't3.id')
                ->leftJoin('tra_approval_recommendations as t4', 't1.application_code', '=', 't4.application_code')
                ->leftJoin('tra_manager_approved_operations as t5', 't1.application_code', '=', 't5.application_code')
                ->select('t1.*', 't2.start_date as start_date', 't2.end_date', 't2.department_name', 't2.organizing_officer', 't2.activity', 't3.name as department_id', 't4.decision_id')
                ->where(array('t1.sub_module_id' => 106, 't4.decision_id' => 1));
            if ($tracking_no != '') {
                $qry->where('t1.tracking_no', 'LIKE', '%' . $tracking_no . '%');
                $qry->orWhere('t1.reference_no', 'LIKE', '%' . $tracking_no . '%');
            }

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
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
    public function getMonitoringApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_system_statuses as t2', 't1.application_status_id', 't2.id')
                ->leftJoin('tra_submissions as t3', 't1.application_code', '=', 't3.application_code')
                ->leftJoin('tra_monitoring_information as t4', 't1.enforcement_id', '=', 't4.id')
                ->leftJoin('par_monitoring_approvals as t5', 't1.application_code', 't5.application_code')
                ->leftJoin('par_monitoring_decisions as t6', 't5.enforcement_decision_id', 't6.id')
                ->leftJoin('par_monitoring_product_information as t7', 't1.application_code', 't7.application_code')
                ->leftJoin('wf_workflow_stages as t8', 't3.current_stage', 't8.id')
                ->leftJoin('par_debrief_recommendations as t9', function ($join) use ($workflow_stage) {
                    $join->on('t1.application_code', '=', 't9.application_code')
                        ->on('t8.stage_category_id', '=', 't9.stage_category_id')
                        ->where('t3.current_stage', $workflow_stage);
                })
                // ->leftJoin('par_debrief_recommendations as t9', 't1.application_code', 't9.id')
                ->select(
                    't1.*',
                    't1.application_code',
                    't4.premise_name',
                    't4.start_date',
                    't4.end_date',
                    't5.enforcement_decision_id',
                    't2.name as application_status',
                    't8.stage_category_id',
                    't9.recommendation_id',
                    't9.medicine_records',
                    't9.expiry_date',
                    't9.validity_dispensed_prescriptions',
                    't9.compliance_personnel',
                    't9.compliance_dispensing_label',
                    't9.handling_medicines',
                    't9.remedial_actions',
                    't9.action_item',
                    't9.responsible_person'
                )
                ->groupBy('t1.application_code')
                ->where(array('t3.current_stage' => $workflow_stage, 'isDone' => 0));

            $results = $qry->orderBy('t3.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMonitoringRecommendationLogs(Request $req)
    {

        $application_code = $req->input('application_code');
        $module_id = $req->input('module_id');

        try {
            $qry = DB::table('par_debrief_recommendations as t1')
                ->leftJoin('par_recommendations as t2', 't1.recommendation_id', 't2.id')
                ->leftJoin('users as t3', 't1.created_by', 't3.id')
                ->leftJoin('wf_stage_categories as t4', 't1.stage_category_id', 't4.id')
                ->select(
                    't1.*',
                    't2.name as recommendation',
                    't1.created_on as recommendation_date',
                    DB::raw("CONCAT_WS(' ',decrypt(t3.first_name),decrypt(t3.last_name)) as recommended_by"),
                    't4.name as stage_name'
                );




            $qry->where('application_code', $application_code);
            $qry->where('module_id', $module_id);

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getPeerRecommendationLogs(Request $req)
    {

        $application_code = $req->input('application_code');
        $module_id = $req->input('module_id');

        try {
            $qry = DB::table('par_enforcement_action_recommendation as t1')
                ->leftJoin('par_enforcement_actions_type as t2', 't1.recommendation_id', 't2.id')
                ->leftJoin('users as t3', 't1.created_by', 't3.id')
                ->leftJoin('wf_stage_categories as t4', 't1.stage_category_id', 't4.id')
                ->select(
                    't1.*',
                    't2.name as recommendation',
                    't1.created_on as recommendation_date',
                    DB::raw("CONCAT_WS(' ',decrypt(t3.first_name),decrypt(t3.last_name))  as recommended_by"),
                    't4.name as stage_name'
                );


            $qry->where('application_code', $application_code);
            $qry->where('module_id', $module_id);

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMonitoringOfficerReview(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_system_statuses as t2', 't1.application_status_id', 't2.id')
                ->leftJoin('tra_submissions as t3', 't1.application_code', '=', 't3.application_code')
                ->leftJoin('tra_monitoring_information as t4', 't1.enforcement_id', '=', 't4.id')
                ->leftJoin('par_monitoring_approvals as t5', 't1.application_code', 't5.application_code')
                ->leftJoin('par_monitoring_decisions as t6', 't5.enforcement_decision_id', 't6.id')
                ->leftJoin('par_monitoring_product_information as t7', 't1.application_code', 't7.application_code')
                ->leftJoin('wf_workflow_stages as t8', 't3.current_stage', 't8.id')
                ->select(
                    't1.*',
                    't1.application_code',
                    't4.premise_name',
                    't4.start_date',
                    't4.end_date',
                    't5.enforcement_decision_id',
                    't5.comment as remarks',
                    't5.id as approval_id',
                    't2.name as application_status',
                    't8.stage_category_id'
                )
                ->where(array('t3.current_stage' => $workflow_stage, 'isDone' => 0));

            $results = $qry->orderBy('t3.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getEnforcementActionApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('par_system_statuses as t2', 't1.application_status_id', 't2.id')
                ->leftJoin('tra_submissions as t3', 't1.application_code', '=', 't3.application_code')
                ->leftJoin('tra_monitoring_information as t4', 't1.enforcement_id', '=', 't4.id')
                ->leftJoin('wf_workflow_stages as t8', 't3.current_stage', 't8.id')
                ->leftJoin('par_enforcement_action_recommendation as t9', function ($join) use ($workflow_stage) {
                    $join->on('t1.application_code', '=', 't9.application_code')
                        ->on('t8.stage_category_id', '=', 't9.stage_category_id')
                        ->where('t3.current_stage', $workflow_stage);
                })
                ->select(
                    't1.*',
                    't2.name as application_status',
                    't4.premise_name',
                    't4.start_date',
                    't4.end_date',
                    't8.stage_category_id',
                    't9.recommendation_id',
                    't9.remarks',
                    't9.lead_investigator',
                    't9.co_investigators',
                    't9.corrective_action'
                )
                ->where(array('t3.current_stage' => $workflow_stage, 'isDone' => 0));

            $results = $qry->orderBy('t3.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    function saveMonitoringProductInformation(Request $request)
    {
        $id = $request->input('id');
        $data = $request->all();
        $medicine_name = $request->input('brand_name');
        $application_code = $request->input('application_code');
        $user_id = $this->user_id;
        $table = $data['table_name'];
        unset($data['_token']);
        unset($data['table_name']);
        unset($data['id']);
        try {
            if (validateIsNumeric($id)) {
                $where_app = array('id' => $id);

                $previous_data = getPreviousRecords($table, $where_app);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                unset($data['id']);

                $data['altered_by'] = $user_id;

                $res = updateRecord($table, $previous_data, $where_app, $data, $user_id);
            } else {

                $data['created_on'] = Carbon::now();
                $data['created_by'] = $user_id;
                $res = insertRecord($table, $data, $user_id);
                if ($res['success'] == false) {
                    return $res;
                }
                //Updating the presribing compliance grid
                $product_id = $res['record_id'];
                $prescribing_data = array(
                    'product_id' => $product_id,
                    'medicine_name' => $medicine_name,
                    'application_code' => $application_code,
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id,
                );

                $update_prescribing_data = insertRecord('par_prescribing_compliance_data', $prescribing_data, $user_id);

                if ($update_prescribing_data['success'] == false) {
                    return $update_prescribing_data;
                }
            }

            $res = array(
                'success' => true,
                'message' => "Saved Successfully"
            );
        } catch (\Exception $exception) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }

        return \response()->json($res);
    }
    function saveMonitoringPremisePersonnel(Request $request)
    {
        $id = $request->input('id');
        $data = $request->all();
        $personnel_name = $request->input('name');
        $reg_number = $request->input('reg_number');
        $application_code = $request->input('application_code');
        $user_id = $this->user_id;
        $table = $data['table_name'];
        unset($data['_token']);
        unset($data['table_name']);
        unset($data['id']);
        try {
            if (validateIsNumeric($id)) {
                $where_app = array('id' => $id);

                $previous_data = getPreviousRecords($table, $where_app);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                unset($data['id']);

                $data['altered_by'] = $user_id;
                $res = updateRecord($table, $previous_data, $where_app, $data, $user_id);
            } else {

                $data['created_on'] = Carbon::now();
                $data['created_by'] = $user_id;
                $res = insertRecord($table, $data, $user_id);
                if ($res['success'] == false) {
                    return \response()->json($res);
                }
                //Updating the dispensing compliance grid
                $personnel_id = $res['record_id'];
                $dispensing_data = array(
                    'personnel_id' => $personnel_id,
                    'dispensing_name' => $personnel_name,
                    'reg_number' => $reg_number,
                    'application_code' => $application_code,
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id,
                );

                $update_dispensing_data = insertRecord('par_dispensing_compliance_data', $dispensing_data, $user_id);

                if ($update_dispensing_data['success'] == false) {
                    return $update_dispensing_data;
                }
            }

            $res = array(
                'success' => true,
                'personnel_id' => $personnel_id,
                'message' => "Saved Successfully"
            );
        } catch (\Exception $exception) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {

            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }

        return \response()->json($res);
    }
    public function getMonitoringProductInformation(Request $request)
    {
        $module_id = $request->input('module_id');
        $application_code = $request->input('application_code');
        $workflow_stage = $request->input('workflow_stage_id');
        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_monitoring_information as t2', 't1.enforcement_id', '=', 't2.id')
                ->leftJoin('par_monitoring_product_information as t3', 't1.application_code', 't3.application_code')
                ->leftJoin('tra_submissions as t4', 't1.application_code', '=', 't4.application_code')
                ->leftJoin('par_schedule_types as t5', 't3.product_schedule', '=', 't5.id')
                ->select(
                    't3.*',
                    't3.brand_name',
                    't3.product_schedule',
                    't5.id as product_schedule',
                    't5.name as product_schedule',
                    't3.common_name',
                    't3.expiry_date',
                    't3.batch_number',
                    't3.is_registered'
                )
                ->where(array('t4.current_stage' => $workflow_stage, 'isDone' => 0))
                ->where('t1.application_code', $application_code);

            $results = $qry->orderBy('t3.id', 'asc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getPremiseResponsibleProfessional(Request $request)
    {
        $module_id = $request->input('module_id');
        $application_code = $request->input('application_code');
        $workflow_stage = $request->input('workflow_stage_id');

        try {
            $qry = DB::table('par_monitoring_premise_personnel as t1')
                ->leftJoin('par_personnel_positions as t2', 't1.position_id', 't2.id')
                ->leftJoin('par_personnel_qualifications as t3', 't1.qualification_id', 't3.id')
                ->where('t1.application_code', $application_code)
                ->select('t1.*', 't2.name as position', 't3.name as qualification');
            $results = $qry->get();

            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }

    public function getPrescribingComplianceInformation(Request $request)
    {
        $application_code = $request->input('application_code');
        $user_id = $this->user_id;
        $prescribing_table = 'par_prescribing_compliance_data';

        $where_application = array(
            'application_code' => $application_code
        );

        try {

            // if(recordExists($prescribing_table ,$where_application)){

            //     $delete_prescribing = deleteRecord($prescribing_table,$where_application,$user_id);
            //     if ($delete_prescribing['success'] == false) {
            //          DB::rollBack();
            //          return $delete_prescribing;
            //      }
            //  }
            //  //get product details
            //  $product_details=DB::table('par_monitoring_product_information as t1')
            //  ->select(DB::raw('t1.*'))
            //  ->where('t1.application_code',$application_code);
            //  $product_details = $product_details->get();

            //  foreach($product_details as $product_detail){           
            //      $prescribing_data =array(
            //          'application_code' => $application_code,
            //          'medicine_name' => $product_detail->brand_name,
            //          'created_on' => Carbon::now(),
            //          'created_by' => $user_id,
            //      );
            //      $insert_prescribing_data = insertRecord('par_prescribing_compliance_data', $prescribing_data,$user_id);

            //  }

            $results = DB::table('par_prescribing_compliance_data as t1')
                ->where('t1.application_code', $application_code)
                ->select(DB::raw('t1.*'));

            $res = array(
                'success' => true,
                'results' => $results->get(),
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($throwable->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }


        return \response()->json($res);
    }
    public function getDispensingComplianceInformation(Request $request)
    {
        $application_code = $request->input('application_code');
        $user_id = $this->user_id;
        $dispensing_table = 'par_dispensing_compliance_data';

        $where_application = array(
            'application_code' => $application_code
        );

        try {

            // if(recordExists($dispensing_table ,$where_application)){
            //    $delete_dispensing = deleteRecord($dispensing_table,$where_application,$user_id);
            //    if ($delete_dispensing['success'] == false) {
            //         DB::rollBack();
            //         return $delete_dispensing;
            //     }
            // }
            // //get repsonsible users
            // $responsible_persons=DB::table('par_monitoring_premise_personnel as t1')
            // ->select(DB::raw('t1.*'))
            // ->where('t1.application_code',$application_code);
            // $personnels = $responsible_persons->get();

            // foreach($personnels as $personnel){           
            //     $dispensing_data =array(
            //         'application_code' => $application_code,
            //         'dispensing_name' => $personnel->name,
            //         'reg_number' => $personnel->reg_number,
            //         'created_on' => Carbon::now(),
            //         'created_by' => $user_id,
            //     );
            //     $insert_dispensing_data = insertRecord('par_dispensing_compliance_data', $dispensing_data,$user_id);

            // }
            $results = DB::table('par_dispensing_compliance_data as t1')
                ->select(DB::raw('t1.*'))
                ->where('t1.application_code', $application_code);

            $res = array(
                'success' => true,
                'results' => $results->get(),
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($throwable->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }


        return \response()->json($res);
    }
    public function getControlledDispensingInformation(Request $request)
    {
        $application_code = $request->input('application_code');
        try {
            $results = DB::table('par_controlled_dispensing_data as t1')
                ->leftJoin('par_schedule_types as t2', 't1.product_schedule', 't2.id')
                ->leftJoin('par_confirmations as t3', 't1.dispenser_authorized', 't3.id')
                ->select('t1.*', 't2.name as product_schedule_name', 't3.name as dispenser_authorized_name')
                ->where('t1.application_code', $application_code);

            $res = array(
                'success' => true,
                'results' => $results->get(),
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($throwable->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }


        return \response()->json($res);
    }
    public function getMonitoringPremisePeronnel(Request $request)
    {
        $application_code = $request->input('application_code');
        try {
            $results = DB::table('par_monitoring_premise_personnel as t1')
                ->leftJoin('par_personnel_positions as t2', 't1.position_id', '=', 't2.id')
                ->leftJoin('par_personnel_qualifications as t3', 't1.qualification_id', '=', 't3.id')
                ->select(DB::raw('t1.*,t2.name as position,t3.name as qualification'))
                ->where('t1.application_code', $application_code);


            $res = array(
                'success' => true,
                'results' => $results->get(),
                'message' => 'All is Well'
            );
        } catch (\Exception $exception) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($exception->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        } catch (\Exception $throwable) {
            //defaults
            $function = "failed to fetch";
            //class
            $class_array = explode('\\', __CLASS__);
            if (isset($class_array[5])) {
                $class = $class_array[5];
            } else {
                $class = "Failed to fetch";
            }
            //specifics
            $me = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (isset($me[0]['function'])) {
                $function = $me[0]['function'];
            }
            if (isset($me[0]['class'])) {
                $class = $me[0]['class'];
            }
            $res = sys_error_handler($throwable->getMessage(), 2, "function-->" . $function . " class-->" . $class, \Auth::user()->id);
        }


        return \response()->json($res);
    }
    public function saveMonitoringRecommendationDetails(Request $req)
    {
        try {
            $user_id = \Auth::user()->id;
            $post_data = $req->all();
            $table_name = 'par_debrief_recommendations';
            $id = $post_data['recommendation_record_id'];
            $stage_category_id = $post_data['stage_category_id'];
            //unset unnecessary values
            unset($post_data['_token']);
            unset($post_data['table_name']);
            unset($post_data['model']);
            unset($post_data['id']);
            unset($post_data['recommendation_record_id']);
            unset($post_data['unset_data']);
            $unsetData = $req->input('unset_data');
            if (isset($unsetData)) {
                $unsetData = explode(",", $unsetData);
                $post_data = unsetArrayData($post_data, $unsetData);
            }

            $table_data = $post_data;
            //add extra params
            $table_data['created_on'] = Carbon::now();
            $table_data['created_by'] = $user_id;
            $where = array(
                'id' => $id
            );
            $res = array();
            if (isset($id) && $id != "") {
                if (recordExists($table_name, $where)) {
                    unset($table_data['created_on']);
                    unset($table_data['created_by']);
                    $table_data['dola'] = Carbon::now();
                    $table_data['altered_by'] = $user_id;

                    $previous_data = getPreviousRecords($table_name, $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res = updateRecord($table_name, $previous_data, $where, $table_data, $user_id);
                }
            } else {
                $res = insertRecord($table_name, $table_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return response()->json($res);
    }
    public function onEnforcementDecision(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $sub_module_id = $req->sub_module_id;
            $module_id = $req->module_id;
            $enforcement_decision_id = $req->enforcement_decision_id;
            $remarks = $req->remarks;
            $approval_id = $req->approval_id;
            $user_id = $this->user_id;
            $app_data = array(
                'module_id' => $req->module_id,
                'application_code' => $req->application_code,
                'enforcement_decision_id' => $req->enforcement_decision_id,
                'comment' => $req->remarks,
                'approved_by' => $req->user_id
            );

            if (validateIsNumeric($approval_id)) {
                $app_data = array(
                    'enforcement_decision_id' => $req->enforcement_decision_id,
                    'comment' => $req->remarks,
                    'approved_by' => $user_id
                );
                $where = array(
                    'application_code' => $application_code
                );

                $previous_data = getPreviousRecords('par_monitoring_approvals', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_monitoring_approvals', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_monitoring_approvals', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getCompliantRegisterApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        $tracking_no = $request->input('tracking_no');
        // $table_name = getTableName($module_id);
        //dd($table_name);
        try {
            $qry = DB::table('par_monitoring_approvals as t0')
                ->leftJoin('tra_enforcement_applications as t1', 't0.application_code', 't1.application_code')
                ->leftJoin('par_system_statuses as t2', 't1.application_status_id', 't2.id')
                ->leftJoin('tra_submissions as t3', 't1.application_code', '=', 't3.application_code')
                ->leftJoin('tra_monitoring_information as t4', 't1.enforcement_id', '=', 't4.id')
                ->select('t0.*', 't1.*', 't4.*', 't1.created_on as assesment_start_date', 't1.id as application_id', 't1.application_code as application_code')
                ->where('t0.enforcement_decision_id', 1);
            if ($tracking_no != '') {
                $qry->where('t1.tracking_no', 'LIKE', '%' . $tracking_no . '%');
                $qry->orWhere('t1.reference_no', 'LIKE', '%' . $tracking_no . '%');
            }
            $results = $qry->orderBy('t3.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveEnforcementActionRecommendationDetails(Request $req)
    {
        try {
            $user_id = \Auth::user()->id;
            $post_data = $req->all();
            $table_name = 'par_enforcement_action_recommendation';
            $id = $post_data['recommendation_record_id'];
            $recommendation_id = $post_data['recommendation_id'];
            $stage_category_id = $post_data['stage_category_id'];
            $co_investigator = $req->input('co_investigators');
            $selected_investigators = json_decode($co_investigator);


            //unset unnecessary values
            unset($post_data['_token']);
            unset($post_data['table_name']);
            unset($post_data['model']);
            unset($post_data['id']);
            unset($post_data['recommendation_record_id']);
            unset($post_data['unset_data']);
            $unsetData = $req->input('unset_data');
            if (isset($unsetData)) {
                $unsetData = explode(",", $unsetData);
                $post_data = unsetArrayData($post_data, $unsetData);
            }

            $table_data = $post_data;
            //add extra params
            $table_data['created_on'] = Carbon::now();
            $table_data['created_by'] = $user_id;
            $where = array(
                'id' => $id
            );
            $res = array();
            if (isset($id) && $id != "") {
                if (recordExists($table_name, $where)) {
                    unset($table_data['created_on']);
                    unset($table_data['created_by']);
                    $table_data['dola'] = Carbon::now();
                    $table_data['altered_by'] = $user_id;

                    $previous_data = getPreviousRecords($table_name, $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res = updateRecord($table_name, $previous_data, $where, $table_data, $user_id);
                }
            } else {
                if ($recommendation_id == 2) {
                    $res = insertRecord($table_name, $table_data);
                    // foreach($selected_investigators as $selected_investigator){
                    //     $params = array(
                    //         'application_code' => $post_data['application_code'],
                    //         'stage_category_id' => $post_data['stage_category_id'],
                    //         'recommendation_id' =>$post_data['recommendation_id'],
                    //         'remarks' => $post_data['remarks'],
                    //         'corrective_action' => $post_data['corrective_action'],
                    //         'lead_investigator' => $post_data['lead_investigator'],
                    //         'co_investigators' => $selected_investigator,
                    //         'created_by' => $this->user_id
                    //     );

                    //     $res = insertRecord($table_name, $params);
                    //     if ($res['success'] == false) {
                    //         DB::rollBack();
                    //         return $res;
                    //     }
                    // }
                } else {
                    $res = insertRecord($table_name, $table_data);
                    if ($res['success'] == false) {
                        DB::rollBack();
                        return $res;
                    }
                }
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return response()->json($res);
    }
    public function getMonitoringPendingCases(Request $request)
    {
        $tracking_no = $request->input('tracking_no');
        try {
            $qry = DB::table('par_enforcement_action_recommendation as t1')
                ->leftJoin('tra_enforcement_applications as t2', 't1.application_code', 't2.application_code')
                ->leftJoin('par_system_statuses as t3', 't2.application_status_id', 't3.id')
                ->leftJoin('tra_submissions as t4', 't1.application_code', '=', 't4.application_code')
                ->leftJoin('tra_monitoring_information as t5', 't2.enforcement_id', '=', 't5.id')
                ->leftJoin('par_enforcement_actions_type as t6', 't1.recommendation_id', 't6.id')
                ->select('t1.*', 't2.*', 't1.created_on as recommended_on', 't5.premise_name', 't5.start_date', 't5.end_date')
                ->where(array('t4.isDone' => 0));
            if ($tracking_no != '') {
                $qry->where('t2.tracking_no', 'LIKE', '%' . $tracking_no . '%');
                $qry->orWhere('t2.reference_no', 'LIKE', '%' . $tracking_no . '%');
            }
            $results = $qry->orderBy('t3.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveJointDetectedOffence(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $active_application_code = $req->active_application_code;
            $record_id = $req->id;
            $offence_type_id = $req->offence_type_id;
            $is_facility_based = $req->is_facility_based;
            $is_facility_registered = $req->is_facility_registered;
            $permit_no = $req->permit_no;
            $premise_name = $req->premise_name;
            $physical_address = $req->physical_address;
            $telephone = $req->telephone;
            $new_premise_name = $req->new_premise_name;
            $new_physical_address = $req->new_physical_address;
            $new_telephone = $req->new_telephone;
            $individual_name = $req->individual_name;
            $individual_physical_address = $req->individual_physical_address;
            $individual_designation = $req->individual_designation;
            $individual_telephone = $req->individual_telephone;
            $action = $req->action;
            $remarks = $req->remarks;
            $user_id = $this->user_id;
            $app_data = array(
                'active_application_code' => $active_application_code,
                'is_facility_registered' => $is_facility_registered,
                'is_facility_based' => $is_facility_based,
                'offence_type_id' => $offence_type_id,
                'permit_no' => $permit_no,
                'premise_name' => $premise_name,
                'physical_address' => $physical_address,
                'telephone' => $telephone,
                'new_premise_name' => $new_premise_name,
                'new_physical_address' => $new_physical_address,
                'new_telephone' => $new_telephone,
                'individual_name' => $individual_name,
                'individual_physical_address' => $individual_physical_address,
                'individual_designation' => $individual_designation,
                'individual_telephone' => $individual_telephone,
                'action' => $action,
                'remarks' => $remarks,

            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );
                $previous_data = getPreviousRecords('par_joint_detected_offences', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_joint_detected_offences', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_joint_detected_offences', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getjointOperationOffenceDetails(Request $request)
    {
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_joint_detected_offences as t1')
                ->leftjoin('par_offence_types as t2', 't1.offence_type_id', '=', 't2.id')
                ->where('t1.active_application_code', $application_code)
                ->select('t1.*', 't2.name as offence', DB::raw("CASE WHEN t1.premise_name is NULL AND  t1.new_premise_name is NULL THEN t1.individual_name WHEN t1.new_premise_name is NULL AND t1.individual_name is NULL THEN t1.premise_name WHEN t1.premise_name is NULL AND t1.individual_name is NULL THEN t1. new_premise_name END premise,
               CASE WHEN t1.physical_address is NULL THEN t1.new_physical_address WHEN t1.new_physical_address is NULL THEN t1.physical_address END address,
               CASE WHEN t1.telephone is NULL THEN t1.new_telephone WHEN t1.new_telephone is NULL THEN t1.telephone END telephone_number"));
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveOperatives(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $active_application_code = $req->active_application_code;
            $record_id = $req->id;
            $internal = $req->internal;
            $internal_no_deployed = $req->internal_no_deployed;
            $external = $req->external;
            $external_no_deployed = $req->external_no_deployed;
            $user_id = $this->user_id;
            $app_data = array(
                'active_application_code' => $active_application_code,
                'internal' => $internal,
                'internal_no_deployed' => $internal_no_deployed,
                'external' => $external,
                'external_no_deployed' => $external_no_deployed,

            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );

                $previous_data = getPreviousRecords('par_operatives', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_operatives', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_operatives', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getOperatives(Request $request)
    {
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_operatives as t1')
                ->where('t1.active_application_code', $application_code)
                ->select('t1.*');
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveOperationSummary(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $active_application_code = $req->active_application_code;
            $record_id = $req->id;
            $objective = $req->objective;
            $scope = $req->scope;
            $summary = $req->summary;
            $conclusion = $req->conclusion;
            $recommendations = $req->recommendations;
            $is_facility_registered = $req->is_facility_registered;
            $permit_no = $req->permit_no;
            $premise_name = $req->premise_name;
            $physical_address = $req->physical_address;
            $telephone = $req->telephone;
            $new_premise_name = $req->new_premise_name;
            $new_physical_address = $req->new_physical_address;
            $new_telephone = $req->new_telephone;
            $action_id = $req->action_id;
            $user_id = $this->user_id;
            $investigator = $req->input('investigator');
            $co_investigator = $req->input('co_investigator');
            $co_investigators = json_decode($co_investigator);
            $app_data = array(
                'active_application_code' => $active_application_code,
                'objective' => $objective,
                'scope' => $scope,
                'summary' => $summary,
                'conclusion' => $conclusion,
                'recommendations' => $recommendations,
                'is_facility_registered' => $is_facility_registered,
                'permit_no' => $permit_no,
                'premise_name' => $premise_name,
                'physical_address' => $physical_address,
                'telephone' => $telephone,
                'new_premise_name' => $new_premise_name,
                'new_physical_address' => $new_physical_address,
                'new_telephone' => $new_telephone,
                'action_id' => $action_id,

            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );
                $previous_data = getPreviousRecords('par_operation_summary', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_operation_summary', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_operation_summary', $app_data);
                if (is_array($co_investigators)) {
                    foreach ($co_investigators as $co_investigators) {
                        $params = array(
                            'application_code' => $active_application_code,
                            'co_investigator' => $co_investigators,
                            'investigator' => $investigator,
                            'created_by' => $this->user_id
                        );
                        $res1 = insertMultipleRecords('tra_investigator_details', $params);
                        if ($res['success'] == false) {
                            DB::rollBack();
                            return $res1;
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getOperationSummary(Request $request)
    {
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_operation_summary as t1')
                ->leftjoin('par_joint_decisions as t2', 't1.action_id', '=', 't2.id')
                ->where('t1.active_application_code', $application_code)
                ->select('t1.*', 't2.name as action', DB::raw("CASE WHEN t1.premise_name is NULL THEN t1.new_premise_name WHEN t1.new_premise_name is NULL THEN t1.premise_name END facility"));
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveJointProducts(Request $request)
    {
        try {
            $table_name = $request->table_name;
            $active_application_code = $request->active_application_code;
            $user_id = $this->user_id;
            $record_id = $request->id;
            $app_data = array(
                'application_code' => $active_application_code,
                'batch_number' => $request->batch_number,
                'expiry_date' => $request->expiry_date,
                'brand_name' => $request->brand_name,
                'product_description' => $request->product_description,
                'dosage_form' => $request->dosage_form,
                'pack_size' => $request->pack_size,
                'manufacturer' => $request->manufacturer,
                'certificate_no' => $request->certificate_no,
                'new_product' => $request->new_product,
                'quantity' => $request->quantity,
                'offence_type_id' => $request->offence_type_id,
                'type_of_offender_id' => $request->type_of_offender_id,
                // 'premise_name'=>$request->premise_name,
                'facility' => $request->facility,
                'joint_offence_id' => $request->joint_offence_id,
                'is_registered' => $request->is_registered
            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );

                $previous_data = getPreviousRecords('par_joint_seized_products', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_joint_seized_products', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_joint_seized_products', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getjointSeizedProducts(Request $request)
    {
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_joint_seized_products as t1')
                ->leftjoin('par_offence_types as t2', 't1.offence_type_id', '=', 't2.id')
                ->where('t1.application_code', $application_code)
                ->select('t1.*', 't2.name as offence', DB::raw("CASE WHEN t1.brand_name is NULL THEN t1.new_product WHEN t1.new_product is NULL THEN t1.brand_name END brand_name"));
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getManufacturingSites(Request $request)
    {
        try {
            $qry = DB::table('tra_manufacturing_sites as t1')
                //->where('t1.application_code', $application_code)
                ->select('t1.*');
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveInquiryDetails(Request $request)
    {
        try {
            $table_name = $request->table_name;
            $active_application_code = $request->active_application_code;
            $user_id = $this->user_id;
            $record_id = $request->id;
            $app_data = array(
                'active_application_code' => $active_application_code,
                'inquiry_name' => $request->inquiry_name,
                'inquiry_information' => $request->inquiry_information,
                'inquiry_relevance' => $request->inquiry_relevance,
                'offence_id' => $request->offence_id,
            );
            if (validateIsNumeric($record_id)) {
                $app_data['dola'] = Carbon::now();
                $app_data['altered_by'] = $user_id;
                $where = array(
                    'id' => $record_id
                );
                $previous_data = getPreviousRecords('par_case_inquiry_details', $where);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res = updateRecord('par_case_inquiry_details', $previous_data, $where, $app_data, $user_id);
            } else {
                $res = insertRecord('par_case_inquiry_details', $app_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getInquiryDetails(Request $request)
    {
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_case_inquiry_details as t1')
                ->leftJoin('par_suspected_offence as t3', 't1.offence_id', '=', 't3.id')
                ->leftJoin('par_offence_types as t4', 't3.offence_type_id', '=', 't4.id')
                ->where('t1.active_application_code', $application_code)
                ->select('t1.*', 't4.name as offence_charged');
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveInvestigationComments(Request $req)
    {
        try {
            $user_id = \Auth::user()->id;
            $post_data = $req->all();
            $table_name = 'tra_case_investigation_comments';
            $id = $post_data['id'];
            $workflow_stage_id = $post_data['workflow_stage_id'];
            //unset unnecessary values
            unset($post_data['_token']);
            unset($post_data['table_name']);
            unset($post_data['model']);
            unset($post_data['id']);
            unset($post_data['unset_data']);
            $unsetData = $req->input('unset_data');
            if (isset($unsetData)) {
                $unsetData = explode(",", $unsetData);
                $post_data = unsetArrayData($post_data, $unsetData);
            }

            $table_data = $post_data;
            //add extra params
            $table_data['created_on'] = Carbon::now();
            $table_data['created_by'] = $user_id;
            $where = array(
                'id' => $id
            );
            $res = array();
            //check stage category
            if (!validateIsNumeric($workflow_stage_id)) {
                return array('success' => false, 'message' => "Faild to fetch stage details");
            }
            $stage_data = getTableData('wf_workflow_stages', array('id' => $workflow_stage_id));
            $stage_category_id = $stage_data->stage_category_id;
            $table_data['stage_category_id'] = $stage_category_id;
            if (isset($id) && $id != "") {
                if (recordExists($table_name, $where)) {
                    unset($table_data['created_on']);
                    unset($table_data['created_by']);
                    $table_data['dola'] = Carbon::now();
                    $table_data['altered_by'] = $user_id;

                    $previous_data = getPreviousRecords($table_name,  $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res = updateRecord($table_name,  $previous_data, $where, $table_data, $user_id);
                }
            } else {
                $res = insertRecord($table_name, $table_data);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return response()->json($res);
    }
    public function getInvestigationsComments(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('active_application_code');
        $workflow_stage_id = $request->input('workflow_stage_id');
        $comment_type_id = $request->input('comment_type_id');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('tra_case_investigation_comments as t1')
                ->leftJoin('users as t2', 't1.created_by', '=', 't2.id')
                ->leftJoin('wf_workflow_stages as t3', 't1.workflow_stage_id', '=', 't3.id')
                ->leftJoin('par_recommendations as t4', 't1.recommendation_id', '=', 't4.id')
                ->leftJoin('par_investigation_comments as t5', 't1.case_decision_id', '=', 't5.id')
                ->where('t1.application_code', $application_code)
                ->select(DB::raw("t1.*,CONCAT_WS(' ',decrypt(t2.first_name),decrypt(t2.last_name)) as author,
                t3.name as stage_name, t5.name as recommendation,t2.id as author_id, '.$user_id.' as current_user"));


            if (isset($workflow_stage_id) && $workflow_stage_id != '') {
                //get stage category
                $stage_data = getTableData('wf_workflow_stages', array('id' => $workflow_stage_id));
                $stage_category_id = $stage_data->stage_category_id;
                $qry->where('t1.stage_category_id', $stage_category_id);
            }
            if (isset($comment_type_id) && $comment_type_id != '') {
                //  $qry->where('t1.comment_type_id', $comment_type_id);
            }
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getStageJointOperationsApplications(Request $request)
    {
        $module_id = $request->input('module_id');
        $workflow_stage = $request->input('workflow_stage_id');
        $table_name = getTableName($module_id);
        //dd($table_name);
        try {
            $qry = DB::table($table_name . ' as t1')
                ->leftJoin('par_system_statuses as t4', 't1.application_status_id', 't4.id')
                ->leftJoin('tra_approval_recommendations as t5', 't5.application_code', '=', 't1.application_code')
                ->leftJoin('tra_submissions as t9', 't9.application_code', '=', 't1.application_code')
                ->leftJoin('tra_enforcement_information as t15', 't1.enforcement_id', '=', 't15.id')
                ->leftJoin('par_report_type as t16', 't15.report_type_id', '=', 't16.id')
                ->leftJoin('users as t10', 't9.usr_from', '=', 't10.id')
                ->leftJoin('wb_trader_account as t17', 't15.applicant_id', '=', 't17.id')
                ->leftJoin('tra_investigation_approvals as t18', 't1.application_code', 't18.application_code')

                ->leftJoin('wf_workflow_stages as t12', 't9.current_stage', 't12.id')
                ->leftJoin('tra_jointOperation_information as t24', 't1.enforcement_id', '=', 't24.id')
                ->leftJoin('users as t25', 't15.applicant_id', '=', 't17.id')
                ->leftJoin('tra_evaluation_recommendations as t13', function ($join) use ($workflow_stage) {
                    $join->on('t1.application_code', '=', 't13.application_code')
                        ->on('t12.stage_category_id', '=', 't13.stage_category_id')
                        ->where('t9.current_stage', $workflow_stage);
                })
                ->leftJoin('tra_approval_recommendations as t14', 't1.application_code', 't14.application_code')
                ->leftJoin('par_entity_types as t19', 't15.entity_type_id', 't19.id')
                // ->leftJoin('par_common_names as t23', 't15.common_name_id', 't23.id')
                //DB::raw("CONCAT(decrypt(t17.first_name,".getDecryptFunParams()."),' ',decrypt(t17.last_name,".getDecryptFunParams().")) 

                ->select(
                    't1.*',
                    't24.activity as activity',
                    't24.scope as scope',
                    't24.objective as objective',
                    't17.name as submitted_by',
                    't9.date_received as submitted_on',
                    't4.name as application_status',
                    't1.id as active_application_id',
                    't12.stage_category_id',
                    't13.id as recommendation_record_id',
                    't13.recommendation_id',
                    't13.remarks',
                    't14.decision_id as approval_decision_id'
                )
                ->where(array('t9.current_stage' => $workflow_stage, 'isDone' => 0));

            $results = $qry->orderBy('t9.id', 'desc')->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getOfficerAssignedActivity(Request $request)
    {
        $user_id = $this->user_id;
        $application_code = $request->input('active_application_code');
        try {
            $qry = DB::table('par_joint_activities_details as t1')
                ->leftJoin('users as t2', 't1.officer', '=', 't2.id')
                //->where('t1.officer',$user_id)
                ->where('t1.application_code', $application_code)
                ->select(DB::raw("t1.*,CONCAT_WS(' ',decrypt(t2.first_name),decrypt(t2.last_name))  as officer"));
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getJointOperationDetectedFacilities(Request $request)
    {
        $application_code = $request->input('active_application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_joint_detected_offences as t1')
                ->leftjoin('par_offence_types as t2', 't1.offence_type_id', '=', 't2.id')
                ->where('t1.active_application_code', $application_code)
                ->select('t1.*', 't2.name as offence', DB::raw("CASE WHEN t1.premise_name is NULL THEN t1.new_premise_name WHEN t1.new_premise_name is NULL THEN t1.premise_name END facility"));
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function submitPlannedActivities(Request $request, $inCall = 0)
    {

        $activity_date = Carbon::now();
        try {
            $qry = DB::table('par_joint_activities_details as t1')
                ->leftJoin('tra_enforcement_applications as t2', 't1.application_code', 't2.application_code')
                ->leftJoin('tra_jointOperation_information as t3', 't2.joint_operation_id', 't3.id')
                ->leftJoin('par_joint_logistics_details as t4', 't1.application_code', 't4.application_code')
                ->select(
                    't1.*',
                    't1.scope as activity_scope',
                    't1.activity as officer_activity',
                    't1.objective as activity_objective',
                    't1.other_details as other_details',
                    't1.id as activity_id',
                    't1.start_date as activity_start_date',
                    't2.*',
                    't3.*',
                    't4.*'
                );
            //$qry->where(array('t1.start_date'=>$activity_date,'t1.is_manager_approved'=>1));
            $qry->where(array('t1.start_date' => $activity_date, 't1.is_manager_approved' => 1, 't1.is_submitted' => null, 't1.is_autogenerated' => null));
            // $qry->where(array('t1.start_date'=>$activity_date,'t1.is_manager_approved'=>1,'t1.is_submitted'=>null));
            $results = $qry->get();

            //dd($results);
            if ($results) {
                foreach ($results as $results) {
                    $enforcement_data = array(
                        'joint_operation_id' => $results->joint_operation_id,
                        'department_name' => $results->department_name,
                        'organizing_officer' => $results->organizing_officer,
                        'organizing_officer_title' => $results->organizing_officer_title,
                        'email' => $results->email,
                        'address' => $results->address,
                        'phone' => $results->phone,
                        'activity' => $results->activity,
                        'objective' => $results->objective,
                        'scope' => $results->scope,
                        'start_date' => $results->start_date,
                        'end_date' => $results->end_date,
                        'internal_operative' => $results->internal_operative,
                        'external_operative' => $results->external_operative,
                        //'other_details'=>$results->other_details,
                    );

                    $officer = $results->officer;
                    $active_application_id = $results->application_id;
                    $section_id = $results->section_id;
                    $module_id = $results->module_id;
                    $sub_module_id = $results->sub_module_id;
                    $process_id = $results->process_id;
                    $workflow_stage_id = $results->workflow_stage_id;
                    $enforcement_id = $results->enforcement_id;
                    $application_code = $results->application_code;
                    $activity_id = $results->activity_id;
                    $user_id = $this->user_id;
                    $where1 = array(
                        'id' => $activity_id,

                    );
                    $mark_submitted = array(
                        'is_submitted' => 1,
                        'is_autogenerated' => 1,
                        'is_manager_approved' => 1,
                    );
                    $previous_data = getPreviousRecords('par_joint_activities_details', $where1);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res2 = updateRecord('par_joint_activities_details', $previous_data, $where1, $mark_submitted, $user_id);


                    // get logistics infor
                    $qry_logistics = DB::table('par_joint_logistics_details as t1')
                        ->select('t1.*');
                    $qry_logistics->where('t1.application_code', $application_code);
                    $results_logistics = $qry_logistics->get();

                    //dd($results_logistics);
                    // dd($enforcement_id);
                    DB::beginTransaction();
                    $applications_table = 'tra_enforcement_applications';
                    $enforcement_table = 'tra_jointOperation_information';

                    //Insert
                    //dd($enforcement_data);
                    $enforcement_res = insertRecord($enforcement_table, $enforcement_data, $user_id);
                    //dd($enforcement_res);
                    if ($enforcement_res['success'] == false) {
                        DB::rollBack();
                        return $enforcement_res;
                    }

                    //tracking the application
                    $joint_operation_id = $enforcement_res['record_id'];

                    //Application_create
                    $codes_array =  $this->getJointApplicationReferenceCodes($request);
                    $view_id = generateApplicationViewID();
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, 1, $user_id);
                    $application_code = generateApplicationCode($sub_module_id, $applications_table);
                    $application_status = getApplicationInitialStatus($module_id, $sub_module_id);

                    if ($tracking_details['success'] == false) {
                        DB::rollBack();
                        return \response()->json($tracking_details);
                    }

                    $tracking_no = $tracking_details['tracking_no'];
                    $reference_no = $tracking_details['tracking_no'];
                    $reference_no = str_replace("TRC", 'BMR', $reference_no);


                    $application_params = array(
                        'view_id' => $view_id,
                        'module_id' => $module_id,
                        'sub_module_id' => $sub_module_id,
                        'section_id' => $section_id,
                        'application_code' => $application_code,
                        'process_id' => $process_id,
                        'workflow_stage_id' => $workflow_stage_id,
                        'tracking_no' => $tracking_no,
                        'reference_no' => $reference_no,
                        'joint_operation_id' => $joint_operation_id,
                        'application_status_id' => $application_status->status_id,
                        "date_added" => Carbon::now(),
                        "created_by" => \Auth::user()->id,
                        "created_on" => Carbon::now()
                    );
                    // dd('herr');

                    $res = insertRecord($applications_table, $application_params, $user_id);

                    //dd($res);
                    // myres
                    if (!isset($res['record_id'])) {
                        DB::rollBack();
                        return $res;
                    }

                    $active_application_id = $res['record_id'];

                    $submission_params = array(
                        'application_id' => $active_application_id,
                        'view_id' => $view_id,
                        'process_id' => $process_id,
                        'application_code' => $application_code,
                        'tracking_no' => $tracking_no,
                        'reference_no' => $reference_no,
                        'usr_from' => $user_id,
                        'usr_to' => $officer,
                        'previous_stage' => $workflow_stage_id,
                        'current_stage' => 1295,
                        //1175
                        'module_id' => $module_id,
                        'sub_module_id' => $sub_module_id,
                        'section_id' => $section_id,
                        'application_status_id' => $application_status->status_id,
                        'urgency' => 1,
                        'remarks' => 'Initial save of the application',
                        'date_received' => Carbon::now(),
                        'created_on' => Carbon::now(),
                        'created_by' => $user_id
                    );
                    DB::table('tra_submissions')
                        ->insert($submission_params);
                    // }
                    //update logistics
                    // foreach($res as $res){
                    foreach ($results_logistics as $results_logistics) {
                        $logistics_data = array(
                            'name' => $results->name,
                            'description' => $results->description,
                            'quantity' => $results->quantity,
                            'amount' => $results->amount,
                            'other_details' => $results->other_details,
                            'application_code' => $application_code,
                        );
                        DB::table('par_joint_logistics_details')
                            ->insert($logistics_data);
                        // }
                    }
                    // add activities
                    $activities_data = array(
                        'activity' => $results->officer_activity,
                        'objective' => $results->activity_objective,
                        'scope' => $results->activity_scope,
                        'start_date' => $results->activity_start_date,
                        'end_date' => $results->end_date,
                        'other_details' => $results->other_details,
                        'officer' => $officer,
                        'application_code' => $application_code,
                        'is_manager_approved' => 1,
                        'is_autogenerated' => 1,
                        'is_submitted' => 1,
                    );
                    DB::table('par_joint_activities_details')
                        ->insert($activities_data);
                    // mark as submitted
                    //$res2 = updateRecord('par_joint_activities_details', $where1, $mark_submitted);
                    DB::commit();

                    $res['active_application_id'] = $active_application_id;
                    $res['process_id'] = $process_id;
                    $res['application_code'] = $application_code;
                    $res['tracking_no'] = $tracking_no;
                    $res['reference_no'] = $reference_no;
                    $res['joint_operation_id'] = $joint_operation_id;
                    $res['msg'] = 'Record Saved Successfully';
                    $res['success'] = true;
                }
            } else {
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        if ($inCall == 1) {
            return $res;
        }
        return \response()->json($res);
    }
    public function getJointApplicationReferenceCodes($application_details)
    {
        //dd($application_details);
        // $section_code = getSingleRecordColValue('par_sections', array('id' => $application_details->section_id), 'code');
        //dd($section_code);
        $section_code = 'HM';
        $codes_array = array(
            'section_code' => $section_code,
        );

        return $codes_array;
    }
    public function submitJointReportsForInvestigation(Request $request, $inCall = 0)
    {
        try {

            $qry = DB::table('par_operation_summary')
                ->where('action_id', 1);
            $results = $qry->get();
            // dd($results);
            foreach ($results as $results) {
                $enforcement_data = array(
                    'joint_operation_id' => $results->joint_operation_id,
                    'department_name' => $results->department_name,
                    'organizing_officer' => $results->organizing_officer,
                    'organizing_officer_title' => $results->organizing_officer_title,
                    'email' => $results->email,
                    'address' => $results->address,
                    'phone' => $results->phone,
                    'activity' => $results->activity,
                    'objective' => $results->objective,
                    'scope' => $results->scope,
                    'start_date' => $results->start_date,
                    'end_date' => $results->end_date,
                    'internal_operative' => $results->internal_operative,
                    'external_operative' => $results->external_operative,
                );

                $officer = $results->officer;
                $active_application_id = $results->application_id;
                $section_id = $results->section_id;
                $module_id = $results->module_id;
                $sub_module_id = $results->sub_module_id;
                $process_id = $results->process_id;
                $workflow_stage_id = $results->workflow_stage_id;
                $enforcement_id = $results->enforcement_id;
                $application_code = $results->application_code;
                $user_id = $this->user_id;
                $where1 = array(
                    'application_code' => $application_code
                );
                $mark_submitted = array(
                    'is_submitted' => 1,
                );
                // get logistics infor
                $qry_logistics = DB::table('par_joint_logistics_details as t1')
                    ->select('t1.*');
                $qry_logistics->where('t1.application_code', $application_code);
                $results_logistics = $qry_logistics->get();

                //dd($results_logistics);
                // dd($enforcement_id);
                DB::beginTransaction();
                $applications_table = 'tra_enforcement_applications';
                $enforcement_table = 'tra_jointOperation_information';

                //Insert
                //dd($enforcement_data);
                $enforcement_res = insertRecord($enforcement_table, $enforcement_data, $user_id);
                //dd($enforcement_res);
                if ($enforcement_res['success'] == false) {
                    DB::rollBack();
                    return $enforcement_res;
                }

                //tracking the application
                $joint_operation_id = $enforcement_res['record_id'];

                //Application_create
                $codes_array =  $this->getJointApplicationReferenceCodes($request);
                $view_id = generateApplicationViewID();
                $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, 1, $user_id);
                $application_code = generateApplicationCode($sub_module_id, $applications_table);
                $application_status = getApplicationInitialStatus($module_id, $sub_module_id);

                if ($tracking_details['success'] == false) {
                    DB::rollBack();
                    return \response()->json($tracking_details);
                }

                $tracking_no = $tracking_details['tracking_no'];
                $reference_no = $tracking_details['tracking_no'];
                $reference_no = str_replace("TRC", 'BMR', $reference_no);


                $application_params = array(
                    'view_id' => $view_id,
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_code' => $application_code,
                    'process_id' => $process_id,
                    'workflow_stage_id' => $workflow_stage_id,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'joint_operation_id' => $joint_operation_id,
                    'application_status_id' => $application_status->status_id,
                    "date_added" => Carbon::now(),
                    "created_by" => \Auth::user()->id,
                    "created_on" => Carbon::now()
                );
                // dd('herr');

                $res = insertRecord($applications_table, $application_params, $user_id);

                //dd($res);
                // myres
                if (!isset($res['record_id'])) {
                    DB::rollBack();
                    return $res;
                }

                $active_application_id = $res['record_id'];

                $submission_params = array(
                    'application_id' => $active_application_id,
                    'view_id' => $view_id,
                    'process_id' => $process_id,
                    'application_code' => $application_code,
                    'tracking_no' => $tracking_no,
                    'reference_no' => $reference_no,
                    'usr_from' => $user_id,
                    'usr_to' => $officer,
                    'previous_stage' => $workflow_stage_id,
                    'current_stage' => 1175,
                    //1175
                    'module_id' => $module_id,
                    'sub_module_id' => $sub_module_id,
                    'section_id' => $section_id,
                    'application_status_id' => $application_status->status_id,
                    'urgency' => 1,
                    'remarks' => 'Initial save of the application',
                    'date_received' => Carbon::now(),
                    'created_on' => Carbon::now(),
                    'created_by' => $user_id
                );
                DB::table('tra_submissions')
                    ->insert($submission_params);
                // }
                //update logistics
                // foreach($res as $res){
                foreach ($results_logistics as $results_logistics) {
                    $logistics_data = array(
                        'name' => $results->name,
                        'description' => $results->description,
                        'quantity' => $results->quantity,
                        'amount' => $results->amount,
                        'other_details' => $results->other_details,
                        'application_code' => $application_code,
                    );
                    DB::table('par_joint_logistics_details')
                        ->insert($logistics_data);
                    // }
                }
                // add activities
                $activities_data = array(
                    'activity' => $results->officer_activity,
                    'objective' => $results->activity_objective,
                    'scope' => $results->activity_scope,
                    'start_date' => $results->activity_start_date,
                    'end_date' => $results->end_date,
                    'other_details' => $results->other_details,
                    'officer' => $officer,
                    'application_code' => $application_code,
                    'is_manager_approved' => 1,
                    'is_submitted' => 1,
                );
                DB::table('par_joint_activities_details')
                    ->insert($activities_data);
                // mark as submitted
                $previous_data = getPreviousRecords('par_joint_activities_details', $where1);
                if ($previous_data['success'] == false) {
                    return $previous_data;
                }
                $previous_data = $previous_data['results'];
                $res2 = updateRecord('par_joint_activities_details', $previous_data, $where1, $mark_submitted, $user_id);
                DB::commit();

                $res['active_application_id'] = $active_application_id;
                $res['process_id'] = $process_id;
                $res['application_code'] = $application_code;
                $res['tracking_no'] = $tracking_no;
                $res['reference_no'] = $reference_no;
                $res['joint_operation_id'] = $joint_operation_id;
                $res['msg'] = 'Record Saved Successfully';
                $res['success'] = true;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        if ($inCall == 1) {
            return $res;
        }
        return \response()->json($res);
    }


    public function saveOfficerSignatureDetails(Request $request)
    {
        $approval_id = $request->approval_id;
        $application_code = $request->application_code;
        $sub_module_id = $request->sub_module_id;
        $module_id = $request->module_id;
        $decision_id = $request->decision_id;
        $approval_date = $request->approval_date;
        $expiry_date = $request->expiry_date;
        $remarks = $request->remarks;
        $table_name = getTableName($module_id);
        // $module_id = $app_details->module_id;
        // $sub_module_id = $app_details->sub_module_id;
        $table_name = $request->input('table_name');
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $qry = DB::table($table_name)
            ->where('id', $application_id);
        $app_details = $qry->first();
        //dd( $app_details);
        if (is_null($app_details)) {
            $res = array(
                'success' => false,
                'message' => 'Problem encountered while getting application details!!'
            );
            return $res;
        }
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

                $user_id = $this->user_id; //sign_file


                $where = array(
                    'application_code' => $application_code
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
                    'permit_signatory' => $permit_signatory,
                    'sign_file' => $request->sign_file
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
                    $prev_data = getPreviousRecords('tra_officer_signature', $where);
                    if ($prev_data['success'] == false) {
                        return \response()->json($prev_data);
                    }
                    $prev_data_results = $prev_data['results'];
                    $prev_decision_id = $prev_data_results[0]['decision_id'];
                    $prev_data_results[0]['record_id'] = $id;
                    $prev_data_results[0]['update_by'] = $user_id;
                    $prev_data_results[0]['recommendation_id'] = $prev_data_results[0]['id'];
                    unset($prev_data_results[0]['id']);
                    DB::table('tra_officer_signature_log')
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

                    $previous_data = getPreviousRecords('tra_officer_signature', $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res = updateRecord('tra_officer_signature', $previous_data, $where, $params, $user_id);
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
                    $res = insertRecord('tra_officer_signature', $params, $user_id);
                    // dd($res);
                    $id = $res['record_id'];
                }

                $UpdateParams['validity_status'] = $validity_status_id;
                $UpdateParams['registration_status'] = $registration_status_id;

                if ($decision_id == 1) {
                    $portal_status_id = 10;
                } else {
                    $portal_status_id = 11;
                }
            }, 5);
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return $res;
    }



    public function submitApplicationForInvestigation(Request $request)
    {

        $qry = DB::table('par_joint_detected_offences as t1') //par_joint_detected_offences
            ->leftJoin('par_operation_summary as t2', 't1.active_application_code', 't2.active_application_code')
            ->leftJoin('tra_investigator_details as t3', 't1.active_application_code', 't3.application_code')
            ->select('t1.*', 't2.*', 't3.*');
        $qry->where(array('t2.action_id' => 1, 't1.is_submitted' => null, 't2.is_manager_approved' => 1));
        $results = $qry->get();
        //dd($results);
        if ($results) {
            foreach ($results as $results) {
                $application_code = $results->active_application_code;
                $where = array(
                    'active_application_code' => $application_code
                );
                $mark_as_submitted = array(
                    'is_submitted' => 1
                );
                //get seized product details
                $qry = DB::table('par_joint_seized_products as t1') //par_joint_detected_offences
                    ->select('t1.*');
                $qry->where('t1.application_code', $application_code);
                $product_results = $qry->get();
                //get investigator details
                $qry = DB::table('tra_investigator_details as t1') //
                    ->select('t1.*');
                $qry->where('t1.application_code', $application_code);
                $investigator_results = $qry->get();

                try {
                    $sub_module_id = 86;
                    $module_id = 8;
                    $section_id = 2;
                    $workflow_stage_id = 1131;
                    $process_id = 183;
                    $user_id = $this->user_id;
                    //offences
                    $enforcement_data = array(
                        'offence_type_id' => $results->offence_type_id,
                        'details' => $results->details,
                        'remarks' => $results->remarks,
                        'permit_no' => $results->permit_no,
                        'premise_name' => $results->premise_name,
                        'new_premise_name' => $results->new_premise_name,
                        'no_of_offence' => $results->no_of_offence,
                        'action' => $results->action,
                        'is_facility_registered' => $results->is_facility_registered,
                        'section_id' => 2
                    );
                    DB::beginTransaction();
                    $applications_table = 'tra_enforcement_applications';
                    $enforcement_table = 'tra_joint_reported_investigation';
                    //dd($enforcement_data);
                    $enforcement_res = insertRecord($enforcement_table, $enforcement_data, $user_id);
                    //dd($enforcement_res);
                    if ($enforcement_res['success'] == false) {
                        DB::rollBack();
                        return $enforcement_res;
                    }

                    //tracking the application
                    $joint_investigation_id = $enforcement_res['record_id'];

                    //Application_create
                    $codes_array =  $this->getJointApplicationReferenceCodes($request);
                    $view_id = generateApplicationViewID();
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, 1, $user_id);
                    $application_code = generateApplicationCode($sub_module_id, $applications_table);
                    $application_status = getApplicationInitialStatus($module_id, $sub_module_id);

                    if ($tracking_details['success'] == false) {
                        DB::rollBack();
                        return \response()->json($tracking_details);
                    }
                    $tracking_no = $tracking_details['tracking_no'];
                    $reference_no = $tracking_details['tracking_no'];
                    $reference_no = str_replace("TRC", 'BMR', $reference_no);
                    $application_params = array(
                        'view_id' => $view_id,
                        'module_id' => $module_id,
                        'sub_module_id' => $sub_module_id,
                        'section_id' => $section_id,
                        'application_code' => $application_code,
                        'process_id' => $process_id,
                        'workflow_stage_id' => $workflow_stage_id,
                        'tracking_no' => $tracking_no,
                        'reference_no' => $reference_no,
                        'joint_investigation_id' => $joint_investigation_id,
                        'application_status_id' => $application_status->status_id,
                        "date_added" => Carbon::now(),
                        'case_opened' => Carbon::now(),
                        "created_by" => \Auth::user()->id,
                        "created_on" => Carbon::now()
                    );
                    // dd('herr');

                    $res = insertRecord($applications_table, $application_params, $user_id);

                    //dd($res);
                    // myres
                    if (!isset($res['record_id'])) {
                        DB::rollBack();
                        return $res;
                    }

                    $active_application_id = $res['record_id'];
                    // make multiple submissions

                    if ($investigator_results) {
                        foreach ($investigator_results as $investigator_results) {
                            $submission_params = array(
                                'application_id' => $active_application_id,
                                'view_id' => $view_id,
                                'process_id' => $process_id,
                                'application_code' => $application_code,
                                'tracking_no' => $tracking_no,
                                'reference_no' => $reference_no,
                                //'usr_to' => $investigator_results->co_investigator,
                                'usr_to' => $investigator_results->investigator,
                                'usr_from' => $user_id,
                                //'usr_to' => $user_id,
                                'previous_stage' => $workflow_stage_id,
                                'current_stage' => $workflow_stage_id,
                                'module_id' => $module_id,
                                'sub_module_id' => $sub_module_id,
                                'section_id' => $section_id,
                                'application_status_id' => $application_status->status_id,
                                'urgency' => 1,
                                'remarks' => 'Initial save of the application',
                                'date_received' => Carbon::now(),
                                'created_on' => Carbon::now(),
                                'created_by' => $user_id
                            );
                            DB::table('tra_submissions')
                                ->insert($submission_params);
                        }
                    }
                    // INSERT THE PRODUCT DETAILS
                    if ($product_results) {
                        foreach ($product_results as $product_results) {
                            $product_details = array(
                                'application_code' => $application_code,
                                'tracking_no' => $tracking_no,
                                'reference_no' => $reference_no,
                                'joint_investigation_id' => $joint_investigation_id,
                                'certificate_no' => $product_results->certificate_no,
                                'new_product' => $product_results->new_product,
                                'manufacturer' => $product_results->manufacturer,
                                'product_description' => $product_results->product_description,
                                'dosage_form' => $product_results->dosage_form,
                                'pack_size' => $product_results->pack_size,
                                'offence_type_id' => $product_results->offence_type_id,
                                'type_of_offender_id' => $product_results->type_of_offender_id,
                                'batch_number' => $product_results->batch_number,
                                'premise_name' => $product_results->premise_name,
                                'product_type' => $product_results->product_type,
                                'brand_name' => $product_results->brand_name,
                                'expiry_date' => $product_results->expiry_date,
                                'quantity' => $product_results->quantity,
                                'prodclass_category_id' => $product_results->prodclass_category_id,
                                'premise_type_id' => $product_results->premise_type_id,
                                "created_by" => \Auth::user()->id,
                                "created_on" => Carbon::now()
                            );
                            DB::table('tra_joint_reported_products')
                                ->insert($product_details);
                        }
                    } else {
                    }
                    $previous_data = getPreviousRecords('par_joint_detected_offences', $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res_update = updateRecord('par_joint_detected_offences', $previous_data, $where, $mark_as_submitted, $user_id);

                    $res['active_application_id'] = $active_application_id;
                    // $res['process_id'] = $process_id;
                    $res['application_code'] = $application_code;
                    $res['tracking_no'] = $tracking_no;
                    $res['reference_no'] = $reference_no;
                    $res['joint_investigation_id'] = $joint_investigation_id;
                    $res['msg'] = 'Record Saved Successfully';
                    $res['success'] = true;
                    DB::commit();
                    // dd($res);

                } catch (\Exception $exception) {
                    DB::rollBack();
                    $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
                } catch (\Throwable $throwable) {
                    DB::rollBack();
                    $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
                }
                //return \response()->json($res);
            }
        } else {
        }
    }
    public function getJointReportInvestigationMoreDetails(Request $request)
    {
        $application_code = $request->input('application_code');

        try {
            $qry = DB::table('tra_enforcement_applications as t1')
                ->leftJoin('tra_joint_reported_investigation as t2', 't1.joint_investigation_id', 't2.id')
                ->select('t1.*', 't2.*')
                ->where('t1.application_code', $application_code);

            $enforcement_details = $qry->first();

            $res = array(
                'success' => true,
                'enforcement_details' => $enforcement_details,
                'branch_id' => 1,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getjointInvestigationReportedProducts(Request $request)
    {
        $application_code = $request->input('application_code');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('tra_joint_reported_products as t1')
                ->leftjoin('par_offence_types as t2', 't1.offence_type_id', '=', 't2.id')
                ->where('t1.application_code', $application_code)
                ->select('t1.*', 't2.name as offence', DB::raw("CASE WHEN t1.brand_name is NULL THEN t1.new_product WHEN t1.new_product is NULL THEN t1.brand_name END brand_name"));
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function prepareExecutionDetails(Request $request)
    {
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');

        try {
            $main_qry = DB::table('tra_enforcement_applications as t1')
                ->select('t1.*')
                ->where('t1.id', $application_id);


            $results = $main_qry->first();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function saveMonitoringPlanRegions(Request $req)
    {
        try {
            $table_name = $req->table_name;
            $record_id = $req->id;
            $monitoring_plan_id = $req->monitoring_plan_id;
            $region_id = $req->region_id;
            $facility_id = $req->facility_id;
            $inspector_id = $req->inspector_id;
            $user_id = $this->user_id;
            $region_ids = json_decode($region_id);
            $facility_ids = json_decode($facility_id);
            $inspector_ids = json_decode($inspector_id);
            //  $app_data = array(
            //      'monitoring_plan_id'=> $monitoring_plan_id,
            //      'region_id'=> $region_id,

            //  );
            foreach ($region_ids as $region_ids) {
                $params = array(
                    'monitoring_plan_id' => $monitoring_plan_id,
                    'region_id' => $region_ids,
                    // 'inspector_id' => $inspector_ids,
                    'created_by' => $this->user_id
                );
                $res = insertMultipleRecords('mon_work_plan_regions', $params);
                if ($res['success'] == false) {
                    DB::rollBack();
                    return $res;
                }
            }
            //  if(validateIsNumeric($record_id)){
            //      $app_data['dola'] = Carbon::now();
            //      $app_data['altered_by'] = $user_id;
            //      $where = array(
            //          'id'=>$record_id
            //      );
            //      $res = updateRecord('mon_work_plan_regions', $where, $app_data);
            //  }else{
            // $res = insertRecord('mon_work_plan_regions', $app_data);

            foreach ($facility_ids as $facility_ids) {
                $params = array(
                    'monitoring_plan_id' => $monitoring_plan_id,
                    //'region_id'=> $region_id,
                    'facility_id' => $facility_ids,
                    'created_by' => $this->user_id
                );
                $res1 = insertMultipleRecords('tra_mon_plan_facilities', $params);
                if ($res['success'] == false) {
                    DB::rollBack();
                    return $res1;
                }
            }
            foreach ($inspector_ids as $inspector_ids) {
                $params = array(
                    'monitoring_plan_id' => $monitoring_plan_id,
                    // 'region_id'=> $region_id,
                    'inspector_id' => $inspector_ids,
                    'created_by' => $this->user_id
                );
                $res2 = insertMultipleRecords('tra_mon_plan_inspectors', $params);
                if ($res['success'] == false) {
                    DB::rollBack();
                    return $res2;
                }
            }

            //    }

        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMonitoringPlanFacilityInformation(Request $request)
    {
        $monitoring_plan_id = $request->input('monitoring_plan_id');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_annual_workplan_details as t1')
                ->leftJoin('tra_mon_plan_facilities as t2', 't1.id', '=', 't2.monitoring_plan_id')
                ->leftJoin('tra_premises_applications as t3', 't2.facility_id', '=', 't3.id')
                ->leftJoin('tra_premises as t4', 't3.premise_id', '=', 't4.id')
                ->where('t1.id', $monitoring_plan_id)
                ->select('t1.*', 't2.*', 't4.name as facility_name', 't4.*');
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMonitoringPlanInspectorsInformation(Request $request)
    {
        $monitoring_plan_id = $request->input('monitoring_plan_id');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_annual_workplan_details as t1')
                ->leftJoin('tra_mon_plan_inspectors as t2', 't1.id', '=', 't2.monitoring_plan_id')
                ->leftJoin('users as t3', 't2.inspector_id', '=', 't3.id')
                ->leftJoin('par_departments as t4', 't3.department_id', '=', 't4.id')
                ->leftJoin('par_gender as t5', 't3.gender_id', '=', 't5.id')
                ->where('t1.id', $monitoring_plan_id)
                ->select(DB::raw("t1.*,t2.*,t3.*,t4.name as department_name,t5.name as gender,decrypt(t3.phone) as phone,decrypt(t3.mobile) as mobile,CONCAT_WS(' ',decrypt(t3.first_name),decrypt(t3.last_name)) as inspector"));

            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function getMonitoringPlanRegionsInformation(Request $request)
    {
        $monitoring_plan_id = $request->input('monitoring_plan_id');
        $user_id = $this->user_id;
        try {
            $qry = DB::table('par_annual_workplan_details as t1')
                ->leftJoin('mon_work_plan_regions as t2', 't1.id', '=', 't2.monitoring_plan_id')
                ->leftJoin('par_regions as t3', 't2.region_id', '=', 't3.id')
                ->where('t1.id', $monitoring_plan_id)
                ->select('t1.*', 't2.*', 't3.name as region');
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
    public function listMonitoringComplianceFacilities(Request $request)
    {
        try {
            $qry = DB::table('par_operation_summary as t1')
                //->leftJoin('par_operation_summary as t2', 't1.active_application_code', 't2.active_application_code')
                ->select('t1.*',)
                ->where(array('t1.action_id' => 3, 't1.is_submitted' => null, 't1.is_manager_approved' => 1));
            $results = $qry->get();
            //dd($results);
            if ($results) {
                foreach ($results as $result) {
                    $application_code = $result->active_application_code;
                    $app_data = array(
                        'application_code' => $application_code,
                        'enforcement_decision_id' => 1
                    );
                    $res = insertRecord('par_monitoring_approvals', $app_data);
                    $update_data = array(
                        'is_submitted' => 1
                    );
                    $where = array(
                        'active_application_code' => $application_code
                    );

                    $previous_data = getPreviousRecords('par_operation_summary', $where);
                    if ($previous_data['success'] == false) {
                        return $previous_data;
                    }
                    $previous_data = $previous_data['results'];
                    $res_update = updateRecord('par_operation_summary', $previous_data, $where, $update_data, $user_id);

                    # code...
                }
                //dd($res);

            } else {
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return \response()->json($res);
    }
}
