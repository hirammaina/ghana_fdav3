<?php

namespace Modules\Parameters\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Parameters\Entities\Finance\CostCenter;
use Modules\Parameters\Entities\Finance\CostCategory;
use Modules\Parameters\Entities\Finance\CostSubCategory;
use Modules\Parameters\Entities\Finance\Currency;
use Modules\Parameters\Entities\Finance\elementscost;
use Modules\Parameters\Entities\Finance\ExchangeRate;
use Modules\Parameters\Entities\Finance\FeeType;
use Modules\Parameters\Entities\Finance\PaymentInterval;
use Modules\Parameters\Entities\Finance\TransactionType;
use Modules\Parameters\Entities\Locations\Country;
use Modules\Parameters\Entities\Locations\Region;
use Modules\Parameters\Entities\Locations\District;
use Modules\Parameters\Entities\Locations\County;
use Modules\Parameters\Entities\Locations\SubCounty;
use Modules\Parameters\Entities\Locations\City;
use Modules\Parameters\Entities\PortalParameter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommonParameterController extends BaseController
{
    public function __construct()
    {

        $this->invoker = [
            //            "save-portalparameter" => function($request) {
            //                $validator = $this->validateParameterRequest($request);
            //
            //                if($validator -> fails()){
            //                    return response() -> json([
            //                        "success" =>  false,
            //                        "message" => "Form has errors",
            //                        "errors" => $validator -> errors()
            //                    ]);
            //                }
            //
            //                return PortalParameter::saveData($request,
            //                    "par_portal_parameters",
            //                    $request->input('id'));
            //            },
            "save-country" => function ($request) {
                $validator = $this->validateParameterRequest($request);

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }

                return Country::saveData(
                    $request,
                    "par_countries",
                    $request->input('id')
                );
            },
            "save-region" => function ($request) {
                $validator = $this->validateParameterRequest(
                    $request,
                    "country_id"
                );

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return Region::saveData(
                    $request,
                    'par_regions',
                    $request->input('id'),
                    'country_id'
                );
            },
            "save-district" => function ($request) {
                $validator = $this->validateParameterRequest($request, "region_id");

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return District::saveData(
                    $request,
                    'par_districts',
                    $request->input('id'),
                    'region_id'
                );
            },
            "save-city" => function ($request) {
                $validator = $this->validateParameterRequest(
                    $request,
                    "district_id"
                );

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return city::saveData(
                    $request,
                    'par_cities',
                    $request->input('id'),
                    'district_id'
                );
            },
            "save-costcenter" => function ($request) {
                $validator = $this->validateParameterRequest($request);

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return CostCenter::saveData($request, "par_cost_centers", $request->input('id'));
            },
            "save-costcategory" => function ($request) {
                $validator = $this->validateParameterRequest($request);

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return CostCategory::saveData(
                    $request,
                    'par_cost_categories',
                    $request->input('id'),
                    'cost_center_id'
                );
            },
            "save-costsubcategory" => function ($request) {
                $validator = $this->validateParameterRequest($request);
                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return CostSubCategory::saveData(
                    $request,
                    'par_cost_sub_categories',
                    $request->input('id'),
                    'cost_category_id'
                );
            },
            "save-currency" => function ($request) {
                $validator = $this->validateParameterRequest($request);

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return Currency::saveData($request, "par_currencies", $request->input('id'));
            },
            "save-exchangerate" => function ($request) {
                $validator = null;
                if ($request->isMethod("PUT")) {
                    $validator = Validator::make($request->all(), [
                        "id" => "required|Integer",
                        "rate" => "required|Numeric",
                        "currency_id" => "required|Integer",
                        "description" => "sometimes|max:255"
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        "rate" => "required|Numeric",
                        "currency_id" => "required|Integer",
                        "description" => "sometimes|max:255"
                    ]);
                }

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return ExchangeRate::saveExchangeRate($request, $request->input('id'));
            },
            "save-feetype" => function ($request) {
                $validator = null;
                if ($request->isMethod("PUT")) {
                    $validator = Validator::make($request->all(), [
                        "id" => "required|Integer",
                        "name" => "required",
                        "gl_code" => "required"
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        "name" => "required",
                        "gl_code" => "required"
                    ]);
                }

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return FeeType::saveFeeType($request, $request->input('id'));
            },
            "save-transactiontype" => function ($request) {
                $validator = null;
                if ($request->isMethod("PUT")) {
                    $validator = Validator::make($request->all(), [
                        "id" => "required|Integer",
                        "t_code" => "required",
                        "description" => "sometimes|max:255",
                        "t_type" => [
                            "required",
                            Rule::in(["Debit", "Credit"])
                        ],
                        "output" => [
                            "required",
                            Rule::in(["None", "Receipt", "Debit Note", "Credit Note"])
                        ],
                        "system_invoice" => "sometimes|boolean",
                        "system_receipt" => "sometimes|boolean"
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        "t_code" => "required",
                        "description" => "sometimes|max:255",
                        "t_type" => [
                            "required",
                            Rule::in(["Debit", "Credit"])
                        ],
                        "output" => [
                            "required",
                            Rule::in(["None", "Receipt", "Debit None", "Credit None"])
                        ],
                        "system_invoice" => "sometimes|boolean",
                        "system_receipt" => "sometimes|boolean"
                    ]);
                }

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return TransactionType::saveTransactionType($request, $request->input('id'));
            },
            "save-paymentinterval" => function ($request) {
                $validator = null;
                if ($request->isMethod("PUT")) {
                    $validator = Validator::make($request->all(), [
                        "id" => "sometimes|Integer",
                        "name" => "sometimes",
                        "duration" => "sometimes|Integer",
                        "unit" => "sometimes|Integer",
                        "fixed" => "required|boolean",
                        "fixed_entry_point" => "sometimes",
                        "notification_time_interval" => "sometimes|Integer",
                        "notification_time_interval_unit" => "sometimes|Integer"
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        "name" => "sometimes",
                        "duration" => "sometimes|Integer",
                        "unit" => "sometimes|Integer",
                        "fixed" => "required|boolean",
                        "fixed_entry_point" => "sometimes",
                        "notification_time_interval" => "sometimes|Integer",
                        "notification_time_interval_unit" => "sometimes|Integer"
                    ]);
                }

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Form has errors",
                        "errors" => $validator->errors()
                    ]);
                }
                return PaymentInterval::savePaymentInterval($request, $request->input('id'));
            },
            "get-portalparameters" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return PortalParameter::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-country" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return Country::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-region" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return Region::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-district" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return District::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },

            "get-county" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return County::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-subcounty" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return SubCounty::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-city" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return City::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-costcenter" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return CostCenter::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-costcategory" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return CostCategory::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-costsubcategory" => function ($start, $limit, $doRetrieveAll, $filter = null) {

                return CostSubCategory::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-currency" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return Currency::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-exchangerate" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return ExchangeRate::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-feetype" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return FeeType::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-transactiontype" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return TransactionType::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "get-paymentinterval" => function ($start, $limit, $doRetrieveAll, $filter = null) {
                return PaymentInterval::getData(
                    $start,
                    $limit,
                    $doRetrieveAll,
                    $filter != null ? $this->parseFilter($filter) : null
                );
            },
            "merge-country" => function ($request) {
                return Country::merge(
                    $request->input('mergeToId'),
                    "country_id",
                    "par_countries",
                    $request->input('ids')
                );
            },
            "merge-region" => function ($request) {
                return Region::merge(
                    $request->input('mergeToId'),
                    "region_id",
                    "par_regions",
                    $request->input('ids')
                );
            },
            "merge-district" => function ($request) {
                return District::merge(
                    $request->input('mergeToId'),
                    "district_id",
                    "par_districts",
                    $request->input('ids')
                );
            },
            "merge-city" => function ($request) {
                return City::merge(
                    $request->input('mergeToId'),
                    "city_id",
                    "par_cities",
                    $request->input('ids')
                );
            },
            "merge-costcenter" => function ($request) {
                return CostCenter::merge(
                    $request->input('mergeToId'),
                    "cost_center_id",
                    "par_cost_centers",
                    $request->input('ids')
                );
            },
            "merge-costcategory" => function ($request) {
                return CostCategory::merge(
                    $request->input('mergeToId'),
                    "cost_category_id",
                    "par_cost_categories",
                    $request->input('ids')
                );
            },
            "merge-costsubcategory" => function ($request) {
                return CostCategory::merge(
                    $request->input('mergeToId'),
                    "cost_sub_category_id",
                    "par_cost_sub_categories",
                    $request->input('ids')
                );
            },
            "merge-currency" => function ($request) {
                return Currency::merge(
                    $request->input('mergeToId'),
                    "currency_id",
                    "par_currencies",
                    $request->input('ids')
                );
            },
            "merge-exchangerate" => function ($request) {
                return Currency::merge(
                    $request->input('mergeToId'),
                    "exchange_rate_id",
                    "par_exchange_rates",
                    $request->input('ids')
                );
            },
            "merge-feetype" => function ($request) {
                return Currency::merge(
                    $request->input('mergeToId'),
                    "fee_type_id",
                    "par_fee_types",
                    $request->input('ids')
                );
            },
            "merge-transactiontype" => function ($request) {
                return Currency::merge(
                    $request->input('mergeToId'),
                    "transaction_type_id",
                    "par_transaction_types",
                    $request->input('ids')
                );
            },
            "merge-paymentinterval" => function ($request) {
                return Currency::merge(
                    $request->input('mergeToId'),
                    "payment_interval_id",
                    "par_payment_intervals",
                    $request->input('ids')
                );
            },
            "delete-country" => function ($id, $action) {
                return Country::deleteData('par_countries', $id, $action);
            },
            "delete-region" => function ($id, $action) {
                return Region::deleteData('par_regions', $id, $action);
            },
            "delete-district" => function ($id, $action) {
                return District::deleteData('par_districts', $id, $action);
            },
            "delete-city" => function ($id, $action) {
                return District::deleteData('par_cities', $id, $action);
            },
            "delete-costcenter" => function ($id, $action) {
                return CostCenter::deleteData('par_cost_centers', $id, $action);
            },
            "delete-costcategory" => function ($id, $action) {
                return CostCategory::deleteData('par_cost_categories', $id, $action);
            },
            "delete-costsubcategory" => function ($id, $action) {
                return CostCategory::deleteData('par_cost_sub_categories', $id, $action);
            },
            "delete-currency" => function ($id, $action) {
                return Currency::deleteData('par_currencies', $id, $action);
            },
            "delete-exchangerate" => function ($id, $action) {
                return Currency::deleteData('par_exchange_rates', $id, $action);
            },
            "delete-feetype" => function ($id, $action) {
                return Currency::deleteData('par_fee_types', $id, $action);
            },
            "delete-transactiontype" => function ($id, $action) {
                return Currency::deleteData('par_transaction_types', $id, $action);
            },
            "delete-paymentinterval" => function ($id, $action) {
                return Currency::deleteData('par_payment_intervals', $id, $action);
            }
        ];
    }

    //Added by KIP
    public function getCommonParamFromModel(Request $request)
    {
        $model_name = $request->input('model_name');
        $strict_mode = $request->input('strict_mode');
        try {
            $model = 'Modules\\Parameters\\Entities\\' . $model_name;
            if (isset($strict_mode) && $strict_mode == 1) {
                $results = $model::where('is_enabled', 1)
                    ->get()
                    ->toArray();
            } else {
                $results = $model::all()
                    ->toArray();
            }
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well'
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


    public function getCommonParamFromTable(Request $request)
    {
        $table_name = $request->input('table_name');
        $strict_mode = $request->input('strict_mode');
        $is_config = $request->input('is_config');
        $filters = $request->input('filters');
        $con = $request->input('con');
        $db_con = 'mysql';
        if (isset($con) && $con != '') {
            $db_con = $con;
        }
        $filters = (array)json_decode($filters);
        $filters = array_filter($filters);

        try {
            if ($table_name == 'par_business_types') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    // ->whereIn('id', [1, 2, 4])// Job to reinstate on 06/02/2024
                    ->select('t1.*');
            } else if ($table_name == 'par_premise_districts') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->select('t1.*');
            } else if ($table_name == 'par_variation_categories') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                    ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                    ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                    ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')
                    ->select('t1.*', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name', 't10.name as product_category');
            } else if ($table_name == 'par_variation_subcategories') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                    ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                    ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                    ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')
                    ->leftJoin('par_variation_categories as t8', 't1.variation_category_id', 't8.id')
                    ->select('t1.*', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name', 't10.name as product_category', 't8.name as variation_category');
            } else if ($table_name == 'par_variation_description') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                    ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                    ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                    ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')
                    ->leftJoin('par_variation_categories as t8', 't1.variation_category_id', 't8.id')
                    ->leftJoin('par_variation_subcategories as t9', 't1.variation_subcategory_id', 't9.id')
                    ->select('t1.*', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name', 't10.name as product_category', 't8.name as variation_category', 't9.name as variation_subcategory');
            } else if ($table_name == 'par_variation_subdescription') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                    ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                    ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                    ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')
                    ->leftJoin('par_variation_categories as t8', 't1.variation_category_id', 't8.id')
                    ->leftJoin('par_variation_subcategories as t9', 't1.variation_subcategory_id', 't9.id')
                    ->leftJoin('par_variation_description as t7', 't1.variation_description_id', 't7.id')
                    ->select('t1.*', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name', 't10.name as product_category', 't8.name as variation_category', 't9.name as variation_subcategory', 't7.name as variation_description');
            } else if ($table_name == 'par_variationconditions_details') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                    ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                    ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                    ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')
                    ->leftJoin('par_variation_categories as t8', 't1.variation_category_id', 't8.id')
                    ->select('t1.*', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name', 't10.name as product_category', 't8.name as variation_category');
            } else if ($table_name == 'par_variationsupporting_datadocs') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                    ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                    ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                    ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')
                    ->leftJoin('par_variation_categories as t8', 't1.variation_category_id', 't8.id')
                    ->select('t1.*', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name', 't10.name as product_category', 't8.name as variation_category');
            } else if ($table_name == 'par_premise_regions' && isset($filters['district_id'])) {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_premise_district_region as t2', 't1.id', 't2.region_id')
                    ->select('t1.*')
                    ->where('t2.district_id', $filters['district_id']);
            } else if ($table_name == 'par_formtype_fields') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->Join('par_formfield_designs as t4', 't1.field_id', '=', 't4.id')
                    ->select('t1.*', 't4.label as field_name', 't4.label');
                //order
                $qry->orderBy('order_no', 'ASC');
            } else if ($table_name == 'pms_program_details') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    //->join('par_sections as t2','t1.section_id','=','t2.id')
                    ->select('t1.*');
            } else if ($table_name == 'par_inspection_types') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    //->join('par_sections as t2','t1.section_id','=','t2.id')
                    ->select('t1.*');
            } else if ($table_name == 'par_business_type_details') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftjoin('par_business_types as t3', 't1.business_type_id', '=', 't3.id')
                    ->leftjoin('par_sections as t2', 't3.section_id', '=', 't2.id')
                    ->select('t1.*', 't3.section_id', 't2.name as section_name', 't3.name as business_type_name');
            } else if ($table_name == 'par_systemreports_repconfig') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftjoin('par_business_types as t3', 't1.business_type_id', '=', 't3.id')
                    ->leftjoin('par_sections as t2', 't3.section_id', '=', 't2.id')
                    ->leftjoin('modules as t4', 't1.module_id', '=', 't4.id')
                    ->leftjoin('sub_modules as t5', 't1.sub_module_id', '=', 't5.id')
                    ->leftjoin('par_systemreport_types as t6', 't1.sysreports_type_id', '=', 't6.id')
                    ->leftjoin('par_approval_decisions as t7', 't1.decision_id', '=', 't7.id')
                    ->leftjoin('par_confirmations as t8', 't1.is_preview', '=', 't8.id')
                    ->leftjoin('par_confirmations as t9', 't1.is_uniformreport', '=', 't9.id')
                    ->leftjoin('par_prodclass_categories as t10', 't1.prodclass_category_id', '=', 't10.id')
                    ->leftjoin('par_importexport_permittypes as t11', 't1.import_export_type_id', '=', 't11.id')
                    ->select('t1.*', 't3.section_id', 't2.name as section_name', 't3.name as bussiness_type', 't4.name as module_name', 't5.name as sub_module_name', 't5.name as report_type_name', 't7.name as decision', 't8.name as is_preview_name', 't9.name as is_uniformreport_name', 't10.name as prodclass_category_name', 't11.name as import_export_type_name');
            } else if ($table_name == 'par_countries') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_countriesregions as t3', 't1.country_region_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as region_name');
            } else if ($table_name == 'par_directorate_emails') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_directorates as t3', 't1.directorate_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as directorate_name');
            } else if ($table_name == 'par_pmsevaluation_decisions' || $table_name == 'par_pmstcmeeting_decisions') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_pmssamples_stages as t3', 't1.samples_nextstage_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as next_stage');
            } else if ($table_name == 'par_departments') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_directorates as t3', 't1.directorate_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as directorate_name');
            } else if ($table_name == 'tra_organisation_information') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_zones as t3', 't1.zone_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as zone');
            } else if ($table_name == 'par_appprocess_definations') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_date_options as t3', 't1.date_option_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as date_option_name');
            } else if ($table_name == 'par_expirynotification_timespan' || $table_name == 'par_auditreport_config' || $table_name == 'par_service_types') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('modules as t3', 't1.module_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as module_name');
            } else if ($table_name == 'par_inventorysection_levels') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_inventorystore_sections as t3', 't1.store_section_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as section_name');
            } else if ($table_name == 'par_inventorystore_sections') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_inventory_stores as t3', 't1.store_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as store_name');
            } else if ($table_name == 'element_costs') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('cost_elements as t3', 't1.element_id', '=', 't3.id')
                    ->leftJoin('par_currencies as t4', 't1.currency_id', '=', 't4.id')
                    ->select('t1.*', 't3.name as name', 't4.name as currency_name');
            } else if ($table_name == 'par_controldocument_masterlist') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_controldocument_types as t3', 't1.controldocument_type_id', '=', 't3.id')
                    ->leftJoin('par_directorates as t4', 't1.directorate_id', '=', 't4.id')
                    ->leftJoin('par_directorate_units as t5', 't1.directorate_unit_id', '=', 't5.id')
                    ->leftJoin('refnumbers_formats as t6', 't1.ref_format_id', '=', 't6.id')
                    ->select('t1.*', 't3.name as controldocument_type_name', 't1.id as controldoc_master_id', 't1.name as control_document_name', 't1.code as document_no', 't4.name as directorate_name', 't5.name as directorate_unit_name', 't6.name as ref_format');
            } else if ($table_name == 'par_directorate_units') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_directorates as t3', 't1.directorate_id', '=', 't3.id')
                    ->select('t1.*', 't3.name as directorate_name');
            } else if ($table_name == 'users') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->select(DB::raw("CONCAT_WS(' ',decrypt(t1.first_name),decrypt(t1.last_name)) as fullnames,t1.*"));
                $is_config = 1;
            } else if ($table_name == 'par_exchange_rates') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_currencies as t3', 't1.currency_id', '=', 't3.id')
                    ->select(
                        't1.*',
                        't3.name as currency_name',
                        DB::raw('DATEDIFF(NOW(), t1.dola) AS days_since_last_change'),
                        DB::raw('(DATEDIFF(NOW(), t1.dola)) % 15 AS number_of_days')
                    );
            } else if ($table_name == 'par_servicecharter_configurations') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('modules as t3', 't1.module_id', '=', 't3.id')
                    ->leftJoin('par_service_types as t4', 't1.service_type_id', '=', 't4.id')
                    ->select('t1.*', 't3.name as module_name', 't4.name as service_type');
            } else if ($table_name == 'par_distributiondirective_units') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_document_directorate as t4', 't1.document_directorate_id', '=', 't4.id')
                    ->select('t1.*', 't4.name as directorate_name');
            } else if ($table_name == 'tra_manufacturers_information') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_countries as t4', 't1.country_id', '=', 't4.id')
                    ->leftJoin('par_regions as t5', 't1.region_id', '=', 't5.id')
                    ->select('t1.*', 't4.name as country_name', 't5.name as region_name');
            } else if ($table_name == 'tra_pharmacist_personnel') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_countries as t4', 't1.country_id', '=', 't4.id')
                    ->leftJoin('par_regions as t5', 't1.region_id', '=', 't5.id')
                    ->leftJoin('par_districts as t6', 't1.district_id', '=', 't6.id')
                    ->leftJoin('par_personnel_qualifications as t7', 't1.qualification_id', '=', 't7.id')
                    ->select('t1.*', 't4.name as country_name', 't5.name as region_name', 't6.name as district_name', 't7.name as qualification_name');
            } else if ($table_name == 'tra_disposal_bodies') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_disposal_license_types as t2', 't1.licence_type_id', '=', 't2.id')
                    ->leftJoin('par_disposal_waste_types as t3', 't1.waste_type_id', '=', 't3.id')
                    ->leftJoin('par_countries as t4', 't1.country_id', '=', 't4.id')
                    ->leftJoin('par_regions as t5', 't1.region_id', '=', 't5.id')
                    ->leftJoin('par_districts as t6', 't1.district_id', '=', 't6.id')
                    ->select('t1.*', 't4.name as country_name', 't5.name as region_name', 't6.name as district_name', 't2.name as license_name', 't3.name as waste_name');
            } else if ($table_name == 'tra_premise_incharge_personnel') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_countries as t4', 't1.country_id', '=', 't4.id')
                    ->leftJoin('par_regions as t5', 't1.region_id', '=', 't5.id')
                    ->leftJoin('par_districts as t6', 't1.district_id', '=', 't6.id')
                    ->leftJoin('par_personnel_qualifications as t7', 't1.qualification_id', '=', 't7.id')
                    ->select('t1.*', 't4.name as country_name', 't5.name as region_name', 't6.name as district_name', 't7.name as qualification_name');
            } else if ($table_name == 'par_audited_tables') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_audit_table_types as t4', 't1.table_type_id', '=', 't4.id')
                    ->select('t1.*', 't4.name as table_type');
            } else if ($table_name == 'par_default_currencies') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->Join('par_currencies as t4', 't1.currency_id', '=', 't4.id')
                    ->select('t4.*', 't1.id', 't4.id as currency_id');
            } else if ($table_name == 'par_formfield_designs') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->Join('par_form_field_types as t5', 't1.form_field_type_id', '=', 't5.id')
                    ->select('t1.*', 't5.name as field_type');
            } else if ($table_name == 'par_form_categories') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->Join('modules as t4', 't1.module_id', '=', 't4.id')
                    ->Join('sub_modules as t5', 't1.sub_module_id', '=', 't5.id')
                    ->leftJoin('par_sections as t6', 't1.section_id', '=', 't6.id')
                    ->leftJoin('par_prodclass_categories as t7', 't1.prodclass_category_id', '=', 't7.id')
                    ->select('t1.*', 't1.id  as form_category_id', 't4.name as module_name', 't5.name as sub_module_name', 't6.name as section_name', 't7.name as prodclass_category_name');
            } else if ($table_name == '') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->Join('par_formfield_designs as t4', 't1.field_id', '=', 't4.id')
                    ->select('t1.*', 't4.label as field_name', 't4.label');
                //order
                $qry->orderBy('order_no', 'ASC');
            } else if ($table_name == 'tra_otherstates_productgmpinspections' || $table_name == 'tra_otherstates_productregistrations') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->Join('par_countries as t4', 't1.country_id', 't4.id')
                   ->leftJoin('par_recognisedassessments_ctrregions as t5', 't1.recognisedassessments_ctrregion_id', 't5.id')//
                    ->select('t1.*', 't4.name as country', 't5.name as recognisedassessments_ctrregion');
                    if($table_name=="tra_otherstates_productregistrations")
                    {
                        $qry->leftjoin("par_marketing_authorizations_decisions as t6","t1.current_registrationstatus_id","t6.id")
                        ->addSelect("t6.name as current_registrationstatus");
                    }
            } else if ($table_name == 'tra_productreg_clinicalresearchsdetails') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_clinical_researchorganisations as t2', 't1.clinical_researchorganisation_id', 't2.id')
                    ->select('t1.*', 't2.name as clinical_researchorganisation');
            } else if ($table_name == 'registered_premises') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->join('tra_premises as t2', 't1.tra_premise_id', 't2.id')
                    ->select('t1.*', 't2.name');
            } else if ($table_name == 'par_controlleddrugsconv_factorsconfig') {
                $qry = DB::connection($db_con)
                    ->table('par_controlleddrugsconv_factorsconfig as t1')
                    ->leftJoin('par_controlleddrugs_types as t2', 't1.controlleddrugs_type_id', 't2.id')
                    ->leftJoin('par_controlled_drugssubstances as t3', 't1.controlled_drugssubstances_id', 't3.id')
                    ->leftJoin('par_controlleddrugs_basesalts as t4', 't1.controlleddrugs_basesalt_id', 't4.id')
                    ->select('t1.*', 't2.name as controlleddrugs_type', 't3.name as controlled_drugssubstances', 't4.name as controlleddrugs_basesalt');
            } else if ($table_name == 'par_controlleddrugsannual_ceilingconfig') {
                $qry = DB::connection($db_con)
                    ->table('par_controlleddrugsannual_ceilingconfig as t1')
                    ->join('par_controlleddrugs_types as t2', 't1.controlleddrugs_type_id', 't2.id')
                    ->join('par_controlled_drugssubstances as t3', 't1.controlled_drugssubstances_id', 't3.id')

                    ->select('t1.*', 't2.name as controlleddrugs_type', 't3.name as controlled_drugssubstances');
            } else if ($table_name == 'par_checklist_categories') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->leftJoin('par_checklist_category_groups as t2', 't1.category_group_id', 't2.id')
                    ->select('t1.*', 't2.name as category_group');
            } else if ($table_name == 'tra_applicationinvoicedata_queries' || $table_name == 'wb_applicationinvoicedata_queries') {
                $qry = DB::connection($db_con)
                    ->table($table_name . ' as t1')
                    ->join('modules as t4', 't1.module_id', '=', 't4.id')
                    ->leftJoin('sub_modules as t5', 't1.sub_module_id', '=', 't5.id')
                    ->leftJoin('par_sections as t6', 't1.section_id', '=', 't6.id')
                    ->select('t1.*', 't4.name as module_name', 't5.name as sub_module_name', 't6.name as section_name');
            } else {
                $qry = DB::connection($db_con)->table($table_name . ' as t1');
            }
            if ($table_name == 'par_sections') {
                //$qry->whereIn('id',[1,3,2,4,5,6,12,15,]);
            }

            if (count((array)$filters) > 0) {
                if ($table_name == 'par_countries') {
                    if (isset($filters['id'])) {
                        $id = $filters['id'];
                        $qry->where(array('t1.id' => $id));
                    }
                    if (isset($filters['is_local'])) {
                        $qry->where('is_local', 1);
                    }
                } else if ($table_name == 'par_product_subcategories' && isset($filters['prodclass_category_id'])) {
                    $qry->leftJoin('par_prodclass_subcategories as t2', 't1.id', 't2.product_subcategory_id')
                        ->select('t1.*')
                        ->where('t2.prodclass_category_id', $filters['prodclass_category_id']);
                } else if ($table_name == 'par_classifications' && isset($filters['prodclass_category_id'])) {
                    $qry->leftJoin('par_prodcat_classifications as t2', 't1.id', 't2.classification_id')
                        ->select('t1.*')
                        ->where('t2.prodclass_category_id', $filters['prodclass_category_id']);
                } else if ($table_name == 'par_variation_reportingtypes') {
                    if ($filters) {
                        $qry = DB::connection($db_con)

                            ->table('tra_variationsummary_guidelinesconfig as t1')
                            ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                            ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                            ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                            ->leftJoin('par_variation_reportingtypes as t5', 't1.variation_reportingtype_id', 't5.id')
                            ->leftJoin('par_variation_subdescription as t6', 't1.variation_subdescription_id', 't6.id')
                            ->leftJoin('par_variation_description as t7', 't1.variation_description_id', 't7.id')
                            ->leftJoin('par_variation_categories as t8', 't7.variation_category_id', 't8.id')
                            ->leftJoin('par_variation_subcategories as t9', 't7.variation_subcategory_id', 't9.id')
                            ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')
                            ->select('t5.*');


                        if (isset($filters['variation_subcategory_id']) && validateIsnumeric($filters['variation_subcategory_id'])) {
                            $qry->where('t9.id', $filters['variation_subcategory_id']);
                        }
                        if (isset($filters['variation_category_id']) && validateIsnumeric($filters['variation_category_id'])) {
                            $qry->where('t8.id', $filters['variation_category_id']);
                        }
                        if (isset($filters['variation_description_id']) && validateIsnumeric($filters['variation_description_id'])) {
                            $qry->where('t7.id', $filters['variation_description_id']);
                        }

                        if (isset($filters['variation_subdescription_id']) && validateIsnumeric($filters['variation_subdescription_id'])) {
                            $qry->where('t6.id', $filters['variation_subdescription_id']);
                        }
                    } else {
                        $qry = DB::connection($db_con)
                            ->table($table_name . ' as t1')
                            ->select('t1.*');
                    }
                } else if ($table_name == 'par_countries') {
                    $id = $filters['id'];
                    $qry->where(array('t1.id' => $id));
                } else {
                    //Job 27.01.24
                    if (array_key_exists("section_id", $filters) && ($table_name == "par_assessment_procedures" || $table_name == 'par_distribution_categories' || $table_name == "routes_of_administration")) {
                    } else {
                        $qry->where($filters);
                    }
                }
            }

            // $qry->where('t1.is_enabled',1);
            if (validateIsnumeric($is_config)) {
                $qry->where('t1.is_enabled', 1);
            }
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => returnMessage($results)
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
    public function getelementcost(request $request)
    {
        $table_name = $request->input('table_name');
        $cost_sub_category_id = $request->input('sub_category_id');
        $fee_type_id = $request->input('fee_type_id');
        $section_id = $request->input('section_id');
        $element_id = $request->input('element_id');
        try {
            $qry = DB::table('tra_element_costs as t1')
                ->LeftJoin('par_cost_elements as t2', 't1.element_id', 't2.id')
                ->LeftJoin('par_currencies as t4', 't1.currency_id', 't4.id')
                ->LeftJoin('par_cost_sub_categories as t5', 't1.sub_cat_id', 't5.id')
                ->LeftJoin('par_fee_types as t6', 't1.feetype_id', 't6.id')
                ->LeftJoin('par_cost_categories as a7', 't1.cost_category_id', 'a7.id')
                ->LeftJoin('par_confirmations as t8', 't1.formula', 't8.flag')
                ->LeftJoin('par_confirmations as t9', 't1.optional', 't9.flag')
                ->LeftJoin('par_gl_accounts as t10', 't1.gl_code_id', 't10.id')

                ->LeftJoin('par_sections as t11', 'a7.section_id', 't11.id')
                ->LeftJoin('par_cost_types as t12', 't1.cost_type_id', 't12.id')
                ->LeftJoin('par_revenue_accounts as t13', 't1.revenue_code_id', 't13.id') //Job on 18.02.24, table not available to reinstate later 
                ->select(
                    't1.*',
                    't1.id as element_costs_id',
                    't11.name as section_name',
                    't4.name as currency_name',
                    'a7.section_id',
                    't4.id as currency_id',
                    't2.name as element',
                    't4.name as currency',
                    't5.name as sub_category',
                    't6.name as feetype',
                    'a7.name as category',
                    't8.name as formulaflag',
                    't9.name as optionalflag',
                    't10.name as glcode',
                    't10.name as revenue_code',
                    't12.name as cost_type',
                    DB::raw("concat(t6.name,' ',a7.name,' ',t5.name,' ',t2.name, ' ',if(cost >0,cost,formula_rate),' ',t4.name) as element_desc")
                );


            if (validateIsNumeric($cost_sub_category_id)) {
                $qry->where('t5.id', $cost_sub_category_id);
            }
            if (validateIsNumeric($section_id)) {
                $qry->where('a7.section_id', $section_id);
            }
            if (validateIsNumeric($fee_type_id)) {
                $qry->where('t1.feetype_id', $fee_type_id);
            }
            if (validateIsNumeric($element_id)) {
                $qry->where('t1.element_id', $element_id);
            }
            $qry->orderBy('t1.id', 'DESC');
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

    public function getRetentionChargeConfig(Request $req)
    {
        try {
            $qry = DB::table('tra_retentioncharge_config as t1')
                ->leftJoin('par_sections as t2', 't1.section_id', 't2.id')
                ->leftJoin('par_prodclass_categories as t3', 't1.prodclass_category_id', 't3.id')
                ->leftJoin('par_classifications as t4', 't1.classification_id', 't4.id')
                ->leftJoin('par_product_types as t5', 't1.product_type_id', 't5.id')
                ->leftJoin('element_costs as t6', 't1.element_costs_id', 't6.id')
                ->leftJoin('par_fee_types as t7', 't1.fee_type_id', 't7.id')
                ->leftJoin('par_currencies as t8', 't6.currency_id', 't8.id')
                ->leftJoin('par_device_types as t9', 't1.device_type_id', 't9.id')
                ->select('t1.*', 't2.name as section_name', 't9.name as device_type', 't3.name as prodclass_category', 't4.name as classification_name', 't5.name as product_type', 't6.cost as element_cost', 't7.name as fee_type', DB::raw("CONCAT_WS(' ', t8.name,t6.cost) as element_cost"));
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => returnMessage($results)
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

    public function getglaccounts(request $request)
    {
        $cost_sub_category_id = $request->input('sub_category_id');
        try {
            $qry = DB::table('par_gl_accounts as t1')
                ->select('t1.*');
            if (isset($cost_sub_category_id)) {
                $qry->where('t5.id', $cost_sub_category_id);
            }
            //by
            $qry->orderBy('id', 'DESC');
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

    public function getRevenueAccounts(request $request)
    {
        $cost_sub_category_id = $request->input('sub_category_id');
        try {
            $qry = DB::table('par_revenue_accounts as t1')
                ->select('t1.*');
            if (isset($cost_sub_category_id)) {
                $qry->where('t5.id', $cost_sub_category_id);
            }
            //by
            $qry->orderBy('id', 'DESC');
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
    public function getAgeAnalysisDaysSpanParam(request $request)
    {
        $qry = DB::table('par_ageanalysisdays_span as t1')
            ->LeftJoin('modules as t2', 't1.module_id', 't2.id')
            ->select('t1.*', 't2.name as module');

        $qry->orderBy('id', 'DESC');
        $results = $qry->get();
        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is welhhl'
        );

        return json_encode($res);
    }

    public function getcostCategories(Request $request)
    {
        $filters = $request->filter;

        $qry = DB::table('par_cost_categories as t1')
            ->LeftJoin('par_fee_types as t2', 't1.fee_type_id', 't2.id')
            ->LeftJoin('par_sections as t3', 't1.section_id', 't3.id')
            ->select('t1.*', 't2.name as fee_type_name', 't3.name as section_name');

        $qry->orderBy('t1.id', 'DESC');
        if ($filters != '') {
            $filters = (array)json_decode($filters);
            if (validateIsNumeric($filters['section_id'])) {
                $results = $qry->where($filters);
            } else {
                unset($filters['section_id']);
                $results = $qry->where($filters);
            }
        }
        $results = $qry->get();
        $res = array(
            'success' => true,
            'data' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }
    public function getcostSubCategories(Request $request)
    {
        $filters = $request->filter;
        $qry = DB::table('par_cost_sub_categories as t1')
            ->LeftJoin('par_sections as t2', 't1.section_id', 't2.id')
            ->LeftJoin('par_cost_categories as t4', 't1.cost_category_id', 't4.id')
            ->LeftJoin('par_fee_types as t3', 't1.fee_type_id', 't3.id')
            ->select('t1.*', 't2.name as section_name', 't3.name as fee_type_name', 't4.name as cost_category_name');

        $qry->orderBy('t1.id', 'DESC');
        if ($filters != '') {
            $filters = (array)json_decode($filters);
            $results = $qry->where($filters);
        }
        $results = $qry->get();
        $res = array(
            'success' => true,
            'data' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }
    public function getProductTypes(Request $request)
    {

        $qry = DB::table('par_product_types as t1')
            ->LeftJoin('par_cost_sub_categories as t2', 't1.cost_subcategory_id', 't2.id')
            ->select('t1.*', 't2.name as cost_subcategory_name');

        $qry->orderBy('t1.id', 'DESC');
        $results = $qry->get();
        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }
    public function getBankBranches(Request $request)
    {

        $qry = DB::table('par_bankbranches as t1')
            ->LeftJoin('par_banks as t2', 't1.bank_id', 't2.id')
            ->select('t1.*', 't2.name as bank_name');

        $qry->orderBy('t1.id', 'DESC');
        $results = $qry->get();
        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }

    public function getCountriesByStateRegions(Request $request)
    {

        $qry = DB::table('par_countries as t1')
            ->select('t1.*');

        $qry->orderBy('t1.id', 'DESC');
        $results = $qry->get();
        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }
    public function getOrgBankAccounts(Request $request)
    {

        $qry = DB::table('tra_orgbank_accounts as t1')
            ->LeftJoin('par_banks as t2', 't1.bank_id', 't2.id')
            ->LeftJoin('par_bankbranches as t3', 't1.branch_id', 't3.id')
            ->LeftJoin('par_banks as t4', 't1.intermediate_bank_id', 't4.id')
            ->LeftJoin('par_currencies as t5', 't1.currency_id', 't5.id')
            ->select('t1.*', 't2.name as bank_name', 't3.name as branch_name', 't4.name as intermediate_bank', 't5.name as currency');

        $qry->orderBy('t1.id', 'DESC');
        $results = $qry->get();
        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }

    public function getDeviceTypes(Request $request)
    {

        $qry = DB::table('par_device_types as t1')
            ->LeftJoin('par_cost_types as t2', 't1.cost_type_id', 't2.id')
            ->LeftJoin('par_sections as t3', 't1.section_id', 't3.id')
            ->select('t1.*', 't2.name as cost_type_name', 't3.name as section_name');

        $qry->orderBy('t1.id', 'DESC');
        $results = $qry->get();
        $res = array(
            'success' => true,
            'data' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }

    public function saveCommonParameter(Request $req)
    {
        try {
            $user_id = \Auth::user()->id;
            $post_data = $req->all();

            $table_name = $post_data['table_name'];
            $id = $post_data['id'];
            //unset unnecessary values
            unset($post_data['_token']);
            unset($post_data['table_name']);
            unset($post_data['_dc']);
            unset($post_data['id']);

            $table_data = $post_data;
            //add extra params
            $table_data['created_on'] = Carbon::now();
            $table_data['created_by'] = $user_id;
            $where = array(
                'id' => $id
            );
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
                $res = insertRecord($table_name, $table_data, $user_id);
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

    function deleteParameters(Request $req)
    {
        $table_name = $req->table_name;
        $id = $req->id;
        $action = $req->action;
        return Currency::deleteData($table_name, $id, $action);
    }

    function getDirectorateNotificationsConfig(Request $req)
    {
        $qry = DB::table('tra_directorate_notifications as t1')
            ->leftJoin('par_directorates as t2', 't1.directorate_id', 't2.id')
            ->leftJoin('par_notification_categories as t3', 't1.notification_category_id', 't3.id')
            ->leftJoin('modules as t4', 't1.module_id', 't4.id')
            ->leftJoin('par_sections as t5', 't1.section_id', 't5.id')
            ->select('t1.*', 't2.name as directorate_name', 't3.name as notification_category_name', 't4.name as module_name', 't5.name as section_name');

        $results = $qry->get();

        foreach ($results as $result) {
            $emailArray = json_decode($result->email_addresses);
            $emails = implode(',', $emailArray);
            $result->email_addresses = $emails;
        }

        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }
    function getDepartmentalNotificationsConfig(Request $req)
    {
        $qry = DB::table('tra_departmental_notifications as t1')
            ->leftJoin('par_departments as t2', 't1.department_id', 't2.id')
            ->leftJoin('par_notification_categories as t3', 't1.notification_category_id', 't3.id')
            ->leftJoin('modules as t4', 't1.module_id', 't4.id')
            ->leftJoin('par_sections as t5', 't1.section_id', 't5.id')
            ->select('t1.*', 't2.name as department_name', 't3.name as notification_category_name', 't4.name as module_name', 't5.name as section_name');
        $results = $qry->get();
        foreach ($results as $result) {
            $groupArray = json_decode($result->group_ids);
            $groups = $this->getStringFromTable($groupArray, 'par_groups', 'name');
            $result->group_ids = $groups;
        }

        $res = array(
            'success' => true,
            'results' => $results,
            'message' => 'All is well'
        );

        return json_encode($res);
    }
    public function getStringFromTable($IDarrays, $table, $field)
    {
        $qry = DB::table($table)
            ->whereIn('id', $IDarrays)
            ->select($field);

        $results = $qry->get();
        $res = array();
        foreach ($results as $result) {
            $res[] = $result->$field;
        }
        $string = implode(',', $res);


        return $string;
    }
    public function getUserGroupsdetails(Request $req)
    {
        try {
            $user_id = $req->user_id;
            $qry = DB::table('tra_user_group as t1')
                ->join('par_groups as t2', 't1.group_id', '=', 't2.id')
                ->select('t2.*')
                ->where('user_id', $user_id);
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => returnMessage($results)
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


    public function getVariationsRequestConfiguration(Request $req)
    {
        try {
            $table_name = 'tra_variationsummary_guidelinesconfig';
            $results = array();
            $records = DB::table($table_name . ' as t1')
                ->leftJoin('modules as t2', 't1.module_id', 't2.id')
                ->leftJoin('sub_modules as t3', 't1.sub_module_id', 't3.id')
                ->leftJoin('par_sections as t4', 't1.section_id', 't4.id')
                ->leftJoin('par_variation_reportingtypes as t5', 't1.variation_reportingtype_id', 't5.id')
                ->leftJoin('par_variation_subdescription as t6', 't1.variation_subdescription_id', 't6.id')
                ->leftJoin('par_variation_description as t7', 't1.variation_description_id', 't7.id')
                ->leftJoin('par_variation_categories as t8', 't7.variation_category_id', 't8.id')
                ->leftJoin('par_variation_subcategories as t9', 't7.variation_subcategory_id', 't9.id')
                ->leftJoin('par_product_categories as t10', 't1.product_category_id', 't10.id')

                ->select('t1.*', 't8.name as variation_category', 't7.variation_subcategory_id', 't7.variation_category_id', 't9.name as variation_subcategory', 't1.id as variationsummary_guidelinesconfig_id', 't2.name as module_name', 't3.name as sub_module_name', 't4.name as section_name', 't10.name as product_category', 't5.name as variation_reportingtype', 't6.name as variation_subdescription', 't7.name as variation_description', DB::raw("(SELECT group_concat(concat(code,': ', name)  SEPARATOR '<br/> <br/>') AS variationconditions_detail_id FROM tra_variationconfigconditions_details q left join par_variationconditions_details j on q.variationconditions_detail_id = j.id WHERE variationsummary_guidelinesconfig_id =t1.id) as variationconditions_detailsdata , (SELECT group_concat(concat(code,': ', name) SEPARATOR '<br/> <br/>') AS variationsupporting_datadoc_id  FROM tra_variationconfigsupporting_datadocs k left join par_variationsupporting_datadocs l on k.variationsupporting_datadoc_id =l.id WHERE variationsummary_guidelinesconfig_id =t1.id) as variationsupporting_datadocs"))
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
                'results' => $results
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
    public function getVariationSupportingDataDetails(Request $req)
    {
        try {
            $variationsummary_guidelinesconfig_id = $req->variationsummary_guidelinesconfig_id;

            $records = DB::table('tra_variationconfigsupporting_datadocs as t1')
                ->join('par_variationsupporting_datadocs as t2', 't1.variationsupporting_datadoc_id', 't2.id')
                ->select('t2.*')
                ->where('variationsummary_guidelinesconfig_id', $variationsummary_guidelinesconfig_id)
                ->get();
            $res = array(
                'success' => true,
                'results' => $records
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
    public function getVariationConditionsDetails(Request $req)
    {
        try {

            $variationsummary_guidelinesconfig_id = $req->variationsummary_guidelinesconfig_id;

            $records = DB::table('tra_variationconfigconditions_details as t1')
                ->join('par_variationconditions_details as t2', 't1.variationconditions_detail_id', 't2.id')
                ->select('t2.*')
                ->where('variationsummary_guidelinesconfig_id', $variationsummary_guidelinesconfig_id)
                ->get();
            $res = array(
                'success' => true,
                'results' => $records
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
