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
class RRAPaymentsIntController extends Controller
{
	
	protected $mis_app_id;
	protected $mis_app_client;
	protected $external_api_client;

	protected $systemid;
	protected $spcode;
	protected $subspcode;
	protected $gepgurl;
	protected $gepg_port;

	public function __construct(Request $req)
    {
		
			$mis_app_id = Config('constants.api.mis_app_client_id');
			$this->mis_app_client = DB::table('oauth_clients')->where('id', $mis_app_id)->first();
			$external_api_id = Config('constants.api.external_api_client_id');
			$this->external_api_client = DB::table('oauth_clients')->where('id', $external_api_id)->first();

			$this->systemid = Config('constants.gepg.systemid'); 
			$this->spcode = Config('constants.gepg.spcode'); 
			$this->subspcode = Config('constants.gepg.subspcode'); 
			$this->gepgurl = Config('constants.gepg.gepgurl'); 
			$this->gepg_port = Config('constants.gepg.gepg_port'); 
			

	}
	function generateUniqueNo(){
				//use the php mt_rand ( int $min , int $max ) function 
				$identification_no = mt_rand(10000000,999999999);
				return $identification_no;
		
		
	}
	
function funcSavePaymentreferenceno($amount_paid,$reference_no,$tracking_no,$receipt_id,$receipt_no,$invoice_no,$user_id,$currency_id,$exchange_rate){
					$currency_name = $this->getCurrencyname($currency_id);
					//totalinvoice amount  
					$total_invoiceamount =DB::table("tra_application as t1")
																		->table('tra_invoice_details as t2', 't1.id', '=','t2.invoice_id')
																		->select(DB::raw("SUM(t2.total_element_amount*t2.quantity) AS  totalinvoice_amount"))
																		->where(array('invoice_no'=>$invoice_no))
																		->first();
					$sum_invoice = $total_invoiceamount->totalinvoice_amount;

					if($sum_invoice >0){
										$records =DB::table("tra_application as t1")
																							->table('tra_invoice_details as t2', 't1.id', '=','t2.invoice_id')
																							->select(DB::raw("(t2.total_element_amount*t2.quantity) AS  invoice_amount,t2.element_costs_id, t1.*"))
																							->where(array('invoice_no'=>$invoice_no))
																							->get();

										foreach($records as $rec){
													$invoice_amount = $rec->invoice_amount;
													$cost_pecentage = ($invoice_amount/$sum_invoice);
													$element_costs_id = $rec->element_costs_id;
													$amount_paidinv =$amount_paid*$cost_pecentage;
													$data = array('invoice_no'=>$invoice_no,
																				'receipt_id'=>$receipt_id,
																				'currency_id'=>$currency_id,
																				'exchange_rate'=>$exchange_rate,
																				'element_costs_id'=>$element_costs_id,
																				'paid_on'=>Carbon::now(),
																				'amount_paid'=>$amount_paidinv,
																				'created_on'=>Carbon::now(),
																				'created_by'=>$user_id
																);
													insertRecord('payments_references', $data, $user_id);
										}
										//the other integration details 
										$records =DB::table("tra_application as t1")
																->join('tra_invoice_details as t2', 't1.id', '=','t2.invoice_id')
																->join('element_costs as t3', 't2.element_costs_id', '=','t3.invoice_id')
																->leftJoin('par_gl_accounts as t4', 't3.gl_code_id', '=','t4.id')
																->leftJoin('par_zones as t5', 't1.zone_id', '=','t5.id')
																->select(DB::raw("sum(t2.total_element_amount*t2.quantity) AS  invoice_amount,t2.element_costs_id, t1.*, t5.name as zone_name,t4.description as cost_description, t5.epicor_code,t4.code as gl_account_code"))
																->where(array('invoice_no'=>$invoice_no))
																->groupBy('t4.id')
																->get();

										if($records){
													foreach($records as $row){
																$created_on = Carbon::now();
																$cost_pecentage = ($row->invoice_amount/$sum_invoice);
																$amount_paidinv =$amount_paid*$cost_pecentage;

																$pay_record = $this->getPaymentDetails($receipt_no);

																$data = array('receipt_no'=>$receipt_no,
																				'invoice_no'=>$invoice_no,
																				'reference_no'=>$reference_no,
																				'tracking_no'=>$tracking_no,
																				'applicant_id'=>$applicant_id,
																				'payment_currency_id'=>$payment_currency_id,
																				'payment_currency'=>$payment_currency,
																				'exchange_rate'=>$exchange_rate,
																				'trans_date'=>$payment_currency,
																				'gl_account_code'=>$gl_account_code,
																				'cost_description'=>$cost_description,
																				'payment_amount'=>$amount_paidinv,
																				'created_on'=>$created_on,
																				'created_by'=>$user_id,
																				'zone_name'=>$pay_record->zone_name,
																				'zone_id'=>$pay_record->zone_id,
																				'epicor_code'=>$pay_record->epicor_code,
																				'payment_mode'=>$pay_record->payment_mode,
																				'epicor_bank'=>$pay_record->epicor_bank,
																				'trans_ref'=>$pay_record->trans_ref,
																				'payment_ref_no'=>$pay_record->payment_ref_no,
																				'created_on'=>Carbon::now(),
																				'created_by'=>$user_id
																);
																insertRecord('epicor_payments_records', $data, $user_id);

													}
										}
					}


}

function getPaymentDetails($receipt_no){
                $data = '';
						$rec = DB::table('tra_payments as t1')
													->select('t4.epicor_code', 't4.name as zone_name', 't1.zone_id', 't3.name as payment_mode', 't2.name as bank_name','t2.epicor_bank', 't1.trans_ref', 't1.payment_ref_no')
													->leftJoin('par_banks as t2','t1.bank_id', '=', 't2.id')
													->leftJoin('par_payment_modes as t3','t1.payment_mode_id', '=', 't3.id')
													->leftJoin('par_zones as t4','t1.zone_id', '=', 't4.id')
													->where(array('receipt_no'=>$receipt_no))
													->first();
													
					
                return $rec;
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
function getBankDetails($bank_name,$currency_id){
			$bank_id = 0;
			$rec = DB::table('gepg_bank_mapping')
							->where(array('currency_id'=>$currency_id, 'gepg_bank_name'=>$bank_name))
							->first();
							
				if($rec){
							$bank_id = $rec->bank_id;
				}
				return $bank_id;
	
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
		
		function authenticateApiUser($username,$password,$request)
    {
		$access_token = '';
        $username = aes_encrypt($username);
        if (is_null($this->external_api_client)) {
            $res = array(
                'success' => false,
                'message' => 'API user not found!!'
            );
            return response()->json($res);
        }
        $request->request->add([
            'grant_type' => 'password',
            'provider' => 'apiusers',
            'client_id' => $this->external_api_client->id,
            'client_secret' => $this->external_api_client->secret,
            'username' => $username,
            'password' => $password
        ]);
        $tokenRequest = $request->create('/oauth/token', 'POST', $request->all());
				$token = \Route::dispatch($tokenRequest);
				
				 $token_details =	json_decode($token->getContent());
				 	
				 if(isset($access_token->error)){
					echo 	$access_token->error_description;
					exit();
				}
				
				return $token_details;
		}
		public function rraPaymentGatewayAuth(){
				
				$response = postInvoiceDetailsonDocumentNo(7,132,1034132);
				print_r($response);
				exit();
				$auth_response = authPaymentIntegration();
			
				print_r($auth_response);
			
		}
		public function rraPaymentGatewayGetPayments(Request $req){
				//request for payment details 
				 try{
					 $where_state = array();
					$records = DB::table('rrapayment_application_invoices as t1')
							->where($where_state)
							->where(array('rra_submission_status'=>1))
							->get();
					
					if($records->count() >0){
						foreach($records as $rec){
							$DocumentNumber = $rec->DocumentNumber;
							$invoice_id = $rec->invoice_id;
							$rrapayment_appinvoice_id = $rec->id;
							$application_code = $rec->application_code;
							
							$response = checkrraPaymentGatewayGetPayments($DocumentNumber,$invoice_id,$rrapayment_appinvoice_id,$application_code);
							
							if($response['success']){
								//send email to customer 
								
								$res= $response;
							}
							$res= $response;
							
						}
						 
					 }
					 else{
						 
						 $res = array(
									'success' => false,
									'message' => 'There is no pending payment remittance on any raised bill'
								);
					 }
				 }
				catch (\Exception $exception) {
			   
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
				print_r($res);
				
		}
		
}