<?php

namespace Modules\APIIntegrations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

use App\traderAccount;

use App\Http\Controllers\Auth;

use Illuminate\Support\Carbon;

use App\Modules\PromotionMaterials\Traits;
use App\Modules\PremiseRegistration\Traits\PremiseRegistrationTrait;
use App\Modules\GmpApplications\Traits\GmpApplicationsTrait;
use App\Modules\ProductRegistration\Traits\ProductsRegistrationTrait;
use App\Modules\ClinicalTrial\Traits\ClinicalTrialTrait;
use App\Modules\Surveillance\Traits\SurveillanceTrait;
use App\Modules\Importexportpermits\Traits\ImportexportpermitsTraits;
use App\Modules\Reports\Traits\ReportsTrait;
use App\Modules\PromotionMaterials\Traits\PromotionMaterialsTrait;
use App\Modules\ProductNotification\Traits\ProductsNotificationTrait;

use App\Modules\Revenuemanagement\Traits\RevenuemanagementTrait;



class PaymentsIntegrationController extends Controller
{
	
	protected $mis_app_id;
	protected $mis_app_client;
	protected $external_api_client;

	protected $systemid;
	protected $spcode;
	protected $subspcode;
	protected $gepgurl;
	protected $gepg_port;
	 protected $user_id;
	  use PremiseRegistrationTrait;
    use GmpApplicationsTrait;
    use ProductsRegistrationTrait;
    use ClinicalTrialTrait;
    use SurveillanceTrait;
    use ImportexportpermitsTraits;
    use ReportsTrait;
    use PromotionMaterialsTrait;
    use ProductsNotificationTrait;
    use RevenuemanagementTrait;
	
	
	public function __construct(Request $req)
    {
		$this->user_id = 2;
		
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
	public function gepgReconcResp(Request $req){
	try{
		$res = '';
						$username = $req->username;
						$password = $req->password;
						$reconciliation_date = $req->reconciliation_date;
						
						$access_token = $this->authenticateApiUser($username,$password,$req);
						if($access_token != ''){
											
								$dataPOST = trim(file_get_contents('php://input'));
								$dataPOST= preg_replace('/(<\?xml[^?]+?)utf-16/i','$1utf-8',$dataPOST);
								
								$xmlData = simplexml_load_string($dataPOST);
								//print_r('data');
								$cert_store = $file_path."gepgclientprivatekey.pfx";
								if (!file_exists($cert_store) || !file_get_contents($cert_store)) {
									
									echo "Error: Unable to read the cert file\n";
									exit;
									
									 
								}	
								if(!empty($xmlData)){
												$signature = $xmlData->gepgSignature;
												$SpReconcReqId = $xmlData->ReconcBatchInfo->SpReconcReqId;
												//$xmlData = $xmlData->ReconcTrans;
												//var_dump($xmlData->gepgSpReconcResp->ReconcTrans);
												
												foreach($xmlData->gepgSpReconcResp->ReconcTrans->ReconcTrxInf as $ReconcTrxInf)
												{
													$data = array('SpBillId'=> $ReconcTrxInf->SpBillId,
																 'BillCtrNum'=> $ReconcTrxInf->BillCtrNum,
																 'pspTrxId'=> $ReconcTrxInf->pspTrxId,
																 'PaidAmt'=> $ReconcTrxInf->PaidAmt,
																 'CCy'=> $ReconcTrxInf->CCy,
																 'PayRefId'=> $ReconcTrxInf->PayRefId,
																 'TrxDtTm'=> $ReconcTrxInf->TrxDtTm,
																 'CtrAccNum'=> $ReconcTrxInf->CtrAccNum,
																 'UsdPayChnl'=> $ReconcTrxInf->UsdPayChnl,
																 'PspName'=> $ReconcTrxInf->PspName,
																 'PspCode'=> $ReconcTrxInf->PspCode,
																 'DptCellNum'=> $ReconcTrxInf->DptCellNum,
																 'DptName'=> $ReconcTrxInf->DptName,
																 'DptEmailAddr'=> $ReconcTrxInf->DptEmailAddr,
																 'Remarks'=>$ReconcTrxInf->Remarks,
																 'SpReconcReqId'=>$SpReconcReqId
																 );
													
													DB::table('tra_gepg_payment_reconciliation')->insert($data);
							
							
												}
							
							$xml_data = '<gepgSpReconcRespAck>'.
											'<ReconcStsCode>7101</ReconcStsCode>'.									
										'</gepgSpReconcRespAck>';
																						
										$cert_store = file_get_contents($cert_store);
										if (openssl_pkcs12_read($cert_store, $cert_info, "tfda!@2018keys"))   
										{
											openssl_sign($xml_data, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
												
											//output crypted data base64 encoded
											$signature = base64_encode($signature);         
											
										} //xml_data
										$xml_data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
										//$data_string = $xml_data;
										echo $xml_data;
							
							
							}
						}
	}catch(\Exception $e){
					$res = $e;
		}catch(\Throwable $throwable){
				$res = $throwable;
		
		}
	
	
}
	public function gepgReconcReq(Request $req){
			try{
						$res = '';
						$username = $req->username;
						$password = $req->password;
						$reconciliation_date = $req->reconciliation_date;
						
						$access_token = $this->authenticateApiUser($username,$password,$req);
						if($access_token != ''){
							$systemId = $this->systemid;
							$SpCode= $this->spcode;
							$SubSpCode = $this->subspcode;
							$GePGURL = $this->gepgurl;
							$gepg_port =  $this->gepg_port;
							
							$url = $this->gepgurl.'/api/reconciliations/sig_sp_qrequest';	
							$file_path = getcwd().'/backend/resources/gepg_keys/';
	
							$cert_store = $file_path."gepgclientprivatekey.pfx";
							if (!file_exists($cert_store) || !file_get_contents($cert_store)) {
								
								echo "Error: Unable to read the cert file\n";
								exit;
								
								 
							}	
							$cert_store = file_get_contents($cert_store);
								
							$SpReconcReqId = $this->generateUniqueNo();
							     $xml_data ='<gepgSpReconcReq>'.
										'<SpReconcReqId>'.$SpReconcReqId.'</SpReconcReqId>'.
										'<SpCode>'.$SpCode.'</SpCode>'.
										'<SpSysId>'.$systemId.'</SpSysId>'.
										'<TnxDt>'.formatDate($reconciliation_date).'</TnxDt>'.
										'<ReconcOpt>1</ReconcOpt>'.
								 '</gepgSpReconcReq>';
								 $date = formatDate($reconciliation_date);
								 $data = array('SpReconcReqId'=>$SpReconcReqId, 'TnxDt'=>$date, 'ReconcOpt'=>1);
								 DB::table('tragepg_payment_reconciliation_requests')->insert($data);
								 			
								
								if (openssl_pkcs12_read($cert_store, $cert_info, "tfda!@2018keys"))   
								{
									
									openssl_sign($xml_data, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
									
									//output crypted data base64 encoded
									$signature = base64_encode($signature);         
									$data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
									
									$resultCurlPost = "";
			
									$data_string = $data;
									
									$ch = curl_init($url);
									curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
									curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array(
														'Content-Type:application/xml',
														'Gepg-Com:default.sp.in',
														'Gepg-Code:SP166',
														'Content-Length:'.strlen($data_string))
											   );
											   
									curl_setopt($ch, CURLOPT_TIMEOUT, 200);
									curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);

									$resultCurlPost = curl_exec($ch);
									curl_close($ch);
									//2214 11 3 8 22 11
									//var_dump($resultCurlPost);
									if(!empty($resultCurlPost)){
										$xml = simplexml_load_string($resultCurlPost);
										$signature = $xml->gepgSignature;
										$xml = $xml->gepgSpReconcReqAck;
                                        $response_code =  (int)$xml->ReconcStsCode;
                                        
										//Tags used in substring response content
										$datatag = "gepgSpReconcReqAck";
										$sigtag = "gepgSignature";
								
										//Get data and signature from response
										$vdata = getDataString($resultCurlPost,$datatag);
										$vsignature = getSignatureString($resultCurlPost,$sigtag);
										
										$xml_data = '<gepgSpReconcReqAck>'.
												'<ReconcStsCode>'.$response_code.'</ReconcStsCode>'.									
											'</gepgSpReconcReqAck>';
											
										if (!file_get_contents($cert_store)) {
											
											echo "Error: Unable to read the cert file\n";
											exit;
										}
										
										//echo $response;
										$signature = base64_encode($signature);
										
										if (openssl_pkcs12_read($cert_store, $cert_info, "tfda!@2018keys"))   
										{
											openssl_sign($xml_data, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
												
											//output crypted data base64 encoded
											$signature = base64_encode($signature);         
											
										} //xml_data
										$xml_data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
									//$data_string = $xml_data;
										echo $xml_data;
										
									}
									else
									{
										echo "No result Returned"."\n";
									}
								}
								else{
									
									echo "In-correct Certificate File";
								}
							
						}
			}catch(\Exception $e){
						echo  $exception;
			}catch(\Throwable $throwable){
						echo $throwable;
				
			}	
			
		}
		public function postBillSubmissionRequest(Request $req){
			
					try{
						$res = '';
						$username = $req->username;
						$password = $req->password;
					
						$access_token = $this->authenticateApiUser($username,$password,$req);
						
						if(isset($access_token->error)){
							
							//	echo $access_token->error_description;
								//exit;
							
							
						}
						
						if($access_token){
							$file_path = getcwd().'/backend/resources/gepg_keys/';
	
							$url = $this->gepgurl.'/api/bill/sigqrequest';
							$cert_store = $file_path."gepgclientprivatekey.pfx";

							if (!file_exists($cert_store) || !file_get_contents($cert_store)) {
					
								echo "Error: Unable to read the cert file\n";
								exit;
							}
							$cert_store = file_get_contents($cert_store);
							
							$where_date = 	" date_format(t1.date_of_invoicing, '%Y-%m-%d') >= '2018-08-19'";
							$sql = DB::table('tra_application_invoices as t1')
												->select(DB::raw("t1.invoice_no,t1.invoice_amount AS inv_amount,t1.date_of_invoicing, 'System Invoice' as created_by,SUM(t2.total_element_amount) AS  invoice_amount,PayCntrNum,t1.paying_exchange_rate as exchange_rate,t1.paying_currency_id as currency_id,t1.applicant_id,t3.name AS  applicant_name,gepgsubmission_status_id,t3.email as email_address ,t3.telephone_no"))
												->join('tra_invoice_details as t2', 't1.id','=','t2.invoice_id')
												->join('wb_trader_account as t3', 't1.applicant_id','=','t3.id')
												->where(array('t1.gepgsubmission_status_id'=>1))
												->whereRAW($where_date)
												->whereIn('t1.id',[54652,54651,54650,54649,54648])
												->groupBy('t1.id')
												->get();
										
								if($sql){
										foreach($sql as $rows){
											
												$bill_expiry_date = date("Y-m-d\TH:i:s", strtotime(date("Y-m-d", strtotime($rows->date_of_invoicing)) . " + 1 year"));
												$prepared_on = date('Y-m-d\TH:i:s', strtotime($rows->date_of_invoicing));
												$invoice_no = $rows->invoice_no;
												$invoice_amount = $rows->invoice_amount;
												$applicant_id = $rows->applicant_id;
												$currency_id = $rows->currency_id;
												$exchange_rate = $rows->exchange_rate;
												$email_address = $rows->email_address;
												$telephone_no = $rows->telephone_no;
												$applicant_name = $rows->applicant_name;
												

												if($currency_id == 1){
														$currency = 'USD';
														$BillPayOpt=2;
												}
												else{
														$currency = 'TZS';
														$BillPayOpt=3;
												}
												
												$equivalent_amount = ($invoice_amount*$exchange_rate);
												$applicant_name = htmlentities($applicant_name,ENT_QUOTES,'UTF-8');
												
												$email_address = $this->validateEmail($email_address);
												$telephone_no = $this->validatePhoneNo($telephone_no);
												//xml data 
												$xml_data ='<gepgBillSubReq>'.
															'<BillHdr>'.
																'<SpCode>'.$this->spcode.'</SpCode>'.
																'<RtrRespFlg>true</RtrRespFlg>'.
															'</BillHdr>'.
															'<BillTrxInf>'.
																	'<BillId>'.$rows->invoice_no.'</BillId>'.
																	'<SubSpCode>'.$this->subspcode.'</SubSpCode>'.
																	'<SpSysId>'.$this->systemid.'</SpSysId>'.
																	'<BillAmt>'. $invoice_amount.'</BillAmt>'.
																	'<MiscAmt>0</MiscAmt>'.
																	'<BillExprDt>'.$bill_expiry_date.'</BillExprDt>'.
																	'<PyrId>'.$applicant_id.'</PyrId>'.
																	'<PyrName>'.$applicant_name.'</PyrName>'.
																	'<BillDesc>TFDA Application Invoice</BillDesc>'.
																	'<BillGenDt>'.$prepared_on.'</BillGenDt>'.
																	'<BillGenBy>'.$rows->created_by.'</BillGenBy>'.
																	'<BillApprBy>'.$rows->created_by.'</BillApprBy>'.
																	'<PyrCellNum>'.$telephone_no.'</PyrCellNum>'.
																	'<PyrEmail>'.$email_address.'</PyrEmail>'.
																	'<Ccy>'.$currency.'</Ccy>'.
																	'<BillEqvAmt>'.$equivalent_amount.'</BillEqvAmt>'.
																	'<RemFlag>true</RemFlag>'.
																	'<BillPayOpt>'.$BillPayOpt.'</BillPayOpt>'.
																'<BillItems>'.
																	'<BillItem>'.
																		'<BillItemRef>'.$rows->invoice_no.'</BillItemRef>'.
																		'<UseItemRefOnPay>N</UseItemRefOnPay>'.
																		'<BillItemAmt>'.$invoice_amount.'</BillItemAmt>'.
																		'<BillItemEqvAmt>'.$equivalent_amount.'</BillItemEqvAmt>'.
																		'<BillItemMiscAmt>0</BillItemMiscAmt>'.
																		'<GfsCode>140396</GfsCode>'.
																	'</BillItem>'.
																'</BillItems>'.
																'</BillTrxInf>'.  
														'</gepgBillSubReq>';
			echo "<br/>processing of ".$invoice_no;
														if (openssl_pkcs12_read($cert_store, $cert_info, "tfda!@2018keys"))   
														{
																					
																				openssl_sign($xml_data, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
																					
																				$signature = base64_encode($signature);  
																				$data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
																				
																				$resultCurlPost = "";
														
																				$data_string = $data;
																				
																				$ch = curl_init($url);
																				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
																				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
																				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
																				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
																				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
																									'Content-Type:application/xml',
																									'Gepg-Com:default.sp.in',
																									'Gepg-Code:'.$this->spcode,
																									'Content-Length:'.strlen($data_string))
																							);
																							
																				curl_setopt($ch, CURLOPT_TIMEOUT, 200);
																				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);

																				$resultCurlPost = curl_exec($ch);
																				curl_close($ch);
																				$xml = simplexml_load_string($resultCurlPost);
																				
																				if(!empty($resultCurlPost)){
																									$response_code =  (int)$xml->gepgBillSubReqAck->TrxStsCode;
																									$BillId =  (int)$xml->gepgBillSubReqAck->BillId;
																														
																									if($response_code == 7101){
																												$res = "Success - Bill Content Successfully submitted".$invoice_no;
																									}
																									else if($response_code == 7242){
																												$res = "Failed - Bill Content Irregular".$invoice_no;
																									}
																									else{ 
																												$res = "Failed - General Error";
																									}
																									//Tags used in substring response content
																									$datatag = "gepgBillSubReqAck";
																									$sigtag = "gepgSignature";
																									
																									$xml_data = '<gepgBillSubReqAck>'.
																											'<TrxStsCode>'.$response_code.'</TrxStsCode>'.									
																										'</gepgBillSubReqAck>';
																									$url = $this->gepgurl.'/api/bill/sigqrequest';
																									if (!file_exists($cert_store) || !file_get_contents($cert_store)) {
						
																											echo "Error: Unable to read the cert file\n";
																											exit;
																									}
																									
																									$cert_store = file_get_contents($cert_store);
																									$signature = base64_encode($signature);
																									$xml_data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
																								
																									echo $xml_data;
																									
																									if($response_code != 7101){

																																		$created_on = date('Y-m-d H:i:s');
																																		$inv_data = array('gepgsubmission_status_id'=>3, 'dola'=> Carbon::now);
																																		DB::table('tra_application_invoices')->where(array('invoice_no'=>$invoice_no))->update($inv_data);

																																		$data = array('invoice_no'=>$invoice_no, 
																																									'error_response'=>$xml,
																																									'response_code'=>$response_code, 
																																									'created_on'=>Carbon::now());
																																		DB::table('gepg_failed_billsresponses')
																																				->insert($data);

																									}
																									else{
																										
																														$created_on = date('Y-m-d H:i:s');
																														$inv_data = array('gepgsubmission_status_id'=>2, 'dola'=> Carbon::now);
																														DB::table('tra_application_invoices')
																																->where(array('invoice_no'=>$invoice_no))
																																->update($inv_data);


																									}
																									
																									continue;
																					
																					
																				}
																				else
																				{
																					echo "No result Returned"."\n";
																				}
														}else{
															echo "In-correct Certificate File";
														}

										}
								}

						}
						else{

							$res = "Authentication Failed";

						}
						
					}catch(\Exception $exception){
								$res = $exception;
					}catch(\Throwable $throwable){
							$res = $throwable;
					
					}
					echo $res;

		}
		//gepgRetentionBillCanclResp
		
		public function gepgBillCanclResp(Request $req){
					try{
						$res = '';
						$username = $req->username;
						$password = $req->password;
						$access_token = $this->authenticateApiUser($username,$password,$req);
						if($access_token != ''){
								$sql = DB::table('tra_application_invoicescancellation as t1')->where(array('is_reversed !='=>1));
								if($sql){
										foreach($sql as $rows){
											$invoice_no = $row->invoice_no;
											$payment_controlno = $row->PayCtrNum;
											$reference_no = $row->reference_no;
											$xml_data ='<gepgBillCanclReq>'.
														'<SpCode>'.$this->spcode.'</SpCode>'.
														'<SpSysId>'.$this->systemid.'</SpSysId>'.
														'<BillId>'.$invoice_no.'</BillId>'.
												'</gepgBillCanclReq>';
												$file_path = getcwd().'/backend/resources/gepg_keys/';
												$cert_store = $file_path."gepgclientprivatekey.pfx";
												if (openssl_pkcs12_read($cert_store, $cert_info, "tfda!@2018keys"))   
													{
														
														openssl_sign($xml_data, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
														
														//output crypted data base64 encoded
														$signature = base64_encode($signature);         
														$data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
														
														$resultCurlPost = "";
								
														$data_string = $data;
														
														$ch = curl_init($url);
														curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
														curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
														curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
														curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
														curl_setopt($ch, CURLOPT_HTTPHEADER, array(
																			'Content-Type:application/xml',
																			'Gepg-Com:default.sp.in',
																			'Gepg-Code:'.$this->spcode,
																			'Content-Length:'.strlen($data_string))
																	);
																	
														curl_setopt($ch, CURLOPT_TIMEOUT, 200);
														curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);

														$resultCurlPost = curl_exec($ch);
														curl_close($ch);
														$xml = simplexml_load_string($resultCurlPost);
														//2214 11 3 8 22 11
											
														if(!empty($resultCurlPost)){
															$signature = $xml->gepgSignature;
															$xml = $xml->gepgBillCanclResp;
																									$response_code =  (int)$xml->BillCanclTrxDt->TrxStsCode;
																									$TrxSts =  $xml->BillCanclTrxDt->TrxSts;
																									$TxnStsCode =  (int)$xml->BillCanclTrxDt->TxnStsCode;
																									$BillId =  (int)$xml->BillCanclTrxDt->BillId;
															
															//Tags used in substring response content
															$datatag = "gepgBillSubReqAck";
															$sigtag = "gepgSignature";
															
															//Get data and signature from response
															$vdata = getDataString($resultCurlPost,$datatag);
															$vsignature = getSignatureString($resultCurlPost,$sigtag);
															if($response_code == 7283){
																		
																															$inv_data = array('is_reversed'=>1, 'dola'=> Carbon::now);
																															DB::table('tra_application_invoicescancellation')
																																	->where(array('invoice_no'=>$invoice_no))
																																	->update($inv_data);
																		$response = "Success - Bill Content Successfully cancelled";
																		
															}
															else if($response_code == 7242){
																		$response = "Failed - Bill Content Irregular";
															}//7102
															else{ 
																		$response = "Failed - General Error";
															}
															$xml_data = '<gepgBillCanclRespAck>'.
																	'<TrxStsCode>'.$response_code.'</TrxStsCode>'.									
																'</gepgBillCanclRespAck>';
															$url = $this->gepgurl.'/api/bill/sigqrequest';
															if (!file_exists($cert_store) || !file_get_contents($cert_store)) {
						
																echo "Error: Unable to read the cert file\n";
																exit;
															}
															
														
															$signature = base64_encode($signature);
															
															if (openssl_pkcs12_read($cert_store, $cert_info, "tfda!@2018keys"))   
															{
																openssl_sign($xml_data, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
																	
																$signature = base64_encode($signature);         
																
															} //xml_data
															$xml_data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
															
															echo $xml_data;
															
														}
														else
														{
															echo "No result Returned"."\n";
														}
													}
													else{
														
														echo "In-correct Certificate File";
													}
								
											

										}
								}
						}else{

							$res = "Authentication Failed";

						}
				}catch(\Exception $e){
							$res = $exception;
				}catch(\Throwable $throwable){
						$res = $throwable;
				
				}
				echo $res;

		}
		public function gepgBillSubResp(Request $req){
			try{

				$res = '';
				$username = $req->username;
				$password = $req->password;
				$dataPOST = $req->all();
				$file_path = getcwd().'/backend/resources/gepg_keys/';

				$access_token = $this->authenticateApiUser($username,$password,$req);
				
				if($access_token != ''){
						$dataPOST = trim(file_get_contents('php://input'));
						$dataPOST= preg_replace('/(<\?xml[^?]+?)utf-16/i','$1utf-8',$dataPOST);
						$xmlData = simplexml_load_string($dataPOST);
						$cert_store = $file_path."gepgclientprivatekey.pfx";
						if(!empty($xmlData)){
				
										$signature = $xmlData->gepgSignature;
										$xmlData = $xmlData->gepgBillSubResp;
										$response_code =  (int)$xmlData->BillTrxInf->TrxStsCode;
										$TrxSts =  $xmlData->BillTrxInf->TrxSts;
										$PayCntrNum =  (int)$xmlData->BillTrxInf->PayCntrNum;
										 $invoice_no =  (int)$xmlData->BillTrxInf->BillId;
											
												if($PayCntrNum != ''){
													 $inv_counter = DB::table('tra_application_invoices')
																 ->where(array('PayCntrNum'=>$PayCntrNum))
																 ->count();
																
															if($inv_counter ==0){
																$inv_data = array('gepgsubmission_status_id'=>1, 'PayCntrNum'=> $PayCntrNum,'dola'=>Carbon::now());
																DB::table('tra_application_invoices')
																		->where(array('invoice_no'=>$invoice_no))
																		->update($inv_data);

																		$inv_data = array('PayCntrNum'=> $PayCntrNum,'dola'=>Carbon::now());
																		DB::connection('db_lims')->table('sample_invoicing_details')
																				->where(array('invoice_no'=>$invoice_no))
																				->update($inv_data);
																		
																		$inv_data = array('PayCntrNum'=> $PayCntrNum,'dola'=>Carbon::now());
																		DB::connection('db_lims')->table('applicaton_invoices')
																						->where(array('invoice_no'=>$invoice_no))
																						->update($inv_data);
																						
																	$response = "Success - Bill Content Successfully received";

															}
															else{
																$response_code = '7226';
																$response = "Success - Control No already Exists";
																
															}
															
												}
												else{
																	$created_on = date('Y/m/d H:i:s');
																	$inv_data = array('gepgsubmission_status_id'=>3, 'PayCntrNum'=> $PayCntrNum,'dola'=>Carbon::now());
																	DB::table('tra_application_invoices')
																			->where(array('invoice_no'=>$invoice_no))
																			->update($inv_data);
																	
																			$data = array('invoice_no'=>$invoice_no, 
																																									'error_response'=>$xml,
																																									'response_code'=>$response_code, 
																																									'created_on'=>Carbon::now());
																																		DB::table('gepg_failed_billsresponses')
																																				->insert($data);
																	
												
												}
												$response_code = 7101;
												$response = "Success - Bill Content Successfully received";
																						
												$xml_data = '<gepgBillSubRespAck>'.
																									'<TrxStsCode>'.$response_code.'</TrxStsCode>'.									
																								'</gepgBillSubRespAck>';
												
												if (openssl_pkcs12_read($cert_store, $cert_info, "tfda!@2018keys"))   
												{
													openssl_sign($xml_data, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
													$signature = base64_encode($signature);         
													
												} 
												$xml_data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
												echo $xml_data;
							}

				}else{

					$res = "Authentication Failed";

				}
		}catch(\Exception $e){
					$res = $exception;
		}catch(\Throwable $throwable){
				$res = $throwable;
		
		}
}

public function gepgPmtSpInfo(Request $req){
						try{

							$res = '';
							$username = $req->username;
							$password = $req->password;
							$dataPOST = $req->all();
						$file_path = getcwd().'/backend/resources/gepg_keys/';

							$access_token = $this->authenticateApiUser($username,$password,$req);
							
						$cert_store = $file_path."gepgclientprivatekey.pfx";
						
							if($access_token != ''){
								$postData = file_get_contents('php://input');
								$postData= preg_replace('/(<\?xml[^?]+?)utf-16/i','$1utf-8',$postData);
								
								$xml = simplexml_load_string($postData);
								$xml = $xml->gepgPmtSpInfo->PymtTrxInf;
								$TrxId = $xml->TrxId;
								$PayRefId =  $xml->PayRefId;
								$invoice_no = $xml->BillId;
								$PayCtrNum = $xml->PayCtrNum;
								$BillAmt = $xml->BillAmt;
								$PaidAmt = $xml->PaidAmt;
								$BillPayOpt = $xml->BillPayOpt;
								$CCy = $xml->CCy;
								$TrxDtTm = $xml->TrxDtTm;
								$UsdPayChnl = $xml->UsdPayChnl;
								$PyrCellNum = $xml->PyrCellNum;
								$PyrName = $xml->PyrName;
								$PyrEmail = validateEmail($xml->PyrEmail);
								$PspReceiptNumber = $xml->PspReceiptNumber;
								$PspName = $xml->PspName;
								$usr_id = '';
								$rec = DB::table('tra_application_invoices')
												->where(array('invoice_no'=>$invoice_no, 'PayCntrNum'=>$PayCtrNum))
												->first();
								$invoice_id = '';
								if($rec){
											$invoice_id = $rec->id;	
								}			//tra_payments
								$count = DB::table('tra_payments')
												->where(array('id'=>$invoice_id, 'PayCtrNum'=>$PayCtrNum,'amount_paid'=>$PaidAmt,'transaction_id'=>$PayRefId))
												->count();
								if($count == 0){
											$count = DB::table('gepg_goverment_paymentdetails')
																->where(array('PayCtrNum'=>$PayCtrNum))
																->count();
											if($count == 0){
														$receipt_no = generateReceiptNo(499);
														$data = array('receipt_no'=>$receipt_no,
																					'TrxId'=>$TrxId,
																					'PayRefId'=>$PayRefId,
																					'invoice_no'=>$invoice_no,
																					'PayCtrNum'=>$PayCtrNum,
																					'BillAmt'=>$BillAmt,
																					'PaidAmt'=>$PaidAmt,
																					'BillPayOpt'=>$BillPayOpt,
																					'CCy'=>$CCy,
																					'TrxDtTm'=>$TrxDtTm,
																					'UsdPayChnl'=>$UsdPayChnl,
																					'PyrCellNum'=>$PyrCellNum,
																					'PyrName'=>$PyrName,
																					'PyrEmail'=>$PyrEmail,
																					'$PspReceiptNumber'=>$PspReceiptNumber,
																					'PspName'=>$PspName,
																					'created_on'=>Carbon::now(),
																					'created_by'=>499
																);
																DB::table('gepg_goverment_paymentdetails')->insert($data);
																$xml_data = '<gepgPmtSpInfoAck>'.
																									'<TrxStsCode>7101</TrxStsCode>'.									
																								'</gepgPmtSpInfoAck>';
																$receipt_response = $this->savePaymentsDetails($PspName,$CCy,$receipt_no,$invoice_id,$PayRefId,$PyrCellNum,$PspReceiptNumber,$PaidAmt,$PayCtrNum);
											}
								}
								else{
										$data = array('TrxId'=>$TrxId,
														'PayRefId'=>$PayRefId,
														'invoice_no'=>$invoice_no,
														'PayCtrNum'=>$PayCtrNum,
														'BillAmt'=>$BillAmt,
														'PaidAmt'=>$PaidAmt,
														'BillPayOpt'=>$BillPayOpt,
														'CCy'=>$CCy,
														'TrxDtTm'=>$TrxDtTm,
														'UsdPayChnl'=>$UsdPayChnl,
														'PyrCellNum'=>$PyrCellNum,
														'PyrName'=>$PyrName,
														'PyrEmail'=>$PyrEmail,
														'$PspReceiptNumber'=>$PspReceiptNumber,
														'PspName'=>$PspName,
														'created_on'=>Carbon::now(),
														'created_by'=>499
											);
									DB::table('gepg_goverment_paymentdetails')->insert($data);

								}

																								$url = $this->gepgurl.'/api/bill/sigqrequest';
																								if (!file_exists($cert_store) || !file_get_contents($cert_store)) {
						
																									echo "Error: Unable to read the cert file\n";
																									exit;
																								}
																								if (openssl_pkcs12_read($cert_store, $cert_info, "tfda!@2018keys"))   
																								{
																									openssl_sign($xml_data, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
																										
																									//output crypted data base64 encoded
																									$signature = base64_encode($signature);         
																									
																								} //xml_data
																								$xml_data = '<Gepg>'.$xml_data.'<gepgSignature>'.$signature.'</gepgSignature></Gepg>';
																							//$data_string = $xml_data;
																								echo $xml_data;
							}else{

								$res = "Authentication Failed";

							}

					}catch(\Exception $e){
								$res = $exception;
					}catch(\Throwable $throwable){
							$res = $throwable;
					
					}
					echo $res;
	}

	function savePaymentsDetails($bank_name,$CCy,$receipt_no,$invoice_id,$transactionId,$phone_no,$payment_reference_no,$amount_paid,$PayCtrNum){
						$usr_id = 418;//temporary
						
						$applicant_name = '';
						$reference_no = '';
						$zone_name = '';
						$zone_id = '';
						
						
						//new functionality batch management 
						$batch_rec = DB::table('tra_batch_invoices_records as t1')
											->join('tra_application_invoices as t2', 't1.app_invoice_id','t2.id')
											->join('tra_invoice_details as t3', 't2.id','t3.invoice_id')
											->select("t2.reference_no, t2.zone_id,t2.applicant_name,t2.invoice_no,t2.date_of_invoicing,t1.app_invoice_id, 'System Invoice' as created_by,SUM(t2.total_element_amount) AS  invoice_amount,PayCtrNum,t2.paying_exchange_rate as exchange_rate,t2.paying_currency_id as currency_id,t2.applicant_id")
											->where(array('t1.batch_invoice_id'=>$invoice_id))
											->groupBy('t2.id')
											->get();
						if($batch_rec){
								$payment_balance = $amount_paid;
								//update the
								foreach($batch_rec as $brec){
										$app_invoice_id = $brec->app_invoice_id;
										$app_invoice_amount = $brec->invoice_amount;
										$app_currency_id = $brec->currency_id;
										$exchange_rate = $brec->exchange_rate;
										//check if payment received for the application 
										$payment_record = DB::table('tra_payments as t1')
															->where(array('app_invoice_id'=>$app_invoice_id))
															->first();
										if(!$payment_record){
													if(($payment_balance >= $app_invoice_amount)){
														$receipt_no = generateReceiptNo(499);
														//update the 
														$reference_no = $brec->reference_no;
														$applicant_id = $brec->applicant_id;
														$applicant_name = $brec->applicant_name;
														$zone_id = $brec->zone_id;
														$exchange_rate = $brec->exchange_rate;
														if($inv_currency_id == $currency_id){
															$invoice_amount = $app_invoice_amount;
															
														}
														else{
															if($inv_currency_id == 1 && $currency_id == 4){
																$invoice_amount = $app_invoice_amount*$exchange_rate;
																$exchange_rate = 1;
															}
															else{
																
																$invoice_amount =  $invoice_amount;
															}
															
														}
														$bank_id = getBankDetails($bank_name,$currency_id);
														$rec = DB::table('tra_application_invoices as t1')
															->select(DB::raw("id as invoice_id,application_code,t1.*,application_id, invoice_no,applicant_id,applicant_name, zone_id,'' as zone_name, '' as group_no"))
															->where(array('id'=>$app_invoice_id))
															->first();
									$payment_data = array('application_code'=>$rec->application_code,
																				'application_id'=>$rec->application_id,
																				'reference_no'=>$rec->reference_no,
																				'tracking_no'=>$rec->tracking_no,
																				'amount_paid'=>$invoice_amount,
																				'$applicant_name'=>$rec->applicant_name,
																				'receipt_no'=>$receipt_no,
																				'trans_date'=>Carbon::now(),
																				'currency_id'=>$currency_id,
																				'invoice_id'=>$rec->invoice_id,
																				'applicant_id'=>$rec->applicant_id,
																				'section_id'=>$rec->section_id,
																				'module_id'=>$rec->module_id,
																				'sub_module_id'=>$rec->sub_module_id,
																				'receipt_type_id'=>1,
																				'payment_mode_id'=>4,
																				'trans_ref'=>$payment_reference_no,
																				'bank_id'=>$bank_id,
																				'PayCtrNum'=>$PayCtrNum,
																				'payment_ref_no'=>$payment_reference_no,
																				'transaction_id'=>$transactionId,
																				'pay_phone_no'=>$phone_no,
																				'zone_id'=>$zone_id,
																				'payment_type_id'=>$payment_type_id,
																				'exchange_rate'=>$exchange_rate,
																				'created_on'=>Carbon::now(),
																				'created_by'=>499
										);

										$result = insertRecord('tra_payments', $payment_data, $user_id);
										if($result['success']){
													$receipt_id = $result['record_id'];
													$check_retention  = DB::table('tra_product_retentions')
																								->where(array('invoice_id'=>$invoice_id))
																								->first();
													if($check_retention){
															$retention_data = array('receipt_id'=>$receipt_id,'retention_status_id'=>2, 'dola'=>Carbon::now());
															DB::table('tra_product_retentions')
																	->where(array('invoice_id'=>$invoice_id))
																	->update($retention_data);

													}
													
													$payment_data = array(
																					'reference_no'=>$rec->reference_no,
																					'tracking_no'=>$rec->tracking_no,
																					'amount_paid'=>$amount_paid,
																					'$applicant_name'=>$rec->applicant_name,
																					'receipt_no'=>$receipt_no,
																					'trans_date'=>Carbon::now(),
																					'currency_id'=>$currency_id,
																					'invoice_id'=>$rec->invoice_id,
																					'applicant_id'=>$rec->applicant_id,
																					'receipt_type_id'=>1,
																					'payment_mode_id'=>4,
																					'trans_ref'=>$payment_reference_no,
																					'bank_id'=>$bank_id,
																					'PayCtrNum'=>$PayCtrNum,
																					'payment_ref_no'=>$payment_reference_no,
																					'transaction_id'=>$transactionId,
																					'pay_phone_no'=>$phone_no,
																					'zone_id'=>$zone_id,
																					'exchange_rate'=>$exchange_rate,
																					'created_on'=>Carbon::now(),
																					'created_by'=>499
																);
																$check_lims  = DB::connection('lims_db')->table('applicaton_invoices')
																						->where(array('invoice_no'=>$invoice_no))
																						->first();
																			if($check_lims){

																				 insertRecord('payments', $payment_data, $user_id,'lims_db');

																			}
															//save payments reference details 
															$this->funcSavePaymentreferenceno($amount_paid,$reference_no,$tracking_no,$receipt_id,$receipt_no,$invoice_no,$user_id,$currency_id,$exchange_rate);

															return true;
													}
											
										}
										else{
											//check if the control number macth
											$checkPayCtrNum = $payment_record->PayCtrNum;
											if($checkPayCtrNum != $PayCtrNum){
												
												
											}
											
										}
										
										
								}
							}
						}
						else{
							$rec = DB::table('tra_application_invoices as t1')
											->select(DB::raw("id as invoice_id,application_code,t1.*,application_id, invoice_no,applicant_id,applicant_name, zone_id,'' as zone_name, '' as group_no"))
											->where(array('invoice_no'=>$invoice_no))
											->first();
				
								if($rec){
									$tracking_no = $rec->tracking_no;
									$reference_no = $rec->reference_no;
									$applicant_id = $rec->applicant_id;
									$applicant_name = $rec->applicant_name;
									$zone_id = $rec->zone_id;
									$exchange_rate = $rec->exchange_rate;
									$invoice_id = $rec->invoice_id;
									$payment_type_id = 1;
									$check_retention  = DB::table('tra_product_retentions')
																					->where(array('invoice_id'=>$invoice_id))
																					->first();
									if($check_retention){
										$payment_type_id = 2;
									}
									if($CCy == 'USD'){
										$currency_id = 1;
									}
									else{
										$currency_id = 4;
									}
									$bank_id = getBankDetails($bank_name,$currency_id);
									
									$payment_data = array('application_code'=>$rec->application_code,
																				'application_id'=>$rec->application_id,
																				'reference_no'=>$rec->reference_no,
																				'tracking_no'=>$rec->tracking_no,
																				'amount_paid'=>$amount_paid,
																				'$applicant_name'=>$rec->applicant_name,
																				'receipt_no'=>$receipt_no,
																				'trans_date'=>Carbon::now(),
																				'currency_id'=>$currency_id,
																				'invoice_id'=>$rec->invoice_id,
																				'applicant_id'=>$rec->applicant_id,
																				'section_id'=>$rec->section_id,
																				'module_id'=>$rec->module_id,
																				'sub_module_id'=>$rec->sub_module_id,
																				'receipt_type_id'=>1,
																				'payment_mode_id'=>4,
																				'trans_ref'=>$payment_reference_no,
																				'bank_id'=>$bank_id,
																				'PayCtrNum'=>$PayCtrNum,
																				'payment_ref_no'=>$payment_reference_no,
																				'transaction_id'=>$transactionId,
																				'pay_phone_no'=>$phone_no,
																				'zone_id'=>$zone_id,
																				'payment_type_id'=>$payment_type_id,
																				'exchange_rate'=>$exchange_rate,
																				'created_on'=>Carbon::now(),
																				'created_by'=>499
										);

										$result = insertRecord('tra_payments', $payment_data, $user_id);
										if($result['success']){
													$receipt_id = $result['record_id'];
													$check_retention  = DB::table('tra_product_retentions')
																								->where(array('invoice_id'=>$invoice_id))
																								->first();
													if($check_retention){
															$retention_data = array('receipt_id'=>$receipt_id,'retention_status_id'=>2, 'dola'=>Carbon::now());
															DB::table('tra_product_retentions')
																	->where(array('invoice_id'=>$invoice_id))
																	->update($retention_data);

													}
													$payment_data = array(
																					'reference_no'=>$rec->reference_no,
																					'tracking_no'=>$rec->tracking_no,
																					'amount_paid'=>$amount_paid,
																					'$applicant_name'=>$rec->applicant_name,
																					'receipt_no'=>$receipt_no,
																					'trans_date'=>Carbon::now(),
																					'currency_id'=>$currency_id,
																					'invoice_id'=>$rec->invoice_id,
																					'applicant_id'=>$rec->applicant_id,
																					'receipt_type_id'=>1,
																					'payment_mode_id'=>4,
																					'trans_ref'=>$payment_reference_no,
																					'bank_id'=>$bank_id,
																					'PayCtrNum'=>$PayCtrNum,
																					'payment_ref_no'=>$payment_reference_no,
																					'transaction_id'=>$transactionId,
																					'pay_phone_no'=>$phone_no,
																					'zone_id'=>$zone_id,
																					'exchange_rate'=>$exchange_rate,
																					'created_on'=>Carbon::now(),
																					'created_by'=>499
																);
																$check_lims  = DB::connection('lims_db')->table('applicaton_invoices')
																						->where(array('invoice_no'=>$invoice_no))
																						->first();
																			if($check_lims){

																				 insertRecord('payments', $payment_data, $user_id,'lims_db');

																			}
															//save payments reference details 
															$this->funcSavePaymentreferenceno($amount_paid,$reference_no,$tracking_no,$receipt_id,$receipt_no,$invoice_no,$user_id,$currency_id,$exchange_rate);

															return true;
												}
												else{
													return false;
												}

											}
							
						}
						
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
		 public function checkUnstructuredApplicationRaisedQueries($application_code, $whereInArray = array(1, 3))
    {
        $hasQueries = 0;
        $qry = DB::table('checklistitems_queries as t1')
            ->where('t1.application_code', $application_code)
            ->whereIn('t1.status', $whereInArray);
        $queriesCount = $qry->count();
        if ($queriesCount > 0) {
            $hasQueries = 1;
        }
        return $hasQueries;
    }
		
		public function checkApplicationRaisedQueries($application_code)
    {
       
        try {
            $hasUnStructuredQueries = $this->checkUnstructuredApplicationRaisedQueries($application_code, array(1, 3));
          
            if ($hasUnStructuredQueries == 1) {
                $hasQueries = 1;
				
            } else {
                $hasQueries = 0;
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
        return $hasQueries;
    }
		public function submitPaymentNextProcessAutoSubmissions(Request $req){
		try{
			//query
			$user_id = 2;
			$application_code = $req->application_code;
			/**
			 * the query was not working correctly due the array of options added in the where clause
			 * 
			 * The query has been modiefied by addning nested joins and remaining with individual multiple conditions check
			 * 
			 * Modified by Eng. Sadam Omary on 2022-08-20
			 */

			$records = DB::table('tra_submissions as t1')
							->join('wf_workflow_stages as t2', 't1.current_stage', 't2.id')
							->leftJoin('wb_trader_account as t3', 't1.applicant_id', 't3.id')
							->join('wf_workflow_transitions as t4', 't2.id', 't4.stage_id')
							->join('wf_workflow_actions as t6', 't4.action_id', 't6.id')
							->join('tra_application_invoices as t7',function($join){
								$join->on( 't1.application_code', 't7.application_code');
								$join->on( 't7.module_id', 't1.module_id');
							})
							->join('tra_payments as t5', function($join){
								$join->on('t1.application_code', 't5.application_code');
								$join->on('t5.invoice_id','t7.id');
							})
							->select(DB::raw("t6.id as action,t1.current_stage as curr_stage_id,t4.nextstage_id as next_stage,t1.sub_module_id,
                                                t7.id as invoice_id, t1.section_id,t6.keep_status,t5.currency_id,  t1.process_id,t1.application_id,
                                                t1.application_code, t1.module_id, t1.reference_no, t1.tracking_no,
                                                t1.current_stage, t5.id as payment_id, t5.receipt_no, t5.trans_date,t3.email, t1.id "))

                            //->where(array('t2.is_receipting_stage'=>1, 't4.is_paymenttransition'=>1, 't1.isDone'<=0))
							->where('t2.is_receipting_stage', '=', 1)
							->where('t4.is_paymenttransition', '=', 1)
							->where('t1.isDone', '=', 0)
                            /*->where([	//['t7.module_id','=','t1.module_id'],
                            			//['t5.invoice_id','=','t7.id'],
                                        ['t2.is_receipting_stage', '=', 1],
                                        ['t4.is_paymenttransition', '=', 1],
                                        ['t1.isDone', '=', 0]
                                    ])*/

							->groupBY('t1.application_code')
	
							->orderBy('t1.id','desc');

					if(validateIsNumeric($application_code)){

						$records->where(array('t1.application_code'=>$application_code));

					}
				//dd($records->toSql());
					$records = $records->get();

					//dd($records);
					
				$res = array('success'=>true, 'message'=>'No Pending Application');
			if($records){
				foreach($records as $rec){

					$reference_no = (isset($rec->reference_no) && !is_null($rec->reference_no))?$rec->reference_no:$rec->tracking_no;
                    $currency = ($rec->currency_id==1)?'USD':'RWD';

					$tracking_no = $rec->tracking_no;

					$module_id = $rec->module_id;
					$payment_balance = getApplicationPaymentsRunningBalance( $rec->application_code, $rec->invoice_id);
					$hasQueries = $this->checkApplicationRaisedQueries($rec->application_code);
					if(is_array($payment_balance) && $hasQueries ==0){

						$running_balance =$payment_balance['running_balance'];

						if($running_balance <= 0 ){


							$table_name = getSingleRecordColValue('modules', array('id' => $rec->module_id), 'table_name');

							$request = new Request([
										  'directive_id'   => 1,
										  'action'   => $rec->action,
										  'curr_stage_id'   => $rec->curr_stage_id,
										  'next_stage'   => $rec->next_stage,
										  'section_id'   => $rec->section_id,
										  'sub_module_id'   => $rec->sub_module_id,
										  'application_code'   => $rec->application_code,
										  'keep_status'   => $rec->keep_status,
										  'module_id'   => $rec->module_id,
										  'application_id'   => $rec->application_id,
										  'process_id'   => $rec->process_id,
										  'table_name' => $table_name
							]);


						  $module_id = $rec->module_id;

						  $res =  "no submission";
							if ($module_id == 1) {//PRODUCT REGISTRATION
								$res = $this->processProductsApplicationSubmission($request);
							} else if ($module_id == 2) {//PREMISE REGISTRATION
								$res = $this->processPremiseApplicationSubmission($request);
							} else if ($module_id == 3) {//GMP APPLICATIONS
								$res = $this->processGmpApplicationsSubmission($request);
							} else if ($module_id == 7) {//CLINICAL TRIAL
								$res = $this->processClinicalTrialApplicationsSubmission($request);
							} else if ($module_id == 5) {//SURVEILLANCE
								$res = $this->processSurveillanceApplicationsSubmission($request);
							} else if ($module_id == 14) {
								$res = $this->processNormalApplicationSubmissionForPromoAndAdverts($request);
							} else if ($module_id == 6) {
								$res = $this->processProductsNotificationSubmission($request);
							}else if ($module_id == 4  || $module_id == 12) {//PRODUCT REGISTRATION
								$res = $this->processImportExportApplicationSubmission($request);
							} else if ($module_id == 15) {//PRODUCT REGISTRATION
								$res = $this->processDisposalApplicationSubmission($request);
							} else if ($module_id == 17) {//PRODUCT REGISTRATION
								$res = $this->processRevenueApplicationSubmission($request);
							}else if ($module_id == 18) {//inventory REGISTRATION
								$res = $this->processNormalApplicationSubmission($request);
							} else if ($module_id == 20) {//PRODUCT REGISTRATION
								$res = $this->processImportExportApplicationSubmission($request);
							} else if ($module_id == 21) {//LTR CHANGES
					             $res = $this->processLtrChangesApplicationSubmission($request);
							}else if ($module_id == 23) {//PV
								 $this->processClinicalTrialApplicationsSubmission($request);
							}else {
							   $res= "module not set";
							}
							
							$data = array('application_code'=>$rec->application_code,
										  'tracking_no'=>$rec->tracking_no,
										  'reference_no'=>$rec->reference_no,
										  'current_stage'=>$rec->next_stage,
										  'previous_stage'=>$rec->curr_stage_id,
										  'payment_balance'=>$running_balance,
										  'created_on'=>Carbon::now(),
										  'submission_date'=>Carbon::now(),
										  'created_by'=>499
							);
							 $res = insertRecord('tra_applicationpaymentssubmission', $data, $user_id);

							$data_update = array('isDone'=>1, 'date_released'=>Carbon::now(), 'released_by'=>$user_id);
							DB::table('tra_submissions')->where(array('id'=>$rec->id,'application_code'=>$rec->application_code))->update($data_update);
							
							 $module_name = getSingleRecordColValue('modules', array('id'=>$module_id), 'name');
							$process_name = getSingleRecordColValue('wf_tfdaprocesses', array('id'=>$rec->process_id), 'name');
							$process_stage = getSingleRecordColValue('wf_workflow_stages', array('id'=>$rec->next_stage), 'name');
							$email_address = 'irimsfinance@rwandafda.gov.rw';
							$vars = array(
								'{module_name}' => $module_name,
								'{process_name}' => $process_name,
								'{process_stage}' => $process_stage,
								'{application_no}' => $rec->reference_no .' '.$rec->tracking_no
							 );
							  $email_res =sendTemplatedApplicationNotificationEmail(36, $email_address,$vars);
							  
						}
						else{ 
						    // if there is pending currency send npotification email
							//applications with Balances

                            // $params = array(
                            //     'paymnent_id' => $rec->payment_id,
                            //     'receipt_id' => $rec->payment_id,
                            //     'reference_no' => $reference_no,
                            //     'base_Url' => $this->base_url,
                            //     'base_url' => $this->base_url
                            // );
                            //  $report = generateJasperReport('receiptReport', 'receipt_' . time(), 'pdf', $params);
                            //  $vars = array(
                            //         '{reference_no}' => $reference_no,
                            //         '{receipt_no}' => $rec->receipt_no,
                            //         '{trans_date}' => $rec->trans_date,
                            //         '{balance}' =>formatMoney($payment_balance['running_balance']),
                            //         '{currency}' => $currency
                            //     );
                            //     $applicant_email = $this->validateEmail($rec->email);
                            //     $email_notification = false;
                            //     if($applicant_email != ''){
                            //         $email_notification = applicationInvoiceEmail(20, $applicant_email, $vars, $report, 'receipt' . $rec->receipt_no);
                            //     }


						}

					}

					$res = array('success'=>true, 'message'=>'Application Submitted Successfully');
				}


			}else{
				$res = array('success'=>true, 'message'=>'No Pending Application');
			}


		 } catch (\Exception $exception) {
			$res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

		} catch (\Throwable $throwable) {
			$res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
		}
		 return response()->json($res, 200);


}  public function getApplicationWorkflowActionDetails($action_id)
    {
        $transition_details = DB::table('wf_workflow_actions')
            ->where('id', $action_id)
            ->first();
        if (is_null($transition_details)) {
            $res = array(
                'success' => false,
                'message' => 'Problem encountered getting action details!!'
            );
            echo json_encode($res);
            exit();
        }
        return $transition_details;
    }
public function processNormalApplicationSubmission(Request $request, $keep_status = false)
    {
        $application_id = $request->input('application_id');
        $table_name = $request->input('table_name');
        $module_id = $request->input('module_id');
        $prev_stage = $request->input('curr_stage_id');
        $action = $request->input('action');
        $to_stage = $request->input('next_stage');
        
        $is_dataammendment_request = $request->input('is_dataammendment_request');
        $is_inspection_submission = $request->input('is_inspection_submission');
        $user_id = $this->user_id;
        DB::beginTransaction();
        try {
            //get application_details
			
        $module_id = $request->input('module_id');
			if($table_name == ''){
				$table_name = getSingleRecordColValue('modules', array('id' => $module_id), 'table_name');
			}
			if($table_name == 'tra_product_notifications'){
				$table_name = 'tra_product_applications';
			}
            $application_details = DB::table($table_name)
                ->where('id', $application_id)
                ->first();
            if (is_null($application_details)) {
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while fetching application details!!'
                );
                echo json_encode($res);
                exit();
            }
           
            $application_status_id = getApplicationTransitionStatus($prev_stage, $action, $to_stage); 
            if ($keep_status == true) {//for approvals
                $application_status_id = $application_details->application_status_id;
            }
            $where = array(
                'id' => $application_id
            );
            if($is_dataammendment_request != 1){
                $app_update = array(
                    'workflow_stage_id' => $to_stage,
                    'application_status_id' => $application_status_id
                );
                $prev_data = getPreviousRecords($table_name, $where);
                if ($prev_data['success'] == false) {
                    echo json_encode($prev_data);
                    exit();
                }
                $update_res = updateRecord($table_name, $prev_data['results'], $where, $app_update, $user_id);
    
                if ($update_res['success'] == false) {
                    echo json_encode($update_res);
                    exit();
                }
            }
			//check the surveillace 
			if($module_id == 5){
				if($to_stage == 364){
					$samples_nextstage = 1;
					DB::table('tra_surveillance_sample_details as t1')
						->where('t1.application_id', $application_id)
						 ->where('t1.stage_id','<>', $samples_nextstage)
						 ->update(array('stage_id'=>$samples_nextstage));
				}
				else if($to_stage == 365){
					$samples_nextstage = 2;
					DB::table('tra_surveillance_sample_details as t1')
						->where('t1.application_id', $application_id)
						 ->where('t1.stage_id','<>', $samples_nextstage)
						 ->update(array('stage_id'=>$samples_nextstage));
				}
				
			}
            
            $this->updateApplicationSubmission($request, $application_details, $application_status_id);
           
 
        } catch (\Exception $exception) {
            DB::rollBack();
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
            echo json_encode($res);
            exit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
           return $res;
        }
    }public function updateApplicationSubmission($request, $application_details, $application_status_id)
    {
       
        $application_id = $request->input('application_id');
        $process_id = $request->input('process_id');
        $action = $request->input('action');
        $table_name = $request->input('table_name');
        $external_user_id= $request->input('external_user_id');
        
        $sub_module_id= $request->input('sub_module_id');
        $user_id = $this->user_id;
        try {
            //get process other details
            $process_details = DB::table('wf_tfdaprocesses')
                ->where('id', $process_id)
                ->first();
            if (is_null($process_details)) {
                DB::rollBack();
                $res = array(
                    'success' => false,
                    'message' => 'Problem encountered while fetching process details!!'
                );
                echo json_encode($res);
                exit();
            }
            $from_stage = $request->input('curr_stage_id');
            $to_stage = $request->input('next_stage');
            $responsible_user = $request->input('responsible_user');
            $remarks = $request->input('remarks');
            $urgency = $request->input('urgency');
            $directive_id = $request->input('directive_id');
            //application details
            $application_code = $application_details->application_code;
            $ref_no = $application_details->reference_no;
            $view_id = $application_details->view_id;
            $tracking_no = $application_details->tracking_no;
            $applicant_id = $application_details->applicant_id;
            $zone_id = (isset($application_details->zone_id) && $process_details->module_id == 18)?$application_details->zone_id:2;
            $sub_module_id = $application_details->sub_module_id;
            //process other details
            $module_id = $process_details->module_id;
           // $sub_module_id = $process_details->sub_module_id;
            $section_id = $process_details->section_id;
            
            //transitions
            //process inforamtion 
            $action_details = $this->getApplicationWorkflowActionDetails($action);
            $keep_status = $action_details->keep_status;
            $has_process_defination = $action_details->has_process_defination;
            $appprocess_defination_id = $action_details->appprocess_defination_id;

            $has_appdate_defination = $action_details->has_appdate_defination;
            $appdate_defination_id = $action_details->appdate_defination_id;
            //for inspection submissions
			$is_inspection_submission = 0;
           if(isset($action_details->is_inspection_submission)){
				 $is_inspection_submission = $action_details->is_inspection_submission;
			}
            $appdate_defination = getSingleRecordColValue('par_appprocess_definations', array('id'=>$appdate_defination_id),'code');
            $application_processdefdata = array();
            if($has_appdate_defination == 1){
                        $application_processdefdata =   array('application_code'=>$application_code,
                                                    'appprocess_defination_id'=>$appprocess_defination_id, 
                                                    'process_date'=>Carbon::NOW(), 
                                                    'created_by'=>$user_id, 
                                                    'created_on'=>Carbon::NOW());
            }
            $processtransition_data = $this->getActionTransitionDetails($action);
            $is_multi_submission = $processtransition_data->is_multi_submission;
            $multinextstage_id = $processtransition_data->multinextstage_id;

            //end 
            $transition_params = array(
                'application_id' => $application_id,
                'application_code' => $application_code,
                'application_status_id' => $application_status_id,
                'process_id' => $process_id,
                'from_stage' => $from_stage,
                'to_stage' => $to_stage,
                'author' => $user_id,
                'remarks' => $remarks,
                'directive_id' => $directive_id,
                'created_on' => Carbon::now(),
                'created_by' => $user_id
            );
            
            DB::table('tra_applications_transitions')
                ->insert($transition_params);
            //submissions
            $submission_params = array(
                'application_id' => $application_id,
                'process_id' => $process_id,
                'view_id' => $view_id,
                'application_code' => $application_code,
                'reference_no' => $ref_no,
                'tracking_no' => $tracking_no,
                'usr_from' => $user_id,
                'usr_to' => $responsible_user,
                'previous_stage' => $from_stage,
                'current_stage' => $to_stage,
                'module_id' => $module_id,
                'external_user_id'=>$external_user_id,
                'sub_module_id' => $sub_module_id,
                'section_id' => $section_id,
                'application_status_id' => $application_status_id,
                'urgency' => $urgency,
                'applicant_id' => $applicant_id,
                'zone_id' => $zone_id,
                'remarks' => $remarks,
                'directive_id' => $directive_id,
                'date_received' => Carbon::now(),
                'created_on' => Carbon::now(),
                'created_by' => $user_id
            );
			
            if(validateIsNumeric($external_user_id)){
                $submission_params['usr_to'] = $external_user_id;
                //send and email to the Extrenal user
                $module_name = getSingleRecordColValue('modules', array('id'=>$module_id), 'name');
                $process_name = getSingleRecordColValue('wf_tfdaprocesses', array('id'=>$process_id), 'name');
                $process_stage = getSingleRecordColValue('wf_workflow_stages', array('id'=>$to_stage), 'name');
                $email_address = aes_decrypt(getSingleRecordColValue('users', array('id'=>$external_user_id), 'email'));
                $vars = array(
                    '{module_name}' => $module_name,
                    '{process_name}' => $process_name,
                    '{process_stage}' => $process_stage,
                 );
                sendTemplatedApplicationNotificationEmail(16, $email_address,$vars);
                //send an email to the rest of the users 

            }
            if($is_inspection_submission == 1){
                
                  $inspectors = $this->getInspectorsIDList($module_id, $application_code);
               
                //loop through while updating submissions data
                 foreach ($inspectors as $inspector) {
                    //change usr_to
                    $submission_params['usr_to'] = $inspector->inspector_id;
                    //update submissions
                    DB::table('tra_submissions')->insert($submission_params);
                 }
             } else {
            
                    DB::table('tra_submissions')
                        ->insert($submission_params);
             }
          if ($action_details->update_portal_status == 1) {
                    $portal_status_id = $action_details->portal_status_id;
					$table_name = getSingleRecordColValue('modules', array('id' => $module_id), 'table_name');
				$portal_table = getPortalApplicationsTable($module_id);
         
                    $proceed = updatePortalApplicationStatus($application_id, $portal_status_id, $table_name, $portal_table);
                    
                }
                if($has_appdate_defination == 1){

                    $appdate_defination = array($appdate_defination=>Carbon::now(),'dola'=>Carbon::now());
                   /* $app_update = DB::table($table_name . ' as t1')
                                    ->where('application_code', $application_code)
                                    ->update($appdate_defination);
									*/
                }
                if(count($application_processdefdata) >0){
    
                    DB::table('tra_applications_processdefinations')
                             ->insert($application_processdefdata);
    
                }
                
            //check if Application is from inspection Submission
             $this->setIsDoneIFInspectionApplicationSubmission($application_code, $from_stage);
             
            updateInTraySubmissions($application_id, $application_code, $from_stage, $user_id);

            if($is_multi_submission == 1){
                $submission_params['current_stage'] =  $multinextstage_id;
                $submission_params['usr_to'] =  '';
                DB::table('tra_submissions')->insert($submission_params);
            }
            DB::commit();
            $res = array(
                'success' => true,
                'message' => 'Application 1Submitted Successfully!!'
            );
        }catch (\Exception $exception) {
			     DB::rollBack();
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
			DB::rollBack();
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
        }
       return $res;
    }
public function getActionTransitionDetails($action_id){
        $rec = DB::table('wf_workflow_transitions as t1')
                ->select('t1.*')
                ->where(array('action_id'=>$action_id))
                ->first();
        return $rec;
    }public function setIsDoneIFInspectionApplicationSubmission($application_code, $pre_stage)
    {
        $pre_prev_stage = DB::table('tra_submissions')
                        ->where('application_code',$application_code)
                        ->where('current_stage',$pre_stage)
                        ->orderBy('id','DESC')
                        ->select('previous_stage')
                        ->first();
                if($pre_prev_stage){
                            $actions = DB::table('wf_workflow_stages as t1')
                            ->join('wf_workflow_actions as t2', 't1.id', '=', 't2.stage_id')
                            ->where('t1.id',$pre_prev_stage->previous_stage)
                            ->select('t2.is_inspection_submission')
                            ->first();
                        $latest_entry = DB::table('tra_submissions')
                                    ->where('application_code',$application_code)
                                    ->orderBy('id','DESC')
                                    ->select('id')
                                    ->first();

                       $is_inspection_submission = 0;
           if(isset($action_details->is_inspection_submission)){
				 $is_inspection_submission = $action_details->is_inspection_submission;
			}
                        if($is_inspection_submission == 1){
                        $update = DB::table('tra_submissions')
                                    ->where('application_code', $application_code)
                                    ->where('id','<',$latest_entry->id)
                                    ->update(array('isDone'=> 1));
                        }

                }
                        
    }
}
