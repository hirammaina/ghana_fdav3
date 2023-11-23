<?php

namespace App\Modules\MigrationScripts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Modules\MigrationScripts\Entities\AppMigrationDataMdl;
use Illuminate\Support\Carbon;
class MigrationScriptsController extends Controller
{
    protected $user_id;

    function getTableColumnsDef($columns)
    {
        $column_defination = '';
        foreach ($columns as $col) {
            $column_defination .= $col . ',';
        }
        return $column_defination;
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

	public function initiatVetemigrateNewProductsDetails(Request $req){
    try{
        $res = 'Error occurred';
        $records_migrated =0;
        
        $table_name = "vet";
		$record_id = 505;// $req->record_id;
		
        $records = DB::table($table_name)->whereRaw("ProductBrandName is not null and ProductBrandName != ''")->get();
		//$records = DB::
		
				foreach($records as $rec){
                    

                     $RefNumber = $rec->RefNumber;
                    $sub_module_id = 7;
                    $module_id = 1;
                    $RECEIVED = formatDate($rec->DateofSubmission);
                    $ProductBrandName = $rec->ProductBrandName;
                    $ProductCommonName = $rec->ProductCommonName;
                  
                    $ProductStrength = $rec->ProductStrength;
                    $DOSAGE_FORM = $rec->ProductdosageForm;
                    $TherapeuticGroup = $rec->TherapeuticGroup;
                    $TherapeuticCode = $rec->TherapeuticCode;

                    $ManufacturerName = $rec->ManufacturerName;
                    $ManufacturesiteCountry = $rec->ManufacturesiteCountry;
                    $ManufacturesitePhysicalAddress = $rec->ManufacturesitePhysicalAddress;
                    $Manufacturesiteregion = $rec->Manufacturesiteregion;
                    $ManufacturerEmail = $rec->ManufacturerEmail;
                    
                    $ApplicantName = $rec->ApplicantName;
                    $ApplicantCountry = $rec->ApplicantCountry;
                    $ApplicantPhysicalAddress = $rec->ApplicantPhysicalAddress;
                    $ApplicantRegion = $rec->ApplicantRegion;
                    $ApplicantEmail = $rec->ApplicantEmail;
                    
                    $LocalTechnicalRepresentativeName = $rec->LocalTechnicalRepresentativeName;
                    $LTRCountry = $rec->LTRCountry;
                    $LTRPhysicalAddress = $rec->LTRPhysicalAddress;
                    $LTREMAIL = $rec->LTREMAIL;

                    $DateofScreening = $rec->DateofScreening;
                    $REGISTRATIONSTATUS = $rec->REGISTRATIONSTATUS;
                    $MarketAuthorizationCertificateNo = $rec->MarketAuthorizationCertificateNo;
                    $MAcertificateexpirydate = $rec->MAcertificateexpirydate;
                    $DateofMAcertificateissuedtoapplicant = $rec->DateofMAcertificateissuedtoapplicant;
                    
                    $product_origin_id = 2;
                    if($ManufacturesiteCountry == 'Rwanda'){
                        $product_origin_id = 1;

                    }
                $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$RefNumber))->first();
                if(!$app_record){
                   
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    
                    $classification_id = 5;
                    
                    $common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$ProductCommonName, 'description'=>$ProductCommonName),array('name'=>$ProductCommonName),'Common Names');
                   
                    $dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('name'=>$DOSAGE_FORM, 'description'=>$DOSAGE_FORM),array('name'=>$DOSAGE_FORM),'DoSage form');
                   
                    $product_information = array('product_origin_id'=>$product_origin_id,
                                                 'common_name_id'=>$common_name_id,
                                                 'prodclass_category_id'=>$prodclass_category_id,
                                                 'classification_id'=>$classification_id,
                                                 'brand_name'=>$ProductBrandName,
                                                 'physical_description'=>$ProductBrandName,
                                                 'dosage_form_id'=>$dosage_form_id,
                                                 'product_strength'=>$ProductStrength,
                                                 'therapeutic_group'=>$TherapeuticGroup,
                                                 'therapeutic_code'=>$TherapeuticCode,
                                                // 'indications'=>$INDICATIONS_,
                                               //  'route_of_administration_id'=>$route_of_administration_id,
                                                 'section_id'=>$section_id,
                                              //   'shelf_life'=>$SHELF_LIFE_MONTHS,
                                                // 'shelflifeduration_desc'=>1
                                             );
                                           
                                             $product_information['created_by'] = '0';
                                             $product_information['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                                             if($resp['success']){
                                                 $product_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
                    //product_ingreidents
                    /*$ingredient_id = $this->saveProductDataEntry('par_ingredients_details',array('name'=>$ACTIVE_INGREDIENTS, 'description'=>$ACTIVE_INGREDIENTS),array('name'=>$ACTIVE_INGREDIENTS),'Ingredietns details');
                    $ingredients_data = array('product_id'=>$product_id, 
                                 'ingredient_id'=>$ingredient_id,
                                'inclusion_reason_id'=>9);
                    
                    DB::table('tra_product_ingredients')->insert($ingredients_data);
                    //save product manufacturing site details 
                     */
                   $mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManufacturesiteCountry, 'description'=>$ManufacturesiteCountry),array('name'=>$ManufacturesiteCountry),'Country');
    
                   $manregion_id = $this->saveProductDataEntry('par_regions',array('name'=>$Manufacturesiteregion, 'description'=>$Manufacturesiteregion, 'country_id'=>$mancountry_id),array('name'=>$Manufacturesiteregion),'Region ');

                    $manufacturer_data = array('name'=>$ManufacturerName, 
                                               // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManufacturesitePhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManufacturerEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManufacturerName, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$ManufacturerName, 
                                                // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManufacturesitePhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManufacturerEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$ManufacturerName, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);
                    //save product applicant_details 
                   
                   $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
    
                   
                   $data = (object)array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     //save product local representative 
                     $local_agent_id = 0;
                     if($LocalTechnicalRepresentativeName != ''){
                        $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$LTRCountry, 'description'=>$LTRCountry),array('name'=>$LTRCountry),'Country ');
    
                        $region_id = 0;
                        $data = (object)array('name'=>$LocalTechnicalRepresentativeName, 
                                     'tpin_no'=>0, 
                                     'contact_person'=>$LocalTechnicalRepresentativeName,
                                     'country_id'=>$country_id, 
                                     'region_id'=>$region_id, 
                                     'physical_address'=> $LTRPhysicalAddress,
                                     'postal_address'=>$LTRPhysicalAddress, 
                                    // 'telephone_no'=>$REPRESENTATIVE_PHONE, 
                                     'email'=>$LTREMAIL, 
                                     'created_by'=>'Migration',
                                     'created_on'=>Carbon::now(),
                                     'status_id'=>1
                                 );
                         $local_agent_id = $this->saveTraderInformationDetails($data);
    
                     }
                    
                     $application_status_id = 5;
                     if($MarketAuthorizationCertificateNo != '' && $MarketAuthorizationCertificateNo != 'nan'){
                        $application_status_id = 6;
                     }
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
                             $process_id = $process_data->id;
                       
                     }
                    //save product application details 
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
                     'application_code'=>$application_code, 
                     'reference_no'=>$RefNumber, 
                     'view_id'=>$view_id, 
                     'applicant_id'=>$applicant_id, 
                     'local_agent_id'=>$local_agent_id, 
                     'sub_module_id'=>$sub_module_id, 
                     'assessmentprocedure_type_id'=>1, 
                     'section_id'=>$section_id, 
                     'product_id'=>$product_id, 
                     'fasttrack_option_id'=>2, 
                     'process_id'=>$process_id, 
                     'module_id'=>$module_id, 
                     'prodclass_category_id'=>$prodclass_category_id, 
                     'date_added'=>formatDate($RECEIVED), 
                     'submission_date'=>formatDate($RECEIVED), 
                     'application_status_id'=>$application_status_id, 
                     'refno_generated'=>1, 
                     'created_on'=>Carbon::now(), 
                     'created_by'=>'Migration', 
                     
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($MarketAuthorizationCertificateNo != '' && $MarketAuthorizationCertificateNo != 'nan'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$MarketAuthorizationCertificateNo))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$MarketAuthorizationCertificateNo,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($DateofMAcertificateissuedtoapplicant),
                            'certificate_issue_date'=>formatDate($DateofMAcertificateissuedtoapplicant),
                            'expiry_date'=>formatDate($MAcertificateexpirydate),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$MarketAuthorizationCertificateNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($DateofMAcertificateissuedtoapplicant),
                                    'registration_no'=>$MarketAuthorizationCertificateNo,
                                    'reg_applicant_id'=>$applicant_id,
                                    'reg_local_agent_id'=>$local_agent_id,
                                    'active_app_referenceno'=>$RefNumber,
                                    'active_application_code'=>$application_code,
                                    'expiry_date'=>formatDate($MAcertificateexpirydate),
                                    'approval_date'=>formatDate($DateofMAcertificateissuedtoapplicant),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
                    }
                    else{
                        $regdata = array('tra_product_id'=>$product_id,
                                'validity_status_id'=>1,
                                'registration_status_id'=>1,
                                
                                'reg_applicant_id'=>$applicant_id,
                                'reg_local_agent_id'=>$local_agent_id,
                                'active_app_referenceno'=>$RefNumber,
                                'active_application_code'=>$application_code,
                                'created_on'=>Carbon::now()
                        );
                        $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                        if($resp['success']){
                            $reg_id = $resp['record_id'];
                        }
                        else{
                                print_r($resp);
                                exit();
                        }
                        DB::table('tra_product_applications')
                        ->where(array('application_code'=>$application_code))
                        ->update(array('reg_product_id'=>$reg_id));
    
                    }
                    //save product register
                    $res = "Application Migration Successfully Application No: ".$RefNumber.'</br>';
                   
                }
                else{
                    //update the records 
                    $product_id = $app_record->product_id;
                    $applicant_id = $app_record->applicant_id;
                    $application_code = $app_record->application_code;
                   
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    
                    $classification_id = 5;
                    
                    $common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$ProductCommonName, 'description'=>$ProductCommonName),array('name'=>$ProductCommonName),'Common Names');
                   
                    $dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('name'=>$DOSAGE_FORM, 'description'=>$DOSAGE_FORM),array('name'=>$DOSAGE_FORM),'DoSage form');
                   
                    $product_information = array('product_origin_id'=>$product_origin_id,
                                                 'common_name_id'=>$common_name_id,
                                                 'prodclass_category_id'=>$prodclass_category_id,
                                                 'classification_id'=>$classification_id,
                                                 'brand_name'=>$ProductBrandName,
                                                 'physical_description'=>$ProductBrandName,
                                                 'dosage_form_id'=>$dosage_form_id,
                                                 'product_strength'=>$ProductStrength,
                                                 'therapeutic_group'=>$TherapeuticGroup,
                                                 'therapeutic_code'=>$TherapeuticCode,
                                                // 'indications'=>$INDICATIONS_,
                                               //  'route_of_administration_id'=>$route_of_administration_id,
                                                 'section_id'=>$section_id,
                                              //   'shelf_life'=>$SHELF_LIFE_MONTHS,
                                                // 'shelflifeduration_desc'=>1
                                             );
                                           
                    DB::table('tra_product_information')->where(array('id'=>$product_id))->update($product_information);

                  
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
                                             
                   $trader_data = array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    
                             DB::table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
                             DB::connection('portal_db')->table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);

                    $res = "Application Already Migrated and Updated Successfully Application No: ".$RefNumber.'</br>';
                   
                }
                print_r($res);

                $records_migrated ++;
                
            }  
		
       //     $this->saveMigrationLogsData('initiatemigrateRenewalProductsDetails',$records_migrated=0);
    
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
function saveProductDataEntry($table_name, $data,$where,$title){
    $record_id = 0;

    if($data['name'] != ''){
        $record = DB::table($table_name)->where($where)->first();
        if($record){
    
            $record_id = $record->id;
    
        }
        else{
            
            $data['created_on'] = Carbon::now();
            $data['created_by'] = 'Migration';
           
            $resp =  insertRecord($table_name, $data, 'Migration');
            
            if($resp['success']){
                $record_id = $resp['record_id'];
            }
            else{
                print_r($title.'</br>');
                print_r($resp);
                exit();
            }
        }
    }
   
   return $record_id;

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
                        'status_id'=>5,//as actve
                        'account_roles_id'=>1,
                        'country_id'=>$rec->country_id,
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

                DB::connection('portal_db')->table('wb_trader_account')->insert($rec);
				   if($data_check->email != ''){
					    DB::connection('portal_db')->table('wb_traderauthorised_users')->insert($user_data);

				   }
               
    }
    else{
      $trader_id = $check->id;
    }
   
    return $trader_id;
}
public function getProductApplicationReferenceCodes($application_details)
    {
         
        $section_code = getSingleRecordColValue('par_sections', array('id' => $application_details->section_id), 'code');
        $class_code = getSingleRecordColValue('par_classifications', array('id' => $application_details->classification_id), 'code');
       
        $codes_array = array(
            'section_code' => $section_code,
            'prod_type_code' => $section_code,
            'gmp_type' => 'O',
            'zone_code' => 00,
            'class_code' => $class_code,
           
        );  
              
        return $codes_array;
    }

public function initiateCosmeticsProductAuthorisationMigration(Request $req){
	 try{
        $res = 'Error occurred';
        $records_migrated =0;
        $sub_module_id  =30;
        $module_id  =1;
        $section_id  =3;
        
        $table_name = 'authoised_cosmetics';
        $record_id = $req->record_id;//->where('id','>=',239)
		 $records = DB::table($table_name)->get();
		//$records = DB::
		
		foreach($records as $rec){
			$ReferenceNo = $rec->ReferenceNo;
			 $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
			//if(!$app_record || $ReferenceNo ==''){
				$process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                    if($process_data){
                        
                            $process_id = $process_data->id;
                      
                    }
					$RECEIVED = Carbon::now();
					$ReferenceNo = $rec->ReferenceNo;
					$app_id = $rec->id; 
					$classification_id = 27;
					$prodclass_category_id = 17;
					$product_origin_id = 1;
					$app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
					if($ReferenceNo == ''){
							$ReferenceNo = $tracking_details['tracking_no'];
                   
					}

					$Product_Brand_Name = $rec->Product_Brand_Name;					
					$Product_Common_Name = $rec->Product_Common_Name;					
					$Product_Strength = $rec->Product_Strength;					
					$Product_Dosage_Form = $rec->Product_Dosage_Form;					
					$Manufacturer = $rec->Manufacturer;					
					$shelf_life = $rec->shelf_life;					
					$Manufacturer_Country = $rec->Manufacturer_Country;					
					$product_pack_size = $rec->product_pack_size;					
					$Applicant_Name = $rec->Applicant_Name;					
					$MAHs_Country = $rec->MAHs_Country;					
					$Local_Technical_Representative = $rec->Local_Technical_Representative;					
					
					$common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$Product_Common_Name, 'description'=>$Product_Common_Name),array('name'=>$Product_Common_Name),'Common Names');	
					$dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('section_id'=>$section_id, 'name'=>$Product_Dosage_Form, 'description'=>$Product_Dosage_Form),array('name'=>$Product_Dosage_Form),'Dosage Forms');	
					 $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$Product_Brand_Name,
                                             'physical_description'=>$Product_Common_Name,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_strength'=>$Product_Strength,
                                             'fpp_manufacturer'=>$Manufacturer,
                                             'fpp_manufacturer_country'=>$Manufacturer_Country,
                                             'product_pack_size'=>$product_pack_size,
                                             'shelf_life'=>$shelf_life,
                                             'section_id'=>$section_id,
                                          
                                         );
					
					
					$product_information['created_by'] = '0';
                    $product_information['created_on'] = Carbon::now();
                    $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                    if($resp['success']){
                            $product_id = $resp['record_id'];
                    }
                    else{
                            print_r($resp);
                            exit();
                    }
					 $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
							 'application_code'=>$application_code, 
							 'reference_no'=>$ReferenceNo, 
							 'view_id'=>$view_id, 
							 'mah_name'=>$Applicant_Name, 
							 'mah_country'=>$MAHs_Country, 
							 'ltr_name'=>$Local_Technical_Representative, 
							 'sub_module_id'=>$sub_module_id, 
							 'assessmentprocedure_type_id'=>1, 
							 'section_id'=>$section_id, 
							 'product_id'=>$product_id, 
							 'fasttrack_option_id'=>2, 
							 'process_id'=>$process_id, 
							 'module_id'=>$module_id, 
							 'prodclass_category_id'=>$prodclass_category_id, 
							 'date_added'=>formatDate($RECEIVED), 
							 'submission_date'=>formatDate($RECEIVED), 
							 //'application_status_id'=>$application_status_id, 
							 'refno_generated'=>1, 
							 'created_on'=>Carbon::now(), 
							 'created_by'=>'Migration', 
							 
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
					if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
					$app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$ReferenceNo))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$ReferenceNo,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
					$app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$ReferenceNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_no'=>$ReferenceNo,
                                    'active_app_referenceno'=>$ReferenceNo,
                                    'active_application_code'=>$application_code,
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
						DB::table('tra_product_authorisationnot')
                            ->where(array('id'=>$app_id))
                            ->update(array('ReferenceNo'=>$ReferenceNo));
						
                         $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
				
			//}
					
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
public function initiatevetProductAuthorisationMigration(Request $req){
	 try{
        $res = 'Error occurred';
        $records_migrated =0;
        $sub_module_id  =7;
        $module_id  =1;
        $section_id  =2;
        
        $table_name = 'human_medicinal_products';
        $record_id = $req->record_id;//->where('id','>=',239)
		 $records = DB::table($table_name)
							
							->get();
		//$records = DB::
		
		foreach($records as $rec){
			$ReferenceNo = $rec->ReferenceNo;
			 $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
			if(!$app_record){
				$process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                    if($process_data){
                        
                            $process_id = $process_data->id;
                      
					  
                    }
					$RECEIVED = Carbon::now();
					$ReferenceNo = $rec->ReferenceNo;
					$app_id = $rec->id; 
					$classification_id = 2;
					$prodclass_category_id = 1;
					$product_category_id = 10;
					$product_origin_id = 1;
					$app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
					if($ReferenceNo == ''){
							$ReferenceNo = $tracking_details['tracking_no'];
					}

					$expiry_date = $rec->expiry_date;					
					$registration_date = $rec->registration_date;					
					$Product_Brand_Name = $rec->PRODUCT_BRAND_NAME;					
					$Product_Common_Name = $rec->PRODUCT_COMMON_NAME;					
					$Product_Strength = $rec->PRODUCT_STRENGTH;					
					$Product_Dosage_Form = $rec->PRODUCT_DOSAGE_FORM;					
					$Manufacturer = $rec->MANUFACTURER_S_NAMES;					
					$shelf_life = $rec->shelf_life;					
					$Manufacturer_Country = $rec->MANUFACTURER_S_COUNTRY;					
					$product_pack_size = $rec->PRODUCT_PACK_SIZE;					
					$Applicant_Name = $rec->Applicant_Name;					
					$MAHs_Country = $rec->MAHs_Country;					
					$Local_Technical_Representative = $rec->Local_Technical_Representative;					
					
					$common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$Product_Common_Name, 'description'=>$Product_Common_Name),array('name'=>$Product_Common_Name),'Common Names');	
					$dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('section_id'=>$section_id, 'name'=>$Product_Dosage_Form, 'description'=>$Product_Dosage_Form),array('name'=>$Product_Dosage_Form),'Dosage Forms');
					
					 $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,                                             
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$Product_Brand_Name,
                                             'physical_description'=>$Product_Common_Name,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_category_id'=>$product_category_id,
                                             'product_strength'=>$Product_Strength,
                                             'fpp_manufacturer'=>$Manufacturer,
                                             'fpp_manufacturer_country'=>$Manufacturer_Country,
                                             'product_pack_size'=>$product_pack_size,
                                             'shelf_life'=>$shelf_life,
                                             'section_id'=>$section_id
                                         );
					
					$product_information['created_by'] = '0';
                    $product_information['created_on'] = Carbon::now();
                    $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                    if($resp['success']){
                            $product_id = $resp['record_id'];
                    }
                    else{
                            print_r($resp);
                            exit();
                    }
					$mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$Manufacturer_Country, 'description'=>$Manufacturer_Country),array('name'=>$Manufacturer_Country),'Country');
					$manufacturer_data = array('name'=>$Manufacturer,
                                                'country_id'=>$mancountry_id
                                        );
					$manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$Manufacturer, 'country_id'=>$mancountry_id),'Manufacturer Id');       

					$product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id,
                                                'manufacturer_role_id'=>1, 'created_on'=>Carbon::now(), 
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);
					
					 $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
							 'application_code'=>$application_code, 
							 'reference_no'=>$ReferenceNo, 
							 'view_id'=>$view_id, 
							 'mah_name'=>$Applicant_Name, 
							 'mah_country'=>$MAHs_Country, 
							 'ltr_name'=>$Local_Technical_Representative, 
							 'sub_module_id'=>$sub_module_id, 
							 'assessmentprocedure_type_id'=>1, 
							 'section_id'=>$section_id, 
							 'product_id'=>$product_id, 
							 'fasttrack_option_id'=>2, 
							 'process_id'=>$process_id, 
							 'module_id'=>$module_id, 
							 'prodclass_category_id'=>$prodclass_category_id, 
							 'date_added'=>formatDate($RECEIVED), 
							 'submission_date'=>formatDate($RECEIVED), 
							 //'application_status_id'=>$application_status_id, 
							 'refno_generated'=>1, 
							 'created_on'=>Carbon::now(), 
							 'created_by'=>'Migration', 
							 
                    );
					
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
					if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
					$app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$ReferenceNo))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$ReferenceNo,
                            'approval_date'=>formatDate($registration_date),
                            'expiry_date'=>formatDate($expiry_date),
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
					$app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$ReferenceNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_no'=>$ReferenceNo,
                                    'active_app_referenceno'=>$ReferenceNo,
                                    'active_application_code'=>$application_code,
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
						//DB::table('human_biological_products')
                         //   ->where(array('id'=>$app_id))
                           // ->update(array('ReferenceNo'=>$ReferenceNo));
						
                         $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
						 print_r('Records Migrated');
			}
			else{
				
				print_r('Records Exists');
			}
					
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



public function initiateMedProductDatabaseMigration(Request $req){
	 try{
        $res = 'Error occurred';
        $records_migrated =0;
        $sub_module_id  =7;
        $module_id  =1;
        $section_id  =2;
        
        $table_name = 'human_medicinal_productsjuly';
        $table_name = 'herbal_medicinal_productsjuly';
        $record_id = $req->record_id;
		 $records = DB::table($table_name)
				
				->orderBy('No', 'desc')
				->get();
		//$records = DB::
		
		foreach($records as $rec){
			$ReferenceNo = $rec->Ref_Number;
			  
			 $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
			 
			if(!$app_record){
				
				$process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                    if($process_data){
                        
                            $process_id = $process_data->id;
                      
                    }
					 
					$RECEIVED = $rec->Submission_Date;
					$app_id = $rec->id;
					$classification_id = 1;
					$prodclass_category_id = 1;
					$product_category_id = 10;
					$product_origin_id = 1;
					$app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    /*
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
					*/
                    //$ReferenceNo = $tracking_details['tracking_no'];
                
					$Product_Brand_Name = $rec->Product_Brand_Name;					
					$Product_Common_Name = mb_detect_encoding($rec->Product_Common_Name_INN);					
					$Product_Strength = $rec->Product_Strength;					
					$Product_Dosage_Form = $rec->Product_dosage_Form;					
					$ManufacturerName = mb_detect_encoding($rec->Manufacturer_Name);					
					$Manufacturer_Country = $rec->Country;					
					$ManufacturesitePhysicalAddress = mb_detect_encoding($rec->Manufacturer_Name);					
					$Applicant_Name = $rec->Applicant_Name;			
					$product_pack_size = '';			
						
					$MAHs_Country = $rec->Country_1;					
					$Local_Technical_Representative = $rec->Local_Technical_Representative_LTR;		

					$mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$MANUFACTURER_COUNTRY, 'description'=>$MANUFACTURER_COUNTRY),array('name'=>$MANUFACTURER_COUNTRY),'Country');
    
                    $manufacturer_data = array('name'=>$ManufacturerName, 
                                                'physical_address'=>$ManufacturesitePhysicalAddress,
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManufacturerName, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$ManufacturerName, 
                                                'physical_address'=>$ManufacturesitePhysicalAddress,
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$ManufacturerName, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    	
					$common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$Product_Common_Name, 'description'=>$Product_Common_Name),array('name'=>$Product_Common_Name),'Common Names');	
					$dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('section_id'=>$section_id, 'name'=>$Product_Dosage_Form, 'description'=>$Product_Dosage_Form),array('name'=>$Product_Dosage_Form),'Dosage Forms');	
					
					$route_of_administration_id = $this->saveProductDataEntry('par_route_of_administration',array('section_id'=>$section_id, 'name'=>$Route_of_administration, 'description'=>$Route_of_administration),array('name'=>$Route_of_administration),'Route_of_administration');	
					
					 $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             'product_category_id'=>$product_category_id,
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'product_pack_size'=>$product_pack_size,
                                             //'shelf_life'=>$SHELF_LIFE,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$Product_Brand_Name,
                                             'physical_description'=>$Product_Common_Name,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'route_of_administration_id'=>$route_of_administration_id,
                                             'product_strength'=>$Product_Strength,
                                             'fpp_manufacturer'=>$Manufacturer,
                                             'fpp_manufacturer_country'=>$Manufacturer_Country,
                                             'section_id'=>$section_id,
                                          
                                         );
					
					
					$product_information['created_by'] = '0';
                    $product_information['created_on'] = Carbon::now();
                    $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                    if($resp['success']){
                            $product_id = $resp['record_id'];
                    }
                    else{
                            print_r($resp);
                            exit();
                    }
					
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);				
					 $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
							 'application_code'=>$application_code, 
							 'reference_no'=>$ReferenceNo, 
							 'view_id'=>$view_id, 
							 'mah_name'=>$Applicant_Name, 
							 'mah_country'=>$MAHs_Country, 
							 'ltr_name'=>$Local_Technical_Representative, 
							 'sub_module_id'=>$sub_module_id, 
							 'assessmentprocedure_type_id'=>1, 
							 'section_id'=>$section_id, 
							 'product_id'=>$product_id, 
							 'fasttrack_option_id'=>2, 
							 'process_id'=>$process_id, 
							 'module_id'=>$module_id, 
							 'prodclass_category_id'=>$prodclass_category_id, 
							 'date_added'=>formatDate($RECEIVED), 
							 'submission_date'=>formatDate($RECEIVED), 
							 //'application_status_id'=>$application_status_id, 
							 'refno_generated'=>1, 
							 'created_on'=>Carbon::now(), 
							 'created_by'=>'Migration', 
							 
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
					if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
					
                         $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
				print_r($res);
			}
			else{
				
                $res = "Already Migrated and Mapped Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
				print_r($res);
				
				
			}
					
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

public function initiateMedProductAuthorisationMigration(Request $req){
	 try{
        $res = 'Error occurred';
        $records_migrated =0;
        $sub_module_id  =7;
        $module_id  =1;
        $section_id  =2;
        
        $table_name = 'humanmedicineaugust';
        $record_id = $req->record_id;
		 $records = DB::table($table_name)
				->get();
		//$records = DB::
		
		
		foreach($records as $rec){
			$REGISTRATION_NO = $rec->REGISTRATION_NO;
			$REGISTRATION_DATE = $rec->REGISTRATION_DATE;
			$EXPIRY_DATE = $rec->EXPIRY_DATE;
			$ReferenceNo = $rec->REGISTRATION_NO;
			  
			 $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
			 
			if(!$app_record){
				
				$process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                    if($process_data){
                        
                            $process_id = $process_data->id;
                      
                    }
					 
					$RECEIVED = Carbon::now();
					$ReferenceNo = $rec->REGISTRATION_NO;
					$app_id = $rec->id;
					$classification_id = 2;
					$prodclass_category_id = 1;
					$product_category_id = 10;
					$product_origin_id = 1;
					$app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    /*
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
					*/
                    //$ReferenceNo = $tracking_details['tracking_no'];
                
					$Product_Brand_Name = $rec->PRODUCT_BRAND_NAME;					
					//$Product_Common_Name = mb_detect_encoding($rec->ACTIVE_PHARMACEUTICAL_INGREDIENTS);					
					$Product_Common_Name = ($rec->GENERIC_NAME);					
					$Product_Strength = $rec->DOSAGE_STRENGTH;					
					$Product_Dosage_Form = $rec->DOSAGE_FORM;					
									
					$ManufacturerName = ($rec->MANUFACTURER_S_ADDRES);					
					$Manufacturer_Country = $rec->MANUFACTURER_COUNTRY;					
					$ManufacturesitePhysicalAddress = ($rec->MANUFACTURER_S_ADDRES);					
					$Applicant_Name = $rec->MARKETING_AUTHORIZATION_HOLDER_MAH;			
					$product_pack_size = $rec->PACKAGING_TYPE.' '.$rec->PACK_SIZE;			
					$SHELF_LIFE = $rec->SHELF_LIFE;			
						
					$MAHs_Country = $rec->MANUFACTURER_COUNTRY;					
					$Local_Technical_Representative = $rec->LOCAL_TECHNICAL_REPRESENTATIVE_LTR;		

					$mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$MANUFACTURER_COUNTRY, 'description'=>$MANUFACTURER_COUNTRY),array('name'=>$MANUFACTURER_COUNTRY),'Country');
    
                    $manufacturer_data = array('name'=>$ManufacturerName, 
                                                'physical_address'=>$ManufacturesitePhysicalAddress,
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManufacturerName, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$ManufacturerName, 
                                                'physical_address'=>$ManufacturesitePhysicalAddress,
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$ManufacturerName, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    	
					$common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$Product_Common_Name, 'description'=>$Product_Common_Name),array('name'=>$Product_Common_Name),'Common Names');	
					$dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('section_id'=>$section_id, 'name'=>$Product_Dosage_Form, 'description'=>$Product_Dosage_Form),array('name'=>$Product_Dosage_Form),'Dosage Forms');	
					 $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             'product_category_id'=>$product_category_id,
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'product_pack_size'=>$product_pack_size,
                                             'shelf_life'=>$SHELF_LIFE,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$Product_Brand_Name,
                                             'physical_description'=>$Product_Common_Name,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_strength'=>$Product_Strength,
                                             'fpp_manufacturer'=>$Manufacturer,
                                             'fpp_manufacturer_country'=>$Manufacturer_Country,
                                             'section_id'=>$section_id,
                                          
                                         );
					
					
					$product_information['created_by'] = '0';
                    $product_information['created_on'] = Carbon::now();
                    $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                    if($resp['success']){
                            $product_id = $resp['record_id'];
                    }
                    else{
                            print_r($resp);
                            exit();
                    }
					
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);				
					 $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
							 'application_code'=>$application_code, 
							 'reference_no'=>$ReferenceNo, 
							 'view_id'=>$view_id, 
							 'mah_name'=>$Applicant_Name, 
							 'mah_country'=>$MAHs_Country, 
							 'ltr_name'=>$Local_Technical_Representative, 
							 'sub_module_id'=>$sub_module_id, 
							 'assessmentprocedure_type_id'=>1, 
							 'section_id'=>$section_id, 
							 'product_id'=>$product_id, 
							 'fasttrack_option_id'=>2, 
							 'process_id'=>$process_id, 
							 'module_id'=>$module_id, 
							 'prodclass_category_id'=>$prodclass_category_id, 
							 'date_added'=>formatDate($RECEIVED), 
							 'submission_date'=>formatDate($RECEIVED), 
							 //'application_status_id'=>$application_status_id, 
							 'refno_generated'=>1, 
							 'created_on'=>Carbon::now(), 
							 'created_by'=>'Migration', 
							 
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
					if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
					$app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$REGISTRATION_NO))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$REGISTRATION_NO,
							'certificate_issue_date'=>formatDate($REGISTRATION_DATE),
							'expiry_date'=>formatDate($EXPIRY_DATE),
							'approval_date'=>formatDate($REGISTRATION_DATE),
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
					$app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$ReferenceNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_no'=>$REGISTRATION_NO,
                                    'active_app_referenceno'=>$REGISTRATION_NO,
                                    'active_application_code'=>$application_code,
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
					
                         $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
				print_r($res);
			}
			else{
				$application_code = $app_record->application_code;
				$application_id = $app_record->id;
				$app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$REGISTRATION_NO))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$REGISTRATION_NO,
							'certificate_issue_date'=>formatDate($REGISTRATION_DATE),
							'expiry_date'=>formatDate($EXPIRY_DATE),
							'approval_date'=>formatDate($REGISTRATION_DATE),
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
					$app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$ReferenceNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_no'=>$REGISTRATION_NO,
                                    'active_app_referenceno'=>$REGISTRATION_NO,
                                    'active_application_code'=>$application_code,
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
					
                         $res = "Already Migrated and Mapped Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
				print_r($res);
				
				
			}
					
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
public function initiatNewFoodProductsemigrateDetails(Request $req){
    try{
        $res = 'Error occurred';
        $records_migrated =0;
        
        $table_name = $req->table_name;
        $record_id = $req->record_id;
		
        $records = DB::table($table_name)->whereRaw("BrandName is not null and BrandName != ''")->get();
		//$records = DB::
		
				foreach($records as $rec){
                                         $ReferenceNo = trim($rec->ReferenceNo);
                                        
                                         $section_id = $rec->section_id;
                                         $prodclass_category_id = $rec->prodclass_category_id;
                                         
                    $sub_module_id = 7;
                    $module_id = 1;
                    $process_id = 0;
                    $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                    if($process_data){
                        
                            $process_id = $process_data->id;
                      
                    }
                    
                    if($rec->DateSubmitted != '' && $rec->DateSubmitted != null && $rec->DateSubmitted != ' '){
                     
                        $RECEIVED = formatDate($rec->DateSubmitted);
                    }
                    else{


                        $RECEIVED = Carbon::now();
                    }
                  
                    $BrandName = $rec->BrandName;
                    $CommonName = $rec->CommonName;
                    $ClassificationName = $rec->ClassificationName;
                    $ProductOrigin = $rec->ProductOrigin;

                    $PhysicalDescription = $rec->PhysicalDescription;
                  
                    $ProductStrength = $rec->ProductStrength;
                    $ProductForm = $rec->ProductForm;
                    $DosageForm = $rec->DosageForm;
                    $StorageConditions = $rec->StorageConditions;
                    $ProductCategory = $rec->ProductCategory;
                    $ProductSubCategory = $rec->ProductSubCategory;
                    $DistributionCategory = $rec->DistributionCategory;
                    $IntendedEndUser = $rec->IntendedEndUser;
                    $IntendedUse = $rec->IntendedUse;
                    $RouteOfAdministration = $rec->RouteOfAdministration;
                    $MethodOfUse = $rec->MethodOfUse;
                    $Contraindication = $rec->Contraindication;
                    $Indication = $rec->Indication;
                    $GmdnCategory = $rec->GmdnCategory;
                    $GmdnTerm = $rec->GmdnTerm;
                    $Warnings = $rec->Warnings;
                    $InstructionsOfUse = $rec->InstructionsOfUse;
                    $ShelfLife = $rec->ShelfLife;
                    $ShelfLifeafterOpening = $rec->ShelfLifeafterOpening;

                    $Ingredient = $rec->Ingredient;
                    $SpecificationType = $rec->SpecificationType;
                    $Strength = $rec->Strength;
                    $Proportionunit = $rec->Proportion; 
                    $IngredientsSiUnit = $rec->IngredientsSiUnit;
                    $InclusionReason = $rec->InclusionReason;

                    $Container = $rec->Container;
                    $ContainerMaterial = $rec->ContainerMaterial;
                    $RetailPackagingSize = $rec->RetailPackagingSize;
                    $PackagingUnits = $rec->PackagingUnits;


                    $ApplicantName = $rec->ApplicantName;
                    $ApplicantCountry = $rec->ApplicantCountry;
                    $ApplicantPhysicalAddress = $rec->ApplicantPhysicalAddress;
                    $ApplicantRegion = $rec->ApplicantRegion;
                    $ApplicantEmail = $rec->ApplicantEmail;
                    
                    
                    $ItrName = $rec->ItrName;
                    $ltrPhysicalAddress = $rec->ltrPhysicalAddress;
                    $ltrEmail = $rec->ltrEmail;
                    $ltrRegion = $rec->ltrRegion;
                    $ltrCountry = $rec->ltrCountry;
                   
                    $ManufacturingSiteName = $rec->ManufacturingSiteName;
                    $ManCountry = $rec->ManCountry;
                    $ManPhysicalAddress = $rec->ManPhysicalAddress;
                    $ManRegion = $rec->ManRegion;
                    $ManEmail = $rec->ManEmail;
                    
                    $ApprovalDecision = $rec->ApprovalDecision;
                    $RegistrationStatus = $rec->RegistrationStatus;
                    $Approvaldate = $rec->Approvaldate;
                    $ExpiryDate = $rec->ExpiryDate;
                    $CerificateNo = $rec->CerificateNo;
                    

                    $product_origin_id = 1;
                    if($ProductOrigin == 'Imported'){
                        $product_origin_id = 2;

                    }
                  
                    $classification_id = 2;
                    
                $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
                $common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$CommonName, 'description'=>$CommonName),array('name'=>$CommonName),'Common Names');
                   
                $classification_id = $this->saveProductDataEntry('par_classifications',array('name'=>$ClassificationName,'section_id'=>$section_id, 'description'=>$ClassificationName),array('name'=>$ClassificationName),'Classification');
               
                if($ReferenceNo == 0 || $ReferenceNo == 'N/A (Applied by email)'  || $ReferenceNo == 'Not specified'){

                    $app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
                    $ReferenceNo = 'MGR-'.$tracking_details['tracking_no'];
                   
                }
               
                $dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('name'=>$DosageForm,'section_id'=>$section_id, 'description'=>$DosageForm),array('name'=>$DosageForm),'DoSage form');
                $product_form_id = $this->saveProductDataEntry('par_product_forms',array('name'=>$ProductForm, 'section_id'=>$section_id,'description'=>$ProductForm),array('name'=>$ProductForm),'Product form');
                $storage_condition_id = $this->saveProductDataEntry('par_storage_conditions',array('name'=>$StorageConditions, 'section_id'=>$section_id,'description'=>$StorageConditions),array('name'=>$StorageConditions),'Product form');
                $product_category_id = $this->saveProductDataEntry('par_product_categories',array('name'=>$ProductCategory, 'section_id'=>$section_id,'description'=>$ProductCategory),array('name'=>$ProductCategory),'Product form');
                $product_subcategory_id = $this->saveProductDataEntry('par_subproduct_categories',array('name'=>$ProductSubCategory, 'product_category_id'=>$product_category_id, 'section_id'=>$section_id,'description'=>$ProductSubCategory),array('name'=>$ProductSubCategory),'Product form');

                $distribution_category_id = $this->saveProductDataEntry('par_distribution_categories',array('name'=>$DistributionCategory, 'section_id'=>$section_id,'description'=>$DistributionCategory),array('name'=>$DistributionCategory),'Product form');

                $intended_enduser_id = $this->saveProductDataEntry('par_intended_enduser',array('name'=>$IntendedEndUser, 'section_id'=>$section_id,'description'=>$IntendedEndUser),array('name'=>$IntendedEndUser),'Product form');
                $route_of_administration_id = $this->saveProductDataEntry('par_route_of_administration',array('name'=>$RouteOfAdministration, 'section_id'=>$section_id,'description'=>$RouteOfAdministration),array('name'=>$RouteOfAdministration),'Product form');

                $method_ofuse_id = $this->saveProductDataEntry('par_methodof_use',array('name'=>$MethodOfUse, 'section_id'=>$section_id,'description'=>$MethodOfUse),array('name'=>$MethodOfUse),'Product form');
                
                
                $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             'product_form_id'=>$product_form_id,
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$BrandName,
                                             'physical_description'=>$PhysicalDescription,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_strength'=>$ProductStrength,
                                             'storage_condition_id'=>$storage_condition_id,
                                             'product_category_id'=>$product_category_id,
                                             'product_subcategory_id'=>$product_subcategory_id,
                                             'distribution_category_id'=>$distribution_category_id,  'intended_enduser_id'=>$intended_enduser_id,
                                             'intended_use'=>$IntendedUse,
                                             'route_of_administration_id'=>$route_of_administration_id,
                                             'method_ofuse_id'=>$method_ofuse_id,
                                             'contraindication'=>$Contraindication,
                                             'indication'=>$Indication,
                                             'gmdn_category'=>$GmdnCategory,
                                             'gmdn_term'=>$GmdnTerm,
                                             'warnings'=>$Warnings,
                                             'shelf_lifeafter_opening'=>$ShelfLifeafterOpening,
                                             'shelf_life'=>$ShelfLife,
                                             'instructions_of_use'=>$InstructionsOfUse,

                                             
                                             'section_id'=>$section_id,
                                          
                                         );
                                         $ingredient_id = $this->saveProductDataEntry('par_ingredients_details',array('name'=>$Ingredient, 'section_id'=>$section_id,'description'=>$Ingredient),array('name'=>$Ingredient),'Product form');

                                         $inclusion_reason_id = $this->saveProductDataEntry('par_inclusions_reasons',array('name'=>$InclusionReason, 'section_id'=>$section_id,'description'=>$InclusionReason),array('name'=>$InclusionReason),'Product form');

                                         $specification_type_id = $this->saveProductDataEntry('par_specification_types',array('name'=>$SpecificationType,'section_id'=>$section_id,  'description'=>$Ingredient),array('name'=>$SpecificationType),'Ingredietns details');

                                         $ingredientssi_unit_id = $this->saveProductDataEntry('par_si_units',array('name'=>$IngredientsSiUnit, 'section_id'=>$section_id,'description'=>$IngredientsSiUnit),array('name'=>$IngredientsSiUnit),'Product form');

                                        
                                        
                                                     //packagind details 
                                        $container_id = $this->saveProductDataEntry('par_containers',array('name'=>$Container, 'section_id'=>$section_id,'description'=>$Container),array('name'=>$Container),'Product form');
                                        $container_material_id = $this->saveProductDataEntry('par_containers',array('name'=>$ContainerMaterial, 'section_id'=>$section_id,'description'=>$ContainerMaterial),array('name'=>$ContainerMaterial),'Product form');

                                        $packaging_units_id = $this->saveProductDataEntry('par_packaging_units',array('name'=>$PackagingUnits, 'section_id'=>$section_id,'description'=>$PackagingUnits),array('name'=>$PackagingUnits),'Product form');
                                        
                        if(!$app_record){
                   
                
                                             $product_information['created_by'] = '0';
                                             $product_information['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                                             if($resp['success']){
                                                 $product_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
                    //product_ingreidents
                   
                    $ingredients_data = array('product_id'=>$product_id, 
                                        'ingredient_id'=>$ingredient_id,
                                        'specification_type_id'=>$specification_type_id,
                                        'strength'=>$Strength,
                                        'proportion'=>$Proportionunit,
                                        'ingredientssi_unit_id'=>$ingredientssi_unit_id,
                                         'inclusion_reason_id'=>$inclusion_reason_id);

                    $packaging_data = array('container_id'=>$container_id, 
                                                'container_material_id'=>$container_material_id,
                                                'retail_packaging_size'=>$RetailPackagingSize,
                                                'packaging_units_id'=>$packaging_units_id,
                                                'product_id'=>$product_id
                                             );  
                    DB::table('tra_product_ingredients')->insert($ingredients_data);
                    DB::table('tra_product_packaging')->insert($packaging_data);
                    //save product manufacturing site details 
                  
                   $mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManCountry, 'description'=>$ManCountry),array('name'=>$ManCountry),'Country');
    
                   $manregion_id = $this->saveProductDataEntry('par_regions',array('name'=>$ManRegion, 'description'=>$ManRegion, 'country_id'=>$mancountry_id),array('name'=>$ManRegion),'Region ');
$mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManCountry, 'description'=>$ManCountry),array('name'=>$ManCountry),'Country');
                    $manufacturer_data = array('name'=>$ManufacturingSiteName, 
                                               // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManufacturingSiteName, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$ManufacturingSiteName, 
                                                // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$ManufacturingSiteName, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);
                    //save product applicant_details 
                   
                   $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
    
                   
                   $data = (object)array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     //save product local representative 
                     $local_agent_id = 0;
                     if($ItrName != ''){
                        $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ltrCountry, 'description'=>$ltrCountry),array('name'=>$ltrCountry),'Country ');
    
                        $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ltrRegion, 'country_id'=>$country_id),array('name'=>$ltrRegion),'Country ');

                        $data = (object)array('name'=>$ItrName, 
                                     'tpin_no'=>0, 
                                     'contact_person'=>$ItrName,
                                     'country_id'=>$country_id, 
                                     'region_id'=>$region_id, 
                                     'physical_address'=> $ltrPhysicalAddress,
                                     'postal_address'=>$ltrPhysicalAddress, 
                                    // 'telephone_no'=>$REPRESENTATIVE_PHONE, 
                                     'email'=>$ltrEmail, 
                                     'created_by'=>'Migration',
                                     'created_on'=>Carbon::now(),
                                     'status_id'=>1
                                 );
                         $local_agent_id = $this->saveTraderInformationDetails($data);
    
                     }
                    
                     $application_status_id = 5;
                     if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $application_status_id = 6;
                     }
                     
                    //save product application details  RefNumber
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
                     'application_code'=>$application_code, 
                     'reference_no'=>$ReferenceNo, 
                     'view_id'=>$view_id, 
                     'applicant_id'=>$applicant_id, 
                     'local_agent_id'=>$local_agent_id, 
                     'sub_module_id'=>$sub_module_id, 
                     'assessmentprocedure_type_id'=>1, 
                     'section_id'=>$section_id, 
                     'product_id'=>$product_id, 
                     'fasttrack_option_id'=>2, 
                     'process_id'=>$process_id, 
                     'module_id'=>$module_id, 
                     'prodclass_category_id'=>$prodclass_category_id, 
                     'date_added'=>formatDate($RECEIVED), 
                     'submission_date'=>formatDate($RECEIVED), 
                     'application_status_id'=>$application_status_id, 
                     'refno_generated'=>1, 
                     'created_on'=>Carbon::now(), 
                     'created_by'=>'Migration', 
                     
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$CerificateNo,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Approvaldate),
                            'certificate_issue_date'=>formatDate($Approvaldate),
                            'expiry_date'=>formatDate($ExpiryDate),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Approvaldate),
                                    'registration_no'=>$CerificateNo,
                                    'reg_applicant_id'=>$applicant_id,
                                    'reg_local_agent_id'=>$local_agent_id,
                                    'active_app_referenceno'=>$ReferenceNo,
                                    'active_application_code'=>$application_code,
                                    'expiry_date'=>formatDate($ExpiryDate),
                                    'approval_date'=>formatDate($Approvaldate),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
                    }
                    else{
                        $regdata = array('tra_product_id'=>$product_id,
                                'validity_status_id'=>1,
                                'registration_status_id'=>1,
                                
                                'reg_applicant_id'=>$applicant_id,
                                'reg_local_agent_id'=>$local_agent_id,
                                'active_app_referenceno'=>$ReferenceNo,
                                'active_application_code'=>$application_code,
                                'created_on'=>Carbon::now()
                        );
                        $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                        if($resp['success']){
                            $reg_id = $resp['record_id'];
                        }
                        else{
                                print_r($resp);
                                exit();
                        }
                        DB::table('tra_product_applications')
                        ->where(array('application_code'=>$application_code))
                        ->update(array('reg_product_id'=>$reg_id));
    
                    }
                    //save product register
                    $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
                   
                }
                else{
					
                    //update the records 
                    $product_id = $app_record->product_id;
                    $applicant_id = $app_record->applicant_id;
                    $application_code = $app_record->application_code;
                   
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    
                    $classification_id = 2;
                    
                   
                    DB::table('tra_product_information')->where(array('id'=>$product_id))->update($product_information);

                  
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
                                             
                   $trader_data = array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    
                             DB::table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
                             DB::connection('portal_db')->table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
  
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$ReferenceNo.'</br>';
                 
                }
                print_r($res);

                $records_migrated ++;
                
            }  
		
       //     $this->saveMigrationLogsData('initiatemigrateRenewalProductsDetails',$records_migrated=0);
    
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
public function initiatTobaccoProductsemigrateDetails(Request $req){
    try{
        $res = 'Error occurred';
        $records_migrated =0;
        
        $table_name = $req->table_name;
        $record_id = $req->record_id;
		
        $records = DB::table($table_name)->whereRaw("BrandName is not null and BrandName != ''")->get();
		//$records = DB::
		
				foreach($records as $rec){
                                         $ReferenceNo = trim($rec->ReferenceNo);
                                        
                                       //  $section_id = $rec->section_id;
                                       //  $prodclass_category_id = $rec->prodclass_category_id;
                                         
                    $sub_module_id = 7;
                    $module_id = 1;
                    $section_id = 8;
                    $prodclass_category_id = 14;
                    $process_id = 0;
                    $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                    if($process_data){
                        
                            $process_id = $process_data->id;
                      
                    }
                    
                    if($rec->DateSubmitted != '' && $rec->DateSubmitted != null && $rec->DateSubmitted != ' '){
                     
                        $RECEIVED = formatDate($rec->DateSubmitted);
                    }
                    else{


                        $RECEIVED = Carbon::now();
                    }
                  
                    $BrandName = $rec->BrandName;
                    $CommonName = $rec->CommonName;
                    $ClassificationName = $rec->ClassificationName;
                    $ProductOrigin = $rec->ProductOrigin;

                    $PhysicalDescription = $rec->PhysicalDescription;
                  
                    $ProductStrength = $rec->ProductStrength;
                    $ProductForm = $rec->ProductForm;
                    $DosageForm = $rec->DosageForm;
                    $StorageConditions = $rec->StorageConditions;
                    $ProductCategory = $rec->ProductCategory;
                    $ProductSubCategory = $rec->ProductSubCategory;
                    $DistributionCategory = $rec->DistributionCategory;
                    $IntendedEndUser = $rec->IntendedEndUser;
                    $IntendedUse = $rec->IntendedUse;
                    $RouteOfAdministration = $rec->RouteOfAdministration;
                    $MethodOfUse = $rec->MethodOfUse;
                    $Contraindication = $rec->Contraindication;
                    $Indication = $rec->Indication;
                    $GmdnCategory = $rec->GmdnCategory;
                    $GmdnTerm = $rec->GmdnTerm;
                    $Warnings = $rec->Warnings;
                    $InstructionsOfUse = $rec->InstructionsOfUse;
                    $ShelfLife = $rec->ShelfLife;
                    $ShelfLifeafterOpening = $rec->ShelfLifeafterOpening;

                    $Ingredient = $rec->Ingredient;
                    $SpecificationType = $rec->SpecificationType;
                    $Strength = $rec->Strength;
                    $Proportionunit = $rec->Proportion; 
                    $IngredientsSiUnit = $rec->IngredientsSiUnit;
                    $InclusionReason = $rec->InclusionReason;

                    $Container = $rec->Container;
                    $ContainerMaterial = $rec->ContainerMaterial;
                    $RetailPackagingSize = $rec->RetailPackagingSize;
                    $PackagingUnits = $rec->PackagingUnits;


                    $ApplicantName = $rec->ApplicantName;
                    $ApplicantCountry = $rec->ApplicantCountry;
                    $ApplicantPhysicalAddress = $rec->ApplicantPhysicalAddress;
                    $ApplicantRegion = $rec->ApplicantRegion;
                    $ApplicantEmail = $rec->ApplicantEmail;
                    
                    
                    $ItrName = $rec->ItrName;
                    $ltrPhysicalAddress = $rec->ltrPhysicalAddress;
                    $ltrEmail = $rec->ltrEmail;
                    $ltrRegion = $rec->ltrRegion;
                    $ltrCountry = $rec->ltrCountry;
                   
                    $ManufacturingSiteName = $rec->ManufacturingSiteName;
                    $ManCountry = $rec->ManCountry;
                    $ManPhysicalAddress = $rec->ManPhysicalAddress;
                    $ManRegion = $rec->ManRegion;
                    $ManEmail = $rec->ManEmail;
                    
                    $ApprovalDecision = $rec->ApprovalDecision;
                    $RegistrationStatus = $rec->RegistrationStatus;
                    $Approvaldate = $rec->Approvaldate;
                    $ExpiryDate = $rec->ExpiryDate;
                    $CerificateNo = $rec->CerificateNo;
                    

                    $product_origin_id = 1;
                    if($ProductOrigin == 'Imported'){
                        $product_origin_id = 2;

                    }
                  
                    $classification_id = 40;
                    
                $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
                $common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$CommonName, 'description'=>$CommonName),array('name'=>$CommonName),'Common Names');
                   
                $classification_id = $this->saveProductDataEntry('par_classifications',array('name'=>$ClassificationName,'section_id'=>$section_id, 'description'=>$ClassificationName),array('name'=>$ClassificationName),'Classification');
               
                if($ReferenceNo == 0 || $ReferenceNo == 'N/A (Applied by email)'  || $ReferenceNo == 'Not specified'){

                    $app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
                    $ReferenceNo = 'MGR-'.$tracking_details['tracking_no'];
                   
                }
               
                $dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('name'=>$DosageForm,'section_id'=>$section_id, 'description'=>$DosageForm),array('name'=>$DosageForm),'DoSage form');
                $product_form_id = $this->saveProductDataEntry('par_product_forms',array('name'=>$ProductForm, 'section_id'=>$section_id,'description'=>$ProductForm),array('name'=>$ProductForm),'Product form');
                $storage_condition_id = $this->saveProductDataEntry('par_storage_conditions',array('name'=>$StorageConditions, 'section_id'=>$section_id,'description'=>$StorageConditions),array('name'=>$StorageConditions),'Product form');
                $product_category_id = $this->saveProductDataEntry('par_product_categories',array('name'=>$ProductCategory, 'section_id'=>$section_id,'description'=>$ProductCategory),array('name'=>$ProductCategory),'Product form');
                $product_subcategory_id = $this->saveProductDataEntry('par_subproduct_categories',array('name'=>$ProductSubCategory, 'product_category_id'=>$product_category_id, 'section_id'=>$section_id,'description'=>$ProductSubCategory),array('name'=>$ProductSubCategory),'Product form');

                $distribution_category_id = $this->saveProductDataEntry('par_distribution_categories',array('name'=>$DistributionCategory, 'section_id'=>$section_id,'description'=>$DistributionCategory),array('name'=>$DistributionCategory),'Product form');

                $intended_enduser_id = $this->saveProductDataEntry('par_intended_enduser',array('name'=>$IntendedEndUser, 'section_id'=>$section_id,'description'=>$IntendedEndUser),array('name'=>$IntendedEndUser),'Product form');
                $route_of_administration_id = $this->saveProductDataEntry('par_route_of_administration',array('name'=>$RouteOfAdministration, 'section_id'=>$section_id,'description'=>$RouteOfAdministration),array('name'=>$RouteOfAdministration),'Product form');

                $method_ofuse_id = $this->saveProductDataEntry('par_methodof_use',array('name'=>$MethodOfUse, 'section_id'=>$section_id,'description'=>$MethodOfUse),array('name'=>$MethodOfUse),'Product form');
                
                
                $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             'product_form_id'=>$product_form_id,
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$BrandName,
                                             'physical_description'=>$PhysicalDescription,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_strength'=>$ProductStrength,
                                             'storage_condition_id'=>$storage_condition_id,
                                             'product_category_id'=>$product_category_id,
                                             'product_subcategory_id'=>$product_subcategory_id,
                                             'distribution_category_id'=>$distribution_category_id,  'intended_enduser_id'=>$intended_enduser_id,
                                             'intended_use'=>$IntendedUse,
                                             'route_of_administration_id'=>$route_of_administration_id,
                                             'method_ofuse_id'=>$method_ofuse_id,
                                             'contraindication'=>$Contraindication,
                                             'indication'=>$Indication,
                                             'gmdn_category'=>$GmdnCategory,
                                             'gmdn_term'=>$GmdnTerm,
                                             'warnings'=>$Warnings,
                                             'shelf_lifeafter_opening'=>$ShelfLifeafterOpening,
                                             'shelf_life'=>$ShelfLife,
                                             'instructions_of_use'=>$InstructionsOfUse,

                                             
                                             'section_id'=>$section_id,
                                          
                                         );
                                         $ingredient_id = $this->saveProductDataEntry('par_ingredients_details',array('name'=>$Ingredient, 'section_id'=>$section_id,'description'=>$Ingredient),array('name'=>$Ingredient),'Product form');

                                         $inclusion_reason_id = $this->saveProductDataEntry('par_inclusions_reasons',array('name'=>$InclusionReason, 'section_id'=>$section_id,'description'=>$InclusionReason),array('name'=>$InclusionReason),'Product form');

                                         $specification_type_id = $this->saveProductDataEntry('par_specification_types',array('name'=>$SpecificationType,'section_id'=>$section_id,  'description'=>$Ingredient),array('name'=>$SpecificationType),'Ingredietns details');

                                         $ingredientssi_unit_id = $this->saveProductDataEntry('par_si_units',array('name'=>$IngredientsSiUnit, 'section_id'=>$section_id,'description'=>$IngredientsSiUnit),array('name'=>$IngredientsSiUnit),'Product form');

                                        
                                        
                                                     //packagind details 
                                        $container_id = $this->saveProductDataEntry('par_containers',array('name'=>$Container, 'section_id'=>$section_id,'description'=>$Container),array('name'=>$Container),'Product form');
                                        $container_material_id = $this->saveProductDataEntry('par_containers',array('name'=>$ContainerMaterial, 'section_id'=>$section_id,'description'=>$ContainerMaterial),array('name'=>$ContainerMaterial),'Product form');

                                        $packaging_units_id = $this->saveProductDataEntry('par_packaging_units',array('name'=>$PackagingUnits, 'section_id'=>$section_id,'description'=>$PackagingUnits),array('name'=>$PackagingUnits),'Product form');
                                        
                        if(!$app_record){
                   
                
                                             $product_information['created_by'] = '0';
                                             $product_information['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                                             if($resp['success']){
                                                 $product_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
                    //product_ingreidents
                   
                    $ingredients_data = array('product_id'=>$product_id, 
                                        'ingredient_id'=>$ingredient_id,
                                        'specification_type_id'=>$specification_type_id,
                                        'strength'=>$Strength,
                                        'proportion'=>$Proportionunit,
                                        'ingredientssi_unit_id'=>$ingredientssi_unit_id,
                                         'inclusion_reason_id'=>$inclusion_reason_id);

                    $packaging_data = array('container_id'=>$container_id, 
                                                'container_material_id'=>$container_material_id,
                                                'retail_packaging_size'=>$RetailPackagingSize,
                                                'packaging_units_id'=>$packaging_units_id,
                                                'product_id'=>$product_id
                                             );  
                    DB::table('tra_product_ingredients')->insert($ingredients_data);
                    DB::table('tra_product_packaging')->insert($packaging_data);
                    //save product manufacturing site details 
                  
                   $mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManCountry, 'description'=>$ManCountry),array('name'=>$ManCountry),'Country');
    
                   $manregion_id = $this->saveProductDataEntry('par_regions',array('name'=>$ManRegion, 'description'=>$ManRegion, 'country_id'=>$mancountry_id),array('name'=>$ManRegion),'Region ');

                    $manufacturer_data = array('name'=>$ManufacturingSiteName, 
                                               // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManufacturingSiteName, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$ManufacturingSiteName, 
                                                // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$ManufacturingSiteName, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);
                    //save product applicant_details 
                   
                   $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
    
                   
                   $data = (object)array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     //save product local representative 
                     $local_agent_id = 0;
                     if($ItrName != ''){
                        $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ltrCountry, 'description'=>$ltrCountry),array('name'=>$ltrCountry),'Country ');
    
                        $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ltrRegion, 'country_id'=>$country_id),array('name'=>$ltrRegion),'Country ');

                        $data = (object)array('name'=>$ItrName, 
                                     'tpin_no'=>0, 
                                     'contact_person'=>$ItrName,
                                     'country_id'=>$country_id, 
                                     'region_id'=>$region_id, 
                                     'physical_address'=> $ltrPhysicalAddress,
                                     'postal_address'=>$ltrPhysicalAddress, 
                                    // 'telephone_no'=>$REPRESENTATIVE_PHONE, 
                                     'email'=>$ltrEmail, 
                                     'created_by'=>'Migration',
                                     'created_on'=>Carbon::now(),
                                     'status_id'=>1
                                 );
                         $local_agent_id = $this->saveTraderInformationDetails($data);
    
                     }
                    
                     $application_status_id = 5;
                     if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $application_status_id = 6;
                     }
                     
                    //save product application details  RefNumber
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
                     'application_code'=>$application_code, 
                     'reference_no'=>$ReferenceNo, 
                     'view_id'=>$view_id, 
                     'applicant_id'=>$applicant_id, 
                     'local_agent_id'=>$local_agent_id, 
                     'sub_module_id'=>$sub_module_id, 
                     'assessmentprocedure_type_id'=>1, 
                     'section_id'=>$section_id, 
                     'product_id'=>$product_id, 
                     'fasttrack_option_id'=>2, 
                     'process_id'=>$process_id, 
                     'module_id'=>$module_id, 
                     'prodclass_category_id'=>$prodclass_category_id, 
                     'date_added'=>formatDate($RECEIVED), 
                     'submission_date'=>formatDate($RECEIVED), 
                     'application_status_id'=>$application_status_id, 
                     'refno_generated'=>1, 
                     'created_on'=>Carbon::now(), 
                     'created_by'=>'Migration', 
                     
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$CerificateNo,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Approvaldate),
                            'certificate_issue_date'=>formatDate($Approvaldate),
                            'expiry_date'=>formatDate($ExpiryDate),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Approvaldate),
                                    'registration_no'=>$CerificateNo,
                                    'reg_applicant_id'=>$applicant_id,
                                    'reg_local_agent_id'=>$local_agent_id,
                                    'active_app_referenceno'=>$ReferenceNo,
                                    'active_application_code'=>$application_code,
                                    'expiry_date'=>formatDate($ExpiryDate),
                                    'approval_date'=>formatDate($Approvaldate),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
                    }
                    else{
                        $regdata = array('tra_product_id'=>$product_id,
                                'validity_status_id'=>1,
                                'registration_status_id'=>1,
                                
                                'reg_applicant_id'=>$applicant_id,
                                'reg_local_agent_id'=>$local_agent_id,
                                'active_app_referenceno'=>$ReferenceNo,
                                'active_application_code'=>$application_code,
                                'created_on'=>Carbon::now()
                        );
                        $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                        if($resp['success']){
                            $reg_id = $resp['record_id'];
                        }
                        else{
                                print_r($resp);
                                exit();
                        }
                        DB::table('tra_product_applications')
                        ->where(array('application_code'=>$application_code))
                        ->update(array('reg_product_id'=>$reg_id));
    
                    }
                    //save product register
                    $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
                   
                }
                else{
					
                    //update the records 
                    $product_id = $app_record->product_id;
                    $applicant_id = $app_record->applicant_id;
                    $application_code = $app_record->application_code;
                   
                    $classification_id = 40;
                    
                   
                    DB::table('tra_product_information')->where(array('id'=>$product_id))->update($product_information);

                  
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
                                             
                   $trader_data = array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    
                             DB::table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
                             DB::connection('portal_db')->table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
  
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$ReferenceNo.'</br>';
                 
                }
                print_r($res);

                $records_migrated ++;
                
            }  
		
       //     $this->saveMigrationLogsData('initiatemigrateRenewalProductsDetails',$records_migrated=0);
    
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
public function initiatNewMedicaldevicesProductsemigrateDetails(Request $req){
    try{
        $res = 'Error occurred';
        $records_migrated =0;
        
        $table_name = $req->table_name;
        $record_id = $req->record_id;$section_id =4;
		
        $records = DB::table($table_name)->whereRaw("BrandName is not null and BrandName != ''")->get();
		//$records = DB::
		
				foreach($records as $rec){
                                         $ReferenceNo = $rec->ReferenceNo;
                    $sub_module_id = 7;
                    $module_id = 1;
					$section_id =4;
                    $RECEIVED = formatDate($rec->DateSubmitted);
                    $BrandName = $rec->BrandName;
                    $CommonName = $rec->CommonName;
                    $ClassificationName = $rec->ClassificationName;
                    $ProductOrigin = $rec->ProductOrigin;

                    $PhysicalDescription = $rec->PhysicalDescription;
                  
                    $ProductStrength = $rec->ProductStrength;
                    $ProductForm = $rec->ProductForm;
                    $DosageForm = $rec->DosageForm;
                    $StorageConditions = $rec->StorageConditions;
                    $ProductCategory = $rec->ProductCategory;
                    $ProductSubCategory = $rec->ProductSubCategory;
                    $DistributionCategory = $rec->DistributionCategory;
                    $IntendedEndUser = $rec->IntendedEndUser;
                    $IntendedUse = $rec->IntendedUse;
                    $RouteOfAdministration = $rec->RouteOfAdministration;
                    $MethodOfUse = $rec->MethodOfUse;
                    $Contraindication = $rec->Contraindication;
                    $Indication = $rec->Indication;
                    $GmdnCategory = $rec->GmdnCategory;
                    $GmdnTerm = $rec->GmdnTerm;
                    $Warnings = $rec->Warnings;
                    $InstructionsOfUse = $rec->InstructionsOfUse;
                    $ShelfLife = $rec->ShelfLife;
                    $ShelfLifeafterOpening = $rec->ShelfLifeafterOpening;

                    $Ingredient = $rec->Ingredient;
                    $SpecificationType = $rec->SpecificationType;
                    $Strength = $rec->Strength;
                    $Proportionunit = $rec->Proportion; 
                    $IngredientsSiUnit = $rec->IngredientsSiUnit;
                    $InclusionReason = $rec->Inclusion_Reason;

                    $Container = $rec->Container;
                    $ContainerMaterial = $rec->ContainerMaterial;
                    $RetailPackagingSize = $rec->RetailPackagingSize;
                    $PackagingUnits = $rec->PackagingUnits;


                    $ApplicantName = $rec->ApplicantName;
                    $ApplicantCountry = $rec->ApplicantCountry;
                    $ApplicantPhysicalAddress = $rec->ApplicantPhysicalAddress;
                    $ApplicantRegion = $rec->ApplicantRegion;
                    $ApplicantEmail = $rec->ApplicantEmail;
                    
                    
                    $ItrName = $rec->ItrName;
                    $ltrPhysicalAddress = $rec->ltrPhysicalAddress;
                    $ltrEmail = $rec->ltrEmail;
                    $ltrRegion = $rec->ltrRegion;
                    $ltrCountry = $rec->ltrCountry;
                   
                    $ManufacturingSiteName = $rec->ManufacturingSiteName;
                    $ManCountry = $rec->ManCountry;
                    $ManPhysicalAddress = $rec->ManPhysicalAddress;
                    $ManRegion = $rec->ManRegion;
                    $ManEmail = $rec->ManEmail;
                    
                    $ApprovalDecision = $rec->ApprovalDecision;
                    $RegistrationStatus = $rec->RegistrationStatus;
                    $Approvaldate = $rec->Approvaldate;
                    $ExpiryDate = $rec->ExpiryDate;
                    $CerificateNo = $rec->Cerificate_No;
                    $DeviceType = $rec->DeviceType;
            
                    $product_origin_id = 1;
                    if($ProductOrigin == 'Imported'){
                        $product_origin_id = 2;

                    }
                    $prodclass_category_id = 4;
                    
                    $classification_id = 2;
                    
                $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
                $common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$CommonName, 'description'=>$CommonName),array('name'=>$CommonName),'Common Names');
                   
				  $device_type_id = $this->saveProductDataEntry('par_device_types',array('section_id'=>$section_id, 'name'=>$DeviceType, 'description'=>$DeviceType),array('name'=>$DeviceType),'Device Type');
                   
                $dosage_form_id = 0;//$this->saveProductDataEntry('par_dosage_forms',array('name'=>$DosageForm,'section_id'=>$section_id, 'description'=>$DosageForm),array('name'=>$DosageForm),'DoSage form');
                $product_form_id =0;// $this->saveProductDataEntry('par_product_forms',array('name'=>$ProductForm, 'section_id'=>$section_id,'description'=>$ProductForm),array('name'=>$ProductForm),'Product form');
                $storage_condition_id = $this->saveProductDataEntry('par_storage_conditions',array('name'=>$StorageConditions, 'section_id'=>$section_id,'description'=>$StorageConditions),array('name'=>$StorageConditions),'Product form');
                $product_category_id = $this->saveProductDataEntry('par_product_categories',array('name'=>$ProductCategory, 'section_id'=>$section_id,'description'=>$ProductCategory),array('name'=>$ProductCategory),'Product form');
                $product_subcategory_id = 0;//$this->saveProductDataEntry('par_subproduct_categories',array('name'=>$ProductSubCategory, 'product_category_id'=>$product_category_id, 'section_id'=>$section_id,'description'=>$ProductSubCategory),array('name'=>$ProductSubCategory),'Product form');

                $distribution_category_id = 0;//$this->saveProductDataEntry('par_distribution_categories',array('name'=>$DistributionCategory, 'section_id'=>$section_id,'description'=>$DistributionCategory),array('name'=>$DistributionCategory),'Product form');

                $intended_enduser_id = $this->saveProductDataEntry('par_intended_enduser',array('name'=>$IntendedEndUser, 'section_id'=>$section_id,'description'=>$IntendedEndUser),array('name'=>$IntendedEndUser),'Product form');
                $route_of_administration_id = 0;//$this->saveProductDataEntry('par_route_of_administration',array('name'=>$RouteOfAdministration, 'section_id'=>$section_id,'description'=>$RouteOfAdministration),array('name'=>$RouteOfAdministration),'Product form');

                $method_ofuse_id = $this->saveProductDataEntry('par_methodof_use',array('name'=>$MethodOfUse, 'section_id'=>$section_id,'description'=>$MethodOfUse),array('name'=>$MethodOfUse),'Product form');
                
                $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             'product_form_id'=>$product_form_id,
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$BrandName,
                                             'physical_description'=>$PhysicalDescription,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_strength'=>$ProductStrength,
                                             'storage_condition_id'=>$storage_condition_id,
                                             'product_category_id'=>$product_category_id,
                                             'product_subcategory_id'=>$product_subcategory_id,
                                             'distribution_category_id'=>$distribution_category_id,  'intended_enduser_id'=>$intended_enduser_id,
                                             'intended_use'=>$IntendedUse,
                                             'route_of_administration_id'=>$route_of_administration_id,
                                             'method_ofuse_id'=>$method_ofuse_id,
                                             'contraindication'=>$Contraindication,
                                             'indication'=>$Indication,
                                             'gmdn_category'=>$GmdnCategory,
                                             'gmdn_term'=>$GmdnTerm,
                                             'warnings'=>$Warnings,
                                             'shelf_lifeafter_opening'=>$ShelfLifeafterOpening,
                                             'shelf_life'=>$ShelfLife,
                                             'instructions_of_use'=>$InstructionsOfUse,

                                             
                                             'device_type_id'=>$device_type_id,
                                             'section_id'=>$section_id,
                                          
                                         );
										 $ingredient_id = 0;
										 if($Ingredient != 'None'){
											 $ingredient_id = $this->saveProductDataEntry('par_ingredients_details',array('name'=>$Ingredient, 'section_id'=>$section_id,'description'=>$Ingredient),array('name'=>$Ingredient),'Product form');
											 
											 $inclusion_reason_id = $this->saveProductDataEntry('par_inclusions_reasons',array('name'=>$InclusionReason, 'section_id'=>$section_id,'description'=>$InclusionReason),array('name'=>$InclusionReason),'Product form');
$specification_type_id = $this->saveProductDataEntry('par_specification_types',array('name'=>$SpecificationType,'section_id'=>$section_id,  'description'=>$Ingredient),array('name'=>$SpecificationType),'Ingredietns details');$ingredientssi_unit_id = $this->saveProductDataEntry('par_si_units',array('name'=>$IngredientsSiUnit, 'section_id'=>$section_id,'description'=>$IngredientsSiUnit),array('name'=>$IngredientsSiUnit),'Product form');
											 $ingredients_data = array('product_id'=>$product_id, 
                                        'ingredient_id'=>$ingredient_id,
                                        'specification_type_id'=>$specification_type_id,
                                        'strength'=>$Strength,
                                        'proportion'=>$Proportionunit,
                                        'ingredientssi_unit_id'=>$ingredientssi_unit_id,
                                         'inclusion_reason_id'=>$inclusion_reason_id);
													DB::table('tra_product_ingredients')->insert($ingredients_data);
										 }
                                         

                                         
                                         

                                         

                                        
                                        
                                                     //packagind details 
                                        $container_id = $this->saveProductDataEntry('par_containers',array('name'=>$Container, 'section_id'=>$section_id,'description'=>$Container),array('name'=>$Container),'Product form');
                                        $container_material_id = $this->saveProductDataEntry('par_containers',array('name'=>$ContainerMaterial, 'section_id'=>$section_id,'description'=>$ContainerMaterial),array('name'=>$ContainerMaterial),'Product form');

                                        $packaging_units_id = $this->saveProductDataEntry('par_packaging_units',array('name'=>$PackagingUnits, 'section_id'=>$section_id,'description'=>$PackagingUnits),array('name'=>$PackagingUnits),'Product form');
                                        
                        if(!$app_record){
                   
                
                                             $product_information['created_by'] = '0';
                                             $product_information['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                                             if($resp['success']){
                                                 $product_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
                    //product_ingreidents
                   
                    
                    $packaging_data = array('container_id'=>$container_id, 
                                                'container_material_id'=>$container_material_id,
                                                'retail_packaging_size'=>$RetailPackagingSize,
                                                'packaging_units_id'=>$packaging_units_id,
                                                'product_id'=>$product_id
                                             );  
                    
                    DB::table('tra_product_packaging')->insert($packaging_data);
                    //save product manufacturing site details 
                  
                   $mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManCountry, 'description'=>$ManCountry),array('name'=>$ManCountry),'Country');
    
                   $manregion_id = $this->saveProductDataEntry('par_regions',array('name'=>$ManRegion, 'description'=>$ManRegion, 'country_id'=>$mancountry_id),array('name'=>$ManRegion),'Region ');

                    $manufacturer_data = array('name'=>$ManufacturingSiteName, 
                                               // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManufacturingSiteName, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$ManufacturingSiteName, 
                                                // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$ManufacturingSiteName, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);
                    //save product applicant_details 
                   
                   $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
    
                   
                   $data = (object)array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     //save product local representative 
                     $local_agent_id = 0;
                     if($ItrName != ''){
                        $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ltrCountry, 'description'=>$ltrCountry),array('name'=>$ltrCountry),'Country ');
    
                        $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ltrRegion, 'country_id'=>$country_id),array('name'=>$ltrRegion),'Country ');

                        $data = (object)array('name'=>$ItrName, 
                                     'tpin_no'=>0, 
                                     'contact_person'=>$ItrName,
                                     'country_id'=>$country_id, 
                                     'region_id'=>$region_id, 
                                     'physical_address'=> $ltrPhysicalAddress,
                                     'postal_address'=>$ltrPhysicalAddress, 
                                    // 'telephone_no'=>$REPRESENTATIVE_PHONE, 
                                     'email'=>$ltrEmail, 
                                     'created_by'=>'Migration',
                                     'created_on'=>Carbon::now(),
                                     'status_id'=>1
                                 );
                         $local_agent_id = $this->saveTraderInformationDetails($data);
    
                     }
                    
                     $application_status_id = 5;
                     if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $application_status_id = 6;
                     }
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
                             $process_id = $process_data->id;
                       
                     }
                    //save product application details  RefNumber
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
                     'application_code'=>$application_code, 
                     'reference_no'=>$ReferenceNo, 
                     'view_id'=>$view_id, 
                     'applicant_id'=>$applicant_id, 
                     'local_agent_id'=>$local_agent_id, 
                     'sub_module_id'=>$sub_module_id, 
                     'assessmentprocedure_type_id'=>1, 
                     'section_id'=>$section_id, 
                     'product_id'=>$product_id, 
                     'fasttrack_option_id'=>2, 
                     'process_id'=>$process_id, 
                     'module_id'=>$module_id, 
                     'prodclass_category_id'=>$prodclass_category_id, 
                     'date_added'=>formatDate($RECEIVED), 
                     'submission_date'=>formatDate($RECEIVED), 
                     'application_status_id'=>$application_status_id, 
                     'refno_generated'=>1, 
                     'created_on'=>Carbon::now(), 
                     'created_by'=>'Migration', 
                     
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$CerificateNo,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Approvaldate),
                            'certificate_issue_date'=>formatDate($Approvaldate),
                            'expiry_date'=>formatDate($ExpiryDate),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Approvaldate),
                                    'registration_no'=>$CerificateNo,
                                    'reg_applicant_id'=>$applicant_id,
                                    'reg_local_agent_id'=>$local_agent_id,
                                    'active_app_referenceno'=>$ReferenceNo,
                                    'active_application_code'=>$application_code,
                                    'expiry_date'=>formatDate($ExpiryDate),
                                    'approval_date'=>formatDate($Approvaldate),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
                    }
                    else{
                        $regdata = array('tra_product_id'=>$product_id,
                                'validity_status_id'=>1,
                                'registration_status_id'=>1,
                                
                                'reg_applicant_id'=>$applicant_id,
                                'reg_local_agent_id'=>$local_agent_id,
                                'active_app_referenceno'=>$ReferenceNo,
                                'active_application_code'=>$application_code,
                                'created_on'=>Carbon::now()
                        );
                        $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                        if($resp['success']){
                            $reg_id = $resp['record_id'];
                        }
                        else{
                                print_r($resp);
                                exit();
                        }
                        DB::table('tra_product_applications')
                        ->where(array('application_code'=>$application_code))
                        ->update(array('reg_product_id'=>$reg_id));
    
                    }
                    //save product register
                    $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
                   
                }
                else{
					
                    //update the records 
                    $product_id = $app_record->product_id;
                    $applicant_id = $app_record->applicant_id;
                    $application_code = $app_record->application_code;
                   
                    $prodclass_category_id = 4;
                    
                    $classification_id = 2;
                    
                   
                    DB::table('tra_product_information')->where(array('id'=>$product_id))->update($product_information);

                  
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
                                             
                   $trader_data = array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    
                             DB::table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
                             DB::connection('portal_db')->table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
  
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$ReferenceNo.'</br>';
                 
                }
                print_r($res);

                $records_migrated ++;
                
            }  
		
       //     $this->saveMigrationLogsData('initiatemigrateRenewalProductsDetails',$records_migrated=0);
    
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
public function initiatNewMedicinesProductsemigrateDetails(Request $req){
    try{
        $res = 'Error occurred';
        $records_migrated =0;
        
        $table_name = $req->table_name;
        $record_id = $req->record_id;
		
        $records = DB::table($table_name)->whereRaw("BrandName is not null and BrandName != ''")->get();
		//$records = DB::
		
				foreach($records as $rec){
                                         $ReferenceNo = $rec->ReferenceNo;
                    $sub_module_id = 7;
                    $module_id = 1;

                    $RECEIVED = formatDate($rec->DateSubmitted);
                    $BrandName = $rec->BrandName;
                    $CommonName = $rec->CommonName;
                    $ClassificationName = $rec->ClassificationName;
                    $ProductOrigin = $rec->ProductOrigin;

                    $PhysicalDescription = $rec->PhysicalDescription;
                  
                    $ProductStrength = $rec->ProductStrength;
                    $ProductForm = $rec->ProductForm;
                    $DosageForm = $rec->DosageForm;
                    $StorageConditions = $rec->StorageConditions;
                    $ProductCategory = $rec->ProductCategory;
                    $ProductSubCategory = $rec->ProductSubCategory;
                    $DistributionCategory = $rec->DistributionCategory;
                    $IntendedEndUser = $rec->IntendedEndUser;
                    $IntendedUse = $rec->IntendedUse;
                    $RouteOfAdministration = $rec->RouteOfAdministration;
                    $MethodOfUse = $rec->MethodOfUse;
                    $Contraindication = $rec->Contraindication;
                    $Indication = $rec->Indication;
                    $GmdnCategory = $rec->GmdnCategory;
                    $GmdnTerm = $rec->GmdnTerm;
                    $Warnings = $rec->Warnings;
                    $InstructionsOfUse = $rec->InstructionsOfUse;
                    $ShelfLife = $rec->ShelfLife;
                    $ShelfLifeafterOpening = $rec->ShelfLifeafterOpening;

                    $Ingredient = $rec->Ingredient;
                    $SpecificationType = $rec->SpecificationType;
                    $Strength = $rec->Strength;
                    $Proportionunit = $rec->Proportionunit; 
                    $IngredientsSiUnit = $rec->IngredientsSiUnit;
                    $InclusionReason = $rec->InclusionReason;

                    $Container = $rec->Container;
                    $ContainerMaterial = $rec->ContainerMaterial;
                    $RetailPackagingSize = $rec->RetailPackagingSize;
                    $PackagingUnits = $rec->PackagingUnits;


                    $ApplicantName = $rec->ApplicantName;
                    $ApplicantCountry = $rec->ApplicantCountry;
                    $ApplicantPhysicalAddress = $rec->ApplicantPhysicalAddress;
                    $ApplicantRegion = $rec->ApplicantRegion;
                    $ApplicantEmail = $rec->ApplicantEmail;
                    
                    
                    $ItrName = $rec->ItrName;
                    $ltrPhysicalAddress = $rec->ltrPhysicalAddress;
                    $ltrEmail = $rec->ltrEmail;
                    $ltrRegion = $rec->ltrRegion;
                    $ltrCountry = $rec->ltrCountry;
                   
                    $ManufacturingSiteName = $rec->ManufacturingSiteName;
                    $ManCountry = $rec->ManCountry;
                    $ManPhysicalAddress = $rec->ManPhysicalAddress;
                    $ManRegion = $rec->ManRegion;
                    $ManEmail = $rec->ManEmail;
                    
                    $ApprovalDecision = $rec->ApprovalDecision;
                    $RegistrationStatus = $rec->RegistrationStatus;
                    $Approvaldate = $rec->Approvaldate;
                    $ExpiryDate = $rec->ExpiryDate;
                    $CerificateNo = $rec->CerificateNo;
                    

                    $product_origin_id = 1;
                    if($ProductOrigin == 'Imported'){
                        $product_origin_id = 2;

                    }
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    
                    $classification_id = 2;
                    
                $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
                $common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$CommonName, 'description'=>$CommonName),array('name'=>$CommonName),'Common Names');
                   
                $dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('name'=>$DosageForm,'section_id'=>$section_id, 'description'=>$DosageForm),array('name'=>$DosageForm),'DoSage form');
                $product_form_id = $this->saveProductDataEntry('par_product_forms',array('name'=>$ProductForm, 'section_id'=>$section_id,'description'=>$ProductForm),array('name'=>$ProductForm),'Product form');
                $storage_condition_id = $this->saveProductDataEntry('par_storage_conditions',array('name'=>$StorageConditions, 'section_id'=>$section_id,'description'=>$StorageConditions),array('name'=>$StorageConditions),'Product form');
                $product_category_id = $this->saveProductDataEntry('par_product_categories',array('name'=>$ProductCategory, 'section_id'=>$section_id,'description'=>$ProductCategory),array('name'=>$ProductCategory),'Product form');
                $product_subcategory_id = $this->saveProductDataEntry('par_subproduct_categories',array('name'=>$ProductSubCategory, 'product_category_id'=>$product_category_id, 'section_id'=>$section_id,'description'=>$ProductSubCategory),array('name'=>$ProductSubCategory),'Product form');

                $distribution_category_id = $this->saveProductDataEntry('par_distribution_categories',array('name'=>$DistributionCategory, 'section_id'=>$section_id,'description'=>$DistributionCategory),array('name'=>$DistributionCategory),'Product form');

                $intended_enduser_id = $this->saveProductDataEntry('par_intended_enduser',array('name'=>$IntendedEndUser, 'section_id'=>$section_id,'description'=>$IntendedEndUser),array('name'=>$IntendedEndUser),'Product form');
                $route_of_administration_id = $this->saveProductDataEntry('par_route_of_administration',array('name'=>$RouteOfAdministration, 'section_id'=>$section_id,'description'=>$RouteOfAdministration),array('name'=>$RouteOfAdministration),'Product form');

                $method_ofuse_id = $this->saveProductDataEntry('par_methodof_use',array('name'=>$MethodOfUse, 'section_id'=>$section_id,'description'=>$MethodOfUse),array('name'=>$MethodOfUse),'Product form');
                
                
                $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             'product_form_id'=>$product_form_id,
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$BrandName,
                                             'physical_description'=>$PhysicalDescription,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_strength'=>$ProductStrength,
                                             'storage_condition_id'=>$storage_condition_id,
                                             'product_category_id'=>$product_category_id,
                                             'product_subcategory_id'=>$product_subcategory_id,
                                             'distribution_category_id'=>$distribution_category_id,  'intended_enduser_id'=>$intended_enduser_id,
                                             'intended_use'=>$IntendedUse,
                                             'route_of_administration_id'=>$route_of_administration_id,
                                             'method_ofuse_id'=>$method_ofuse_id,
                                             'contraindication'=>$Contraindication,
                                             'indication'=>$Indication,
                                             'gmdn_category'=>$GmdnCategory,
                                             'gmdn_term'=>$GmdnTerm,
                                             'warnings'=>$Warnings,
                                             'shelf_lifeafter_opening'=>$ShelfLifeafterOpening,
                                             'shelf_life'=>$ShelfLife,
                                             'instructions_of_use'=>$InstructionsOfUse,

                                             
                                             'section_id'=>$section_id,
                                          
                                         );
                                         $ingredient_id = $this->saveProductDataEntry('par_ingredients_details',array('name'=>$Ingredient, 'section_id'=>$section_id,'description'=>$Ingredient),array('name'=>$Ingredient),'Product form');

                                         $inclusion_reason_id = $this->saveProductDataEntry('par_inclusions_reasons',array('name'=>$InclusionReason, 'section_id'=>$section_id,'description'=>$InclusionReason),array('name'=>$InclusionReason),'Product form');

                                         $specification_type_id = $this->saveProductDataEntry('par_specification_types',array('name'=>$SpecificationType,'section_id'=>$section_id,  'description'=>$Ingredient),array('name'=>$SpecificationType),'Ingredietns details');

                                         $ingredientssi_unit_id = $this->saveProductDataEntry('par_si_units',array('name'=>$IngredientsSiUnit, 'section_id'=>$section_id,'description'=>$IngredientsSiUnit),array('name'=>$IngredientsSiUnit),'Product form');

                                        
                                        
                                                     //packagind details 
                                        $container_id = $this->saveProductDataEntry('par_containers',array('name'=>$Container, 'section_id'=>$section_id,'description'=>$Container),array('name'=>$Container),'Product form');
                                        $container_material_id = $this->saveProductDataEntry('par_containers',array('name'=>$ContainerMaterial, 'section_id'=>$section_id,'description'=>$ContainerMaterial),array('name'=>$ContainerMaterial),'Product form');

                                        $packaging_units_id = $this->saveProductDataEntry('par_packaging_units',array('name'=>$PackagingUnits, 'section_id'=>$section_id,'description'=>$PackagingUnits),array('name'=>$PackagingUnits),'Product form');
                                        
                        if(!$app_record){
                   
                
                                             $product_information['created_by'] = '0';
                                             $product_information['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                                             if($resp['success']){
                                                 $product_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
                    //product_ingreidents
                   
                    $ingredients_data = array('product_id'=>$product_id, 
                                        'ingredient_id'=>$ingredient_id,
                                        'specification_type_id'=>$specification_type_id,
                                        'strength'=>$Strength,
                                        'proportion'=>$Proportionunit,
                                        'ingredientssi_unit_id'=>$ingredientssi_unit_id,
                                         'inclusion_reason_id'=>$inclusion_reason_id);

                    $packaging_data = array('container_id'=>$container_id, 
                                                'container_material_id'=>$container_material_id,
                                                'retail_packaging_size'=>$RetailPackagingSize,
                                                'packaging_units_id'=>$packaging_units_id,
                                                'product_id'=>$product_id
                                             );  
                    DB::table('tra_product_ingredients')->insert($ingredients_data);
                    DB::table('tra_product_packaging')->insert($packaging_data);
                    //save product manufacturing site details 
                  
                   $mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManCountry, 'description'=>$ManCountry),array('name'=>$ManCountry),'Country');
    
                   $manregion_id = $this->saveProductDataEntry('par_regions',array('name'=>$ManRegion, 'description'=>$ManRegion, 'country_id'=>$mancountry_id),array('name'=>$ManRegion),'Region ');

                    $manufacturer_data = array('name'=>$ManufacturingSiteName, 
                                               // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManufacturingSiteName, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$ManufacturingSiteName, 
                                                // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$ManufacturingSiteName, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);
                    //save product applicant_details 
                   
                   $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
    
                   
                   $data = (object)array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     //save product local representative 
                     $local_agent_id = 0;
                     if($ItrName != ''){
                        $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ltrCountry, 'description'=>$ltrCountry),array('name'=>$ltrCountry),'Country ');
    
                        $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ltrRegion, 'country_id'=>$country_id),array('name'=>$ltrRegion),'Country ');

                        $data = (object)array('name'=>$ItrName, 
                                     'tpin_no'=>0, 
                                     'contact_person'=>$ItrName,
                                     'country_id'=>$country_id, 
                                     'region_id'=>$region_id, 
                                     'physical_address'=> $ltrPhysicalAddress,
                                     'postal_address'=>$ltrPhysicalAddress, 
                                    // 'telephone_no'=>$REPRESENTATIVE_PHONE, 
                                     'email'=>$ltrEmail, 
                                     'created_by'=>'Migration',
                                     'created_on'=>Carbon::now(),
                                     'status_id'=>1
                                 );
                         $local_agent_id = $this->saveTraderInformationDetails($data);
    
                     }
                    
                     $application_status_id = 5;
                     if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $application_status_id = 6;
                     }
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
                             $process_id = $process_data->id;
                       
                     }
                    //save product application details  RefNumber
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
                     'application_code'=>$application_code, 
                     'reference_no'=>$ReferenceNo, 
                     'view_id'=>$view_id, 
                     'applicant_id'=>$applicant_id, 
                     'local_agent_id'=>$local_agent_id, 
                     'sub_module_id'=>$sub_module_id, 
                     'assessmentprocedure_type_id'=>1, 
                     'section_id'=>$section_id, 
                     'product_id'=>$product_id, 
                     'fasttrack_option_id'=>2, 
                     'process_id'=>$process_id, 
                     'module_id'=>$module_id, 
                     'prodclass_category_id'=>$prodclass_category_id, 
                     'date_added'=>formatDate($RECEIVED), 
                     'submission_date'=>formatDate($RECEIVED), 
                     'application_status_id'=>$application_status_id, 
                     'refno_generated'=>1, 
                     'created_on'=>Carbon::now(), 
                     'created_by'=>'Migration', 
                     
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$CerificateNo,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Approvaldate),
                            'certificate_issue_date'=>formatDate($Approvaldate),
                            'expiry_date'=>formatDate($ExpiryDate),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Approvaldate),
                                    'registration_no'=>$CerificateNo,
                                    'reg_applicant_id'=>$applicant_id,
                                    'reg_local_agent_id'=>$local_agent_id,
                                    'active_app_referenceno'=>$ReferenceNo,
                                    'active_application_code'=>$application_code,
                                    'expiry_date'=>formatDate($ExpiryDate),
                                    'approval_date'=>formatDate($Approvaldate),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
                    }
                    else{
                        $regdata = array('tra_product_id'=>$product_id,
                                'validity_status_id'=>1,
                                'registration_status_id'=>1,
                                
                                'reg_applicant_id'=>$applicant_id,
                                'reg_local_agent_id'=>$local_agent_id,
                                'active_app_referenceno'=>$ReferenceNo,
                                'active_application_code'=>$application_code,
                                'created_on'=>Carbon::now()
                        );
                        $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                        if($resp['success']){
                            $reg_id = $resp['record_id'];
                        }
                        else{
                                print_r($resp);
                                exit();
                        }
                        DB::table('tra_product_applications')
                        ->where(array('application_code'=>$application_code))
                        ->update(array('reg_product_id'=>$reg_id));
    
                    }
                    //save product register
                    $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
                   
                }
                else{
					
                    //update the records 
                    $product_id = $app_record->product_id;
                    $applicant_id = $app_record->applicant_id;
                    $application_code = $app_record->application_code;
                   
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    
                    $classification_id = 2;
                    
                   
                    DB::table('tra_product_information')->where(array('id'=>$product_id))->update($product_information);

                  
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
                                             
                   $trader_data = array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    
                             DB::table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
                             DB::connection('portal_db')->table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
  
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$ReferenceNo.'</br>';
                 
                }
                print_r($res);

                $records_migrated ++;
                
            }  
		
       //     $this->saveMigrationLogsData('initiatemigrateRenewalProductsDetails',$records_migrated=0);
    
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
public function testemail(){
		try{
			
					//$res = sendMailFromNotification('Hiram Maina', 'hiram.maina@softclans.co.ke','Test','Tests','hiram.maina@softclans.co.ke','hiramwachira@gmail.com');
					 $vars = array(
                            '{tracking_no}' =>'Test Application'
                        );
					$res= onlineApplicationNotificationMail(3, 'hiram.maina@softclans.co.ke', $vars,9125);
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
	
	
}function saveMigrationLogsData($migration_description,$records_migrated=0,$records_updated=0,$records_failed=0 ){
    $data = array('migration_description'=>$migration_description, 'migration_on'=>Carbon::now(),
                         'records_migrated'=>$records_migrated,
                         'records_updated'=>$records_updated,
                         'records_failed'=>$records_failed,
                         'migration_on'=>Carbon::now(),
                         'created_on'=>Carbon::now(),
                         'created_by'=>'Migration'
                        
                        );
    DB::table('tra_migration_logs')->insert($data);
    
	
	
	
}


public function initiatemappingClincialTrialRegistrationSubmission(Request $req){
        try{
			$section_id = $req->section_id;
			
            $records_migrated =0;
                $records = DB::table('tra_clinical_trial_applications as t1')
								->leftJoin('tra_approval_recommendations as t3', 't1.application_code', 't3.application_code')
                                ->select('t1.*',  't1.id as application_id')
								->whereNull('t3.id')
                                ->get();
                if($records){
                    foreach($records as $rec){
                       
                           $destination_process = 149;
                        
                        $sub_rec = DB::table('tra_submissions')->where(array('application_code'=>$rec->application_code,'current_stage'=>$destination_process,'isDone'=>0))->first();

                        if(!$sub_rec){
                            $submission_data = array(
                                'application_id'=>$rec->application_id,
                                'applicant_id'=>$rec->applicant_id,
                                'application_code'=>$rec->application_code,
                                'reference_no'=>$rec->reference_no,
                                'tracking_no'=>$rec->tracking_no,
                                'view_id'=>$rec->view_id,
                                'process_id'=>$rec->process_id,
                                'previous_stage'=>$destination_process,
                                'current_stage'=>$destination_process,
                                'module_id'=>$rec->module_id,
                                'sub_module_id'=>$rec->sub_module_id,
                                'directive_id'=>1,
                                'application_status_id'=>$rec->application_status_id,
                                'section_id'=>$rec->section_id,
                                'urgency'=>1,
                                'remarks'=>'Migrated Renewal Applications',
                                'isRead'=>0,
                                'isDone'=>0,
                                'isComplete'=>0,
                                'date_received'=>Carbon::now(),
                                'created_on'=>Carbon::now(),
                                'created_by'=>'Migrated By'
                            
                        );
        
                        DB::table('tra_submissions')->insert($submission_data);
                        $res = 'Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no;
                        print_r('Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no);

                        } else{

                      //print_r($rec);
                           $res = 'Applications already been mapped on the submission table successsfully Application No'.$rec->reference_no;

                            $records_migrated++;
                    }  
                    }


                }
                else{

                    $res = "No application found";
                }
                $this->saveMigrationLogsData('initiatemappingProductRegistrationSubmission',$records_migrated);
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



public function initiatemappingPromotionsSubmission(Request $req){
        try{
			$section_id = $req->section_id;
			
            $records_migrated =0;
                $records = DB::table('tra_promotion_adverts_applications as t1')
								->join('Database_Promotional_Materials as t3', 't1.reference_no', 't3.reference_no')
                                ->select('t1.*',  't1.id as application_id')
								//->whereNull('t3.Ref_No_of_APPROVAL_REJECTION')
                                ->get();
                if($records){
                    foreach($records as $rec){
                        $destination_process = 419;
                         
                        $sub_rec = DB::table('tra_submissions')->where(array('application_code'=>$rec->application_code,'current_stage'=>$destination_process,'isDone'=>0))->first();


                        if(!$sub_rec){
                            $submission_data = array(
                                'application_id'=>$rec->application_id,
                                'applicant_id'=>$rec->applicant_id,
                                'application_code'=>$rec->application_code,
                                'reference_no'=>$rec->reference_no,
                                'tracking_no'=>$rec->tracking_no,
                                'view_id'=>$rec->view_id,
                                'process_id'=>$rec->process_id,
                                'previous_stage'=>$destination_process,
                                'current_stage'=>$destination_process,
                                'module_id'=>$rec->module_id,
                                'sub_module_id'=>$rec->sub_module_id,
                                'directive_id'=>1,
                                'application_status_id'=>$rec->application_status_id,
                                'section_id'=>$rec->section_id,
                                'urgency'=>1,
                                'remarks'=>'Migrated  Applications',
                                'isRead'=>0,
                                'isDone'=>0,
                                'isComplete'=>0,
                                'date_received'=>Carbon::now(),
                                'created_on'=>Carbon::now(),
                                'created_by'=>'Migrated By'
                            
                        );
        
                        DB::table('tra_submissions')->insert($submission_data);
                        $res = 'Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no;
                        print_r('Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no);

                        } else{

                      //print_r($rec);
                           $res = 'Applications already been mapped on the submission table successsfully Application No'.$rec->reference_no;

                            $records_migrated++;
                    }  
                    }


                }
                else{

                    $res = "No application found";
                }
                $this->saveMigrationLogsData('initiatemappingProductRegistrationSubmission',$records_migrated);
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
public function initiatemappingGmpSubmission(Request $req){
        try{
			$section_id = $req->section_id;
			
            $records_migrated =0;
                $records = DB::table('tra_premises_applications as t1')
								->leftJoin('tra_approval_recommendations as t3', 't1.application_code', 't3.application_code')
                                ->select('t1.*',  't1.id as application_id')
								->whereNull('t3.id')
                                ->get();
                if($records){
                    foreach($records as $rec){
                       
                         if($rec->section_id == 1){
                            $destination_process = 8;
                        }else if($rec->section_id == 2){
							
                            $destination_process = 25;
                        }
						else if($rec->section_id == 3){
                            $destination_process = 36;
                        }
						else{
							 $destination_process = 25;
						}
                        $sub_rec = DB::table('tra_submissions')->where(array('application_code'=>$rec->application_code,'current_stage'=>$destination_process,'isDone'=>0))->first();


                        if(!$sub_rec){
                            $submission_data = array(
                                'application_id'=>$rec->application_id,
                                'applicant_id'=>$rec->applicant_id,
                                'application_code'=>$rec->application_code,
                                'reference_no'=>$rec->reference_no,
                                'tracking_no'=>$rec->tracking_no,
                                'view_id'=>$rec->view_id,
                                'process_id'=>$rec->process_id,
                                'previous_stage'=>$destination_process,
                                'current_stage'=>$destination_process,
                                'module_id'=>$rec->module_id,
                                'sub_module_id'=>$rec->sub_module_id,
                                'directive_id'=>1,
                                'application_status_id'=>$rec->application_status_id,
                                'section_id'=>$rec->section_id,
                                'urgency'=>1,
                                'remarks'=>'Migrated Renewal Applications',
                                'isRead'=>0,
                                'isDone'=>0,
                                'isComplete'=>0,
                                'date_received'=>Carbon::now(),
                                'created_on'=>Carbon::now(),
                                'created_by'=>'Migrated By'
                            
                        );
        
                        DB::table('tra_submissions')->insert($submission_data);
                        $res = 'Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no;
                        print_r('Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no);

                        } else{

                      //print_r($rec);
                           $res = 'Applications already been mapped on the submission table successsfully Application No'.$rec->reference_no;

                            $records_migrated++;
                    }  
                    }


                }
                else{

                    $res = "No application found";
                }
                $this->saveMigrationLogsData('initiatemappingProductRegistrationSubmission',$records_migrated);
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
public function initiatemappingPremisesSubmission(Request $req){
        try{
			$section_id = $req->section_id;
			
            $records_migrated =0;
                $records = DB::table('tra_premises_applications as t1')
								->leftJoin('tra_approval_recommendations as t3', 't1.application_code', 't3.application_code')
                                ->select('t1.*',  't1.id as application_id')
								->whereNull('t3.id')
                                ->get();
                if($records){
                    foreach($records as $rec){
                       
                         if($rec->section_id == 1){
                            $destination_process = 8;
                        }else if($rec->section_id == 2){
							
                            $destination_process = 25;
                        }
						else if($rec->section_id == 3){
                            $destination_process = 36;
                        }
						else{
							 $destination_process = 25;
						}
                        $sub_rec = DB::table('tra_submissions')->where(array('application_code'=>$rec->application_code,'current_stage'=>$destination_process,'isDone'=>0))->first();


                        if(!$sub_rec){
                            $submission_data = array(
                                'application_id'=>$rec->application_id,
                                'applicant_id'=>$rec->applicant_id,
                                'application_code'=>$rec->application_code,
                                'reference_no'=>$rec->reference_no,
                                'tracking_no'=>$rec->tracking_no,
                                'view_id'=>$rec->view_id,
                                'process_id'=>$rec->process_id,
                                'previous_stage'=>$destination_process,
                                'current_stage'=>$destination_process,
                                'module_id'=>$rec->module_id,
                                'sub_module_id'=>$rec->sub_module_id,
                                'directive_id'=>1,
                                'application_status_id'=>$rec->application_status_id,
                                'section_id'=>$rec->section_id,
                                'urgency'=>1,
                                'remarks'=>'Migrated Renewal Applications',
                                'isRead'=>0,
                                'isDone'=>0,
                                'isComplete'=>0,
                                'date_received'=>Carbon::now(),
                                'created_on'=>Carbon::now(),
                                'created_by'=>'Migrated By'
                            
                        );
        
                        DB::table('tra_submissions')->insert($submission_data);
                        $res = 'Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no;
                        print_r('Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no);

                        } else{

                      //print_r($rec);
                           $res = 'Applications already been mapped on the submission table successsfully Application No'.$rec->reference_no;

                            $records_migrated++;
                    }  
                    }


                }
                else{

                    $res = "No application found";
                }
                $this->saveMigrationLogsData('initiatemappingProductRegistrationSubmission',$records_migrated);
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

public function initiatemappingProductRegistrationSubmission(Request $req){
        try{
			$section_id = $req->section_id;
			
            $records_migrated =0;
                $records = DB::table('tra_product_applications as t1')
                                ->join('tra_product_information as t2','t1.product_id', 't2.id')
								->leftJoin('herbal_medicinal_productsjuly as t3', 't1.reference_no', 't3.Ref_Number')
                                ->select('t1.*', 't2.*', 't1.id as application_id')
							
                                ->get();
                if($records){
                    foreach($records as $rec){
						$destination_process = 178;
                        $sub_rec = DB::table('tra_submissions')->where(array('application_code'=>$rec->application_code,'current_stage'=>$destination_process,'isDone'=>0))->first();

                        if(!$sub_rec){
                            $submission_data = array('prodclass_category_id'=>$rec->prodclass_category_id,
                                'application_id'=>$rec->application_id,
                                'applicant_id'=>$rec->applicant_id,
                                'application_code'=>$rec->application_code,
                                'reference_no'=>$rec->reference_no,
                                'tracking_no'=>$rec->tracking_no,
                                'view_id'=>$rec->view_id,
                                'process_id'=>$rec->process_id,
                                'previous_stage'=>$destination_process,
                                'current_stage'=>$destination_process,
                                'module_id'=>$rec->module_id,
                                'sub_module_id'=>$rec->sub_module_id,
                                'directive_id'=>1,
                                'application_status_id'=>$rec->application_status_id,
                                'section_id'=>$rec->section_id,
                                'urgency'=>1,
                                'remarks'=>'Migrated Renewal Applications',
                                'isRead'=>0,
                                'isDone'=>0,
                                'isComplete'=>0,
                                'date_received'=>Carbon::now(),
                                'created_on'=>Carbon::now(),
                                'created_by'=>'Migrated By'
                            
                        );
        
                        DB::table('tra_submissions')->insert($submission_data);
                        $res = 'Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no;
                        print_r('Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no);

                        } else{

                      //print_r($rec);
                           $res = 'Applications already been mapped on the submission table successsfully Application No'.$rec->reference_no;

                            $records_migrated++;
                    }  
                    }


                }
                else{

                    $res = "No application found";
                }
                $this->saveMigrationLogsData('initiatemappingProductRegistrationSubmission',$records_migrated);
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
public function initiatNewCosmeticsProductsemigrateDetails(Request $req){
    try{
        $res = 'Error occurred';
        $records_migrated =0;
        
        $table_name = $req->table_name;
        $record_id = $req->record_id;
		
        $records = DB::table($table_name)->whereRaw("BrandName is not null and BrandName != ''")->get();
		//$records = DB::
		
				foreach($records as $rec){
                                         $ReferenceNo = $rec->RefNumber;
                    $sub_module_id = 7;
                    $module_id = 1;

                    $RECEIVED = formatDate($rec->DateSubmitted);
                    $BrandName = $rec->BrandName;
                    $CommonName = $rec->Activeingredient;
                    $ClassificationName = $rec->Classificationname;
                    $ProductOrigin = $rec->Productorigin;

                    $PhysicalDescription = $rec->Physicaldescription;
                  
                    $ProductStrength = $rec->ProductStrength;
                    $ProductForm = $rec->ProductForm;
                    $DosageForm = $rec->Dosageform;
                    $StorageConditions = $rec->Storageconditions;
                    $ProductCategory = $rec->Productcategory;
                    $ProductSubCategory = $rec->Productsubcategory;
                    $DistributionCategory = $rec->Distributioncategory;
                    $IntendedEndUser = $rec->IntendedEndUser;
                    $IntendedUse = $rec->Intendeduse;
                    $RouteOfAdministration = $rec->Routeofadministration;
                    $MethodOfUse = $rec->Methodofuse;
                    $Contraindication = $rec->Contraindication;
                    $Indication = $rec->Indication;
                    $GmdnCategory = $rec->GmdnCategory;
                    $GmdnTerm = $rec->GmdnTerm;
                    $GmdnCode = $rec->GmdnCode;
                    $Warnings = $rec->Warnings;
                    $InstructionsOfUse = $rec->InstructionsOfUse;
                    $ShelfLife = $rec->ShelfLife;
                    $ShelfLifeafterOpening = $rec->ShelfLifeafterOpening;
                    
                    $Ingredient = $rec->Ingredient;
                    $SpecificationType = $rec->SpecificationType;
                    $Strength = $rec->Strength;
                    $Proportionunit = $rec->Proportion; 
                    $IngredientsSiUnit = $rec->IngredientsSiUnit;
                    $InclusionReason = $rec->InclusionReason;

                    $Container = $rec->Container;
                    $ContainerMaterial = $rec->ContainerMaterial;
                    $RetailPackagingSize = $rec->RetailPackagingSize;
                    $PackagingUnits = $rec->PackagingUnits;


                    $ApplicantName = $rec->ApplicantName;
                    $ApplicantCountry = $rec->Applicantcountry;
                    $ApplicantPhysicalAddress = $rec->Apllicantphysicaladress;
                    $ApplicantRegion = $rec->Applicantregion;
                    $ApplicantEmail = $rec->Emailaddress;
                    
                    
                    $ItrName = $rec->LocalTechnicalRepresentative;
                    $ltrPhysicalAddress = $rec->LTRphysicaladress;
                    $ltrEmail = $rec->LTREmailaddress;
                    $ltrRegion = $rec->LTRregion;
                    $ltrCountry = $rec->LTRcountry;
                   
                    $ManufacturingSiteName = $rec->ManufacturerName;
                    $ManCountry = $rec->Manufacturercountry;
                    $ManPhysicalAddress = $rec->Manufacturerphysicaladress;
                    $ManRegion = $rec->Manufacturerregion;
                    $ManEmail = $rec->ManufacturerEmailaddress;
                    
                    $ApprovalDecision = $rec->ApprovalDecision;
                    $RegistrationStatus = $rec->RegistrationStatus;
                    $Approvaldate = $rec->Approvaldate;
                    $ExpiryDate = $rec->ExpiryDate;
                    $CerificateNo = $rec->CerificateNo;
                    

                    $product_origin_id = 1;
                    if($ProductOrigin == 'Imported'){
                        $product_origin_id = 2;

                    }
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    
                    $classification_id =  $rec->classification_id;;
                    
                $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$ReferenceNo))->first();
                $common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$CommonName, 'description'=>$CommonName),array('name'=>$CommonName),'Common Names');
                   
                $dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('name'=>$DosageForm,'section_id'=>$section_id, 'description'=>$DosageForm),array('name'=>$DosageForm),'DoSage form');
                $product_form_id = $this->saveProductDataEntry('par_product_forms',array('name'=>$ProductForm, 'section_id'=>$section_id,'description'=>$ProductForm),array('name'=>$ProductForm),'Product form');
                $storage_condition_id = $this->saveProductDataEntry('par_storage_conditions',array('name'=>$StorageConditions, 'section_id'=>$section_id,'description'=>$StorageConditions),array('name'=>$StorageConditions),'Product form');
                $product_category_id = $this->saveProductDataEntry('par_product_categories',array('name'=>$ProductCategory, 'section_id'=>$section_id,'description'=>$ProductCategory),array('name'=>$ProductCategory),'Product form');
                $product_subcategory_id = $this->saveProductDataEntry('par_subproduct_categories',array('name'=>$ProductSubCategory, 'product_category_id'=>$product_category_id, 'section_id'=>$section_id,'description'=>$ProductSubCategory),array('name'=>$ProductSubCategory),'Product form');

                $distribution_category_id = $this->saveProductDataEntry('par_distribution_categories',array('name'=>$DistributionCategory, 'section_id'=>$section_id,'description'=>$DistributionCategory),array('name'=>$DistributionCategory),'Product form');

                $intended_enduser_id = $this->saveProductDataEntry('par_intended_enduser',array('name'=>$IntendedEndUser, 'section_id'=>$section_id,'description'=>$IntendedEndUser),array('name'=>$IntendedEndUser),'Product form');
                $route_of_administration_id = $this->saveProductDataEntry('par_route_of_administration',array('name'=>$RouteOfAdministration, 'section_id'=>$section_id,'description'=>$RouteOfAdministration),array('name'=>$RouteOfAdministration),'Product form');

                $method_ofuse_id = $this->saveProductDataEntry('par_methodof_use',array('name'=>$MethodOfUse, 'section_id'=>$section_id,'description'=>$MethodOfUse),array('name'=>$MethodOfUse),'Product form');
                
                
                $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             'product_form_id'=>$product_form_id,
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$BrandName,
                                             'physical_description'=>$PhysicalDescription,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_strength'=>$ProductStrength,
                                             'storage_condition_id'=>$storage_condition_id,
                                             'product_category_id'=>$product_category_id,
                                             'product_subcategory_id'=>$product_subcategory_id,
                                             'distribution_category_id'=>$distribution_category_id,  'intended_enduser_id'=>$intended_enduser_id,
                                             'intended_use'=>$IntendedUse,
                                             'route_of_administration_id'=>$route_of_administration_id,
                                             'method_ofuse_id'=>$method_ofuse_id,
                                             'contraindication'=>$Contraindication,
                                             'indication'=>$Indication,
                                             'gmdn_category'=>$GmdnCategory,
                                             'gmdn_term'=>$GmdnTerm,
                                             'warnings'=>$Warnings,
                                             'shelf_lifeafter_opening'=>$ShelfLifeafterOpening,
                                             'shelf_life'=>$ShelfLife,
                                             'instructions_of_use'=>$InstructionsOfUse,

                                             
                                             'section_id'=>$section_id,
                                          
                                         );
                                         $ingredient_id = $this->saveProductDataEntry('par_ingredients_details',array('name'=>$Ingredient, 'section_id'=>$section_id,'description'=>$Ingredient),array('name'=>$Ingredient),'Product form');

                                         $inclusion_reason_id = $this->saveProductDataEntry('par_inclusions_reasons',array('name'=>$InclusionReason, 'section_id'=>$section_id,'description'=>$InclusionReason),array('name'=>$InclusionReason),'Product form');

                                         $specification_type_id = $this->saveProductDataEntry('par_specification_types',array('name'=>$SpecificationType,'section_id'=>$section_id,  'description'=>$Ingredient),array('name'=>$SpecificationType),'Ingredietns details');

                                         $ingredientssi_unit_id = $this->saveProductDataEntry('par_si_units',array('name'=>$IngredientsSiUnit, 'section_id'=>$section_id,'description'=>$IngredientsSiUnit),array('name'=>$IngredientsSiUnit),'Product form');

                                        
                                        
                                                     //packagind details 
                                        $container_id = $this->saveProductDataEntry('par_containers',array('name'=>$Container, 'section_id'=>$section_id,'description'=>$Container),array('name'=>$Container),'Product form');
                                        $container_material_id = $this->saveProductDataEntry('par_containers',array('name'=>$ContainerMaterial, 'section_id'=>$section_id,'description'=>$ContainerMaterial),array('name'=>$ContainerMaterial),'Product form');

                                        $packaging_units_id = $this->saveProductDataEntry('par_packaging_units',array('name'=>$PackagingUnits, 'section_id'=>$section_id,'description'=>$PackagingUnits),array('name'=>$PackagingUnits),'Product form');
                                        
                        if(!$app_record){
                   
                
                                             $product_information['created_by'] = '0';
                                             $product_information['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                                             if($resp['success']){
                                                 $product_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
                    //product_ingreidents
                   
                    $ingredients_data = array('product_id'=>$product_id, 
                                        'ingredient_id'=>$ingredient_id,
                                        'specification_type_id'=>$specification_type_id,
                                        'strength'=>$Strength,
                                        'proportion'=>$Proportionunit,
                                        'ingredientssi_unit_id'=>$ingredientssi_unit_id,
                                         'inclusion_reason_id'=>$inclusion_reason_id);

                    $packaging_data = array('container_id'=>$container_id, 
                                                'container_material_id'=>$container_material_id,
                                                'retail_packaging_size'=>$RetailPackagingSize,
                                                'packaging_units_id'=>$packaging_units_id,
                                                'product_id'=>$product_id
                                             );  
                    DB::table('tra_product_ingredients')->insert($ingredients_data);
                    DB::table('tra_product_packaging')->insert($packaging_data);
                    //save product manufacturing site details 
                  
                   $mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManCountry, 'description'=>$ManCountry),array('name'=>$ManCountry),'Country');
    
                   $manregion_id = $this->saveProductDataEntry('par_regions',array('name'=>$ManRegion, 'description'=>$ManRegion, 'country_id'=>$mancountry_id),array('name'=>$ManRegion),'Region ');

                    $manufacturer_data = array('name'=>$ManufacturingSiteName, 
                                               // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManufacturingSiteName, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$ManufacturingSiteName, 
                                                // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$ManPhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$ManEmail, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$ManufacturingSiteName, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);
                    //save product applicant_details 
                   
                   $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
    
                   
                   $data = (object)array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     //save product local representative 
                     $local_agent_id = 0;
                     if($ItrName != ''){
                        $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ltrCountry, 'description'=>$ltrCountry),array('name'=>$ltrCountry),'Country ');
    
                        $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ltrRegion, 'country_id'=>$country_id),array('name'=>$ltrRegion),'Country ');

                        $data = (object)array('name'=>$ItrName, 
                                     'tpin_no'=>0, 
                                     'contact_person'=>$ItrName,
                                     'country_id'=>$country_id, 
                                     'region_id'=>$region_id, 
                                     'physical_address'=> $ltrPhysicalAddress,
                                     'postal_address'=>$ltrPhysicalAddress, 
                                    // 'telephone_no'=>$REPRESENTATIVE_PHONE, 
                                     'email'=>$ltrEmail, 
                                     'created_by'=>'Migration',
                                     'created_on'=>Carbon::now(),
                                     'status_id'=>1
                                 );
                         $local_agent_id = $this->saveTraderInformationDetails($data);
    
                     }
                    
                     $application_status_id = 5;
                     if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $application_status_id = 6;
                     }
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
                             $process_id = $process_data->id;
                       
                     }
                    //save product application details  RefNumber
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
                     'application_code'=>$application_code, 
                     'reference_no'=>$ReferenceNo, 
                     'view_id'=>$view_id, 
                     'applicant_id'=>$applicant_id, 
                     'local_agent_id'=>$local_agent_id, 
                     'sub_module_id'=>$sub_module_id, 
                     'assessmentprocedure_type_id'=>1, 
                     'section_id'=>$section_id, 
                     'product_id'=>$product_id, 
                     'fasttrack_option_id'=>2, 
                     'process_id'=>$process_id, 
                     'module_id'=>$module_id, 
                     'prodclass_category_id'=>$prodclass_category_id, 
                     'date_added'=>formatDate($RECEIVED), 
                     'submission_date'=>formatDate($RECEIVED), 
                     'application_status_id'=>$application_status_id, 
                     'refno_generated'=>1, 
                     'created_on'=>Carbon::now(), 
                     'created_by'=>'Migration', 
                     
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($CerificateNo != '' && $CerificateNo != 'nan'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$CerificateNo,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Approvaldate),
                            'certificate_issue_date'=>formatDate($Approvaldate),
                            'expiry_date'=>formatDate($ExpiryDate),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$CerificateNo))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Approvaldate),
                                    'registration_no'=>$CerificateNo,
                                    'reg_applicant_id'=>$applicant_id,
                                    'reg_local_agent_id'=>$local_agent_id,
                                    'active_app_referenceno'=>$ReferenceNo,
                                    'active_application_code'=>$application_code,
                                    'expiry_date'=>formatDate($ExpiryDate),
                                    'approval_date'=>formatDate($Approvaldate),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }
                    }
                    else{
                        $regdata = array('tra_product_id'=>$product_id,
                                'validity_status_id'=>1,
                                'registration_status_id'=>1,
                                
                                'reg_applicant_id'=>$applicant_id,
                                'reg_local_agent_id'=>$local_agent_id,
                                'active_app_referenceno'=>$ReferenceNo,
                                'active_application_code'=>$application_code,
                                'created_on'=>Carbon::now()
                        );
                        $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                        if($resp['success']){
                            $reg_id = $resp['record_id'];
                        }
                        else{
                                print_r($resp);
                                exit();
                        }
                        DB::table('tra_product_applications')
                        ->where(array('application_code'=>$application_code))
                        ->update(array('reg_product_id'=>$reg_id));
    
                    }
                    //save product register
                    $res = "Application Migration Successfully Application No: ".$ReferenceNo.'</br>';
                   
                }
                else{
					
                    //update the records 
                    $product_id = $app_record->product_id;
                    $applicant_id = $app_record->applicant_id;
                    $application_code = $app_record->application_code;
                   
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    
                    $classification_id = 2;
                    
                   $app_data = array('prodclass_category_id'=>$prodclass_category_id);
                    DB::table('tra_product_information')->where(array('id'=>$product_id))->update($product_information);
						DB::table('tra_product_applications')->where(array('application_code'=>$application_code))->update($app_data);

                  
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion, 'description'=>$ApplicantRegion, 'country_id'=>$country_id),array('name'=>$ApplicantRegion),'Country ');
                                             
                   $trader_data = array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    
                             DB::table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
                             DB::connection('portal_db')->table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
  
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$ReferenceNo.'</br>';
                 
                }
                print_r($res);

                $records_migrated ++;
                
            }  
		
       //     $this->saveMigrationLogsData('initiatemigrateRenewalProductsDetails',$records_migrated=0);
    
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


public function initiatemigratePromotionalDatasets(Request $req){
    try{
        $records = DB::table('Database_Promotional_Materials')->get();
        if($records){
			
			
			
			
                foreach($records as $rec){
                    //clinical trial details 
					
                     $ReferenceNo = $rec->reference_no;
                     $reference_no = $rec->reference_no;
					 $id = $rec->SNO;
                     $date_received = $rec->date_received;
                     $section_id = $rec->section_id;
                    $sub_module_id = 33;
                    $module_id = 12;
					
                     $DESCRIPTION_OF_THE_APPLICATION = $rec->DESCRIPTION_OF_THE_APPLICATION;
                     $DRUG_MEDICAL_DEVICES_OR_COSMETICS = $rec->DRUG_MEDICAL_DEVICES_OR_COSMETICS;
                     $MODE_OF_PROMOTION = $rec->MODE_OF_PROMOTION;
                     $ApplicantName = $rec->COMPANY;
                     $APPROVAL_STATUS = $rec->APPROVAL_STATUS;
                     $Ref_No_of_APPROVAL_REJECTION = $rec->Ref_No_of_APPROVAL_REJECTION;
                     $DATE_OF_APPROVAL = $rec->DATE_OF_APPROVAL;
                     $VALIDITY_OF_APPROVAL = $rec->VALIDITY_OF_APPROVAL;
                     $certificate_no = $rec->Ref_No_of_APPROVAL_REJECTION;
                     $company_country = $rec->company_country;
					$records = DB::table('tra_promotion_adverts_applications')->where('reference_no', $reference_no)->count();
					if($records ==0){
						
                     $id = $rec->SNO;
                     $date_received = $rec->date_received;
                     $section_id = $rec->section_id;
                    $sub_module_id = 33;
                    $module_id = 12;
					
                     $DESCRIPTION_OF_THE_APPLICATION = $rec->DESCRIPTION_OF_THE_APPLICATION;
                     $DRUG_MEDICAL_DEVICES_OR_COSMETICS = $rec->DRUG_MEDICAL_DEVICES_OR_COSMETICS;
                     $MODE_OF_PROMOTION = $rec->MODE_OF_PROMOTION;
                     $ApplicantName = $rec->COMPANY;
                     $APPROVAL_STATUS = $rec->APPROVAL_STATUS;
                     $Ref_No_of_APPROVAL_REJECTION = $rec->Ref_No_of_APPROVAL_REJECTION;
                     $DATE_OF_APPROVAL = $rec->DATE_OF_APPROVAL;
                     $VALIDITY_OF_APPROVAL = $rec->VALIDITY_OF_APPROVAL;
                     $certificate_no = $rec->Ref_No_of_APPROVAL_REJECTION;
                     $company_country = $rec->company_country;
                     $description_of_advert = $rec->DESCRIPTION_OF_THE_APPLICATION.' '.$MODE_OF_PROMOTION;
                     $process_id = 0;
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array( 'sub_module_id'=>$sub_module_id,'section_id'=>$section_id));
                     if($process_data){
                         
                         $process_id = $process_data->id;
                       
                     }
                  
                   $applicant_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$company_country, 'description'=>$company_country),array('name'=>$company_country),'Country');
                
                   $data = (object)array('name'=>$ApplicantName, 
                        'tpin_no'=>0, 
                        'contact_person'=>$ApplicantName,
                        'country_id'=>$applicant_country_id, 
                        'created_by'=>'Migration',
                        'created_on'=>Carbon::now(),
                        'status_id'=>1
                    );
                    
                    $applicant_id = $this->saveTraderInformationDetails($data);
                  
                    
                    $view_id = generateApplicationViewID();
                    $application_code =  generateApplicationCode(7, 'tra_clinical_trial_applications');
                    if($certificate_no != ''){


                        $application_status_id = 6;
                    }
                    else{
                        $application_status_id = 5;

                    }
					$advertisement_type_id = 1;
                    $application_data = array(
                                    'application_code'=>$application_code, 
                                    'reference_no'=>$ReferenceNo, 
                                    'tracking_no'=>$ReferenceNo, 
                                    'view_id'=>$view_id, 
                                    'applicant_id'=>$applicant_id, 
                                    'sub_module_id'=>$sub_module_id, 
                                    'section_id'=>$section_id, 
                                    'fasttrack_option_id'=>2, 
                                    'process_id'=>$process_id, 
                                    'module_id'=>$module_id, 
                                    'advertisement_type_id'=>$advertisement_type_id, 
                                    'description_of_advert'=>$description_of_advert, 
                                    'date_added'=>formatDate($date_received), 
                                    'submission_date'=>formatDate($date_received), 
                                    'application_status_id'=>$application_status_id, 
                                    'refno_generated'=>1, 
                                    'created_on'=>Carbon::now(), 
                                    'created_by'=>'Migration', 
                    
                   );
                 
                    $resp =  insertRecord('tra_promotion_adverts_applications', $application_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
					
                    //approval infrmation  ReferenceNo
                    if($certificate_no != ''){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$certificate_no, 'module_id'=>$module_id))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                                                'application_id'=>$application_id,
                                                'decision_id'=>1,
                                                'module_id'=>$module_id,
                                                'certificate_no'=>$certificate_no,
                                                'appvalidity_status_id'=>2,
                                                'appregistration_status_id'=>2,
                                                'comment'=>'Migration Approval Details',
                                                'approval_date'=>$DATE_OF_APPROVAL,
                                                'certificate_issue_date'=>formatDate($DATE_OF_APPROVAL),
                                                'expiry_date'=>formatDate($VALIDITY_OF_APPROVAL),
                                                'approved_by'=>'Migration Data',
                                                'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }
                            $app_record = DB::table('tra_registered_promotionalapps')->where(array('registration_no'=>$certificate_no))->first();
                            if(!$app_record){
                                $regdata = array(
                                        'validity_status_id'=>2,
                                        'registration_status_id'=>2, 
										'registration_date'=>formatDate($DATE_OF_APPROVAL),
										'expiry_date'=>formatDate($VALIDITY_OF_APPROVAL),
                                        'registration_no'=>$certificate_no,
                                        'registration_ref_no'=>$reference_no,
                                        'active_application_code'=>$application_code,
                                        'created_on'=>Carbon::now()
                                );
                                $resp =  insertRecord('tra_registered_promotionalapps', $regdata, 'Migration');
                                if($resp['success']){
                                    $reg_id = $resp['record_id'];
                                }
                                else{
                                        print_r($resp);
                                        exit();
                                }
                                DB::table('tra_promotion_adverts_applications')
                                ->where(array('application_code'=>$application_code))
                                ->update(array('registered_promotionalapp_id'=>$reg_id));
                            }
                        }
                        $res = array('success'=>true, 'message'=>'Application Migrated Successfully');
                        print_r("Application has been migrated successfully ".$reference_no.'</br>');
                    }else{
                        $res = array('success'=>true, 'message'=>'Application Migrated Successfully');
                        print_r("Application Not Approvedd  ".$reference_no.'</br>');

                    }
						
						
					}else{
						 if($certificate_no != ''){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$certificate_no, 'module_id'=>$module_id))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                                                'application_id'=>$application_id,
                                                'decision_id'=>1,
                                                'module_id'=>$module_id,
                                                'certificate_no'=>$certificate_no,
                                                'appvalidity_status_id'=>2,
                                                'appregistration_status_id'=>2,
                                                'comment'=>'Migration Approval Details',
                                                'approval_date'=>$DATE_OF_APPROVAL,
                                                'certificate_issue_date'=>formatDate($DATE_OF_APPROVAL),
                                                'expiry_date'=>formatDate($VALIDITY_OF_APPROVAL),
                                                'approved_by'=>'Migration Data',
                                                'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }
                            $app_record = DB::table('tra_registered_promotionalapps')->where(array('registration_no'=>$certificate_no))->first();
                            if(!$app_record){
                                $regdata = array(
                                        'validity_status_id'=>2,
                                        'registration_status_id'=>2, 
										'registration_date'=>formatDate($DATE_OF_APPROVAL),
										'expiry_date'=>formatDate($VALIDITY_OF_APPROVAL),
                                        'registration_no'=>$certificate_no,
                                        'registration_ref_no'=>$reference_no,
                                        'active_application_code'=>$application_code,
                                        'created_on'=>Carbon::now()
                                );
                                $resp =  insertRecord('tra_registered_promotionalapps', $regdata, 'Migration');
                                if($resp['success']){
                                    $reg_id = $resp['record_id'];
                                }
                                else{
                                        print_r($resp);
                                        exit();
                                }
                                DB::table('tra_promotion_adverts_applications')
                                ->where(array('application_code'=>$application_code))
                                ->update(array('registered_promotionalapp_id'=>$reg_id));
                            }
                        }
                        $res = array('success'=>true, 'message'=>'Application Migrated Successfully');
                        print_r("Application has been migrated successfully ".$reference_no.'</br>');
                    }
                        $res = array('success'=>true, 'message'=>'Application Migrated Successfully');
                        print_r("Application already migrated  ".$reference_no.'</br>');

                    }
                }
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

public function initiatemigrateClinicalTrialDatasets(Request $req){
    try{
        $records = DB::table('clinical_trialmigration')->get();
        if($records){
                foreach($records as $rec){
                    //clinical trial details 
                     $id = $rec->Id;
                     $Date_of_Clinical_Trial_Application = Carbon::now();
                    $sub_module_id = 10;
                //     $Date_of_Issue_of_Clinical_Trial_Certificate = $rec->Date_of_Issue_of_Clinical_Trial_Certificate;
                  //   $Expiry_Date = $rec->Expiry_Date;

                     $Clinical_Trial_Title = $rec->Title_of_the_clinical_trial_Protocol;
                     $ReferenceNo = $rec->Application_reference_Number;
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array( 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
                         $process_id = $process_data->id;
                       
                     }
                     if($ReferenceNo == ''){

                        $codes_array = array('section_code'=>'CTR', 'zone_code'=>00);
        
                        $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                        if ($tracking_details['success'] == false) {
                            return \response()->json($tracking_details);
                        }
                        $ReferenceNo = 'MGR-'.$tracking_details['tracking_no'];
                       
                    }
                    
                    $ProtocolNo = '';// $rec->ProtocolNo;
                    $VersionNo ='';// $rec->VersionNo;
                    $DateOfProtocol = '';//$rec->DateOfProtocol;

                    $Study_Design = '';//$rec->TrialDesign;
                  //  $Study_Phase = $rec->Study_Phase;
                    $PurposeOfTrial = $rec->Title_of_the_clinical_trial_Protocol;
                    $PublicationUrl = '';//$rec->PublicationUrl;
                    $PublicTitle = $rec->Title_of_the_clinical_trial_Protocol;
                    $ClinicalTrialDescription = $rec->Title_of_the_clinical_trial_Protocol;
                    $PhaseId = $rec->Trial_Phase;
                    $IP_ProdSection = $rec->Investigational_Product_s;

                    
                    $StudyStartDate = '';// $rec->StudyStartDate;
                    $StudyEndDate =  '';//$rec->StudyEndDate;
                    $EthicsClearanceNo =  '';//$rec->EthicsClearanceNo;

                    
                    $StudyDuration = $rec->Trial_duration;
                   
                   
                    $InvestigationProductSection = $rec->Investigational_Product_s;
                    $InvestigationProductBrand =  '';//$rec->InvestigationProductBrand;
                    $InvestigatorProductClassification =  '';//$rec->InvestigatorProductClassification;
                    $DosageForm =  '';//$rec->DosageForm;
                    $CommonName =  '';//$rec->CommonName;
                    $ActiveIngredients =  '';//$rec->ActiveIngredients;
                    //manufactrer
                    $ManufacturerName = '';// $rec->ManufacturerName;
                    $ManufacturerPhysicalAddress =  '';//$rec->ManufacturerPhysicalAddress;
                    $ManufacturerPhysicalRegion =  '';//$rec->ManufacturerPhysicalRegion;
                    $ManufacturerPhysicalCountry =  '';//$rec->ManufacturerPhysicalCountry;
                   //applicant details 
                   
                    //comparator product
                  //  $Name_of_Comparator = $rec->Name_of_Comparator;
                     //Applicant Details
                     $ApplicantName = $rec->Principal_Investigator_PI;
                     $ApplicantPhysicalAddress =  '';//$rec->ApplicantPhysicalAddress;
                     $ApplicantEmail =  '';//$rec->ApplicantEmail;
                     $ApplicantRegion =  '';//$rec->ApplicantRegion;
                     $ApplicantCountry =  '';//$rec->ApplicantCountry;
                 
                    //prncipal investigators
                    $principalInvName = $rec->Principal_Investigator_PI;
                    $principalInvPhysicalAddress = '';//$rec->principalInvPhysicalAddress;
                    $PrincipalInvEmail = '';//$rec->PrincipalInvEmail;
                    $PrincipalInvRegion = '';//$rec->PrincipalInvRegion;
                    $PrincipalInvCountry = '';//$rec->PrincipalInvCountry;
                    //sponsor details 
                    $SponsorName = $rec->Name_of_Sponsor;
                    $SponsorPhysicalAddress ='';// $rec->SponsorPhysicalAddress;
                    $SponsorEmail ='';// $rec->SponsorEmail;
                    $SponsorRegion ='';// $rec->SponsorRegion;
                    $SponsorCountry ='';// $rec->SponsorCountry;
                   
                    $status =$rec->Current_regulatory_Status;
                    //study site details
                    $StudySiteName = $rec->Clinical_Trial_Site;
                    $StudySiteCountry = '';// $rec->StudySiteCountry;
                    $StudySiteRegion = '';// $rec->StudySiteRegion;
                    $StudySiteCountry1 = '';// $rec->StudySiteCountry1;
                    $StudyPhysicalAddress = '';// $rec->StudyPhysicalAddress;
                    $StudyTelephone = '';// $rec->StudyTelephone;
                    $StudyEmailAddress ='';//  $rec->StudyEmailAddress;

                     //other investigators
                     $OtherInvestigatorsName = '';// $rec->OtherInvestigatorsName;
                     $OtherInvestigatorsPhysicalAddress ='';//  $rec->OtherInvestigatorsPhysicalAddress;
                     $OtherInvestigatorsEmail ='';//  $rec->OtherInvestigatorsEmail;
                     $OtherInvestigatorsRegion = '';// $rec->OtherInvestigatorsRegion;
                     $OtherInvestigatorsCountry = '';// $rec->OtherInvestigatorsCountry;
                     $OtherInvestigatorsCategory = '';// $rec->OtherInvestigatorsCategory;

                   
                    //IP
                    
                   //applicant
                   $applicant_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
                
                   $applicant_regionid = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion,'country_id'=>$applicant_country_id, 'description'=>$ApplicantRegion),array('name'=>$ApplicantRegion),'Region Details');
                   
                   $data = (object)array('name'=>$ApplicantName, 
                        'tpin_no'=>0, 
                        'contact_person'=>$ApplicantName,
                        //'country_id'=>$applicant_country_id, 
                        //'region_id'=>$applicant_regionid, 
                        'physical_address'=>$ApplicantPhysicalAddress, 
                        'postal_address'=>$principalInvPhysicalAddress, 
                      //  'telephone_no'=>$PrincContact_Details_Telephone_1, 
                        'email'=>$ApplicantEmail, 
                        'created_by'=>'Migration',
                        'created_on'=>Carbon::now(),
                        'status_id'=>1
                    );
                    
                    $applicant_id = $this->saveTraderInformationDetails($data);
                  
                    //save investigator
                    $pi_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$PrincipalInvCountry, 'description'=>$PrincipalInvCountry),array('name'=>$PrincipalInvCountry),'Country');
                
                    $pi_regionid = $this->saveProductDataEntry('par_regions',array('name'=>$PrincipalInvRegion,'country_id'=>$pi_country_id, 'description'=>$PrincipalInvRegion),array('name'=>$PrincipalInvRegion),'Region Details'); 
                    $investigator_data = array('name'=>$principalInvName, 
                       // 'country_id'=>$pi_country_id, 
                        //'region_id'=>$pi_regionid, 
                        'physical_address'=>$principalInvPhysicalAddress, 
                        'postal_address'=>$principalInvPhysicalAddress, 
                      //  'telephone'=>$PrincContact_Details_Telephone_1, 
                        'email'=>$PrincipalInvEmail, 
                        'created_by'=>'Migration',
                        'created_on'=>Carbon::now()
                    );
                   
                    $investigator_id = $this->saveProductDataEntry('clinical_trial_personnel',$investigator_data,array('name'=>$principalInvName, 'country_id'=>$pi_country_id),'Investigator Details  Id');    
                    //save sponsor 
              
                    $sponscountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$SponsorCountry, 'description'=>$SponsorCountry),array('name'=>$SponsorCountry),'Country');
                    $sponsregionid = $this->saveProductDataEntry('par_regions',array('name'=>$SponsorRegion,'country_id'=>$sponscountry_id, 'description'=>$SponsorRegion),array('name'=>$SponsorRegion),'Region Details');
                    
                    $sponsor_data = array('name'=>$SponsorName, 
                                                'physical_address'=>$SponsorPhysicalAddress, 
                                                'postal_address'=>$SponsorPhysicalAddress, 
                                                'email'=>$SponsorEmail, 
                                                //'telephone'=>$SponsorContact_Details_telephone_2, 
                                              //  'region_id'=>$sponsregionid, 
                                               // 'country_id'=>$sponscountry_id
                                            );
                                                       
                    $sponsor_id = $this->saveProductDataEntry('clinical_trial_personnel',$sponsor_data,array('name'=>$SponsorName, 'country_id'=>$sponscountry_id),'Sponsor Details  Id');            
                    //save clincial study site
                    
                    $study_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$StudySiteCountry1, 'description'=>$StudySiteCountry1),array('name'=>$StudySiteCountry1),'Country');
                    $study_region_id = $this->saveProductDataEntry('par_regions',array('name'=>$StudySiteRegion,'country_id'=>$study_country_id, 'description'=>$StudySiteRegion),array('name'=>$StudySiteRegion),'Region Details');

                    $study_sitedata = array('name'=>$StudySiteName, 
                                                'physical_address'=>$StudyPhysicalAddress, 
                                                'postal_address'=>$StudyPhysicalAddress, 
                                                'email_address'=>$StudyEmailAddress, 
                                            
                                                //'region_id'=>$study_region_id, 
                                                'telephone'=>$StudyTelephone,
                                                'country_id'=>126
                                            );
                    $study_site_id = $this->saveProductDataEntry('study_sites',$study_sitedata,array('name'=>$StudySiteName, 'region_id'=>$study_region_id),'Study Site Details  Id');            
                    $PhaseIdData = array('name'=>$PhaseId, 'description'=>$PhaseId, 'is_enabled'=>1);
                    $phase_id = $this->saveProductDataEntry('par_clinical_phases',$PhaseIdData,array('name'=>$PhaseId, 'description'=>$PhaseId),'Study Site Details  Id');   
                    $module_id = 7;
                    $sub_module_id = 10;
                    $section_id  = 5;
                    
                    $view_id = generateApplicationViewID();
                    $application_code =  generateApplicationCode(7, 'tra_clinical_trial_applications');
                    if($status == 'Active' || $status == 'Closed' || $status == 'Approved'){


                        $application_status_id = 6;
                    }
                    else{
                        $application_status_id = 5;

                    }
                    $application_data = array(
                                    'application_code'=>$application_code, 
                                    'reference_no'=>$ReferenceNo, 
                                    'view_id'=>$view_id, 
                                    'applicant_id'=>$applicant_id, 
                                    'sub_module_id'=>$sub_module_id, 
                                    'section_id'=>$section_id, 
                                    'fasttrack_option_id'=>2, 
                                    'process_id'=>$process_id, 
                                    'module_id'=>$module_id, 
                                    'trial_design'=>$Study_Design, 
                                    'clinical_prodsection_id'=>2,
                                    'public_title'=>$PublicTitle, 
                                    'short_study_title'=>$PublicTitle, 
                                    'clinicaltrial_description'=>$ClinicalTrialDescription,
                                    'purpose_of_trial'=>$PurposeOfTrial,
                                    'proposed_start_date'=>formatDate($StudyStartDate), 
                                    'phase_id'=>$phase_id, 
                                    'sponsor_id'=>$sponsor_id, 
                                    'investigator_id'=>$investigator_id, 
                                    'study_title'=>$Clinical_Trial_Title, 
                                    'protocol_no'=>$ProtocolNo, 
                                    'version_no'=>$VersionNo,  
                                    'date_of_protocol'=>$DateOfProtocol,
                                    'publication_url'=>$PublicationUrl,
                                    'clearance_no'=>$EthicsClearanceNo,
                                    'study_duration'=>$StudyDuration,
                                    'study_end_date'=>formatDate($StudyEndDate),  

                                    'date_added'=>formatDate($Date_of_Clinical_Trial_Application), 
                                    'submission_date'=>formatDate($Date_of_Clinical_Trial_Application), 
                                    'application_status_id'=>$application_status_id, 
                                    'refno_generated'=>1, 
                                    'created_on'=>Carbon::now(), 
                                    'created_by'=>'Migration', 
                    
                   );
                 
                    $resp =  insertRecord('tra_clinical_trial_applications', $application_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    $site_data = array('application_id'=>$application_id,
                                    'study_site_id'=>$study_site_id,
                                    'created_on'=>Carbon::now(), 
                                    'created_by'=>'Migration', 
                                );

                    
                    $resp =  insertRecord('clinical_trial_sites', $site_data, 'Migration');
                    //investigation product 
                    $investigationproduct_section_id = 2;
                   
                    $investigationprod_classification_id = 2;
                    $product_category_id = 2;
                    if($InvestigationProductSection == 'Medical Devices'){

                        $investigationproduct_section_id =4;
                    }
                    else  if($InvestigationProductSection == 'Medicines'){

                        $investigationproduct_section_id =2;

                    } else  if($InvestigationProductSection == 'Food'){


                        $investigationproduct_section_id =1;
                    }    $investigationproduct_section_id =2;
					/*
                    $commonnameData = array('name'=>$CommonName, 'description'=>$CommonName, 'is_enabled'=>1, 'section_id'=>$investigationproduct_section_id);
                    $common_name_id = $this->saveProductDataEntry('par_common_names',$commonnameData,array('name'=>$CommonName, 'section_id'=>$investigationproduct_section_id),'Common Name'); 

                    $dosageData = array('name'=>$DosageForm, 'description'=>$DosageForm, 'is_enabled'=>1, 'section_id'=>$investigationproduct_section_id);

                    $common_name_id = $this->saveProductDataEntry('par_dosage_forms',$dosageData,array('name'=>$DosageForm, 'section_id'=>$investigationproduct_section_id),'Dosage'); 
                    $classData = array('name'=>$DosageForm, 'description'=>$DosageForm, 'is_enabled'=>1, 'section_id'=>$investigationproduct_section_id);

                    $classification_id = $this->saveProductDataEntry('par_classifications',$classData,array('name'=>$InvestigatorProductClassification, 'section_id'=>$investigationproduct_section_id),'Dosage');

                    
                    $man_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManufacturerPhysicalCountry, 'description'=>$ManufacturerPhysicalCountry),array('name'=>$ManufacturerPhysicalCountry),'Country');

                    $man_region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ManufacturerPhysicalRegion,'country_id'=>$man_country_id, 'description'=>$ManufacturerPhysicalRegion),array('name'=>$ManufacturerPhysicalRegion),'Region Details');

                    $man_sitedata = array('name'=>$ManufacturerName, 
                                                'physical_address'=>$ManufacturerPhysicalAddress, 
                                                'postal_address'=>$ManufacturerPhysicalAddress, 
                                                'email_address'=>$StudyEmailAddress, 
                                            
                                                'region_id'=>$man_region_id, 
                                                'country_id'=>$man_country_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$man_sitedata,array('name'=>$ManufacturerName, 'country_id'=>$man_country_id),'Manufacturer');    

                    $ip_product_data = array('investigationproduct_section_id'=>$investigationproduct_section_id, 
                                            'investigationprod_classification_id'=>$investigationprod_classification_id,  
                                            'common_name_id'=>$common_name_id, 
                                            'product_desc'=>$ActiveIngredients, 
                                            'brand_name'=>$InvestigationProductBrand,
                                            'product_category_id'=>2,
                                            'manufacturer_id'=>$manufacturer_id
                                );
                    $resp =  insertRecord('clinical_trial_products', $ip_product_data, 'Migration');
                    $category_id =7;
                    if($OtherInvestigatorsCategory == 'Co Invesigator'){
                        $category_id =    1; 

                    }
                    else if($OtherInvestigatorsCategory == 'Principal investigator'){

                        $category_id = 7;
                    }
                     $pi_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$OtherInvestigatorsCountry, 'description'=>$OtherInvestigatorsCountry),array('name'=>$OtherInvestigatorsCountry),'Country');
                
                     $pi_regionid = $this->saveProductDataEntry('par_regions',array('name'=>$OtherInvestigatorsRegion,'country_id'=>$pi_country_id, 'description'=>$OtherInvestigatorsRegion),array('name'=>$OtherInvestigatorsRegion),'Region Details'); 
                     $otherinvestigator_data = array('name'=>$OtherInvestigatorsName, 
                         'country_id'=>$pi_country_id, 
                         'region_id'=>$pi_regionid, 
                         'physical_address'=>$OtherInvestigatorsPhysicalAddress, 
                         'postal_address'=>$principalInvPhysicalAddress, 
                       //  'telephone'=>$PrincContact_Details_Telephone_1, 
                         'email'=>$OtherInvestigatorsEmail, 
                         'created_by'=>'Migration',
                         'created_on'=>Carbon::now()
                     );
                    
                     $investigator_id = $this->saveProductDataEntry('clinical_trial_personnel',$otherinvestigator_data,array('name'=>$OtherInvestigatorsName, 'country_id'=>$pi_country_id),'Investigator Details  Id');    
                     $otherinvestigator_data = array('category_id'=>$category_id, 
                                'investigator_id'=>$investigator_id,  
                                'application_id'=>$application_id, 
                                'application_reference_no'=>$ReferenceNo
                    );
                     $resp =  insertRecord('clinical_trial_investigators', $otherinvestigator_data, 'Migration');
                     */
                    //approval infrmation  ReferenceNo
                    if($status == 'Active' || $status == 'Closed' || $status == 'Approved'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$ReferenceNo, 'module_id'=>$module_id))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                                                'application_id'=>$application_id,
                                                'decision_id'=>1,
                                                'module_id'=>$module_id,
                                                'certificate_no'=>$ReferenceNo,
                                                'appvalidity_status_id'=>2,
                                                'appregistration_status_id'=>2,
                                                'comment'=>'Migration Approval Details',
                                                //'approval_date'=>$certificate_approval_date,
                                                //'certificate_issue_date'=>formatDate($certificate_approval_date),
                                                //'expiry_date'=>formatDate($certificate_expiry_date),
                                                'approved_by'=>'Migration Data',
                                                'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }
                            $app_record = DB::table('registered_clinical_trials')->where(array('registration_no'=>$ReferenceNo))->first();
                            if(!$app_record){
                                $regdata = array('tra_clinical_trial_id'=>$application_id,
                                        'validity_status_id'=>2,
                                        'registration_status_id'=>2,
                                        'registration_no'=>$ReferenceNo,
                                        'active_application_code'=>$application_code,
                                        'created_on'=>Carbon::now()
                                );
                                $resp =  insertRecord('registered_clinical_trials', $regdata, 'Migration');
                                if($resp['success']){
                                    $reg_id = $resp['record_id'];
                                }
                                else{
									
									
                                        print_r($resp);
                                        exit();
                                }
                                DB::table('tra_clinical_trial_applications')
                                ->where(array('application_code'=>$application_code))
                                ->update(array('reg_clinical_trial_id'=>$reg_id));
                            }
                        }
                        $res = array('success'=>true, 'message'=>'Application Migrated Successfully');
                        print_r("Application has been migrated successfully ".$ReferenceNo.'</br>');
                    }else{
                        $res = array('success'=>true, 'message'=>'Application Migrated Successfully');
                        print_r("Application already migrated  ".$ReferenceNo.'</br>');

                    }
                    DB::table('clinical_trial')->where('id',$id)->update(array('ReferenceNo'=>$ReferenceNo));
                    
                }
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

function getLikeSingleRecordColValue($table_name,$value){
	$record_id =0;
	$record = DB::table($table_name)->whereRaw("name like '%".$value."%'")->first();
	if($record){
		$record_id = $record->id;
	}
	return $record_id;
	
	
}
public function initiateFoodPremisesDataMapping(Request $req){
	
	
	$month = date('m', strtotime('2021-12-30'));
	
    try{
        $records_migrated =0;
        $table_name = "ict_extracted_productsdata";
        $table_name = "migration_food_premises";
        $records = DB::table($table_name)->whereNotNull('Premise_registration_certificate_number')->get();
        foreach($records as $rec){
			
            $Application_No = $rec->Operational_License_Ref_No;
            $premise_reg_no = $rec->Premise_registration_certificate_number;
            $Premise_reg_no = $rec->Premise_registration_certificate_number;
            $permit_no = $rec->Operational_License_Ref_No;
            $Premise_registration_certificate_number = $rec->Premise_registration_certificate_number;
            $sub_module_id = 1;
                    $module_id = 2;
                    $section_id = 1;
                    //all the columns 
                    $Premises_name = $rec->NAME_OF_INSTITUTION;
                    $RECEIVED = Carbon::now();//$rec->RECEIVED;
                   
                    $Region_name = $rec->Province;
                    $District_name = $rec->District;
                    $Sector = $rec->Sector;
                    $Cell_Centre = $rec->Cell_Centre;
                    $postal_address = '';//$rec->Postal_address;
					
                    $Email = $rec->Director_Email;
                    $physical_address = $rec->Cell_Centre;
                    $Date_of_registration = $rec->Date_of_issuance;
                    $expiry_date = $rec->expiry_date;
					
                    //$Organization_Type = $rec->Organization_Type;
                    //$Secondary_Organization_Type = $rec->Secondary_Organization_Type;
                    $Sector = $rec->Sector;
                    $Cell_Centre = $rec->Cell_Centre;
                    $Director_Name = $rec->Director_Name;
                    $Director_Position = $rec->Director_Position;
                    $Director_Email = $rec->Director_Email;
                    $company_registration_no =$rec->Company_code;
                    $Director_Phone_Number = $rec->Director_Phone_Number;
					
                    $Pharmacist_Name = $rec->Name_of_Responsible_Technician;
                    $Premise_Category = $rec->Premise_Category;
					
                    $Product_category = $rec->Product_category;
                    $Products_type = $rec->Products_type;
					
			//	$premise_type_id = 7;
					//$business_type_detail_id = 5;
					//print_r('test');
					//exit();
					
                     $country_id = 126;
                    $region_id = getSingleRecordColValue('par_regions', array('name'=>$Region_name), 'id');
                     $district_id = getSingleRecordColValue('par_districts', array('name'=>$District_name), 'id');
                    $sector_id = getSingleRecordColValue('par_sectors', array('name'=>$Sector), 'id');
                    $cell_id = getSingleRecordColValue('par_cells', array('name'=>$Cell_Centre), 'id');
                    
                    $premise_type_id = getSingleRecordColValue('par_business_types', array('name'=>$Premise_Category), 'id');
					
                    $product_category_id = $this->getLikeSingleRecordColValue('par_product_categories', $Product_category);
                    $product_subcategory_id = $this->getLikeSingleRecordColValue('par_subproduct_categories', $Products_type);
					
                    $product_details = $Product_category .' '.$Products_type;
					 $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
							$process_id = $process_data->id;
                       
                     }
                $app_record = DB::table('tra_premises_applications as t1')
											->join('tra_premises as t2', 't1.premise_id', 't2.id')
											->select('t1.*')
											->where(array('t2.name'=>$Premises_name))
											->first();
									$premapp_record = 	$app_record;	
                if(!$app_record){
                    //generate trader not
					$codes_array = array('section_code'=>'F');
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
					
							$ReferenceNo = 'MGR/'.$tracking_details['tracking_no'];
                   
					
                    $premises_infor = array('premise_type_id'=>$premise_type_id,
                                                 'name'=>$Premises_name,
                                                 'premise_reg_no'=>$premise_reg_no,
                                                 'country_id'=>$country_id,
                                                 'region_id'=>$region_id,
                                                 'city_id'=>$region_id,
                                                 'section_id'=>$section_id,
                                                 'physical_address'=>$physical_address,
                                                 'postal_address'=>$postal_address,
                                                 'street'=>$physical_address,
                                                 'managing_director_email'=>$Director_Email,
                                                 'managing_director'=>$Director_Name,
                                                 'managing_director_telepone'=>$Director_Phone_Number,
                                                 'sector_id'=>$sector_id,
                                                 'cell_id'=>$cell_id,
                                                 'company_registration_no'=>$company_registration_no
                                                 
                                             );
                                           
                                             $premises_infor['created_by'] = '0';
                                             $premises_infor['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises', $premises_infor, 'Migration');
                                             
                                             if($resp['success']){
                                                 $premise_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
								$premises_otherdetails = array('business_type_id'=>$premise_type_id,
                                                 'product_category_id'=>$product_category_id,
                                                 'product_subcategory_id'=>$product_subcategory_id,
                                                 'product_details'=>$product_details,
                                                 'premise_id'=>$premise_id,
												 );
                     $premises_otherdetails['created_by'] = '0';
                                             $premises_otherdetails['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_otherdetails', $premises_otherdetails, 'Migration');
                                             
                   $personnel_details = array('personnel_name'=>$Pharmacist_Name,
                                                 'premise_id'=>$premise_id,
												 );
                     $personnel_details['created_by'] = '0';
                                             $personnel_details['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_personnel', $personnel_details, 'Migration');
                                             
                   $data = (object)array('name'=>$Director_Name, 
                                'tpin_no'=>0, 
                                'contact_person'=>$Director_Name,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$physical_address, 
                                'postal_address'=>$physical_address, 
                                'telephone_no'=>$Director_Phone_Number, 
                                'email'=>$Director_Email, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     
                     $application_status_id = 5;
                     if($premise_reg_no != ''){
                        $application_status_id = 6;
                     }
                    
                    //save product application details 
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(1, 'tra_premises_applications');
                     $premises_app = array(
							 'application_code'=>$application_code, 
							 'reference_no'=>$ReferenceNo, 
							 'view_id'=>$view_id, 
							
                                                 'section_id'=>$section_id,
							 'sub_module_id'=>$sub_module_id, 
							 'section_id'=>$section_id, 
							 'premise_id'=>$premise_id,  
							 'process_id'=>$process_id, 
							 'module_id'=>$module_id, 
							 'date_added'=>formatDate($RECEIVED), 
							 'submission_date'=>formatDate($RECEIVED), 
							 'application_status_id'=>$application_status_id, 
							 'refno_generated'=>1, 
							 'created_on'=>Carbon::now(), 
							 'created_by'=>'Migration', 
							 
                    );
                    $resp =  insertRecord('tra_premises_applications', $premises_app, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($permit_no != ''){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('permit_no'=>$permit_no))->first();

                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'permit_no'=>$permit_no,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Date_of_registration),
                            'certificate_issue_date'=>formatDate($Date_of_registration),
                            'expiry_date'=>formatDate($expiry_date),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('registered_premises')->where(array('registration_no'=>$permit_no))->first();
                        if(!$app_record){
                            $regdata = array('tra_premise_id'=>$premise_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Date_of_registration),
                                    'registration_no'=>$premise_reg_no,
                                    'active_app_referenceno'=>$Application_No,
                                    'active_application_code'=>$application_code,
                                    'approval_date'=>formatDate($Date_of_registration),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('registered_premises', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_premises_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_premise_id'=>$reg_id));
							
							  DB::table('tra_premises')
                            ->where(array('id'=>$premise_id))
                            ->update(array('registered_id'=>$reg_id));
							
							
                        }
						
                    }
                   
                    $res = "Application Migration Successfully Application No: ".$Application_No.'</br>';
                }
                else{
					/*
					$premise_id = $app_record->premise_id;
					
					$premises_otherdetails = array('business_type_id'=>$premise_type_id,
                                                 'product_category_id'=>$product_category_id,
                                                 'product_subcategory_id'=>$product_subcategory_id,
                                                 'product_details'=>$product_details,
                                                 'premise_id'=>$premise_id,
												 );
                     $premises_otherdetails['created_by'] = '0';
                                             $premises_otherdetails['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_otherdetails', $premises_otherdetails, 'Migration');
                                             
                   $personnel_details = array('personnel_name'=>$Pharmacist_Name,
                                                 'premise_id'=>$premise_id,
												 );
                     $personnel_details['created_by'] = '0';
                                             $personnel_details['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_personnel', $personnel_details, 'Migration');
                      */			
					if($Premise_registration_certificate_number != ''){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('permit_no'=>$Premise_registration_certificate_number))->first();

                        if(!$app_record){
                            $approval_data = array('application_code'=>$premapp_record->application_code,
                            'application_id'=>$premapp_record->id,
                            'decision_id'=>1,
                            'module_id'=>$premapp_record->module_id,
                            'permit_no'=>$Premise_registration_certificate_number,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Date_of_registration),
                            'certificate_issue_date'=>formatDate($Date_of_registration),
                            'expiry_date'=>formatDate($expiry_date),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('registered_premises')->where(array('registration_no'=>$Premise_registration_certificate_number))->first();
                        if(!$app_record){
                            $regdata = array('tra_premise_id'=>$premapp_record->premise_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Date_of_registration),
                                    'registration_no'=>$Premise_registration_certificate_number,
                                    'active_app_referenceno'=>$premapp_record->reference_no,
                                    'active_application_code'=>$premapp_record->application_code,
                                    'approval_date'=>formatDate($Date_of_registration),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('registered_premises', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_premises_applications')
                            ->where(array('application_code'=>$premapp_record->application_code))
                            ->update(array('reg_premise_id'=>$reg_id));
							
							  DB::table('tra_premises')
                            ->where(array('id'=>$premapp_record->premise_id))
                            ->update(array('registered_id'=>$reg_id));
							
							
                        }
						
                    }	
		
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$Application_No.'</br>';
                   
                }
                print_r($res);

                $records_migrated ++;
                
            }  
            $this->saveMigrationLogsData('initiatePremisesDataMapping',$records_migrated=0);
    
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
public function initiateGmpRegistrationMigration(Request $req){
	
	
	$month = date('m', strtotime('2021-12-30'));
	
    try{
        $records_migrated =0;
        $table_name = "gmp_migrationseptember";
        $records = DB::table($table_name)->get();
        foreach($records as $rec){
			
            $Application_No = $rec->Certificate_No;
            $reference_no = '';
            $SubmittedOn = Carbon::now();
         //   $AssessmentType = $rec->AssessmentType;
          //  $Premise_reg_no = $rec->Premise_registration_certificate_number;
            $GmpProductsection = 2;
           // $ProcessName = $rec->ProcessName;
            $ApplicantName = $rec->Applicant_Name;
            $ApplicantPhysicalAddress = $rec->Physical_Address;
            $ApplicantEmail = $rec->Email;
            $ApplicantRegion = $rec->Region;
            $ApplicantCountry = $rec->Country;
            $InspectionType = $rec->Assessment_Type;
			
            $ManName = $rec->Facility_name;
            $District = $rec->District_name;
            $RegionName = $rec->Region;
            $Country = $rec->Country;
            //$MobileNo = $rec->MobileNo;
            $EmailAddress = $rec->Email;
           // $TelephoneNo = $rec->TelephoneNo;
            $PhysicalAddress = $rec->Physical_Address;
        //    $BusinessRegistrationNo = $rec->BusinessRegistrationNo;
           // $GMPBusinessActivities = $rec->GMPBusinessActivities;
           // $ManufacturingBlocks = $rec->ManufacturingBlocks;
           // $ProductLine = $rec->ProductLine;
			
           // $ProductLineDescription = $rec->ProductLineDescription;
           // $Manufacturing_contact_person = $rec->Manufacturing_contact_person;
           // $Contact_person_Email = $rec->Contact_person_Email;
           // $Contact_Telephone = $rec->Contact_Telephone;
           // $Contact_Position = $rec->Contact_Position;
           // $Current_Status = $rec->Current_Status;
           // $RegistrationStatus = $rec->RegistrationStatus;
            $ApprovalDate = $rec->Date_of_Certification_Issue;
            $ExpiryDate = $rec->Date_of_Certification_Expiry;
            $PermitNo = $rec->Certificate_No;
            $GMPCertificateNo = $rec->Certificate_No;
			
          //  $LTRName = $rec->LTRName;
           // $LTREmailAddress_1 = $rec->EmailAddress_1;
           // $LTRTelephoneNo_1 = $rec->TelephoneNo_1;
          //  $LTRPhysicalAddress_1 = $rec->PhysicalAddress_1;
			$sub_module_id = 5;
                    $module_id = 3;
                    $section_id = 2;
			if($reference_no == '' || $reference_no == 'null' ||  $reference_no == 0 || $reference_no == 'N/A (Applied by email)'  || $reference_no == 'Not specified'){

                    $app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
                    $reference_no = 'MGR-'.$tracking_details['tracking_no'];
                   
                }
			
				$country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
				 $manregion_id = $this->saveProductDataEntry('par_regions',array('name'=>$RegionName, 'description'=>$RegionName, 'country_id'=>$country_id),array('name'=>$RegionName),'Region');
				 $mandistrict_id = $this->saveProductDataEntry('par_districts',array('name'=>$District, 'description'=>$District, 'region_id'=>$manregion_id),array('name'=>$District),'Region');

				 $data = (object)array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                'region_id'=>$manregion_id, 
                                'district_id'=>$mandistrict_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
					// $application_status_id = 5;
                    // if($Current_Status == 'Compliant'){
                        $application_status_id = 6;
                   //  }
                     
					
                    //all the columns 
                     $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_gmp_applications');
					
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$Country, 'description'=>$Country),array('name'=>$Country),'Country');
					$gmp_type_id = 1;
					if($country ==126){
							$gmp_type_id = 2;
						
					}
					
					 $manufacturer_data = array('name'=>$ManName, 
                                                'physical_address'=>$PhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
											
											'region_id'=>$manregion_id, 
											'district_id'=>$mandistrict_id, 
                                                'email_address'=>$EmailAddress, 
                                                'country_id'=>$country_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManName, 'country_id'=>$country_id),'Manufacturer Id');  
					
                    $premises_infor = array(
                                    'name'=>$ManName,
                                    'applicant_id'=>$applicant_id,
                                    'business_type_id'=>76,
                                    'country_id'=>$country_id,
									'country_id'=>$country_id, 
									'region_id'=>$manregion_id, 
									'district_id'=>$mandistrict_id, 
                                    'section_id'=>$section_id,
                                    'email'=>$EmailAddress,
									 'manufacturer_id'=>$manufacturer_id,
                                    'postal_address'=>$PhysicalAddress,
                                    'telephone_no'=>$req->TelephoneNo,
                                    'mobile_no'=>$req->TelephoneNo,
                                    'physical_address'=>$PhysicalAddress,
                                    'gmp_type_id'=>$gmp_type_id
                        );
                                           
                       $premises_infor['created_by'] = '0';
                        $premises_infor['created_on'] = Carbon::now();
                        $resp =  insertRecord('tra_manufacturing_sites', $premises_infor, 'Migration');
                                             
                                             if($resp['success']){
                                                 $manufacturing_site_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
							
                   
                    
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     
                     $application_status_id = 5;
                    if($Current_Status == 'Compliant'){
                        $application_status_id = 6;
                     }
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
							$process_id = $process_data->id;
                       
                     }
                    //save product application details 
                   $app_data = array('sub_module_id'=>$sub_module_id,
                                   'section_id'=>$section_id,
                                   'module_id'=>$module_id,
                                   'gmp_type_id'=>$gmp_type_id,
                                   'applicant_id'=>$applicant_id,
                                   'manufacturing_site_id'=>$manufacturing_site_id
                           );
						 
					$app_data['manufacturing_site_id']=$manufacturing_site_id;
					$app_data['assessment_type_id']=1;
					$app_data['view_id']=$view_id;
                            $app_data['application_code']=$application_code;
                            $app_data['tracking_no']=$reference_no;
                            $app_data['reference_no']=$reference_no;
                            $app_data['date_added']=formatDate($SubmittedOn);
                            $app_data['application_status_id']=1;
                            $app_data['created_by']='';
                            $app_data['created_on']=Carbon::now();
                            
                            $resp = insertRecord('tra_gmp_applications', $app_data, '');
                    
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                   // if($Current_Status == 'Compliant'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$GMPCertificateNo))->first();

                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$GMPCertificateNo,
                            'permit_no'=>$GMPCertificateNo,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($ApprovalDate),
                            'certificate_issue_date'=>formatDate($ApprovalDate),
                            'expiry_date'=>formatDate($ExpiryDate),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register SubmittedOn
                        $app_record = DB::table('registered_manufacturing_sites')->where(array('registration_no'=>$reference_no))->first();
                        if(!$app_record){
                            $regdata = array('tra_site_id'=>$manufacturing_site_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($ApprovalDate),
                                    'registration_no'=>$reference_no,
                                    'active_app_referenceno'=>$reference_no,
                                    'active_application_code'=>$application_code,
                                    'approval_date'=>formatDate($Date_of_registration),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('registered_manufacturing_sites', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_gmp_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_site_id'=>$reg_id));
						
							
                        }
						
                 //   }
                   
                    $res = "Application Migration Successfully Application No: ".$Application_No.'</br>';
                   /*
                }
                else{
					
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$Application_No.'</br>';
                   
                }
				*/
                print_r($res);

                $records_migrated ++;
                
            }  
            $this->saveMigrationLogsData('initiatePremisesDataMapping',$records_migrated=0);
    
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
public function initiateGmpRegistrationMigrationArchive(Request $req){
	
	
	$month = date('m', strtotime('2021-12-30'));
	
    try{
        $records_migrated =0;
        $table_name = "gmp_migrationseptember";
        $records = DB::table($table_name)->get();
        foreach($records as $rec){
			
            $Application_No = $rec->ReferenceNo;
            $reference_no = $rec->ReferenceNo;
            $SubmittedOn = $rec->SubmittedOn;
            $AssessmentType = $rec->AssessmentType;
            $Premise_reg_no = $rec->Premise_registration_certificate_number;
            $GmpProductsection = $rec->GmpProductsection;
            $ProcessName = $rec->ProcessName;
            $ApplicantName = $rec->ApplicantName;
            $ApplicantPhysicalAddress = $rec->ApplicantPhysicalAddress;
            $ApplicantEmail = $rec->ApplicantEmail;
            $ApplicantRegion = $rec->ApplicantRegion;
            $ApplicantCountry = $rec->ApplicantCountry;
            $InspectionType = $rec->InspectionType;
			
            $ManName = $rec->ManName;
            $District = $rec->District;
            $RegionName = $rec->RegionName;
            $Country = $rec->Country;
            $MobileNo = $rec->MobileNo;
            $EmailAddress = $rec->EmailAddress;
            $TelephoneNo = $rec->TelephoneNo;
            $PhysicalAddress = $rec->PhysicalAddress;
            $BusinessRegistrationNo = $rec->BusinessRegistrationNo;
            $GMPBusinessActivities = $rec->GMPBusinessActivities;
            $ManufacturingBlocks = $rec->ManufacturingBlocks;
            $ProductLine = $rec->ProductLine;
			
            $ProductLineDescription = $rec->ProductLineDescription;
            $Manufacturing_contact_person = $rec->Manufacturing_contact_person;
            $Contact_person_Email = $rec->Contact_person_Email;
            $Contact_Telephone = $rec->Contact_Telephone;
            $Contact_Position = $rec->Contact_Position;
            $Current_Status = $rec->Current_Status;
            $RegistrationStatus = $rec->RegistrationStatus;
            $ApprovalDate = $rec->ApprovalDate;
            $ExpiryDate = $rec->ExpiryDate;
            $PermitNo = $rec->PermitNo;
            $GMPCertificateNo = $rec->GMPCertificateNo;
            $LTRName = $rec->LTRName;
            $LTREmailAddress_1 = $rec->EmailAddress_1;
            $LTRTelephoneNo_1 = $rec->TelephoneNo_1;
            $LTRPhysicalAddress_1 = $rec->PhysicalAddress_1;
			$sub_module_id = 5;
                    $module_id = 3;
                    $section_id = 2;
			if($reference_no == '' || $reference_no == 'null' ||  $reference_no == 0 || $reference_no == 'N/A (Applied by email)'  || $reference_no == 'Not specified'){

                    $app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
                    $reference_no = 'MGR-'.$tracking_details['tracking_no'];
                   
                }
			
				$country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
					
				 $data = (object)array('name'=>$ApplicantName, 
                                'tpin_no'=>0, 
                                'contact_person'=>$ApplicantName,
                                'country_id'=>$country_id, 
                                //'region_id'=>$region_id, 
                                'physical_address'=>$ApplicantPhysicalAddress, 
                                'postal_address'=>$ApplicantPhysicalAddress, 
                                'telephone_no'=>'', 
                                'email'=>$ApplicantEmail, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
					 $application_status_id = 5;
                     if($Current_Status == 'Compliant'){
                        $application_status_id = 6;
                     }
                     
					
                    //all the columns 
                     $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
					
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$Country, 'description'=>$Country),array('name'=>$Country),'Country');
					$gmp_type_id = 1;
					if($country ==126){
							$gmp_type_id = 2;
						
					}
					
					 $manufacturer_data = array('name'=>$ManName, 
                                                'physical_address'=>$PhysicalAddress, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$EmailAddress, 
                                                'country_id'=>$country_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$ManName, 'country_id'=>$country_id),'Manufacturer Id');  
					
                    $premises_infor = array(
                                    'name'=>$ManName,
                                    'applicant_id'=>$applicant_id,
                                    'business_type_id'=>76,
                                    'country_id'=>$country_id,
                                    'section_id'=>$section_id,
                                    'email'=>$EmailAddress,
									 'manufacturer_id'=>$manufacturer_id,
                                    'postal_address'=>$PhysicalAddress,
                                    'telephone_no'=>$req->TelephoneNo,
                                    'mobile_no'=>$req->TelephoneNo,
                                    'physical_address'=>$PhysicalAddress,
                                   
                                    'gmp_type_id'=>$gmp_type_id
                        );
                                           
                       $premises_infor['created_by'] = '0';
                        $premises_infor['created_on'] = Carbon::now();
                        $resp =  insertRecord('tra_manufacturing_sites', $premises_infor, 'Migration');
                                             
                                             if($resp['success']){
                                                 $manufacturing_site_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
							
                   
                    
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     
                     $application_status_id = 5;
                    if($Current_Status == 'Compliant'){
                        $application_status_id = 6;
                     }
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
							$process_id = $process_data->id;
                       
                     }
                    //save product application details 
                   $app_data = array('sub_module_id'=>$sub_module_id,
                                   'section_id'=>$section_id,
                                   'module_id'=>$module_id,
                                   'gmp_type_id'=>$gmp_type_id,
                                   'applicant_id'=>$applicant_id,
                                   'manufacturing_site_id'=>$manufacturing_site_id
                           );
						 
					$app_data['manufacturing_site_id']=$manufacturing_site_id;
					$app_data['assessment_type_id']=1;
					$app_data['view_id']=$view_id;
                            $app_data['application_code']=$application_code;
                            $app_data['tracking_no']=$reference_no;
                            $app_data['reference_no']=$reference_no;
                            $app_data['date_added']=formatDate($SubmittedOn);
                            $app_data['application_status_id']=1;
                            $app_data['created_by']='';
                            $app_data['created_on']=Carbon::now();
                            
                            $resp = insertRecord('tra_gmp_applications', $app_data, '');
                    
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($Current_Status == 'Compliant'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$reference_no))->first();

                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'certificate_no'=>$reference_no,
                            'permit_no'=>$permit_no,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Date_of_registration),
                            'certificate_issue_date'=>formatDate($Date_of_registration),
                            'expiry_date'=>formatDate($expiry_date),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register SubmittedOn
                        $app_record = DB::table('registered_manufacturing_sites')->where(array('registration_no'=>$reference_no))->first();
                        if(!$app_record){
                            $regdata = array('tra_site_id'=>$manufacturing_site_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Date_of_registration),
                                    'registration_no'=>$reference_no,
                                    'active_app_referenceno'=>$reference_no,
                                    'active_application_code'=>$application_code,
                                    'approval_date'=>formatDate($Date_of_registration),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('registered_manufacturing_sites', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_gmp_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_site_id'=>$reg_id));
						
							
                        }
						
                    }
                   
                    $res = "Application Migration Successfully Application No: ".$Application_No.'</br>';
                   /*
                }
                else{
					
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$Application_No.'</br>';
                   
                }
				*/
                print_r($res);

                $records_migrated ++;
                
            }  
            $this->saveMigrationLogsData('initiatePremisesDataMapping',$records_migrated=0);
    
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

public function initiatePremisesDataMapping(Request $req){
	
	
	$month = date('m', strtotime('2021-12-30'));
	
    try{
        $records_migrated =0;
        $table_name = "ict_extracted_productsdata";
        $table_name = "licensed_premises_human_wholesale_pharmacies";
        $records = DB::table($table_name)->get();
        foreach($records as $rec){
			
            $Application_No = $rec->Operational_License_Ref_No;
            $premise_reg_no = $rec->Premise_registration_certificate_number;
            $Premise_reg_no = $rec->Premise_registration_certificate_number;
            $permit_no = $rec->Operational_License_Ref_No;
            $sub_module_id = 1;
                    $module_id = 2;
                    $section_id = 2;
                    //all the columns 
                    $Premises_name = $rec->NAME_OF_INSTITUTION;
                    $RECEIVED = Carbon::now();//$rec->RECEIVED;
                   
                    $Region_name = $rec->Province;
                    $District_name = $rec->District;
                    $Sector = $rec->Sector;
                    $Cell_Centre = $rec->Cell_Centre;
                    $postal_address = '';//$rec->Postal_address;
					
                    $Email = $rec->Director_Email;
                    $physical_address = $rec->Cell_Centre;
                    $Date_of_registration = $rec->Date_of_issuance;
                    $expiry_date = $rec->expiry_date;
					
                    $Organization_Type = $rec->Organization_Type;
                    $Secondary_Organization_Type = $rec->Secondary_Organization_Type;
                    $Sector = $rec->Sector;
                    $Cell_Centre = $rec->Cell_Centre;
                    $Director_Name = $rec->Director_Name;
                    $Director_Position = $rec->Director_Position;
                    $Director_Email = $rec->Director_Email;
                    $company_registration_no =$rec->Company_code;
                    $Director_Phone_Number = $rec->Director_Phone_Number;
					
                    $Pharmacist_Name = $rec->Pharmacist_Name;
					
					$premise_type_id = 7;
					$business_type_detail_id = 5;
					
                $app_record = DB::table('tra_premises_applications')->where(array('reference_no'=>$Application_No))->first();
                if(!$app_record){
                    
                     $country_id = 126;
                    $region_id = getSingleRecordColValue('par_regions', array('name'=>$Region_name), 'id');
                     $district_id = getSingleRecordColValue('par_districts', array('name'=>$District_name), 'id');
                    $sector_id = getSingleRecordColValue('par_sectors', array('name'=>$Sector), 'id');
                    $cell_id = getSingleRecordColValue('par_cells', array('name'=>$Cell_Centre), 'id');
                    
                   
                    $premises_infor = array('premise_type_id'=>$premise_type_id,
                                                 'name'=>$Premises_name,
                                                 'premise_reg_no'=>$premise_reg_no,
                                                 'country_id'=>$country_id,
                                                 'region_id'=>$region_id,
                                                 'city_id'=>$region_id,
                                                 'section_id'=>$section_id,
                                                 'physical_address'=>$physical_address,
                                                 'postal_address'=>$postal_address,
                                                 'street'=>$physical_address,
                                                 'managing_director_email'=>$Director_Email,
                                                 'managing_director'=>$Director_Name,
                                                 'managing_director_telepone'=>$Director_Phone_Number,
                                                 'sector_id'=>$sector_id,
                                                 'cell_id'=>$cell_id,
                                                 'company_registration_no'=>$company_registration_no
                                                 
                                             );
                                           
                                             $premises_infor['created_by'] = '0';
                                             $premises_infor['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises', $premises_infor, 'Migration');
                                             
                                             if($resp['success']){
                                                 $premise_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
								$premises_otherdetails = array('business_type_id'=>$premise_type_id,
                                                 'business_type_detail_id'=>$business_type_detail_id,
                                                 'premise_id'=>$premise_id,
												 );
                     $premises_otherdetails['created_by'] = '0';
                                             $premises_otherdetails['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_otherdetails', $premises_otherdetails, 'Migration');
                                             
                   
                   $data = (object)array('name'=>$Director_Name, 
                                'tpin_no'=>0, 
                                'contact_person'=>$Director_Name,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$physical_address, 
                                'postal_address'=>$physical_address, 
                                'telephone_no'=>$Director_Phone_Number, 
                                'email'=>$Director_Email, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     
                     $application_status_id = 5;
                     if($premise_reg_no != ''){
                        $application_status_id = 6;
                     }
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
							$process_id = $process_data->id;
                       
                     }
                    //save product application details 
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(1, 'tra_premises_applications');
                     $premises_app = array(
							 'application_code'=>$application_code, 
							 'reference_no'=>$Application_No, 
							 'view_id'=>$view_id, 
							
                                                 'section_id'=>$section_id,
							 'sub_module_id'=>$sub_module_id, 
							 'section_id'=>$section_id, 
							 'premise_id'=>$premise_id,  
							 'process_id'=>$process_id, 
							 'module_id'=>$module_id, 
							 'date_added'=>formatDate($RECEIVED), 
							 'submission_date'=>formatDate($RECEIVED), 
							 'application_status_id'=>$application_status_id, 
							 'refno_generated'=>1, 
							 'created_on'=>Carbon::now(), 
							 'created_by'=>'Migration', 
							 
                    );
                    $resp =  insertRecord('tra_premises_applications', $premises_app, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($permit_no != ''){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('permit_no'=>$permit_no))->first();

                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'permit_no'=>$permit_no,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Date_of_registration),
                            'certificate_issue_date'=>formatDate($Date_of_registration),
                            'expiry_date'=>formatDate($expiry_date),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('registered_premises')->where(array('registration_no'=>$permit_no))->first();
                        if(!$app_record){
                            $regdata = array('tra_premise_id'=>$premise_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Date_of_registration),
                                    'registration_no'=>$premise_reg_no,
                                    'active_app_referenceno'=>$Application_No,
                                    'active_application_code'=>$application_code,
                                    'approval_date'=>formatDate($Date_of_registration),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('registered_premises', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_premises_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_premise_id'=>$reg_id));
							
							  DB::table('tra_premises')
                            ->where(array('id'=>$premise_id))
                            ->update(array('registered_id'=>$reg_id));
							
							
                        }
						
                    }
                   
                    $res = "Application Migration Successfully Application No: ".$Application_No.'</br>';
                   
                }
                else{
					
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$Application_No.'</br>';
                   
                }
                print_r($res);

                $records_migrated ++;
                
            }  
            $this->saveMigrationLogsData('initiatePremisesDataMapping',$records_migrated=0);
    
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


public function getAppdataMigrationRequests(Request $request)
    {
        $module_id = $request->input('module_id');
        $sub_module_id = $request->input('sub_module_id');
        $section_id = $request->input('section_id');
        try {

            $qry = DB::table('tra_datamigrationrequests as t1')
                ->leftJoin('sub_modules as t4', 't1.sub_module_id', '=', 't4.id')
                ->leftJoin('modules as t3', 't1.module_id', '=', 't3.id')
                ->leftJoin('par_sections as t5', 't1.section_id', '=', 't5.id')
                ->select('t1.*', 't3.name as module_name','t4.name as sub_module', 't5.name as section_name');
            if (validateIsNumeric($module_id)) {
                $qry->where('t1.module_id', $module_id);
            }
            if (validateIsNumeric($sub_module_id)) {
                $qry->where('t1.sub_module_id', $sub_module_id);
            }
			 if (validateIsNumeric($section_id)) {
                $qry->where('t1.section_id', $section_id);
            }
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => returnMessage($results)
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
        }
        return response()->json($res);
    }

    public function getProductAppdataMigrationDetails(Request $request)
    {
        $migration_request_id = $request->input('migration_request_id');
        $start = $request->input('start');
        $limit = $request->input('limit');
        $table_name = $request->input('table_name');
       
        try {
			if(validateIsNumeric($migration_request_id)){
					$module_id = getSingleRecordColValue('tra_datamigrationrequests', array('id' => $migration_request_id), 'module_id');
								if(validateIsNumeric($module_id)){
										$module_details = getTableData('modules', array('id' => $module_id));
										$table_name = $module_details->migration_table_name;
									}
				
			}
			
						
            $qry = DB::table($table_name.' as t1')
                ->select('t1.*');
            if (validateIsNumeric($migration_request_id)) {
                $qry->where('t1.migration_request_id', $migration_request_id);
            }
			 $count = $qry->count();
            if(validateIsNumeric($limit)){
				$results = $qry->skip($start)->take($limit)->get();
				
				
			}
			
            $results = $qry->get();
            $res = array(
                'success' => true,
                'results' => $results,'total' => $count,
                'message' => returnMessage($results)
            );
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');

        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), '');
        }
        return response()->json($res);
    }

    public function getAppDataMigrationsGridColumnsConfig(Request $req)
    {
            $def_id = $req->def_id;
            $table_name = $req->table_name;

            $param_joins = DB::table($table_name)->get();
            $labels = array();
            foreach ($param_joins as $param_join) {
                //$labels[] = $param_join->table_label;
            } 
			//$param_columns = DB::getSchemaBuilder()->getColumnListing($table_name);
           // $param_columns = array();
            //$results = $labels;
			  $db_columns = DB::select('SHOW COLUMNS FROM '. $table_name);
				$fields_list = array_map(function($db_column) {
					return $db_column->Field;
				}, $db_columns);
			
            $results = array_merge( $fields_list, $labels);
   
            foreach ($results as $key => $value) {
                    if($value == 'is_enabled'){
                        unset($results[$key]);
                    }
                    if($value == 'created_on'){
                        unset($results[$key]);
                    }
                    if($value == 'created_by'){
                        unset($results[$key]);
                    }
                    if($value == 'dola'){
                        unset($results[$key]);
                    }
                    if($value == 'altered_by'){
                        unset($results[$key]);
                    }
                    if($value == 'id'){
                        unset($results[$key]);
                    }
                    if($value == 'altered_on'){
                        unset($results[$key]);
                    }
            }
           
            $pure_array = array();
            foreach ($results as $result) {
                $pure_array[] = $result;
            }
            $res = array(
                        'success' => true,
                        'results' => $pure_array,
                        'table_name'=>$table_name,
                        'message' => 'All is well'
                    );
                return response()->json($res);
            }
            //downloadappdatamigrationuploadTemplate
    
public function downloadappdatamigrationuploadTemplate(Request $req){
				//download an excel form 
				$data = array();
						$module_id = $req->module_id;
						$val=date('Y').date('m').date('d').date('h').date('i').date('s');
						if(validateIsNumeric($module_id)){
							$module_details = getTableData('modules', array('id' => $module_id));
							$table_name = $module_details->migration_table_name;
						}
						else{
							
							$table_name = 'tra_productapps_datamigration';
						}
						
							 $db_columns = DB::select('SHOW COLUMNS FROM '. $table_name);
							
							$column_data = array();
							
							foreach($db_columns as $row){
								if (strpos($row->Field, '_id') === false){
									$column_data[strtoupper($row->Field)] = "";	
								
								}
								
							}
							
						  $data[] = $column_data;

					 
					   
						$data_array = json_decode(json_encode($data), true);

				$dataSpreadsheet = new Spreadsheet();
				$sheet = $dataSpreadsheet->getActiveSheet();
			  
				$cell=0;

			  if(isset($data_array[0])){
				  $header=array_keys($data_array[0]);
				  $length=count($header);
			   }
			   else{
				  $data_array=array();
				  $header=array();
				  $length=1;
				  $sheet->getCell('A2')->setValue("No data");
			   }

			   $size=count($data_array)+7;

			   $sheet->insertNewColumnBefore('A', 1);

			   $sheet->fromArray($header, null, "A1");

			   $sheet->fromArray( $data_array, null,  "A2");

			   for($i=8; $i <= $size; $i++){
				  $sheet->getCell('A'.$i)->setValue($i-7);
			   }
				$length = $length+1; //add one for the new column added 
				$letter=number_to_alpha($length,"");
					 
				$cellRange = 'A7:'.$letter.''.$size;
				$sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
				$sheet->getColumnDimension('A')->setAutoSize(true);

			   $writer = new Xlsx($dataSpreadsheet);
				 

			  $response =  new StreamedResponse(
				  function () use ($writer) {
					  $writer->save('php://output');
				  }
			  );
					$filename ='Data Migration Template';

			  $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			  $response->headers->set('Content-Disposition', 'attachment;filename='.$filename.'.xlsx');
			  $response->headers->set('Cache-Control','max-age=0');
			 return $response;

}
function funcMigrateDataExtraction($table_name,$data,$data_keys,$req,$section_id){

	$has_error =0; 
	$error_message =''; 
	foreach($data_keys as $row){
															$excel_key = $row->excel_key;
															$column_name = $row->column_name;
															
															if(isset($data[$excel_key])){
																if($excel_key != 'id'){
																		$upload_data[$column_name] = $data[$excel_key];
																
																}
															
															}
															else{
																$has_error =1;
																$error_message .= $column_name.',<br/>';
															}
																
													}
													if($error_message != ''){
														$upload_data['error_message'] = 'The following fields are missing in the excel '.$error_message;
													}
													
													$upload_data['has_error'] = $has_error;
													$upload_data['migration_request_id'] = $req->migration_request_id;
													$upload_data['module_id'] = $req->module_id;
													$upload_data['sub_module_id'] = $req->sub_module_id;
													$upload_data['section_id'] = $section_id;
							DB::table($table_name)
											->insert($upload_data);					
	return $upload_data;
	
}
public function saveappdatamigrationuploads(Request $req){
		try{
			//upload Options
			$data = array();
						$module_id = $req->module_id;
						$sub_module_id = $req->sub_module_id;
						$migration_request_id = $req->migration_request_id;
						$val=date('Y').date('m').date('d').date('h').date('i').date('s');
						if(validateIsNumeric($module_id)){
							$module_details = getTableData('modules', array('id' => $module_id));
							$table_name = $module_details->migration_table_name;
						}
						else{
							
							$table_name = 'tra_productapps_datamigration';
						}
						$section_id = getSingleRecordColValue('tra_datamigrationrequests', array('id' => $migration_request_id), 'section_id');
						
						$valid_extension = array("csv", "xlsx");
						$file = $req->file('uploaded_doc');
						$extension = $file->getClientOriginalExtension();
						if (in_array(strtolower($extension), $valid_extension)) {
							
							
						}
						$data_keys = DB::table('tra_apps_datamigrationkeys')
										->select()->where('module_id',$module_id)
										->get();
						
						$arr = array();
						$res = array();
						$trader_id = $req->trader_id;
						$file = $req->file('uploaded_doc');
						$application_code = $req->application_code;
						if ($req->hasFile('uploaded_doc')){
								$filePath = $req->file('uploaded_doc')->getRealPath();
								$filePath = $req->file('uploaded_doc')->getPathName();
								$excelData =Excel::toArray([],$req->file('uploaded_doc'));
								$excelData = $excelData[0];
								if(count($excelData) >0){
										$i = 0;	
										
										foreach ($excelData as $row) {
												$upload_data = array();
												$error_message = '';
												$has_error =0;
										
												$arr[] = $this->funcMigrateDataExtraction($table_name,$row,$data_keys,$req,$section_id);
												$i++;
												
										}
										
										if(!empty($arr)){
											
											$res = array('success'=>true,'message'=>'Application Data Mapped Successfully, you can proceed and synchonise the datasets.');
										}else{
											$res = array('success'=>false,'message'=>'No Records Found');
										}
								}
						}
						else{
							$res = array('success'=>false,'message'=>'Upload the Allowed Format or template');

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
public function synApplicationMigratedDataSets(Request $req){
	 $migration_request_id = $req->input('migration_request_id');
		 $module_id = getSingleRecordColValue('tra_datamigrationrequests', array('id' => $migration_request_id), 'module_id');
            $data_migration = $req->input('data_migration');
			$table_name = '';
            $data_migration = json_decode($data_migration);
            	if(validateIsNumeric($module_id)){
							$module_details = getTableData('modules', array('id' => $module_id));
							$table_name = $module_details->migration_table_name;
						}
					$res = array(
							'success' => false,
							'message' => 'Application Products details not found!!'
						);
            $user_id = $this->user_id;
            try {
                $insert_params = array();
                foreach ($data_migration as $app_detail) {
                    
                    $record_id = $app_detail->record_id;
                    if (validateIsNumeric($record_id)) {
                       
                        //loop
						switch ($module_id) {
						  case 1:
							$res= $this->productRegistrationDataMigration($table_name,$migration_request_id,$module_id,$record_id);
							break;
						  case 2:
							$res= $this->premisesLicensesDataMigration($table_name,$migration_request_id,$module_id,$record_id);
							break;
						  case 3:
							$res= $this->gmpInspectionsDataMigration($table_name,$migration_request_id,$module_id,$record_id);
							break;
						  case 7:
							$res= $this->clinicaltrialRegistrationDataMigration($table_name,$migration_request_id,$module_id,$record_id);
						  break;
						   case 14:
							$res= $this->PromotionAdvsertsemnesApplicationDataMigration($table_name,$migration_request_id,$module_id,$record_id);
						  break;
						  default:
							$res= array('success'=>false, 'message'=>'Modue Not Mapped');
						} 
                    }
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
function productRegistrationDataMigration($table_name,$migration_request_id,$module_id,$record_id){
	try{
        $res = 'Error occurred';
        $records_migrated =0;
        
        
        $records = DB::table($table_name)
					->where(array('id'=>$record_id, 'migration_request_id'=>$migration_request_id))
					->get();
		//$records = DB::
		
				foreach($records as $rec){
					
                    $reference_no = trim($rec->reference_no);
                                        
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    $sub_module_id = $rec->sub_module_id;
                    $module_id = $rec->module_id;
                                 
                    $process_id = 0;
                    $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                    if($process_data){
                        
                            $process_id = $process_data->id;
                      
                    }
                    
                    if($rec->DateSubmitted != '' && $rec->DateSubmitted != null && $rec->DateSubmitted != ' '){
                     
                        $RECEIVED = formatDate($rec->DateSubmitted);
                    }
                    else{


                        $RECEIVED = Carbon::now();
                    }
                  
                    $brand_name = $rec->brand_name;
                    $common_name = $rec->common_name;
                    $classification_name = $rec->classification_name;
                    $product_origin = $rec->product_origin;

                    $physical_description = $rec->physical_description;
                  
                    $product_strength = $rec->product_strength;
                    $ProductForm = $rec->ProductForm;
                    $product_form = $rec->product_form;
                    $storage_conditions = $rec->storage_conditions;
                    $product_category = $rec->product_category;
                    $product_sub_category = $rec->product_sub_category;
                    $distribution_category = $rec->distribution_category;
                    $intendedend_user = $rec->intendedend_user;
                    $intended_use = $rec->intended_use;
                    $route_of_administration = $rec->route_of_administration;
                    $method_of_use = $rec->method_of_use;
                    $contraindication = $rec->contraindication;
                    $indication = $rec->indication;
                    $gmdn_code = $rec->gmdn_code;
                    $gmdn_category = $rec->gmdn_category;
                    $gmdn_term = $rec->gmdn_term;
                    $warnings = $rec->warnings;
                    $instructions_of_use = $rec->instructions_of_use;
                    $shelf_life = $rec->shelf_life;
                    $shelflife_after_opening = $rec->shelflife_after_opening;

                    $ingredient = $rec->ingredient;
                    $specification_type = $rec->specification_type;
                    $strength = $rec->strength;
                    $proportion_unit = $rec->proportion_unit; 
                    $ingredients_si_unit = $rec->ingredients_si_unit;
                    $inclusion_reason = $rec->inclusion_reason;

                    $container = $rec->container;
                    $container_material = $rec->container_material;
                    $retail_packaging_size = $rec->retail_packaging_size;
                    $packaging_units = $rec->packaging_units;


                    $applicant_name = $rec->applicant_name;
                    $applicant_country = $rec->applicant_country;
                    $applicant_physical_address = $rec->applicant_physical_address;
                    $applicant_region = $rec->applicant_region;
                    $applicant_email = $rec->applicant_email;
                    
                    
                    $itr_name = $rec->itr_name;
                    $ltr_physical_address = $rec->ltr_physical_address;
                    $ltr_email = $rec->ltr_email;
                    $ltr_region = $rec->ltr_region;
                    $ltr_country = $rec->ltr_country;
                   
                    $manufacturing_site_name = $rec->manufacturing_site_name;
                    $man_country = $rec->man_country;
                    $manphysical_address = $rec->manphysical_address;
                    $man_region = $rec->man_region;
                    $man_email = $rec->man_email;
                    
                    $approval_decision = $rec->approval_decision;
                    $registration_status = $rec->registration_status;
                    $approval_date = $rec->approval_date;
                    $expiry_date = $rec->expiry_date;
                    $cerificate_no = $rec->cerificate_no;
                    
                    $product_origin_id = 1;
                    if($ProductOrigin == 'Imported'){
                        $product_origin_id = 2;
                    }
					
                    $classification_id = 2;
                    
                $app_record = DB::table('tra_product_applications')->where(array('reference_no'=>$reference_no))->first();
                $common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$section_id, 'name'=>$common_name, 'description'=>$common_name),array('name'=>$common_name),'Common Names');
                   
                $classification_id = $this->saveProductDataEntry('par_classifications',array('name'=>$classification_name,'section_id'=>$section_id, 'description'=>$classification_name),array('name'=>$classification_name),'Classification');
               
                if($reference_no == 0 || $reference_no == 'N/A (Applied by email)'  || $reference_no == 'Not specified'){

                    $app_details = (object)array('section_id'=>$section_id, 'classification_id'=>$classification_id);
                    $codes_array = $this->getProductApplicationReferenceCodes($app_details);
    
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
                    $reference_no = 'MGR-'.$tracking_details['tracking_no'];
                   
                }
               
                $dosage_form_id = $this->saveProductDataEntry('par_dosage_forms',array('name'=>$dosage_form,'section_id'=>$section_id, 'description'=>$dosage_form),array('name'=>$dosage_form),'DoSage form');
                $product_form_id = $this->saveProductDataEntry('par_product_forms',array('name'=>$product_form, 'section_id'=>$section_id,'description'=>$product_form),array('name'=>$product_form),'Product form');
                $storage_condition_id = $this->saveProductDataEntry('par_storage_conditions',array('name'=>$storage_conditions, 'section_id'=>$section_id,'description'=>$storage_conditions),array('name'=>$storage_conditions),'Product form');
				
                $product_category_id = $this->saveProductDataEntry('par_product_categories',array('name'=>$product_category, 'section_id'=>$section_id,'description'=>$product_category),array('name'=>$product_category),'Product form');
				
                $product_subcategory_id = $this->saveProductDataEntry('par_subproduct_categories',array('name'=>$product_sub_category, 'product_category_id'=>$product_category_id, 'section_id'=>$section_id,'description'=>$product_sub_category),array('name'=>$product_sub_category),'Product form');

                $distribution_category_id = $this->saveProductDataEntry('par_distribution_categories',array('name'=>$distribution_category, 'section_id'=>$section_id,'description'=>$distribution_category),array('name'=>$distribution_category),'Product form');

                $intended_enduser_id = $this->saveProductDataEntry('par_intended_enduser',array('name'=>$IntendedEndUser, 'section_id'=>$section_id,'description'=>$IntendedEndUser),array('name'=>$IntendedEndUser),'Product form');
				
                $route_of_administration_id = $this->saveProductDataEntry('par_route_of_administration',array('name'=>$route_of_administration, 'section_id'=>$section_id,'description'=>$route_of_administration),array('name'=>$route_of_administration),'Product form');

                $method_ofuse_id = $this->saveProductDataEntry('par_methodof_use',array('name'=>$method_of_use, 'section_id'=>$section_id,'description'=>$method_of_use),array('name'=>$method_of_use),'Product form');
                
                
                $product_information = array('product_origin_id'=>$product_origin_id,
                                             'common_name_id'=>$common_name_id,
                                             'product_form_id'=>$product_form_id,
                                             'prodclass_category_id'=>$prodclass_category_id,
                                             'classification_id'=>$classification_id,
                                             'brand_name'=>$brand_name,
                                             'physical_description'=>$physical_description,
                                             'dosage_form_id'=>$dosage_form_id,
                                             'product_strength'=>$product_strength,
                                             'storage_condition_id'=>$storage_condition_id,
                                             'product_category_id'=>$product_category_id,
                                             'product_subcategory_id'=>$product_subcategory_id,
                                             'distribution_category_id'=>$distribution_category_id,  'intended_enduser_id'=>$intended_enduser_id,
                                             'intended_use'=>$intended_use,
                                             'route_of_administration_id'=>$route_of_administration_id,
                                             'method_ofuse_id'=>$method_ofuse_id,
                                             'contraindication'=>$contraindication,
                                             'indication'=>$indication,
                                             'gmdn_category'=>$gmdn_category,
                                             'gmdn_term'=>$gmdn_term,
                                             'warnings'=>$warnings,
                                             'shelf_lifeafter_opening'=>$shelflife_after_opening,
                                             'shelf_life'=>$shelf_life,
                                             'instructions_of_use'=>$instructions_of_use,
                                             'section_id'=>$section_id,
                                          
                                         );
                                         $ingredient_id = $this->saveProductDataEntry('par_ingredients_details',array('name'=>$ingredient, 'section_id'=>$section_id,'description'=>$ingredient),array('name'=>$ingredient),'Product form');

                                         $inclusion_reason_id = $this->saveProductDataEntry('par_inclusions_reasons',array('name'=>$inclusion_reason, 'section_id'=>$section_id,'description'=>$inclusion_reason),array('name'=>$inclusion_reason),'Product form');

                                         $specification_type_id = $this->saveProductDataEntry('par_specification_types',array('name'=>$specification_type,'section_id'=>$section_id,  'description'=>$specification_type),array('name'=>$specification_type),'Ingredietns details');

                                         $ingredientssi_unit_id = $this->saveProductDataEntry('par_si_units',array('name'=>$ingredients_si_unit, 'section_id'=>$section_id,'description'=>$ingredients_si_unit),array('name'=>$ingredients_si_unit),'Product form');

                                        
                                        
                                                     //packagind details 
                                        $container_id = $this->saveProductDataEntry('par_containers',array('name'=>$container, 'section_id'=>$section_id,'description'=>$container),array('name'=>$container),'Product form');
                                        $container_material_id = $this->saveProductDataEntry('par_containers',array('name'=>$container_material, 'section_id'=>$section_id,'description'=>$container_material),array('name'=>$container_material),'Product form');

                                        $packaging_units_id = $this->saveProductDataEntry('par_packaging_units',array('name'=>$packaging_units, 'section_id'=>$section_id,'description'=>$packaging_units),array('name'=>$packaging_units),'Product form');
                                        
                        if(!$app_record){
                   
                
                                             $product_information['created_by'] = '0';
                                             $product_information['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_product_information', $product_information, 'Migration');
                                             
                                             if($resp['success']){
                                                 $product_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
                    //product_ingreidents
                   
                    $ingredients_data = array('product_id'=>$product_id, 
                                        'ingredient_id'=>$ingredient_id,
                                        'specification_type_id'=>$specification_type_id,
                                        'strength'=>$Strength,
                                        'proportion'=>$Proportionunit,
                                        'ingredientssi_unit_id'=>$ingredientssi_unit_id,
                                         'inclusion_reason_id'=>$inclusion_reason_id);

                    $packaging_data = array('container_id'=>$container_id, 
                                                'container_material_id'=>$container_material_id,
                                                'retail_packaging_size'=>$retail_packaging_size,
                                                'packaging_units_id'=>$packaging_units_id,
                                                'product_id'=>$product_id
                                             );  
                    DB::table('tra_product_ingredients')->insert($ingredients_data);
                    DB::table('tra_product_packaging')->insert($packaging_data);
                    //save product manufacturing site details 
                  
                   $mancountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$man_country, 'description'=>$man_country),array('name'=>$man_country),'Country');
    
                   $manregion_id = $this->saveProductDataEntry('par_regions',array('name'=>$man_region, 'description'=>$man_region, 'country_id'=>$mancountry_id),array('name'=>$man_region),'Region ');

                    $manufacturer_data = array('name'=>$manufacturing_site_name, 
                                               // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$manphysical_address, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$man_email, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$manufacturer_data,array('name'=>$manufacturing_site_name, 'country_id'=>$mancountry_id),'Manufacturer Id');       

                    $manufacturersite_data = array('name'=>$manufacturing_site_name, 
                                                // 'telephone_no'=>$FPP_Telephone_NoOptional, 
                                                'physical_address'=>$manphysical_address, 
                                            //    'postal_address'=>$MANUFACTURESMAILING_ADDRESS, 
                                                'email_address'=>$man_email, 
                                                'region_id'=>$manregion_id, 
                                                'country_id'=>$mancountry_id,
                                                'manufacturer_id'=>$manufacturer_id
                                            );
                    $man_site_id = $this->saveProductDataEntry('par_man_sites',$manufacturersite_data,array('name'=>$manufacturing_site_name, 'manufacturer_id'=>$manufacturer_id),'Manufacturer Id');            
                    
                    $product_manufacturer = array('product_id'=>$product_id, 
                                                'manufacturer_id'=>$manufacturer_id, 
                                                'man_site_id'=>$man_site_id, 
                                                'manufacturer_role_id'=>1,
                                                'manufacturer_type_id'=>1
                                            );
                    DB::table('tra_product_manufacturers')->insert($product_manufacturer);
                    //save product applicant_details 
                   
                   $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$applicant_country, 'description'=>$applicant_country),array('name'=>$applicant_country),'Country');
					
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$applicant_region, 'description'=>$applicant_region, 'country_id'=>$country_id),array('name'=>$applicant_region),'Country ');
    
                   
                   $data = (object)array('name'=>$applicant_name, 
                                'tpin_no'=>0, 
                                'contact_person'=>$applicant_name,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$applicant_physical_address, 
                                'postal_address'=>$applicant_physical_address, 
                                'telephone_no'=>'', 
                                'email'=>$applicant_email, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     //save product local representative 
                     $local_agent_id = 0;
                     if($itr_name != ''){
                        $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ltr_country, 'description'=>$ltr_country),array('name'=>$ltr_country),'Country ');
    
                        $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ltr_region, 'description'=>$ltr_region, 'country_id'=>$country_id),array('name'=>$ltr_region),'Country ');

                        $data = (object)array('name'=>$itr_name, 
                                     'tpin_no'=>0, 
                                     'contact_person'=>$itr_name,
                                     'country_id'=>$country_id, 
                                     'region_id'=>$region_id, 
                                     'physical_address'=> $ltr_physical_address,
                                     'postal_address'=>$ltr_physical_address, 
                                    // 'telephone_no'=>$REPRESENTATIVE_PHONE, 
                                     'email'=>$ltr_email, 
                                     'created_by'=>'Migration',
                                     'created_on'=>Carbon::now(),
                                     'status_id'=>1
                                 );
                         $local_agent_id = $this->saveTraderInformationDetails($data);
    
                     }
                    
                     $application_status_id = 5;
                     if($cerificate_no != '' && $cerificate_no != 'nan' && $cerificate_no != 'N/A'){
                        $application_status_id = 6;
                     }
                     
                    //save product application details  RefNumber
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(7, 'tra_product_applications');
                     $product_data = array('product_type_id'=>$product_origin_id, 
								 'application_code'=>$application_code, 
								 'reference_no'=>$reference_no, 
								 'view_id'=>$view_id, 
								 'applicant_id'=>$applicant_id, 
								 'local_agent_id'=>$local_agent_id, 
								 'sub_module_id'=>$sub_module_id, 
								 'assessmentprocedure_type_id'=>1, 
								 'section_id'=>$section_id, 
								 'product_id'=>$product_id, 
								 'fasttrack_option_id'=>2, 
								 'process_id'=>$process_id, 
								 'module_id'=>$module_id, 
								 'prodclass_category_id'=>$prodclass_category_id, 
								 'date_added'=>formatDate($RECEIVED), 
								 'submission_date'=>formatDate($RECEIVED), 
								 'application_status_id'=>$application_status_id, 
								 'refno_generated'=>1, 
								 'created_on'=>Carbon::now(), 
								 'created_by'=>'Migration', 
								 
                    );
                    $resp =  insertRecord('tra_product_applications', $product_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($cerificate_no != '' && $cerificate_no != 'nan'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$cerificate_no))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
										'application_id'=>$application_id,
										'decision_id'=>1,
										'module_id'=>$module_id,
										'certificate_no'=>$cerificate_no,
										'appvalidity_status_id'=>2,
										'appregistration_status_id'=>2,
										'comment'=>'Migration Approval Details',
										'approval_date'=>formatDate($approval_date),
										'certificate_issue_date'=>formatDate($approval_date),
										'expiry_date'=>formatDate($expiry_date),
										'approved_by'=>'Migration Data',
										'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('tra_registered_products')->where(array('registration_no'=>$cerificate_no))->first();
                        if(!$app_record){
                            $regdata = array('tra_product_id'=>$product_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($approval_date),
                                    'registration_no'=>$cerificate_no,
                                    'reg_applicant_id'=>$applicant_id,
                                    'reg_local_agent_id'=>$local_agent_id,
                                    'active_app_referenceno'=>$reference_no,
                                    'active_application_code'=>$application_code,
                                    'expiry_date'=>formatDate($expiry_date),
                                    'approval_date'=>formatDate($approval_date),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_product_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_product_id'=>$reg_id));
                        }else{
							
							//submission
							$this->syncmappingProductRegistrationSubmission($application_code);
							
							
						}
                    }
                    else{
                        $regdata = array('tra_product_id'=>$product_id,
                                'validity_status_id'=>1,
                                'registration_status_id'=>1,
                                'reg_applicant_id'=>$applicant_id,
                                'reg_local_agent_id'=>$local_agent_id,
                                'active_app_referenceno'=>$reference_no,
                                'active_application_code'=>$application_code,
                                'created_on'=>Carbon::now()
                        );
                        $resp =  insertRecord('tra_registered_products', $regdata, 'Migration');
                        if($resp['success']){
                            $reg_id = $resp['record_id'];
                        }
                        else{
                                print_r($resp);
                                exit();
                        }
                        DB::table('tra_product_applications')
                        ->where(array('application_code'=>$application_code))
                        ->update(array('reg_product_id'=>$reg_id));
    
                    }
                    //save product register
                    $res = "Application Migration Successfully Application No: ".$reference_no.'</br>';
                }
                else{
					
                    //update the records 
                    $product_id = $app_record->product_id;
                    $applicant_id = $app_record->applicant_id;
                    $application_code = $app_record->application_code;
                   
                    $section_id = $rec->section_id;
                    $prodclass_category_id = $rec->prodclass_category_id;
                    
                    $classification_id = 2;
                    
                   
                    DB::table('tra_product_information')->where(array('id'=>$product_id))->update($product_information);

                  
                    $country_id = $this->saveProductDataEntry('par_countries',array('name'=>$applicant_country, 'description'=>$applicant_country),array('name'=>$applicant_country),'Country');
    
                   $region_id = $this->saveProductDataEntry('par_regions',array('name'=>$applicant_region, 'description'=>$applicant_region, 'country_id'=>$country_id),array('name'=>$applicant_region),'Country ');
                                             
                   $trader_data = array('name'=>$applicant_name, 
                                'tpin_no'=>0, 
                                'contact_person'=>$applicant_name,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$applicant_physical_address, 
                                'postal_address'=>$applicant_physical_address, 
                                'telephone_no'=>'', 
                                'email'=>$applicant_email, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    
                             DB::table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
                             DB::connection('portal_db')->table('wb_trader_account')->where(array('id'=>$applicant_id))->update($trader_data);
  
                    $res = "Application Already Migrated and Updated Successfully Application No: ".$reference_no.'</br>';
                 
                }
                print_r($res);

                $records_migrated ++;
                
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
    return $res;
	
}

public function syncmappingProductRegistrationSubmission($application_code){
        try{
			$section_id = $req->section_id;
			
            $records_migrated =0;
                $records = DB::table('tra_product_applications as t1')
                                ->join('tra_product_information as t2','t1.product_id', 't2.id')
								->leftJoin('tra_approval_recommendations as t3', 't1.application_code', 't3.application_code')
                                ->select('t1.*', 't2.*', 't1.id as application_id')
								->where('t1.application_code',$application_code)
								->whereNull('t3.id')
                                ->get();
                if($records){
                    foreach($records as $rec){
                        if($rec->section_id == 3){
                            $destination_process = 191;
                        }else if($rec->section_id == 4){
                            $destination_process = 204;
                        }
						else if($rec->section_id == 1){
                            $destination_process = 217;
                        }
						else if($rec->section_id == 2){
                            $destination_process = 178;
                        }else if($rec->section_id == 7){
                            $destination_process = 1013;
                        }else if($rec->section_id == 8){
                            $destination_process = 1038;
                        }else if($rec->section_id == 9){
                            $destination_process = 988;
                        }
                        else{
                            $destination_process = 5;
                        }
                        $sub_rec = DB::table('tra_submissions')->where(array('application_code'=>$rec->application_code,'current_stage'=>$destination_process,'isDone'=>0))->first();

                        if(!$sub_rec){
                            $submission_data = array('prodclass_category_id'=>$rec->prodclass_category_id,
                                'application_id'=>$rec->application_id,
                                'applicant_id'=>$rec->applicant_id,
                                'application_code'=>$rec->application_code,
                                'reference_no'=>$rec->reference_no,
                                'tracking_no'=>$rec->tracking_no,
                                'view_id'=>$rec->view_id,
                                'process_id'=>$rec->process_id,
                                'previous_stage'=>$destination_process,
                                'current_stage'=>$destination_process,
                                'module_id'=>$rec->module_id,
                                'sub_module_id'=>$rec->sub_module_id,
                                'directive_id'=>1,
                                'application_status_id'=>$rec->application_status_id,
                                'section_id'=>$rec->section_id,
                                'urgency'=>1,
                                'remarks'=>'Migrated Renewal Applications',
                                'isRead'=>0,
                                'isDone'=>0,
                                'isComplete'=>0,
                                'date_received'=>Carbon::now(),
                                'created_on'=>Carbon::now(),
                                'created_by'=>'Migrated By'
                            
                        );
        
                        DB::table('tra_submissions')->insert($submission_data);
                        $res = 'Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no;
                        print_r('Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no);

                        } else{

                      //print_r($rec);
                           $res = 'Applications already been mapped on the submission table successsfully Application No'.$rec->reference_no;

                            $records_migrated++;
                    }  
                    }


                }
                else{

                    $res = "No application found";
                }
                $this->saveMigrationLogsData('initiatemappingProductRegistrationSubmission',$records_migrated);
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
        return $res;


}
function premisesLicensesDataMigration($table_name,$migration_request_id,$module_id,$record_id){
	
	$month = date('m', strtotime('2021-12-30'));
	
    try{
        $records_migrated =0;
        $records = DB::table($table_name)->where('id',$record_id)->get();
        foreach($records as $rec){
			
            $section_id = $rec->section_id;
            $sub_module_id = $rec->sub_module_id;
			
            $Application_No = $rec->operational_license_ref_no;
            $premise_reg_no = $rec->premise_registration_certificate_number;
            $Premise_reg_no = $rec->premise_registration_certificate_number;
            $permit_no = $rec->operational_license_ref_no;
            $Premise_registration_certificate_number = $rec->premise_registration_certificate_number;
            
                    //all the columns 
                    $Premises_name = $rec->name_of_institution;
                    $RECEIVED = Carbon::now();//$rec->RECEIVED; results
                   
                    $Region_name = $rec->province;
                    $District_name = $rec->district;
                    $Sector = $rec->sector;
                    $Cell_Centre = $rec->cell_centre;
                    $postal_address = '';//$rec->Postal_address;
					
                    $Email = $rec->director_email;
                    $physical_address = $rec->cell_centre;
                    $Date_of_registration = $rec->date_of_issuance;
                    $expiry_date = $rec->expiry_date;
					
                    //$Organization_Type = $rec->Organization_Type;
                    //$Secondary_Organization_Type = $rec->Secondary_Organization_Type;
                    $Sector = $rec->sector;
                    $Cell_Centre = $rec->cell_centre;
                    $Director_Name = $rec->director_name;
                    $Director_Position = $rec->director_position;
                    $Director_Email = $rec->director_email;
                    $Director_Phone_Number = $rec->director_phone_number;
					
                    $company_registration_no =$rec->company_code;
                    $Pharmacist_Name = $rec->name_of_responsible_technician;
                    $Premise_Category = $rec->premise_category;
					
                    $Product_category = $rec->product_category;
                    $Products_type = $rec->products_type;
					
			//	$premise_type_id = 7;
					//$business_type_detail_id = 5;
					//print_r('test');
					//exit();
					
                     $country_id = 126;
                    $region_id = getSingleRecordColValue('par_regions', array('name'=>$Region_name), 'id');
                     $district_id = getSingleRecordColValue('par_districts', array('name'=>$District_name), 'id');
                    $sector_id = getSingleRecordColValue('par_sectors', array('name'=>$Sector), 'id');
                    $cell_id = getSingleRecordColValue('par_cells', array('name'=>$Cell_Centre), 'id');
                    
                    $premise_type_id = getSingleRecordColValue('par_business_types', array('name'=>$Premise_Category), 'id');
					
                    $product_category_id = $this->getLikeSingleRecordColValue('par_product_categories', $Product_category);
                    $product_subcategory_id = $this->getLikeSingleRecordColValue('par_subproduct_categories', $Products_type);
					
                    $product_details = $Product_category .' '.$Products_type;
					 $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array('section_id'=>$section_id, 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
							$process_id = $process_data->id;
                       
                     }
                $app_record = DB::table('tra_premises_applications as t1')
											->join('tra_premises as t2', 't1.premise_id', 't2.id')
											->select('t1.*')
											->where(array('t2.name'=>$Premises_name))
											->first();
									$premapp_record = 	$app_record;	
                if(!$app_record){
                    //generate trader not
					$codes_array = array('section_code'=>'F');
                    $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                    if ($tracking_details['success'] == false) {
                        return \response()->json($tracking_details);
                    }
					
							$ReferenceNo = 'MGR/'.$tracking_details['tracking_no'];
                   
					
                    $premises_infor = array('premise_type_id'=>$premise_type_id,
                                                 'name'=>$Premises_name,
                                                 'premise_reg_no'=>$premise_reg_no,
                                                 'country_id'=>$country_id,
                                                 'region_id'=>$region_id,
                                                 'city_id'=>$region_id,
                                                 'section_id'=>$section_id,
                                                 'physical_address'=>$physical_address,
                                                 'postal_address'=>$postal_address,
                                                 'street'=>$physical_address,
                                                 'managing_director_email'=>$Director_Email,
                                                 'managing_director'=>$Director_Name,
                                                 'managing_director_telepone'=>$Director_Phone_Number,
                                                 'sector_id'=>$sector_id,
                                                 'cell_id'=>$cell_id,
                                                 'company_registration_no'=>$company_registration_no
                                                 
                                             );
                                           
                                             $premises_infor['created_by'] = '0';
                                             $premises_infor['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises', $premises_infor, 'Migration');
                                             
                                             if($resp['success']){
                                                 $premise_id = $resp['record_id'];
                                             }
                                             else{
                                                    print_r($resp);
                                                    exit();
                                             }
								$premises_otherdetails = array('business_type_id'=>$premise_type_id,
                                                 'product_category_id'=>$product_category_id,
                                                 'product_subcategory_id'=>$product_subcategory_id,
                                                 'product_details'=>$product_details,
                                                 'premise_id'=>$premise_id,
												 );
                     $premises_otherdetails['created_by'] = '0';
                                             $premises_otherdetails['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_otherdetails', $premises_otherdetails, 'Migration');
                                             
                   $personnel_details = array('personnel_name'=>$Pharmacist_Name,
                                                 'premise_id'=>$premise_id,
												 );
                     $personnel_details['created_by'] = '0';
                                             $personnel_details['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_personnel', $personnel_details, 'Migration');
                                             
                   $data = (object)array('name'=>$Director_Name, 
                                'tpin_no'=>0, 
                                'contact_person'=>$Director_Name,
                                'country_id'=>$country_id, 
                                'region_id'=>$region_id, 
                                'physical_address'=>$physical_address, 
                                'postal_address'=>$physical_address, 
                                'telephone_no'=>$Director_Phone_Number, 
                                'email'=>$Director_Email, 
                                'created_by'=>'Migration',
                                'created_on'=>Carbon::now(),
                                'status_id'=>1
                            );
                    $applicant_id = $this->saveTraderInformationDetails($data);
                     
                     $application_status_id = 5;
                     if($premise_reg_no != ''){
                        $application_status_id = 6;
                     }
                    
                    //save product application details 
                   
                    $view_id = generateApplicationViewID();
                     $application_code = $application_code = generateApplicationCode(1, 'tra_premises_applications');
                     $premises_app = array(
							 'application_code'=>$application_code, 
							 'reference_no'=>$ReferenceNo, 
							 'view_id'=>$view_id, 
							
                                                 'section_id'=>$section_id,
							 'sub_module_id'=>$sub_module_id, 
							 'section_id'=>$section_id, 
							 'premise_id'=>$premise_id,  
							 'process_id'=>$process_id, 
							 'module_id'=>$module_id, 
							 'date_added'=>formatDate($RECEIVED), 
							 'submission_date'=>formatDate($RECEIVED), 
							 'application_status_id'=>$application_status_id, 
							 'refno_generated'=>1, 
							 'created_on'=>Carbon::now(), 
							 'created_by'=>'Migration', 
							 
                    );
                    $resp =  insertRecord('tra_premises_applications', $premises_app, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    //save product approval details 
                    if($permit_no != ''){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('permit_no'=>$permit_no))->first();

                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                            'application_id'=>$application_id,
                            'decision_id'=>1,
                            'module_id'=>$module_id,
                            'permit_no'=>$permit_no,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Date_of_registration),
                            'certificate_issue_date'=>formatDate($Date_of_registration),
                            'expiry_date'=>formatDate($expiry_date),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('registered_premises')->where(array('registration_no'=>$permit_no))->first();
                        if(!$app_record){
                            $regdata = array('tra_premise_id'=>$premise_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Date_of_registration),
                                    'registration_no'=>$premise_reg_no,
                                    'active_app_referenceno'=>$Application_No,
                                    'active_application_code'=>$application_code,
                                    'approval_date'=>formatDate($Date_of_registration),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('registered_premises', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_premises_applications')
                            ->where(array('application_code'=>$application_code))
                            ->update(array('reg_premise_id'=>$reg_id));
							
							  DB::table('tra_premises')
                            ->where(array('id'=>$premise_id))
                            ->update(array('registered_id'=>$reg_id));
							
							
                        }
						
                    }else{
						$this->syncmappingPremisesRegistrationSubmission($application_code);
						
						
						
					}
                   
                    $res =array('success'=>true,'message'=> "Application Migration Successfully Application No: ".$Application_No.'</br>');
                }
                else{
					/*
					$premise_id = $app_record->premise_id;
					
					$premises_otherdetails = array('business_type_id'=>$premise_type_id,
                                                 'product_category_id'=>$product_category_id,
                                                 'product_subcategory_id'=>$product_subcategory_id,
                                                 'product_details'=>$product_details,
                                                 'premise_id'=>$premise_id,
												 );
                     $premises_otherdetails['created_by'] = '0';
                                             $premises_otherdetails['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_otherdetails', $premises_otherdetails, 'Migration');
                                             
                   $personnel_details = array('personnel_name'=>$Pharmacist_Name,
                                                 'premise_id'=>$premise_id,
												 );
                     $personnel_details['created_by'] = '0';
                                             $personnel_details['created_on'] = Carbon::now();
                                             $resp =  insertRecord('tra_premises_personnel', $personnel_details, 'Migration');
                      */			
					if($Premise_registration_certificate_number != ''){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('permit_no'=>$Premise_registration_certificate_number))->first();

                        if(!$app_record){
                            $approval_data = array('application_code'=>$premapp_record->application_code,
                            'application_id'=>$premapp_record->id,
                            'decision_id'=>1,
                            'module_id'=>$premapp_record->module_id,
                            'permit_no'=>$Premise_registration_certificate_number,
                            'appvalidity_status_id'=>2,
                            'appregistration_status_id'=>2,
                            'comment'=>'Migration Approval Details',
                            'approval_date'=>formatDate($Date_of_registration),
                            'certificate_issue_date'=>formatDate($Date_of_registration),
                            'expiry_date'=>formatDate($expiry_date),
                            'approved_by'=>'Migration Data',
                            'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }


                        }
                        
                        //register
                        $app_record = DB::table('registered_premises')->where(array('registration_no'=>$Premise_registration_certificate_number))->first();
                        if(!$app_record){
                            $regdata = array('tra_premise_id'=>$premapp_record->premise_id,
                                    'validity_status_id'=>2,
                                    'registration_status_id'=>2,
                                    'registration_date'=>formatDate($Date_of_registration),
                                    'registration_no'=>$Premise_registration_certificate_number,
                                    'active_app_referenceno'=>$premapp_record->reference_no,
                                    'active_application_code'=>$premapp_record->application_code,
                                    'approval_date'=>formatDate($Date_of_registration),
                                    'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('registered_premises', $regdata, 'Migration');
                            if($resp['success']){
                                $reg_id = $resp['record_id'];
                            }
                            else{
                                    print_r($resp);
                                    exit();
                            }
                            DB::table('tra_premises_applications')
                            ->where(array('application_code'=>$premapp_record->application_code))
                            ->update(array('reg_premise_id'=>$reg_id));
							
							  DB::table('tra_premises')
                            ->where(array('id'=>$premapp_record->premise_id))
                            ->update(array('registered_id'=>$reg_id));
							
							
                        }
						
                    }	
			
                       $res =array('success'=>true,'message'=> "Application Already Migrated and Updated Successfully Application No: ".$Application_No.'</br>');
                
                }
				
                $records_migrated ++;
                
            }  
            $this->saveMigrationLogsData('initiatePremisesDataMapping',$records_migrated=0);
    
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
   return $res;
	
	
}
function syncmappingPremisesRegistrationSubmission($application_code){
	
        try{
			$section_id = $req->section_id;
			
            $records_migrated =0;
                $records = DB::table('tra_premises_applications as t1')
								->leftJoin('tra_approval_recommendations as t3', 't1.application_code', 't3.application_code')
                                ->select('t1.*',  't1.id as application_id')
								->whereNull('t3.id')
								->where('t1.application_code',$application_code)
                                ->get();
                if($records){
                    foreach($records as $rec){
                         $destination_process = 8;
                        
                        $sub_rec = DB::table('tra_submissions')->where(array('application_code'=>$rec->application_code,'current_stage'=>$destination_process,'isDone'=>0))->first();


                        if(!$sub_rec){
                            $submission_data = array(
                                'application_id'=>$rec->application_id,
                                'applicant_id'=>$rec->applicant_id,
                                'application_code'=>$rec->application_code,
                                'reference_no'=>$rec->reference_no,
                                'tracking_no'=>$rec->tracking_no,
                                'view_id'=>$rec->view_id,
                                'process_id'=>$rec->process_id,
                                'previous_stage'=>$destination_process,
                                'current_stage'=>$destination_process,
                                'module_id'=>$rec->module_id,
                                'sub_module_id'=>$rec->sub_module_id,
                                'directive_id'=>1,
                                'application_status_id'=>$rec->application_status_id,
                                'section_id'=>$rec->section_id,
                                'urgency'=>1,
                                'remarks'=>'Migrated Renewal Applications',
                                'isRead'=>0,
                                'isDone'=>0,
                                'isComplete'=>0,
                                'date_received'=>Carbon::now(),
                                'created_on'=>Carbon::now(),
                                'created_by'=>'Migrated By'
                            
                        );
        
                        DB::table('tra_submissions')->insert($submission_data);
                        $message = 'Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no;
                      $res = array(
										'success' => false,
										'message' => $message
									);
                        } else{

                      //print_r($rec);
                           $message = 'Applications already been mapped on the submission table successsfully Application No'.$rec->reference_no;
							$res = array(
										'success' => false,
										'message' => $message
									);
                            $records_migrated++;
                    }  
                    }


                }
                else{

                    $res = "No application found";
                }
                $this->saveMigrationLogsData('initiatemappingProductRegistrationSubmission',$records_migrated);
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
        return $res;
}
public function deleteApplicationMigratedDataSets (Request $req){
		 $migration_request_id = $req->input('migration_request_id');
		 $module_id = $req->input('module_id');
            $data_migration = $req->input('data_migration');
			$table_name = '';
            $data_migration = json_decode($data_migration);
            	if(validateIsNumeric($module_id)){
							$module_details = getTableData('modules', array('id' => $module_id));
							$table_name = $module_details->migration_table_name;
						}
					$res = array(
							'success' => false,
							'message' => 'Application Products details not found!!'
						);
            $user_id = $this->user_id;
            try {
                $insert_params = array();
                foreach ($data_migration as $app_detail) {
                    
                    $record_id = $app_detail->record_id;
                    if (validateIsNumeric($record_id)) {
                        $where = array(
                            'id' => $record_id
                        );
                        
                        $prev_data = getPreviousRecords($table_name, $where);
 $prev_data = $prev_data['results'];
                        $res = deleteRecord($table_name, $prev_data, $where, $user_id);
                    }
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
public function getParameterFormColumnsConfig(Request $req)
   {
    $module_id = $req->module_id;
	if(validateIsNumeric($module_id)){
							$module_details = getTableData('modules', array('id' => $module_id));
							$table_name = $module_details->migration_table_name;
	}
   
    $labels = array();
    $child = true;
    $param_column_name=''; 
    $link_column_name = '';
   
    $colums = DB::select('SHOW COLUMNS FROM '.$table_name);
    $fields = array();
    foreach ($colums as $column) {
        if($column->Null == 'YES'){
            $fields[] = ['field'=>$column->Field,'null'=>true];
        }else{
            $fields[] = ['field'=>$column->Field,'null'=>false];
        }
        
    }
    //dd($fields); col_diff
    $param_columns = $fields;//DB::getSchemaBuilder()->getColumnListing($param->table_name);
    

    foreach ($param_columns as $key => $value) {
       if($value['field'] == 'is_enabled'){
            unset($param_columns[$key]);
       }
       if($value['field'] == 'created_on'){
            unset($param_columns[$key]);
       }
       if($value['field'] == 'created_by'){
            unset($param_columns[$key]);
       }
       if($value['field'] == 'dola'){
            unset($param_columns[$key]);
       }
       if($value['field'] == 'altered_by'){
            unset($param_columns[$key]);
       }
       if($value['field'] == 'id'){
            unset($param_columns[$key]);
       }
       if($value['field'] == 'altered_on'){
            unset($param_columns[$key]);
       }
      
   }
  

    $pure_array = array();
    foreach ($param_columns as $result) {
        $pure_array[] = $result;
    }
    $res = array(
                'success' => true,
                'main_fields' => $pure_array,
                'table_name'=>$table_name,
                'message' => 'All is well'
            );
      
         return response()->json($res);
   }
   function clinicaltrialRegistrationDataMigration($table_name,$migration_request_id,$module_id,$record_id){
	   
    try{
        $records = DB::table($table_name)->where('id',$record_id)->get();
        if($records){
                foreach($records as $rec){
                    //clinical trial details 
                     $id = $rec->Id;
                     $Date_of_Clinical_Trial_Application = $rec->referenceno;
                    $sub_module_id = 10;
                //     $Date_of_Issue_of_Clinical_Trial_Certificate = $rec->Date_of_Issue_of_Clinical_Trial_Certificate;
                  //   $Expiry_Date = $rec->Expiry_Date;

                     $Clinical_Trial_Title = $rec->publictitle;
                     $ReferenceNo = $rec->referenceno;
                     $process_id = 0;
                     $process_data = getSingleRecord('wf_tfdaprocesses', array( 'sub_module_id'=>$sub_module_id));
                     if($process_data){
                         
                         $process_id = $process_data->id;
                       
                     }
                     if($ReferenceNo == ''){

                        $codes_array = array('section_code'=>'CTR', 'zone_code'=>00);
        
                        $tracking_details = generateApplicationTrackingNumber($sub_module_id, 1, $codes_array, $process_id, '', '');
                        if ($tracking_details['success'] == false) {
                            return \response()->json($tracking_details);
                        }
                        $ReferenceNo = 'MGR-'.$tracking_details['tracking_no'];
                       
                    }
                    
                    $ProtocolNo = $rec->protocolno;
                    $VersionNo = $rec->versionno;
                    $DateOfProtocol = $rec->dateofprotocol;

                    $Study_Design = $rec->trialdesign;
                  //  $Study_Phase = $rec->Study_Phase;
                    $PurposeOfTrial = $rec->purposeoftrial;
                    $PublicationUrl = $rec->publicationurl;
                    $PublicTitle = $rec->publictitle;
                    $ClinicalTrialDescription = $rec->clinicaltrialdescription;
                    $PhaseId = $rec->phaseid;
                    $IP_ProdSection = $rec->ip_prodsection;

                    
                    $StudyStartDate = $rec->studystartdate;
                    $StudyEndDate = $rec->studyenddate;
                    $EthicsClearanceNo = $rec->ethicsclearanceno;

                    
                    $StudyDuration = $rec->studyduration;
                   
                   
                    $InvestigationProductSection = $rec->investigationproductsection;
                    $InvestigationProductBrand = $rec->investigationproductbrand;
                    $InvestigatorProductClassification = $rec->investigatorproductclassification;
                    $DosageForm = $rec->dosageform;
                    $CommonName = $rec->commonname;
                    $ActiveIngredients = $rec->activeingredients;
                    //manufactrer
                    $ManufacturerName = $rec->manufacturername;
                    $ManufacturerPhysicalAddress = $rec->manufacturerphysicaladdress;
                    $ManufacturerPhysicalRegion = $rec->manufacturerphysicalregion;
                    $ManufacturerPhysicalCountry = $rec->manufacturerphysicalcountry;
                   //applicant details 
                   
                    //comparator product
                  //  $Name_of_Comparator = $rec->Name_of_Comparator;
                     //Applicant Details
                     $ApplicantName = $rec->applicantname;
                     $ApplicantPhysicalAddress = $rec->applicantphysicaladdress;
                     $ApplicantEmail = $rec->applicantemail;
                     $ApplicantRegion = $rec->applicantregion;
                     $ApplicantCountry = $rec->applicantcountry;
                 
                    //prncipal investigators
                    $principalInvName = $rec->principalinvname;
                    $principalInvPhysicalAddress = $rec->principalinvphysicaladdress;
                    $PrincipalInvEmail = $rec->principalinvemail;
                    $PrincipalInvRegion = $rec->principalinvregion;
                    $PrincipalInvCountry = $rec->principalinvcountry;
                    //sponsor details 
                    $SponsorName = $rec->sponsorname;
                    $SponsorPhysicalAddress = $rec->sponsorphysicaladdress;
                    $SponsorEmail = $rec->sponsoremail;
                    $SponsorRegion = $rec->sponsorregion;
                    $SponsorCountry = $rec->sponsorcountry;
                   
                    $status ='';
                    //study site details
                    $StudySiteName = $rec->studysitename;
                    $StudySiteCountry = $rec->studysitecountry;
                    $StudySiteRegion = $rec->studysiteregion;
                    $StudySiteCountry1 = $rec->studysitecountry1;
                    $StudyPhysicalAddress = $rec->studyphysicaladdress;
                    $StudyTelephone = $rec->studytelephone;
                    $StudyEmailAddress = $rec->studyemailaddress;

                     //other investigators
                     $OtherInvestigatorsName = $rec->otherinvestigatorsname;
                     $OtherInvestigatorsPhysicalAddress = $rec->otherinvestigatorsphysicaladdress;
                     $OtherInvestigatorsEmail = $rec->otherinvestigatorsemail;
                     $OtherInvestigatorsRegion = $rec->otherinvestigatorsregion;
                     $OtherInvestigatorsCountry = $rec->otherinvestigatorscountry;
                     $OtherInvestigatorsCategory = $rec->otherinvestigatorscategory;

                   
                    //IP
                    
                   //applicant
                   $applicant_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ApplicantCountry, 'description'=>$ApplicantCountry),array('name'=>$ApplicantCountry),'Country');
                
                   $applicant_regionid = $this->saveProductDataEntry('par_regions',array('name'=>$ApplicantRegion,'country_id'=>$applicant_country_id, 'description'=>$ApplicantRegion),array('name'=>$ApplicantRegion),'Region Details');
                   
                   $data = (object)array('name'=>$ApplicantName, 
                        'tpin_no'=>0, 
                        'contact_person'=>$ApplicantName,
                        'country_id'=>$applicant_country_id, 
                        'region_id'=>$applicant_regionid, 
                        'physical_address'=>$ApplicantPhysicalAddress, 
                        'postal_address'=>$principalInvPhysicalAddress, 
                      //  'telephone_no'=>$PrincContact_Details_Telephone_1, 
                        'email'=>$ApplicantEmail, 
                        'created_by'=>'Migration',
                        'created_on'=>Carbon::now(),
                        'status_id'=>1
                    );
                    
                    $applicant_id = $this->saveTraderInformationDetails($data);
                  
                    //save investigator
                    $pi_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$PrincipalInvCountry, 'description'=>$PrincipalInvCountry),array('name'=>$PrincipalInvCountry),'Country');
                
                    $pi_regionid = $this->saveProductDataEntry('par_regions',array('name'=>$PrincipalInvRegion,'country_id'=>$pi_country_id, 'description'=>$PrincipalInvRegion),array('name'=>$PrincipalInvRegion),'Region Details'); 
                    $investigator_data = array('name'=>$principalInvName, 
                        'country_id'=>$pi_country_id, 
                        'region_id'=>$pi_regionid, 
                        'physical_address'=>$principalInvPhysicalAddress, 
                        'postal_address'=>$principalInvPhysicalAddress, 
                      //  'telephone'=>$PrincContact_Details_Telephone_1, 
                        'email'=>$PrincipalInvEmail, 
                        'created_by'=>'Migration',
                        'created_on'=>Carbon::now()
                    );
                   
                    $investigator_id = $this->saveProductDataEntry('clinical_trial_personnel',$investigator_data,array('name'=>$principalInvName, 'country_id'=>$pi_country_id),'Investigator Details  Id');    
                    //save sponsor 
              
                    $sponscountry_id = $this->saveProductDataEntry('par_countries',array('name'=>$SponsorCountry, 'description'=>$SponsorCountry),array('name'=>$SponsorCountry),'Country');
                    $sponsregionid = $this->saveProductDataEntry('par_regions',array('name'=>$SponsorRegion,'country_id'=>$sponscountry_id, 'description'=>$SponsorRegion),array('name'=>$SponsorRegion),'Region Details');
                    
                    $sponsor_data = array('name'=>$SponsorName, 
                                                'physical_address'=>$SponsorPhysicalAddress, 
                                                'postal_address'=>$SponsorPhysicalAddress, 
                                                'email'=>$SponsorEmail, 
                                                //'telephone'=>$SponsorContact_Details_telephone_2, 
                                                'region_id'=>$sponsregionid, 
                                                'country_id'=>$sponscountry_id
                                            );
                                                       
                    $sponsor_id = $this->saveProductDataEntry('clinical_trial_personnel',$sponsor_data,array('name'=>$SponsorName, 'country_id'=>$sponscountry_id),'Sponsor Details  Id');            
                    //save clincial study site
                    
                    $study_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$StudySiteCountry1, 'description'=>$StudySiteCountry1),array('name'=>$StudySiteCountry1),'Country');
                    $study_region_id = $this->saveProductDataEntry('par_regions',array('name'=>$StudySiteRegion,'country_id'=>$study_country_id, 'description'=>$StudySiteRegion),array('name'=>$StudySiteRegion),'Region Details');

                    $study_sitedata = array('name'=>$StudySiteName, 
                                                'physical_address'=>$StudyPhysicalAddress, 
                                                'postal_address'=>$StudyPhysicalAddress, 
                                                'email_address'=>$StudyEmailAddress, 
                                            
                                                'region_id'=>$study_region_id, 
                                                'telephone'=>$StudyTelephone,
                                                'country_id'=>$study_country_id
                                            );
                    $study_site_id = $this->saveProductDataEntry('study_sites',$study_sitedata,array('name'=>$StudySiteName, 'region_id'=>$study_region_id),'Study Site Details  Id');            
                    $PhaseIdData = array('name'=>$PhaseId, 'description'=>$PhaseId, 'is_enabled'=>1);
                    $phase_id = $this->saveProductDataEntry('par_clinical_phases',$PhaseIdData,array('name'=>$PhaseId, 'description'=>$PhaseId),'Study Site Details  Id');   
                    $module_id = 7;
                    $sub_module_id = 10;
                    $section_id  = 5;
                    
                    $view_id = generateApplicationViewID();
                    $application_code =  generateApplicationCode(7, 'tra_clinical_trial_applications');
                    if($status == 'Active' || $status == 'Closed' || $status == 'Approved'){


                        $application_status_id = 6;
                    }
                    else{
                        $application_status_id = 5;

                    }
                    $application_data = array(
                                    'application_code'=>$application_code, 
                                    'reference_no'=>$ReferenceNo, 
                                    'view_id'=>$view_id, 
                                    'applicant_id'=>$applicant_id, 
                                    'sub_module_id'=>$sub_module_id, 
                                    'section_id'=>$section_id, 
                                    'fasttrack_option_id'=>2, 
                                    'process_id'=>$process_id, 
                                    'module_id'=>$module_id, 
                                    'trial_design'=>$Study_Design, 
                                    'clinical_prodsection_id'=>2,
                                    'public_title'=>$PublicTitle, 
                                    'clinicaltrial_description'=>$ClinicalTrialDescription,
                                    'purpose_of_trial'=>$PurposeOfTrial,
                                    'proposed_start_date'=>formatDate($StudyStartDate), 
                                    'phase_id'=>$phase_id, 
                                    'sponsor_id'=>$sponsor_id, 
                                    'investigator_id'=>$investigator_id, 
                                    'study_title'=>$Clinical_Trial_Title, 
                                    'protocol_no'=>$ProtocolNo, 
                                    'version_no'=>$VersionNo,  
                                    'date_of_protocol'=>$DateOfProtocol,
                                    'publication_url'=>$PublicationUrl,
                                    'clearance_no'=>$EthicsClearanceNo,
                                    'study_duration'=>$StudyDuration,
                                    'study_end_date'=>formatDate($StudyEndDate),  

                                    'date_added'=>formatDate($Date_of_Clinical_Trial_Application), 
                                    'submission_date'=>formatDate($Date_of_Clinical_Trial_Application), 
                                    'application_status_id'=>$application_status_id, 
                                    'refno_generated'=>1, 
                                    'created_on'=>Carbon::now(), 
                                    'created_by'=>'Migration', 
                    
                   );
                 
                    $resp =  insertRecord('tra_clinical_trial_applications', $application_data, 'Migration');
                    if($resp['success']){
                        $application_id = $resp['record_id'];
                    }
                    else{
                           print_r($resp);
                           exit();
                    }
                    $site_data = array('application_id'=>$application_id,
                                    'study_site_id'=>$study_site_id,
                                    'created_on'=>Carbon::now(), 
                                    'created_by'=>'Migration', 
                                );

                    
                    $resp =  insertRecord('clinical_trial_sites', $site_data, 'Migration');
                    //investigation product 
                    $investigationproduct_section_id = 2;
                   
                    $investigationprod_classification_id = 2;
                    $product_category_id = 2;
                    if($InvestigationProductSection == 'Medical Devices'){

                        $investigationproduct_section_id =4;
                    }
                    else  if($InvestigationProductSection == 'Medicines'){

                        $investigationproduct_section_id =2;

                    } else  if($InvestigationProductSection == 'Food'){


                        $investigationproduct_section_id =1;
                    }
                    $commonnameData = array('name'=>$CommonName, 'description'=>$CommonName, 'is_enabled'=>1, 'section_id'=>$investigationproduct_section_id);
                    $common_name_id = $this->saveProductDataEntry('par_common_names',$commonnameData,array('name'=>$CommonName, 'section_id'=>$investigationproduct_section_id),'Common Name'); 

                    $dosageData = array('name'=>$DosageForm, 'description'=>$DosageForm, 'is_enabled'=>1, 'section_id'=>$investigationproduct_section_id);

                    $common_name_id = $this->saveProductDataEntry('par_dosage_forms',$dosageData,array('name'=>$DosageForm, 'section_id'=>$investigationproduct_section_id),'Dosage'); 
                    $classData = array('name'=>$DosageForm, 'description'=>$DosageForm, 'is_enabled'=>1, 'section_id'=>$investigationproduct_section_id);

                    $classification_id = $this->saveProductDataEntry('par_classifications',$classData,array('name'=>$InvestigatorProductClassification, 'section_id'=>$investigationproduct_section_id),'Dosage');

                    
                    $man_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$ManufacturerPhysicalCountry, 'description'=>$ManufacturerPhysicalCountry),array('name'=>$ManufacturerPhysicalCountry),'Country');

                    $man_region_id = $this->saveProductDataEntry('par_regions',array('name'=>$ManufacturerPhysicalRegion,'country_id'=>$man_country_id, 'description'=>$ManufacturerPhysicalRegion),array('name'=>$ManufacturerPhysicalRegion),'Region Details');

                    $man_sitedata = array('name'=>$ManufacturerName, 
                                                'physical_address'=>$ManufacturerPhysicalAddress, 
                                                'postal_address'=>$ManufacturerPhysicalAddress, 
                                                'email_address'=>$StudyEmailAddress, 
                                            
                                                'region_id'=>$man_region_id, 
                                                'country_id'=>$man_country_id
                                            );
                    $manufacturer_id = $this->saveProductDataEntry('tra_manufacturers_information',$man_sitedata,array('name'=>$ManufacturerName, 'country_id'=>$man_country_id),'Manufacturer');    

                    $ip_product_data = array('investigationproduct_section_id'=>$investigationproduct_section_id, 
                                            'investigationprod_classification_id'=>$investigationprod_classification_id,  
                                            'common_name_id'=>$common_name_id, 
                                            'product_desc'=>$ActiveIngredients, 
                                            'brand_name'=>$InvestigationProductBrand,
                                            'product_category_id'=>2,
                                            'manufacturer_id'=>$manufacturer_id
                                );
                    $resp =  insertRecord('clinical_trial_products', $ip_product_data, 'Migration');
                    $category_id =7;
                    if($OtherInvestigatorsCategory == 'Co Invesigator'){
                        $category_id =    1; 

                    }
                    else if($OtherInvestigatorsCategory == 'Principal investigator'){

                        $category_id = 7;
                    }
                     $pi_country_id = $this->saveProductDataEntry('par_countries',array('name'=>$OtherInvestigatorsCountry, 'description'=>$OtherInvestigatorsCountry),array('name'=>$OtherInvestigatorsCountry),'Country');
                
                     $pi_regionid = $this->saveProductDataEntry('par_regions',array('name'=>$OtherInvestigatorsRegion,'country_id'=>$pi_country_id, 'description'=>$OtherInvestigatorsRegion),array('name'=>$OtherInvestigatorsRegion),'Region Details'); 
                     $otherinvestigator_data = array('name'=>$OtherInvestigatorsName, 
                         'country_id'=>$pi_country_id, 
                         'region_id'=>$pi_regionid, 
                         'physical_address'=>$OtherInvestigatorsPhysicalAddress, 
                         'postal_address'=>$principalInvPhysicalAddress, 
                       //  'telephone'=>$PrincContact_Details_Telephone_1, 
                         'email'=>$OtherInvestigatorsEmail, 
                         'created_by'=>'Migration',
                         'created_on'=>Carbon::now()
                     );
                    
                     $investigator_id = $this->saveProductDataEntry('clinical_trial_personnel',$otherinvestigator_data,array('name'=>$OtherInvestigatorsName, 'country_id'=>$pi_country_id),'Investigator Details  Id');    
                     $otherinvestigator_data = array('category_id'=>$category_id, 
                                'investigator_id'=>$investigator_id,  
                                'application_id'=>$application_id, 
                                'application_reference_no'=>$ReferenceNo
                    );
                     $resp =  insertRecord('clinical_trial_investigators', $otherinvestigator_data, 'Migration');
                     
                    //approval infrmation  ReferenceNo
                    if($status == 'Active' || $status == 'Closed' || $status == 'Approved'){
                        $app_record = DB::table('tra_approval_recommendations')->where(array('certificate_no'=>$ReferenceNo, 'module_id'=>$module_id))->first();
                        if(!$app_record){
                            $approval_data = array('application_code'=>$application_code,
                                                'application_id'=>$application_id,
                                                'decision_id'=>1,
                                                'module_id'=>$module_id,
                                                'certificate_no'=>$ReferenceNo,
                                                'appvalidity_status_id'=>2,
                                                'appregistration_status_id'=>2,
                                                'comment'=>'Migration Approval Details',
                                                //'approval_date'=>$certificate_approval_date,
                                                //'certificate_issue_date'=>formatDate($certificate_approval_date),
                                                //'expiry_date'=>formatDate($certificate_expiry_date),
                                                'approved_by'=>'Migration Data',
                                                'created_on'=>Carbon::now()
                            );
                            $resp =  insertRecord('tra_approval_recommendations', $approval_data, 'Migration');
                            if($resp['success']){
                                $application_id = $resp['record_id'];
                            }
                            else{
                                   print_r($resp);
                                   exit();
                            }
                            $app_record = DB::table('registered_clinical_trials')->where(array('registration_no'=>$ReferenceNo))->first();
                            if(!$app_record){
                                $regdata = array('tra_clinical_trial_id'=>$application_id,
                                        'validity_status_id'=>2,
                                        'registration_status_id'=>2,
                                        'registration_no'=>$ReferenceNo,
                                        'active_application_code'=>$application_code,
                                        'created_on'=>Carbon::now()
                                );
                                $resp =  insertRecord('registered_clinical_trials', $regdata, 'Migration');
                                if($resp['success']){
                                    $reg_id = $resp['record_id'];
                                }
                                else{
                                        print_r($resp);
                                        exit();
                                }
                                DB::table('tra_clinical_trial_applications')
                                ->where(array('application_code'=>$application_code))
                                ->update(array('reg_clinical_trial_id'=>$reg_id));
                            }
                        }
                        $res = array('success'=>true, 'message'=>'Application Migrated Successfully');
                       
                    }else{
						//submission 
						
                        $res = array('success'=>true, 'message'=>'Application Migrated Successfully');
                      
                    }
                    DB::table('clinical_trial')->where('id',$id)->update(array('ReferenceNo'=>$ReferenceNo));
                    
                }
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
	return $res;

   }
   function syncmappingClinicaltrialsSubmission($application_code){
		
        try{
			$section_id = $req->section_id;
			
            $records_migrated =0;
                $records = DB::table('tra_clinical_trial_applications as t1')
								->leftJoin('tra_approval_recommendations as t3', 't1.application_code', 't3.application_code')
                                ->select('t1.*',  't1.id as application_id')
								->where('t1.application_code',$application_code)
                                ->get();
                if($records){
                    foreach($records as $rec){
                       
                           $destination_process = 149;
                        
                        $sub_rec = DB::table('tra_submissions')->where(array('application_code'=>$rec->application_code,'current_stage'=>$destination_process,'isDone'=>0))->first();

                        if(!$sub_rec){
                            $submission_data = array(
                                'application_id'=>$rec->application_id,
                                'applicant_id'=>$rec->applicant_id,
                                'application_code'=>$rec->application_code,
                                'reference_no'=>$rec->reference_no,
                                'tracking_no'=>$rec->tracking_no,
                                'view_id'=>$rec->view_id,
                                'process_id'=>$rec->process_id,
                                'previous_stage'=>$destination_process,
                                'current_stage'=>$destination_process,
                                'module_id'=>$rec->module_id,
                                'sub_module_id'=>$rec->sub_module_id,
                                'directive_id'=>1,
                                'application_status_id'=>$rec->application_status_id,
                                'section_id'=>$rec->section_id,
                                'urgency'=>1,
                                'remarks'=>'Migrated Renewal Applications',
                                'isRead'=>0,
                                'isDone'=>0,
                                'isComplete'=>0,
                                'date_received'=>Carbon::now(),
                                'created_on'=>Carbon::now(),
                                'created_by'=>'Migrated By'
                            
                        );
        
                        DB::table('tra_submissions')->insert($submission_data);
                        $message = 'Applications Have been mapped on the submission table successsfully Application No'.$rec->reference_no;
                       $res = array(
							'success' => true,
							'message' => $message
						);
                        } else{

                      //print_r($rec);
                           $message = 'Applications already been mapped on the submission table successsfully Application No'.$rec->reference_no;
							$res = array(
								'success' => true,
								'message' => $message
							);
                            $records_migrated++;
                    }  
                    }


                }
                else{

                    $res = "No application found";
                }
                $this->saveMigrationLogsData('initiatemappingProductRegistrationSubmission',$records_migrated);
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
		return $res;
	   
	  
   }
   
   public function mapNewWorkflowfromExisting(Request $req){
		try{
				$new_workflow_id = $req->new_workflow_id;
				$existing_workflow_id = $req->existing_workflow_id;
				
				if(validateIsNumeric($new_workflow_id) && validateIsNumeric($existing_workflow_id)){
							$workflow_stages = DB::table('wf_workflow_stages')
										->where(array('workflow_id'=>$existing_workflow_id))
										->get();
							foreach($workflow_stages as $workflow_stage){
										$stage_id = $workflow_stage->id;
										$workflow_stage = (array)$workflow_stage;
										$workflow_stage['workflow_id'] = $new_workflow_id;
										unset($workflow_stage['id']);
										$resp =  insertRecord('wf_workflow_stages', $workflow_stage, 'Migration');
										
										if($resp['success']){
											$new_stage_id = $resp['record_id'];
											
										}
										$stages_groups = DB::table('wf_stages_groups')
											->where(array('stage_id'=>$stage_id))
											->get();
										foreach($stages_groups as $stages_group){
											$stages_group = (array)$stages_group;
											$stages_group['stage_id'] = $new_stage_id;
												unset($stages_group['id']);
											$resp =  insertRecord('wf_stages_groups', $stages_group, 'Migration');
										}
                                        $workflow_actions = DB::table('wf_workflow_actions')
											->where(array('stage_id'=>$stage_id))
											->get();
										foreach($workflow_actions as $workflow_action){
											$workflow_action = (array)$workflow_action;
											$workflow_action['stage_id'] = $new_stage_id;
											unset($workflow_action['id']);
											$resp =  insertRecord('wf_workflow_actions', $workflow_action, 'Migration');
										}
									
							}
					$res = array('success'=>true, 'message'=>'Mapped Successfully');
				}else{
					
					$res = array('success'=>false, 'message'=>'Validate the parameters submitted, they are missing or non numeric');
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
		return $res;
	   
   }
   //remapProductAuthorisationManuacturer
	public function remapProductAuthorisationManuacturer(){
				try{
					
				$records = DB::table('tra_product_applications as t1')
								->join('tra_product_authorisationnot as t2', 't1.reference_no', 't2.ReferenceNo')
								->select('t1.product_id','t2.Product_Common_Name', 't1.section_id')
								->where('t1.product_id',9336)
								->get();
				foreach($records as $rec){
						$product_id = $rec->product_id;
						$common_name_id = $this->saveProductDataEntry('par_common_names',array('section_id'=>$rec->section_id, 'name'=>$rec->Product_Common_Name, 'description'=>$rec->Product_Common_Name, 'is_enabled'=>1),array('name'=>$rec->Product_Common_Name),'Common Names');
						print_r($common_name_id);
						exit();
						DB::table('tra_product_information')
                            ->where(array('id'=>$product_id))
                            ->update(array('common_name_id'=>$common_name_id));
					$res = "Success";
					
					
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
		return $res;
	}
	
	
public function remapImportApplicationsToPortal(){
		$table_name = 'wb_importexport_applications';
		$records = DB::connection('audit_db')->table('tra_portalaudit_trail')
						->where(array('table_name'=>$table_name))->whereIn('id',[60484])
						->get();
		foreach($records  as $rec){
					//insert 
					$table_action = $rec->table_action;
					$current_tabledata = unserialize($rec->prev_tabledata);
					$record_id = $rec->record_id;
					$current_tabledata = $current_tabledata['results'][0];
				
					if($table_action == 'delete'){
						
						//$check = DB::connection('portal_db')->table($table_name)->where('id',$record_id)->count();
						//if($check ==0){
						//	print_r($current_tabledata['results'][0]);
							//exit();
							//$current_tabledata = $current_tabledata['results'][0];
						unset($current_tabledata['id']);
							
							DB::connection('portal_db')->table($table_name)->insert($current_tabledata);
							
							//DB::table('tra_permits_products')->insert($current_tabledata);
							print_r($current_tabledata);
							exit();
						//}
						
						
					}
					else{
						
						$check = DB::connection('portal_db')->table($table_name)->where('id',$record_id)->count();
						if($check > 0){
							
							DB::connection('portal_db')->table($table_name)->where('id',$record_id)->update($current_tabledata);
							
						}
					}
					
					print_r('records mapped successfully');
					
					//update
		}
	
	
}

}