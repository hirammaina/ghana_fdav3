<?php

namespace Modules\APIIntegrations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class NewIntegrationsController extends Controller
{

// protected $user_id;

// public function __construct(Request $req)
//     {
//         $is_mobile = $req->input('is_mobile');
//         if (is_numeric($is_mobile) && $is_mobile > 0) {
//             $this->user_id = $req->input('user_id');
//         } else {
//             $this->middleware(function ($request, $next) {
//                 if (!\Auth::check()) {
//                     $res = array(
//                         'success' => false,
//                         'message' => '<p>NO SESSION, SERVICE NOT ALLOWED!!<br>PLEASE RELOAD THE SYSTEM!!</p>'
//                     );
//                     echo json_encode($res);
//                     exit();
//                 }
//                 $this->user_id = \Auth::user()->id;
//                 return $next($request);
//             });
//         }
//  }
    
public function getCompanyDetails(Request $req){
 try {
    $user_id = $this->user_id;
    $company_registration_no = $req->company_registration_no;
    $where = array(
            'company_registration_no' => $company_registration_no
    );
    $res = array();
    $table_name = 'tra_premise_company_details';
     if (isset($company_registration_no) && $company_registration_no != "") {
        if (recordExists($table_name, $where)) {
            $previous_data = getPreviousRecords($table_name, $where);
            if ($previous_data['success'] == false) {
                return $previous_data;
            }
            return $previous_data;
        }else{
          $token = $this->generateAccessToken();
          $obrs_configs = $this->getObrsConfigurations();
          $company_details=$this->curl_post($token,$obrs_configs->companydetails_url,array(
          'brn'=> trim($company_registration_no)
            //'brn'=> '80034506867656'
          ));
          $company_details = json_decode($company_details,true);
          if (!isset($company_details['company'])) {
            $res = array(
                    'success' => false,
                    'message' => $company_details['error']
            );
            echo json_encode($res);
            exit();
          }

            if (
                isset($company_details['company']) &&
                $company_details['company']['business_reg_no'] !== '' &&
                $company_details['company']['entity_name'] !== '' &&
                $company_details['company']['type'] !== '' &&
                $company_details['company']['subtype'] !== '' &&
                $company_details['company']['incorporation_date'] !== '' &&
                $company_details['company']['reg_date'] !== '' &&
                $company_details['company']['reg_status'] !== ''
            ) {
           $company_data = array(
               'company_registration_no' =>$company_details['company']['business_reg_no'],
                'name' => $company_details['company']['entity_name'],  
                'registration_date' => $company_details['company']['incorporation_date'],
                'type' => $company_details['company']['type'],
                'subtype' => $company_details['company']['subtype'],
                'reg_status' => $company_details['company']['reg_status']
            );
            $res = insertRecord($table_name, $company_data, $user_id);
            if ($res['success'] == true) {
              $res=getPreviousRecords($table_name, $where);
             }
             }
            else{
            $res = array(
                    'success' => false,
                    'message' => '<p>No Data found for this brn Number!!</p>'
            );
            echo json_encode($res);
            exit();
           
           }
         }
       }
       } catch (\Exception $exception) {
            sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
            $res = array('success'=> false, 'message'=>$exception->getMessage(), 'source'=> 'mis');

        } catch (\Throwable $throwable) {
            sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
            $res = array('success'=> false, 'message'=>$throwable->getMessage(), 'source'=> 'mis');
        }
        return \response()->json($res);
    }

    public function getCompanyShareholders(Request $req)
    {
      $token = $this->generateAccessToken();
      $obrs_configs = $this->getObrsConfigurations();
      $company_shareholder=$this->curl_post($token,$obrs_configs->shareholders_url,array(
      'brn'=> '80034447904226'
      ));
      return $company_shareholder;
    }

    public function getObrsConfigurations(){
        $obrs_configs = DB::table('tra_obrs_configurations')->first();
        return $obrs_configs;

    }

    public function generateAccessToken()
    {
        $obrs_configs = $this->getObrsConfigurations();
        $baseurl = $obrs_configs->baseurl; 
        $endpoint = $obrs_configs->authenticate_url;
        $payload = array(
            'appKey' => $obrs_configs->consumer_key,
            'appSecret' => $obrs_configs->consumer_secret, 
        );

    $headers = array(
        'Content-Type: application/json',
        'Accept: application/json'
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $baseurl . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => $headers,
        
    ));

    $curl_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($status !== 200) {
        $res = array(
                'success' => false,
                'message' => '<p>UNABLE TO UTHENTICATE!!<br>PLEASE TRY AGAIN!!</p>'
        );
        echo json_encode($res);
        exit();
    }
    $result = json_decode($curl_response);
    $access_token = $result->access_token;
 
    return $access_token;
    }
     
    public function curl_post($token,$endpoint,$payload){
         $obrs_configs = $this->getObrsConfigurations();
         $baseurl = $obrs_configs->baseurl; 
         $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$token,
        'Accept: application/json'
        );
         $curl = curl_init();
         curl_setopt_array($curl,array(
         CURLOPT_URL=>$baseurl . $endpoint,
         CURLOPT_RETURNTRANSFER=>true,
         CURLOPT_CUSTOMREQUEST=>"POST",
         CURLOPT_POSTFIELDS=>json_encode($payload),
         CURLOPT_SSL_VERIFYPEER=>false,
         CURLOPT_SSL_VERIFYHOST=>false,
         CURLOPT_HTTPHEADER=>$headers
         
         ));
         $curl_response = curl_exec($curl);
         $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
         return $curl_response;
     }

      //vigiflow
    public function generateUploadableE2BFile(Request $req){
        try {
            // $application_code = $req->application_code;
            $selected = json_decode($req->selected);
            //log export
            $log = DB::table('tra_pv_vigiflow_export_log')->orderBy('id', 'DESC')->first();
            if($log){
                $reference = 'NDA-IRIMS-Export-0'.$log->id;
            }else{
                $reference = 'NDA-IRIMS-Export-001';
            }
            $data = array(
                'date_generated' => Carbon::now(),
                'application_codes' => json_encode($selected),
                'reference' => $reference,
                'generated_by' => $this->user_id
            );
            $log_res = insertRecord('tra_pv_vigiflow_export_log', $data);
            if(!isset($log_res['record_id'])){
                return $log_res;
            }
            //messagedate recieved
            $messagedate =strtotime(date("Y/m/d h:i:sa")); //gets dates instance
            $year = date("Y", $messagedate);
            $month = date("m", $messagedate);
            $day = date("d", $messagedate);
            $hr = date("H", $messagedate);
            $min = date("i", $messagedate);
            $sec = date("s", $messagedate);
            $messagedate_fmt = $year."".$month."".$day."".$hr."".$min."".$sec;
            $trans_date_fmt = $year."".$month."".$day;

            //start creating xml
            $xml_string = "<?xml version='1.0' encoding='UTF-8'?>
                <!DOCTYPE ichicsr SYSTEM 'http://eudravigilance.ema.europa.eu/dtd/icsr21xml.dtd'>
                <ichicsr lang='en'>";
             /*
                ichicsrmessageheader
            */
            $xml_string .="
            <ichicsrmessageheader>
                <messagetype>ichicsr</messagetype>
                <messageformatversion>2.1</messageformatversion>
                <messageformatrelease>2.0</messageformatrelease>
                <messagenumb>".$reference."</messagenumb>
                <messagesenderidentifier>NDA</messagesenderidentifier>
                <messagereceiveridentifier>IRIMS</messagereceiveridentifier>
                <messagedateformat>204</messagedateformat>
                <messagedate>".$messagedate_fmt."</messagedate>
            </ichicsrmessageheader>";

            foreach ($selected as $application_code) {
                //get record
                $report = DB::table('tra_pv_applications as t1')
                    ->leftJoin('tra_application_documents as t2', 't1.application_code', 't2.application_code')
                    ->leftJoin('par_titles as t3', 't1.title_id', 't3.id')
                    ->select('t1.*', DB::raw("CASE WHEN t2.id IS NUll THEN 2 ELSE 1 End has_documents, t3.name as patient_title,CASE WHEN t1.seriousness_id IS NUll THEN 2 ELSE 3 End termhighlighted"))
                    ->where('t1.application_code', $application_code)
                    ->first();
                //seriousness
                if(validateIsNumeric($report->seriousness_id)){
                    $serious = 1;
                    $seriousness_id = $report->seriousness_id;
                    $is_lifethreatening = 2;
                    $is_hospitalized = 2;
                    $is_disabling = 2;
                    $is_congenital = 2;
                    $is_other_serious = 2;
                    $is_death = 2;

                    switch ($seriousness_id) {
                        case 1:
                            $is_lifethreatening = 1;
                            break;
                        case 2:
                            $is_hospitalized = 1;
                            break;
                        case 3:
                            $is_congenital = 1;
                            break;
                        case 4:
                            $is_disabling = 4;
                            break;
                        case 5:
                           $is_death = 1;
                            break;
                        case 6:
                            $is_other_serious = 1;
                            break;
                    }
                }else{
                    $serious = 2;
                }
                //dates preparation
                //Date recieved
                $date_added =strtotime($report->date_added); //gets dates instance
                $year = date("Y", $date_added);
                $month = date("m", $date_added);
                $day = date("d", $date_added);
                $hr = date("H", $date_added);
                $min = date("i", $date_added);
                $sec = date("s", $date_added);
                $date_added_fmt = $year."".$month."".$day;

                //Date recieved
                $receipt_date =strtotime($report->created_on); //gets dates instance
                $year = date("Y", $receipt_date);
                $month = date("m", $receipt_date);
                $day = date("d", $receipt_date);
                $hr = date("H", $receipt_date);
                $min = date("i", $receipt_date);
                $sec = date("s", $receipt_date);
                $receipt_date_fmt = $year."".$month."".$day;

                //reaction start date
                $reaction_start_date =strtotime($report->reaction_start_date); //gets dates instance
                $year = date("Y", $reaction_start_date);
                $month = date("m", $reaction_start_date);
                $day = date("d", $reaction_start_date);
                $reaction_start_date_fmt = $year."".$month."".$day;

                //reaction start date
                $date_recovered =strtotime($report->date_recovered); //gets dates instance
                $year = date("Y", $date_recovered);
                $month = date("m", $date_recovered);
                $day = date("d", $date_recovered);
                $date_recovered_fmt = $year."".$month."".$day;

                //reaction format
                switch ($report->duration_id) {
                    case 1:
                        $patientonsetageunit = 801;
                        break;
                    case 2:
                        $patientonsetageunit = 802;
                        break;
                    case 3:
                        $patientonsetageunit = 803;
                        break;
                    case 4:
                        $patientonsetageunit = 804;
                        break;
                    case 4:
                        $patientonsetageunit = 805;
                        break;

                }

                //mentration end date
                $last_menstruation_date =strtotime($report->last_menstruation_date); //gets dates instance
                $year = date("Y", $last_menstruation_date);
                $month = date("m", $last_menstruation_date);
                $day = date("d", $last_menstruation_date);
                $last_menstruation_date_fmt = $year."".$month."".$day;

                //report refs
                $reportid = $report->tracking_no;
                /*
                    safetyreport
                */
                $xml_string .= "
                <safetyreport>
                    <safetyreportversion>1</safetyreportversion> 
                    <safetyreportid>".$reportid."</safetyreportid>
                    <primarysourcecountry>UG</primarysourcecountry>
                    <occurcountry>UG</occurcountry>
                    <transmissiondateformat>102</transmissiondateformat>
                    <transmissiondate>".$trans_date_fmt."</transmissiondate>
                    <reporttype>1</reporttype>
                    <serious>".$serious."</serious>
                    <seriousnessdeath>".$is_death."</seriousnessdeath>
                    <seriousnesslifethreatening>".$is_lifethreatening."</seriousnesslifethreatening>
                    <seriousnesshospitalization>".$is_hospitalized."</seriousnesshospitalization>
                    <seriousnessdisabling>".$is_disabling."</seriousnessdisabling>
                    <seriousnesscongenitalanomali>".$is_congenital."</seriousnesscongenitalanomali>
                    <seriousnessother>".$is_other_serious."</seriousnessother>
                    <receivedateformat>102</receivedateformat>
                    <receivedate>".$date_added_fmt."</receivedate>
                    <receiptdateformat>102</receiptdateformat>
                    <receiptdate>".$receipt_date_fmt."</receiptdate>
                    <additionaldocument>".$report->has_documents."</additionaldocument>
                    <fulfillexpeditecriteria>1</fulfillexpeditecriteria>
                    <companynumb>".$reportid."</companynumb>
                    <primarysource>
                        <reportertitle>".$report->professional_title."</reportertitle>
                        <reportergivename>PRIVACY</reportergivename>
                        <reporterfamilyname>PRIVACY</reporterfamilyname>
                        <reporterorganization>PRIVACY</reporterorganization>
                        <reportercountry>UG</reportercountry>
                        <qualification>".$report->professional_qualification_id."</qualification>
                    </primarysource>
                    <sender>
                        <sendertype>2</sendertype>
                        <senderorganization>NDA</senderorganization>
                        <senderdepartment>Product-Safety</senderdepartment>
                        <senderstreetaddress>NDA HQ</senderstreetaddress>
                        <sendercity>Kampala</sendercity>
                        <sendercountrycode>UG</sendercountrycode>
                        <sendertel></sendertel>
                        <sendertelextension></sendertelextension>
                        <sendertelcountrycode></sendertelcountrycode>
                    </sender>
                    <receiver>
                        <receivertype>2</receivertype>
                        <receiverorganization>NDA</receiverorganization>
                        <receivercountrycode>UG</receivercountrycode>
                    </receiver>";

                //patient
                if($report->gender_id == 1){//male
                    $xml_string .= "
                    <patient>
                        <patientinitial>".$report->patient_title."</patientinitial>
                        <patientonsetage>".$report->patient_age."</patientonsetage>
                        <patientonsetageunit>".$patientonsetageunit."</patientonsetageunit>
                        <patientweight>".$report->patient_weight."</patientweight> 
                        <patientsex>".$report->gender_id."</patientsex>
                        <patientmedicalhistorytext>".$report->other_medical_conditions."</patientmedicalhistorytext>";
                }else{ //female
                    $xml_string .= "
                    <patient>
                        <patientinitial>".$report->patient_title."</patientinitial>
                        <patientonsetage>".$report->patient_age."</patientonsetage>
                        <patientonsetageunit>".$patientonsetageunit."</patientonsetageunit>
                        <patientweight>".$report->patient_weight."</patientweight>
                        <patientsex>".$report->gender_id."</patientsex>
                        <lastmenstrualdateformat>102</lastmenstrualdateformat>
                        <patientlastmenstrualdate>".$last_menstruation_date_fmt."</patientlastmenstrualdate>
                        <patientmedicalhistorytext>".$report->other_medical_conditions."</patientmedicalhistorytext>";
                }
                $xml_string .= "<patientdeath>";
                if($report->adr_outcome_id == 7 && $report->date_recovered){
                        $xml_string .= "
                            <patientdeathdateformat>102</patientdeathdateformat>
                            <patientdeathdate>".$date_recovered_fmt."</patientdeathdate>";
                }
                // else if($report->adr_outcome_id == 6 && $report->date_recovered){
                //         $xml_string .= "
                //             <patientdeathdateformat>102</patientdeathdateformat>
                //             <patientdeathdate>".$date_recovered_fmt."</patientdeathdate>";
                // }
                switch ($report->autopsy_done) {
                    case 1:
                        $xml_string .= "<patientautopsyyesno>1</patientautopsyyesno>";
                        break;
                    case 2:
                        $xml_string .= "<patientautopsyyesno>2</patientautopsyyesno>";
                        break;
                    
                    default:
                        $xml_string .= "<patientautopsyyesno>3</patientautopsyyesno>";
                        break;
                }
                $xml_string .= "</patientdeath>";
                //reaction
                $xml_string .="
                <reaction>
                    <primarysourcereaction>".$report->adverse_event."</primarysourcereaction>

                    <reactionmeddraversionllt>21.0</reactionmeddraversionllt>
                    <reactionmeddrallt>10033557</reactionmeddrallt>

                    <termhighlighted>".$report->termhighlighted."</termhighlighted>
                    <reactionstartdateformat>102</reactionstartdateformat>
                    <reactionstartdate>".$reaction_start_date_fmt."</reactionstartdate>";
                if($report->date_recovered){
                   $xml_string .= "<reactionenddateformat>102</reactionenddateformat>
                    <reactionenddate>".$date_recovered_fmt."</reactionenddate>"; 
                }
                    
                $xml_string .= "<reactionoutcome>".$report->termhighlighted."</reactionoutcome>
                </reaction>";

                $investigational_products = DB::table('tra_pv_suspected_drugs')->where('application_code', $report->application_code)->get();
                foreach ($investigational_products as $product) {
                    if($product->is_other_drugs_used == 1){
                        $drugcharacterization = 3;
                    }else{
                        $drugcharacterization = 1;
                    }
                    //drugstartdate
                    $start_date =strtotime($product->start_date); //gets dates instance
                    $year = date("Y", $start_date);
                    $month = date("m", $start_date);
                    $day = date("d", $start_date);
                    $start_date_fmt = $year."".$month."".$day;
                    // drugenddate
                    $end_date =strtotime($product->end_date); //gets dates instance
                    $year = date("Y", $end_date);
                    $month = date("m", $end_date);
                    $day = date("d", $end_date);
                    $end_date_fmt = $year."".$month."".$day;
                    $route_of_administration = getSingleRecordColValue('par_route_of_administration',['id'=>$product->route_of_administration_id], 'name');
                    $dosage_form = getSingleRecordColValue('par_dosage_forms',['id'=>$product->dosage_form_id], 'name');

                    $xml_string .="<drug>
                        <drugcharacterization>".$drugcharacterization."</drugcharacterization>
                        <medicinalproduct>".$product->brand_name."</medicinalproduct>
                        <obtaindrugcountry>UG</obtaindrugcountry>
                        <drugbatchnumb>".$product->batch_no."</drugbatchnumb>
                        <drugauthorizationcountry></drugauthorizationcountry>
                        <drugauthorizationholder></drugauthorizationholder>
                        <drugdosagetext>".$product->dosage." - ".$product->frequency."</drugdosagetext>
                        <drugdosageform>".$dosage_form."( with Route of administration being - ".$route_of_administration." )</drugdosageform>
                        ";
                    if($product->start_date){
                        $xml_string .=
                        "<drugstartdateformat>201</drugstartdateformat>
                        <drugstartdate>".$start_date_fmt."</drugstartdate>
                        ";
                    }
                    if($product->end_date){
                        $xml_string .="
                        <drugenddateformat>201</drugenddateformat>
                        <drugenddate>".$end_date_fmt."</drugenddate>";
                    }
                    $xml_string .=
                        "<actiondrug>".$product->drug_action_id."</actiondrug>              
                        <activesubstance>
                            <activesubstancename></activesubstancename>
                        </activesubstance>
                        
                    </drug>";
                }
               $xml_string .= 
                    "<summary>
                        <narrativeincludeclinical>
                        Treatment sourced 
                        ".$report->treatment.". 
                        Pre-Exisitng conditions 
                        ".$report->other_medical_conditions.".</narrativeincludeclinical>
                    </summary>
                </patient>
            </safetyreport>";
            //update status
            updateRecord('tra_pv_applications', ['application_code' => $application_code], ['is_exported'=>1]);
        }
        $xml_string.="</ichicsr>";
    

    //create a file and add content
    // file_put_contents(storage_path().'/file.xml', $xml_string);
        $response = Response::create($xml_string, 200);
        $response->header('Content-Type', 'text/xml');
        $response->header('Cache-Control', 'public');
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename='.$reportid.'.xml');
        $response->header('Content-Transfer-Encoding', 'binary');
        return $response;

            
        } catch (\Exception $exception) {
            sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
            $res = array('success'=> false, 'message'=>$exception->getMessage(), 'source'=> 'mis');

        } catch (\Throwable $throwable) {
            sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
            $res = array('success'=> false, 'message'=>$throwable->getMessage(), 'source'=> 'mis');
        }
        return \response()->json($res);
    }

}
