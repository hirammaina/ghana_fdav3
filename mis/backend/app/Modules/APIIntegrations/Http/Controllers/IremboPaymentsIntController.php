<?php

namespace App\Modules\APIIntegrations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

use App\traderAccount;

use App\Http\Controllers\Auth;

use Illuminate\Support\Carbon;

use GuzzleHttp\Client as Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use App\Modules\Reports\Traits\ReportsTrait;

class IremboPaymentsIntController extends Controller
{
	
	protected $mis_app_id;
	protected $mis_app_client;
	protected $external_api_client;

	protected $irembopayment_url;
	protected $irembopay_secretkey;
	
	use ReportsTrait;
	public function __construct(Request $req)
    {
			$this->irembopayment_url = Config('constants.irembopay.irembopayment_url'); 
			$this->irembopay_secretkey = Config('constants.irembopay.irembopay_secretkey'); 
			
	}
	function generateUniqueNo(){
				//use the php mt_rand ( int $min , int $max ) function 
				$identification_no = mt_rand(10000000,999999999);
				return $identification_no;
		
		
	}
	
function getCurrencyname($currency_id){
				$currency_name = '';
				$rec = DB::table('par_currencies')
											->where(array('id'=>$currency_id))
											->first();

				if($rec){
							
						$currency_name = $rec->name;
								
				}
			return $currency_name;

}
		function validateEmail($email_address){
			$email_address = preg_replace('/\s+/', '', $email_address);
			// Check the formatting is correct
			if(filter_var($email_address, FILTER_VALIDATE_EMAIL) === false){
				$email_address = '';
			}
			return $email_address;
			
		}function validatePhoneNo($telephone){
				//remove white spaces
				$telephone = preg_replace('/\s+/', '', $telephone);
				$tel_value = '';
				$telephone = trim($telephone);
				//echo $telephone;
				$firstCharacter = substr($telephone, 0, 1);
				$tel_value = '';
				if($firstCharacter == '0'){
					//check the string size
					if(strlen($telephone) == 10){
						
						$tel_value = $telephone;
					}
					
				}
				else if($firstCharacter == '+'){
					
					$telephone = ltrim ($telephone,'+');
					if(strlen($telephone) == 12){
						
						$tel_value = $telephone;
					}
					
				}
				
				return $tel_value;
			
		}
		
		public function iremboFuncInvoiceSubmission(Request $req){
				$invoice_id = $req->invoice_id;
					iremboFuncInvoiceSubmission($invoice_id);
				$res = array();
				

		}
			public function iremboFuncGetInvoiceSubmission(Request $req){
					$invoice_id = $req->invoice_id;
					$response = $this->iremboFuncGetSubmittedInvoice($invoice_id);
	
				 return response()->json($response);


		}
		public function onApplicationInvoicePaymentConfirmation(Request $req){
					$invoice_id = $req->invoice_id;
					$invoice_record = DB::table('tra_invoice_details as t1')
							->join('tra_application_invoices as t2', 't1.invoice_id', 't2.id')
							->select(DB::raw("sum(total_element_amount) as total_element_amount"))
							->where('t2.invoice_no',$invoice_id)
							->first();
						
					if($invoice_record){
						$total_element_amount = $invoice_record->total_element_amount;
						if($total_element_amount ==0){
							$response = array('success'=>true, 'message'=>'Application Is Exempted from Payment, Proceed with the Application Submission');
						
						}else{
							$response = $this->iremboFuncGetSubmittedInvoice($invoice_id);
						
							
						}
						
					}
					else{
						
						$response = array('success'=>true, 'message'=>'Invoice Not Found or generated');
					}
					
	
        return response()->json($response);


		}	public function onGroupApplicationInvoicePaymentConfirmation(Request $req){
					$group_application_code = $req->group_application_code;
					$group_invoice_no = $req->group_invoice_no;
					
					$response = $this->iremboGroupedAppFuncGetSubmittedInvoice($group_application_code,$group_invoice_no);
	
        return response()->json($response);


		}
	
function iremboGroupedAppFuncGetSubmittedInvoice($group_application_code,$group_invoice_no){
				
					$client = new Client([
						'base_uri' => $this->irembopayment_url,
							'headers' => [
								'Accept' => 'application/json',
								'Content-Type' => 'application/json',
								'irembopay-secretkey' => $this->irembopay_secretkey,

							]
						]);
			try{
				$table_name = 'tra_groupedapplication_invoices';
				
				$inv_record  = DB::table('tra_iremboinvoices_information as t1')
									->join('tra_groupedapplication_invoices as t2', 't1.rfdaInvoiceNo', 't2.group_invoice_no')
									->where(array('t2.group_invoice_no'=>$group_invoice_no, 't2.group_application_code'=>$group_application_code))
									->first();
			
				if($inv_record){
					$group_invoice_no = $inv_record->group_invoice_no;
					$group_application_code = $inv_record->group_application_code;
					$response = $client->request('GET', $this->irembopayment_url.'invoices/'.$group_invoice_no, ['exceptions' => false]);
					$success = false;
					$status_code = $response->getStatusCode();
					$func_reresponse = json_decode((string)$response->getBody());
					saveIrembopaymentApplicationResponses('iremboFuncGetSubmittedInvoice', $func_reresponse, $group_invoice_no, $status_code);
						
					if ($status_code == 200 || $status_code == 201) {
							$invoice_record =  $func_reresponse->data;
							$paymentStatus =  $invoice_record->paymentStatus;
							$iremboInvoiceNumber =  $invoice_record->invoiceNumber;
							$group_invoice_no =  $invoice_record->transactionId;
							$paid_amount =  $invoice_record->amount;
							$type =  $invoice_record->type;
							
							//check the data records
							$invoice_respdata = funcInvoiceDataPayment($invoice_record);
							
							
							
							$where_iremboinv = array('iremboInvoiceNumber'=> $invoice_record->invoiceNumber,
												  'rfdaInvoiceNo'=> $invoice_record->transactionId);	
							
							if($paymentStatus == 'PAID'){
									if($type == 'BATCH'){
										//for payments allocations  tra_batchpayments_details
										$paymentReference =  $invoice_record->paymentReference;
										$paymentMethod =  $invoice_record->paymentMethod;
										$currency =  $invoice_record->currency;

										$payment_mode_id = getSingleRecordColValue('par_payment_modes', array('name'=>$paymentMethod),'id');
										$currency_id = getSingleRecordColValue('par_currencies', array('code'=>$currency),'id');

										$invoice_rec = DB::table('tra_iremboinvoices_information as t1')
												->join('tra_groupedapplication_invoices as t2','t1.rfdaInvoiceNo', 't2.group_invoice_no')
												->leftJoin('wb_trader_account as t3','t2.applicant_id', 't3.id')
												->select('t2.id as group_invoice_id','t2.*', 't1.*', 't3.name as applicant_name')
												->where(array('t1.iremboInvoiceNumber'=>$iremboInvoiceNumber,'t2.group_application_code'=>$group_application_code, 't1.rfdaInvoiceNo'=>$group_invoice_no))
												->first();
										
										if($invoice_rec){
											$group_invoice_id = $invoice_rec->group_invoice_id;
											
											$check_payment = array('group_invoice_id'=>$group_invoice_id,'group_invoice_id'=>$group_invoice_id, 'amount_paid'=>$paid_amount, 'trans_ref'=>$paymentReference);
											
								
											$payment_record = DB::table('tra_batchpayments_details')
																	->where($check_payment)
																	->count();
											//chec the payment details 
											if($payment_record == 0){
												$payment_record = (object)array(
													'group_application_code'=>$invoice_rec->group_application_code,
													'amount_paid'=>$paid_amount,
													'trans_ref'=>$paymentReference,
													'iremboInvoiceNumber'=>$iremboInvoiceNumber,
													'currency_id'=>$currency_id,
													'applicant_id'=>$invoice_rec->applicant_id,
													'section_id'=>$invoice_rec->section_id,
													'module_id'=>$invoice_rec->module_id,
													'sub_module_id'=>$invoice_rec->sub_module_id,
													'group_invoice_id'=>$group_invoice_id,
													'applicant_name'=>$invoice_rec->applicant_name,
													'payment_mode_id'=>$payment_mode_id,
												);
												$func_reresponse = $this->saveGroupedApplicationPaymentDetails($payment_record,$group_application_code);
												
												DB::table('tra_iremboinvoices_information as t1')
													->where($where_iremboinv)
													->update($invoice_respdata);
												if($func_reresponse['success']){
													$func_reresponse = array('success'=>true, 'message'=>'Payment has already been received for the said Bill:');

												}
											}
											else{

												$func_reresponse = array('success'=>true, 'message'=>'Payment has already been received for the said Bill:'.$invoice_no);

											}
										}else{
											$func_reresponse = array('success'=>false, 'message'=>'Bill not found in the system Bill No: '.$invoice_no);
										}	
										
										
										
									}
									else{
										
										$paymentReference =  $invoice_record->paymentReference;
										$paymentMethod =  $invoice_record->paymentMethod;
										$currency =  $invoice_record->currency;
 $invoice_no = $invoice_record->transactionId;
										$payment_mode_id = getSingleRecordColValue('par_payment_modes', array('name'=>$paymentMethod),'id');
										$currency_id = getSingleRecordColValue('par_currencies', array('code'=>$currency),'id');

										$invoice_rec = DB::table('tra_iremboinvoices_information as t1')
												->join('tra_application_invoices as t2','t1.rfdaInvoiceNo', 't2.invoice_no')
												->leftJoin('wb_trader_account as t3','t2.applicant_id', 't3.id')
												->select('t2.id as invoice_id','t2.*', 't1.*', 't3.name as applicant_name')
												->where(array('t1.iremboInvoiceNumber'=>$iremboInvoiceNumber, 'rfdaInvoiceNo'=>$invoice_no))
												->first();
										
										if($invoice_rec){
											$invoice_id = $invoice_rec->invoice_id;
											
											$check_payment = array('invoice_id'=>$invoice_id, 'amount_paid'=>$paid_amount, 'trans_ref'=>$paymentReference);
								
											$payment_record = DB::table('tra_payments')
																	->where($check_payment)
																	->count();
											//chec the payment details 
											if($payment_record == 0){
												$payment_record = (object)array(
													'application_id'=>$invoice_rec->application_id,
													'application_code'=>$invoice_rec->application_code,
													'reference_no'=>$invoice_rec->reference_no,
													'tracking_no'=>$invoice_rec->tracking_no,
													'amount_paid'=>$paid_amount,
													'trans_ref'=>$paymentReference,
													'iremboInvoiceNumber'=>$iremboInvoiceNumber,
													'currency_id'=>$currency_id,
													'applicant_id'=>$invoice_rec->applicant_id,
													'section_id'=>$invoice_rec->section_id,
													'module_id'=>$invoice_rec->module_id,
													'sub_module_id'=>$invoice_rec->sub_module_id,
													'invoice_id'=>$invoice_id,
													'applicant_name'=>$invoice_rec->applicant_name,
													'payment_mode_id'=>$payment_mode_id,
												);
												$func_reresponse = $this->saveApplicationPaymentDetails($payment_record);
												DB::table('tra_iremboinvoices_information as t1')
													->where($where_iremboinv)
													->update($invoice_respdata);
												if($func_reresponse['success']){
													$func_reresponse = array('success'=>true, 'message'=>'Payment has already been received for the said Bill:'.$invoice_no);

												}
											}
											else{

												$func_reresponse = array('success'=>true, 'message'=>'Payment has already been received for the said Bill:'.$invoice_no);

											}
										}else{
											$func_reresponse = array('success'=>false, 'message'=>'Bill not found in the system Bill No: '.$invoice_no);
										}	
										
										
									}
									
							}
							else{
								
								$func_reresponse = array('success'=>false, 'message'=>'The Payment have not been received for the bill '.$invoice_no);			

							}
							
					}
					else{
						$func_reresponse = array('success'=>false,'message'=>'Connection Problem', 'status_code'=>$status_code);
							
					}

				}else{
					$func_reresponse = array('success'=>false,'message'=>'Invoice no found');
				}
						
			} catch (TooManyRedirectsException $e) {
						// handle too many redirects
			} catch (ClientException | ServerException $e) {
					// ClientException is thrown for 400 level errors if the http_errors request option is set to true.
					// ServerException is thrown for 500 level errors if the http_errors request option is set to true.
					if ($e->hasResponse()) {
					   // is HTTP status code, e.g. 500 
						$response = $e->getResponse();
					   $statusCode = $e->getResponse()->getStatusCode();
						$errorMessage = $e->getMessage();
						$func_reresponse = json_decode((string)$response->getBody());
						saveIrembopaymentApplicationResponses('iremboFuncGetSubmittedInvoice', $func_reresponse, $invoice_id, $statusCode, $errorMessage);
					}
			} catch (ConnectException $e) {
					// ConnectException is thrown in the event of a networking error.
					if ($e->hasResponse()) {
						$response = $e->getResponse();
						$statusCode = $e->getResponse()->getStatusCode();
						$errorMessage = $e->getMessage();
						$func_reresponse = json_decode((string)$response->getBody());
						saveIrembopaymentApplicationResponses('iremboFuncGetSubmittedInvoice', $func_reresponse, $invoice_id, $statusCode, $errorMessage);
					}
					
			} catch (RequestException  $e) {	
						if ($e->hasResponse()) {
						   // is HTTP status code, e.g. 500 
							$response = $e->getResponse();
						   $statusCode = $e->getResponse()->getStatusCode();
							$errorMessage = $e->getMessage();
							$func_reresponse = json_decode((string)$response->getBody());
							saveIrembopaymentApplicationResponses('iremboFuncGetSubmittedInvoice', $func_reresponse, $invoice_id, $statusCode, $errorMessage);
						}
			}
		
			return $func_reresponse;

}
function iremboFuncGetSubmittedInvoice($invoice_id){
				
 
					$client = new Client([
						'base_uri' => $this->irembopayment_url,
							'headers' => [
								'Accept' => 'application/json',
								'Content-Type' => 'application/json',
								'irembopay-secretkey' => $this->irembopay_secretkey,

							]
						]);
			try{

				$inv_record  = DB::table('tra_iremboinvoices_information as t1')
									->join('tra_application_invoices as t2', 't1.rfdaInvoiceNo', 't2.invoice_no')
									->where('t1.rfdaInvoiceNo', $invoice_id)
									->first();
				if($inv_record){
					$response = $client->request('GET', $this->irembopayment_url.'invoices/'.$invoice_id, ['exceptions' => false]);
					$success = false;
					$status_code = $response->getStatusCode();
					$func_reresponse = json_decode((string)$response->getBody());
					saveIrembopaymentApplicationResponses('iremboFuncGetSubmittedInvoice', $func_reresponse, $invoice_id, $status_code);

					if ($status_code == 200 || $status_code == 201) {
							$invoice_record =  $func_reresponse->data;
							$paymentStatus =  $invoice_record->paymentStatus;
							$iremboInvoiceNumber =  $invoice_record->invoiceNumber;
							$invoice_no =  $invoice_record->transactionId;
							$paid_amount =  $invoice_record->amount;
							
							//check the data records
							$invoice_respdata = funcInvoiceDataPayment($invoice_record);
							$where_iremboinv = array('iremboInvoiceNumber'=> $invoice_record->invoiceNumber,
												  'rfdaInvoiceNo'=> $invoice_record->transactionId);	
							
							if($paymentStatus == 'PAID'){
									$paymentReference =  $invoice_record->paymentReference;
									$paymentMethod =  $invoice_record->paymentMethod;
									$currency =  $invoice_record->currency;

									$payment_mode_id = getSingleRecordColValue('par_payment_modes', array('name'=>$paymentMethod),'id');
									$currency_id = getSingleRecordColValue('par_currencies', array('code'=>$currency),'id');

									$invoice_rec = DB::table('tra_iremboinvoices_information as t1')
											->join('tra_application_invoices as t2','t1.rfdaInvoiceNo', 't2.invoice_no')
											->leftJoin('wb_trader_account as t3','t2.applicant_id', 't3.id')
											->select('t2.id as invoice_id','t2.*', 't1.*', 't3.name as applicant_name')
											->where(array('t1.iremboInvoiceNumber'=>$iremboInvoiceNumber, 'rfdaInvoiceNo'=>$invoice_no))
											->first();
									
									if($invoice_rec){
										$invoice_id = $invoice_rec->invoice_id;
										
										$check_payment = array('invoice_id'=>$invoice_id, 'amount_paid'=>$paid_amount, 'trans_ref'=>$paymentReference);
							
										$payment_record = DB::table('tra_payments')
																->where($check_payment)
																->count();
										//chec the payment details 
										if($payment_record == 0){
											$payment_record = (object)array(
												'application_id'=>$invoice_rec->application_id,
												'application_code'=>$invoice_rec->application_code,
												'reference_no'=>$invoice_rec->reference_no,
												'tracking_no'=>$invoice_rec->tracking_no,
												'amount_paid'=>$paid_amount,
												'trans_ref'=>$paymentReference,
												'iremboInvoiceNumber'=>$iremboInvoiceNumber,
												'currency_id'=>$currency_id,
												'applicant_id'=>$invoice_rec->applicant_id,
												'section_id'=>$invoice_rec->section_id,
												'module_id'=>$invoice_rec->module_id,
												'sub_module_id'=>$invoice_rec->sub_module_id,
												'invoice_id'=>$invoice_id,
												'applicant_name'=>$invoice_rec->applicant_name,
												'payment_mode_id'=>$payment_mode_id,
											);
											$func_reresponse = $this->saveApplicationPaymentDetails($payment_record);
											DB::table('tra_iremboinvoices_information as t1')
												->where($where_iremboinv)
												->update($invoice_respdata);
											if($func_reresponse['success']){
												$func_reresponse = array('success'=>true, 'message'=>'Payment has already been received for the said Bill:'.$invoice_no);

											}
										}
										else{

											$func_reresponse = array('success'=>true, 'message'=>'Payment has already been received for the said Bill:'.$invoice_no);

										}
									}else{
										$func_reresponse = array('success'=>false, 'message'=>'Bill not found in the system Bill No: '.$invoice_no);
									}
							}
							else{
								$func_reresponse = array('success'=>false, 'message'=>'The Payment have not been received for the bill '.$invoice_no);			

							}
							
					}
					else{
						$func_reresponse = array('success'=>false,'message'=>'Connection Problem', 'status_code'=>$status_code);
							
					}

				}else{
					$func_reresponse = array('success'=>false,'message'=>'Invoice no found');
				}
						
			} catch (TooManyRedirectsException $e) {
						// handle too many redirects
			} catch (ClientException | ServerException $e) {
					// ClientException is thrown for 400 level errors if the http_errors request option is set to true.
					// ServerException is thrown for 500 level errors if the http_errors request option is set to true.
					if ($e->hasResponse()) {
					   // is HTTP status code, e.g. 500 
						$response = $e->getResponse();
					   $statusCode = $e->getResponse()->getStatusCode();
						$errorMessage = $e->getMessage();
						$func_reresponse = json_decode((string)$response->getBody());
						saveIrembopaymentApplicationResponses('iremboFuncGetSubmittedInvoice', $func_reresponse, $invoice_id, $statusCode, $errorMessage);
					}
			} catch (ConnectException $e) {
					// ConnectException is thrown in the event of a networking error.
					if ($e->hasResponse()) {
						$response = $e->getResponse();
						$statusCode = $e->getResponse()->getStatusCode();
						$errorMessage = $e->getMessage();
						$func_reresponse = json_decode((string)$response->getBody());
						saveIrembopaymentApplicationResponses('iremboFuncGetSubmittedInvoice', $func_reresponse, $invoice_id, $statusCode, $errorMessage);
					}
					
			} catch (RequestException  $e) {	
						if ($e->hasResponse()) {
						   // is HTTP status code, e.g. 500 
							$response = $e->getResponse();
						   $statusCode = $e->getResponse()->getStatusCode();
							$errorMessage = $e->getMessage();
							$func_reresponse = json_decode((string)$response->getBody());
							saveIrembopaymentApplicationResponses('iremboFuncGetSubmittedInvoice', $func_reresponse, $invoice_id, $statusCode, $errorMessage);
						}
			}
		
			return $func_reresponse;

}
function saveGroupedApplicationPaymentDetails($payment_record,$group_application_code){
	 try {
		
				$user_id = 0;
				$currency_id = $payment_record->currency_id;
				$receipt_no = date('Y').'102'.generateReceiptNo($user_id);
				$exchange_rate = getSingleRecordColValue('par_exchange_rates', array('currency_id' => $currency_id), 'exchange_rate');
				
				$payment_record->group_receipt_no = $receipt_no;
				$payment_record->exchange_rate = $exchange_rate;
				$payment_record->created_on = Carbon::now();
				
				$res = insertRecord('tra_batchpayments_details', (array)$payment_record, 0);
				
				if($res['success']){
					$where = array('t2.group_application_code'=>$group_application_code);
					
					$invoice_records = DB::table('tra_application_invoices as t1')
											->join('tra_groupedapplication_invoices as t2', 't1.group_invoice_id', 't2.id')
											->join('tra_invoice_details as t3', 't1.id', 't3.invoice_id')
											->select(DB::raw("t1.*,t1.id as invoice_id, t3.paying_currency_id, sum(t3.total_element_amount) as equivalent_amount"))
											->where($where)
											->groupBy('t1.id')
											->get();
					foreach($invoice_records as $invoice_record){
							$application_id = $invoice_record->application_id;
							$application_code = $invoice_record->application_code;
							$amount = $invoice_record->equivalent_amount;
							$applicant_id = $invoice_record->applicant_id;
							
							$section_id = $invoice_record->section_id;
							$module_id = $invoice_record->module_id;
							$sub_module_id = $invoice_record->sub_module_id;
							$invoice_id = $invoice_record->invoice_id;
							$reference_no = $invoice_record->reference_no;
							$tracking_no = $invoice_record->tracking_no;
							
							$applicant_name = $invoice_record->applicant_name;
							
							$currency_id = $payment_record->currency_id;
							$payment_mode_id = $payment_record->payment_mode_id;
							$trans_ref = $payment_record->trans_ref;
								
							$non_gepg_reason = 'Online E-Payment';//invoice_no
							$receipt_no = generateReceiptNo($user_id);
							
							$params = array(
								'application_id' => $application_id,
								'application_code' => $application_code,
								'applicant_name' => $payment_record->applicant_name,
								'amount_paid' => $amount,
								'invoice_id' => $invoice_id,
								'receipt_no' => $receipt_no,
								'tracking_no' => $tracking_no,
								'reference_no' => $reference_no,
								'trans_date' => Carbon::now(),
								'currency_id' => $currency_id,
								'applicant_id' => $applicant_id,
								'section_id' => $section_id,
								'module_id' => $module_id,
								'payment_type_id' => 1,
								'sub_module_id' => $sub_module_id,
								'receipt_type_id' => 1,
								'payment_mode_id' => $payment_mode_id,
								'trans_ref' => $trans_ref,
								'bank_id' => 0,
								'drawer' => $applicant_name,
								'exchange_rate' => $exchange_rate,
								'created_on' => Carbon::now(),
								'created_by' => $user_id
							);
							
							$res = insertRecord('tra_payments', $params, $user_id);

							generatePaymentRefDistribution($invoice_id, $res['record_id'], $amount, $currency_id, $user_id);
						
					}
				}
        }  catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), 0);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), 0);
        }
		return $res;
	//get all the invoices for the batch and alocate the amounts 
	
	
}
function saveApplicationPaymentDetails($payment_record)
    {
		
        $user_id = 0;
        $application_id = $payment_record->application_id;
        $application_code = $payment_record->application_code;
        $amount = $payment_record->amount_paid;
        $currency_id = $payment_record->currency_id;
        $applicant_id = $payment_record->applicant_id;
        $section_id = $payment_record->section_id;
        $module_id = $payment_record->module_id;
        $sub_module_id = $payment_record->sub_module_id;
        $invoice_id = $payment_record->invoice_id;
        $applicant_name = $payment_record->applicant_name;
        $payment_mode_id = $payment_record->payment_mode_id;
        $trans_ref = $payment_record->trans_ref;

        $reference_no = $payment_record->reference_no;
        $tracking_no = $payment_record->tracking_no;
        $non_gepg_reason = 'Online E-Payment';
        $receipt_no = generateReceiptNo($user_id);
        $exchange_rate = getSingleRecordColValue('par_exchange_rates', array('currency_id' => $currency_id), 'exchange_rate');
        $params = array(
            'application_id' => $application_id,
            'application_code' => $application_code,
            'applicant_name' => $payment_record->applicant_name,
            'amount_paid' => $amount,
            'invoice_id' => $invoice_id,
            'receipt_no' => $receipt_no,
            'tracking_no' => $tracking_no,
            'reference_no' => $reference_no,
            'trans_date' => Carbon::now(),
            'currency_id' => $currency_id,
            'applicant_id' => $applicant_id,
            'section_id' => $section_id,
            'module_id' => $module_id,
            'payment_type_id' => 1,
            'sub_module_id' => $sub_module_id,
            'receipt_type_id' => 1,
            'payment_mode_id' => $payment_mode_id,
            'trans_ref' => $trans_ref,
            'bank_id' => 0,
            'drawer' => $applicant_name,
            'exchange_rate' => $exchange_rate,
            'created_on' => Carbon::now(),
            'created_by' => $user_id
        );

        try {
            $res = insertRecord('tra_payments', $params, $user_id);

           generatePaymentRefDistribution($invoice_id, $res['record_id'], $amount, $currency_id, $user_id);
           
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), 0);

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), 0);
        }
        return $res;
    }
	public function iremboGetPaymentNotifications(Request $req){
		
			try{
				$func_reresponse = $req->all();

				saveIrembopaymentApplicationResponses('iremboGetPaymentNotifications', $func_reresponse, 00,00);
				
				$response = $this->iremboGetPaymentNotificationsREceive($func_reresponse,$req);
				
					
				//$this->irembopay_secretkey
				//ash-based message authentication code (HMAC) with SHA-256
					//HMAC_SHA256 is a function to Compute an HMAC with the SHA256 hash function.
				//<Merchant_Secret_Key> is the secret key configured for the merchan
				//Payload_To_Hash> is the concatenation of the timestamp, the character “#” and the request body.


			} catch (\Exception $exception) {
				$response = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), 0);

			} catch (\Throwable $throwable) {
				$response = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), 0);
			}
			
			return $response;


	}
	function iremboGetPaymentNotificationsREceive($func_reresponse,$req){
	$func_reresponse = (object)$func_reresponse;
	
	$success = $func_reresponse->success;
	
	if($success){
			$invoice_record =  (object)$func_reresponse->data;
			$paymentStatus =  $invoice_record->paymentStatus;
		    $invoice_respdata = funcInvoiceDataPayment($invoice_record);
			$where_iremboinv = array('iremboInvoiceNumber'=> $invoice_record->invoiceNumber,
									  'rfdaInvoiceNo'=> $invoice_record->transactionId);	
									  $invoice_no = $invoice_record->transactionId;
									  $iremboInvoiceNumber = $invoice_record->invoiceNumber;
			if($paymentStatus == 'PAID'){  
			
										$paymentReference =  $invoice_record->paymentReference;
										$paymentMethod =  $invoice_record->paymentMethod;
										$currency =  $invoice_record->currency;
										$paid_amount =  $invoice_record->amount;

										$payment_mode_id = getSingleRecordColValue('par_payment_modes', array('name'=>$paymentMethod),'id');
										$currency_id = getSingleRecordColValue('par_currencies', array('code'=>$currency),'id');

										$invoice_rec = DB::table('tra_iremboinvoices_information as t1')
												->join('tra_application_invoices as t2','t1.rfdaInvoiceNo', 't2.invoice_no')
												->leftJoin('wb_trader_account as t3','t2.applicant_id', 't3.id')
												->select('t2.id as invoice_id','t2.*', 't1.*', 't3.name as applicant_name')
												->where(array('t1.iremboInvoiceNumber'=>$iremboInvoiceNumber, 'rfdaInvoiceNo'=>$invoice_no))
												->first();
										
										if($invoice_rec){
											$invoice_id = $invoice_rec->invoice_id;
											
											$check_payment = array('invoice_id'=>$invoice_id, 'amount_paid'=>$paid_amount, 'trans_ref'=>$paymentReference);
								
											$payment_record = DB::table('tra_payments')
																	->where($check_payment)
																	->count();
											//chec the payment details 
											if($payment_record == 0){
												
												$payment_record = (object)array(
													'application_id'=>$invoice_rec->application_id,
													'application_code'=>$invoice_rec->application_code,
													'reference_no'=>$invoice_rec->reference_no,
													'tracking_no'=>$invoice_rec->tracking_no,
													'amount_paid'=>$paid_amount,
													'trans_ref'=>$paymentReference,
													'iremboInvoiceNumber'=>$iremboInvoiceNumber,
													'currency_id'=>$currency_id,
													'applicant_id'=>$invoice_rec->applicant_id,
													'section_id'=>$invoice_rec->section_id,
													'module_id'=>$invoice_rec->module_id,
													'sub_module_id'=>$invoice_rec->sub_module_id,
													'invoice_id'=>$invoice_id,
													'applicant_name'=>$invoice_rec->applicant_name,
													'payment_mode_id'=>$payment_mode_id,
												);
												$func_reresponse = $this->saveApplicationPaymentDetails($payment_record);
												DB::table('tra_iremboinvoices_information as t1')
													->where($where_iremboinv)
													->update($invoice_respdata);
													
													//send notifications 
												if($func_reresponse['success']){
													$receipt_id = $func_reresponse['record_id'];
													
													$payment_record = DB::table('tra_payments')->where('id',$receipt_id)->first();
												
													if($payment_record){
														$receipt_no = $payment_record->receipt_no;
														$applicant_id = $payment_record->applicant_id;
														$payment_id = $payment_record->id;
														$module_id = $payment_record->module_id;
														$application_code = $payment_record->application_code;
														$application_id = $payment_record->application_id;
														
														$message = "Kindly find attached Payment Receipt as per the following details:";
														$message .= "<br/>Application No:".$invoice_rec->tracking_no;
														$message .= "<br/>Receipt No:".$receipt_no;
														$message .= "<br/>Payment Date:".Carbon::now();
																			
														$trader_record = getSingleRecord('wb_trader_account', array('id'=>$applicant_id),'mysql');
														
														if($trader_record){
															$attachement_name = 'Payment Receipt.pdf';
															$document_root = $_SERVER['DOCUMENT_ROOT'];
															$attachement =  $document_root.'/'.Config('constants.dms.system_uploaddirectory').date('Y-m-d H:i:s').'Receipt'.'.pdf';
															$request = new Request([
																'table_name'   => 'unit test',
																'application_code' => $application_code,
																'application_id' => $application_id,
																'module_id' => $module_id
															]);
															$this->printApplicationReceipt($payment_id,$request, 'notify',$attachement);
															
															$response = sendMailNotification($trader_record->name, $trader_record->email,$subject,$message,'','',$attachement,$attachement_name);
														
																		$data = array('receipt_no'=>$receipt_no, 
																				'application_code'=>$application_code,
																				'trader_name'=>$trader_record->name,
																				'notification_sent_on'=>Carbon::now(),
																				'notification_sent_to'=>$trader_record->email,
																				'notification_status_id'=>2,
																				'created_on'=>Carbon::now()
																			);
																	   
																		DB::table('tra_paymentinvoices_notifications')->insert($data);

																		$where = array('id'=>$payment_id);
																		$data_update = array('notification_status_id'=>2, 'dola'=>Carbon::now());
																		DB::table('tra_payments as t')->where($where)->update($data_update);
															unlink($attachement);
														}
														
														
													}
													
													
													$func_reresponse = array('success'=>true, 'message'=>'Payment has been received for the said Bill:'.$invoice_no);

												}else{
													$func_reresponse = array('success'=>false, 'message'=>$func_reresponse['message']);

													
												}
											}
											else{

												$func_reresponse = array('success'=>true, 'message'=>'Payment has already been received for the said Bill:'.$invoice_no);

											}
										}else{
											$func_reresponse = array('success'=>false, 'message'=>'Bill not found in the system Bill No: '.$invoice_no);
										}	
										
			
			
			
			
			}
	}
	else{
		$func_reresponse = array('success'=>false, 'The Payment details has a error status');
		
	}
	return $func_reresponse;
}
}
