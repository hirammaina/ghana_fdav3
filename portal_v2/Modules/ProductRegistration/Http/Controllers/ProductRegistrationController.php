<?php

namespace Modules\ProductRegistration\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use Validator;
use App\User;

use Carbon\Carbon;

class ProductRegistrationController extends Controller
{
    public function __construct()
    {
        if (!Auth::guard('api')->check()) {
            $res = array(
                'success' => false,
                'message' => 'Invalid Token or failed authentication, login to proceed!!'
            );
            //echo json_encode($res);
            // exit();
        }
    }
    function funcSaveMedicalDevNotificaitonManSite($product_id, $manufacturer_id, $email_address)
    {
        $where_data = array('product_id' => $product_id);

        $man_data = array('product_id' => $product_id, 'manufacturer_id' => $manufacturer_id);
        if (!recordExists('wb_product_manufacturers', $where_data)) {
            $man_data['created_by'] = $email_address;
            $man_data['created_on'] = Carbon::now();
            $man_data['manufacturer_type_id'] = 1;

            $resp = insertRecord('wb_product_manufacturers', $man_data, $email_address);
        } else {
            //update the records
            $man_data['altered_by'] = $email_address;
            $man_data['manufacturer_type_id'] = 1;


            $man_data['dola'] = Carbon::now();
            $previous_data = getPreviousRecords('wb_product_manufacturers', $where_data);
            $resp =   updateRecord('wb_product_manufacturers', $previous_data, $where_data, $man_data, $email_address);
        }
    }
    public function onSaveMedicalProductNotification(Request $req)
    {
        try {

            $product_id = $req->product_id;
            $trader_initiator_id = $req->trader_id;
            $trader_id = $req->trader_id;
            $email_address = $req->email_address;

            $local_agent_id = $req->local_agent_id;
            $man_site_id = $req->man_site_id;

            $section_id = $req->section_id;
            $reference_no = $req->reference_no;
            $sub_module_id = $req->sub_module_id;
            $zone_id = $req->zone_id;
            $reason_for_classification_id = $req->reason_for_classification_id;

            $module_id = getSingleRecordColValue('sub_modules', array('id' => $req->sub_module_id), 'module_id', 'mis_db');
            $manufacturer_id = $req->manufacturer_id;
            $product_origin_id = $req->product_origin_id;
            ///$manufacturer_id = getSingleRecordColValue('par_man_sites', array('id' => $man_site_id), 'manufacturer_id','mis_db'); zone_id
            if (!validateIsNumeric($req->product_origin_id)) {
                $product_origin_id = 1;
            }
            $product_infor = array(
                'common_name_id' => $req->common_name_id,
                'classification_id' => $req->classification_id,
                'brand_name' => $req->brand_name,
                'device_type_id' => $req->device_type_id,
                'physical_description' => $req->physical_description,

                'intended_enduser_id' => $req->intended_enduser_id,
                'intended_use_id' => $req->intended_use_id,
                'product_origin_id' => $product_origin_id,

                'section_id' => $req->section_id,
                'gmdn_code' => $req->gmdn_code,
                'gmdn_category' => $req->gmdn_category,
                'gmdn_term' => $req->gmdn_term,
                'gmdn_term' => $req->gmdn_term,
                'shelf_lifeafter_opening' => $req->shelf_lifeafter_opening,
                'shelf_life' => $req->shelf_life,
                'instructions_of_use' => $req->instructions_of_use,
                'warnings' => $req->warnings,
                'intended_use' => $req->intended_use,
                'reason_for_classification_id' => $req->reason_for_classification_id,
                'shelflifeduration_desc' => $req->shelflifeduration_desc,
                'prodclass_category_id' => $req->prodclass_category_id, 'is_manufactureredin_eastafrica' => $req->is_manufactureredin_eastafrica,
                'manufacturing_country_id' => $req->manufacturing_country_id,
                'description_ofpackagingmaterial' => $req->description_ofpackagingmaterial
            );
            //description_ofpackagingmaterial
            if ($req->expiry_date != '') {
                $product_infor['expiry_date'] = formatDate($req->expiry_date);
                $product_infor['manufacturing_date'] = formatDate($req->manufacturing_date);
            }
            $process_id = getSingleRecordColValue('wf_tfdaprocesses', array('module_id' => $module_id, 'section_id' => $section_id, 'sub_module_id' => $sub_module_id), 'id', 'mis_db');
            $app_data = array(
                'trader_id' => $trader_id,
                'local_agent_id' => $local_agent_id,
                'application_code' => $req->application_code,
                'sub_module_id' => $req->sub_module_id,
                'section_id' => $req->section_id,
                'product_id' => $product_id,
                'process_id' => $process_id,
                'zone_id' => $req->zone_id,
                //	'product_origin_id'=>$product_origin_id,

                'reference_no' => $reference_no,
                'module_id' => $module_id,
                'application_status_id' => 1
            );
            $table_name = 'wb_product_information';
            /** Already Saved */

            if (validateIsNumeric($product_id)) {
                //update the record 
                //product information
                //date_added
                $where = array('id' => $product_id);
                $where_app = array('product_id' => $product_id);

                if (recordExists('wb_product_information', $where)) {

                    $product_infor['dola'] = Carbon::now();
                    $product_infor['altered_by'] = $email_address;

                    $previous_data = getPreviousRecords($table_name, $where);
                    updateRecord('wb_product_information', $previous_data, $where, $product_infor, $email_address);
                    $app_data = array(
                        'trader_id' => $trader_id,
                        'zone_id' => $req->zone_id,
                        'date_added' => Carbon::now(),
                        'altered_by' => $email_address,
                        'dola' => Carbon::now()
                    );

                    $previous_data = getPreviousRecords('wb_product_applications', $where_app);

                    $tracking_no = $previous_data['results'][0]['tracking_no'];
                    $reference_no = $previous_data['results'][0]['reference_no'];
                    $application_code = $previous_data['results'][0]['application_code'];
                    $section_id = $previous_data['results'][0]['section_id'];
                    $module_id = $previous_data['results'][0]['module_id'];
                    $sub_module_id = $previous_data['results'][0]['sub_module_id'];

                    $this->funcSaveMedicalDevNotificaitonManSite($product_id, $manufacturer_id, $email_address);

                    $resp =   updateRecord('wb_product_applications', $previous_data, $where_app, $app_data, $email_address);
                    $sql = DB::connection('mis_db')->table('tra_application_documentsdefination')->where(array('application_code' => $application_code))->first();
                    if (!$sql) {

                        initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 100), $trader_id);
                    }
                }
            } else {

                $resp = insertRecord('wb_product_information', $product_infor, $email_address);

                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'module_id' => $module_id, 'reference_type_id' => 1), 'reference_format_id', 'mis_db');

                $zone_code = getSingleRecordColValue('par_zones', array('id' => $req->zone_id), 'zone_code', 'mis_db');
                $section_code = getSingleRecordColValue('par_sections', array('id' => $req->section_id), 'code', 'mis_db');
                $class_code = getSingleRecordColValue('par_classifications', array('id' => $req->classification_id), 'code', 'mis_db');
                $apptype_code = getSingleRecordColValue('par_product_types', array('id' => $req->product_type_id), 'code', 'mis_db');
                $device_typecode = getSingleRecordColValue('par_device_types', array('id' => $req->device_type_id), 'code', 'mis_db');


                $codes_array = array(
                    'section_code' => $section_code,
                    'prod_type_code' => $section_code,
                    'zone_code' => $zone_code,
                    'class_code' => $class_code,
                    'device_typecode' => $device_typecode
                );

                $tracking_no = generateApplicationRefNumber($ref_id, $codes_array, date('Y'), $process_id, $zone_id, $trader_id);
                if (!validateIsNumeric($ref_id)) {
                    return \response()->json(array('success' => false, 'message' => 'Reference No Format has not been set, contact the system administrator'));
                } else if ($tracking_no == '') {
                    return \response()->json(array('success' => false, 'tracking_no' => $tracking_no, 'message' => $tracking_no));
                }

                $application_code = generateApplicationCode($sub_module_id, 'wb_product_applications');
                $product_id = $resp['record_id'];
                $app_data['created_by'] = $email_address;
                $app_data['created_on'] = Carbon::now();
                $app_data['date_added'] = Carbon::now();
                $app_data['tracking_no'] = $tracking_no;
                $app_data['reference_no'] = $reference_no;
                $app_data['product_id'] = $product_id;
                $app_data['application_code'] = $application_code;
                $app_data['application_initiator_id'] = $trader_id;
                $app_data['application_status_id'] = 1;


                $resp = insertRecord('wb_product_applications', $app_data, $email_address);
                initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 100), $trader_id);
                saveApplicationSubmissionDetails($application_code, 'wb_product_applications');
                $this->funcSaveMedicalDevNotificaitonManSite($product_id, $manufacturer_id, $email_address);
            }

            if ($resp['success']) {
                $res = array(
                    'tracking_no' => $tracking_no,
                    'product_id' => $product_id,
                    'success' => true,
                    'application_code' => $application_code,
                    'reference_no' => $reference_no,
                    'message' => 'Product Notification Saved Successfully, with Tracking No: ' . $tracking_no
                );
            } else {
                $res = array(
                    'success' => false,
                    'error' => $resp['message'],
                    'message' => 'Error Occurred Product Notification not saved, it this persists contact the system Administrator'
                );
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'resp' => $resp,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,    'resp' => $resp,
                'message' => $throwable->getMessage()
            );
        }

        return response()->json($res);
    }
    function getProductInformationRequests($req)
    {
        $data = array(
            'common_name_id' => $req->common_name_id,
            'atc_code_id' => $req->atc_code_id,
            'classification_id' => $req->classification_id,
            'brand_name' => $req->brand_name,
            'prodassessment_countries_ids' => $req->prodassessment_countries_ids,
            'device_type_id' => $req->device_type_id,
            'physical_description' => $req->physical_description,
            'therapeutic_code' => $req->therapeutic_code,
            'therapeutic_group' => $req->therapeutic_group,
            'dosage_form_id' => $req->dosage_form_id,
            'gtin_number' => $req->gtin_number,
            'glocation_number' => $req->glocation_number,
            'product_strength' => $req->product_strength,
            'si_unit_id' => $req->si_unit_id,
            'storage_condition_id' => $req->storage_condition_id,
            'product_origin_id' => $req->product_origin_id,
            'product_category_id' => $req->product_category_id,
            'product_subcategory_id' => $req->product_subcategory_id,
            'distribution_category_id' => $req->distribution_category_id,
            'special_category_id' => $req->special_category_id,
            'intended_enduser_id' => $req->intended_enduser_id,

            'therapeutic_code' => $req->therapeutic_code,
            'section_id' => $req->section_id,
            'contraindication' => $req->contraindication,
            'gmdn_code' => $req->gmdn_code,
            'gmdn_category' => $req->gmdn_category,
            'gmdn_term' => $req->gmdn_term,
            'gmdn_term' => $req->gmdn_term,
            'shelf_lifeafter_opening' => $req->shelf_lifeafter_opening,
            'shelf_life' => $req->shelf_life,
            'storage_conditionafter_opening' => $req->storage_conditionafter_opening,
            'shelf_lifeafter_reconstitution' => $req->shelf_lifeafter_reconstitution,
            'instructions_of_use' => $req->instructions_of_use,
            'warnings' => $req->warnings,
            'intended_use' => $req->intended_use,
            'medical_systemmodel_series' => $req->medical_systemmodel_series,
            'medical_family' => $req->medical_family,
            'shelflifeduration_desc' => $req->shelflifeduration_desc,
            'shelflifeafteropeningduration_desc' => $req->shelflifeafteropeningduration_desc,
            'reason_for_classification_id' => $req->reason_for_classification_id,
            'prodclass_category_id' => $req->prodclass_category_id,
            'productrisk_category_id' => $req->productrisk_category_id,


            'has_maximum_residue_limit' => $req->has_maximum_residue_limit,
            'reagents_accessories' => $req->reagents_accessories,
            'has_medical_family' => $req->has_medical_family,
            'has_medical_systemmodel_series' => $req->has_medical_systemmodel_series,
            'has_reagents_accessories' => $req->has_reagents_accessories,

            'application_method' => $req->application_method,
            'pack_sizes' => $req->pack_sizes,
            'storage_condition' => $req->storage_condition,
            'description_ofpackagingmaterial' => $req->description_ofpackagingmaterial,
            'descriptionofmethod_ofshelflife' => $req->descriptionofmethod_ofshelflife,
            'applied_product_id' => $req->applied_product_id,
            'flash_flame_form' => $req->flash_flame_form,
            'formulation_id' => $req->formulation_id,
            'liquid_gravity' => $req->liquid_gravity,
            'pesticide_type_id' => $req->pesticide_type_id,
            'require_child_resistant' => $req->require_child_resistant,
            'solid_product_density' => $req->solid_product_density,
            'who_class_id' => $req->who_class_id,
            'label_signal_id' => $req->label_signal_id,
            'indication' => $req->indication,
            'prodclass_category_id' => $req->prodclass_category_id,
            'is_manufactureredin_eastafrica' => $req->is_manufactureredin_eastafrica,
            'manufacturing_country_id' => $req->manufacturing_country_id,
            'vetmedicines_registrationtype_id' => $req->vetmedicines_registrationtype_id,

        );


        return $data;
    }
    public function onSaveProductApplication(Request $req)
    {

        try {
            DB::beginTransaction();
            $product_id = $req->product_id;
            $trader_initiator_id = $req->trader_id;
            $applicant_id = $req->trader_id;
            $trader_id = $req->trader_id;
            $email_address = $req->email_address;

            $local_agent_id = $req->local_agent_id;
            $section_id = $req->section_id;
            $reference_no = $req->reference_no;
            $sub_module_id = $req->sub_module_id;
            $zone_id = $req->zone_id;
            $product_res =  '';
            $route_of_administrations = $req->route_of_administration_id;
            $target_species = $req->target_species_id;


            $intended_uses = $req->intended_use_id;
            $product_forms = $req->product_form_id;
            $method_ofuses = $req->method_ofuse_id;
            $group_application_code = $req->group_application_code;


            $module_id = getSingleRecordColValue('sub_modules', array('id' => $req->sub_module_id), 'module_id', 'mis_db');
            $product_infor = $this->getProductInformationRequests($req);
            if ($req->expiry_date != '') {
                $product_infor['expiry_date'] = formatDate($req->expiry_date);
                $product_infor['manufacturing_date'] = formatDate($req->manufacturing_date);
            }

            $app_data = array(
                'trader_id' => $trader_id,
                'local_agent_id' => $local_agent_id,
                'application_code' => $req->application_code,
                'group_application_code' => $req->group_application_code,
                'sub_module_id' => $req->sub_module_id,
                'section_id' => $req->section_id,
                'product_id' => $product_id,
                'product_type_id' => $req->product_type_id,
                'zone_id' => $req->zone_id,
                'reference_no' => $reference_no,
                'module_id' => $module_id,
                'assessment_procedure_id' => $req->assessment_procedure_id,
                'assessmentprocedure_type_id' => $req->assessmentprocedure_type_id, 'eac_registeringbody_id' => $req->eac_registeringbody_id,
                'is_fast_track' => $req->is_fast_track,
                'paying_currency_id' => $req->paying_currency_id,
                'application_status_id' => 1
            );
            $table_name = 'wb_product_information';
            /** Already Saved */

            if (validateIsNumeric($product_id)) {
                //update the record 
                //product information
                //
                $where = array('id' => $product_id);
                $where_app = array('product_id' => $product_id);

                if (recordExists('wb_product_information', $where)) {

                    $product_infor['dola'] = Carbon::now();
                    $product_infor['altered_by'] = $email_address;

                    $previous_data = getPreviousRecords($table_name, $where);
                    updateRecord('wb_product_information', $previous_data, $where, $product_infor, $email_address);
                    $app_data = array(
                        'zone_id' => $req->zone_id,
                        'assessment_procedure_id' => $req->assessment_procedure_id,
                        'local_agent_id' => $req->local_agent_id,
                        'assessmentprocedure_type_id' => $req->assessmentprocedure_type_id,
                        'date_added' => Carbon::now(),
                        'group_application_code' => $req->group_application_code,
                        'is_fast_track' => $req->is_fast_track,
                        'paying_currency_id' => $req->paying_currency_id,
                        'altered_by' => $email_address,
                        'dola' => Carbon::now()
                    );
                    $previous_data = getPreviousRecords('wb_product_applications', $where_app);
                    $tracking_no = $previous_data['results'][0]['tracking_no'];
                    $application_code = $previous_data['results'][0]['application_code'];

                    $resp =   updateRecord('wb_product_applications', $previous_data, $where_app, $app_data, $email_address);
                }

                if ($resp['success']) {
                    $sql = DB::connection('mis_db')->table('tra_application_documentsdefination')->where(array('application_code' => $application_code))->first();
                    // $sql = DB::table('mis_db.tra_application_documentsdefination')->where(array('application_code' => $application_code))->first();
                    if (!$sql) {
                        //print_r('test');//to reisntate dms not working at the moment since its down and folders not set well Job
                        //initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 100), $trader_id);
                    }
                    $res = array(
                        'tracking_no' => $tracking_no,
                        'product_id' => $product_id,
                        'success' => true,

                        'application_code' => $application_code,
                        'message' => 'Product Application Saved Successfully, with Tracking No: ' . $tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false,
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            } else {



                $resp = insertRecord('wb_product_information', $product_infor, $email_address);





                $product_res =  $resp;
                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'module_id' => $module_id, 'reference_type_id' => 1), 'reference_format_id', 'mis_db');

                $zone_code = getSingleRecordColValue('par_zones', array('id' => $req->zone_id), 'zone_code', 'mis_db');
                $section_code = getSingleRecordColValue('par_sections', array('id' => $req->section_id), 'code', 'mis_db');
                $class_code = getSingleRecordColValue('par_classifications', array('id' => $req->classification_id), 'code', 'mis_db');
                $apptype_code = getSingleRecordColValue('par_product_types', array('id' => $req->product_type_id), 'code', 'mis_db');
                $assessment_code = getSingleRecordColValue('par_assessment_procedures', array('id' => $req->assessment_procedure_id), 'code', 'mis_db');
                $device_typecode = getSingleRecordColValue('par_device_types', array('id' => $req->device_type_id), 'code', 'mis_db');
                $process_id = getSingleRecordColValue('wf_tfdaprocesses', array('module_id' => $module_id, 'section_id' => $section_id, 'sub_module_id' => $sub_module_id), 'id', 'mis_db');

                if ($class_code == '') {
                    $class_code = $section_code;
                }
                $codes_array = array(
                    'section_code' => $section_code,
                    'zone_code' => $zone_code,
                    'class_code' => $class_code,
                    'assessment_code' => $assessment_code,
                    'device_typecode' => $device_typecode
                );

                $tracking_no = generateApplicationRefNumber($ref_id, $codes_array, date('Y'), $process_id, $zone_id, $trader_id);
                if (!validateIsNumeric($ref_id)) {
                    return \response()->json(array('success' => false, 'message' => 'Reference No Format has not been set, contact the system administrator'));
                } else if ($tracking_no == '') {
                    return \response()->json(array('success' => false, 'tracking_no' => $tracking_no, 'message' => $tracking_no));
                }

                $application_code = generateApplicationCode($sub_module_id, 'wb_product_applications');
                $product_id = $resp['record_id'];

                $app_data['created_by'] = $email_address;
                $app_data['created_on'] = Carbon::now();
                $app_data['tracking_no'] = $tracking_no;
                $app_data['reference_no'] = $tracking_no;
                $app_data['product_id'] = $product_id;

                $app_data['date_added'] = Carbon::now();
                $app_data['application_code'] = $application_code;
                $app_data['group_application_code'] = $group_application_code;



                $app_data['application_initiator_id'] = $trader_id;
                $app_data['application_status_id'] = 1;

                $resp = insertRecord('wb_product_applications', $app_data, $email_address);


                if ($resp['success']) {
                    //initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 100), $trader_id);
                    saveApplicationSubmissionDetails($application_code, 'wb_product_applications');


                    $res = array(
                        'tracking_no' => $tracking_no,
                        'product_id' => $product_id,
                        'application_code' => $application_code,
                        'success' => true,
                        'message' => 'Product Application Saved Successfully, with Tracking No: ' . $tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false, 'message1' => $resp['message'],
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            }
            //on save routes 
            $routesadmin = array();
            if (is_array($route_of_administrations)) {
                foreach ($route_of_administrations as $route_of_administration) {

                    $routesadmin[] = array(
                        'product_id' => $product_id,
                        'route_of_administration_id' => $route_of_administration,
                        'created_by' => $email_address,
                        'created_on' => Carbon::now()
                    );
                }

                if (count($routesadmin)) {
                    DB::table('wb_prod_routeofadministrations')->where('product_id', $product_id)->delete();
                    DB::table('wb_prod_routeofadministrations')->insert($routesadmin);
                }
            }
            if (is_array($target_species)) {

                $targetspecies = array();
                foreach ($target_species as $target_species_id) {

                    $targetspecies[] = array(
                        'product_id' => $product_id,
                        'target_species_id' => $target_species_id,
                        'created_by' => $email_address,
                        'created_on' => Carbon::now()
                    );
                }
                if (count($targetspecies) > 0) {
                    DB::table('wb_prod_targetspecies')->where('product_id', $product_id)->delete();
                    DB::table('wb_prod_targetspecies')->insert($targetspecies);
                }
            }

            if (is_array($target_species)) {

                $targetspecies = array();
                foreach ($target_species as $target_species_id) {

                    $targetspecies[] = array(
                        'product_id' => $product_id,
                        'target_species_id' => $target_species_id,
                        'created_by' => $email_address,
                        'created_on' => Carbon::now()
                    );
                }
                if (count($targetspecies) > 0) {
                    DB::table('wb_prod_targetspecies')->where('product_id', $product_id)->delete();
                    DB::table('wb_prod_targetspecies')->insert($targetspecies);
                }
            }

            if (is_array($intended_uses)) {

                $data = array();
                foreach ($intended_uses as $intended_use_id) {

                    $data[] = array(
                        'product_id' => $product_id,
                        'intended_use_id' => $intended_use_id,
                        'created_by' => $email_address,
                        'created_on' => Carbon::now()
                    );
                }
                if (count($data) > 0) {
                    DB::table('wb_prod_intendeduses')->where('product_id', $product_id)->delete();
                    DB::table('wb_prod_intendeduses')->insert($data);
                }
            }
            if (is_array($product_forms)) {

                $data = array();
                foreach ($product_forms as $product_form_id) {

                    $data[] = array(
                        'product_id' => $product_id,
                        'product_form_id' => $product_form_id,
                        'created_by' => $email_address,
                        'created_on' => Carbon::now()
                    );
                }
                if (count($data) > 0) {
                    DB::table('wb_prod_product_forms')->where('product_id', $product_id)->delete();
                    DB::table('wb_prod_product_forms')->insert($data);
                }
            }
            if (is_array($method_ofuses)) {

                $data = array();
                foreach ($method_ofuses as $method_ofuse_id) {

                    $data[] = array(
                        'product_id' => $product_id,
                        'method_ofuse_id' => $method_ofuse_id,
                        'created_by' => $email_address,
                        'created_on' => Carbon::now()
                    );
                }
                if (count($data) > 0) {
                    DB::table('wb_prod_method_ofuses')->where('product_id', $product_id)->delete();
                    DB::table('wb_prod_method_ofuses')->insert($data);
                }
            }

            if ($res['success']) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            $res = array(
                'success' => false, 'message1' => $product_res,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = array(
                'success' => false, 'message1' => $product_res,
                'message' => $throwable->getMessage()
            );
        }

        return response()->json($res);
    }

    public function onSaveVariantProductApplication(Request $req)
    {
        try {
            //   DB::beginTransaction();
            $applications_table = 'tra_product_applications';
            $product_id = $req->product_id;

            $trader_id = $req->trader_id;

            $applicant_id = $req->applicant_id;
            $email_address = $req->email_address;
            //get

            $section_id = $req->section_id;
            $reference_application_code = $req->reference_application_code;

            $reference_no = $req->reference_no;
            $sub_module_id = $req->sub_module_id;
            $reg_product_id = $req->reg_product_id;
            $tra_product_id = $req->tra_product_id;
            $prodclass_category_id = $req->prodclass_category_id;
            $local_agent_id = $req->local_agent_id;

            $module_id = getSingleRecordColValue('sub_modules', array('id' => $req->sub_module_id), 'module_id', 'mis_db');
            $process_id = getSingleRecordColValue('wf_tfdaprocesses', array('section_id' => $req->section_id, 'module_id' => $module_id, 'sub_module_id' => $req->sub_module_id,), 'id', 'mis_db');

            if (!validateIsNumeric($prodclass_category_id)) {
                if ($section_id == 2) {
                    $prodclass_category_id = 1;
                } else {
                    $prodclass_category_id = 4;
                }
            }

            $product_type_id = $req->product_type_id;
            if (!validateIsNumeric($req->product_type_id)) {
                $product_type_id = 1;
            }
            $product_infor = $this->getProductInformationRequests($req);

            if ($req->expiry_date != '') {
                $product_infor['expiry_date'] = formatDate($req->expiry_date);
                $product_infor['manufacturing_date'] = formatDate($req->manufacturing_date);
            }

            $app_data = array(
                'trader_id' => $trader_id,
                'local_agent_id' => $local_agent_id,
                'application_code' => $req->application_code,
                'sub_module_id' => $req->sub_module_id,
                'section_id' => $req->section_id,
                'product_id' => $product_id,
                'product_type_id' => $req->product_type_id,
                'zone_id' => $req->zone_id,
                'reference_no' => $reference_no,
                'module_id' => $module_id,
                'assessment_procedure_id' => $req->assessment_procedure_id,
                'assessmentprocedure_type_id' => $req->assessmentprocedure_type_id,
                'is_fast_track' => $req->is_fast_track,
                'paying_currency_id' => $req->paying_currency_id,
                'application_status_id' => 1
            );
            $table_name = 'wb_product_applications';
            /** Already Saved */
            if (validateIsNumeric($product_id)) {
                $where = array('id' => $product_id);
                $where_app = array('product_id' => $product_id);

                if (recordExists('wb_product_information', $where)) {

                    $product_infor['dola'] = Carbon::now();
                    $product_infor['altered_by'] = $email_address;
                    $previous_data = getPreviousRecords($table_name, $where);

                    updateRecord('wb_product_information', $previous_data, $where, $product_infor, $email_address);
                    $app_data = array(
                        'trader_id' => $trader_id,
                        'zone_id' => $req->zone_id,
                        'assessment_procedure_id' => $req->assessment_procedure_id,
                        'assessmentprocedure_type_id' => $req->assessmentprocedure_type_id,
                        'date_added' => Carbon::now(),
                        'altered_by' => $email_address,
                        'dola' => Carbon::now()
                    );
                    $previous_data = getPreviousRecords('wb_product_applications', $where_app);
                    $tracking_no = $previous_data['results'][0]['tracking_no'];
                    $application_code = $previous_data['results'][0]['application_code'];

                    $resp =   updateRecord('wb_product_applications', $previous_data, $where_app, $app_data, $email_address);

                    $where_app = array('application_code' => $application_code);
                    if (!recordExists('tra_application_uploadeddocuments', $where_app, 'mis_db')) {
                        initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 1000), $trader_id);
                    }
                }

                if ($resp) {
                    $res = array(
                        'tracking_no' => $tracking_no,
                        'product_id' => $product_id,
                        'success' => true,
                        'application_code' => $application_code,
                        'message' => 'Product Application Saved Successfully, with Tracking No: ' . $tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false,
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            } else {

                $app_data['local_agent_id'] = $local_agent_id;
                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'module_id' => $module_id, 'reference_type_id' => 1), 'reference_format_id', 'mis_db');
                //reg_product_id
                $where_statement = array(
                    'sub_module_id' => 7,
                    't1.application_code' => $reference_application_code, 't1.section_id' => $section_id
                );


                $primary_reference_no = getProductPrimaryReferenceNo($where_statement, 'tra_product_applications');
                $codes_array = array(
                    'ref_no' => $primary_reference_no
                );

                $where_statementref = array('reg_product_id' => $reg_product_id, 'sub_module_id' => $sub_module_id);

                $tracking_no = generateSubRefNumber($where_statementref, $applications_table, $ref_id, $codes_array, $sub_module_id, $trader_id);

                if (!validateIsNumeric($ref_id)) {
                    return \response()->json(array('success' => false, 'message' => 'Reference No Format has not been set, contact the system administrator'));
                } else if ($tracking_no == '') {
                    return \response()->json(array('success' => false, 'tracking_no' => $tracking_no, 'message' => $tracking_no));
                }

                $res = array('success' => false, 'tracking_no' => $ref_id);

                $resp = insertRecord('wb_product_information', $product_infor, $email_address);
                $application_code = generateApplicationCode($sub_module_id, 'wb_product_applications');

                $product_id = $resp['record_id'];
                $app_data['created_by'] = $email_address;
                $app_data['created_on'] = Carbon::now();
                $app_data['tracking_no'] = $tracking_no;
                $app_data['reference_no'] = $tracking_no;
                $app_data['product_id'] = $product_id;
                $app_data['application_code'] = $application_code;
                $app_data['reference_application_code'] = $reference_application_code;
                $app_data['application_initiator_id'] = $trader_id;
                $app_data['application_status_id'] = 1;

                $resp = insertRecord('wb_product_applications', $app_data, $email_address);

                $res = array(
                    'tracking_no' => $tracking_no,
                    'product_id' => $product_id,
                    'application_code' => $application_code,
                    'success' => true,
                    'message' => 'Product Application Saved Successfully, with Tracking No: ' . $tracking_no
                );
                initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 100), $trader_id);
                saveApplicationSubmissionDetails($application_code, 'wb_product_applications');
                funcSaveRegisteredProductOtherdetails($tra_product_id, $product_id, $trader_id);
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
    public function onSaveRenAltProductApplication(Request $req)
    {
        try {
            //   DB::beginTransaction();
            $applications_table = 'tra_product_applications';
            $product_id = $req->product_id;

            $trader_id = $req->trader_id;

            $applicant_id = $req->applicant_id;
            $email_address = $req->email_address;
            //get

            $section_id = $req->section_id;

            $reference_no = $req->reference_no;
            $sub_module_id = $req->sub_module_id;
            $reg_product_id = $req->reg_product_id;
            $tra_product_id = $req->tra_product_id;
            $prodclass_category_id = $req->prodclass_category_id;
            $local_agent_id = $req->local_agent_id;

            $module_id = getSingleRecordColValue('sub_modules', array('id' => $req->sub_module_id), 'module_id', 'mis_db');
            $process_id = getSingleRecordColValue('wf_tfdaprocesses', array('section_id' => $req->section_id, 'module_id' => $module_id, 'sub_module_id' => $req->sub_module_id,), 'id', 'mis_db');

            if (!validateIsNumeric($prodclass_category_id)) {
                if ($section_id == 2) {
                    $prodclass_category_id = 1;
                } else {
                    $prodclass_category_id = 4;
                }
            }

            $product_type_id = $req->product_type_id;
            if (!validateIsNumeric($req->product_type_id)) {
                $product_type_id = 1;
            }
            $product_infor = $this->getProductInformationRequests($req);

            if ($req->expiry_date != '') {
                $product_infor['expiry_date'] = formatDate($req->expiry_date);
                $product_infor['manufacturing_date'] = formatDate($req->manufacturing_date);
            }

            $app_data = array(
                'trader_id' => $trader_id,
                'local_agent_id' => $local_agent_id,
                'application_code' => $req->application_code,
                'sub_module_id' => $req->sub_module_id,
                'section_id' => $req->section_id,
                'product_id' => $product_id,
                'product_type_id' => $req->product_type_id,
                'zone_id' => $req->zone_id,
                'reference_no' => $reference_no,
                'module_id' => $module_id,
                'assessment_procedure_id' => $req->assessment_procedure_id,
                'assessmentprocedure_type_id' => $req->assessmentprocedure_type_id,
                'is_fast_track' => $req->is_fast_track,
                'paying_currency_id' => $req->paying_currency_id,
                'application_status_id' => 1
            );
            $table_name = 'wb_product_applications';
            /** Already Saved */
            if (validateIsNumeric($product_id)) {
                $where = array('id' => $product_id);
                $where_app = array('product_id' => $product_id);

                if (recordExists('wb_product_information', $where)) {

                    $product_infor['dola'] = Carbon::now();
                    $product_infor['altered_by'] = $email_address;
                    $previous_data = getPreviousRecords($table_name, $where);

                    updateRecord('wb_product_information', $previous_data, $where, $product_infor, $email_address);
                    $app_data = array(
                        'trader_id' => $trader_id,
                        'zone_id' => $req->zone_id,
                        'assessment_procedure_id' => $req->assessment_procedure_id,
                        'assessmentprocedure_type_id' => $req->assessmentprocedure_type_id,
                        'date_added' => Carbon::now(),
                        'altered_by' => $email_address,
                        'dola' => Carbon::now()
                    );
                    $previous_data = getPreviousRecords('wb_product_applications', $where_app);
                    $tracking_no = $previous_data['results'][0]['tracking_no'];
                    $application_code = $previous_data['results'][0]['application_code'];

                    $resp =   updateRecord('wb_product_applications', $previous_data, $where_app, $app_data, $email_address);

                    $where_app = array('application_code' => $application_code);
                    if (!recordExists('tra_application_uploadeddocuments', $where_app, 'mis_db')) {
                        initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 1000), $trader_id);
                    }
                }

                if ($resp) {
                    $res = array(
                        'tracking_no' => $tracking_no,
                        'product_id' => $product_id,
                        'success' => true,
                        'application_code' => $application_code,
                        'message' => 'Product Application Saved Successfully, with Tracking No: ' . $tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false,
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            } else {

                $app_data['local_agent_id'] = $local_agent_id;
                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'module_id' => $module_id, 'reference_type_id' => 1), 'reference_format_id', 'mis_db');


                $anyOngoingApps = checkForOngoingApplications($reg_product_id, 'tra_product_applications', 'reg_product_id', $process_id, $sub_module_id);

                $anyOngoingPortalApps = checkForPortalOngoingApplications($reg_product_id, 'wb_product_applications', 'reg_product_id', $process_id);

                if ($anyOngoingApps['exists'] == true || $anyOngoingPortalApps['exists'] == true) {
                    $res = array(
                        'success' => false,
                        'message' => 'There is an ongoing application pending approval with reference number ' . $anyOngoingApps['ref_no'] . ' ' . $anyOngoingPortalApps['ref_no']
                    );
                    return \response()->json($res);
                }
                //reg_product_id
                $where_statement = array(
                    'sub_module_id' => 7,
                    't1.reg_product_id' => $reg_product_id, 't1.section_id' => $section_id
                );


                $primary_reference_no = getProductPrimaryReferenceNo($where_statement, 'tra_product_applications');
                $codes_array = array(
                    'ref_no' => $primary_reference_no
                );

                $where_statementref = array('reg_product_id' => $reg_product_id, 'sub_module_id' => $sub_module_id);

                $tracking_no = generateSubRefNumber($where_statementref, $applications_table, $ref_id, $codes_array, $sub_module_id, $trader_id);

                if (!validateIsNumeric($ref_id)) {
                    return \response()->json(array('success' => false, 'message' => 'Reference No Format has not been set, contact the system administrator'));
                } else if ($tracking_no == '') {
                    return \response()->json(array('success' => false, 'tracking_no' => $tracking_no, 'message' => $tracking_no));
                }

                $res = array('success' => false, 'tracking_no' => $ref_id);

                $resp = insertRecord('wb_product_information', $product_infor, $email_address);
                $application_code = generateApplicationCode($sub_module_id, 'wb_product_applications');

                $product_id = $resp['record_id'];
                $app_data['created_by'] = $email_address;
                $app_data['created_on'] = Carbon::now();
                $app_data['tracking_no'] = $tracking_no;
                $app_data['reference_no'] = $tracking_no;
                $app_data['product_id'] = $product_id;
                $app_data['application_code'] = $application_code;
                $app_data['application_initiator_id'] = $trader_id;
                $app_data['application_status_id'] = 1;

                $resp = insertRecord('wb_product_applications', $app_data, $email_address);

                $res = array(
                    'tracking_no' => $tracking_no,
                    'product_id' => $product_id,
                    'application_code' => $application_code,
                    'success' => true,
                    'message' => 'Product Application Saved Successfully, with Tracking No: ' . $tracking_no
                );
                initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 100), $trader_id);
                saveApplicationSubmissionDetails($application_code, 'wb_product_applications');
                funcSaveRegisteredProductOtherdetails($tra_product_id, $product_id, $trader_id);
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
    public function onValidateBrandNameDetails(Request $req)
    {
        try {
            $resp = "";
            DB::enableQueryLog();
            $brand_name = $req->brand_name;
            $count = DB::connection('mis_db')->table('tra_product_information as t1')
                ->join('tra_product_applications as t2', 't1.id', '=', 't2.product_id')
                ->where(array('brand_name' => $brand_name))
                ->count();

            if ($count) {
                $res = array('success' => false, 'message' => $count, 'message' => 'A Product wth a similar Brand Name has already been submitted for registration, kindly contact the Authority for further guide or Submit a product with a different Brand Name!!');
            } else {
                $res = array('success' => true);
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
    public function onSaveWithdrawalProductApplication(Request $req)
    {
        try {
            DB::beginTransaction();
            $applications_table = 'tra_product_applications';
            $product_id = $req->product_id;

            $trader_id = $req->trader_id;

            $applicant_id = $req->applicant_id;
            $email_address = $req->email_address;
            //get

            $section_id = $req->section_id;

            $reference_no = $req->reference_no;
            $sub_module_id = $req->sub_module_id;
            $reg_product_id = $req->reg_product_id;
            $tra_product_id = $req->tra_product_id;
            $prodclass_category_id = $req->prodclass_category_id;
            $local_agent_id = $req->local_agent_id;

            $module_id = getSingleRecordColValue('sub_modules', array('id' => $req->sub_module_id), 'module_id', 'mis_db');
            $process_id = getSingleRecordColValue('wf_tfdaprocesses', array('section_id' => $req->section_id, 'module_id' => $module_id, 'sub_module_id' => $req->sub_module_id,), 'id', 'mis_db');

            if (!validateIsNumeric($prodclass_category_id)) {
                if ($section_id == 2) {
                    $prodclass_category_id = 1;
                } else {
                    $prodclass_category_id = 4;
                }
            }

            $product_type_id = $req->product_type_id;
            if (!validateIsNumeric($req->product_type_id)) {
                $product_type_id = 1;
            }
            $product_infor = array(
                'common_name_id' => $req->common_name_id,
                'atc_code_id' => $req->atc_code_id,
                'classification_id' => $req->classification_id,
                'brand_name' => $req->brand_name,
                'device_type_id' => $req->device_type_id,
                'physical_description' => $req->physical_description,
                'dosage_form_id' => $req->dosage_form_id,
                'product_form_id' => $req->product_form_id,
                'product_strength' => $req->product_strength,
                'si_unit_id' => $req->si_unit_id,
                'storage_condition_id' => $req->storage_condition_id,
                'product_origin_id' => $req->product_origin_id,
                'product_category_id' => $req->product_category_id,
                'product_subcategory_id' => $req->product_subcategory_id,
                'distribution_category_id' => $req->distribution_category_id,
                'special_category_id' => $req->special_category_id,
                'intended_enduser_id' => $req->intended_enduser_id,
                'intended_use_id' => $req->intended_use_id,
                'route_of_administration_id' => $req->route_of_administration_id,
                'method_ofuse_id' => $req->method_ofuse_id,
                'section_id' => $req->section_id,
                'contraindication' => $req->contraindication,
                'gmdn_code' => $req->gmdn_code,
                'gmdn_category' => $req->gmdn_category,
                'gmdn_term' => $req->gmdn_term,
                'gmdn_term' => $req->gmdn_term,
                'shelf_lifeafter_opening' => $req->shelf_lifeafter_opening,
                'shelf_life' => $req->shelf_life,
                'instructions_of_use' => $req->instructions_of_use,
                'warnings' => $req->warnings,
                'intended_use' => $req->intended_use,
                'medical_systemmodel_series' => $req->medical_systemmodel_series,
                'medical_family' => $req->medical_family,
                'shelflifeduration_desc' => $req->shelflifeduration_desc,
                'shelflifeafteropeningduration_desc' => $req->shelflifeafteropeningduration_desc,
                'reason_for_classification_id' => $req->reason_for_classification_id,
                'prodclass_category_id' => $req->prodclass_category_id,
                'productrisk_category_id' => $req->productrisk_category_id,


                'reagents_accessories' => $req->reagents_accessories,
                'has_medical_family' => $req->has_medical_family,
                'has_medical_systemmodel_series' => $req->has_medical_systemmodel_series,
                'has_reagents_accessories' => $req->has_reagents_accessories,
                'is_manufactureredin_eastafrica' => $req->is_manufactureredin_eastafrica,
                'manufacturing_country_id' => $req->manufacturing_country_id,
            );


            if ($req->expiry_date != '') {
                $product_infor['expiry_date'] = formatDate($req->expiry_date);
                $product_infor['manufacturing_date'] = formatDate($req->manufacturing_date);
            }

            $app_data = array(
                'trader_id' => $trader_id,
                'local_agent_id' => $local_agent_id,
                'application_code' => $req->application_code,
                'sub_module_id' => $req->sub_module_id,
                'section_id' => $req->section_id,
                'product_id' => $product_id,
                'product_type_id' => $req->product_type_id,
                'zone_id' => $req->zone_id,
                'reference_no' => $reference_no,
                'module_id' => $module_id,
                'assessment_procedure_id' => $req->assessment_procedure_id,
                'assessmentprocedure_type_id' => $req->assessmentprocedure_type_id,
                'is_fast_track' => $req->is_fast_track,
                'paying_currency_id' => $req->paying_currency_id,
                'application_status_id' => 1
            );
            $table_name = 'wb_product_applications';
            /** Already Saved */
            if (validateIsNumeric($product_id)) {
                $where = array('id' => $product_id);
                $where_app = array('product_id' => $product_id);

                if (recordExists('wb_product_information', $where)) {

                    $product_infor['dola'] = Carbon::now();
                    $product_infor['altered_by'] = $email_address;
                    $previous_data = getPreviousRecords($table_name, $where);

                    updateRecord('wb_product_information', $previous_data, $where, $product_infor, $email_address);
                    $app_data = array(
                        'trader_id' => $trader_id,
                        'zone_id' => $req->zone_id,
                        'assessment_procedure_id' => $req->assessment_procedure_id,
                        'assessmentprocedure_type_id' => $req->assessmentprocedure_type_id,
                        'date_added' => Carbon::now(),
                        'altered_by' => $email_address,
                        'dola' => Carbon::now()
                    );
                    $previous_data = getPreviousRecords('wb_product_applications', $where_app);
                    $tracking_no = $previous_data['results'][0]['tracking_no'];
                    $application_code = $previous_data['results'][0]['application_code'];

                    $resp =   updateRecord('wb_product_applications', $previous_data, $where_app, $app_data, $email_address);

                    $where_app = array('application_code' => $application_code);
                    if (!recordExists('tra_application_uploadeddocuments', $where_app, 'mis_db')) {
                        initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no . rand(0, 1000), $trader_id);
                    }
                }

                if ($resp) {
                    $res = array(
                        'tracking_no' => $tracking_no,
                        'product_id' => $product_id,
                        'success' => true,
                        'application_code' => $application_code,
                        'message' => 'Product Application Saved Successfully, with Tracking No: ' . $tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false,
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            } else {
                $app_data['local_agent_id'] = $local_agent_id;


                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'module_id' => $module_id, 'reference_type_id' => 1), 'reference_format_id', 'mis_db');

                $where_statement = array(
                    'sub_module_id' => 7,
                    't1.reg_product_id' => $reg_product_id, 't1.section_id' => $section_id
                );


                $primary_reference_no = getProductPrimaryReferenceNo($where_statement, 'tra_product_applications');
                $codes_array = array(
                    'ref_no' => $primary_reference_no
                );

                $where_statementref = array('reg_product_id' => $reg_product_id, 'sub_module_id' => $sub_module_id);

                $tracking_no = generateSubRefNumber($where_statementref, $applications_table, $ref_id, $codes_array, $sub_module_id, $trader_id);

                if (!validateIsNumeric($ref_id)) {
                    return \response()->json(array('success' => false, 'message' => 'Reference No Format has not been set, contact the system administrator'));
                } else if ($tracking_no == '') {
                    return \response()->json(array('success' => false, 'tracking_no' => $tracking_no, 'message' => $tracking_no));
                }

                $res = array('success' => false, 'tracking_no' => $ref_id);

                $resp = insertRecord('wb_product_information', $product_infor, $email_address);
                $application_code = generateApplicationCode($sub_module_id, 'wb_product_applications');

                $product_id = $resp['record_id'];
                $app_data['created_by'] = $email_address;
                $app_data['created_on'] = Carbon::now();
                $app_data['tracking_no'] = $tracking_no;
                $app_data['product_id'] = $product_id;
                $app_data['application_code'] = $application_code;
                $app_data['application_initiator_id'] = $trader_id;
                $app_data['application_status_id'] = 1;

                $resp = insertRecord('wb_product_applications', $app_data, $email_address);

                $res = array(
                    'tracking_no' => $tracking_no,
                    'product_id' => $product_id,
                    'application_code' => $application_code,
                    'success' => true,
                    'message' => 'Product Application Saved Successfully, with Tracking No: ' . $tracking_no
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
    public function onSaveProductOtherDetails(Request $req)
    {
        try {
            $resp = "";
            $trader_id = $req->trader_id;
            $email_address = $req->email_address;
            $product_id = $req->product_id;

            $data = $req->all();

            $table_name = $req->table_name;
            $record_id = $req->id;
            unset($data['table_name']);
            unset($data['email_address']);
            unset($data['trader_id']);
            unset($data['manufacturer_name']);
            unset($data['physical_address']);

            if ($table_name == 'wb_product_ingredients') {
                unset($data['id']);
            }


            if ($table_name == 'wb_product_manufacturers') {
                $manufacturer_role_id = $req->manufacturer_role_id;

                unset($data['manufacturing_site_name']);
                // unset($data['has_beeninspected']);
                unset($data['inspected_site_name']);
                $active_ingredient_id = $req->active_ingredient_id;

                $data['created_by'] = $email_address;
                $data['created_on'] = Carbon::now();
                $resp = insertRecord($table_name, $data, $email_address);

                $product_manufacturer_id = $resp['record_id'];
                $man_rolesdata = array();

                if (!validateIsNumeric($active_ingredient_id)) {
                }
            } else {
                if (validateIsNumeric($record_id)) {
                    $where = array('id' => $record_id);
                    if (recordExists($table_name, $where)) {

                        $data['dola'] = Carbon::now();
                        $data['altered_by'] = $email_address;

                        $previous_data = getPreviousRecords($table_name, $where);

                        $resp = updateRecord($table_name, $previous_data, $where, $data, $email_address);
                    }
                } else {

                    //insert 
                    $data['created_by'] = $email_address;
                    $data['created_on'] = Carbon::now();

                    $resp = insertRecord($table_name, $data, $email_address);
                }
            }

            if ($resp['success']) {

                $res =  array(
                    'success' => true,
                    'message' => 'Saved Successfully'
                );
            } else {
                $res =  array(
                    'success' => false,
                    'message' => $resp['message']
                );
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message1' => $resp,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false, 'message1' => $resp,
                'message' => $throwable->getMessage()
            );
        }

        return response()->json($res);
    }
    //check if its local 
    function checkProductTypes($manufacturer_id)
    {
        $sql = DB::connection('mis_db')
            ->table('tra_manufacturers_information as t1')
            ->join('countries as t2', 't1.country_id', '=', 't2.id')
            ->where(array('id' => $manufacturer_id))
            ->first();
        if ($sql) {
        }
    }

    public function onAddManufacturingSite(Request $req)
    {
        try {
            $resp = "";
            $trader_id = $req->trader_id;
            $traderemail_address = $req->traderemail_address;
            $email_address = $req->email_address;
            $error_message = 'Error occurred, data not saved successfully';

            $data = $req->all();

            $table_name = $req->table_name;
            $record_id = $req->id;
            $manufacturer_role_id = $req->manufacturer_role_id;
            $product_id = $req->product_id;

            $name = $req->name;
            $country_id = $req->country_id;

            unset($data['table_name']);
            unset($data['email_address']);
            unset($data['trader_id']);
            unset($data['manufacturer_role_id']);
            unset($data['product_id']);
            unset($data['traderemail_address']);
            if (validateIsNumeric($record_id)) {
                $where = array('id' => $record_id);
                if (recordExists($table_name, $where, 'mis_db')) {

                    $data['dola'] = Carbon::now();
                    $data['altered_by'] = $traderemail_address;

                    $previous_data = getPreviousRecords($table_name, $where, 'mis_db');

                    $resp = updateRecord($table_name, $previous_data, $where, $data, $traderemail_address);
                }
            } else {
                //insert 
                $data['created_by'] = $traderemail_address;
                $data['created_on'] = Carbon::now();

                if ($table_name == 'tra_manufacturers_information') {

                    $where = array('name' => $name, 'country_id' => $country_id);
                } else {
                    $where = array('name' => $name, 'country_id' => $country_id);
                }

                if (!recordExists($table_name, $where, 'mis_db')) {
                    $resp = insertRecord($table_name, $data, $traderemail_address, 'mis_db');

                    $record_id = $resp['record_id'];
                } else {
                    $error_message = "The Receiver/Sender Information exists with the following email Address: " . $email_address;
                }
            }
            if ($resp) {
                $res =  array(
                    'success' => true,
                    'record_id' => $record_id,
                    'message' => 'Saved Successfully'
                );
            } else {
                $res =  array(
                    'success' => false,
                    'message' => $error_message
                );
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'resp' => $resp,
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

    public function getproductNotificationsApps(Request $req)
    {
        try {
            $trader_id = $req->trader_id;
            $application_status_id = $req->application_status_id;
            $sub_module_id = $req->sub_module_id;
            $section_id = $req->section_id;

            $module_id = 6;

            $data = array();
            //get the records 
            $records = DB::table('wb_product_applications as t1')
                ->select(DB::raw('t1.tracking_no,t1.reference_no, t1.section_id,t5.name as local_agent,t1.id as application_id, t4.name as applicant_name, t2.*,t3.name as status_name,t1.application_status_id as status_id, t1.date_added, t1.submission_date,t1.product_id, t1.sub_module_id, t1.trader_id,t1.trader_id as applicant_id,  t1.reference_no, t1.application_status_id'))
                ->leftJoin('wb_product_information as t2', 't1.product_id', '=', 't2.id')
                ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                ->leftJoin('wb_trader_account as t4', 't1.trader_id', '=', 't4.id')
                ->leftJoin('wb_trader_account as t5', 't1.local_agent_id', '=', 't5.id')

                ->where(function ($q) use ($trader_id) {
                    $q->where('trader_id', $trader_id)
                        ->orWhere('application_initiator_id', $trader_id);
                });
            if (validateIsNumeric($application_status_id)) {
                $records =  $records->where(array('t1.application_status_id' => $application_status_id));
            }
            if (validateIsNumeric($sub_module_id)) {
                $records =  $records->where(array('t1.sub_module_id' => $sub_module_id));
            }
            if (validateIsNumeric($section_id)) {
                $records =  $records->where(array('t1.section_id' => $section_id));
            }
            if (validateIsNumeric($module_id)) {
                $records =  $records->where(array('t1.module_id' => $module_id));
            }
            $records = $records->get();

            $actionColumnData = returnContextMenuActions();
            $sectionsData = getParameterItems('par_sections', '', 'mis_db');
            $classData = getParameterItems('par_classifications', '', 'mis_db');
            $subModuleData = getParameterItems('sub_modules', '', 'mis_db');
            //manufacturing Information 

            foreach ($records as $rec) {
                //get the array 

                $data[] = array(
                    'reference_no' => $rec->reference_no,
                    'id' => $rec->id,
                    'tracking_no' => $rec->tracking_no,
                    'application_id' => $rec->application_id,
                    'product_id' => $rec->product_id,
                    'section_id' => $rec->section_id,
                    'brand_name' => $rec->brand_name,
                    'gmdn_code' => $rec->gmdn_code,
                    'gmdn_term' => $rec->gmdn_term,
                    'gmdn_category' => $rec->gmdn_category,
                    'date_added' => $rec->date_added,
                    'sub_module_id' => $rec->sub_module_id,
                    'applicant_name' => $rec->applicant_name,
                    'local_agent' => $rec->local_agent,
                    'application_status_id' => $rec->application_status_id,
                    'created_by' => $rec->created_by,
                    'submission_date' => $rec->submission_date,
                    'section' => returnParamFromArray($sectionsData, $rec->section_id),
                    'classification' => returnParamFromArray($classData, $rec->classification_id),
                    'application_type' => returnParamFromArray($subModuleData, $rec->sub_module_id),
                    'status' => $rec->status_name,
                    'contextMenu' => returnActionColumn($rec->application_status_id, $actionColumnData)

                );
            }
            $res = array(
                'success' => true,
                'data' => $data
            );
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
    public function getLocaAgentProductApplications(Request $req)
    {
        try {
            $localagent_id = $req->trader_id;
            $application_status_id = $req->application_status_id;
            if ($application_status_id != '') {

                $application_status_id = explode(',', $req->application_status_id);
            }
            $sub_module_id = $req->sub_module_id;
            $section_id = $req->section_id;
            $application_status = $req->application_status;
            $mis_db = DB::connection('mis_db')->getDatabaseName();

            $data = array();
            //get the records 
            $records = DB::table('wb_product_applications as t1')
                ->select(DB::raw('t1.tracking_no,t1.reference_no,t1.application_code, t1.section_id,t5.name as local_agent,t1.id as application_id, t4.name as applicant_name,t4.physical_address, t2.*,t3.name as status_name,t1.application_status_id as status_id, t1.date_added, t1.submission_date,t1.product_id, t1.sub_module_id, t1.trader_id,t1.trader_id as applicant_id,  t1.reference_no, t1.application_status_id'))
                ->leftJoin('wb_product_information as t2', 't1.product_id', '=', 't2.id')
                ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                ->leftJoin('wb_trader_account as t4', 't1.trader_id', '=', 't4.id')
                ->leftJoin('wb_premises as t5', 't1.local_agent_id', '=', 't5.id')
                ->leftJoin('wb_premises_applications as t6', 't6.premise_id', '=', 't5.id')

                ->where(function ($q) use ($localagent_id) {
                    $q->where('t6.trader_id', $localagent_id);
                    //  ->Where('t1.trader_id','!=', $localagent_id);
                });
            if (is_array($application_status_id) && count($application_status_id) > 0) {

                $records =  $records->whereIn('application_status_id', $application_status_id);
            }
            if (validateIsNumeric($sub_module_id)) {
                $records =  $records->where(array('t1.sub_module_id' => $sub_module_id));
            }
            if (validateIsNumeric($section_id)) {
                $records =  $records->where(array('t1.section_id' => $section_id));
            }
            if ($application_status != '') {
                $records =  $records->whereIn('t1.application_status_id', explode(',', $application_status));
            }
            $records = $records->orderby('t1.date_added', 'desc')->get();

            $actionColumnData = returnContextMenuActions();
            $sectionsData = getParameterItems('par_sections', '', 'mis_db');
            $classData = getParameterItems('par_classifications', '', 'mis_db');
            $subModuleData = getParameterItems('sub_modules', '', 'mis_db');

            foreach ($records as $rec) {
                //get the array 

                $data[] = array(
                    'reference_no' => $rec->reference_no,
                    'id' => $rec->id,
                    'tracking_no' => $rec->tracking_no,
                    'application_id' => $rec->application_id,
                    'product_id' => $rec->product_id,
                    'section_id' => $rec->section_id,
                    'brand_name' => $rec->brand_name,
                    'date_added' => $rec->date_added,
                    'sub_module_id' => $rec->sub_module_id,
                    'applicant_name' => $rec->applicant_name,
                    'local_agent' => $rec->local_agent,
                    'application_status_id' => $rec->application_status_id,
                    'created_by' => $rec->created_by,
                    'submission_date' => $rec->submission_date,

                    'physical_address' => $rec->physical_address,
                    'section' => returnParamFromArray($sectionsData, $rec->section_id),
                    'classification' => returnParamFromArray($classData, $rec->classification_id),
                    'application_type' => returnParamFromArray($subModuleData, $rec->sub_module_id),
                    'status' => $rec->status_name,
                    'application_code' => $rec->application_code,
                    'contextMenu' => returnActionColumn($rec->application_status_id, $actionColumnData)
                );
            }
            $res = array(
                'success' => true,
                'data' => $data
            );
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

    public function getProductApplications(Request $req)
    {

        try {
            $trader_id = $req->trader_id;
            $application_status_id = $req->application_status_id;
            if ($application_status_id != '') {

                $application_status_id = explode(',', $req->application_status_id);
            }
            $module_id = $req->module_id;
            $sub_module_id = $req->sub_module_id;
            $section_id = $req->section_id;
            $application_status = $req->application_status;
            $group_application_code = $req->group_application_code;
            $mis_db = DB::connection('mis_db')->getDatabaseName();
            $data = array();
            //get the records 
            $records = DB::table('wb_product_applications as t1')
                ->select(DB::raw('t7.name as action_name,t7.iconcls,t7.action,  t1.tracking_no,t1.reference_no,t1.application_code, t1.section_id,t5.name as local_agent,t5.name as local_agent_name,t1.id as application_id, t4.name as applicant_name, t2.*,t3.name as status_name,t1.application_status_id as status_id, t1.date_added, t1.submission_date,t1.product_id, t1.sub_module_id, t1.trader_id,t1.trader_id as applicant_id,  t1.reference_no, t1.application_status_id'))
                ->leftJoin('wb_product_information as t2', 't1.product_id', '=', 't2.id')
                ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                ->leftJoin('wb_trader_account as t4', 't1.trader_id', '=', 't4.id')
                ->leftJoin('wb_premises as t5', 't1.local_agent_id', '=', 't5.id')
                ->leftJoin('wb_processstatus_actions as t6', function ($join) {
                    $join->on('t1.application_status_id', '=', 't6.status_id')
                        ->on('t6.is_default_action', '=', DB::raw(1));
                })->leftJoin('wb_appsubmissions_typedetails as t8', function ($join) {
                    $join->on('t1.group_application_code', '=', 't8.group_application_code')
                        ->on('t1.application_status_id', '!=', DB::raw(1));
                })
                ->where("t1.date_added", ">", "2024-02-12") //Job to remove
                ->leftJoin('wb_statuses_actions as t7', 't6.action_id', 't7.id');

            // ->whereRaw("if(t1.group_application_code >0,t1.application_status_id >1,1)");

            if ($trader_id != 25) {
                $records->where(function ($q) use ($trader_id) {
                    $q->where('t1.trader_id', $trader_id)
                        ->orWhere('t1.application_initiator_id', $trader_id);
                });
            }

            if (is_array($application_status_id) && count($application_status_id) > 0) {
                $records =  $records->whereIn('t1.application_status_id', $application_status_id);
            }
            if (validateIsNumeric($sub_module_id)) {
                $records =  $records->where(array('t1.sub_module_id' => $sub_module_id));
            }
            if (validateIsNumeric($section_id)) {
                $records =  $records->where(array('t1.section_id' => $section_id));
            }
            if (validateIsNumeric($group_application_code)) {
                $records =  $records->where(array('t1.group_application_code' => $group_application_code));
            }
            $records =  $records->whereIn('t1.module_id', [1, 6]);
            if ($application_status != '') {
                $records =  $records->whereIn('t1.application_status_id', explode(',', $application_status));
            }
            $records = $records->orderby('t1.date_added', 'desc')->get();


            $actionColumnData = returnContextMenuActions();
            $sectionsData = getParameterItems('par_sections', '', 'mis_db');
            $classData = getParameterItems('par_classifications', '', 'mis_db');
            $subModuleData = getParameterItems('sub_modules', '', 'mis_db');

            foreach ($records as $rec) {
                //get the array 
                $data[] = array(
                    'reference_no' => $rec->reference_no,
                    'id' => $rec->id,
                    'tracking_no' => $rec->tracking_no,
                    'application_id' => $rec->application_id,
                    'product_id' => $rec->product_id,
                    'section_id' => $rec->section_id,
                    'brand_name' => $rec->brand_name,
                    'date_added' => $rec->date_added,
                    'sub_module_id' => $rec->sub_module_id,
                    'applicant_name' => $rec->applicant_name,
                    'local_agent' => $rec->local_agent,
                    'local_agent_name' => $rec->local_agent_name,
                    'application_status_id' => $rec->application_status_id,
                    'created_by' => $rec->created_by,
                    'submission_date' => $rec->submission_date,
                    'section' => returnParamFromArray($sectionsData, $rec->section_id),
                    'classification' => returnParamFromArray($classData, $rec->classification_id),
                    'application_type' => returnParamFromArray($subModuleData, $rec->sub_module_id),
                    'status' => $rec->status_name,
                    'status_name' => $rec->status_name,
                    'action_name' => $rec->action_name,
                    'action' => $rec->action,
                    'iconcls' => $rec->iconcls,
                    'application_code' => $rec->application_code,
                    'contextMenu' => returnActionColumn($rec->application_status_id, $actionColumnData)

                );
            }


            if (!validateIsNumeric($group_application_code)) {
                $recordData = DB::table('wb_appsubmissions_typedetails as t1')
                    ->select(DB::raw("t7.name as action_name,t7.iconcls,t7.action,  t1.group_tracking_no as tracking_no,t1.group_tracking_no as reference_no,t1.group_application_code, t1.section_id,t5.name as local_agent,t1.id as application_id, t4.name as applicant_name, 'Grouped Application' as brand_name,t3.name as status_name,t1.application_status_id as status_id,t1.appsubmissions_type_id, t1.date_added,  t1.sub_module_id, t1.trader_id,t1.trader_id as applicant_id, t1.application_status_id"))
                    ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                    ->leftJoin('wb_trader_account as t4', 't1.trader_id', '=', 't4.id')
                    ->leftJoin('wb_premises as t5', 't1.local_agent_id', '=', 't5.id')
                    ->leftJoin('wb_processstatus_actions as t6', function ($join) {
                        $join->on('t1.application_status_id', '=', 't6.status_id')
                            ->on('t6.is_default_action', '=', DB::raw(1));
                    })
                    ->where("t1.date_added", ">", "2024-02-12") //Job to remove just for demo
                    ->leftJoin('wb_statuses_actions as t7', 't6.action_id', 't7.id');

                if ($trader_id != 25) {
                    $recordData->where(function ($q) use ($trader_id) {
                        $q->where('t1.trader_id', $trader_id);
                    });
                }
                if (is_array($application_status_id) && count($application_status_id) > 0) {
                    $recordData =  $recordData->whereIn('t1.application_status_id', $application_status_id);
                }
                if (validateIsNumeric($sub_module_id)) {
                    $recordData =  $recordData->where(array('t1.sub_module_id' => $sub_module_id));
                }
                if (validateIsNumeric($section_id)) {
                    $recordData =  $recordData->where(array('t1.section_id' => $section_id));
                }
                $recordData =  $recordData->whereIn('t1.module_id', [1, 6]);
                if ($application_status != '') {
                    $recordData =  $recordData->whereIn('t1.application_status_id', explode(',', $application_status));
                }
                $recordData = $recordData->orderby('t1.date_added', 'desc')->get();

                foreach ($recordData as $rec) {
                    //get the array 
                    $data[] = array(
                        'reference_no' => $rec->reference_no,
                        'group_tracking_no' => $rec->tracking_no,
                        'group_application_id' => $rec->application_id,
                        'section_id' => $rec->section_id,
                        'id' => $rec->application_id,
                        'brand_name' => $rec->brand_name,
                        'date_added' => $rec->date_added,
                        'sub_module_id' => $rec->sub_module_id,
                        'applicant_name' => $rec->applicant_name,
                        'local_agent' => $rec->local_agent,
                        'application_status_id' => $rec->application_status_id,
                        'section' => returnParamFromArray($sectionsData, $rec->section_id),
                        'application_type' => 'Grouped Product Application ' . returnParamFromArray($subModuleData, $rec->sub_module_id),
                        'status' => $rec->status_name,
                        'status_name' => $rec->status_name,
                        'action_name' => $rec->action_name,
                        'action' => $rec->action,
                        'iconcls' => $rec->iconcls,
                        'group_application_code' => $rec->group_application_code,
                        'appsubmissions_type_id' => $rec->appsubmissions_type_id,
                        'contextMenu' => returnActionColumn($rec->application_status_id, $actionColumnData)

                    );
                }
            }


            $res = array(
                'success' => true,
                'data' => $data
            );
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
    public function getProductApplicationInformation(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $data = array();
            // $mis_db = "mis_db";
            $mis_db = DB::connection('mis_db')->getDatabaseName();
            //get the records ,date_format(manufacturing_date, '%m/%d/%Y') as manufacturing_date, date_format(expiry_date, '%m/%d/%Y') as expiry_date
            $records = DB::table('wb_product_applications as t1')
                ->select(DB::raw("t1.*,t5.name as local_agent_name,t5.name as local_agent,  t2.*,t1.application_status_id as status_id, t3.name as status_name,t6.manufacturer_id, t4.router_link,t1.trader_id as applicant_id, t4.name as process_title"))
                ->join('wb_product_information as t2', 't1.product_id', '=', 't2.id')
                ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                ->leftJoin('wb_tfdaprocesses as t4', function ($join) {
                    $join->on('t1.sub_module_id', '=', 't4.sub_module_id');
                    $join->on('t1.application_status_id', '=', 't4.status_id');
                    //  $join->on('t1.section_id', '=', 't4.section_id');
                    //$join->on('t2.prodclass_category_id', '=', 't4.prodclass_category_id');
                })
                ->leftJoin($mis_db . '.tra_premises  as t5', 't1.local_agent_id', '=', 't5.id')
                ->leftJoin('wb_product_manufacturers as t6', 't1.product_id', '=', 't6.product_id')
                ->where(array('t1.application_code' => $application_code))
                ->where(array('t4.appsubmissions_type_id' => 1))
                ->first();
            $manufacturer_id = $records->manufacturer_id;
            $manufacturing_site_name = getSingleRecordColValue('tra_manufacturers_information', array('id' => $manufacturer_id), 'name', 'mis_db');
            //par_man_sites
            $records->{"manufacturer_name"} = $manufacturing_site_name;
            $records->{"form_fields"} = getApplicationGeneralFormsFields($records);
            $product_id = $records->product_id;
            $qry = DB::table('wb_prod_routeofadministrations')
                ->where('product_id', $product_id)
                ->select('route_of_administration_id');
            $routeofadministrations = $qry->get();
            if ($routeofadministrations->count() > 0) {
                $routeofadministrations = convertStdClassObjToArray($routeofadministrations);
                $route_of_administration_id = convertAssArrayToSimpleArray($routeofadministrations, 'route_of_administration_id');
                $records->route_of_administration_id = $route_of_administration_id;
            }

            $qry = DB::table('wb_prod_targetspecies')
                ->where('product_id', $product_id)
                ->select('target_species_id');
            $prod_targetspecies = $qry->get();
            if ($prod_targetspecies->count() > 0) {
                $prod_targetspecies = convertStdClassObjToArray($prod_targetspecies);
                $target_species_id = convertAssArrayToSimpleArray($prod_targetspecies, 'target_species_id');
                $records->target_species_id = $target_species_id;
            }

            $res = array('success' => true, 'data' => $records);
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
    public function getProductNotificationsInformation(Request $req)
    {
        try {
            $application_id = $req->application_id;
            $data = array();
            //get the records 

            $mis_db = DB::connection('mis_db')->getDatabaseName();
            $records = DB::table('wb_product_applications as t1')
                ->select(DB::raw("t1.*,t6.manufacturer_id, t5.name as local_agent_name, t2.*,t1.application_status_id as status_id, t3.name as status_name, t4.router_link,t1.trader_id as applicant_id, t4.name as process_title,date_format(manufacturing_date, '%m/%d/%Y') as manufacturing_date, date_format(expiry_date, '%m/%d/%Y') as expiry_date"))
                ->join('wb_product_information as t2', 't1.product_id', '=', 't2.id')
                ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                ->leftJoin('wb_tfdaprocesses as t4', function ($join) {
                    $join->on('t1.sub_module_id', '=', 't4.sub_module_id');
                    $join->on('t1.application_status_id', '=', 't4.status_id');
                })
                ->leftJoin($mis_db . '.tra_premises as t5', 't1.local_agent_id', '=', 't5.id')
                ->leftJoin('wb_product_manufacturers as t6', 't1.product_id', '=', 't6.product_id')
                ->where(array('t1.id' => $application_id))
                ->first();
            //get the two columns 
            $manufacturer_id = $records->manufacturer_id;

            $manufacturing_site_name = getSingleRecordColValue('tra_manufacturers_information', array('id' => $manufacturer_id), 'name', 'mis_db');
            //par_man_sites
            $records->{"manufacturer_name"} = $manufacturing_site_name;

            //print_r($records);
            $res = array('success' => true, 'data' => $records);
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

    public function getProductSampleStageInformation(Request $req)
    {
        try {
            $application_id = $req->application_id;
            $data = array();
            $mis_db = DB::connection('mis_db')->getDatabaseName();
            //get the records 
            $records = DB::table('wb_product_applications as t1')
                ->select(DB::raw("t1.*,t5.name as local_agent_name, t2.*,t1.application_status_id as status_id,t3.name as status_name, t4.router_link,t1.trader_id as applicant_id, t4.name as process_title,date_format(manufacturing_date, '%m/%d/%Y') as manufacturing_date, date_format(expiry_date, '%m/%d/%Y') as expiry_date"))
                ->join('wb_product_information as t2', 't1.product_id', '=', 't2.id')
                ->leftJoin('wb_statuses as t3', 't1.application_status_id',  't1.application_status_id', '=', 't3.id')
                ->leftJoin('wb_tfdaprocesses as t4', function ($join) {
                    $join->on('t1.sub_module_id', '=', 't4.sub_module_id');
                    $join->on('t1.application_status_id', '=', 't4.status_id');
                })
                ->leftJoin($mis_db . '.tra_premises as t5', 't1.local_agent_id', '=', 't5.id')
                ->where(array('t1.id' => $application_id))
                ->get();

            $res = array('success' => true, 'data' => $records);
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
    public function getProductsIngredients(Request $req)
    {
        $product_id = $req->product_id;
        if (!is_numeric($product_id)) {
            return $res = array(
                'success' => true,
                'data' => []
            );
        }
        try {

            $data = array();
            //get the records 
            $records = DB::table('wb_product_ingredients as t1')
                ->select('t1.*')
                ->where(array('t1.product_id' => $product_id))
                ->get();
            //loop
            $speficification_typeData = getParameterItems('par_specification_types', '', 'mis_db');
            $si_unitData = getParameterItems('par_si_units', '', 'mis_db');
            $ingredientsData = getParameterItems('par_ingredients_details', '', 'mis_db');
            $inclusion_reasonData = getParameterItems('par_inclusions_reasons', '', 'mis_db');
            $ingredientTypeData = getParameterItems('par_ingredients_types', '', 'mis_db');

            foreach ($records as $rec) {
                //get the array 

                $data[] = array(
                    'product_id' => $rec->product_id,
                    'id' => $rec->id,
                    'ingredient_type_id' => $rec->ingredient_type_id,
                    'ingredient_id' => $rec->ingredient_id,
                    'specification_type_id' => $rec->specification_type_id,
                    'strength' => $rec->strength,
                    'proportion' => $rec->proportion,
                    'inci_name' => $rec->inci_name,
                    'cas_number' => $rec->cas_number,
                    'ingredient_function' => $rec->ingredient_function,
                    'ingredientssi_unit_id' => $rec->ingredientssi_unit_id,
                    'inclusion_reason_id' => $rec->inclusion_reason_id,
                    'ingredient' => returnParamFromArray($ingredientsData, $rec->ingredient_id),
                    'ingredient_type' => returnParamFromArray($ingredientTypeData, $rec->ingredient_type_id),
                    'specification' => returnParamFromArray($speficification_typeData, $rec->specification_type_id),
                    'si_units' => returnParamFromArray($si_unitData, $rec->ingredientssi_unit_id),
                    'reason_of_inclusion' => returnParamFromArray($inclusion_reasonData, $rec->inclusion_reason_id),
                );
            }
            $res = array('success' => true, 'data' => $data);
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
    public function getProductsNutrients(Request $req)
    {
        try {
            $product_id = $req->product_id;
            $data = array();
            //get the records 
            $records = DB::table('wb_product_nutrients as t1')
                ->select('t1.*')
                ->where(array('t1.product_id' => $product_id))
                ->get();
            //loop
            $nutrientsCategory = getParameterItems('par_nutrients_category', '', 'mis_db');
            $si_unitData = getParameterItems('par_si_units', '', 'mis_db');
            $nutrientsData = getParameterItems('par_nutrients', '', 'mis_db');

            foreach ($records as $rec) {
                //get the array 

                $data[] = array(
                    'product_id' => $rec->product_id,
                    'id' => $rec->id,
                    'nutrients_category_id' => $rec->nutrients_category_id,
                    'nutrients_id' => $rec->nutrients_id,
                    'proportion' => $rec->proportion,
                    'units_id' => $rec->units_id,
                    'nutrients' => returnParamFromArray($nutrientsData, $rec->nutrients_id),
                    'nutrients_category' => returnParamFromArray($nutrientsCategory, $rec->nutrients_category_id),
                    'si_units' => returnParamFromArray($si_unitData, $rec->units_id),
                );
            }
            $res = array('success' => true, 'data' => $data);
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

    public function getProductsGMPInspectionDetails(Request $req)
    {
        $product_id = $req->product_id;
        if (!is_numeric($product_id)) {
            return $res = array(
                'success' => true,
                'data' => []
            );
        }
        try {

            $data = array();
            //get the records 
            $records = DB::table('wb_product_gmpinspectiondetails as t1')
                ->select('t1.*')
                ->where(array('t1.product_id' => $product_id))
                ->get();

            foreach ($records as $rec) {
                //get the array 
                $reg_site_id = $rec->reg_site_id;
                $gmp_productline_id = $rec->gmp_productline_id;

                $records =  DB::connection('mis_db')->table('tra_manufacturing_sites as t1')
                    ->select('t5.id as reg_manufacturer_site_id', 't7.permit_no as gmp_certificate_no', 't6.reference_no as gmp_application_reference', 't8.name as registration_status', 't7.permit_no', 't1.physical_address', 't1.email as email_address', 't1.id as manufacturer_id', 't1.name as manufacturer_name', 't2.name as country_name', 't3.name as region_name', 't4.name as district')
                    ->join('par_countries as t2', 't1.country_id', '=', 't2.id')
                    ->join('par_regions as t3', 't1.region_id', '=', 't3.id')
                    ->join('par_districts as t4', 't1.district_id', '=', 't4.id')
                    ->join('registered_manufacturing_sites as t5', 't1.id', '=', 't5.tra_site_id')
                    ->leftJoin('tra_gmp_applications as t6', 't1.id', '=', 't6.manufacturing_site_id')
                    ->join('tra_approval_recommendations as t7', 't1.permit_id', '=', 't7.id')
                    ->leftJoin('par_system_statuses as t8', 't5.status_id', '=', 't8.id')
                    ->where(array('t5.id' => $reg_site_id))
                    ->first();
                $product_linedetails = $this->getGMPProductLineDetails($gmp_productline_id);
                if ($records) {
                    $data[] = array(
                        'id' => $rec->id,
                        'product_id' => $rec->product_id,
                        'reg_site_id' => $reg_site_id,
                        'gmp_certificate_no' => $records->gmp_certificate_no,
                        'gmp_application_reference' => $records->gmp_application_reference,
                        'permit_no' => $records->permit_no,
                        'manufacturer_name' => $records->manufacturer_name,
                        'physical_address' => $records->physical_address,
                        'email_address' => $records->email_address,
                        'manufacturer_id' => $records->manufacturer_id,
                        'country' => $records->country_name,
                        'region' => $records->region_name,
                        'district' => $records->district,
                        'product_linedetails' => $product_linedetails
                    );
                }
            }
            $res = array('success' => true, 'data' => $data);
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
    function getGMPProductLineDetails($product_line_id)
    {
        $records = DB::connection('mis_db')->table('gmp_productline_details as t1')
            ->select('t1.*', 't2.name as product_line', 't1.id as product_id', 't3.name as product_category')
            ->leftJoin('gmp_product_lines as t2', 't1.product_line_id', '=', 't2.id')
            ->leftJoin('gmp_product_categories as t3', 't1.category_id', '=', 't3.id')
            ->leftJoin('gmp_product_descriptions as t4', 't1.prodline_description_id', '=', 't4.id')
            ->where(array('t1.id' => $product_line_id))
            ->first();
        if ($records) {
            return  $records->product_line . ' ' . $records->product_category;
        }
    }
    public function getgmpProductLineDatadetails(Request $req)
    {
        try {
            $manufacturing_site_id = $req->manufacturing_site_id;
            $data = array();
            //get the records 
            $records = DB::connection('mis_db')->table('gmp_productline_details as t1')
                ->select('t1.*', 't2.name as product_line', 't1.id as product_id', 't3.name as product_category')
                ->join('gmp_product_lines as t2', 't1.product_line_id', '=', 't2.id')
                ->join('gmp_product_categories as t3', 't1.category_id', '=', 't3.id')
                ->join('gmp_product_descriptions as t4', 't1.prodline_description_id', '=', 't4.id')
                ->where(array('t1.manufacturing_site_id' => $manufacturing_site_id))
                ->get();
            $res = array('success' => true, 'data' => $records);
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

    public function getProductsDrugsPackaging(Request $req)
    {
        $product_id = $req->product_id;
        if (!is_numeric($product_id)) {
            return $res = array(
                'success' => true,
                'data' => []
            );
        }
        try {

            $data = array();
            //get the records 
            $records = DB::table('wb_product_packaging as t1')
                ->select(DB::raw("t1.*, CONCAT_WS('X',retail_packaging_size,retail_packaging_size1,retail_packaging_size2,retail_packaging_size3,retail_packaging_size4) as retail_packaging"))

                ->where(array('t1.product_id' => $product_id))
                ->get();
            //loop container_id
            $containersData = getParameterItems('par_containers', '', 'mis_db');
            $containersMaterialsData = getParameterItems('par_containers_materials', '', 'mis_db');
            $containersClosuresData = getParameterItems('par_closure_materials', '', 'mis_db');
            $containersSealData = getParameterItems('par_seal_types', '', 'mis_db');
            $containersTypesData = getParameterItems('par_containers_types', '', 'mis_db');
            $packagingUnitsData = getParameterItems('par_packaging_units', '', 'mis_db');

            foreach ($records as $rec) {
                //get the array 

                $data[] = array(
                    'product_id' => $rec->product_id,
                    'id' => $rec->id,
                    'container_id' => $rec->container_id,
                    'container_material_id' => $rec->container_material_id,
                    'container_type_id' => $rec->container_type_id,
                    'closure_material_id' => $rec->closure_material_id,
                    'seal_type_id' => $rec->seal_type_id,
                    'retail_packaging_size' => $rec->retail_packaging_size,
                    'retail_packaging_size1' => $rec->retail_packaging_size1,
                    'retail_packaging_size2' => $rec->retail_packaging_size2,
                    'retail_packaging_size3' => $rec->retail_packaging_size3,
                    'retail_packaging_size4' => $rec->retail_packaging_size4,

                    'retail_packaging' => $rec->retail_packaging,
                    'packaging_units_id' => $rec->packaging_units_id,
                    'unit_pack' => $rec->unit_pack,

                    'unit_pack_name' => returnParamFromArray($packagingUnitsData, $rec->unit_pack),
                    'primary_container' => returnParamFromArray($containersData, $rec->container_id),
                    'container_materials' => returnParamFromArray($containersMaterialsData, $rec->container_material_id),
                    'container_type' => returnParamFromArray($containersTypesData, $rec->container_type_id),
                    'closure_material' => returnParamFromArray($containersClosuresData, $rec->closure_material_id),
                    'seal_type' => returnParamFromArray($containersSealData, $rec->seal_type_id),
                );
            }
            $res = array('success' => true, 'data' => $data);
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
    public function onDeleteProductsDetails(Request $req)
    {

        try {
            $record_id = $req->record_id;
            $product_id = $req->product_id;
            $table_name = $req->table_name;
            $title = $req->title;
            $email_address = $req->email_address;
            $data = array();
            //get the records 
            $resp = false;
            $where_state = array('product_id' => $product_id, 'id' => $record_id);
            $records = DB::table($table_name)
                ->where($where_state)
                ->get();
            if (count($records) > 0) {
                //delete functionality

                $previous_data = getPreviousRecords($table_name, $where_state);

                $resp = deleteRecordNoTransaction($table_name, $previous_data, $where_state,  $email_address);
            }
            if ($resp) {
                $res = array('success' => true, 'message' => $title . ' deleted successfully');
            } else {
                $res = array('success' => false, 'message' => $title . ' delete failed, contact the system admin if this persists');
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

    public function getManufacturersInformation(Request $req)
    {
        try {
            $take = $req->take;
            $skip = $req->skip;
            $searchValue = $req->searchValue;

            if ($req->searchValue != 'undefined') {
                $searchValue = explode(',', $searchValue);
                $searchValue =  $searchValue[2];
            } else {
                $searchValue =  '';
            }

            $qry = DB::connection('mis_db')
                ->table('tra_manufacturers_information as t1')
                ->select('t1.*', 't1.id as manufacturer_id', 't1.name as manufacturer_name', 't2.name as country', 't3.name as region', 't4.name as district', 't1.country_id  as country_oforigin_id')
                ->leftJoin('par_countries as t2', 't1.country_id', '=', 't2.id')
                ->leftJoin('par_regions as t3', 't1.region_id', '=', 't3.id')
                ->leftJoin('par_districts as t4', 't1.district_id', '=', 't4.id');

            if ($searchValue != '') {
                $whereClauses = array();
                $whereClauses[] = "t1.name like '%" . ($searchValue) . "%'";
                $whereClauses[] = "t1.email_address like '%" . ($searchValue) . "%'";
                $filter_string = implode(' OR ', $whereClauses);
                $qry->whereRAW($filter_string);
            }
            $records = $qry->skip($skip)->take($take)->get();

            $totalCount = $qry->count();
            $res = array(
                'success' => true,
                'data' => $records,
                'totalCount' => $totalCount
            );
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
    public function getManufacturingSiteInformation(Request $req)
    {
        try {
            $take = $req->take;
            $skip = $req->skip;
            $searchValue = $req->searchValue;
            $manufacturer_id = $req->manufacturer_id;

            $qry = DB::connection('mis_db')
                ->table('par_man_sites as t1')
                ->select('t1.*', 't1.id as man_site_id', 't5.name as manufacturer_name', 't1.name as manufacturing_site_name', 't2.name as country', 't3.name as region', 't4.name as district')
                ->join('par_countries as t2', 't1.country_id', '=', 't2.id')
                ->join('par_regions as t3', 't1.region_id', '=', 't3.id')
                ->leftJoin('par_districts as t4', 't1.district_id', '=', 't4.id')
                ->join('tra_manufacturers_information as t5', 't1.manufacturer_id', '=', 't5.id');

            if (validateIsNumeric($manufacturer_id)) {
                $qry =    $qry->where('t1.manufacturer_id', $manufacturer_id);
            }

            $records = $qry->get();

            $totalCount = $qry->count();
            $res = array(
                'success' => true,
                'data' => $records,
                'totalCount' => $totalCount
            );
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
    function getManufacturerRoles($product_manufacturer_id, $manufacturer_roleData)
    {
        $man_roles = '';
        $records = DB::table('wb_product_manufacturers_roles')
            ->where(array('product_manufacturer_id' => $product_manufacturer_id))
            ->get();
        foreach ($records as $rec) {
            $manufacturer_role_id = $rec->manufacturer_role_id;

            $manufacturing_role = returnParamFromArray($manufacturer_roleData, $manufacturer_role_id);

            $man_roles .= $manufacturing_role . ';';
        }
        return $man_roles;
    }
    public function getproductManufactureringData(Request $req)
    {

        try {
            $data = array();
            $product_id = $req->product_id;
            if (!is_numeric($product_id)) {
                return $res = array(
                    'success' => true,
                    'data' => []
                );
            }
            $manufacturer_type_id = $req->manufacturer_type_id;
            $records = DB::table('wb_product_manufacturers as t1')
                ->where(array('product_id' => $product_id, 'manufacturer_type_id' => $manufacturer_type_id))
                ->get();

            foreach ($records as $rec) {
                $product_manufacturer_id = $rec->id;
                $manufacturer_id = $rec->manufacturer_id;
                $man_site_id = $rec->man_site_id;

                $manufacturer_roleData = getParameterItems('par_manufacturing_roles', '', 'mis_db');
                $manufacturing_role = $this->getManufacturerRoles($product_manufacturer_id, $manufacturer_roleData);

                $man_data = DB::connection('mis_db')
                    ->table('tra_manufacturers_information as t1')
                    ->select('t5.*', 't1.id as manufacturer_id', 't5.name as manufacturing_site', 't1.name as manufacturer_name', 't2.name as country', 't3.name as region', 't4.name as district')
                    ->join('par_countries as t2', 't1.country_id', '=', 't2.id')
                    ->leftJoin('par_regions as t3', 't1.region_id', '=', 't3.id')
                    ->leftJoin('par_districts as t4', 't1.district_id', '=', 't4.id')
                    ->leftJoin('par_man_sites as t5', 't5.manufacturer_id', '=', 't1.id')
                    ->where(array('t1.id' => $manufacturer_id));

                if (validateIsNumeric($man_site_id)) {
                    $man_data->where(array('t5.id' => $man_site_id));
                }
                $man_data =   $man_data->first();

                if ($man_data) {
                    $data[] = array(
                        'id' => $rec->id,
                        'manufacturer_name' => $man_data->manufacturer_name,
                        'manufacturing_site' => $man_data->manufacturing_site,
                        'country' => $man_data->country,
                        'region' => $man_data->region,
                        'product_id' => $rec->product_id,
                        'physical_address' => $man_data->physical_address,
                        'postal_address' => $man_data->postal_address,
                        'manufacturing_role' => $manufacturing_role,
                        'email_address' => $man_data->email
                    );
                } else {
                    $data[] = array(
                        'id' => $rec->id,
                        'manufacturer_name' => $man_data->manufacturer_name,
                        'country' => $man_data->country,
                        'region' => $man_data->region,
                        'product_id' => $rec->product_id,
                        'physical_address' => $man_data->physical_address,
                        'postal_address' => $man_data->postal_address,
                        'manufacturing_role' => $manufacturing_role,
                        'email_address' => $man_data->email
                    );
                }
            }
            $res = array(
                'success' => true,
                'data' => $data
            );
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
    public function getAPIproductManufactureringData(Request $req)
    {
        $product_id = $req->product_id;
        if (!is_numeric($product_id)) {
            return $res = array(
                'success' => true,
                'data' => []
            );
        }
        try {
            $data = array();

            $manufacturer_type_id = 2;
            $records = DB::table('wb_product_manufacturers as t1')
                ->select('t1.*', 't2.ingredient_id')
                ->join('wb_product_ingredients as t2', 't1.active_ingredient_id', '=', 't2.id')
                ->where(array('t1.product_id' => $product_id, 'manufacturer_type_id' => $manufacturer_type_id))
                ->get();
            foreach ($records as $rec) {
                $manufacturer_id = $rec->manufacturer_id;
                $ingredient_id = $rec->ingredient_id;

                $manufacturer_role_id = $rec->manufacturer_role_id;
                $manufacturer_roleData = getParameterItems('par_manufacturing_roles', '', 'mis_db');
                $manufacturing_role = returnParamFromArray($manufacturer_roleData, $manufacturer_role_id);

                $ingredients_Data = getParameterItems('par_ingredients_details', '', 'mis_db');
                $active_ingredient = returnParamFromArray($ingredients_Data, $ingredient_id);

                $records = DB::connection('mis_db')
                    ->table('tra_manufacturers_information as t1')
                    ->select('t1.*', 't1.id as manufacturer_id', 't1.name as manufacturer_name', 't2.name as country', 't3.name as region', 't4.name as district')
                    ->leftJoin('par_countries as t2', 't1.country_id', '=', 't2.id')
                    ->leftJoin('par_regions as t3', 't1.region_id', '=', 't3.id')
                    ->leftJoin('par_districts as t4', 't1.district_id', '=', 't4.id')
                    ->where(array('t1.id' => $manufacturer_id))
                    ->first();

                $data[] = array(
                    'id' => $rec->id,
                    'manufacturer_name' => $records->manufacturer_name,
                    'country' => $records->country,
                    'region' => $records->region,
                    'product_id' => $rec->product_id,
                    'physical_address' => $records->physical_address,
                    'postal_address' => $records->postal_address,
                    'manufacturing_role' => $manufacturing_role,
                    'active_ingredient' => $active_ingredient,
                    'email_address' => $records->email_address
                );
            }
            $res = array(
                'success' => true,
                'data' => $data
            );
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
    public function getTraderInformationDetails(Request $req)
    {
        //the details 
        try {
            $search_value  = '';
            $take = 50; // $req->take;
            $skip = 0; // $req->skip;
            $searchValue = $req->searchValue;
            if ($req->searchValue != 'undefined') {

                $searchValue = explode(',', $searchValue);
                $search_value = '';
                if (isset($searchValue[2])) {
                    $search_value =  $searchValue[2];
                }
            }
            $data = array();
            $is_local_agent = $req->is_local_agent;

            if ($is_local_agent == 1) {


                $data = DB::connection('mis_db')->table('tra_premises_applications as t6')
                    ->join('tra_premises as t1', 't6.premise_id', '=', 't1.id')
                    ->leftJoin('tra_approval_recommendations as t2', 't6.application_code', '=', 't2.application_code')
                    ->leftJoin('wb_trader_account as t3', 't6.applicant_id', '=', 't3.id')
                    ->leftJoin('registered_premises as t4', 't1.id', '=', 't4.tra_premise_id')
                    ->leftJoin('par_validity_statuses as t5', 't2.appvalidity_status_id', '=', 't5.id')
                    ->leftJoin('par_regions as t7', 't1.region_id', '=', 't7.id')
                    ->leftJoin('par_registration_statuses as t8', 't2.appregistration_status_id', '=', 't8.id')
                    ->select(DB::raw(" DISTINCT t4.tra_premise_id,t1.id as premise_id, t1.name as manufacturing_site_name,t1.name as trader_name, t1.*, t2.permit_no, t3.name as applicant_name,t4.id as registered_id,
                				t3.id as applicant_id, t3.name as applicant_name,'Ghana' as country, t3.contact_person, t3.tin_no,
                				t3.country_id as app_country_id, t3.region_id as app_region_id, t3.district_id as app_district_id,t7.name as region_name,
                				t3.physical_address, t3.postal_address,validity_status as validity_status_id,t8.name as registration_status,
                				t3.telephone_no as app_telephone, t3.fax as app_fax, t3.email, t3.website as app_website,if(t2.appvalidity_status_id >0, t5.name, 'Not Licensed') as validity_status, t2.appvalidity_status_id as validity_status_id, t1.id")); //change to status

                // $data = DB::connection('mis_db')->table('tra_premises_applications as t6')
                //     ->join('tra_premises as t1', 't6.premise_id', '=', 't1.id')
                //     ->leftJoin('tra_approval_recommendations as t2', 't6.application_code', '=', 't2.application_code')
                //     ->leftJoin('wb_trader_account as t3', 't6.applicant_id', '=', 't3.id')
                //     ->leftJoin('registered_premises as t4', 't1.id', '=', 't4.tra_premise_id')
                //     ->leftJoin('par_validity_statuses as t5', 't2.appvalidity_status_id', '=', 't5.id')
                //     ->leftJoin('par_regions as t7', 't1.region_id', '=', 't7.id')
                //     ->leftJoin('par_registration_statuses as t8', 't2.appregistration_status_id', '=', 't8.id')
                //     ->select(DB::raw("DISTINCT ON (t4.tra_premise_id) t4.tra_premise_id, t1.id as premise_id, t1.name as manufacturing_site_name, t1.name as trader_name, t1.*, t2.permit_no, t3.name as applicant_name, t4.id as registered_id,
                //         t3.id as applicant_id, t3.name as applicant_name, 'Rwanda' as country, t3.contact_person, t3.tin_no,
                //         t3.country_id as app_country_id, t3.region_id as app_region_id, t3.district_id as app_district_id, t7.name as region_name,
                //         t3.physical_address, t3.postal_address, t5.name as validity_status, t2.appvalidity_status_id as validity_status_id, t8.name as registration_status,
                //         t3.telephone_no as app_telephone, t3.fax as app_fax, t3.email, t3.website as app_website"))
                //     ->groupBy(
                //         't4.tra_premise_id',
                //         't1.id',
                //         't1.name',
                //         't6.id',
                //         't1.name',
                //         't1.*',
                //         't2.permit_no',
                //         't3.name',
                //         't4.id',
                //         't3.id',
                //         't3.name',
                //         't3.contact_person',
                //         't3.tin_no',
                //         't3.country_id',
                //         't3.region_id',
                //         't3.district_id',
                //         't7.name',
                //         't3.physical_address',
                //         't3.postal_address',
                //         't5.name',
                //         't2.appvalidity_status_id',
                //         't8.name',
                //         't3.telephone_no',
                //         't3.fax',
                //         't3.email',
                //         't3.website'
                //     )
                //     ->orderBy('t4.tra_premise_id', 'desc');



                if ($search_value != '') {
                    $whereClauses = array();
                    $whereClauses[] = "t2.permit_no like '%" . ($search_value) . "%'";
                    $whereClauses[] = "t1.premise_reg_no like '%" . ($search_value) . "%'";

                    $whereClauses[] = "t3.name  like '%" . ($search_value) . "%'";
                    $whereClauses[] = "t1.name  like '%" . ($search_value) . "%'";
                    $filter_string = implode(' OR ', $whereClauses);
                    $data->whereRAW($filter_string);
                }
                $totalCount = $data->count();
                $data->orderBy('t6.id', 'desc')->groupBy('t4.tra_premise_id');
                if (validateIsNumeric($take)) {
                    //$data = $data->skip($skip)->take($take)->get();
                } else {
                }
                $data = $data->get();
                //get the data from the rdb registered lists 
                $rdb_data = DB::connection('mis_db')->table('tra_rdbbusiness_applications as t6')
                    ->join('tra_premises as t1', 't6.premise_id', '=', 't1.id')
                    ->leftJoin('wb_trader_account as t3', 't6.applicant_id', '=', 't3.id')
                    ->leftJoin('par_regions as t7', 't1.region_id', '=', 't7.id')
                    ->select(DB::raw(" DISTINCT t1.id as premise_id, t1.name as manufacturing_site_name,t1.name as trader_name, t1.*, t1.company_registration_no as permit_no, t3.name as applicant_name,
                				t3.id as applicant_id, t3.name as applicant_name,'Ghana' as country, t3.contact_person, t3.tin_no,
                				t3.country_id as app_country_id, t3.region_id as app_region_id, t3.district_id as app_district_id,t7.name as region_name,
                				t3.physical_address, t3.postal_address,
                				t3.telephone_no as app_telephone, t3.fax as app_fax, t3.email, t3.website as app_website,'Registered RDB Premises' as validity_status,t1.id")); //change to status

                //     $rdb_data = DB::connection('mis_db')->table('tra_rdbbusiness_applications as t6')
                //         ->join('tra_premises as t1', 't6.premise_id', '=', 't1.id')
                //         ->leftJoin('wb_trader_account as t3', 't6.applicant_id', '=', 't3.id')
                //         ->leftJoin('par_regions as t7', 't1.region_id', '=', 't7.id')
                //         ->select(DB::raw("DISTINCT ON (t1.id) t1.id as premise_id, t1.name as manufacturing_site_name, t1.name as trader_name, t1.*, t1.company_registration_no as permit_no, t3.name as applicant_name,
                // t3.id as applicant_id, t3.name as applicant_name, 'Rwanda' as country, t3.contact_person, t3.tin_no,
                // t3.country_id as app_country_id, t3.region_id as app_region_id, t3.district_id as app_district_id, t7.name as region_name,
                // t3.physical_address, t3.postal_address,
                // t3.telephone_no as app_telephone, t3.fax as app_fax, t3.email, t3.website as app_website, 'Registered RDB Premises' as validity_status"))
                //         ->groupBy(
                //             't1.id',
                //             't1.name',
                //             't1.name',
                //             't1.*',
                //             't3.name',
                //             't3.id',
                //             't3.name',
                //             't3.contact_person',
                //             't3.tin_no',
                //             't3.country_id',
                //             't3.region_id',
                //             't3.district_id',
                //             't7.name',
                //             't3.physical_address',
                //             't3.postal_address',
                //             't3.telephone_no',
                //             't3.fax',
                //             't3.email',
                //             't3.website'
                //         )
                //         ->orderBy('t1.id', 'desc');


                if ($search_value != '') {
                    $whereClauses = array();
                    $whereClauses[] = "t3.name  like '%" . ($search_value) . "%'";
                    $whereClauses[] = "t1.name  like '%" . ($search_value) . "%'";
                    $filter_string = implode(' OR ', $whereClauses);
                    $rdb_data->whereRAW($filter_string);
                }
                $totalCount = $rdb_data->count();
                $rdb_data->orderBy('t1.id', 'desc');
                if (validateIsNumeric($take)) {
                    //$data = $data->skip($skip)->take($take)->get();
                } else {
                }
                $rdb_data = $rdb_data->get();

                $data =  $data->merge($rdb_data);
            } else {
                $qry = DB::table('wb_trader_account as t1')
                    ->select('t1.*');
                if ($is_local_agent == 1) {
                    //  $qry =  $qry->where(array('country_id'=>36));
                }

                if ($search_value != '') {
                    $whereClauses = array();
                    $whereClauses[] = "t1.identification_no like '%" . ($search_value) . "%'";
                    $whereClauses[] = "t1.email  like '%" . ($search_value) . "%'";
                    $whereClauses[] = "t1.physical_address  like '%" . ($search_value) . "%'";
                    $whereClauses[] = "t1.name  like '%" . ($search_value) . "%'";

                    $filter_string = implode(' OR ', $whereClauses);
                    $qry->whereRAW($filter_string);
                }
                if (validateIsNumeric($skip)) {
                    $records = $qry->skip($skip)->take($take)->get();
                } else {
                    $records = $qry->get();
                }

                $totalCount = $qry->count();

                foreach ($records as $rec) {
                    $data[] = array(
                        'id' => $rec->id,
                        'trader_name' => $rec->name,
                        //  'country'=> returnParamFromArray($countriesData,$rec->country_id),
                        //'region'=> returnParamFromArray($regionsData,$rec->region_id),
                        'district' => '', // returnParamFromArray($districtsData,$rec->district_id),
                        'physical_address' => $rec->physical_address,
                        'postal_address' => $rec->postal_address,
                        'email_address' => $rec->email,
                        'trader_no' => $rec->identification_no,

                    );
                }
            }



            $res = array(
                'success' => true,
                'data' => $data,
                'totalCount' => $totalCount
            );
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
    public function onNewProductsApplicationSubmit(Request $req)
    {
        try {
            $tracking_no = $req->tracking_no;
            $product_id = $req->product_id;
            $status_id = $req->status_id;
            $trader_id = $req->trader_id;
            $remarks = $req->remarks;
            $traderemail_address = $req->traderemail_address;
            $data = array();
            //get the records 
            $table_name = 'wb_product_applications';
            $resp = false;
            $where_state = array('product_id' => $product_id, 'tracking_no' => $tracking_no);
            $records = DB::table($table_name)
                ->where($where_state)
                ->first();
            if ($records) {
                //delete functionality
                $previous_status_id = $records->application_status_id;
                $current_status_id = 2;
                $premise_data = array(
                    'application_status_id' => $current_status_id,
                    'altered_by' => $traderemail_address,
                    'dola' => Carbon::now(),
                    'submission_date' => Carbon::now()
                );
                $submission_data = array(
                    'tracking_no' => $tracking_no,
                    'application_code' => $records->application_code,
                    'trader_id' => $trader_id,
                    'remarks' => $remarks,
                    'previous_status_id' => $previous_status_id,
                    'current_status_id' => $current_status_id,
                    'submission_date' => Carbon::now(),
                    'created_by' => $traderemail_address,
                    'created_on' => Carbon::now(),
                );

                $previous_data = getPreviousRecords($table_name, $where_state);
                $resp = updateRecord($table_name, $previous_data, $where_state, $premise_data, $traderemail_address, 'mysql');

                $resp = insertRecord('wb_application_submissions', $submission_data, $traderemail_address, 'mysql');
            }
            if ($resp) {
                $res = array('success' => true, 'message' => 'Premises Application has been submitted Successfully for processing.');
            } else {
                $res = array('success' => false, 'message' => ' Application Submission failed, contact the system admin if this persists');
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

    public function getProductsCounterDetails(Request $req)
    {
        //the statuses
        try {
            $trader_id = $req->trader_id;

            $data = array();
            //get the records 
            $resp = false;
            $table_name = 'wb_product_applications as t1';
            $records = DB::table($table_name)
                ->select(DB::raw("count(application_status_id) as application_counter,t2.name as status_name, t2.id as status_id"))
                ->join('wb_statuses as t2', 't1.application_status_id', '=', 't2.id')

                ->where(function ($query) use ($trader_id) {
                    $query->where(array('trader_id' => $trader_id))
                        ->orWhere(array('application_initiator_id' => $trader_id));
                })
                ->groupBy('t2.id')
                ->get();
            if (count($records) > 0) {
                //delete functionality
                $res = array('success' => true, 'data' => $records);
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
    public function getGmpInspectionsdetails(Request $req)
    {
        try {
            //getTraderInformationDetails
            $take = $req->take;
            $skip = $req->skip;
            $searchValue = $req->searchValue;
            $product_id = $req->product_id;
            $application_code = $req->application_code;
            $man_sites = getRecordValFromWhere('wb_product_manufacturers', array('product_id' => $product_id), 'man_site_id');

            $search_value =  '';
            if ($req->searchValue != 'undefined') {
                $searchValue = explode(',', $searchValue);
                $search_value =  $searchValue[2];
            }
            //getManufacturingSiteInformation
            $qry = DB::connection('mis_db')->table('tra_manufacturing_sites as t1')
                ->select('t5.id as reg_site_id', 't1.id', 't1.id as manufacturing_site_id', 't7.permit_no as gmp_certificate_no', 't6.reference_no as gmp_application_reference', 't8.name as registration_status', 't7.permit_no', 't5.tra_site_id', 't1.physical_address', 't1.email as email_address', 't1.id as manufacturer_id', 't1.name as manufacturer_name', 't2.name as country_name', 't3.name as region_name', 't4.name as district')
                ->join('par_countries as t2', 't1.country_id', '=', 't2.id')
                ->join('par_regions as t3', 't1.region_id', '=', 't3.id')
                ->leftJoin('par_districts as t4', 't1.district_id', '=', 't4.id')
                ->join('registered_manufacturing_sites as t5', 't1.id', '=', 't5.tra_site_id')
                ->join('tra_gmp_applications as t6', 't1.id', '=', 't6.manufacturing_site_id')
                ->join('tra_approval_recommendations as t7', 't1.permit_id', '=', 't7.id')
                ->leftJoin('par_system_statuses as t8', 't6.application_status_id', '=', 't8.id')
                ->whereIn('t1.man_site_id', $man_sites);
            if ($search_value != '') {
                $whereClauses = array();
                $whereClauses[] = "t7.permit_no like '%" . ($search_value) . "%'";
                $whereClauses[] = "t6.reference_no like '%" . ($search_value) . "%'";
                $whereClauses[] = "t1.name  like '%" . ($search_value) . "%'";
                $whereClauses[] = "t1.physical_address  like '%" . ($search_value) . "%'";
                $whereClauses[] = "t2.name  like '%" . ($search_value) . "%'";
                $filter_string = implode(' OR ', $whereClauses);
                $qry->whereRAW($filter_string);
            }

            $records = $qry->skip($skip)->take($take)->get();

            $totalCount = $qry->count();
            $res = array(
                'success' => true,
                'data' => $records,
                'totalCount' => $totalCount
            );
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
    public function onSearchRegisteredProductApplication(Request $req)
    {
        try {
            $mistrader_id = $req->mistrader_id;
            $section_id = $req->section_id;
            $validity_status = $req->validity_status;
            $registration_status = $req->registration_status;

            $take = $req->take;
            $skip = $req->skip;
            $searchValue = $req->searchValue;
            $search_value =  '';
            if ($req->searchValue != 'undefined' && $req->searchValue != '') {
                $searchValue = explode(',', $searchValue);
                $search_value =  $searchValue[2];
            }
            $portalDb = \DB::getDatabaseName();
            $qry = DB::connection('mis_db')->table('tra_product_applications as t1')
                ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                ->join('tra_product_information as t7', 't1.product_id', '=', 't7.id')
                ->leftJoin('par_common_names as t8', 't7.common_name_id', '=', 't8.id')
                ->leftJoin('tra_premises as t9', 't1.local_agent_id', '=', 't9.id')
                ->leftJoin('par_classifications as t10', 't7.classification_id', '=', 't10.id')
                ->join('tra_approval_recommendations as t11', 't1.application_code', '=', 't11.application_code')
                ->leftJoin('tra_registered_products as t12', 't12.tra_product_id', '=', 't7.id')

                ->leftJoin('par_storage_conditions as t13', 't7.storage_condition_id', '=', 't13.id')
                ->leftJoin('par_validity_statuses as t4', 't11.appvalidity_status_id', '=', 't4.id')
                ->leftJoin('par_registration_statuses as t15', 't11.appregistration_status_id', '=', 't15.id')
                ->join('par_sections as t16', 't1.section_id', '=', 't16.id')
                ->leftJoin('tra_product_manufacturers as t14', function ($join) {
                    $join->on('t7.id', '=', 't14.product_id')
                        ->on('t14.manufacturer_role_id', '=', DB::raw(1))
                        ->on('t14.manufacturer_type_id', '=', DB::raw(1));
                })
                ->select(
                    't7.*',
                    't1.*',
                    't16.name as section_name',
                    't4.name as validity_status',
                    't15.name as registration_status',
                    't1.id as active_application_id',
                    't1.reg_product_id',
                    't3.name as applicant_name',
                    't9.name as local_agent',
                    't12.id as registered_product_id',
                    't13.name as storage_condition',
                    't7.brand_name',
                    't8.name as common_name',
                    't10.name as classification_name',
                    't11.certificate_no',
                    't7.brand_name as sample_name',
                    't14.manufacturer_id',
                    DB::raw("if(t12.tra_product_id >0,t12.tra_product_id,t1.product_id) as  tra_product_id")
                )
                ->where(array('t11.decision_id' => 1))
                ->groupBy('t7.id')->orderBy('t1.id', 'desc'); //, 't7.section_id'=>$section_id'','t1.product_id as tra_product_id',

            if (isset($section_id) && is_numeric($section_id)) {
                $qry->where('t1.section_id', $section_id);
            }
            if (validateIsNumeric($validity_status)) {
                $qry->where('t12.validity_status_id', $validity_status);
            }
            if (validateIsNumeric($registration_status)) {
                $qry->where('t12.registration_status_id', $registration_status);
            }
            if (validateIsNumeric($mistrader_id)) {
                //    $qry->where('t1.applicant_id', $mistrader_id);
            }


            if ($search_value != '') {
                $whereClauses = array();
                $whereClauses[] = "t8.name like '%" . ($search_value) . "%'";

                $whereClauses[] = "t7.brand_name  like '%" . ($search_value) . "%'";
                $whereClauses[] = "t8.name  like '%" . ($search_value) . "%'";
                $whereClauses[] = "t11.certificate_no  like '%" . ($search_value) . "%'";
                $filter_string = implode(' OR ', $whereClauses);
                $qry->whereRAW($filter_string);
            }

            $totalCount = $qry->count();
            if (validateIsNumeric($take)) {
                $records = $qry->skip($skip)->take($take)->get();
            } else {

                $records = $qry->get();
            }

            $res = array(
                'success' => true,
                'data' => $records, 'totalCount' => $totalCount
            );
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
    public function getSampleSubmissionDetails(Request $req)
    {
        $application_code = $req->application_code;
        if (!is_numeric($application_code)) {
            return $res = array(
                'success' => true,
                'data' => []
            );
        }
        try {


            $product_id = getSingleRecordColValue('wb_product_applications', array('application_code' => $req->application_code), 'product_id');
            $mis_db = DB::connection('mis_db')->getDatabaseName();
            // $mis_db = "mis_db";
            $records = DB::table('wb_sample_information as t1')
                ->select('t1.*', 't2.name as quantity_unit', 't3.name as pack_unit', 't4.name as sample_status', 't5.name as sample_storage')
                ->leftjoin($mis_db . '.par_packaging_units as t2', 't1.quantity_unit_id', '=', 't2.id')
                ->leftjoin($mis_db . '.par_packaging_units as t3', 't1.pack_unit_id', '=', 't3.id')
                ->leftjoin($mis_db . '.par_sample_status as t4', 't1.sample_status_id', '=', 't4.id')
                ->leftjoin($mis_db . '.par_storage_conditions as t5', 't1.storage_id', '=', 't5.id')
                ->leftjoin('wb_product_information as t6', 't1.product_id', '=', 't6.id')
                ->where(array('product_id' => $product_id))
                ->get();
            $res = array(
                'success' => true,
                'data' => $records
            );
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
    function validateProductData($table_name, $product_id, $title)
    {
        $sql = DB::table($table_name)->where(array('product_id' => $product_id))->get();

        if (count($sql) == 0) {
            $res = array('success' => true, 'message' => $title);
            echo json_encode($res);
            exit();
        }
    }
    public function onValidateProductOtherdetails(Request $req)
    {
        try {

            $section_id = $req->section_id;

            $product_id = $req->product_id;

            if ($section_id == 1) {
                $this->validateProductData('wb_product_ingredients', $product_id, 'Add Product Ingredients Details to proceed');
                $this->validateProductData('wb_product_packaging', $product_id, 'Add Product Packaging Details to proceed');
                $this->validateProductData('wb_product_manufacturers', $product_id, 'Add Product Packaging Details to proceed');
                $this->validateProductData('wb_product_nutrients', $product_id, 'Add Product Nutrients Details to proceed');
            } else if ($section_id == 2) {
                $this->validateProductData('wb_product_ingredients', $product_id, 'Add Product Ingredients Details to proceed');
                $this->validateProductData('wb_product_packaging', $product_id, 'Add Product Packaging Details to proceed');
                $this->validateProductData('wb_product_manufacturers', $product_id, 'Add Product Packaging Details to proceed');
            } else if ($section_id == 3) {
                $this->validateProductData('wb_product_ingredients', $product_id, 'Add Product Ingredients Details to proceed');
                $this->validateProductData('wb_product_packaging', $product_id, 'Add Product Packaging Details to proceed');
                $this->validateProductData('wb_product_manufacturers', $product_id, 'Add Product Packaging Details to proceed');
            } else {
                //medical devices 
                $this->validateProductData('wb_product_manufacturers', $product_id, 'Add Product Packaging Details to proceed');
            }
            $res = array(
                'success' => true,
                'message' => 'Data entry validated'
            );
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
    public function onSaveGroupedApplicationdetails(Request $req)
    {
        try {
            DB::beginTransaction();
            $product_id = $req->product_id;
            $trader_initiator_id = $req->trader_id;
            $applicant_id = $req->trader_id;
            $trader_id = $req->trader_id;
            $email_address = $req->email_address;

            $local_agent_id = $req->local_agent_id;
            $section_id = $req->section_id;
            $reference_no = $req->reference_no;
            $sub_module_id = $req->sub_module_id;
            $group_application_code = $req->group_application_code;

            $product_res =  '';
            $module_id = getSingleRecordColValue('sub_modules', array('id' => $req->sub_module_id), 'module_id', 'mis_db');

            $app_data = array(
                'local_agent_id' => $local_agent_id,
                'sub_module_id' => $req->sub_module_id,
                'section_id' => $req->section_id,
                'appsubmissions_type_id' => $req->appsubmissions_type_id,
                'product_origin_id' => $req->product_origin_id,
                'prodclass_category_id' => $req->prodclass_category_id,
                'application_status_id' => 1,
                'module_id' => $module_id,
                'reason_for_groupedsubmission' => $req->reason_for_groupedsubmission
            );
            $table_name = 'wb_appsubmissions_typedetails';
            /** Already Saved */

            if (validateIsNumeric($group_application_code)) {
                //update the record 
                //product information
                //
                $where = array('group_application_code' => $group_application_code);

                if (recordExists('wb_appsubmissions_typedetails', $where)) {

                    $previous_data = getPreviousRecords('wb_appsubmissions_typedetails', $where);
                    $group_tracking_no = $previous_data['results'][0]['group_tracking_no'];
                    $group_application_code = $previous_data['results'][0]['group_application_code'];

                    $resp =   updateRecord('wb_appsubmissions_typedetails', $previous_data, $where, $app_data, $email_address);
                }

                if ($resp['success']) {

                    $res = array(
                        'group_tracking_no' => $group_tracking_no,
                        'success' => true,
                        'group_application_code' => $group_application_code,
                        'message' => 'Grouped Application Saved Successfully, with Tracking No: ' . $group_tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false,
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            } else {

                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => 90, 'module_id' => 27, 'reference_type_id' => 5), 'reference_format_id', 'mis_db');

                $section_code = getSingleRecordColValue('par_sections', array('id' => $req->section_id), 'code', 'mis_db');
                $class_code = getSingleRecordColValue('par_classifications', array('id' => $req->classification_id), 'code', 'mis_db');

                $process_id = getSingleRecordColValue('wf_tfdaprocesses', array('module_id' => $module_id, 'section_id' => $section_id, 'sub_module_id' => $sub_module_id), 'id', 'mis_db');


                $codes_array = array(
                    'section_code' => $section_code
                );

                $group_tracking_no = generateApplicationRefNumber($ref_id, $codes_array, date('Y'), $process_id, 0, $trader_id);
                if (!validateIsNumeric($ref_id)) {
                    return \response()->json(array('success' => false, 'message' => 'Reference No Format has not been set, contact the system administrator'));
                } else if ($group_tracking_no == '') {
                    return \response()->json(array('success' => false, 'group_tracking_no' => $group_tracking_no, 'message' => $group_tracking_no));
                }

                $group_application_code = rand(0, 100) . generateApplicationCode($sub_module_id, 'wb_appsubmissions_typedetails');

                $app_data['created_by'] = $email_address;
                $app_data['created_on'] = Carbon::now();
                $app_data['group_tracking_no'] = $group_tracking_no;

                $app_data['date_added'] = Carbon::now();
                $app_data['group_application_code'] = $group_application_code;
                $app_data['application_code'] = $group_application_code;
                $app_data['trader_id'] = $trader_id;
                $app_data['application_status_id'] = 1;

                $resp = insertRecord('wb_appsubmissions_typedetails', $app_data, $email_address);

                if ($resp['success']) {

                    $res = array(
                        'group_tracking_no' => $group_tracking_no,
                        'success' => true,
                        'group_application_code' => $group_application_code,
                        'message' => 'Grouped Application Saved Successfully, with Tracking No: ' . $group_tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false, 'message1' => $resp['message'],
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            }
            //on save routes 

            if ($res['success']) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            $res = array(
                'success' => false, 'message1' => $product_res,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = array(
                'success' => false, 'message1' => $product_res,
                'message' => $throwable->getMessage()
            );
        }

        return response()->json($res);
    }
    public function getGroupedProductApplicationInformation(Request $req)
    {
        try {
            $application_code = $req->application_code;
            $data = array();
            //get the records 
            $mis_db = "mis_db"; //DB::connection('mis_db')->getDatabaseName();
            $records = DB::table('wb_appsubmissions_typedetails as t1')
                ->select(DB::raw("t1.*,t1.application_status_id as status_id, t3.name as status_name, t4.router_link,t1.trader_id as applicant_id, t4.name as process_title"))
                ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                ->leftJoin('wb_tfdaprocesses as t4', function ($join) {
                    $join->on('t1.sub_module_id', '=', 't4.sub_module_id');
                    $join->on('t1.application_status_id', '=', 't4.status_id');

                    //$join->on('t2.prodclass_category_id', '=', 't4.prodclass_category_id');
                })
                ->leftJoin($mis_db . '.tra_premises as t5', 't1.local_agent_id', '=', 't5.id')
                ->where(array('t1.group_application_code' => $application_code, 't4.appsubmissions_type_id' => 2))
                ->first();

            $records->{"form_fields"} = getApplicationGeneralFormsFields($records);

            $res = array('success' => true, 'data' => $records);
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
    public function getGroupedProductApplicationsSub(Request $req)
    {
        try {
            $trader_id = $req->trader_id;
            $data = array();
            $group_application_code = $req->group_application_code;
            $mis_db = "mis_db";

            if (validateIsNumeric($group_application_code)) {
                //get the records 

                $records = DB::table('wb_product_applications as t1')
                    ->select(DB::raw('t7.name as action_name,t7.iconcls,t7.action,t1.local_agent_id,  t1.tracking_no,t1.reference_no,t1.application_code, t1.section_id,t5.name as local_agent,t5.name as local_agent_name,t1.id as application_id, t4.name as applicant_name, t2.*,t3.name as status_name,t1.application_status_id as status_id, t1.date_added, t1.submission_date,t1.product_id, t1.sub_module_id, t1.trader_id,t1.trader_id as applicant_id,  t1.reference_no, t1.application_status_id'))
                    ->leftJoin('wb_product_information as t2', 't1.product_id', '=', 't2.id')
                    ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                    ->leftJoin('wb_trader_account as t4', 't1.trader_id', '=', 't4.id')
                    ->leftJoin($mis_db . '.tra_premises as t5', 't1.local_agent_id', '=', 't5.id')
                    ->leftJoin('wb_processstatus_actions as t6', function ($join) {
                        $join->on('t1.application_status_id', '=', 't6.status_id')
                            ->on('t6.is_default_action', '=', DB::raw(1));
                    })
                    ->leftJoin('wb_statuses_actions as t7', 't6.action_id', 't7.id')
                    ->where('t1.group_application_code', $group_application_code);

                $records = $records->orderby('t1.date_added', 'desc')->get();

                $actionColumnData = returnContextMenuActions();
                $sectionsData = getParameterItems('par_sections', '', 'mis_db');
                $classData = getParameterItems('par_classifications', '', 'mis_db');
                $subModuleData = getParameterItems('sub_modules', '', 'mis_db');

                foreach ($records as $rec) {
                    //get the array 
                    $data[] = array(
                        'reference_no' => $rec->reference_no,
                        'id' => $rec->id,
                        'tracking_no' => $rec->tracking_no,
                        'application_id' => $rec->application_id,
                        'product_id' => $rec->product_id,
                        'section_id' => $rec->section_id,
                        'brand_name' => $rec->brand_name,
                        'date_added' => $rec->date_added,
                        'sub_module_id' => $rec->sub_module_id,
                        'applicant_name' => $rec->applicant_name,
                        'local_agent' => $rec->local_agent,
                        'local_agent_name' => $rec->local_agent_name,
                        'application_status_id' => $rec->application_status_id,
                        'created_by' => $rec->created_by,
                        'submission_date' => $rec->submission_date,
                        'section' => returnParamFromArray($sectionsData, $rec->section_id),
                        'classification' => returnParamFromArray($classData, $rec->classification_id),
                        'application_type' => returnParamFromArray($subModuleData, $rec->sub_module_id),
                        'status' => $rec->status_name,
                        'status_name' => $rec->status_name,
                        'action_name' => $rec->action_name,
                        'action' => $rec->action,
                        'iconcls' => $rec->iconcls,
                        'application_code' => $rec->application_code,
                        'contextMenu' => returnActionColumn($rec->application_status_id, $actionColumnData)

                    );
                }
            }



            $res = array(
                'success' => true,
                'data' => $data
            );
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


    public function onSaveRenetionRequestApplication(Request $req)
    {
        try {
            $product_res =  '';
            $trader_id = $req->trader_id;
            $email_address = $req->email_address;
            $retention_yearfrom = formatDate($req->retention_yearfrom);
            $retention_yearto = formatDate($req->retention_yearto);
            $remarks = $req->remarks;
            $reference_no = $req->reference_no;
            $sub_module_id = $req->sub_module_id;
            $application_code = $req->application_code;
            $section_id = 7;
            $module_id = getSingleRecordColValue('sub_modules', array('id' => $req->sub_module_id), 'module_id', 'mis_db');
            $app_data = array(
                'trader_id' => $trader_id,

                'sub_module_id' => $req->sub_module_id,
                'reference_no' => $reference_no,
                'module_id' => $module_id,
                'application_status_id' => 1
            );
            $retentionapp_data = array(
                'retention_yearfrom' => $retention_yearfrom, 'retention_yearto' => $retention_yearto,
                'requested_on' => Carbon::now(),
                'requested_by' => $email_address,
                'remarks' => $remarks
            );
            $table_name = 'wb_product_applications';
            if (validateIsNumeric($application_code)) {
                $where_app = array('application_code' => $application_code);

                if (recordExists($table_name, $where_app)) {
                    //update the retention generateInvoiceNo
                    $previous_data = getPreviousRecords('tra_retentiongeration_requests', $where_app, 'mis_db');
                    $retentiongeration_request_id = $previous_data['results'][0]['id'];
                    $resp =   updateRecord('tra_retentiongeration_requests', $previous_data, $where_app, $retentionapp_data, $email_address, 'mis_db');

                    $previous_data = getPreviousRecords('wb_product_applications', $where_app);
                    $tracking_no = $previous_data['results'][0]['tracking_no'];
                    $application_code = $previous_data['results'][0]['application_code'];
                    $application_id = $previous_data['results'][0]['id'];

                    $resp =   updateRecord('wb_product_applications', $previous_data, $where_app, $app_data, $email_address);
                }
                if ($resp['success']) {
                    $sql = DB::connection('mis_db')->table('tra_application_documentsdefination')->where(array('application_code' => $application_code))->first();
                    if (!$sql) {

                        // initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no.rand(0,100), $trader_id);

                    }
                    $res = array(
                        'tracking_no' => $tracking_no,
                        'success' => true,

                        'application_code' => $application_code,
                        'application_id' => $application_id,
                        'retentiongeration_request_id' => $retentiongeration_request_id,
                        'message' => 'Product Retention Application Request Saved Successfully, with Tracking No: ' . $tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false,
                        'message1' => $resp['message'],
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            } else {
                $ref_id = getSingleRecordColValue('tra_submodule_referenceformats', array('sub_module_id' => $sub_module_id, 'module_id' => $module_id, 'reference_type_id' => 1), 'reference_format_id', 'mis_db');

                $process_id = getSingleRecordColValue('wf_tfdaprocesses', array('module_id' => $module_id, 'sub_module_id' => $sub_module_id), 'id', 'mis_db');

                $codes_array = array(
                    'section_code' => '',

                );

                $tracking_no = generateApplicationRefNumber($ref_id, $codes_array, date('Y'), $process_id, 0, $trader_id);


                if (!validateIsNumeric($ref_id)) {
                    return \response()->json(array('success' => false, 'message' => 'Reference No Format has not been set, contact the system administrator'));
                } else if ($tracking_no == '') {
                    return \response()->json(array('success' => false, 'tracking_no' => $tracking_no, 'message' => $tracking_no));
                }

                $application_code = generateApplicationCode($sub_module_id, 'wb_product_applications');
                $app_data['created_by'] = $email_address;
                $app_data['created_on'] = Carbon::now();
                $app_data['tracking_no'] = $tracking_no;
                $app_data['process_id'] = $process_id;

                $app_data['date_added'] = Carbon::now();
                $app_data['application_code'] = $application_code;
                $app_data['application_initiator_id'] = $trader_id;
                $app_data['application_status_id'] = 1;

                $resp = insertRecord('wb_product_applications', $app_data, $email_address);
                $retentionapp_data['created_by'] = $email_address;
                $retentionapp_data['created_on'] = Carbon::now();
                $retentionapp_data['application_code'] = $application_code;
                $resp_retintion = insertRecord('tra_retentiongeration_requests', $retentionapp_data, $email_address, 'mis_db');

                if ($resp['success']) {
                    $application_id = $resp['record_id'];
                    $retentiongeration_request_id = $resp_retintion['record_id'];

                    //initializeApplicationDMS($section_id, $module_id, $sub_module_id, $application_code, $tracking_no.rand(0,100), $trader_id);
                    saveApplicationSubmissionDetails($application_code, 'wb_product_applications');

                    $res = array(
                        'tracking_no' => $tracking_no,
                        'success' => true,
                        'application_code' => $application_code,
                        'application_id' => $application_id,
                        'retentiongeration_request_id' => $retentiongeration_request_id,
                        'message' => 'Product Retention Application Request Saved Successfully, with Tracking No: ' . $tracking_no
                    );
                } else {
                    $res = array(
                        'success' => false, 'message1' => $resp['message'],
                        'message' => 'Error Occurred Product Application not saved, it this persists contact the system Administrator'
                    );
                }
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        }

        return response()->json($res, 200);
    }

    public function getProductProductretentionRequests(Request $req)
    {
        try {
            $trader_id = $req->trader_id;
            $application_status_id = $req->application_status_id;
            if ($application_status_id != '') {

                $application_status_id = explode(',', $req->application_status_id);
            }
            $module_id = $req->module_id;
            $sub_module_id = $req->sub_module_id;
            $section_id = $req->section_id;
            $application_status = $req->application_status;

            $data = array();
            //get the records 
            $records = DB::table('wb_product_applications as t1')
                ->select(DB::raw('t7.name as action_name,t7.iconcls,t7.action,  t1.tracking_no,t1.reference_no,t1.application_code, t1.section_id,t1.id as application_id, t4.name as applicant_name, t3.name as status_name,t1.application_status_id as status_id, t1.date_added, t1.submission_date,t1.sub_module_id,t1.module_id, t1.trader_id,t1.trader_id as applicant_id,  t1.application_status_id'))
                ->leftJoin('wb_statuses as t3', 't1.application_status_id', '=', 't3.id')
                ->leftJoin('wb_trader_account as t4', 't1.trader_id', '=', 't4.id')
                ->leftJoin('wb_processstatus_actions as t6', function ($join) {
                    $join->on('t1.application_status_id', '=', 't6.status_id')
                        ->on('t6.is_default_action', '=', DB::raw(1));
                })
                ->leftJoin('wb_statuses_actions as t7', 't6.action_id', 't7.id');

            if ($trader_id != 25) {

                $records->where(function ($q) use ($trader_id) {
                    $q->where('trader_id', $trader_id)
                        ->orWhere('application_initiator_id', $trader_id);
                });
            }
            if (is_array($application_status_id) && count($application_status_id) > 0) {

                $records =  $records->whereIn('application_status_id', $application_status_id);
            }
            if (validateIsNumeric($sub_module_id)) {
                $records =  $records->where(array('t1.sub_module_id' => $sub_module_id));
            }
            $records =  $records->whereIn('sub_module_id', [67]);
            if ($application_status != '') {
                $records =  $records->whereIn('t1.application_status_id', explode(',', $application_status));
            }
            $records = $records->orderby('t1.date_added', 'desc')->get();

            $actionColumnData = returnContextMenuActions();
            $subModuleData = getParameterItems('sub_modules', '', 'mis_db');

            foreach ($records as $rec) {
                //get the array 
                //get the retention data  re
                $retention_data = getSingleRecord('tra_retentiongeration_requests', array('application_code' => $rec->application_code), 'mis_db');
                $data[] = array(
                    'reference_no' => $rec->reference_no,
                    'tracking_no' => $rec->tracking_no,
                    'application_id' => $rec->application_id,
                    'id' => $rec->application_id,
                    'date_added' => $rec->date_added,
                    'sub_module_id' => $rec->sub_module_id,
                    'module_id' => $rec->module_id,
                    'applicant_name' => $rec->applicant_name,
                    'application_status_id' => $rec->application_status_id,
                    'created_by' => $rec->applicant_name,
                    'submission_date' => $rec->submission_date,
                    'application_type' => returnParamFromArray($subModuleData, $rec->sub_module_id),
                    'status' => $rec->status_name,
                    'action_name' => $rec->action_name,
                    'action' => $rec->action,
                    'iconcls' => $rec->iconcls,
                    'retention_yearto' => formatDate($retention_data->retention_yearto),
                    'retention_yearfrom' => formatDate($retention_data->retention_yearfrom),
                    'retentiongeration_request_id' => $retention_data->id,
                    'requested_on' => $retention_data->requested_on,
                    'remarks' => $retention_data->remarks,
                    'application_code' => $rec->application_code,
                    'contextMenu' => returnActionColumn($rec->application_status_id, $actionColumnData)
                );
            }
            $res = array(
                'success' => true,
                'data' => $data
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        }
        return response()->json($res, 200);
    }

    public function getAnnualretentionRegisteredProducts(Request $req)
    {
        try {
            $mistrader_id = $req->mistrader_id;
            $section_id = $req->section_id;
            $validity_status = $req->validity_status;
            $registration_status = $req->registration_status;

            $take = $req->take;
            $skip = $req->skip;
            $searchValue = $req->searchValue;
            $search_value =  '';
            if ($req->searchValue != 'undefined' && $req->searchValue != '') {
                $searchValue = explode(',', $searchValue);
                $search_value =  $searchValue[2];
            }

            $qry = DB::connection('mis_db')->table('tra_product_applications as t1')
                ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                ->join('tra_product_information as t7', 't1.product_id', '=', 't7.id')
                ->leftJoin('par_common_names as t8', 't7.common_name_id', '=', 't8.id')
                ->leftJoin('wb_trader_account as t9', 't1.local_agent_id', '=', 't9.id')
                ->leftJoin('par_classifications as t10', 't7.classification_id', '=', 't10.id')
                ->leftJoin('tra_approval_recommendations as t11', 't1.application_code', '=', 't11.application_code')
                ->join('tra_registered_products as t12', 't12.tra_product_id', '=', 't7.id')
                ->leftJoin('par_validity_statuses as t4', 't12.validity_status_id', '=', 't4.id')
                ->leftJoin('par_registration_statuses as t15', 't12.registration_status_id', '=', 't15.id')
                ->join('par_sections as t16', 't1.section_id', '=', 't16.id')
                ->leftJoin('tra_product_manufacturers as t14', function ($join) {
                    $join->on('t7.id', '=', 't14.product_id')
                        ->on('t14.manufacturer_role_id', '=', DB::raw(1))
                        ->on('t14.manufacturer_type_id', '=', DB::raw(1));
                })

                ->select(DB::raw("DISTINCT t12.registration_no,t7.*,t1.*, t16.name as section_name, t4.name as validity_status,t15.name as registration_status, t1.id as active_application_id, t1.reg_product_id, t3.name as applicant_name, t9.name as local_agent, t12.id as registered_product_id,t1.product_id as tra_product_id,
           t7.brand_name, t12.tra_product_id, t8.name as common_name, t10.name as classification_name, t12.registration_no as certificate_no, max(t11.expiry_date) as expiry_date,
           t7.brand_name as sample_name,t14.manufacturer_id"))
                ->groupBy('t12.registration_no')->orderBy('t12.expiry_date', 'desc'); //, 't7.section_id'=>$section_id

            if (isset($section_id) && is_numeric($section_id)) {
                $qry->where('t1.section_id', $section_id);
            }
            if (validateIsNumeric($validity_status)) {
                $qry->where('t12.validity_status_id', $validity_status);
            }
            if (validateIsNumeric($registration_status)) {
                $qry->where('t12.registration_status_id', $registration_status);
            }
            if (validateIsNumeric($mistrader_id)) {
                //  $qry->where('t1.applicant_id', $mistrader_id);
            }

            if ($search_value != '') {
                $whereClauses = array();
                $whereClauses[] = "t8.name like '%" . ($search_value) . "%'";

                $whereClauses[] = "t7.brand_name  like '%" . ($search_value) . "%'";
                $whereClauses[] = "t11.certificate_no  like '%" . ($search_value) . "%'";
                $filter_string = implode(' OR ', $whereClauses);
                $qry->whereRAW($filter_string);
            }

            $totalCount = $qry->count();
            if (validateIsNumeric($take)) {
                $records = $qry->skip($skip)->take($take)->get();
            } else {

                $records = $qry->get();
            }

            $res = array(
                'success' => true,
                'data' => $records, 'totalCount' => $totalCount
            );


            //var_dump($qry->toSql());
            //exit();

        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        }
        return response()->json($res, 200);
    }

    public function saveProductRetentionSelection(Request $req)
    {
        try {
            $res = array();
            $application_code = $req->application_code;
            $reg_product_id = $req->reg_product_id;
            $retention_status_id = $req->retention_status_id;
            $retention_year = $req->retention_year;
            $next_retention_year = $req->next_retention_year;
            $retentiongeration_request_id = $req->retentiongeration_request_id;
            $table_name = 'tra_product_retentions';

            $trader_id = $req->trader_id;
            $email_address = $req->email_address;
            $retentionapp_data = array(
                'application_code' => $application_code,
                'reg_product_id' => $reg_product_id,
                'retention_status_id' => $retention_status_id,
                'retention_year' => $retention_year,
                'next_retention_year' => $next_retention_year,
                'retentiongeration_request_id' => $retentiongeration_request_id
            );
            $where_app = array('retentiongeration_request_id' => $retentiongeration_request_id, 'reg_product_id' => $reg_product_id);
            if (!recordExists($table_name, $where_app, 'mis_db')) {
                $retentionapp_data['created_by'] = $email_address;
                $retentionapp_data['created_on'] = Carbon::now();
                $resp_retintion = insertRecord($table_name, $retentionapp_data, $email_address, 'mis_db');
                if ($resp_retintion['success']) {

                    $res = array('success' => true, 'message' => 'The registered product has been added successfully, add more products or proceed for Documents upload and invoice Generation');
                } else {
                    $res =   $resp_retintion;
                }
            } else {
                $res = array('success' => false, 'message' => 'The Registered product has already been selected');
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        }
        return response()->json($res, 200);
    }
    public function getretentionFeesProductsData(Request $req)
    {
        try {
            $records = array();
            $retentiongeration_request_id = $req->retentiongeration_request_id;
            if (validateIsNumeric($retentiongeration_request_id)) {
                $qry = DB::connection('mis_db')->table('tra_product_applications as t1')
                    ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                    ->join('tra_product_information as t7', 't1.product_id', '=', 't7.id')
                    ->leftJoin('par_common_names as t8', 't7.common_name_id', '=', 't8.id')
                    ->leftJoin('wb_trader_account as t9', 't1.local_agent_id', '=', 't9.id')
                    ->leftJoin('par_classifications as t10', 't7.classification_id', '=', 't10.id')
                    ->leftJoin('tra_approval_recommendations as t11', 't1.application_code', '=', 't11.application_code')
                    ->join('tra_registered_products as t12', 't12.tra_product_id', '=', 't7.id')
                    ->leftJoin('par_validity_statuses as t4', 't12.validity_status_id', '=', 't4.id')
                    ->leftJoin('par_registration_statuses as t15', 't12.registration_status_id', '=', 't15.id')
                    ->leftJoin('par_sections as t16', 't1.section_id', '=', 't16.id')
                    ->leftJoin('tra_product_manufacturers as t14', function ($join) {
                        $join->on('t7.id', '=', 't14.product_id')
                            ->on('t14.manufacturer_role_id', '=', DB::raw(1))
                            ->on('t14.manufacturer_type_id', '=', DB::raw(1));
                    })
                    ->leftJoin('tra_product_retentions as t17', 't1.application_code', '=', 't17.application_code')
                    ->select(DB::raw("t7.*,t1.*,t17.id as product_retention_id, t16.name as section_name, t4.name as validity_status,t15.name as registration_status, t1.id as active_application_id, t1.reg_product_id, t3.name as applicant_name, t9.name as local_agent, t12.id as registered_product_id,t1.product_id as tra_product_id,
                    t7.brand_name, t12.tra_product_id, t8.name as common_name, t10.name as classification_name, t12.registration_no as certificate_no, max(t11.expiry_date) as expiry_date,
                    t7.brand_name as sample_name,t14.manufacturer_id"))
                    ->where(array('t17.retentiongeration_request_id' => $retentiongeration_request_id))
                    ->groupBy('t17.id')->orderBy('t12.expiry_date', 'desc'); //, 't7.section_id'=>$section_id

                $records = $qry->get();
            }

            $res = array(
                'success' => true,
                'data' => $records
            );


            //var_dump($qry->toSql());
            //exit();

        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        }
        return response()->json($res, 200);
    }
    public function onDeleteRetentionProductsDetails(Request $req)
    {

        try {
            $record_id = $req->record_id;
            $product_id = $req->product_id;
            $table_name = $req->table_name;
            $title = $req->title;
            $email_address = $req->email_address;
            $data = array();
            //get the records 
            $resp = false;
            $where_state = array('id' => $record_id);
            $records = DB::connection('mis_db')->table($table_name)
                ->where($where_state)
                ->get();
            //add a check for the invoice 

            if (count($records) > 0) {
                $previous_data = getPreviousRecords($table_name, $where_state, 'mis_db');

                $resp = deleteRecordNoTransaction($table_name, $previous_data, $where_state,  $email_address, 'mis_db');
            }
            if ($resp) {
                $res = array('success' => true, 'message' => $title . ' removed successfully');
            } else {

                $res = array('success' => false, 'message' => $title . ' delete failed, contact the system admin if this persists');
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), '');
        }
        return response()->json($res, 200);
    }
    public function getOnProductSummaryVariationChanges(Request $req)
    {
        try {
            $application_code = $req->application_code;

            $table_name = 'tra_variationsummary_guidelinesconfig';
            $results = array();
            $records = DB::connection('mis_db')->table($table_name . ' as t1')
                ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                ->leftJoin('par_variation_reportingtypes as t5', 't1.variation_reportingtype_id', 't5.id')
                ->leftJoin('par_variation_subdescription as t6', 't1.variation_subdescription_id', 't6.id')
                ->leftJoin('par_variation_description as t7', 't1.variation_description_id', 't7.id')
                ->leftJoin('par_variation_categories as t8', 't7.variation_category_id', 't8.id')
                ->leftJoin('par_variation_subcategories as t9', 't7.variation_subcategory_id', 't9.id')
                ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')

                ->select('t1.*', 't1.variation_reportingtype_id as variation_type_id', 't8.name as variation_category', 't7.variation_subcategory_id', 't7.variation_category_id', 't9.name as variation_subcategory', 't1.id as variationsummary_guidelinesconfig_id', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name', 't10.name as product_category', 't5.name as variation_reportingtype', 't6.name as variation_subdescription', 't7.name as variation_description', DB::raw("(SELECT group_concat(concat(code,': ', name)  SEPARATOR '<br/> <br/>') AS variationconditions_detail_id FROM tra_variationconfigconditions_details q left join par_variationconditions_details j on q.variationconditions_detail_id = j.id WHERE variationsummary_guidelinesconfig_id =t1.id) as variationconditions_detailsdata , (SELECT group_concat(concat(code,': ', name) SEPARATOR '<br/> <br/>') AS variationsupporting_datadoc_id  FROM tra_variationconfigsupporting_datadocs k left join par_variationsupporting_datadocs l on k.variationsupporting_datadoc_id =l.id WHERE variationsummary_guidelinesconfig_id =t1.id) as variationsupporting_datadocs"))
                ->get();
            foreach ($records as $rec) {
                $rec->variationsupporting_datadocs_code = explode(',', $rec->variationsupporting_datadocs);
                $rec->variationconditions_detailscodes = explode(',', $rec->variationconditions_detailsdata);

                $rec->variationsupporting_datadoc_id = explode(',', $rec->variationsupporting_datadocs);
                $rec->variationconditions_detail_id = explode(',', $rec->variationconditions_detailsdata);
                $results[] = $rec;
            }
            $res = array(
                'success' => true,
                'data' => $records
            );
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

    public function onSearchFoodProductProductApplication(Request $req)
    {
        try {
            $mistrader_id = $req->mistrader_id;
            $section_id = $req->section_id;
            $validity_status = $req->validity_status;
            $registration_status = $req->registration_status;

            $take = $req->take;
            $skip = $req->skip;
            $searchValue = $req->searchValue;
            $search_value =  '';
            if ($req->searchValue != 'undefined' && $req->searchValue != '') {
                $searchValue = explode(',', $searchValue);
                $search_value =  $searchValue[2];
            }
            $portalDb = \DB::getDatabaseName();
            $qry = DB::connection('mis_db')->table('tra_product_applications as t1')
                ->leftJoin('wb_trader_account as t3', 't1.applicant_id', '=', 't3.id')
                ->join('tra_product_information as t7', 't1.product_id', '=', 't7.id')
                ->leftJoin('par_common_names as t8', 't7.common_name_id', '=', 't8.id')
                ->leftJoin('tra_premises as t9', 't1.local_agent_id', '=', 't9.id')
                ->leftJoin('par_classifications as t10', 't7.classification_id', '=', 't10.id')
                ->leftJoin('tra_approval_recommendations as t11', 't1.application_code', '=', 't11.application_code')
                ->leftJoin('tra_registered_products as t12', 't12.tra_product_id', '=', 't7.id')

                ->leftJoin('par_storage_conditions as t13', 't7.storage_condition_id', '=', 't13.id')
                ->leftJoin('par_validity_statuses as t4', 't11.appvalidity_status_id', '=', 't4.id')
                ->leftJoin('par_registration_statuses as t15', 't11.appregistration_status_id', '=', 't15.id')
                ->join('par_sections as t16', 't1.section_id', '=', 't16.id')
                ->leftJoin('tra_product_manufacturers as t14', function ($join) {
                    $join->on('t7.id', '=', 't14.product_id')
                        ->on('t14.manufacturer_role_id', '=', DB::raw(1))
                        ->on('t14.manufacturer_type_id', '=', DB::raw(1));
                })
                ->select(
                    't7.*',
                    't1.*',
                    't1.application_code as reference_application_code',
                    't16.name as section_name',
                    't4.name as validity_status',
                    't15.name as registration_status',
                    't1.id as active_application_id',
                    't1.reg_product_id',
                    't3.name as applicant_name',
                    't9.name as local_agent',
                    't12.id as registered_product_id',
                    't1.product_id as tra_product_id',
                    't13.name as storage_condition',
                    't7.brand_name',
                    't12.tra_product_id',
                    't8.name as common_name',
                    't10.name as classification_name',
                    't11.certificate_no',
                    't7.brand_name as sample_name',
                    't14.manufacturer_id',
                    DB::raw("5 as prodclass_category_id")
                )
                ->whereNull('t11.id')
                ->whereIn('t1.section_id', [1, 9, 19])
                ->groupBy('t7.id')->orderBy('t1.id', 'desc'); //, 't7.section_id'=>$section_id'',

            if (isset($section_id) && is_numeric($section_id)) {
                $qry->where('t1.section_id', $section_id);
            }
            if (validateIsNumeric($validity_status)) {
                $qry->where('t12.validity_status_id', $validity_status);
            }
            if (validateIsNumeric($registration_status)) {
                $qry->where('t12.registration_status_id', $registration_status);
            }
            if (validateIsNumeric($mistrader_id)) {
                //$qry->where('t1.applicant_id', $mistrader_id);
            }


            if ($search_value != '') {
                $whereClauses = array();
                $whereClauses[] = "t8.name like '%" . ($search_value) . "%'";

                $whereClauses[] = "t7.brand_name  like '%" . ($search_value) . "%'";
                $whereClauses[] = "t8.name  like '%" . ($search_value) . "%'";
                $whereClauses[] = "t11.certificate_no  like '%" . ($search_value) . "%'";
                $filter_string = implode(' OR ', $whereClauses);
                $qry->whereRAW($filter_string);
            }
            $qry->where('t1.section_id', 1);
            $qry->where('t1.sub_module_id', 7);
            $totalCount = $qry->count();
            if (validateIsNumeric($take)) {
                $records = $qry->skip($skip)->take($take)->get();
            } else {

                $records = $qry->get();
            }

            $res = array(
                'success' => true,
                'data' => $records, 'totalCount' => $totalCount
            );
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
}
