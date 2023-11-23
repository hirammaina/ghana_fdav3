<?php
/**
 * Created by PhpStorm.
 * User: Kip
 * Date: 11/20/2018
 * Time: 2:31 PM
 */

namespace App\Modules\Utilities\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

trait UtilitiesTrait
{
    public function getProductInvoiceDetails($application_code,$con)
    {
        $qry = DB::connection($con)->table('tra_product_applications as t1')
            ->join('wf_tfdaprocesses as t2', 't1.process_id', '=', 't2.id')
            ->join('tra_product_information as t3', 't1.product_id', '=', 't3.id')
            ->leftJoin('par_common_names as t5', 't3.common_name_id', '=', 't5.id')
            ->join('modules as t4', 't1.module_id', '=', 't4.id')
            ->select(DB::raw("t1.reference_no,t2.name as process_name,t4.invoice_desc as module_name,
                     CONCAT_WS(', ',t3.brand_name,t5.name) as module_desc"))
            ->where('t1.application_code', $application_code);
        $invoice_details = $qry->first();
        return $invoice_details;
        
    }
}