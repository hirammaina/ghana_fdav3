<?php
/**
 * Created by PhpStorm.
 * User: Kip
 * Date: 4/9/2019
 * Time: 8:41 PM
 */

namespace App\Modules\Reports\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;


use \Mpdf\Mpdf as mPDF;

use PDF;

use App\Modules\Reports\Providers\PdfProvider;
use App\Modules\Reports\Providers\PdfLettersProvider;
use App\Modules\Reports\Providers\PdfPlainLettersProvider;
use App\Modules\Reports\Providers\PdfOfficialcertficateProvider;
use App\Modules\Reports\Providers\PdfPermitProvider;
use App\Modules\Reports\Providers\PdfImpExpLicenseProvider;
use App\Modules\Reports\Providers\PdfDisposalProvider;
use App\Modules\Reports\Providers\PdfPremisesLicenseProvider;
trait ReportsTrait
{

    public function generatePremisePermit($premise_id)
    {
        $params = array(
            'premise_id' => $premise_id,
            'document_type' => 'permit'
        );
        $report = generateJasperReport('premisePermitReport', 'permit_' . time(), 'pdf', $params);
        return $report;
    }

    public function generatePremiseCertificate($premise_id)
    {
        $params = array(
            'premise_id' => $premise_id,
            'document_type' => 'certificate'
        );
        $report = generateJasperReport('certificateReport', 'certificate_' . time(), 'pdf', $params);
        return $report;
    }
	
	function getOfficialCertificateHeader($pdf,$code){
										// add a page
										$pdf->AddPage();
										$pdf->SetLineWidth(0.4);
										//$pdf->Rect(3,3,204,290);
										$pdf->SetLineWidth(1.2);
										//$pdf->Rect(5,5,200,285);
										$pdf->SetLineWidth(0.4);
									//	$pdf->Rect(7,7,195,280);
										$pdf->setMargins(10,10,10,true);
										$template_url = base_path('/');
										$pdf->setSourceFile($template_url."resources/templates/certificate_template.pdf");
										// import page 1
										$tplId = $pdf->importPage(1);	
									
										// use the imported page and place it at point 10,10 with a width of 100 mm
										//$pdf->useTemplate($tplId,0,0);
										$pdf->setPageMark();
										$pdf->SetLineWidth(0.4);
										$pdf->Rect(5,5,200,280);
										$pdf->Rect(3,3,204,285);
										$logo = getcwd() . '/resources/images/org-logo.png';
									$pdf->SetFont('times','B',9);
								$pdf->Cell(0,1,'',0,1);
									$pdf->ln();
									$pdf->Image($logo, 8, 9, 33, 35);
											$org_info = $this->getOrganisationInfo();
									$pdf->SetFont('times','B',10);
											$pdf->cell(0,20,'',0,1);
										$pdf->cell(0, 6, $org_info->postal_address, 0,1, '');
										$pdf->cell(0, 6,$org_info->email_address, 0,1, '');
													
									$pdf->cell(0, 6,$org_info->website, 0,1, '');
			
	}
	function getCertificateHeader($pdf,$code){
										// add a page
										$pdf->AddPage();
										$pdf->SetLineWidth(0.4);
										//$pdf->Rect(3,3,204,290);
										$pdf->SetLineWidth(1.2);
										//$pdf->Rect(5,5,200,285);
										$pdf->SetLineWidth(0.4);
									//	$pdf->Rect(7,7,195,280);
										$pdf->setMargins(20,25,20,true);
										$template_url = base_path('/');
										$pdf->setSourceFile($template_url."resources/templates/certificate_template.pdf");
										// import page 1
										$tplId = $pdf->importPage(1);	
									
										// use the imported page and place it at point 10,10 with a width of 100 mm
										$pdf->useTemplate($tplId,0,0);
										$pdf->setPageMark();
										$pdf->SetLineWidth(0.4);
										$pdf->Rect(5,5,200,280);
										$pdf->Rect(3,3,204,285);
			
	}
	function funcGenerateQrCode($row,$pdf){
		
								$data = url('/').'/api/permitValidation?application_code='.base64_encode($row->application_code).'&module_id='.$row->module_id;

								//$data = "application_code:".$row->certificate_no."; Brand Name:".$row->brandName.";Expiry Date:".formatDate($row->expiry_date);
								 $styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
								// QRCODE,H : QR-CODE Best error correction
								$template_url = getcwd();
								$qrc_code = $template_url . '/resources/images/qrc_code.jpg';
								$width = 16;
								$height = 16;
								
								$qr_codex = 178;
								$qr_codey = 28;
								$pdf->write2DBarcode($data, 'QRCODE,H', $qr_codex,$qr_codey , $width, $height);
								
							   $pdf->Image($qrc_code,$qr_codex+$width+0,$qr_codey,$width-3,$height-4);
								
		
	}
	//genenerateImportExportPermitValidation
	
	public function genenerateImportExportPermitVal($application_code,$permit_watermark,$is_preview)
    {
		
		$document_type_id = 25;
		$document_requirement_id = 254;
		
        $approvalGrant = DB::table('tra_managerpermits_review')->where('application_code',$application_code)->first();
        if((!empty($approvalGrant) && $approvalGrant->decision_id == 1) || empty($approvalGrant)){
			$record = DB::table('tra_importexport_applications as t1')
						->join('sub_modules as t2','t1.sub_module_id','t2.id')
						->select('t2.title', 't1.sub_module_id')
						->where('application_code',$application_code)->first();
						$sub_module_id = $record->sub_module_id;
			if(empty($approvalGrant)){
				$permit_watermark = 'Print Preview';
			}
		
			if($sub_module_id == 78 || $sub_module_id == 61  || $sub_module_id == 83   || $sub_module_id == 82){

				$this->printImportExportLicense($application_code,$record,$permit_watermark);
				
			}
			else if($sub_module_id == 60){
				
				$this->printOfficialCertficateCtrDrgs($application_code,$record,$permit_watermark);
			
			}else if($sub_module_id == 81){
				$this->printExportLicense($application_code,$record,$permit_watermark,$permit_watermark);
			}
			else{
				
				$this->printImportExportvisa($application_code,$record,$permit_watermark);
			
			}
			
						
        }else if(!empty($approvalGrant) && $approvalGrant->decision_id== 2){
			echo "The Application has been rejected ".$approvalGrant->comment;
			
			
		}else{
           echo "No approval recommendation, contact the system admin";
			
        }
        
	}
	function funcAppGenerateQrCode($row,$pdf){
		
								$data = url('/').'/api/permitValidation?application_code='.$row->application_code.'&module_id='.$row->module_id;

								//$data = "application_code:".$row->certificate_no."; Brand Name:".$row->brandName.";Expiry Date:".formatDate($row->expiry_date);
								 $styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
								// QRCODE,H : QR-CODE Best error correction
								$template_url = getcwd();
								$qrc_code = $template_url . '/resources/images/qrc_code.jpg';
								$width = 16;
								$height = 16;
								
								$qr_codex = 178;
								$qr_codey = 28;
								$pdf->write2DBarcode($data, 'QRCODE,H', $qr_codex,$qr_codey , $width, $height);
							   //$pdf->Image($qrc_code,$qr_codex+$width-4,$qr_codey,$width-3,$height-4);
								
		
	}
	public function foodProductRegistrationCertificate($application_code,$row){
		try{
			
						
						$is_provisional =0;
						
						if($row){
							if($row->recommendation_id == 2){
								$is_provisional =1;
							}
							$org_info = $this->getOrganisationInfo();
								
								$pdf = new PdfProvider();
								$this->getCertificateHeader($pdf, 'DAR/FMT/042');
								
								$logo = getcwd() . '/resources/images/org-logo.png';
								$pdf->SetFont('times','B',9);
								$pdf->Cell(0,1,'',0,1);
								
								
								$pdf->Cell(0,21,'',0,1);
								
								$pdf->Cell(0,5,'REGISTRATION CERTIFICATE OF FOOD PRODUCT',0,1,'C');
								$pdf->SetFont('times','',10);
								$pdf->ln();
								$act_statement = "Made under Law No. 003/2018 of 09/02/2018 establishing the Rwanda FDA and determining its mission, organization and functioning in his article 3 and article 8 and regulation No. CBD/TRG/010. The Authority here issues.\n";
								
								$pdf->MultiCell(0,5,$act_statement,0,'J',0,1);
							
								$pdf->SetFont('times','B',10);
								$pdf->Cell(0,3,'',0,1);
								
								$pdf->SetFont('times','',10);
									
                                if( $is_provisional == 1){
                                   // $pdf->Cell(70,8,0,0);
									$pdf->MultiCell(70,8,'Provisional registration number of the medicine',0,'',0,0);
                                }
                                else{
                                    $pdf->Cell(70,8,'Registration number:',0,0);
								
                                }
								
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,$row->certificate_no,0,1);
								//$pdf->Cell(0,1,'',0,2);
								$pdf->SetFont('times','',10);
								
								//Brand Name
								$pdf->MultiCell(0,8,"This is to certify that the medicine described below has been registered in Rwanda subject to conditions indicated at the back of the this certificate:\n",0,'J',0,1);
							
								$pdf->SetFont('times','',10);
									
								$pdf->MultiCell(70,8,'Brand Name:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->brandName),0,'',0,1);
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(70,8,'Common Name:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->common_names),0,'',0,1);
								
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(69,10,'Pack size and Packaging type:',0,'',0,0);
								//$pdf->Cell(70,5,'',0,0,'L');
								
								$pdf->SetFont('times','B',10);
								$packaging = '';
											$container_name = '';
											$retail_packaging_size = '';
								$packaging_data = DB::table('tra_product_packaging as t1')
											->select(DB::raw("t1.*, t2.name as container_type, t3.name as container_name, t4.name as container_material, t5.name as closure_materials, t4.name as container_material, t5.name as closure_material, t6.name as seal_type, t7.name as packaging_units, CONCAT_WS('X',retail_packaging_size,retail_packaging_size1,retail_packaging_size2,retail_packaging_size3,retail_packaging_size4) as retail_packaging"))
											->leftJoin('par_containers_types as t2', 't1.container_type_id', '=', 't2.id')
											->leftJoin('par_containers as t3', 't1.container_id', '=', 't3.id')
											->leftJoin('par_containers_materials as t4', 't1.container_material_id', '=', 't4.id')
											->leftJoin('par_closure_materials as t5', 't1.closure_material_id', '=', 't5.id')
											->leftJoin('par_seal_types as t6', 't1.seal_type_id', '=', 't6.id')
											->leftJoin('par_packaging_units as t7', 't1.packaging_units_id', '=', 't7.id')
											->where(array('t1.product_id' => $row->product_id))
											->get();
								
								if($packaging_data->count() >0){
								$i = 1;
									foreach($packaging_data as $packaging_rec){
										
											$container_material = $packaging_rec->container_material;
											$container_name = $packaging_rec->container_name;
											
											$retail_packaging_size = $packaging_rec->retail_packaging;
											
											$product_unit = $packaging_rec->unit_pack;		
											if($i != 1){
												$pdf->Cell(69,5,'',0,0);
											}									
											if($product_unit == ''){
											
													$pdf->MultiCell(0,5,strtoupper($container_material).' '.strtoupper($container_name) .' OF '.strtoupper($retail_packaging_size),0,'',0,1);
											}
											else{
												
													$pdf->MultiCell(0,5,strtoupper($container_material).' '.strtoupper($container_name) .' OF '.strtoupper($retail_packaging_size).' X '.strtoupper($product_unit),0,'',0,1);													
																							
											}
											
											$i++;									
									}
											
								
								}
								else{
											$pdf->MultiCell(0,10,'',0,'',0,1);
											
								}
								
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Shelf life of medicine in months and Storage statement:',0,'',0,0); ;
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(100,8,strtoupper($row->shelf_life).', '.strtoupper(html_entity_decode(($row->storage_condition))),0,'',0,1); ;
								
								$pdf->SetFont('times','B',10);
								
								
								$pdf->MultiCell(70,10,'Name of Marketing authorization holder:',0,'',0,0);
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,10,strtoupper($row->trader_name),0,'',0,1);
								//$pdf->Cell(100,12,ucwords($applicantName),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								//Manufacturer
								 $manrow = DB::table('tra_product_manufacturers as t1')
									->select('t1.*', 't2.email_address','t1.id as manufacturer_id', 't2.physical_address', 't2.name as manufacturer_name','t2.postal_address', 't3.name as country_name', 't4.name as region_name', 't5.name as district_name')
									->join('tra_manufacturers_information as t2', 't1.manufacturer_id', '=', 't2.id')
									->join('par_countries as t3', 't2.country_id', '=', 't3.id')
									->leftJoin('par_regions as t4', 't2.region_id', '=', 't4.id')
									->leftJoin('par_districts as t5', 't2.district_id', '=', 't5.id')
									->leftJoin('par_manufacturing_roles as t6', 't1.manufacturer_role_id', '=', 't6.id')
									->where(array('t1.product_id' => $row->product_id, 'manufacturer_type_id' => 1))
									->first();
									
									$manufacturer_name='';
									$man_postal_address='';
									$man_physical_address='';
									$man_countryName='';
									$man_districtName='';
									$man_regionName = '';
									
								if($manrow){
									$manufacturer_name=$manrow->manufacturer_name;
									$man_postal_address=$manrow->postal_address;
									$man_physical_address=$manrow->physical_address;
									
									$man_countryName= $manrow->country_name;
									$man_regionName = $manrow->region_name;
								}
								
								//Manufacturer sql 
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(70,10,'Name and Address of the Manufacturer:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($manufacturer_name),0,'',0,1);
								
								$pdf->SetFont('times','',10);
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($man_postal_address),0,'',0,1);
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($man_physical_address),0,'L');
								
								if($man_regionName!=''){
									$pdf->Cell(70,5,'',0,0,'L');
									$pdf->SetFont('times','B',10);
									$pdf->Cell(100,5,strtoupper($man_regionName),0,1,'L'); 
								}
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,5,strtoupper($man_countryName),0,1,'L'); 
								 
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Name of Local Technical Representative:',0,'',0,0);
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,8,strtoupper($row->localAgentName),0,'',0,1);
								
								$pdf->SetFont('times','',10);
								$pdf->Cell(70,8,'Valid From:',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(35,8,ucwords(date('F d, Y ',strtotime($row->certificate_issue_date))),0,0,'L'); 
								
								
								$pdf->SetFont('times','',10);
								$pdf->Cell(30,8,'To:',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(0,8,ucwords(date('F d, Y ',strtotime($row->expiry_date))),0,1,'L'); 
								
								
								$pdf->Cell(0,2,'',0,1);
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
								$this->funcGenerateQrCode($row,$pdf);
								
								$this->getCertificateSignatoryDetail($row,$pdf);
								
								$pdf->AddPage();
								$pdf->SetFont('times','B',9);
								
								
								$pdf->Cell(0,5,'Conditions of Registration:',0,1);
								$pdf->SetFont('times','',11);
								$pdf->Cell(0,2,'',0,1);
								
								$this->getCertificateRegistrationConditions($row,$pdf);
								
								$pdf->Output();
						}	
							
								
			
			
		} catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			print_r($res);
			exit();
        return response()->json($res);
		
	}
	function printPremisesCertificateLetter($request,$approvalGrant,$permit_previewoption=null,$upload_directory=null){
		try{
			$application_code = $request->application_code;
			
			$record = DB::table('tra_premises_applications as t1')
												->join('tra_premises as t2', 't1.premise_id','t2.id')
												->join('par_countries as t3', 't2.country_id', 't3.id')
												->leftJoin('par_regions as t4', 't2.region_id','t4.id')
												->leftJoin('par_districts as t5', 't2.district_id', 't5.id')
												->join('wb_trader_account as t6', 't1.applicant_id', 't6.id')
												->leftJoin('par_countries as t7', 't6.country_id', 't7.id')
												->leftJoin('par_regions as t8', 't6.region_id','t8.id')
												->leftJoin('par_districts as t9', 't6.district_id', 't9.id')
												->leftJoin('par_business_types as t11', 't2.business_type_id', 't11.id')
												->leftJoin('par_sectors as t12', 't2.sector_id', 't12.id')
												->leftJoin('par_cells as t13', 't2.cell_id', 't13.id')
												->leftJoin('tra_approval_recommendations as t10','t1.application_code','t10.application_code')
												->leftJoin('users as t17', 't10.permit_signatory', '=', 't17.id')
												->leftJoin('par_sections as t18', 't1.section_id', '=', 't18.id')
												->leftJoin('tc_recommendations as t14', 't1.application_code', '=', 't14.application_code')
												->select(DB::raw("t1.reference_no,t18.name as section_name, t13.name as cell_name, t12.name as sector_name,t1.application_code,t1.*,concat(decrypt(t17.first_name),' ',decrypt(t17.last_name)) as permit_signatoryname, t2.*, t10.permit_signatory,t1.premise_id,  t2.postal_address as premise_poastal_address,t11.name as premises_type,t11.license_title, t2.physical_address as premise_physical_address, t4.name as premise_region_name,t5.name as premise_district_name,t7.name as premise_country,t1.date_added as date_registered,t10.id as decision_record,t10.decision_id, t10.expiry_date,t10.approval_date as permit_issue_date,t10.permit_no,t2.premise_reg_no,t2.name as premise_name,t6.name as applicant_name,t6.physical_address,t6.postal_address,t6.telephone_no as telephone,t6.email,t9.name as districtName,t8.name as regionName,t7.name as countryName, t11.is_manufacturer, t14.certificate_expiry_statement"))
												->where(array('t1.application_code'=>$application_code))
												->first();
									
						if(!validateIsNumeric($record->decision_record) || $record->decision_id == 1){
											$org_info = getOrganisationInfo();
											
											$pdf = new PdfPremisesLicenseProvider();
											$this->getPremisesCertificateHeader($pdf, '');
											
											
											$pdf->setCellHeightRatio(1.5); 
											$logo = getcwd() . '/resources/images/org-logo.jpg';
											$pdf->SetFont('','B',9);
											$pdf->Cell(0,1,'',0,1);
											
											$pdf->Cell(0,8,'',0,1);
											$pdf->ln();
														$personnel  ='';	
											if($record){
														$row=$record;
														$applicantName=$row->applicant_name;
														$premise_name=$row->premise_name;
														$reference_no=$row->reference_no;
														$permit_no=$row->permit_no;
																			
														$date_added=$row->date_registered;
														$postal_address=$row->postal_address;
														$physical_address=$row->physical_address;
														$countryName=$row->countryName;
														$regionName=$row->regionName;
														$districtName=$row->districtName;
														$premiseID=$row->premise_id;
														$premise_reg_no=$row->premise_reg_no;
														$premises_id = $row->premise_id;
														$permit_issue_date = $row->permit_issue_date;
														$locationDesc ='';
														$org_info = getOrganisationInfo();

														$premise_name = $row->premise_name;
														$premise_poastal_address = $row->premise_poastal_address;
														$premise_physical_address = $row->premise_physical_address;
														$premise_region_name = $row->premise_region_name;
														$premise_country = $row->premise_country;
														$premise_district_name = $row->premise_district_name;
														$this->funcPremisesGenerateQrCode($record,$pdf);
	
														//if($row->section_id == 1){
																		$pdf->ln();$pdf->SetFont('times','B',10);
																		$pdf->setFillColor(230,230,230); 
													//	$pdf->Cell(0,5,'Premise registration certificate No: '.$premise_reg_no,0,1,'C'); premise
													//$pdf->ln();
$pdf->MultiCell(0,5,strtoupper('Premises registration certificate No: '.$premise_reg_no),0, 'C', 1, 1, '' ,'', true);
																		
																if($row->is_manufacturer){
$pdf->SetFont('times','B',16);
	$pdf->ln();
																		$pdf->MultiCell(0,6,'PREMISES LICENSE FOR MANUFACTURER OF',0,'C',0,1);
																		$pdf->MultiCell(0,6,strtoupper($record->license_title),0,'C',0,1);
																		
																		$pdf->SetFont('','B',11);
																		
																		$pdf->Cell(0,6,'This is to certify that ',0,1,'');
																		$pdf->Cell(0,6,'Premises License No: 	'.$record->permit_no,0,1,'');
																		$pdf->SetFont('times','B',11);
																			$pdf->ln();
																		$pdf->Cell(0,6,'was granted to:',0,1,'');
																		$pdf->SetFont('times','B',11);
																			$pdf->ln();
																		$pdf->MultiCell(60,6,'Name of the Company: ',0,'',0,0);
																			$pdf->SetFont('times','B',11);
																		
																		$pdf->MultiCell(0,6,$record->premise_name,0,'',0,1);
																			$pdf->SetFont('times','B',11);
																		
																		$pdf->MultiCell(60,6,'Company Code:	 ',0,'',0,0);
																		$pdf->SetFont('times','',11);
																		
																		$pdf->MultiCell(0,6,$record->company_registration_no,0,'',0,1);
																		$pdf->SetFont('times','B',11);
																		
																		$pdf->MultiCell(60,6,'Location of the premises:',0,'',0,0);
																		$pdf->SetFont('times','',11);
																		
																		$pdf->MultiCell(0,6,$record->premise_region_name.", " .$record->premise_district_name.", ".$record->sector_name.", ".$record->cell_name,0,'',0,1);$pdf->SetFont('times','B',11);
																		$pdf->ln();
																		$pdf->MultiCell(60,6,'Name of the Managing Director:',0,'',0,0);
																		$pdf->SetFont('times','',11);
																		
																		$pdf->MultiCell(0,6,$record->managing_director,0,'',0,1);
																		$pdf->SetFont('times','B',11);
																		
																		$pdf->MultiCell(60,6,'Telephone No:',0,'',0,0);
																		$pdf->SetFont('times','',11);
																		
																		$pdf->MultiCell(0,6,$record->managing_director_telepone,0,'',0,1);
																		$personnel = "";
																		$prem_perrecords = DB::table('tra_premises_personnel as t1')
																						->leftJoin('tra_personnel_information as t2', 't1.personnel_id','t2.id')
																						->leftJoin('par_personnel_positions as t3', 't1.position_id','t3.id')
																						->select('personnel_name')
																						->where(array('t1.premise_id'=>$record->premise_id))
																						->get();
																						//, 't3.main_personnel'=>1
																		if($prem_perrecords){
																			foreach($prem_perrecords as $prem_per){
																				$personnel .= $prem_per->personnel_name.',';
																					
																			}
																					
																		}
																		
																		$pdf->SetFont('times','B',11);
																		
																		$pdf->MultiCell(60,7,'Head of Production Department:',0,'',0,0);
$pdf->SetFont('times','',11);
																		
																		$pdf->MultiCell(0,7,rtrim($personnel,','),0,'',0,1);
																	
																		$pdf->Cell(0,8,'to carry out the following manufacturing activities: ',0,1,'');
																		  $premises_operations = DB::table('tra_premises_otherdetails as t1')
																		->join('par_business_types as t2', 't1.business_type_id', '=', 't2.id')
																		->leftJoin('par_business_type_details as t3', 't1.business_type_detail_id', '=', 't3.id')
																		->leftJoin('par_product_categories as t4', 't1.product_category_id', '=', 't4.id')
																		->leftJoin('par_subproduct_categories as t5', 't1.product_subcategory_id', '=', 't5.id')
																		->select('t1.*','t4.name as product_category', 't5.name as product_subcategory', 't2.name as business_type', 't3.name as business_type_detail')
																		->where('t1.premise_id', $premises_id)
																		->get();$pdf->SetFont('times','B',11);
																		
																		$pdf->Cell(50,5,'Product category',1,0,'');
																		$pdf->Cell(50,5,'Product type',1,0,'');
																		$pdf->Cell(0,5,'Manufacturing activities',1,1,'');

																		if($premises_operations){
																			$pdf->SetFont('times','',10);
																		
																			foreach($premises_operations  as $operation){
																//$manufacturing_activities = 'Production, packaging, labeling, storage and distribution';
																$manufacturing_activities = $operation->manufacturing_activities;
																$rowcount = max(PDF::getNumLines($operation->product_category, 50),PDF::getNumLines($operation->product_details, 50),PDF::getNumLines($manufacturing_activities, 55));
																					
										$pdf->MultiCell(50,$rowcount*5,$operation->product_category,1,'',0,0);
																					$pdf->MultiCell(50,$rowcount*5,$operation->product_details,1,'',0,0);
																					$pdf->MultiCell(0,$rowcount*5,$manufacturing_activities,1,'',0,1);
																		

											
																			}

																		}$pdf->Cell(0,1,'',0,1);

																	$pdf->SetFont('times', 'b', 11);
																	if($record->certificate_expiry_statement != ''){
																	//	$pdf->Cell(0,5,$record->certificate_expiry_statement,0,1);
																$pdf->MultiCell(0,5,$record->certificate_expiry_statement,0,'',0,1);
																	}else{
																		$pdf->Cell(0,5,'This license is valid until '.formatDateRpt($record->expiry_date),0,1);
																
																		
																	}
																$pdf->Cell(0,1,'',0,1);
$pdf->SetFont('times','I',11);
																		$pdf->MultiCell(0,5,'This premises license may be suspended or withdrawn if the conditions under which it was granted are violated. The product is put on market after its assessment and registration by Rwanda FDA. The application for renewal of license shall be due one month before its expiry.',0,'',0,1);
																			$pdf->Cell(0,5,'',0,1);
																		$pdf->Cell(0,5,'Done at Kigali on : '.formatDateRpt($record->permit_issue_date),0,1);
																		
																		$permit_signitory = '';
																		$title= 'ACTING';
																		$title= '';
																		$approved_by = '';
																		//application_code
																		$this->getCertificateSignatoryDetail($record,$pdf);
																		$pdf->Output();
																		$i= 1;
																		$l =1;
																		
														}
														
														else{
$pdf->SetFont('times','BU',18);	$pdf->ln();
																		
																$pdf->Cell(0,3,'PREMISES LICENSE',0,1,'C');	
																$pdf->ln();
			$pdf->SetFont('times','',11);
																		
																$premises_statement1 = "This is to certify that <b>".strtoupper($record->premise_name)."</b> with premises license number<b> ".$permit_no."</b> under company code <b>".$record->company_registration_no."</b> licensed to store and operate as <b>".strtoupper($record->license_title)."</b> on the following location:\n";
															$pdf->WriteHTML($premises_statement1, true, false, true, true, '');
																$pdf->SetFont('times','B',11);
$pdf->Cell(0,1,'',0,1);
																$pdf->MultiCell(60,5,'Sales room:',0,'',0,0);
																$pdf->SetFont('times','',11);
																		
																$pdf->MultiCell(0,5,$record->premise_region_name.", " .$record->premise_district_name.", ".$record->sector_name.", ".$record->cell_name,0,'',0,1);
$pdf->SetFont('times','B',11);
																		
																$pdf->MultiCell(60,5,'Store Room 1:',0,'',0,0);
$pdf->SetFont('times','',11);
																		
																$pdf->MultiCell(0,5,$record->premise_region_name.", " .$record->premise_district_name.", ".$record->sector_name.", ".$record->cell_name,0,'',0,1);
$pdf->Cell(0,1,'',0,1);
																		$pdf->SetFont('times','B',11);
																		$pdf->MultiCell(60,5,'Name of the Managing Director:',0,'',0,0);
																		$pdf->SetFont('times','B',11);
																		
																		$pdf->MultiCell(0,5,$record->managing_director,0,'',0,1);
																		$pdf->SetFont('times','B',11);
																		
																		$pdf->MultiCell(60,5,'Telephone No:',0,'',0,0);
																		$pdf->SetFont('times','',11);
																		
																		$pdf->MultiCell(0,5,$record->managing_director_telepone,0,'',0,1);
$pdf->Cell(0,2,'',0,1);
				$pdf->SetFont('times', 'b', 11);				
																if($record->certificate_expiry_statement != ''){
																	
																$pdf->MultiCell(0,5,$record->certificate_expiry_statement,0,'',0,1);
																		//$pdf->Cell(0,5,$record->certificate_expiry_statement,0,1);
																
																	}else{
																		$pdf->Cell(0,5,'This license is valid until '.formatDateRpt($record->expiry_date),0,1);
																
																		
																	}
																//$pdf->Cell(0,5,'This license is valid until '.formatDateRpt($record->expiry_date),0,1);
																	$pdf->SetFont('times','BI',11);
$pdf->Cell(0,2,'',0,1);
																		$pdf->Cell(0,5,'N.B',0,0);
																$pdf->ln();$pdf->SetFont('times','I',11);
																$this->getCertificateRegistrationConditions($record,$pdf);
																$pdf->ln();
$pdf->SetFont('times','B',11);
																		
																$pdf->Cell(0,5,'Done at Kigali on : '.formatDateRpt($record->permit_issue_date),0,1);
																		
																$permit_signitory = '';
																		$title= 'ACTING';
																		$title= '';
																		$approved_by = '';
																		
																		$this->getCertificateSignatoryDetail($record,$pdf);
																		
																	//	$pdf->Output();	
																if($permit_previewoption =='notify'){
																
																	$pdf->Output($upload_directory, "F"); 
																}
																else{
																	$pdf->OutPut('Premises Certificate.pdf');
																
																}
														}
										


												 
											}
												
        }else{
            return "Set rejection letter";
        }
        
				
		} catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}

		//	print_r($res);
		//	exit();
			//exit();
       // return response()->json($res);
		
		
		
		
	}function getPremisesCertificateHeader($pdf,$code){
										// add a page
										$pdf->AddPage();
										$pdf->SetLineWidth(0.4);
										$pdf->SetLineWidth(1.2);
										$pdf->SetLineWidth(0.4);
										$pdf->setMargins(20,30,20,true);
										$template_url = base_path('/');
										
										$pdf->setPageMark();
										$pdf->SetLineWidth(0.4);
									
	}
	function funcPremisesGenerateQrCode($row,$pdf){
		
								$data = url('/').'/api/permitValidation?application_code='.$row->application_code.'&module_id='.$row->module_id;
								 $styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
									
								$template_url = getcwd();
								$qrc_code = $template_url . '/resources/images/qrc_code.jpg';
								$width = 16;
								$height = 16;
								
								$qr_codex = 150;
								$qr_codey = 210;
	$qr_codey = $pdf->GetY();
								$pdf->write2DBarcode($data, 'QRCODE,H', $qr_codex+25,$qr_codey-15 , $width, $height);
							//   $pdf->Image($qrc_code,$qr_codex+$width-4,$qr_codey,$width-3,$height-4);
								
		
	}
	public function printDisposalCertificate($application_code){
									
					$logo=getcwd().'/assets/images/logo.jpg';
					
					
					$records = DB::table('tra_disposal_applications as t1')
										->join('wb_trader_account as t2', 't1.applicant_id', 't2.id')
										->leftJoin('par_districts as t7', 't7.id', 't2.district_id')
										->leftJoin('par_regions as t3', 't2.region_id', 't3.id')
										->leftJoin('par_currencies as t4', 't1.currency_id', 't4.id')
										->leftJoin('par_weights_units as t5', 't1.weights_units_id', 't5.id')
										->leftJoin('par_currencies as t8', 't8.id', 't1.currency_id')
										->join('tra_approval_recommendations as t6', 't1.application_code', 't6.application_code')
										->leftJoin('users as t17', 't6.permit_signatory', '=', 't17.id')
										->select(DB::raw("t4.name as currency,total_weight,t8.name as currency_name, t6.permit_signatory,concat(decrypt(t17.first_name),' ',decrypt(t17.last_name)) as permit_signatoryname, market_value, t5.name as weights_units ,t2.name as applicant,t7.name as district_name, t2.physical_address, t3.name as region_name,t6.approval_date, t2.postal_address, t1.*, t6.decision_id"))
										->where(array('t1.application_code'=>$application_code))
										->first();
				
					if($records){
						$row = $records;
						if($row->decision_id == 1){
								$record = $records;
								$org_info = $this->getOrganisationInfo();
												
								$pdf = new PdfDisposalProvider();
								$this->getCertificateHeader($pdf, '');
											
								$this->funcGenerateQrCode($record,$pdf);
												
											
											$logo = getcwd() . '/resources/images/org-logo.png';
											$pdf->SetFont('times','B',9);
											$pdf->Cell(0,1,'',0,1);
											//$pdf->Image($logo, 86, 18, 40, 35);
											
											
											$pdf->Cell(0,21,'',0,1);
											$pdf->Cell(0,5,'P.O. Box '.$org_info->postal_address.' '.$org_info->region_name,0,1);
											$pdf->Cell(0,5,$org_info->email_address,0,1);
											$pdf->Cell(0,5,$org_info->website,0,1);
											$pdf->ln();
										
								$reference_no  = $records->tracking_no ;
								$applicant_name   = $records->applicant ;
								$district_name  = $records->district_name ;
								$region_name  = $records->region_name ;
								$physical_address  = $records->physical_address ;
								$pdf->Cell(0,5,'',0,1,'C');
								$pdf->SetFont('','BI',10);
								$pdf->Cell(40,5,'Ref No: '.$reference_no,0,0);
								$pdf->Cell(0,5,'Date: '.date('jS F, Y',strtotime($row->approval_date)),0,1,'R');
								$pdf->ln();$pdf->SetFont('','B',12);
								
								$pdf->MultiCell(0,5,'CERTIFICATE OF SAFE DISPOSAL OF SUBSTANDARD, FALSIFIED AND EXPIRED REGULATED PRODUCTS',0,'C',0,1);
								
								$pdf->ln();$pdf->SetFont('','',10);
								$destruction_startdate = formatDaterpt($row->destruction_startdate);
								$destruction_enddate = formatDaterpt($row->destruction_enddate);
								if($destruction_startdate == $destruction_enddate){
									$date_of_destruction =  date('jS F, Y',strtotime($row->destruction_enddate));
								}
								else{
									$date_of_destruction =   date('jS F, Y',strtotime($row->destruction_startdate)).' to '.  date('jS F, Y',strtotime($row->destruction_enddate));
								}
								$text= "Reference is made to the Law Nº 003/2018 of 09/02/2018 establishing Rwanda Food and Drugs Authority and determining its mission, organization and functioning especially in its article 8; and considering the provisions of the Law No 47/2012 of 14/01/2013 relating to the regulation and inspection of food and pharmaceutical products especially in its article 38;.\n";
								$pdf->setCellHeightRatio(2);
								$pdf->writeHTML($text, true, false, false, false, 'J');
		
								$methodsof_destructionsdata = DB::table('tra_methodsof_destructions as t1')
																		->join('par_destruction_methods as t2', 't1.destructionmethod_id', 't2.id')
																		->select('t2.name as disposal_method')

																		->where(array('application_code'=>$application_code));
										$methods = '';								
								if($methodsof_destructionsdata->get()){
									$i = 1;
									$totals = $methodsof_destructionsdata->count();
										$results =$methodsof_destructionsdata->get();
									
									foreach($results as $rows){
										
										if($totals == $i && $i != 1){
											$methods .= ' and '.$rows->disposal_method;
										}
										else{
											if(($i+1) == $totals ){
												$methods .= $rows->disposal_method;
											}
											else{
												
												$methods .= $rows->disposal_method.',';
											}
											
										}
										$i++;
									}
									
								}
							$destruction_sites = DB::table('tra_destruction_exercisesites as t1')
																		->join('par_disposaldestruction_sites as t2', 't1.destruction_site_id', 't2.id')
																		->select('t2.name as destruction_site')

																		->where(array('application_code'=>$application_code));
										$destruction_site  = '';								
								if($destruction_sites->get()){
									$i = 1;
									$totals = $destruction_sites->count();
										$results =$destruction_sites->get();
									
									foreach($results as $rows){
										
										if($totals == $i && $i != 1){
											$destruction_site .= ' and '.$rows->destruction_site;
										}
										else{
											if(($i+1) == $totals ){
												$destruction_site .= $rows->destruction_site;
											}
											else{
												
												$destruction_site .= $rows->destruction_site.',';
											}
											
										}
										$i++;
									}
									
								}
							
								$records = DB::table('tra_disposal_inspectors as t1')
								->join('par_disposal_inspectors_titles as t2', 't1.inspectors_title_id', '=', 't2.id')
								->select(DB::raw("count(t1.id) as counter, t2.name as title"))
								->where(array('t1.application_code' => $application_code))
								->groupBy('t2.id');
		$witness = '';
								if(	$records->get()){
									$i = 1;
									
									
									$totals = $records->count();
									$records = $records->get();
									foreach($records as $rows){
										$counter = '';
										if($rows->counter > 1){
											$counter = $rows->counter.' ';
										}
										
										if($totals == $i && $i != 1){
											$witness .= ' and '.$counter.$rows->title;
										}
										else{
											if(($i+1) == $totals ){
												$witness .= $counter.$rows->title;
											}else{
												$witness .= $counter.$rows->title.', ';
											}
										}
										$i++;
									}
									
								}//destruction_site
								$weight_consignement = $row->total_weight.' '. $row->weights_units;
								$market_value = formatMoney($row->market_value).' '. $row->currency_name;
 
								$pdf->Cell(0,2,'',0,1);
								$text2 = "Rwanda FDA, hereby certifies the disposal of substandard/ falsified/ expired products being the property of the company named <b>".$applicant_name."</b>.located in <b>".$region_name."</b/> Province, <b>".$district_name."</b> District, <b>".$physical_address."</b> which took place on <b>".$date_of_destruction."</b>.\n";
								$pdf->writeHTML($text2, true, false, false, false, 'J');
								$pdf->Cell(0,2,'',0,1);
								$text3 = "The annexed consignment was destroyed by <b>".$methods ."</b>(method) at <b>".$destruction_site."</b>(location/site) under the witness and supervision of (Rwanda FDA Inspectors, and others if any) as specified in the attached disposal form. The weight of the consignment disposed was <b>".$weight_consignement."</b> and its market value was <b>".$market_value."</b>.\n";
								$pdf->writeHTML($text3, true, false, false, false, 'J');
								$pdf->SetFont('','B',10);
								$pdf->ln();
								$pdf->Cell(0,5,'Done at Kigali on : '.formatDateRpt($record->approval_date),0,1);
												
								
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
												
								$this->getCertificateSignatoryDetail($record,$pdf);
																

						}else{

						}
					}
						$pdf->Output('Disposal Certificate.pdf');


		
		
		
		
	}
	public function generateLetterOfREjection($application_code,$req,$module_id)
{
	try{

																	$application_code = $req->application_code;
																	
																	$query_id = $req->query_id;
																	$module_data = getTableData('modules', ['id'=>$module_id]);
																	if(!isset($module_data->table_name)){
																		return "Module details not found";
																	}
																	$app_data = DB::table($module_data->table_name.' as t1')
																				->join('wb_trader_account as t2', 't1.applicant_id', 't2.id')
																				->leftJoin('par_countries as t3', 't2.country_id', 't3.id')
																				->leftJoin('par_regions as t4', 't2.region_id', 't4.id')
																				->leftJoin('sub_modules as t5', 't1.sub_module_id', 't5.id')
																				->leftJoin('tra_apprejprovisional_recommendation as t7', 't1.application_code', 't7.application_code')
																				->where('t1.application_code', $application_code);
																	
																	if($module_id ==1){
																		$app_data->join('tra_product_information as t6', 't1.product_id','t6.id')->select('t7.created_on as approval_date', 't7.reason_for_rejection','t1.applicant_id','t5.title as application_title','t1.reference_no', 't1.tracking_no', 't2.*', 't3.name as country_name', 't4.name as region_name', 't6.brand_name');
																	}
																	else{
																		$app_data->select('t7.created_on as approval_date', 't7.reason_for_rejection','t1.applicant_id','t5.title as application_title','t1.reference_no', 't1.tracking_no', 't2.*', 't3.name as country_name', 't4.name as region_name');
																	}
																	$app_data = $app_data->first();
																
																	if(!$app_data){
																		return "Application details not found";
																	}
																	
																	$org_info = $this->getOrganisationInfo();
																	$pdf = new PdfLettersProvider();
																	$pdf->AddPage();
																	//$pdf->SetLineWidth(0.4);
																	//$pdf->Rect(3,3,204,285);
																		$template_url = base_path('/');
																		$pdf->setSourceFile($template_url."resources/templates/certificate_template.pdf");
																		// import page 1
																		$tplId = $pdf->importPage(1);	
																	
																		// use the imported page and place it at point 10,10 with a width of 100 mm
																		$pdf->useTemplate($tplId,0,0);
																		$pdf->setPageMark();
																	//use template 
																	
																	$logo = getcwd() . '/resources/images/zamra-logo.png';
																	$pdf->Image($logo, 86, 18, 40, 35);
																	
																	$pdf->SetFont('times','B',9);
																	
																	
																	$pdf->Cell(0,4,'FORM II',0,1,'R');
																	$pdf->Cell(0,4,'(Regulation 3)',0,1,'R');
																	$pdf->SetFont('times','B',13);
																	$pdf->Cell(0,25,'',0,1);
																	$pdf->Cell(0,15,'',0,1);
																	$pdf->Cell(0,4,$org_info->org_name,0,1,'C');
																	$pdf->SetFont('times','B',11);
																	$pdf->Cell(0,4,'The Medicines and Allied Substances Act, 2013',0,1,'C');
																	
																	
																	$pdf->Cell(0,4,'(Act No. 3 of 2013)',0,1,'C');
																	$pdf->SetFont('times','B',12);
																	$pdf->Cell(0,8,'The Medicines and Allied Substances',0,1,'C');
																	$pdf->SetFont('times','B',11);
																	if($module_id == 4){
																			$regulation_title = "The Medicines and Allied Substances (Importation and Exportaion) Regulations, 2017";
																			
																	}
																	else if($module_id == 1){
																		$regulation_title = "(Marketing Authorisation of Medicines) Regulations, 2019";
																	
																	}
																	$pdf->Cell(0,4,$regulation_title,0,1,'C');

																	$pdf->Cell(0,5,'',0,1);
																	$pdf->SetFont('times','B',13);
																	//application_title
																	$title = "NOTICE OF REJECTION OF ".$app_data->application_title;

																	$pdf->Cell(0,5,strtoupper($title),0,1,'C');
																	$pdf->SetFont('times','B',10);
																	
																	$application_no = '';

																	if($app_data->tracking_no != ''){

																		$application_no = 	$app_data->tracking_no;
																	}
																	if($app_data->reference_no != ''){

																		$application_no = 	$app_data->reference_no;
																	}
																	$pdf->Cell(0,10,'Application No:'.$application_no,0,1, 'R');
																		// $pdf->MultiCell(0,10,'Application Reference:<u>'.$app_data->tracking_no.'</u>',0,'R',0,1,'','',true,0,true);
																	$data = '{"tracking_no":'.$app_data->tracking_no.',"module_id":'.$module_id.',"application_code":'.$application_code.'}';

																	$styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
																	// QRCODE,H : QR-CODE Best error correction
																	$pdf->write2DBarcode($data, 'QRCODE,H', 178, 28, 16, 16);
																	$pdf->SetFont('times','',12);
																	//Letter heading 
																	$pdf->Cell(0,8,'To:',0,1);
																	$pdf->Cell(0,8,$app_data->name.',',0,1);
																	
																	$pdf->Cell(0,8,$app_data->physical_address.',',0,1);
																	$pdf->Cell(0,8,$app_data->postal_address.',',0,1);
																	$pdf->Cell(0,8,$app_data->region_name." ".$app_data->country_name,0,1);
																	
																	$pdf->SetFont('times','',11);
																	$pdf->ln();
																		
																	if($module_id ==1){

																		$template = "IN THE MATTER OF ".$application_no.' '.$app_data->brand_name." you are notified that your application for (3) a marketing authorisation/renewal of a marketing authorisation has been rejected by the Authority on the following grounds:";
																
																	}
																	else{
																		$template = "IN THE MATTER OF ".$application_no." you are notified that your application for ".$app_data->application_title." has been rejected by the Authority on the following grounds:";
																

																	}
																	$reason_for_rejection = $app_data->reason_for_rejection;
																	if($reason_for_rejection == ''){
																		$data = DB::connection('portal_db')->table('wb_rejection_remarks')->where('application_code',$application_code)->first();
																		$reason_for_rejection = $data->remark;
																		$pdf->setCellHeightRatio(2);
																		$pdf->WriteHTML($template, true, false, true, true);
																		$pdf->WriteHTML($reason_for_rejection, true, false, true, true);
																		$pdf->SetFont('times','B',12);
																	}else{
																		
																		$pdf->setCellHeightRatio(2);
																		$pdf->WriteHTML($template, true, false, true, true);
																		$pdf->WriteHTML($reason_for_rejection, true, false, true, true);
																		$pdf->SetFont('times','B',12);
																		
																		$dt =strtotime($app_data->approval_date); //gets dates instance
																		$year = date("Y", $dt);
																		$month = date("m", $dt);
																		$day = date("d", $dt);
																		
																			$pdf->Cell(0, 0,'Dated this '.$day.' day of '.$month.', '.$year, 0, 1, '', 0, '', 3);

																				$startY = $pdf->GetY();
																				$startX =$pdf->GetX();
																				$signiture = getcwd() . '/backend/resources/templates/signatures_uploads/dg_sinatory.png';
																				$pdf->Image($signiture,$startX+75,$startY-7,30,12);
																				$pdf->Cell(0, 0, '___________________________',0,1,'C');
																				$pdf->Cell(0, 0, 'AG. Director-General',0,1,'C');
																	}
																	
																	
																			$pdf->Output('Letter of Rejection '.$application_no.'.pdf');
																			
																}catch (\Exception $exception) {
																	$res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
											
															} catch (\Throwable $throwable) {
																	$res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1),explode('\\', __CLASS__), \Auth::user()->id);
															}
															return response()->json($res);									
																	
}
	function getCertificateRegistrationConditions($row,$pdf){
		$module_id = $row->module_id;
		$section_id = $row->section_id;
		$template_url = base_path('/');
				$pdf->setSourceFile($template_url."resources/templates/certificate_template.pdf");
				// import page 1
				$tplId = $pdf->importPage(1);	
				$pdf->useTemplate($tplId,0,0);
		$where = array('module_id'=>$module_id, 'section_id'=>$section_id);
		
		$records = DB::table('par_certificate_conditions')->where($where)->orderBy('order_no')->get();
			
		if($records){
			
			foreach($records as $rec){
				
					$pdf->Cell(8,2,$rec->order_no.'. ',0,0);
					$pdf->MultiCell(0,5,$rec->certificate_conditions." .\n",0,'J',0,1);
				
			}
			
		}
	}
	
	function getImportCertificateSignatoryDetail($record ,$pdf){
		
								
								
										$director_details = getPermitSignatoryDetails();
								
								
										$dg_signatory = $director_details->director_id;
										$title_name = $director_details->title_name;
										$director = $director_details->director;
										$is_acting_director = $director_details->is_acting_director;
										
										$approved_by = $record->permit_signatory;
										$permit_signatoryname = $record->permit_signatoryname;
										
										if($dg_signatory != $approved_by){
											$signatory = $approved_by;
										}
										else{
											$signatory = $dg_signatory;
										}
										//manually code but will be changed 
										$signatory = 1701;
										//permit_approval permit_signatory 
										$signature = getUserSignatureDetails($signatory);
										//kXjKa1684726833.png
										$pdf->ln();
											$pdf->SetFont('times','BI',10);
										 $pdf->Cell(0,6,'By Authority Delegation', 0,1,'');
										 $pdf->Cell(0,6,'', 0,1,'');
										 
								$startY = $pdf->GetY();
								$startX =$pdf->GetX();
										 $logo = getcwd() . '/resources/images/org_stamp.png'; 
											$pdf->SetFont('times','B',9);
											$startY = $pdf->GetY();
											$startX =$pdf->GetX();
										$signature = getcwd() . '/resources/images/signs/'.$signature;
										///opt/lampp/htdocs/mis/resources/images/signs/kXjKa1684726833.png
										$pdf->Image($signature,$startX+2,$startY-8,35,15);
										$startY = $pdf->GetY();
											$startX =$pdf->GetX();
											$pdf->Image($logo,$startX+30,$startY-8,20,20);
											
										$pdf->ln();
									
										$pdf->ln();
										 	$pdf->SetFont('times','B',10);
										 $pdf->Cell(0,4,'Theobald HABIYAREMYE', 0,1,'');
										 $pdf->Cell(0,4,'Division Manager, Food and Drugs Import & Export Control', 0,1,'');
										

		
	}
	function getCertificateSignatoryDetail($record ,$pdf){
		
			$pdf->ln();
								$startY = $pdf->GetY();
								$startX =$pdf->GetX();
								
								
								$director_details = getPermitSignatoryDetails();
								
								
										$dg_signatory = $director_details->director_id;
										$title_name = $director_details->title_name;
										$director = $director_details->director;
										$is_acting_director = $director_details->is_acting_director;
										
										$approved_by = $record->permit_signatory;
										$permit_signatoryname = $record->permit_signatoryname;
										
										if($dg_signatory != $approved_by){
											$signatory = $approved_by;
										}
										else{
											$signatory = $dg_signatory;
										}
										//permit_approval permit_signatory 
										$signature = getUserSignatureDetails($signatory);
										
									
										$pdf->ln();
										$pdf->ln();
										$pdf->Cell(0,4,'Signature', 0,1,'');
										// $pdf->Cell(0,8,'...............................................................', 0,1,'');
										 
										$title = "Director General";
										if($approved_by != ''){
											if($dg_signatory != $approved_by){
												$title = ' '.$title;
											}else{
												$permit_signatoryname = $director;
												if($is_acting_director ==1){
													$title = $title;
												}
											}
											//$pdf->Cell(0,5,'SIGNATURE', 0,1,'');
											$pdf->Cell(0,5,$title_name.' '.strtoupper($permit_signatoryname), 0,1,'');
											 $pdf->Cell(0,5,$title, 0,0,'');
											
										}
										

		
	}
	
public function medicalDevicesProductNotifications($application_code,$row){
		try{
			
						
						$is_provisional =0;
						
						if($row){
							if($row->recommendation_id == 2){
								$is_provisional =1;
							}
							$org_info = $this->getOrganisationInfo();
								
								$pdf = new PdfProvider();
								$this->getCertificateHeader($pdf,'');
								$pdf->SetLineWidth(0.4);
								$pdf->Rect(3,3,204,285);
								$logo = getcwd() . '/resources/images/org-logo.png';
								$pdf->SetFont('times','B',9);
								$pdf->Cell(0,1,'',0,1);
								
								
								$pdf->Cell(0,21,'',0,1);
								
								$pdf->Cell(0,5,'OTIFICATION CERTIFICATE OF MEDICAL DEVICES ',0,1,'C');
								$pdf->SetFont('times','B',10);
								$pdf->ln();
								$act_statement = "Made under Law No. 003/2018 of 09/02/2018 establishing the Rwanda FDA and determining its mission, organization and functioning in its article 9 paragraph 2 and regulations No: DFAR/HMDAR/TRG/002 in its article 13.\n";
								
								$pdf->MultiCell(0,5,$act_statement,0,'J',0,1);
							
								$pdf->SetFont('times','B',10);
								$pdf->Cell(0,3,'',0,1);
								
								$pdf->SetFont('times','',10);
									
                                if( $is_provisional == 1){
                                   // $pdf->Cell(70,8,0,0);
									$pdf->MultiCell(70,8,'Provisional registration number of the medicine',0,'',0,0);
                                }
                                else{
                                    $pdf->Cell(70,8,'Notification  number:',0,0);
								
                                }
								$data = "Product Notification : Certificate No:".$row->certificate_no."; Brand Name:".$row->brandName.";Expiry Date:".formatDate($row->expiry_date);
								 $styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
								// QRCODE,H : QR-CODE Best error correction
								
								$pdf->write2DBarcode($data, 'QRCODE,H', 178, 28, 16, 16);
							   
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,$row->certificate_no,0,1);
								//$pdf->Cell(0,1,'',0,2);
								$pdf->SetFont('times','',10);
								
								//Brand Name
								$pdf->MultiCell(0,8,"This is to certify that the Medical Device described below has been notified in Rwanda subject to conditions indicated at the back of this certificate.\n",0,'J',0,1);
							
								$pdf->SetFont('times','',10);
								
								//$pdf->Cell(70,10,0,0,'L');
								$pdf->MultiCell(70,8,'Device’s name: ',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->brandName),0,'',0,1);
							
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Class of the device:  ',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->classification_name),0,'',0,1);
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Brief intended use of the device:  ',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->intended_use),0,'',0,1);
							
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Pack size and Packaging type:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(69,10,strtoupper($row->description_ofpackagingmaterial),0,'',0,0);
								//$pdf->Cell(70,5,'',0,0,'L');
								
								$pdf->SetFont('times','B',10);
								$packaging = '';
											$container_name = '';
											$retail_packaging_size = '';
								
								$pdf->SetFont('times','',10);
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Shelf life of medicine in months and Storage statement:',0,'',0,0); ;
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,8,strtoupper($row->shelf_life).', '.strtoupper(html_entity_decode(($row->storage_condition))),0,'',0,1); ;
								
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(70,10,'Name and address of the Marketing Authorization Holder: ',0,'',0,0);
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,10,strtoupper($row->trader_name),0,'',0,1);
								//$pdf->Cell(100,12,ucwords($applicantName),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								//Manufacturer
								 $manrow = DB::table('tra_product_manufacturers as t1')
									->select('t1.*', 't2.email_address','t1.id as manufacturer_id', 't2.physical_address', 't2.name as manufacturer_name','t2.postal_address', 't3.name as country_name', 't4.name as region_name', 't5.name as district_name')
									->join('tra_manufacturers_information as t2', 't1.manufacturer_id', '=', 't2.id')
									->join('par_countries as t3', 't2.country_id', '=', 't3.id')
									->leftJoin('par_regions as t4', 't2.region_id', '=', 't4.id')
									->leftJoin('par_districts as t5', 't2.district_id', '=', 't5.id')
									->leftJoin('par_manufacturing_roles as t6', 't1.manufacturer_role_id', '=', 't6.id')
									->where(array('t1.product_id' => $row->product_id, 'manufacturer_type_id' => 1))
									->first();
									
									$manufacturer_name='';
									$man_postal_address='';
									$man_physical_address='';
									$man_countryName='';
									$man_districtName='';
									$man_regionName = '';
									
								if($manrow){
									$manufacturer_name=$manrow->manufacturer_name;
									$man_postal_address=$manrow->postal_address;
									$man_physical_address=$manrow->physical_address;
									
									$man_countryName= $manrow->country_name;
									$man_regionName = $manrow->region_name;
								}
								
								//Manufacturer sql 
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(70,10,'Name and Address of the Manufacturer:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($manufacturer_name),0,'',0,1);
								
								$pdf->SetFont('times','',10);
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($man_postal_address),0,'',0,1);
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($man_physical_address),0,'L');
								
								if($man_regionName!=''){
									$pdf->Cell(70,5,'',0,0,'L');
									$pdf->SetFont('times','B',10);
									$pdf->Cell(100,5,strtoupper($man_regionName),0,1,'L'); 
								}
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,5,strtoupper($man_countryName),0,1,'L'); 
								 
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Name and address of the Local Technical Representative: ',0,'',0,0);
								
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,8,strtoupper($row->localAgentName),0,'',0,1);
								$pdf->MultiCell(70,8,'',0,0);
								$pdf->MultiCell(0,8,strtoupper($row->local_agent_address),0,'',0,1);
								//$pdf->Cell(100,8,strtoupper($localAgentName),0,1,'L'); 
								
								$pdf->SetFont('times','',10);
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(70,8,'Issued on:',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,ucwords(date('F d, Y ',strtotime($row->certificate_issue_date))),0,1,'L'); 
								
								
								$pdf->SetFont('times','',10);
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(70,8,'Expires on:',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,ucwords(date('F d, Y ',strtotime($row->expiry_date))),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(0,2,'',0,1);
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
								$this->getCertificateSignatoryDetail($row,$pdf);
								$pdf->AddPage();
								$pdf->SetFont('times','B',9);
								
								
								$pdf->Cell(0,5,'Conditions for Medical Device Notification:',0,1);
								$pdf->SetFont('times','',11);
								$pdf->Cell(0,2,'',0,1);
								
								$this->getCertificateRegistrationConditions($row,$pdf);
								
								$pdf->Output();
						}	
							
								
			
			
		} catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
	}
public function medicalDevicesProductRegistration($application_code,$row){
		try{
			
						
						$is_provisional =0;
						
						if($row){
							if($row->recommendation_id == 2){
								$is_provisional =1;
							}
							$org_info = $this->getOrganisationInfo();
								
								$pdf = new PdfProvider();
								$this->getCertificateHeader($pdf,'');
								$pdf->SetLineWidth(0.4);
								$pdf->Rect(3,3,204,285);
								$logo = getcwd() . '/resources/images/org-logo.png';
								$pdf->SetFont('times','B',9);
								$pdf->Cell(0,1,'',0,1);
								
								
								$pdf->Cell(0,21,'',0,1);
								
								$pdf->Cell(0,5,'REGISTRATION CERTIFICATE OF MEDICAL DEVICES ',0,1,'C');
								$pdf->SetFont('times','B',10);
								$pdf->ln();
								$act_statement = "Made under Law No. 003/2018 of 09/02/2018 establishing the Rwanda FDA and determining its mission, organization and functioning in its article 9 paragraph 2 and regulations No: DFAR/HMDAR/TRG/002 in its article 13.\n";
								
								$pdf->MultiCell(0,5,$act_statement,0,'J',0,1);
							
								$pdf->SetFont('times','B',10);
								$pdf->Cell(0,3,'',0,1);
								
								$pdf->SetFont('times','',10);
									
                                if( $is_provisional == 1){
                                   // $pdf->Cell(70,8,0,0);
									$pdf->MultiCell(70,8,'Provisional registration number of the medicine',0,'',0,0);
                                }
                                else{
                                    $pdf->Cell(70,8,'Registration number:',0,0);
								
                                }
								$data = "Product Registration: Certificate No:".$row->certificate_no."; Brand Name:".$row->brandName.";Expiry Date:".formatDate($row->expiry_date);
								 $styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
								// QRCODE,H : QR-CODE Best error correction
								
								$pdf->write2DBarcode($data, 'QRCODE,H', 178, 28, 16, 16);
							   
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,$row->certificate_no,0,1);
								//$pdf->Cell(0,1,'',0,2);
								$pdf->SetFont('times','',10);
								
								//Brand Name
								$pdf->MultiCell(0,8,"This is to certify that the Medical Device described below has been registered in Rwanda subject to conditions indicated at the back of this certificate.\n",0,'J',0,1);
							
								$pdf->SetFont('times','',10);
								
								//$pdf->Cell(70,10,0,0,'L');
								$pdf->MultiCell(70,8,'Device’s name: ',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->brandName),0,'',0,1);
							
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Class of the device:  ',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->classification_name),0,'',0,1);
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Brief intended use of the device:  ',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->intended_use),0,'',0,1);
							
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Pack size and Packaging type:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->SetFont('times','B',10);
								$packaging = '';
											$container_name = '';
											$retail_packaging_size = '';
								$packaging_data = DB::table('tra_product_packaging as t1')
											->select(DB::raw("t1.*, t2.name as container_type, t3.name as container_name, t4.name as container_material, t5.name as closure_materials, t4.name as container_material, t5.name as closure_material, t6.name as seal_type, t7.name as packaging_units, CONCAT_WS('X',retail_packaging_size,retail_packaging_size1,retail_packaging_size2,retail_packaging_size3,retail_packaging_size4) as retail_packaging"))
											->leftJoin('par_containers_types as t2', 't1.container_type_id', '=', 't2.id')
											->leftJoin('par_containers as t3', 't1.container_id', '=', 't3.id')
											->leftJoin('par_containers_materials as t4', 't1.container_material_id', '=', 't4.id')
											->leftJoin('par_closure_materials as t5', 't1.closure_material_id', '=', 't5.id')
											->leftJoin('par_seal_types as t6', 't1.seal_type_id', '=', 't6.id')
											->leftJoin('par_packaging_units as t7', 't1.packaging_units_id', '=', 't7.id')
											->where(array('t1.product_id' => $row->product_id))
											->get();
								
								if($packaging_data->count() >0){
								$i = 1;
									foreach($packaging_data as $packaging_rec){
										
											$container_material = $packaging_rec->container_material;
											$container_name = $packaging_rec->container_name;
											
											$retail_packaging_size = $packaging_rec->retail_packaging;
											
											$product_unit = $packaging_rec->unit_pack;		
											if($i != 1){
												$pdf->Cell(69,5,'',0,0);
											}									
											if($product_unit == ''){
											
													$pdf->MultiCell(0,5,strtoupper($container_material).' '.strtoupper($container_name) .' OF '.strtoupper($retail_packaging_size),0,'',0,1);
											}
											else{
												
													$pdf->MultiCell(0,5,strtoupper($container_material).' '.strtoupper($container_name) .' OF '.strtoupper($retail_packaging_size).' X '.strtoupper($product_unit),0,'',0,1);													
																							
											}
											
											$i++;		
									
									}
											
								
								}
								else{
									
											$pdf->MultiCell(0,10,'',0,'',0,1);
											
								}
								$pdf->SetFont('times','',10);
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Shelf life of medicine in months and Storage statement:',0,'',0,0); ;
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,8,strtoupper($row->shelf_life).', '.strtoupper(html_entity_decode(($row->storage_condition))),0,'',0,1); ;
								
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(70,10,'Name and address of the Marketing Authorization Holder: ',0,'',0,0);
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,10,strtoupper($row->trader_name),0,'',0,1);
								//$pdf->Cell(100,12,ucwords($applicantName),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								//Manufacturer
								 $manrow = DB::table('tra_product_manufacturers as t1')
									->select('t1.*', 't2.email_address','t1.id as manufacturer_id', 't2.physical_address', 't2.name as manufacturer_name','t2.postal_address', 't3.name as country_name', 't4.name as region_name', 't5.name as district_name')
									->join('tra_manufacturers_information as t2', 't1.manufacturer_id', '=', 't2.id')
									->join('par_countries as t3', 't2.country_id', '=', 't3.id')
									->leftJoin('par_regions as t4', 't2.region_id', '=', 't4.id')
									->leftJoin('par_districts as t5', 't2.district_id', '=', 't5.id')
									->leftJoin('par_manufacturing_roles as t6', 't1.manufacturer_role_id', '=', 't6.id')
									->where(array('t1.product_id' => $row->product_id, 'manufacturer_type_id' => 1))
									->first();
									
									$manufacturer_name='';
									$man_postal_address='';
									$man_physical_address='';
									$man_countryName='';
									$man_districtName='';
									$man_regionName = '';
									
								if($manrow){
									$manufacturer_name=$manrow->manufacturer_name;
									$man_postal_address=$manrow->postal_address;
									$man_physical_address=$manrow->physical_address;
									
									$man_countryName= $manrow->country_name;
									$man_regionName = $manrow->region_name;
								}
								
								//Manufacturer sql 
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(70,10,'Name and Address of the Manufacturer:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($manufacturer_name),0,'',0,1);
								
								$pdf->SetFont('times','',10);
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($man_postal_address),0,'',0,1);
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($man_physical_address),0,'L');
								
								if($man_regionName!=''){
									$pdf->Cell(70,5,'',0,0,'L');
									$pdf->SetFont('times','B',10);
									$pdf->Cell(100,5,strtoupper($man_regionName),0,1,'L'); 
								}
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,5,strtoupper($man_countryName),0,1,'L'); 
								 
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Name and address of the Local Technical Representative: ',0,'',0,0);
								
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,8,strtoupper($row->localAgentName),0,'',0,1);
								$pdf->MultiCell(70,8,'',0,0);
								$pdf->MultiCell(0,8,strtoupper($row->local_agent_address),0,'',0,1);
								//$pdf->Cell(100,8,strtoupper($localAgentName),0,1,'L'); 
								
								$pdf->SetFont('times','',10);
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(70,8,'Issued on:',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,ucwords(date('F d, Y ',strtotime($row->certificate_issue_date))),0,1,'L'); 
								
								
								$pdf->SetFont('times','',10);
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(70,8,'Expires on:',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,ucwords(date('F d, Y ',strtotime($row->expiry_date))),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(0,2,'',0,1);
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
								
								
								$this->getCertificateSignatoryDetail($row,$pdf);
								
								$pdf->AddPage();
								$pdf->SetFont('times','B',9);
								
								
								$pdf->Cell(0,5,'Conditions for Medical Device Registration:',0,1);
								$pdf->SetFont('times','',11);
								$pdf->Cell(0,2,'',0,1);
								
								$this->getCertificateRegistrationConditions($row,$pdf);
								
								$pdf->Output();
						}	
							
								
			
			
		} catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
	}

public function cosmeticssProductRegistrationCertficiate($application_code,$row){
		try{
			
						
						$is_provisional =0;
						
						if($row){
							if($row->recommendation_id == 2){
								$is_provisional =1;
							}
							$org_info = $this->getOrganisationInfo();
								
								$pdf = new PdfProvider();
								$this->getCertificateHeader($pdf,'');
								$pdf->SetLineWidth(0.4);
								$pdf->Rect(3,3,204,285);
								$logo = getcwd() . '/resources/images/org-logo.png';
								$pdf->SetFont('times','B',9);
								$pdf->Cell(0,1,'',0,1);
								
								
								$pdf->Cell(0,21,'',0,1);
								
								$pdf->Cell(0,5,strtoupper($row->certificate_title),0,1,'C');
								$pdf->SetFont('times','B',10);
								$pdf->ln();
								$act_statement = "Made under Law No. 003/2018 of 09/02/2018 establishing the Rwanda FDA and determining its mission, organization and functioning in its articles 3 and 8, and regulation No. CBD/TRG/010. The Authority here issues .\n";
								
								$pdf->MultiCell(0,5,$act_statement,0,'J',0,1);
							
								$pdf->SetFont('times','B',10);
								$pdf->Cell(0,3,'',0,1);
								
								$pdf->SetFont('times','',10);
									
                                if( $is_provisional == 1){
                                   // $pdf->Cell(70,8,0,0);
									$pdf->MultiCell(70,8,'Provisional registration number of the medicine',0,'',0,0);
                                }
                                else{
                                    $pdf->Cell(70,8,'Registration number:',0,0);
								
                                }
								$data = "Product Registration: Certificate No:".$row->certificate_no."; Brand Name:".$row->brandName.";Expiry Date:".formatDate($row->expiry_date);
								 $styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
								// QRCODE,H : QR-CODE Best error correction
								
								$pdf->write2DBarcode($data, 'QRCODE,H', 178, 28, 16, 16);
							   
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,$row->certificate_no,0,1);
								//$pdf->Cell(0,1,'',0,2);
								$pdf->SetFont('times','',10);
								
								//Brand Name
								$pdf->MultiCell(0,8,"This is to certify that the product described below has been registered in Rwanda subject to the conditions indicated on the back of this certificate.\n",0,'J',0,1);
							
								$pdf->SetFont('times','',10);
								
								//$pdf->Cell(70,10,0,0,'L');
								$pdf->MultiCell(70,8,'Brand Name: ',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->brandName),0,'',0,1);
								//$pdf->Cell(100,8,strtoupper($brandName),0,1,'L'); //Todo: Add Dosage Form
								
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(70,12,'Name of the Active ingredient(s) and Strength: ',0,'',0,0);
								
								 $ingred_rows = DB::table('tra_product_ingredients as t1')
									->select('t1.*', 't6.name as reason_for_inclusion', 't2.name as ingredient_specification', 't3.name as si_unit', 't4.name as ingredient_name', 't5.name as ingredient_type')
									->leftJoin('par_specification_types as t2', 't1.specification_type_id', '=', 't2.id')
									->leftJoin('par_si_units as t3', 't1.ingredientssi_unit_id', '=', 't3.id')
									->leftJoin('par_ingredients_details as t4', 't1.ingredient_id', '=', 't4.id')
									->leftJoin('par_ingredients_types as t5', 't1.ingredient_type_id', '=', 't5.id')
									->leftJoin('par_inclusions_reasons as t6', 't1.inclusion_reason_id', '=', 't6.id')
									->where(array('t1.product_id' => $row->product_id, 'is_active_reason'=>1))
									->get();			
								
								if($ingred_rows){
									$pdf->SetFont('times','I',10);
									foreach($ingred_rows as $ingred_row){
										
										$ingr_name=$ingred_row->ingredient_name;
										$strength=$ingred_row->strength;
										$pdf->SetFont('times','B',10);
										$pdf->MultiCell(0,5,strtoupper($ingr_name).'  '.strtoupper($strength).' '.strtoupper($ingred_row->si_unit),0,'',0,1);
										$pdf->Cell(70,5,'',0,0,'L');
										
									}
									
								}else{
									$ingr_name='';
									$proportion='';
									$strength='';
									$specification_id=0;
								}
								
								$pdf->ln();
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Intended use of the product: ',0,'',0,0);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(0,8,strtoupper($row->intended_use),0,'',0,1);
							
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(70,8,'Pack size and Packaging type:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(69,10,strtoupper($row->description_ofpackagingmaterial),0,'',0,1);
								//$pdf->Cell(70,5,'',0,0,'L');
								
								$pdf->SetFont('times','',10);
								
								
								$pdf->MultiCell(70,10,'Name of Marketing Authorization Holder:',0,'',0,0);
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,10,strtoupper($row->trader_name),0,'',0,1);
								//$pdf->Cell(100,12,ucwords($applicantName),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								//Manufacturer
								 $manrow = DB::table('tra_product_manufacturers as t1')
									->select('t1.*', 't2.email_address','t1.id as manufacturer_id', 't2.physical_address', 't2.name as manufacturer_name','t2.postal_address', 't3.name as country_name', 't4.name as region_name', 't5.name as district_name')
									->join('tra_manufacturers_information as t2', 't1.manufacturer_id', '=', 't2.id')
									->join('par_countries as t3', 't2.country_id', '=', 't3.id')
									->leftJoin('par_regions as t4', 't2.region_id', '=', 't4.id')
									->leftJoin('par_districts as t5', 't2.district_id', '=', 't5.id')
									->leftJoin('par_manufacturing_roles as t6', 't1.manufacturer_role_id', '=', 't6.id')
									->where(array('t1.product_id' => $row->product_id, 'manufacturer_type_id' => 1))
									->first();
									
									$manufacturer_name='';
									$man_postal_address='';
									$man_physical_address='';
									$man_countryName='';
									$man_districtName='';
									$man_regionName = '';
									
								if($manrow){
									$manufacturer_name=$manrow->manufacturer_name;
									$man_postal_address=$manrow->postal_address;
									$man_physical_address=$manrow->physical_address;
									
									$man_countryName= $manrow->country_name;
									$man_regionName = $manrow->region_name;
								}
								
								//Manufacturer sql 
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(70,10,'Name and Address of the Manufacturer:',0,'',0,0);
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($manufacturer_name),0,'',0,1);
								
								$pdf->SetFont('times','',10);
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($man_postal_address),0,'',0,1);
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,5,strtoupper($man_physical_address),0,'L');
								
								if($man_regionName!=''){
									$pdf->Cell(70,5,'',0,0,'L');
									$pdf->SetFont('times','B',10);
									$pdf->Cell(100,5,strtoupper($man_regionName),0,1,'L'); 
								}
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,5,strtoupper($man_countryName),0,1,'L'); 
								 
								$pdf->SetFont('times','',10);$pdf->MultiCell(70,8,'Name of Local Technical Representative:',0,'',0,0);
								
								
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(0,8,strtoupper($row->localAgentName),0,'',0,1);
								$pdf->Cell(100,8,strtoupper($row->local_agent_address),0,1,'L'); 
								
								$pdf->SetFont('times','',10);
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(70,8,'Issued on:',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,ucwords(date('F d, Y ',strtotime($row->certificate_issue_date))),0,1,'L'); 
								
								
								$pdf->SetFont('times','',10);
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(70,8,'Expires on:',0,0,'L');
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,ucwords(date('F d, Y ',strtotime($row->expiry_date))),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(0,2,'',0,1);
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
								
								
								
								$pdf->Cell(0,2,'',0,1);
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
								
								$this->getCertificateSignatoryDetail($row,$pdf);
								
								$pdf->AddPage();
								$pdf->SetFont('times','B',9);
								
								
								$pdf->Cell(0,5,'Conditions of Registration:',0,1);
								$pdf->SetFont('times','',11);
								$pdf->Cell(0,2,'',0,1);
								
								$this->getCertificateRegistrationConditions($row,$pdf);
								
								$pdf->Output();
						}	
							
								
			
			
		} catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
	}
public function medicinesProductRegistration($application_code,$row){
		try{
			
						
						$is_provisional =0;
						
						if($row){
							if($row->recommendation_id == 2){
								$is_provisional =1;
							}
							$org_info = $this->getOrganisationInfo();
								
								$pdf = new PdfProvider();
								
								$this->getCertificateHeader($pdf,'');
								$pdf->SetFont('times','B',9);
								$pdf->Cell(0,3,'DHT/FMT/042',0,1, 'R');
								$pdf->SetLineWidth(0.4);
								$pdf->Rect(3,3,204,285);
								$logo = getcwd() . '/resources/images/org-logo.png';
								$pdf->SetFont('times','B',9);
								$pdf->Cell(0,1,'',0,1);
								
								
								$pdf->Cell(0,21,'',0,1);
								
								$pdf->Cell(0,5,$row->certificate_title,0,1,'C');
								$pdf->SetFont('times','B',10);
								$pdf->ln();
								$act_statement = "Made under Law No. 003/2018 of 09/02/2018 establishing the Rwanda FDA and determining its mission, organization and functioning in his article 3 and article 8 and regulation No. CBD/TRG/010. The Authority here issues.\n";
								
								$pdf->MultiCell(0,5,$act_statement,0,'J',0,1);
							
								$pdf->SetFont('times','B',10);
								$pdf->Cell(0,3,'',0,1);
								
								$pdf->SetFont('times','',10);
									
                                if( $is_provisional == 1){
                                   // $pdf->Cell(70,8,0,0);
									$pdf->MultiCell(70,8,'Provisional registration number of the medicine',0,'',0,0);
                                }
                                else{
                                    $pdf->Cell(70,8,'Registration number:',0,0);
								
                                }
								$data = "Product Registration: Certificate No:".$row->certificate_no."; Brand Name:".$row->brandName.";Expiry Date:".formatDate($row->expiry_date);
								 $styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
								// QRCODE,H : QR-CODE Best error correction
								
							   
								$pdf->SetFont('times','B',10);
								$pdf->Cell(100,8,$row->certificate_no,0,1);
								//$pdf->Cell(0,1,'',0,2);
								$pdf->SetFont('times','',10);
								
								//Brand Name
								$pdf->MultiCell(0,8,"This is to certify that the medicine described below has been registered in Rwanda subject to conditions indicated at the back of the this certificate:\n",0,'J',0,1);
							
								$pdf->SetFont('times','B',10);
								
								//$pdf->Cell(70,10,0,0,'L');
								$pdf->MultiCell(70,8,'Trade name of the medicine:',0,'',0,0);
								$pdf->SetFont('times','',10);
								
								$pdf->MultiCell(0,8,($row->brandName),0,'',0,1);
								//$pdf->Cell(100,8,strtoupper($brandName),0,1,'L'); //Todo: Add Dosage Form
								
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(70,12,'Name of the Active immunogenic ingredient(s) and Strength:',0,'',0,0);
								/*
								 $ingred_rows = DB::table('tra_product_ingredients as t1')
									->select('t1.*', 't6.name as reason_for_inclusion', 't2.name as ingredient_specification', 't3.name as si_unit', 't4.name as ingredient_name', 't5.name as ingredient_type')
									->leftJoin('par_specification_types as t2', 't1.specification_type_id', '=', 't2.id')
									->leftJoin('par_si_units as t3', 't1.ingredientssi_unit_id', '=', 't3.id')
									->leftJoin('par_ingredients_details as t4', 't1.ingredient_id', '=', 't4.id')
									->leftJoin('par_ingredients_types as t5', 't1.ingredient_type_id', '=', 't5.id')
									->leftJoin('par_inclusions_reasons as t6', 't1.inclusion_reason_id', '=', 't6.id')
									->where(array('t1.product_id' => $row->product_id, 'is_active_reason'=>1))
									->get();			
								
								if($ingred_rows){
									$pdf->SetFont('times','I',10);
									foreach($ingred_rows as $ingred_row){
										
										$ingr_name=$ingred_row->ingredient_name;
										$strength=$ingred_row->strength;
										$pdf->SetFont('times','B',10);
										
										$pdf->Cell(70,5,'',0,0,'L');
										
									}
									
								}else{
									$ingr_name='';
									$proportion='';
									$strength='';
									$specification_id=0;
								}
								*/$pdf->SetFont('times','',10);
								$pdf->MultiCell(0,5,($row->common_names).'  '.($row->product_strength),0,'',0,1);
								$pdf->ln();
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(70,8,'Indication:',0,'',0,0);
								$pdf->SetFont('times','',10);
								
									$pdf->MultiCell(0,8,($row->indication),0,'J',0,1);
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(70,8,'Dosage Form and appearance:',0,'',0,0);
								$pdf->SetFont('times','',10);
						
								$pdf->MultiCell(0,12,($row->dosage_form).' '.($row->physical_description),0,'J',0,1);
								$pdf->SetFont('times','',10);
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(69,10,'Pack size and Packaging type:',0,'',0,0);
								//$pdf->Cell(70,5,'',0,0,'L');
								
								$pdf->SetFont('times','',10);
								$packaging = '';
											$container_name = '';
											$retail_packaging_size = '';
								$packaging_data = DB::table('tra_product_packaging as t1')
											->select(DB::raw("t1.*, t2.name as container_type, t3.name as container_name, t4.name as container_material, t5.name as closure_materials, t4.name as container_material, t5.name as closure_material, t6.name as seal_type, t7.name as packaging_units, CONCAT_WS('X',retail_packaging_size,retail_packaging_size1,retail_packaging_size2,retail_packaging_size3,retail_packaging_size4) as retail_packaging"))
											->leftJoin('par_containers_types as t2', 't1.container_type_id', '=', 't2.id')
											->leftJoin('par_containers as t3', 't1.container_id', '=', 't3.id')
											->leftJoin('par_containers_materials as t4', 't1.container_material_id', '=', 't4.id')
											->leftJoin('par_closure_materials as t5', 't1.closure_material_id', '=', 't5.id')
											->leftJoin('par_seal_types as t6', 't1.seal_type_id', '=', 't6.id')
											->leftJoin('par_packaging_units as t7', 't1.packaging_units_id', '=', 't7.id')
											->where(array('t1.product_id' => $row->product_id))
											->get();
								
								if($packaging_data->count() >0){
								$i = 1;
									foreach($packaging_data as $packaging_rec){
										
											$container_material = $packaging_rec->container_material;
											$container_name = $packaging_rec->container_name;
											
											$retail_packaging_size = $packaging_rec->retail_packaging;
											
											$product_unit = $packaging_rec->unit_pack;		
											if($i != 1){
												$pdf->Cell(69,5,'',0,0);
											}									
											if($product_unit == ''){
											
													$pdf->MultiCell(0,5,($container_material).' '.($container_name) .' OF '.($retail_packaging_size),0,'',0,1);
											}
											else{
												
													$pdf->MultiCell(0,5,($container_material).' '.($container_name) .' OF '.($retail_packaging_size).' X '.($product_unit),0,'',0,1);													
																							
											}
											
											$i++;		
									
									}
											
								
								}
								else{
									
											$pdf->MultiCell(0,10,'',0,'',0,1);
											
								}
								$pdf->SetFont('times','',10);
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(70,7,'Shelf life of medicine in months and Storage statement:',0,'',0,0); ;
								
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(0,7,($row->shelf_life).', '.(html_entity_decode(($row->storage_condition))),0,'',0,1); ;
								
								
								$pdf->SetFont('times','B',10);
								$pdf->Cell(70,7,'Distribution category:',0,0,'L');
								$pdf->SetFont('times','',10);
								$pdf->Cell(100,7,($row->distribution_category),0,1,'L'); 
								
								$pdf->SetFont('times','B',10);
								//$pdf->Cell(0,1,'',0,1);
								
								$pdf->MultiCell(70,7,'Name of marketing authorization holder:',0,'',0,0);
								
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(0,7,($row->trader_name),0,'',0,1);
								//$pdf->Cell(100,12,ucwords($applicantName),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								//Manufacturer
								 $manrow = DB::table('tra_product_manufacturers as t1')
									->select('t1.*','t7.physical_address as mansite_address', 't2.email_address','t1.id as manufacturer_id', 't2.physical_address', 't2.name as manufacturer_name','t2.postal_address', 't3.name as country_name', 't4.name as region_name', 't5.name as district_name')
									->join('tra_manufacturers_information as t2', 't1.manufacturer_id', '=', 't2.id')
									->join('par_countries as t3', 't2.country_id', '=', 't3.id')
									->leftJoin('par_regions as t4', 't2.region_id', '=', 't4.id')
									->leftJoin('par_districts as t5', 't2.district_id', '=', 't5.id')
									->leftJoin('par_manufacturing_roles as t6', 't1.manufacturer_role_id', '=', 't6.id')
									->leftJoin('par_man_sites as t7', 't1.man_site_id', '=', 't7.id')
									->where(array('t1.product_id' => $row->product_id, 'manufacturer_type_id' => 1))
									->first();
									
									$manufacturer_name='';
									$man_postal_address='';
									$man_physical_address='';
									$man_countryName='';
									$man_districtName='';
									$man_regionName = '';
									
								if($manrow){
									$manufacturer_name=$manrow->manufacturer_name;
									
									$man_postal_address=$manrow->postal_address;
									$man_physical_address=$manrow->physical_address;
									if($man_physical_address ==''){
										
										$man_physical_address=$manrow->mansite_address;
									}
									$man_countryName= $manrow->country_name;
									$man_regionName = $manrow->region_name;
								}
								
								//Manufacturer sql 
								$pdf->SetFont('times','B',10);
								
								$pdf->MultiCell(70,10,'Name and Address of the Manufacturer:',0,'',0,0);
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(0,5,($manufacturer_name),0,'',0,1);
								
								$pdf->SetFont('times','',10);
								
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(0,5,($man_physical_address),0,'L');
								
								if($man_regionName!=''){
									$pdf->Cell(70,5,'',0,0,'L');
									$pdf->SetFont('times','',10);
									$pdf->Cell(100,5,($man_regionName),0,1,'L'); 
								}
								$pdf->Cell(70,5,'',0,0,'L');
								$pdf->SetFont('times','',10);
								$pdf->Cell(100,5,($man_countryName),0,1,'L'); 
								 
								$pdf->SetFont('times','B',10);
								$pdf->MultiCell(70,5,'Name of Local Technical Representative:',0,'',0,0);
								
								
								$pdf->SetFont('times','',10);
								$pdf->MultiCell(0,5,($row->localAgentName),0,'',0,1);
								//$pdf->Cell(100,8,strtoupper($localAgentName),0,1,'L'); 
								
								$pdf->SetFont('times','B',10);
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(70,5,'Issued on:',0,0,'L');
								$pdf->SetFont('times','',10);
								$pdf->Cell(100,5,ucwords(date('F d, Y ',strtotime($row->certificate_issue_date))),0,1,'L'); 
								
								
								$pdf->SetFont('times','B',10);
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(70,5,'Expires on:',0,0,'L');
								$pdf->SetFont('times','',10);
								$pdf->Cell(100,5,ucwords(date('F d, Y ',strtotime($row->expiry_date))),0,1,'L'); 
								//$pdf->Cell(0,1,'',0,1);
								$pdf->Cell(0,2,'',0,1);
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
								
								
								
								$pdf->Cell(0,2,'',0,1);
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
								
								$this->getCertificateSignatoryDetail($row,$pdf);
								
								$pdf->write2DBarcode($data, 'QRCODE,H', 178, 250, 24, 24);
								$pdf->AddPage();
								$pdf->SetFont('times','B',9);
								
								
								$pdf->Cell(0,5,'Conditions of Registration:',0,1);
								$pdf->SetFont('times','',11);
								$pdf->Cell(0,2,'',0,1);
								
								$this->getCertificateRegistrationConditions($row,$pdf);
								
								$pdf->Output();
						}	
							
								
			
			
		} catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
	}
	public function printClinicalTrialCertificate($application_code,$application_id){
		
		try{
				$approvalGrant = DB::table('tra_approval_recommendations')->where('application_code', $application_code)->first();
			if(!$approvalGrant){
				echo "The application has not been approved, contact the system administration.";
				exit();
			}
			if($approvalGrant->decision_id == 1){
			 
							$record = DB::table('tra_clinical_trial_applications as t2')
					->join('wb_trader_account as t3', 't2.applicant_id', '=', 't3.id')
					->leftJoin('clinical_trial_personnel as t4', 't2.sponsor_id', '=', 't4.id')
					->leftJoin('clinical_trial_personnel as t5', 't2.investigator_id', '=', 't5.id')
									->join('tra_approval_recommendations as t6', 't2.application_code', '=', 't6.application_code')
									->leftJoin('par_countries as t7', 't4.country_id', '=', 't7.id')
									->leftJoin('par_regions as t8', 't4.region_id', '=', 't7.id')
									->leftJoin('users as t17', 't6.permit_signatory', '=', 't17.id')
					->select(DB::raw("t2.*,t2.id as previous_id,concat(decrypt(t17.first_name),' ',decrypt(t17.last_name)) as permit_signatoryname,	 t6.permit_signatory,t6.permit_no,t3.name as applicant_name,t4.name as sponsor,t5.name as investigator,
						t3.id as applicant_id, t3.name as applicant_name, t3.contact_person, t3.tin_no,t2.reference_no,t2.*,t6.expiry_date as regexpiry_date,t6.approval_date as regcertificate_issue_date, t6.certificate_no as registration_no,t7.name as sponsor_country, t7.name as sponsor_region,
						t3.country_id as app_country_id, t3.region_id as app_region_id, t3.district_id as app_district_id,t2.id as application_id,
						t3.physical_address as app_physical_address, t3.postal_address as app_postal_address,t4.postal_address as sponsor_address ,
											t3.telephone_no as app_telephone,t3.fax as app_fax, t3.email as app_email, t3.website as app_website"))
											->where('t2.application_code',$application_code)
											->first();
							if($record){
								$row = $record;
								$principal_investigator= $row->investigator;
								$principal_investigator= $row->investigator;
								$application_id = $row->application_id;
								$reference_no = $row->reference_no;	$protocol_no = $row->protocol_no;
								$data = "Clincial Trial Authorisation: Permit No:".$row->registration_no."; Protocol No:".$row->protocol_no.";Issued Date:".formatDate($row->regcertificate_issue_date);
								$styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
							
											$org_info = $this->getOrganisationInfo();
												
								$pdf = new PdfProvider();
								$this->getCertificateHeader($pdf, '');
											
								$this->funcGenerateQrCode($record,$pdf);
												
											
											$logo = getcwd() . '/resources/images/org-logo.png'; 
											$pdf->SetFont('times','B',9);
											$pdf->Cell(0,1,'',0,1);
											$pdf->Image($logo, 91, 15, 31, 36);
											
										
										$pdf->Cell(0,25,'',0,1);
											
										$pdf->SetFont('','B',12);
									
										$pdf->Cell(0,5,'CLINICAL TRIAL APPROVAL CERTIFICATE',0,1,'C');
										$pdf->SetFont('','BI',9);
										$pdf->Cell(0,5,'(Made under section 61(2)(b)(ii) of RWANDA FOOD AND DRUGS AUTHORITY Act, Cap 219)',0,1,'C');
										$pdf->MultiCell(0,5,'(Made under law No. 003/2018 of 09/02/2018 establishing the Rwanda FDA and determining its mission, organization, and functioning in its article 8, paragraph 7and article 9, paragraph 2)',0,'C',0,1);
								
										$pdf->Cell(0,5,'',0,1);
										$pdf->SetFont('','B',11);
										$pdf->Cell(0,5,'Clinical Trial Approval Certificate No:'.strtoupper($row->registration_no),0,1,'');
										$pdf->Cell(0,5,'',0,1);
									$pdf->SetFont('','',11);
										$pdf->MultiCell(0,5,"This is to certify that the clinical trial described below has been approved in Rwanda subject to conditions indicated in this certificate.:\n",0,'J',0,1);
												
										$pdf->SetFont('','',11);
										$pdf->SetLineWidth(0.2);
										//get Study sites 
										$study_siterec = DB::table('study_sites as t1')
										->join('par_countries as t2', 't1.country_id', '=', 't2.id')
										->leftJoin('par_regions as t3', 't1.region_id', '=', 't3.id')
										->join('clinical_trial_sites as t4', 't1.id', '=', 't4.study_site_id')
									
										->select('t1.*','t1.name as study_site_name', 't2.name as country_name', 't3.name as region_name')
										->where('t4.application_id',$application_id);
										$total_record = $study_siterec->count();
										$study_siterec = 	$study_siterec->get();
										$study_sites= '';
															
															$i = 1;
															if($study_siterec){
																
																foreach($study_siterec as $rows){
																	if( $total_record == 1){
																		$study_sites.= $rows->study_site_name." ".$rows->physical_address." ".$rows->region_name." "; 
																
																	}
																	else if($i == $total_record){
																		$study_sites.= " and ".$rows->study_site_name." ".$rows->physical_address." ".$rows->region_name." "; 
																
																	}
																	else if(($i+1) == $total_record){
																		$study_sites.=$rows->study_site_name." ".$rows->physical_address." ".$rows->region_name." "; 
																
																	}
																	else{
																		$study_sites.= $rows->study_site_name." ".$rows->physical_address." ".$rows->region_name.", "; 
																
																	}
																			$i++;
																}
															
															}
										
										$pdf->Cell(0,5,'',0,1);
										$pdf->SetFont('','',11);
										$pdf->Cell(60,5,'Protocol Title: ',0,0);
										$pdf->SetFont('','B',11);
										$pdf->MultiCell(0,5,strtoupper($row->study_title),0,'L');
										$pdf->Cell(0,5,'',0,1);
										$pdf->SetFont('','',11);
										$pdf->setCellHeightRatio(1.8);
										//$date_of_protocol = date('jS F, Y',strtotime($pdf->date_of_protocol));
										$pdf->Cell(60,5,'Protocol Number and version: ',0,0);
										$pdf->WriteHTML('<span style="text-align:justify;"><b>'.strtoupper($row->protocol_no).'</b> <b>'.strtoupper($row->version_no).'</b></span>', true, 0, true, true,'');
									
										$pdf->SetFont('','',11);
										
										$pdf->Cell(60,7,'Name of the Investigational product (s):',0,1);
										$pdf->MultiCell(90,6,'Investigational Product(s)/Intervention (s)',1,'',0,0);
										
										$pdf->MultiCell(0,6,'Comparator (s)',1,'',0,1);
										
										$prod_records = DB::table("clinical_trial_products")
																->select("*")
																->where(array('application_id'=>$application_id))
																->get();
										$comparator_products = '';							
										$investigational_products = '';							
										if($prod_records){
												foreach($prod_records as $prod_record){
														$brand_name = $prod_record->brand_name;
														$product_category_id = $prod_record->product_category_id;
														if($product_category_id == 1){
															$comparator_products = $brand_name.', ';
														}else if($product_category_id == 2){
															$investigational_products = $brand_name.', ';
														}
														
													
												}
										}
										$pdf->SetFont('','B',11);
										$pdf->MultiCell(90,6,strtoupper(trim($investigational_products, ', ')),1,'',0,0);
										
										$pdf->MultiCell(0,6,strtoupper(trim($comparator_products ,', ')),1,'',0,1);
										
										$pdf->SetFont('','',11);
										$pdf->Cell(60,5,'Study site(s):',0,0);
										$pdf->SetFont('','B',11);
										$pdf->MultiCell(0,5,strtoupper($study_sites),0,'',0,1);
										
										
										$pdf->SetFont('','',11);
										$pdf->Cell(60,5,'Name of the Principal Investigator(s).',0,0);
										$pdf->SetFont('','B',11);
										$pdf->MultiCell(0,5,strtoupper($principal_investigator),0,'',0,1);
										$pdf->SetFont('','',11);
										$pdf->MultiCell(60,5,'Sponsor’s name:  ',0,'',0,0);
										$pdf->MultiCell(0,5,strtoupper($row->sponsor),0,'',0,1);
										$pdf->SetFont('','',11);
										$pdf->Cell(60,5,'Issued on:',0,0);
										$pdf->SetFont('','B',11);
										
										$approval_date = date('j\<\s\u\p\>S\<\/\s\u\p\> F Y', strtotime($row->regcertificate_issue_date));
										
										$pdf->SetFont('','',11);
										
										$pdf->WriteHTML('<b>'.strtoupper($approval_date),true,0,true,true);
										
										$expiry_date = date('j\<\s\u\p\>S\<\/\s\u\p\> F Y', strtotime($row->regexpiry_date));
										
										$pdf->SetFont('','',11);
										$pdf->Cell(60,5,'Expires on:',0,0);
										$pdf->WriteHTML('<b>'.strtoupper($expiry_date),true,0,true,true);
										$pdf->ln();
										$permit_signitory = '';
										$title= 'ACTING';
										$title= '';
										$approved_by = '';
														
										$this->getCertificateSignatoryDetail($record,$pdf);
										
										$pdf->AddPage();
										
												$pdf->SetFont('','BU',13);
												$pdf->Cell(0,5,'Key Conditions for compliance ',0,1);
															
										$pdf->SetFont('times','',11);
										
										$this->getCertificateRegistrationConditions($row,$pdf);
										
										
							}
							else{
									$pdf->SetFont('','B',12);
									$pdf->Cell(0,5,'No Record Found',0,1);
							
							}
								 $pdf->Output('Clinical trial Certificate '.date('Y').date('m').date('d').date('i').date('s').'.pdf','I');
							
	
			}else{
				return "Setup rejection letter";
			}
			
			
		}catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
		
	}
	public function printImportExportLicense($application_code,$record,$permit_watermark){
		try{
				
$record = DB::table('tra_importexport_applications as t1')
						->join('sub_modules as t2','t1.sub_module_id','t2.id')
						->leftJoin('wb_trader_account as t3','t1.applicant_id', 't3.id')
						->leftJoin('par_countries as t4', 't3.country_id', 't4.id')
						->leftJoin('par_regions as t5', 't3.region_id', 't5.id')
						->leftJoin('par_ports_information as t6', 't1.port_id', 't6.id')
						->leftJoin('tra_managerpermits_review as t7', 't1.application_code', 't7.application_code')
						->leftJoin('users as t8', 't7.permit_signatory', 't8.id')
						->leftJoin('tra_permitsenderreceiver_data as t9','t1.sender_receiver_id', 't9.id')
						->leftJoin('par_countries as t10', 't9.country_id', 't10.id')
						->leftJoin('par_regions as t11', 't9.region_id', 't11.id')
						->leftJoin('par_modesof_transport as t12', 't1.mode_oftransport_id', 't12.id')
						->leftJoin('tra_managerpermits_review as t13', 't1.application_code', 't13.application_code')
						->leftJoin('tra_consignee_data as t14', 't1.consignee_id', 't14.id')
						->leftJoin('par_sections as t15', 't1.section_id', 't15.id')
						->leftJoin('tra_premises as t16', 't1.premise_id', 't16.id')
						->leftJoin('par_sections as t17', 't1.section_id', 't17.id')
						->leftJoin('par_business_types as t20', 't16.business_type_id', 't20.id')
						
							->leftJoin('par_permitsproduct_categories as t18', 't1.permit_productscategory_id', 't18.id')
						->select('t2.title','t20.name as business_type', 't1.premise_id','t13.expiry_date as permit_expiry_date','t18.name as permit_productscat', 't17.name as permit_productscategory', 't3.physical_address as applicant_physical_address', 't3.postal_address as applicant_postal_address', 't15.name  as product_category','t16.*','t2.title as permit_title','t13.permit_no','t14.name as consignee_name', 't1.sub_module_id', 't1.*','t16.name as premise_name','t3.name as applicant_name','t2.action_title','t6.name as port_entry', 't3.*', 't4.name as country_name', 't5.name as region_name','t7.permit_signatory', 't7.approval_date', DB::raw("concat(decrypt(t8.first_name),' ',decrypt(t8.last_name)) as permit_signatoryname, t9.name as suppler_name, t9.physical_address as suppler_address,'t13.expiry_date',  t9.postal_address as supplierpostal_address,
									 t10.name as supplier_country, t11.name as supplier_region, t9.postal_address as supplier_postal_address, t12.name as mode_of_transport"))
						->where('t1.application_code',$application_code)
						->first();
						$sub_module_id = $record->sub_module_id;
						$permit_title = $record->permit_title;
						$action_title = $record->action_title;
						$consignee_name  = $record->consignee_name ;
						$approval_date = '';
						if($record->approval_date != ''){
								$approval_date = $record->approval_date;
						}
						
						if($record){
							
							$org_info = getOrganisationInfo();
								$pdf = new PdfImpExpLicenseProvider();
								$this->getImpPermitCertificateHeader($pdf,$record,$permit_title);
								
								  $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Import License No: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->permit_no,0,1);
								 if($record->sub_module_id == 82){
									 
										//$pdf->Cell(0,7,'Visa  No: ',0,1);
								 }
									
								$pdf->setCellHeightRatio(1.8);
								 if(validateIsNumeric($record->premise_id)){
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Name of Importer: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->premise_name,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'TIN: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->tpin_no,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Premise No: ',0,0);
									 $pdf->SetFont('','',10);
									 //$pdf->Cell(0,7,$record->premise_reg_no,0,1);
									 $pdf->MultiCell(0,7,$record->premise_reg_no.' ('.$record->business_type.')',0,'',0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Postal Address: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->postal_address,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Physical Location: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->physical_address,0,1);
									 
								 }
								 else{
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Name of Importer: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_name,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'TIN: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->tin_no,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Postal Address: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_postal_address,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Physical Location: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_physical_address,0,1);
									 
								 }
								 
								$this->funcImpGenerateQrCode($record,$pdf);
								 $pdf->ln();
								 $startY = $pdf->GetY()-5;
										$startX = $pdf->GetX();
										$pdf->SetLineWidth(0.2);
										$pdf->Line(0+10,$startY,198,$startY);
											$pdf->SetFont('','B',10);
								
								$pdf->SetFont('','B',10);
									// $pdf->Cell(40,7,'Product Category: ',0,0);
									 $pdf->SetFont('','',10);
									// $pdf->Cell(0,7,$record->permit_productscategory,0,1);
									  $pdf->MultiCell(40,7,'Product Category:',0,'',0,0);
									 $pdf->MultiCell(0,7,$record->permit_productscategory.' ('.$record->permit_productscat.')',0,'',0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Transport Mean:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->mode_of_transport,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Port Of Entry:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->port_entry,0,1);
									 
								$pdf->SetFont('','',10);
								$pdf->setCellHeightRatio(1.8);
									$pdf->ln();
								$pdf->SetFont('','B',9);
								$pdf->SetLineWidth(0.1);
								$pdf->Cell(10,7,'No',1,0);
								$pdf->Cell(35,7,'Product',1,0);
								$pdf->Cell(25,7,'Pack Size',1,0);
								$pdf->Cell(25,7,'Batch/serial #',1,0);
								$pdf->Cell(25,7,'Mgf Date(s)',1,0);
								$pdf->Cell(25,7,'Quantity',1,0);
								$pdf->Cell(20,7,'Unit Value',1,0);
								$pdf->Cell(0,7,'Total Value',1,1);
								
								$pdf->SetFont('','',9);
							$prod_rec = DB::table('tra_permits_products as t1')
																		->leftJoin('tra_product_information as t2', 't1.product_id', 't2.id')
																		->leftJoin('par_dosage_forms as t3', 't1.dosage_form_id', 't3.id')
																		->leftJoin('par_packaging_units as t4', 't1.packaging_unit_id', 't4.id')
																		->leftJoin('par_common_names as t5', 't1.common_name_id', 't5.id')
																		->leftJoin('par_si_units as t6', 't1.unitpack_unit_id', 't6.id')
																		->leftJoin('par_currencies as t7', 't1.currency_id', 't7.id')
																		->leftJoin('tra_manufacturers_information as t8', 't1.manufacturer_id', 't8.id')
																		->leftJoin('par_countries as t9', 't1.country_oforigin_id', 't9.id')
																		->select('t1.*','t7.name as currency_name','t8.name as manufacturer_name',  't4.name as packaging_unit','t1.product_strength','t5.name as generic_name','t1.permitcommon_name', 't2.brand_name','t9.name as country_name', 't3.name as dosage_form', 't6.name as si_unit', 't1.unitpack_size', 't1.product_strength')
																		->where(array('application_code'=>$record->application_code))
																		->whereNotIn('permitprod_recommendation_id', [3])
																		->get();
											$prod_counter = $prod_rec->count();		
								$currency_name = '';											
								$total_amount = 0;											
								if($prod_counter >0){
											$i=1;
									foreach($prod_rec as $rec){
										/*if(validateIsNumeric($rec->product_id)){
												
												$permit_brandname = $rec->brand_name.' '.$rec->generic_name;
										}
										else{	
												$permit_brandname = $rec->permitbrand_name.' '.$rec->permitcommon_name;

										}		
										*/
										if(validateIsNumeric($rec->product_id)){
											$generic_name = $rec->permitcommon_name ;
												if($rec->permitcommon_name == ''){
													$generic_name = $rec->generic_name ;
												}
												$permit_brandname = $rec->brand_name.' '.$generic_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;
										}
										else{	
										$generic_name = $rec->generic_name ;
										$generic_name = $rec->permitcommon_name ;
												if($rec->permitcommon_name == ''){
													$generic_name = $rec->generic_name ;
												}
												$permit_brandname = $rec->permitbrand_name.' '.$generic_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;

										}
										
										if($rec->permitmanufacturer_name != '' && $rec->permitmanufacturer_name != 0){
											
											$permit_brandname .= ' . Manufacturer: '.$rec->permitmanufacturer_name.' Country: '.$rec->country_name;
												
										}
										else{
											if($rec->manufacturer_name != ''){
												$permit_brandname .= ' . Manufacturer: '.$rec->manufacturer_name.' Country: '.$rec->country_name;
												
											}
											
										}
										$amount = $rec->unit_price*$rec->quantity;										
										$packaging_data = $rec->unitpack_size.' '.$rec->si_unit;
										$product_batch_no = trim($rec->product_batch_no);
										$manufacturing_dates = 'Mgf Date: '.formatDateRpt($rec->product_manufacturing_date).' Exp. Date'.formatDateRpt($rec->product_expiry_date);
											
											$rowcount = max($pdf->getNumLines($permit_brandname, 24),$pdf->getNumLines($packaging_data, 25),$pdf->getNumLines($packaging_data, 22),$pdf->getNumLines($packaging_data, 25),$pdf->getNumLines($manufacturing_dates, 22),$pdf->getNumLines($product_batch_no, 24));
											
											$pdf->MultiCell(10,5*$rowcount,$i,1,'',0,0);
											$pdf->MultiCell(35,5*$rowcount,$permit_brandname,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$rec->unitpack_size.' '.$rec->si_unit,1,'',0,0);
											
											$pdf->MultiCell(25,5*$rowcount,$product_batch_no,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$manufacturing_dates,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$rec->quantity,1,'',0,0);//.' '.$rec->packaging_unit
											$pdf->MultiCell(20,5*$rowcount,($rec->unit_price).' ',1,'',0,0);
											$pdf->MultiCell(0,5*$rowcount,formatMoney($amount),1,'R',0,1);	
													
											$currency_name = $rec->currency_name;
											$total_amount = $total_amount+$amount;
											$i++;
									} 
									 $pdf->SetFont('','B',10);
									$pdf->Cell(145,8,'Total Value in ('.$currency_name.')',1,0, 'R');
										$pdf->Cell(0,8,formatMoney($total_amount),1,1, 'R');
								}   $pdf->SetFont('','',10);
								
								
								 $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Name of the Supplier:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->suppler_name,0,1);
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Address:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->supplierpostal_address,0,1);
									
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Physical Location:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->suppler_address,0,1);
									 
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Country:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->supplier_country,0,1);
									 
								$pdf->WriteHTML("This certificate authorizes the above importer to import the products specified in Invoice number <b>".$record->proforma_invoice_no."</b> into the country after complying with the importation requirements.", true, 0, true, true,'J');
								
								
								$pdf->WriteHTML("All the imported consignments must be inspected at port of entry or at importer’s premises at arrival before being used to ensure that they comply with claimed specifications.", true, 0, true, true,'J');
								
								$pdf->Cell(45,8,'This Certficate is valid up to: ',0,0);
								$pdf->SetFont('','B',10);
								$pdf->Cell(0,8,formatDateRpt($record->permit_expiry_date),0,0);
								$pdf->SetFont('','',10);
										
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
											
								$pdf->ln();
								$this->getImportCertificateSignatoryDetail($record,$pdf);
									if($permit_watermark != ''){
									
									$this->printWaterMark($pdf,$permit_watermark);
								}		
									$pdf->Output($permit_title.'.pdf');

						}
					
										
					
		}catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
		
		
		
	}
	
	public function printOfficialCertficateCtrDrgs($application_code,$record,$permit_watermark){
		try{
				$record = DB::table('tra_importexport_applications as t1')
						->join('sub_modules as t2','t1.sub_module_id','t2.id')
						->leftJoin('wb_trader_account as t3','t1.applicant_id', 't3.id')
						->join('par_countries as t4', 't3.country_id', 't4.id')
						->leftJoin('par_regions as t5', 't3.region_id', 't5.id')
						->leftJoin('par_ports_information as t6', 't1.port_id', 't6.id')
						->leftJoin('tra_permitsrelease_recommendation as t7', 't1.application_code', 't7.application_code')
						->leftJoin('users as t8', 't7.permit_signatory', 't8.id')
						->leftJoin('tra_permitsenderreceiver_data as t9','t1.sender_receiver_id', 't9.id')
						->leftJoin('par_countries as t10', 't9.country_id', 't10.id')
						->leftJoin('par_regions as t11', 't9.region_id', 't11.id')
						->leftJoin('par_modesof_transport as t12', 't1.mode_oftransport_id', 't12.id')
						->leftJoin('tra_managerpermits_review as t13', 't1.application_code', 't13.application_code')
						->leftJoin('tra_consignee_data as t14', 't1.consignee_id', 't14.id')
						->leftJoin('par_permitsproduct_categories as t15', 't1.permit_productscategory_id', 't15.id')
						->select('t2.title','t15.name  as product_category' ,'t2.title as permit_title','t13.permit_no','t14.name as consignee_name', 't1.sub_module_id', 't1.*','t3.name as applicant_name','t2.action_title','t6.name as port_entry', 't3.*', 't4.name as country_name', 't5.name as region_name','t13.permit_signatory', 't13.approval_date', DB::raw("concat(decrypt(t8.first_name),' ',decrypt(t8.last_name)) as permit_signatoryname, t9.name as suppler_name,   t9.postal_address as supplierpostal_address,
									  t9.physical_address as suppler_address, t10.name as supplier_country, t11.name as supplier_region, t9.postal_address as supplier_postal_address, t12.name as mode_of_transport"))
						->where('t1.application_code',$application_code)->first();

						$sub_module_id = $record->sub_module_id;
						$permit_title = $record->permit_title;
						$action_title = $record->action_title;
						$consignee_name  = $record->consignee_name ;
						$approval_date = '';
						$approval_date = $record->approval_date;
						
						if($record){
								$org_info = $this->getOrganisationInfo();
									//$org_info = getOrganisationInfo();			
								$pdf = new PdfOfficialcertficateProvider();
								$this->getOfficialCertificateHeader($pdf, $org_info);
											
								$this->funcGenerateQrCode($record,$pdf);
								$logo = getcwd() . '/resources/images/org-logo.png';
								$pdf->SetFont('times','B',9);
								$pdf->Cell(0,1,'',0,1);
								
									//$pdf->Image($logo, 89, 15, 33, 35);
											
								$pdf->SetFont('','B',10);				 
								$pdf->setCellHeightRatio(1.8);
								$pdf->setFillColor(230,230,230); 
								$permit_no = $record->permit_no;
								if($record->permit_no == '' || $record->permit_no == null){
									$permit_no = $record->reference_no;
									if($permit_no ==''){
										$permit_no = $record->tracking_no;
									}
								}
								$pdf->SetFont('','B',9);
								$pdf->MultiCell(0,8,strtoupper('OFFICIAL CERTIFICATE OF IMPORTATION OF CONTROLLED SUBSTANCES No: '.$permit_no),1, '', 1, 1, '' ,'', true);
								
								$pdf->setCellHeightRatio(1.8);
								$pdf->SetFont('','I',11);
								$pdf->MultiCell(0,7,'Adopted from the Single Convention on Narcotic Drugs, 1961 and Convention on Psychotropic Substances 1971',0,'',0,1);
								$pdf->SetFont('','',11);
									//$pdf->Cell(0,1,'',0,1);
								$pdf->WriteHTML('Reference made to the Law Nº 003/2018 of 09/02/2018 establishing Rwanda Food and Drugs Authority and determining its mission, organisation and functioning especially in its article 8, Rwanda FDA is responsible for the implementation of laws and regulations relating to narcotic drugs and psychotropic substances covered by international conventions and protocols, hereby authorizes the importation of controlled substance (s) listed below:', true, 0, true, true,'J');
							
								$pdf->SetFont('','',10);
									
								$pdf->SetFont('','B',9);
								
								$pdf->SetLineWidth(0.3);
								$pdf->MultiCell(10,17,'No',1,'',0, 0);
								$pdf->MultiCell(45,17,'Brand name of the substances',1,'', 0, 0);
								$pdf->MultiCell(35,17,'International Nonproprietary Name',1,'', 0, 0);
								$pdf->MultiCell(30,17,'Pharmaceutical dosage form',1,'', 0, 0);
								$pdf->MultiCell(25,17,'Quantity',1,'', 0, 0);
								$pdf->MultiCell(25,17,'Content per unit in (mg)',1,'', 0, 0);
								$pdf->MultiCell(0,17,'Total quantity in grams (g)',1,'', 0, 1);
								
								$pdf->SetFont('','',10);
							 $prod_rec = DB::table('tra_permits_products as t1')
									->leftJoin('tra_product_information as t2', 't1.product_id', 't2.id')
									->leftJoin('par_dosage_forms as t3', 't1.dosage_form_id', 't3.id')
									->leftJoin('par_common_names as t12', 't1.common_name_id', 't12.id')
									->leftJoin('par_controlled_drugssubstances as t7', 't1.controlled_drugssubstances_id', 't7.id')
									->leftJoin('par_controlleddrugs_basesalts as t8', 't1.controlleddrugs_basesalt_id', 't8.id')
									->leftJoin('par_drugspackaging_types as t9', 't1.drugspackaging_type_id', 't9.id')
									->leftJoin('par_gramsbasesiunits_configs as t10', 't1.gramsbasesiunit_id', 't10.id')
									->leftJoin('par_si_units as t6', 't1.unitpack_unit_id', 't6.id')
									->select('t1.*','t9.name as packaging_unit','t1.product_strength','t7.name as drug_name', 't3.name as dosage_form','t12.name as generic_name', 't10.name as gramsbasesiunit', 't1.unitpack_size','t8.name as base_salt' )
									->where(array('application_code'=>$record->application_code))
									->get();
											$prod_counter = $prod_rec->count();		
								$currency_name = '';											
								$total_amount = 0;											
								if($prod_counter >0){
											$i=1;
									foreach($prod_rec as $rec){
											//$permit_brandname = $rec->permitbrand_name;//.' '.$rec->product_strength.' '.$rec->dosage_form;
											$permit_brandname = $rec->permitbrand_name;//.' '.$rec->product_strength.' '.$rec->dosage_form;
											$base_salt = $rec->base_salt;
											$generic_name = $rec->drug_name;
											if($base_salt != ''){
												$generic_name = $rec->drug_name.'('.$base_salt.')';
											}
											
											
											if(!validateIsNumeric($rec->controlleddrugs_basesalt_id) ){
												//$contentperunit  = $rec->controlleddrug_base/ 100; 
												$contentperunit  = $rec->product_strength;
											
												
											}else{
												$contentperunit  = $rec->drugs_content*($rec->product_strength/100);
											
											}
											
											//$contentperunit  = $rec->drugs_content*((int)$rec->product_strength/100);
											
											
											
											$quantity = $rec->quantity*(int)$rec->pack_unit;
											$rowcount = max(PDF::getNumLines($permit_brandname, 29),PDF::getNumLines($generic_name, 30),PDF::getNumLines($rec->contentperunit, 20),PDF::getNumLines($rec->dosage_form, 24));
											
											$pdf->MultiCell(10,5*$rowcount,$i,1,'',0,0);
											$pdf->MultiCell(45,5*$rowcount,$permit_brandname,1,'',0,0);
											$pdf->MultiCell(35,5*$rowcount,$generic_name,1,'',0,0);
											$pdf->MultiCell(30,5*$rowcount,$rec->dosage_form,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$quantity,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$contentperunit,1,'',0,0);
											$pdf->MultiCell(0,5*$rowcount,$rec->controlleddrug_base,1,'R',0,1);	
												$i++;	
									} 
									
								}   $pdf->SetFont('','',10);
								$pdf->ln();
								$pdf->ln();
								$details_importer = "Details of Importer (Name, Location, Tel, Fax, Postal Address, E-mail.)";
								$details_exporter = "Details of Exporter (Name, Location, Tel, Fax, Postal Address, E-mail.)";
								
								$rowcount = max(PDF::getNumLines($details_importer, 95),PDF::getNumLines($details_exporter, 95));
											$pdf->SetFont('','B',10);
								$pdf->setFillColor(230,230,230); 
								$pdf->MultiCell(95,5*$rowcount,$details_importer,1,'',1,0);
								$pdf->MultiCell(0,5*$rowcount,$details_exporter,1,'',1,1);
								$details_importer = strtoupper($record->applicant_name)." of ".strtoupper($record->physical_address.", ".$record->postal_address.", ".$record->region_name.", ".$record->country_name);
								$details_exporter = strtoupper($record->suppler_name)." of ".strtoupper($record->suppler_address.", ".$record->supplier_region.", ".$record->supplier_country);
								$rowcount = max(PDF::getNumLines($details_importer, 70),PDF::getNumLines($details_exporter, 90));$pdf->SetFont('','',10);
								$pdf->MultiCell(95,5*$rowcount,$details_importer,1,'',0,0);
								$pdf->MultiCell(0,5*$rowcount,$details_exporter,1,'',0,1);
								
								$pdf->MultiCell(95,8,'Shipping information/Method: '.$record->mode_of_transport,1,'',0,0);
								$pdf->MultiCell(0,8,'Name of the Port of entry in Rwanda:'.$record->port_entry,1,'',0,1);
								
								$pdf->MultiCell(0,8,'Reason for Importation(Medical or Scientific Research, registration Purposes): Medical Supply',1,'',0,1);
								
								
								$pdf->MultiCell(95,8,'Reference Proforma/ Invoice No:'.$record->proforma_invoice_no,1,'',0,0);
								$pdf->MultiCell(0,8,'Date'.$record->proforma_invoice_date,1,'',0,1);
									$pdf->SetFont('','B',10);
								$pdf->Cell(0,5,'This certificate is subjected to the following conditions:',0,1);
									$pdf->SetFont('','I',10);
								$pdf->Cell(0,5,'1.	This certificate is valid for one (1) shipment only.',0,1);
								$pdf->Cell(0,5,'2.	This certificate is only valid for substances or preparations as specified above.',0,1);
								$pdf->Cell(0,5,'3.	This certificate is valid for importer and exporter as specified above.',0,1);
								$pdf->Cell(0,5,'4.	It is not permitted to import quantities greater than those specified in this certificate.',0,1);
								
								$pdf->ln();
								$pdf->SetFont('','B',10);
								$pdf->Cell(0,5,'Validity: This certificate is valid only for twelve (12) months from date of its signature',0,1);
								$pdf->Cell(0,5,'Done at Kigali on : '.formatDateRpt($record->approval_date),0,1);
											
								
								
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
												
								$this->getImportCertificateSignatoryDetail($record,$pdf);
									$pdf->Output($permit_title.'.pdf');

						}
					
										
					
		}catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
		
		
	}
	public function printImportExportvisa($application_code,$record,$permit_watermark){
		try{
				
				$record = DB::table('tra_importexport_applications as t1')
						->join('sub_modules as t2','t1.sub_module_id','t2.id')
						->leftJoin('wb_trader_account as t3','t1.applicant_id', 't3.id')
						->leftJoin('par_countries as t4', 't3.country_id', 't4.id')
						->leftJoin('par_regions as t5', 't3.region_id', 't5.id')
						->leftJoin('par_ports_information as t6', 't1.port_id', 't6.id')
						->leftJoin('tra_managerpermits_review as t7', 't1.application_code', 't7.application_code')
						->leftJoin('users as t8', 't7.permit_signatory', 't8.id')
						->leftJoin('tra_permitsenderreceiver_data as t9','t1.sender_receiver_id', 't9.id')
						->leftJoin('par_countries as t10', 't9.country_id', 't10.id')
						->leftJoin('par_regions as t11', 't9.region_id', 't11.id')
						->leftJoin('par_modesof_transport as t12', 't1.mode_oftransport_id', 't12.id')
						->leftJoin('tra_managerpermits_review as t13', 't1.application_code', 't13.application_code')
						->leftJoin('tra_consignee_data as t14', 't1.consignee_id', 't14.id')
						->leftJoin('par_sections as t15', 't1.section_id', 't15.id')
						->leftJoin('tra_premises as t16', 't1.premise_id', 't16.id')
						->leftJoin('par_sections as t17', 't1.section_id', 't17.id')
						->leftJoin('par_sections as t19', 't16.section_id', 't19.id')
						->leftJoin('par_business_types as t20', 't16.business_type_id', 't20.id')
						->leftJoin('par_permitsproduct_categories as t18', 't1.permit_productscategory_id', 't18.id')
						->select('t2.title','t20.name as business_type', 't19.name as premise_section','t1.premise_id','t13.expiry_date as permit_expiry_date','t18.name as permit_productscat','t17.name as permit_productscategory', 't3.physical_address as applicant_physical_address', 't3.postal_address as applicant_postal_address', 't15.name  as product_category','t16.*','t2.title as permit_title','t13.permit_no','t14.name as consignee_name', 't1.sub_module_id', 't1.*','t16.name as premise_name','t3.name as applicant_name','t2.action_title','t6.name as port_entry', 't3.*', 't4.name as country_name', 't5.name as region_name','t7.permit_signatory', 't7.approval_date', DB::raw("concat(decrypt(t8.first_name),' ',decrypt(t8.last_name)) as permit_signatoryname, t9.name as suppler_name, t9.physical_address as suppler_address, t10.name as supplier_country, t11.name as supplier_region, t9.postal_address as supplier_postal_address, t12.name as mode_of_transport"))
						->where('t1.application_code',$application_code)
						->first();
						$sub_module_id = $record->sub_module_id;
						$permit_title = $record->permit_title;
						$action_title = $record->action_title;
						$consignee_name  = $record->consignee_name ;
						$approval_date = '';
						if($record->approval_date != ''){
								$approval_date = $record->approval_date;
						}
						if($record){
							
														$org_info = getOrganisationInfo();
												
								$pdf = new PdfPermitProvider();
								$this->getImpPermitCertificateHeader($pdf,$record,$permit_title);
								
								$pdf->SetFont('','',10);
								 $pdf->Cell(0,5,'Import Visa No: '.$record->permit_no,0,1);
							
								 if(validateIsNumeric($record->premise_id)){
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Name of Importer: ',0,0);
									 $pdf->SetFont('','',10);
									// $pdf->Cell(0,7,$record->premise_name.' ('.$record->business_type.' on '.$premise_section.')',0,1);
									 $pdf->MultiCell(0,7,$record->premise_name,0,'',0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'TIN: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->tpin_no,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Premise No: ',0,0);
									 $pdf->SetFont('','',10);
									// $pdf->Cell(0,7,,0,1);
									  $pdf->MultiCell(0,7,$record->premise_reg_no.' ('.$record->business_type.')',0,'',0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Postal Address: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->postal_address,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Physical Location: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->physical_address,0,1);
									 
								 }
								 else{
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Name of Importer: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_name,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'TIN: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->tin_no,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Postal Address: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_postal_address,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Physical Location: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_physical_address,0,1);
									 
								 }
								 
								 
								$this->funcImpGenerateQrCode($record,$pdf);
								$pdf->ln();
								 $startY = $pdf->GetY()-5;
										$startX = $pdf->GetX();
										$pdf->SetLineWidth(0.2);
										$pdf->Line(0+10,$startY,198,$startY);
											$pdf->SetFont('','B',10);
											
								$pdf->SetFont('','B',10);
									 //$pdf->Cell(40,7,'Product Category: ',0,0);
									 $pdf->SetFont('','',10);
									// $pdf->Cell(0,7,$record->permit_productscategory.' '.$record->permit_productscat,0,1);
									 $pdf->MultiCell(40,7,'Product Category:',0,'',0,0);
									 $pdf->MultiCell(0,7,$record->permit_productscategory.' ('.$record->permit_productscat.')',0,'',0,1);
									 //$pdf->MultiCell(30,7,$record->permit_productscategory.' '.$record->permit_productscat,0,'',0,1);
								$pdf->SetFont('','',10);
								$pdf->setCellHeightRatio(1.8);
									
								$pdf->SetFont('','B',9);
								$pdf->SetLineWidth(0.1);
								$pdf->Cell(10,7,'No',1,0);
								$pdf->Cell(80,7,'Product',1,0);
								//$pdf->Cell(30,7,'Pack Size',1,0);
								//$pdf->Cell(25,7,'Batch/serial #',1,0);
								//$pdf->Cell(25,7,'Mgf Date(s)',1,0);
								$pdf->Cell(25,7,'Quantity',1,0);
								$pdf->Cell(30,7,'Unit Value',1,0);
								$pdf->Cell(0,7,'Total Value',1,1,'C');$pdf->SetFont('','',9);
							$prod_rec = DB::table('tra_permits_products as t1')
																		->leftJoin('tra_product_information as t2', 't1.product_id', 't2.id')
																		->leftJoin('par_dosage_forms as t3', 't2.dosage_form_id', 't3.id')
																		->leftJoin('par_packaging_units as t4', 't1.packaging_unit_id', 't4.id')
																		->leftJoin('par_common_names as t5', 't1.common_name_id', 't5.id')
																		->leftJoin('par_si_units as t6', 't1.unitpack_unit_id', 't6.id')
																		->leftJoin('par_currencies as t7', 't1.currency_id', 't7.id')
																		->leftJoin('tra_manufacturers_information as t8', 't1.manufacturer_id', 't8.id')
																		->leftJoin('par_countries as t9', 't1.country_oforigin_id', 't9.id')
																		->select('t1.*','t7.name as currency_name', 't1.manufacturer_name as permitmanufacturer_name','t8.name as manufacturer_name',  't4.name as packaging_unit','t1.product_strength','t5.name as generic_name','t1.permitcommon_name', 't2.brand_name','t9.name as country_name', 't3.name as dosage_form', 't6.name as si_unit', 't1.unitpack_size', 't1.product_strength')
																		->where(array('application_code'=>$record->application_code))
																		->get();
											$prod_counter = $prod_rec->count();		
											
								$currency_name = '';											
								$total_amount = 0;											
								if($prod_counter >0){
											$i=1;
									foreach($prod_rec as $rec){
										if(validateIsNumeric($rec->product_id)){
											$generic_name = $rec->generic_name ;
												if($rec->generic_name == ''){
													$generic_name = $rec->permitcommon_name ;
												}
												$permit_brandname = $rec->brand_name.' '.$generic_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;
												//$permit_brandname = $rec->brand_name.' '.$rec->generic_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;
										}
										else{	
										
												$permit_brandname = $rec->permitbrand_name.' '.$rec->permitcommon_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;

										}
										
										if($rec->permitmanufacturer_name != ''){
											
											$permit_brandname .= ' . Manufacturer: '.$rec->permitmanufacturer_name.' Country: '.$rec->country_name;
												
										}
										else{
											if($rec->manufacturer_name != ''){
												$permit_brandname .= ' . Manufacturer: '.$rec->manufacturer_name.' Country: '.$rec->country_name;
												
											}
											
										}
										
										$amount = $rec->unit_price*$rec->quantity;										
										$packaging_data = $rec->unitpack_size.' '.$rec->si_unit;
										$product_batch_no = $rec->product_batch_no;
										$manufacturing_dates = 'Mgf Date: '.formatDateRpt($rec->product_manufacturing_date).' Exp. Date'.formatDateRpt($rec->product_expiry_date);
											
											$rowcount = max(PDF::getNumLines($permit_brandname, 62),PDF::getNumLines($rec->quantity, 25),PDF::getNumLines($rec->unit_price, 25));
											
											$pdf->MultiCell(10,5*$rowcount,$i,1,'',0,0);
											$pdf->MultiCell(80,5*$rowcount,$permit_brandname,1,'',0,0);
											//$pdf->MultiCell(30,5*$rowcount,$rec->unitpack_size.' '.$rec->si_unit,1,'',0,0);
											
											//$pdf->MultiCell(25,5*$rowcount,$product_batch_no,1,'',0,0);
											//$pdf->MultiCell(25,5*$rowcount,$manufacturing_dates,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$rec->quantity,1,'',0,0);
											$pdf->MultiCell(30,5*$rowcount,($rec->unit_price).' ',1,'',0,0);
											$pdf->MultiCell(0,5*$rowcount,formatMoney($amount).' '.$rec->currency_name,1,'R',0,1);	
													$i++;
											$currency_name = $rec->currency_name;
											$total_amount = $total_amount+$amount;
									} 
									$pdf->Cell(145,7,'Total Value:',1,0, 'R');
										$pdf->Cell(0,7,formatMoney($total_amount).' '.$currency_name,1,1, 'R');
								}   $pdf->SetFont('','',10);
								
								 $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Name of the Supplier:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->suppler_name,0,1);
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Address:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->supplier_postal_address,0,1);
									 
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Physical Location:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->suppler_address,0,1);
									 
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Country:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->supplier_country,0,1);
									 
								
								$pdf->WriteHTML("This certificate gives the right to the above importer to confirm an order/purchase order of the products specified in Proforma Invoice number <b>".$record->proforma_invoice_no."</b>  and to apply for an import license.", true, 0, true, true,'J');
								
								$pdf->Cell(45,8,'This Certficate is valid up to: ',0,0);
								$pdf->SetFont('','B',10);
								$pdf->Cell(0,8,formatDateRpt($record->permit_expiry_date),0,0);
								$pdf->SetFont('','',10);
								
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
												
								$this->getImportCertificateSignatoryDetail($record,$pdf);
								if($permit_watermark != ''){
									
									$this->printWaterMark($pdf,$permit_watermark);
								}
								
									$pdf->Output($permit_title.'.pdf');

						}
					
										
					
		}catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
		
		
	}
	function printWaterMark($pdf,$permit_watermark){
		$pdf->setPage( 1 );

		// Get the page width/height
		$myPageWidth = $pdf->getPageWidth();
		$myPageHeight = $pdf->getPageHeight();

		// Find the middle of the page and adjust.
		$myX = ( $myPageWidth / 2 ) - 75;
		$myY = ( $myPageHeight / 2 ) + 25;

		// Set the transparency of the text to really light
		$pdf->SetAlpha(0.09);

		// Rotate 45 degrees and write the watermarking text
		$pdf->StartTransform();
		$pdf->Rotate(45, $myX, $myY);
		$pdf->SetFont("courier", "", 80);
		$pdf->Text($myX, $myY,$permit_watermark);
		$pdf->StopTransform();

		// Reset the transparency to default
		$pdf->SetAlpha(1);
		
	}
	public function printImportExportLetterofRejection($application_code,$record,$permit_watermark){
		
		try{
			
				
					
		}catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			
			print_r($res);
        return response()->json($res);
		
		
	}function generateRequestForAdditionalInformation($req){
		
		$application_code = $req->application_code;
		$module_id = $req->module_id;
		$query_id = $req->query_id;
		if(!validateIsNumeric($module_id)){
			$app_data = DB::table('tra_submissions')
            ->select('module_id')
			->where(array('application_code'=>$application_code))
            ->first();
			if($app_data){
						$module_id = $app_data->module_id;
					}
			}
			
			
			
			$module_data = getTableData('modules', ['id'=>$module_id]);
			
			$requestadditionalinfo_timespan =getTableData('par_requestadditionalinfo_timespan', ['module_id'=>$module_id]);
			if(!isset($requestadditionalinfo_timespan->time_span)){
				$time_span =23;
			}else{
				
				$time_span =$requestadditionalinfo_timespan->time_span ;
				
			}
					if(!isset($module_data->table_name)){
						return "Module details not found";
					}
			 $invoice_details = getInvoiceDetails($module_id, '',$application_code);
			 $app_description= '';
			if(isset($invoice_details)){
				$app_description = $invoice_details['module_desc'];
			}
			$app_data = DB::table($module_data->table_name.' as t1')
						->join('wb_trader_account as t2', 't1.applicant_id', 't2.id')
						->leftJoin('par_countries as t3', 't2.country_id', 't3.id')
						->leftJoin('par_regions as t4', 't2.region_id', 't4.id')
						->where('application_code', $application_code)
						->select('t1.applicant_id','t1.reference_no', 't1.tracking_no', 't2.*', 't3.name as country_name', 't4.name as region_name')
						->first();
			if(!$app_data){
				return "Application details not found";
			}

			$org_info = $this->getOrganisationInfo();
			$pdf = new mPDF( [
					'mode' => 'utf-8',
					'format' => 'A4',
					'margin_header' => '3',
					'margin_top' => '20',
					'margin_bottom' => '20',
					'margin_footer' => '2',
					'tempDir'=> '/opt/lampp/htdocs/mis/backend/public/resources'
				]); 
			// $pdf = new PdfLettersProvider();
			$pdf->setMargins(5,25,5,true);
			$pdf->AddPage();
				$template_url = base_path('/');
				$pdf->setSourceFile($template_url."resources/templates/certificate_template.pdf");
				// import page 1
				$tplId = $pdf->importPage(1);	
				$pdf->useTemplate($tplId,0,0);
				$logo = getcwd() . '/resources/images/logo.png';
				$pdf->Image($logo,90,15,34,30);
				//$pdf->setPageMark();

			// $pdf->SetFont('times','B',9);
			// $pdf->Cell(0,1,'',0,1);

			$pdf->Cell(0,4,'',0,1,'R');
			// $pdf->Cell(0,4,'',0,1,'R');
			$pdf->SetFont('times','B',12);
			// $pdf->Cell(0,15,'',0,1);
			$pdf->Cell(0,4,$org_info->org_name,0,1,'C');
			$pdf->Cell(0,4,'The Medicines and Allied Substances Act, 2013',0,1,'C');

			$pdf->SetFont('times','B',12);
			$pdf->Cell(0,4,'(Act No. 3 of 2013)',0,1,'C');
			//$pdf->Cell(0,30,'',0,1);


			 $pdf->Cell(0,3,'',0,1);
				$startY = $pdf->y;
			$startX = $pdf->x;
			$pdf->SetLineWidth(0.3);
			$pdf->Line(0+55,$startY,160,$startY);
				$pdf->Cell(0,3,'',0,1);
			if($module_id == 4){
					$regulation_title = "The Medicines and Allied Substances (Importation and Exportaion) Regulations, 2017";
					$pdf->Cell(0,4,$regulation_title,0,1,'C');

			}
			else if($module_id == 2){
				//get the premises types 
				$record = DB::table('tra_premises_applications as t1')
								->join('tra_premises as t2', 't1.premise_id', 't2.id')
								->leftJoin('par_premises_types	 as t7', 't2.premise_type_id', 't7.id')
								->select('t7.act_name as premises_type')
								->where('application_code',$application_code)
								->first();
					if($record){
						$premise_type = $record->premises_type;
						
					$regulation_title = $premise_type;
					}else{
						
					$regulation_title = "The Medicines and Allied Substances (Certificate of Registration) Regulations, 2017";
					}
					$pdf->Cell(0,4,$regulation_title,0,1,'C');

			}
			else{
				$regulation_title = "The Medicines and Allied Substances";
				$pdf->Cell(0,4,$regulation_title,0,1,'C');
				$regulation_title = "(Marketing Authorisation of Medicines) Regulations, 2019";
				
				$pdf->Cell(0,4,$regulation_title,0,1,'C');
			}
			

			$pdf->Cell(0,5,'',0,1);
			$pdf->SetFont('times','B',12);
			
			$pdf->WriteHTML('REQUEST FOR ADDITIONAL INFORMATION FOR '.strtoupper($app_description)); 
			$pdf->SetFont('times','B',10);

			$pdf->SetFont('times','',10);
			$application_no = '';

			if($app_data->tracking_no != ''){
				
				$application_no = 	$app_data->tracking_no;
				
			}
			if($app_data->reference_no != ''){

				$application_no .= 	' '.$app_data->reference_no;
			}
			$pdf->Cell(0,10,'Application Reference:'.$application_no,0,1, 'R');
				// $pdf->MultiCell(0,10,'Application Reference:<u>'.$app_data->tracking_no.'</u>',0,'R',0,1,'','',true,0,true);
			$data = '{"tracking_no":'.$app_data->tracking_no.',"module_id":'.$module_id.',"application_code":'.$application_code.'}';

			$styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
			// QRCODE,H : QR-CODE Best error correction
			// $pdf->write2DBarcode($data, 'QRCODE,H', 178, 28, 16, 16);

			// $barcode = "<barcode code='".$data."' type='CODE11' height='0.66' text='1' />";
			//$pdf->writeBarcode('111111111',0, 178, 28);
			$pdf->SetFont('times','',12);
			//Letter heading 
			$pdf->Cell(0,8,'To:',0,1);
			$pdf->Cell(0,8,$app_data->name.',',0,1);
			if($app_data->physical_address != ''){
					$pdf->Cell(0,8,$app_data->physical_address.',',0,1);

				}		
				if(($app_data->physical_address !=  $app_data->postal_address)){
					
						$pdf->Cell(0,8,$app_data->postal_address.',',0,1);
				}
			//$pdf->Cell(0,8,$app_data->physical_address.',',0,1);
			//$pdf->Cell(0,8,$app_data->postal_address.',',0,1);
			$pdf->Cell(0,8,$app_data->region_name." ".$app_data->country_name,0,1);

			$pdf->SetFont('times','',11);
			//$pdf->ln();

			//add query header tag
			$template = "You are requested to furnish, the following information or documents in request of your application for ".$module_data->name." within ".$time_span." days of this request.";

			$pdf->WriteHTML($template);
			$pdf->SetFont('times','B',12);
			//add query items
			//loop through requests
			//$pdf->ln();

			$pdf->Cell(0,5,'',0,1);
			$request_data = DB::table('checklistitems_queries as t1')
							->join('tra_application_query_reftracker as t2', 't1.query_id', 't2.id')
							->leftJoin('par_checklist_items as t3', 't1.checklist_item_id', 't3.id')
							->select('t1.query', 't1.comment', 't2.queried_on', 't2.is_live_signature','t3.name as checklist_item', 't2.sign_file')
							->where('t2.id', $query_id)
							->get();

			$pdf->SetFont('times','',11);

			$counter = 1;
			$is_live_signature=0;
			$sign_data='';
			$query_date = Carbon::now();
			
			
				
			foreach ($request_data as $data){
				$pdf->SetTextColor(0,0,0);
					//$query_data = $data->checklist_item.': '.$data->query;
					$query_data = $data->query;
					$pdf->Cell(12,5,$counter.'. ',0,0);

					// $pdf->WriteHTML($query_data, true, false, true, true);
					if($query_data != ''){
						$pdf->WriteHTML($query_data); 
						$pdf->ln();
					}
					

				$counter++;
			}//setPageMark

			$pdf->cell(10,3,'',0,1);
			$template = "<p  align='justify'>If you fail to furnish the requested information within the stipulated period, your application will be treated as invalid and be rejected</b></p>";
			$pdf->WriteHTML($template); 
			$pdf->ln();

			$dt =strtotime($query_date); //gets dates instance
			$year = date("Y", $dt);
			$month = date("F", $dt);
			$day = date("d", $dt);

				$pdf->Cell(0, 0,'Dated this '.$day.' day of '.$month.', '.$year, 0, 1, '', 0, '', 3);
			$pdf->cell(0,8,'',0,1);
					$startY = $pdf->y;
			$startX =$pdf->x;
			$signiture = getcwd() . '/backend/resources/templates/signatures_uploads/dg_sinatory.png';
			//$pdf->Image($signiture,$startX+75,$startY-7,30,12);
					//$pdf->Cell(0, 0, '___________________________',0,1,'C');
					$pdf->Cell(0, 0, 'On behalf of ZAMRA',0,1,'');
			return response($pdf->Output('Request for Additional Information('.$application_no.').pdf',"I"),200)->header('Content-Type','application/pdf');
																			
						
		
		
	}function getImpPermitCertificateHeader($pdf,$record,$permit_title){
										// add a page
										$pdf->AddPage();
									
										$pdf->SetLineWidth(0.2);
										$pdf->SetLineWidth(1.2);
										$pdf->SetLineWidth(0.4);
										$pdf->setMargins(10,4,10,true);
										
										$pdf->setPageMark();
										
										$pdf->SetLineWidth(0.2);
										$org_info = getOrganisationInfo();
										$logo = getcwd() . '/resources/images/org-logo.png';
										$pdf->Image($logo, 10, 10, 27, 29);
										
										
										
										$pdf->SetFont('times','B',9);
										$pdf->SetFont('times','B',10);
										$pdf->Cell(0,7,strtoupper($org_info->name),0,1, 'R');
										$pdf->Cell(0,7,'Telephone '.$org_info->telephone_nos,0,1, 'R');
										$pdf->Cell(0,7,'Address '.$org_info->physical_address,0,1, 'R');
										$pdf->Cell(0,7,$org_info->region_name.', '.$org_info->country_name.'. Website: '.$org_info->website,0,1, 'R');
										$pdf->ln();
										$startY = $pdf->GetY()-3;
										$startX = $pdf->GetX();
										
										$pdf->SetLineWidth(0.2);
										$pdf->Line(0+10,$startY,198,$startY);
										
										$pdf->SetFont('','B',15);
										$pdf->MultiCell(0,5,strtoupper($permit_title),0,'C',0,1);
										
										$pdf->SetFont('','',10);
										$pdf->Cell(0,7,'Date: '.formatDateRpt($record->approval_date),0,1, 'R');
										
			
	}
	function getExpImpLicenseCertificateHeader($pdf,$record,$permit_title){
										// add a page
										$pdf->AddPage();
										
										$pdf->SetLineWidth(0.2);
										$pdf->SetLineWidth(1.2);
										$pdf->SetLineWidth(0.4);
										$pdf->setMargins(10,10,10,true);
										
										$pdf->setPageMark();
										
										$pdf->SetLineWidth(0.2);
										$org_info = getOrganisationInfo();
										$logo = getcwd() . '/resources/images/org-logo.png';
										$pdf->Image($logo, 10, 10, 27, 29);
										
										
										
										$pdf->SetFont('times','B',9);
										$pdf->SetFont('times','B',10);
										$pdf->Cell(0,7,strtoupper($org_info->name),0,1, 'R');
										$pdf->Cell(0,7,'Telephone '.$org_info->telephone_nos,0,1, 'R');
										$pdf->Cell(0,7,'Address '.$org_info->physical_address,0,1, 'R');
										$pdf->Cell(0,7,$org_info->region_name.', '.$org_info->country_name.'. Website: '.$org_info->website,0,1, 'R');
										$pdf->ln();
										$startY = $pdf->GetY()-3;
										$startX = $pdf->GetX();
										
										$pdf->SetLineWidth(0.2);
										$pdf->Line(0+10,$startY,198,$startY);
										
										$pdf->SetFont('','B',15);
										$pdf->MultiCell(0,5,strtoupper($permit_title),0,'C',0,1);
										
										$pdf->SetFont('','',10);
										$pdf->Cell(0,7,'Date: '.formatDateRpt($record->approval_date),0,1, 'R');
										
			
	}
	public function printExportLicense($application_code,$record,$permit_watermark,$is_preview= false){
		try{
				$record = DB::table('tra_importexport_applications as t1')
						->join('sub_modules as t2','t1.sub_module_id','t2.id')
						->leftJoin('wb_trader_account as t3','t1.applicant_id', 't3.id')
						->leftJoin('par_countries as t4', 't3.country_id', 't4.id')
						->leftJoin('par_regions as t5', 't3.region_id', 't5.id')
						->leftJoin('par_ports_information as t6', 't1.port_id', 't6.id')
						->leftJoin('tra_managerpermits_review as t7', 't1.application_code', 't7.application_code')
						->leftJoin('users as t8', 't7.permit_signatory', 't8.id')
						->leftJoin('tra_permitsenderreceiver_data as t9','t1.sender_receiver_id', 't9.id')
						->leftJoin('par_countries as t10', 't9.country_id', 't10.id')
						->leftJoin('par_regions as t11', 't9.region_id', 't11.id')
						->leftJoin('par_modesof_transport as t12', 't1.mode_oftransport_id', 't12.id')
						->leftJoin('tra_managerpermits_review as t13', 't1.application_code', 't13.application_code')
						->leftJoin('tra_consignee_data as t14', 't1.consignee_id', 't14.id')
						->leftJoin('par_sections as t15', 't1.section_id', 't15.id')
						->leftJoin('tra_premises as t16', 't1.premise_id', 't16.id')
						->leftJoin('par_sections as t17', 't1.section_id', 't17.id')
						->leftJoin('par_permitsproduct_categories as t18', 't1.permit_productscategory_id', 't18.id')
						->select('t2.title','t1.premise_id', 't13.expiry_date as permit_expiry_date','t18.name as permit_productscat', 't17.name as permit_productscategory', 't3.physical_address as applicant_physical_address', 't3.postal_address as applicant_postal_address', 't15.name  as product_category','t16.*','t2.title as permit_title','t13.permit_no','t14.name as consignee_name', 't1.sub_module_id', 't1.*','t16.name as premise_name','t3.name as applicant_name','t2.action_title','t6.name as port_entry', 't3.*', 't4.name as country_name', 't5.name as region_name','t7.permit_signatory', 't7.approval_date', DB::raw("concat(decrypt(t8.first_name),' ',decrypt(t8.last_name)) as permit_signatoryname,t9.postal_address as supplierpostal_address, t9.name as suppler_name, t9.physical_address as suppler_address,'t7.expiry_date', t10.name as supplier_country, t11.name as supplier_region, t9.postal_address as supplier_postal_address, t12.name as mode_of_transport"))
						->where('t1.application_code',$application_code)
						->first();

						$sub_module_id = $record->sub_module_id;
						$permit_title = $record->permit_title;
						$action_title = $record->action_title;
						$consignee_name  = $record->consignee_name ;
						$approval_date = '';
						if($record->approval_date != ''){
								$approval_date = $record->approval_date;
						}
						if($record){
							$org_info = getOrganisationInfo();
												
								$pdf = new PdfImpExpLicenseProvider();
								$this->getExpImpLicenseCertificateHeader($pdf,$record,$permit_title);
								
								$pdf->SetFont('','',10);
								 $pdf->Cell(0,7,'Export License No: '.$record->permit_no,0,1);
								 $pdf->ln();
								 if(validateIsNumeric($record->premise_id)){
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Name of Exporter: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->premise_name,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'TIN: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->tpin_no,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Premise No: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->premise_reg_no,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Postal Address: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->postal_address,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Physical Location: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->physical_address,0,1);
									 
								 }
								 else{
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Name of Exporter: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_name,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'TIN: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->tin_no,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Postal Address: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_postal_address,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Physical Location: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_physical_address,0,1);
									 
								 }
								 
								 
								$this->funcImpGenerateQrCode($record,$pdf);
								 $pdf->ln();
								 $startY = $pdf->GetY()-5;
										$startX = $pdf->GetX();
										$pdf->SetLineWidth(0.8);
										$pdf->Line(0+10,$startY,198,$startY);
											$pdf->SetFont('','B',10);
											
								$pdf->SetFont('','B',10);
									// $pdf->Cell(40,7,'Product Category: ',0,0);
									 $pdf->SetFont('','',10);
									// $pdf->Cell(0,7,$record->permit_productscategory,0,1);
									 $pdf->MultiCell(40,7,'Product Category:',0,'',0,0);
									 $pdf->MultiCell(0,7,$record->permit_productscategory.' '.$record->permit_productscat,0,'',0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Transport Mean:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->mode_of_transport,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Port Of Entry:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->port_entry,0,1);
								$pdf->SetFont('','',10);
								$pdf->setCellHeightRatio(1.8);
									$pdf->ln();
								$pdf->SetFont('','B',9);
								$pdf->SetLineWidth(0.1);
								$pdf->Cell(10,7,'No',1,0);
								$pdf->Cell(35,7,'Product',1,0);
								$pdf->Cell(25,7,'Pack Size',1,0);
								$pdf->Cell(25,7,'Batch/serial #',1,0);
								$pdf->Cell(25,7,'Mgf Date(s)',1,0);
								$pdf->Cell(25,7,'Quantity',1,0);
								$pdf->Cell(25,7,'Unit Value',1,0);
								$pdf->Cell(0,7,'Total Value',1,1);$pdf->SetFont('','',9);
							$prod_rec = DB::table('tra_permits_products as t1')
																		->leftJoin('tra_product_information as t2', 't1.product_id', 't2.id')
																		->leftJoin('par_dosage_forms as t3', 't1.dosage_form_id', 't3.id')
																		->leftJoin('par_packaging_units as t4', 't1.packaging_unit_id', 't4.id')
																		->leftJoin('par_common_names as t5', 't1.common_name_id', 't5.id')
																		->leftJoin('par_si_units as t6', 't1.unitpack_unit_id', 't6.id')
																		->leftJoin('par_currencies as t7', 't1.currency_id', 't7.id')
																		->leftJoin('tra_manufacturers_information as t8', 't1.manufacturer_id', 't8.id')
																		->leftJoin('par_countries as t9', 't1.country_oforigin_id', 't9.id')
																		->select('t1.*', 't1.manufacturer_name as permitmanufacturer_name','t7.name as currency_name','t8.name as manufacturer_name',  't4.name as packaging_unit','t1.product_strength','t5.name as generic_name','t1.permitcommon_name', 't2.brand_name','t9.name as country_name', 't3.name as dosage_form', 't6.name as si_unit', 't1.unitpack_size', 't1.product_strength')
																		->where(array('application_code'=>$record->application_code))
																		->get();
											$prod_counter = $prod_rec->count();		
								$currency_name = '';											
								$total_amount = 0;											
								if($prod_counter >0){
											$i=1;
									foreach($prod_rec as $rec){
										if(validateIsNumeric($rec->product_id)){
												if($rec->generic_name == ''){
													$generic_name = $rec->permitcommon_name ;
												}
												$permit_brandname = $rec->brand_name.' '.$generic_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;
												//$permit_brandname = $rec->brand_name.' '.$rec->generic_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;
										}
										else{	
												$permit_brandname = $rec->permitbrand_name.' '.$rec->permitcommon_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;

										}
										
										if($rec->permitmanufacturer_name != ''){
											
											$permit_brandname .= ' . Manufacturer: '.$rec->permitmanufacturer_name.' Country: '.$rec->country_name;
												
										}
										else{
											if($rec->manufacturer_name != ''){
												$permit_brandname .= ' . Manufacturer: '.$rec->manufacturer_name.' Country: '.$rec->country_name;
												
											}
											
										}
										
										$amount = $rec->unit_price*$rec->quantity;										
										$packaging_data = $rec->unitpack_size.' '.$rec->si_unit;
										$product_batch_no = $rec->product_batch_no;
										$manufacturing_dates = 'Mgf Date: '.formatDateRpt($rec->product_manufacturing_date).' Exp. Date'.formatDateRpt($rec->product_expiry_date);
											
											$rowcount = max(PDF::getNumLines($permit_brandname, 35),PDF::getNumLines($packaging_data, 25),PDF::getNumLines($packaging_data, 25),PDF::getNumLines($packaging_data, 25),PDF::getNumLines($product_batch_no, 25),PDF::getNumLines($manufacturing_dates, 25));
											
											
											
											$pdf->MultiCell(10,5*$rowcount,$i,1,'',0,0);
											$pdf->MultiCell(35,5*$rowcount,$permit_brandname,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$rec->unitpack_size.' '.$rec->si_unit,1,'',0,0);
											
											$pdf->MultiCell(25,5*$rowcount,$product_batch_no,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$manufacturing_dates,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$rec->quantity.' '.$rec->packaging_unit,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,($rec->unit_price).' ',1,'',0,0);
											$pdf->MultiCell(0,5*$rowcount,formatMoney($amount).' '.$rec->currency_name,1,'R',0,1);	
													
											$currency_name = $rec->currency_name;
											$total_amount = $total_amount+$amount;
											$i++;
									} 
									$pdf->Cell(145,7,'Total Value:',1,0, 'R');
										$pdf->Cell(0,7,formatMoney($total_amount).' '.$currency_name,1,1, 'R');
								}   $pdf->SetFont('','',10);
								$pdf->ln();
								
							 $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Name of the Receiver:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->suppler_name,0,1);
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Address:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->supplierpostal_address,0,1);
									
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Physical Location:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->suppler_address,0,1);
									 
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Country:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->supplier_country,0,1);
									 
								$pdf->ln();
								
								$pdf->WriteHTML("This certificate authorizes the above exporter to export the products specified in Invoice number  <b>".$record->proforma_invoice_no."</b> after complying with the exportation requirements.", true, 0, true, true,'J');
								
								
								
								$pdf->WriteHTML("All consignments to be exported must be inspected at port of exit or at exporter’s premises before being shipped to ensure that they comply with claimed specifications.", true, 0, true, true,'J');
								
								$pdf->Cell(45,8,'This Certficate is valid up to: ',0,0);
								$pdf->SetFont('','B',10);
								$pdf->Cell(0,8,formatDateRpt($record->permit_expiry_date),0,0);
								$pdf->SetFont('','',10);
								
												
								$permit_signitory = '';
								$title= 'ACTING';
								$title= '';
								$approved_by = '';
										
								$this->getImportCertificateSignatoryDetail($record,$pdf);
									if($permit_watermark != ''){
									
									$this->printWaterMark($pdf,$permit_watermark);
								}	
								$pdf->Output($permit_title.'.pdf');

						}
					
										
					
		}catch (\Exception $exception) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $exception->getMessage()
				);
			} catch (\Throwable $throwable) {
				//DB::rollBack();
				$res = array(
					'success' => false,
					'message' => $throwable->getMessage()
				);
			}
			print_r($res);
			exit();
			//
        return response()->json($res);
		
		
		
		
	}function funcImpGenerateQrCode($row,$pdf){
								$public_ipdomain = Config('constants.base_url.public_ipdomain');

								$data = $public_ipdomain.'permitValidation?application_code='.base64_encode($row->application_code).'&module_id='.base64_encode($row->module_id);

								//$data = "application_code:".$row->certificate_no."; Brand Name:".$row->brandName.";Expiry Date:".formatDate($row->expiry_date);
								 $styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
								// QRCODE,H : QR-CODE Best error correction
								$template_url = getcwd();
								$qrc_code = $template_url . '/resources/images/qrc_code.jpg';
								$width = 16;
								$height = 16;
								
								$qr_codex = 178;
								$qr_codey = 65;
								$pdf->write2DBarcode($data, 'QRCODE,H', $qr_codex,$qr_codey , $width, $height);
							   //$pdf->Image($qrc_code,$qr_codex+$width-4,$qr_codey,$width-3,$height-4);
								
		
	}	function generatePaymentInvoiceHeader($pdf,$org_rec,$rec,$title){
		
		$pdf->Cell(0,20,'',0,1);
		$pdf->SetFont('times', 'B', 13);
			   if(isset($rec->invoice_no)){
				   	   $data = '{"invoice_no":'.$rec->invoice_no.',"module_id":'.$rec->module_id.',"application_code":'.$rec->application_code.'}';
			   }
			   else{
				   	   $data = '{"receipt_no":'.$rec->receipt_no.',"module_id":'.$rec->module_id.',"application_code":'.$rec->application_code.'}';
				   
			   }
		

				$styleQR = array('border' => false, 'padding' => 0, 'fgcolor' => array(0, 0, 0), 'bgcolor' => false);
																							
				$pdf->write2DBarcode($data, 'QRCODE,H', 178, 28, 16, 16);
               $pdf->Cell(0, 7, strtoupper($title), 0, 2, 'C');
				$pdf->SetFont('times', 'B', 11);
				
	}
	
	public function printApplicationPOSTPaymentReceipt($payment_id,$request, $permit_previewoption,$upload_directory=null){
			

		$table_name = $request->input('table_name');
        $application_code = $request->input('application_code');
       
        $application_id = $request->input('application_id');
        $module_id = $request->input('module_id');
		
		if(validateIsNumeric($module_id)){
			$module_details = getTableData('modules', array('id' => $module_id));
            $table_name = $module_details->table_name;
		}
		
        if(validateIsNumeric($application_code)){
            $reference_no = getSingleRecordColValue($table_name, array('application_code' => $application_code), 'reference_no');
           // $payment_id = getSingleRecordColValue('tra_payments', array('application_code' => $application_code), 'id');
        }
        else{
            
            $reference_no = getSingleRecordColValue($table_name, array('id' => $application_id), 'reference_no');
            
        }
		$payment_receivedby = '';
		
		//check the paymetn Control Number process_id
		$rec = DB::table('tra_postpayments_requests as t1')
					->join('wb_trader_account as t2','t1.applicant_id', 't2.id')
					->leftJoin('par_countries as t3', 't2.country_id','t3.id')
					->leftJoin('par_regions as t4', 't2.region_id','t4.id')
					->leftJoin('modules as t5', 't1.module_id','t5.id')
					->leftJoin('sub_modules as t6', 't1.sub_module_id','t6.id')
					->leftJoin('par_currencies as t7', 't1.currency_id','t7.id')
					->leftJoin('par_payment_modes as t8', 't1.payment_mode_id','t8.id')
					->leftJoin('users as t9', 't1.usr_id','t9.id')
					->select('t1.*','t2.name as applicant_name','t8.name as payment_mode', 't7.name as currency_name', 't2.postal_address', 't2.email','t3.name as country_name','t4.name as region_name', 't5.name as module_name', 't6.name as sub_module', DB::raw(" CONCAT_WS(' ',decrypt(t9.first_name),decrypt(t9.last_name)) as payment_receivedby"))
					->where(array('t1.id'=>$payment_id))->first();
		if($rec){	
			$payment_type_id = $rec->payment_type_id;
			$module_id = $rec->module_id;
			$application_code = $rec->application_code;
			$payment_receivedby = $rec->payment_receivedby;
			if($payment_type_id == 3){
				$this->funcGenerateCreditNote($payment_id);
				
			}
			else{
				
				$pdf = new PdfLettersProvider();
				$pdf->AddPage('');
				$template_url = base_path('/');
				$pdf->setSourceFile($template_url."resources/templates/certificate_template.pdf");
																		// import page 1
				$tplId = $pdf->importPage(1);	
				$pdf->useTemplate($tplId,0,0);
				$pdf->setPageMark();
							
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$org_rec = getSingleRecord('tra_organisation_information', array('id'=>1));
				$logo = getcwd() . '/resources/images/zamra-logo.png';
				$org_rec = getSingleRecord('tra_organisation_information', array('id'=>1));
				$logo = getcwd() . '/resources/images/zamra-logo.png';
				$pdf->SetFont('times', 'B', 12);
				$this->generatePaymentInvoiceHeader($pdf,$org_rec,$rec,'POST PAYMENT REQUEST RECEIPT');
				
				$pdf->SetFont('times','B',11);
				$pdf->Cell(70,7,strtoupper('Account Payee(From)'),0,0); 
				$pdf->Cell(0,7,strtoupper('Request Details'),0,1,'R');
				$pdf->SetFont('times', '', 11);
				$pdf->Cell(70,7,strtoupper($rec->applicant_name),0,0);
				$pdf->Cell(0,7,strtoupper('Request Date:'.$rec->trans_date),0,1, 'R');
				
				$pdf->MultiCell(0,5,strtoupper($rec->region_name.', '.$rec->country_name),0,'',0,0);
				
				$pdf->Cell(0,7,strtoupper('Request Number: '.$rec->receipt_no),0,1,'R');
				$pdf->Cell(70,7,strtoupper($rec->email),0,1);
				//$pdf->Cell(0,7,strtoupper('Payment Mode: '.$rec->payment_mode),0,1, 'R');
				
				$pdf->SetFont('times', 'b', 11);
				
				$pdf->Cell(0,7,strtoupper('Ref No:'. $rec->tracking_no .' '.$rec->reference_no),0,1, 'R');
				
				$pdf->ln();
				
				$pdf->SetFont('times', 'b', 11);
				
				$pdf->SetFont('times','',11);
				
						$pdf->SetFont('times','B',11);
				
				$pdf->MultiCell(0,7,'Post Payment Request Receipt/Payments Details for '.$rec->module_name.' ('.$rec->sub_module.')',0,'',0,1);
				$invoice_details = getInvoiceDetails($module_id, 0,$application_code);
			 $app_description= '';
			if(isset($invoice_details)){
				$app_description = $invoice_details['module_desc'];
			}
				//invoice details 
				$pdf->SetLineWidth(0.1);
				$pdf->SetFont('times','B',11);
				$pdf->Cell(15,10,'Sn',1,0);
				$pdf->Cell(140,10,'Being Payment for: ',1,0,'C');
				$pdf->Cell(0,10,'Total',1,1,'C');
				$inv_rec = DB::table('postpayments_references as t1')
								->leftJoin('par_currencies as t2','t1.currency_id','t2.id')
								->leftJoin('tra_element_costs as t3','t1.element_costs_id','t3.id')
								->leftJoin('par_cost_elements as t4','t3.element_id','t4.id')
								->leftJoin('par_fee_types as t5','t3.feetype_id','t5.id')
								->leftJoin('par_cost_categories as t6','t3.cost_category_id','t6.id')
								->select(DB::raw(" t4.name AS cost_element, t5.name AS fee_type, t6.name AS cost_category, t1.amount_paid, t1.currency_id,t2.name as currency_name"))
								->where(array('t1.receipt_id'=>$payment_id))
								->get();
								
				if($inv_rec){
					$i = 1;
					$total_amount = 0;
					$currency_name = '';
					$currency_id = '';$pdf->SetLineWidth(0.1);
					foreach($inv_rec as $inv){
						$currency_name = $inv->currency_name;
						$cost_item = $inv->fee_type." ".$inv->cost_category." ".$inv->cost_element .'for ('.$app_description.')';
						$pdf->SetFont('times','',11);
							$rowcount = max($pdf->getNumLines($cost_item, 92),$pdf->getNumLines($inv->amount_paid, 40));
						$pdf->MultiCell(15,7*$rowcount,$i,1,'',0,0);
						$pdf->MultiCell(140,7*$rowcount,$cost_item,1,'',0,0);
						$pdf->MultiCell(0,7*$rowcount,formatMoney($inv->amount_paid),1,'R',0,1);
						$total_amount = $total_amount+$inv->amount_paid;
						
						$i++;
					}
					$pdf->SetFont('times','B',11);
					$pdf->MultiCell(155,10,'Sub-Total('.$currency_name.')',1,'R',0,0);
					$pdf->MultiCell(0,10,formatMoney($total_amount),1,'R',0,1);
						
					$pdf->MultiCell(155,10,'Post Payment Request for Total Amount('.$currency_name.')',1,'R',0,0);
					$pdf->MultiCell(0,10,formatMoney($total_amount),1,'R',0,1);
						
				}
				$pdf->SetFont('times','i',11);
				$pdf->MultiCell(0,7,'Amount in words '.ucwords(convert_number_to_words($rec->amount_paid)).'('.$currency_name.')'.' Only',1,'',0,1);
				$pdf->MultiCell(100,7,'Rwanda Food & Drugs Authority',1,'',0,0);
				$pdf->MultiCell(0,7,'Print Date: '.Carbon::now(),1,'',0,1);
					$pdf->AddPage();
					if($module_id == 4 || $module_id == 12){
						$this->GetInmportExportProducts($pdf,$application_code,$sub_module);
					}
			if($permit_previewoption =='preview'){
											
											$pdf->Output($rec->tracking_no.' POST Payment Request Receipt.pdf');											
										}
										else{
											$pdf->Output($upload_directory, "F"); 
										}	
			}
			
		
		}
		else{
			echo "<h4>Receipt details Not Found</h4>";
		}
       
		
	}
	public function printApplicationReceipt($payment_id,$request, $permit_previewoption,$upload_directory=null){
			

		$table_name = $request->input('table_name');
        $application_code = $request->input('application_code');
       
        $application_id = $request->input('application_id');
        $module_id = $request->input('module_id');
		
		if(validateIsNumeric($module_id)){
			$module_details = getTableData('modules', array('id' => $module_id));
            $table_name = $module_details->table_name;
		}
		
        if(validateIsNumeric($application_code)){
            $reference_no = getSingleRecordColValue($table_name, array('application_code' => $application_code), 'reference_no');
           // $payment_id = getSingleRecordColValue('tra_payments', array('application_code' => $application_code), 'id');
        }
        else{
            
            $reference_no = getSingleRecordColValue($table_name, array('id' => $application_id), 'reference_no');
            
        }
		$payment_receivedby = '';
		
		//check the paymetn Control Number process_id
		$rec = DB::table('tra_payments as t1')
					->join('tra_application_invoices as t10','t1.invoice_id', 't10.id')
					->leftJoin('wb_trader_account as t2','t10.applicant_id', 't2.id')
					->leftJoin('par_countries as t3', 't2.country_id','t3.id')
					->leftJoin('par_regions as t4', 't2.region_id','t4.id')
					->leftJoin('modules as t5', 't1.module_id','t5.id')
					->leftJoin('sub_modules as t6', 't1.sub_module_id','t6.id')
					->leftJoin('par_currencies as t7', 't1.currency_id','t7.id')
					->leftJoin('par_payment_modes as t8', 't1.payment_mode_id','t8.id')
					->leftJoin('users as t9', 't1.usr_id','t9.id')
					->select('t1.*','t2.name as applicant_name','t8.name as payment_mode', 't7.name as currency_name', 't2.postal_address', 't2.email','t3.name as country_name','t4.name as region_name', 't5.name as module_name', 't6.name as sub_module', DB::raw(" CONCAT_WS(' ',decrypt(t9.first_name),decrypt(t9.last_name)) as payment_receivedby"))
					->where(array('t1.id'=>$payment_id))->first();
		if($rec){	
			$payment_type_id = $rec->payment_type_id;
			$module_id = $rec->module_id;
			$application_code = $rec->application_code;
			$payment_receivedby = $rec->payment_receivedby;
			if($payment_type_id == 3){
				$this->funcGenerateCreditNote($payment_id);
				
			}
			else{
				
				$pdf = new PdfLettersProvider();
				$pdf->AddPage('');
				$template_url = base_path('/');
				$pdf->setSourceFile($template_url."resources/templates/certificate_template.pdf");
																		// import page 1
				$tplId = $pdf->importPage(1);	
				$pdf->useTemplate($tplId,0,0);
				$pdf->setPageMark();
							
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$org_rec = getSingleRecord('tra_organisation_information', array('id'=>1));
				$logo = getcwd() . '/resources/images/zamra-logo.png';
				$org_rec = getSingleRecord('tra_organisation_information', array('id'=>1));
				$logo = getcwd() . '/resources/images/zamra-logo.png';
				$pdf->SetFont('times', 'B', 12);
				$this->generatePaymentInvoiceHeader($pdf,$org_rec,$rec,'RECEIPT');
				
				 
				$pdf->SetFont('times','B',11);
				$pdf->Cell(70,7,strtoupper('Account Payee(From)'),0,0); 
				$pdf->Cell(0,7,strtoupper('Receipt Details'),0,1,'R');
				$pdf->SetFont('times', '', 11);
				$pdf->Cell(70,7,strtoupper($rec->applicant_name),0,0);
				$pdf->Cell(0,7,strtoupper('Payment Date:'.$rec->trans_date),0,1, 'R');
				
				$pdf->MultiCell(0,5,strtoupper($rec->region_name.', '.$rec->country_name),0,'',0,0);
				
				$pdf->Cell(0,7,strtoupper('Receipt Number: '.$rec->receipt_no),0,1,'R');
				$pdf->Cell(70,7,strtoupper($rec->email),0,0);
				$pdf->Cell(0,7,strtoupper('Payment Mode: '.$rec->payment_mode),0,1, 'R');
				
				$pdf->SetFont('times', 'b', 11);
				
				$pdf->Cell(0,7,strtoupper('Ref No:'. $rec->tracking_no .' '.$rec->reference_no),0,1, 'R');
				
				$pdf->ln();
				
				$pdf->SetFont('times', 'b', 11);
				
				$pdf->SetFont('times','',11);
				
						$pdf->SetFont('times','B',11);
				
				$pdf->MultiCell(0,7,'Receipt/Payments Details for '.$rec->module_name.' ('.$rec->sub_module.')',0,'',0,1);
				$invoice_details = getInvoiceDetails($module_id, 0,$application_code);
				
			 $app_description= '';
			if(isset($invoice_details)){
				$app_description = $invoice_details['module_desc'];
			}
				//invoice details 
				$pdf->SetLineWidth(0.1);
				$pdf->SetFont('times','B',11);
				$pdf->Cell(15,10,'Sn',1,0);
				$pdf->Cell(140,10,'Being Payment for: ',1,0,'C');
				$pdf->Cell(0,10,'Total',1,1,'C');
				$inv_rec = DB::table('payments_references as t1')
								->leftJoin('par_currencies as t2','t1.currency_id','t2.id')
								->leftJoin('tra_element_costs as t3','t1.element_costs_id','t3.id')
								->leftJoin('par_cost_elements as t4','t3.element_id','t4.id')
								->leftJoin('par_fee_types as t5','t3.feetype_id','t5.id')
								->leftJoin('par_cost_categories as t6','t3.cost_category_id','t6.id')
								->select(DB::raw(" t4.name AS cost_element, t5.name AS fee_type, t6.name AS cost_category, t1.amount_paid, t1.currency_id,t2.name as currency_name"))
								->where(array('t1.receipt_id'=>$payment_id))
								->get();
								
				if($inv_rec){
					$i = 1;
					$total_amount = 0;
					$currency_name = '';
					$currency_id = '';$pdf->SetLineWidth(0.1);
					foreach($inv_rec as $inv){
						$currency_name = $inv->currency_name;
						$cost_item = $inv->fee_type." ".$inv->cost_category." ".$inv->cost_element .'for ('.$app_description.')';
						$pdf->SetFont('times','',11);
							$rowcount = max($pdf->getNumLines($cost_item, 92),$pdf->getNumLines($inv->amount_paid, 40));
						$pdf->MultiCell(15,7*$rowcount,$i,1,'',0,0);
						$pdf->MultiCell(140,7*$rowcount,$cost_item,1,'',0,0);
						$pdf->MultiCell(0,7*$rowcount,formatMoney($inv->amount_paid),1,'R',0,1);
						$total_amount = $total_amount+$inv->amount_paid;
						
						$i++;
					}
					$pdf->SetFont('times','B',11);
					$pdf->MultiCell(155,10,'Sub-Total('.$currency_name.')',1,'R',0,0);
					$pdf->MultiCell(0,10,formatMoney($total_amount),1,'R',0,1);
						
					$pdf->MultiCell(155,10,'Received with thanks Total Amount('.$currency_name.')',1,'R',0,0);
					$pdf->MultiCell(0,10,formatMoney($total_amount),1,'R',0,1);
						
				}
				$pdf->SetFont('times','i',11);
				$pdf->MultiCell(0,7,'Amount in words '.ucwords(convert_number_to_words($rec->amount_paid)).'('.$currency_name.')'.' Only',1,'',0,1);
				$pdf->MultiCell(100,7,'Received By: Rwanda Food & Drugs Authority',1,'',0,0);
				$pdf->MultiCell(0,7,'Print Date: '.Carbon::now(),1,'',0,1);
					$pdf->AddPage();
					if($module_id == 4 || $module_id == 12){
						$this->GetInmportExportProducts($pdf,$application_code,$sub_module);
					}
			if($permit_previewoption =='preview'){
											
											$pdf->Output($rec->tracking_no.' Payment Receipt.pdf');											
										}
										else{
											$pdf->Output($upload_directory, "F"); 
										}	
			}
			
		
		}
		else{
			echo "<h4>Receipt details Not Found</h4>";
		}
       
		
	}
	public function GetInmportExportProducts($pdf,$application_code,$sub_module){
		$portal_databasename = DB::connection('portal_db')->getDatabaseName();
		
			$record = DB::table($portal_databasename.'.wb_importexport_applications as t1')
						->join('sub_modules as t2','t1.sub_module_id','t2.id')
						->leftJoin('wb_trader_account as t3','t1.trader_id', 't3.id')
						->leftJoin('par_countries as t4', 't3.country_id', 't4.id')
						->leftJoin('par_regions as t5', 't3.region_id', 't5.id')
						->leftJoin('par_ports_information as t6', 't1.port_id', 't6.id')
						->leftJoin('tra_managerpermits_review as t7', 't1.application_code', 't7.application_code')
						->leftJoin('users as t8', 't7.permit_signatory', 't8.id')
						->leftJoin('tra_permitsenderreceiver_data as t9','t1.sender_receiver_id', 't9.id')
						->leftJoin('par_countries as t10', 't9.country_id', 't10.id')
						->leftJoin('par_regions as t11', 't9.region_id', 't11.id')
						->leftJoin('par_modesof_transport as t12', 't1.mode_oftransport_id', 't12.id')
						->leftJoin('tra_managerpermits_review as t13', 't1.application_code', 't13.application_code')
						->leftJoin('tra_consignee_data as t14', 't1.consignee_id', 't14.id')
						->leftJoin('par_sections as t15', 't1.section_id', 't15.id')
						->leftJoin('tra_premises as t16', 't1.premise_id', 't16.id')
						->leftJoin('par_sections as t17', 't1.section_id', 't17.id')
						->leftJoin('par_business_types as t20', 't16.business_type_id', 't20.id')
						
							->leftJoin('par_permitsproduct_categories as t18', 't1.permit_productscategory_id', 't18.id')
						->select('t2.title','t20.name as business_type', 't1.premise_id','t13.expiry_date as permit_expiry_date','t18.name as permit_productscat', 't17.name as permit_productscategory', 't3.physical_address as applicant_physical_address', 't3.postal_address as applicant_postal_address', 't15.name  as product_category','t16.*','t2.title as permit_title','t13.permit_no','t14.name as consignee_name', 't1.sub_module_id', 't1.*','t16.name as premise_name','t3.name as applicant_name','t2.action_title','t6.name as port_entry', 't3.*', 't4.name as country_name', 't5.name as region_name','t7.permit_signatory', 't7.approval_date', DB::raw("concat(decrypt(t8.first_name),' ',decrypt(t8.last_name)) as permit_signatoryname, t9.name as suppler_name, t9.physical_address as suppler_address,'t13.expiry_date',  t9.postal_address as supplierpostal_address,
									 t10.name as supplier_country, t11.name as supplier_region, t9.postal_address as supplier_postal_address, t12.name as mode_of_transport"))
						->where('t1.application_code',$application_code)
						->first();
						$sub_module_id = $record->sub_module_id;
						$permit_title = $record->permit_title;
						$action_title = $record->action_title;
						$consignee_name  = $record->consignee_name ;
						$approval_date = '';
						if($record->approval_date != ''){
								$approval_date = $record->approval_date;
						}
						
						if($record){
							
								 if($record->sub_module_id == 82){
									 
										//$pdf->Cell(0,7,'Visa  No: ',0,1);
								 }
									$pdf->SetFont('times','B',14);
								$pdf->Cell(0,8,'Application Details',0,1);
								$pdf->setCellHeightRatio(1.8);
								 if(validateIsNumeric($record->premise_id)){
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Name of Importer: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->premise_name,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'TIN: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->tpin_no,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Premise No: ',0,0);
									 $pdf->SetFont('','',10);
									 //$pdf->Cell(0,7,$record->premise_reg_no,0,1);
									 $pdf->MultiCell(0,7,$record->premise_reg_no.' ('.$record->business_type.')',0,'',0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Postal Address: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->postal_address,0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Physical Location: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->physical_address,0,1);
									 
								 }
								 else{
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Name of Importer: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_name,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'TIN: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->tin_no,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Postal Address: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_postal_address,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Physical Location: ',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->applicant_physical_address,0,1);
									 
								 }
								 
								 $pdf->ln();
								 $startY = $pdf->GetY()-5;
										$startX = $pdf->GetX();
										$pdf->SetLineWidth(0.2);
										$pdf->Line(0+10,$startY,198,$startY);
											$pdf->SetFont('','B',10);
								
								$pdf->SetFont('','B',10);
									// $pdf->Cell(40,7,'Product Category: ',0,0);
									 $pdf->SetFont('','',10);
									// $pdf->Cell(0,7,$record->permit_productscategory,0,1);
									  $pdf->MultiCell(40,7,'Product Category:',0,'',0,0);
									 $pdf->MultiCell(0,7,$record->permit_productscategory.' ('.$record->permit_productscat.')',0,'',0,1);
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Transport Mean:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->mode_of_transport,0,1);
									 
									 $pdf->SetFont('','B',10);
									 $pdf->Cell(40,7,'Port Of Entry:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->port_entry,0,1);
									 
								$pdf->SetFont('','',10);
								$pdf->setCellHeightRatio(1.8);
									$pdf->ln();
								$pdf->SetFont('','B',9);
								$pdf->SetLineWidth(0.1);
								
								
								$pdf->SetFont('','',9);
							$records = DB::table($portal_databasename.'.wb_permits_products as t1')
								->leftJoin('tra_product_information as t2', 't1.product_id', 't2.id')
								->leftJoin('par_dosage_forms as t3', 't1.dosage_form_id', 't3.id')
								->leftJoin('par_packaging_units as t4', 't1.packaging_unit_id', 't4.id')
								->leftJoin('par_common_names as t5', 't1.common_name_id', 't5.id')
								->leftJoin('par_si_units as t6', 't1.unitpack_unit_id', 't6.id')
								->leftJoin('par_currencies as t7', 't1.currency_id', 't7.id')
								->leftJoin('tra_manufacturers_information as t8', 't1.manufacturer_id', 't8.id')
								->leftJoin('par_countries as t9', 't1.country_oforigin_id', 't9.id')
								->select('t1.*','t7.name as currency_name','t8.name as manufacturer_name',  't4.name as packaging_unit','t1.product_strength','t5.name as generic_name','t1.permitcommon_name', 't2.brand_name','t9.name as country_name', 't3.name as dosage_form', 't6.name as si_unit', 't1.unitpack_size', 't1.product_strength')
								->where(array('t1.application_code'=>$application_code))
								->get();
								$pdf->SetFont('times','B',14);
								$pdf->Cell(0,8,$sub_module.' Product Listing',0,1);
								$pdf->SetFont('times','',10);
											$i=1;		
								if($records->count() >0){
									$pdf->SetFont('times','B',10);
									$pdf->Cell(10,7,'No',1,0);
									$pdf->Cell(35,7,'Product',1,0);
									$pdf->Cell(25,7,'Pack Size',1,0);
									$pdf->Cell(25,7,'Batch/serial #',1,0);
									$pdf->Cell(25,7,'Mgf Date(s)',1,0);
									$pdf->Cell(25,7,'Quantity',1,0);
									$pdf->Cell(20,7,'Unit Value',1,0);
									$pdf->Cell(0,7,'Total Value',1,1);
									$pdf->SetFont('times','',10);
									foreach($records  as $rec){
											if(validateIsNumeric($rec->product_id)){
																$generic_name = $rec->permitcommon_name ;
																	if($rec->permitcommon_name == ''){
																		$generic_name = $rec->generic_name ;
																	}
																	$permit_brandname = $rec->brand_name.' '.$generic_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;
															}
															else{	
															$generic_name = $rec->generic_name ;
															$generic_name = $rec->permitcommon_name ;
																	if($rec->permitcommon_name == ''){
																		$generic_name = $rec->generic_name ;
																	}
																	$permit_brandname = $rec->permitbrand_name.' '.$generic_name .' '.$rec->product_strength.' '.$rec->dosage_form.' '.$rec->unitpack_size;

															}
											$amount = $rec->unit_price*$rec->quantity;										
											$packaging_data = $rec->unitpack_size;
											$product_batch_no = trim($rec->product_batch_no);
											$manufacturing_dates = 'Mgf Date: '.formatDateRpt($rec->product_manufacturing_date).' Exp. Date'.formatDateRpt($rec->product_expiry_date);
											$rowcount = max($pdf->getNumLines($permit_brandname, 25),$pdf->getNumLines($packaging_data, 25),$pdf->getNumLines($packaging_data, 25),$pdf->getNumLines($packaging_data, 25),$pdf->getNumLines($manufacturing_dates, 25),$pdf->getNumLines($product_batch_no, 24));
																
											$pdf->MultiCell(10,5*$rowcount,$i,1,'',0,0);
											$pdf->MultiCell(35,5*$rowcount,$permit_brandname,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$rec->unitpack_size.' '.$rec->si_unit,1,'',0,0);
																
											$pdf->MultiCell(25,5*$rowcount,$product_batch_no,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$manufacturing_dates,1,'',0,0);
											$pdf->MultiCell(25,5*$rowcount,$rec->quantity,1,'',0,0);//.' '.$rec->packaging_unit
											$pdf->MultiCell(20,5*$rowcount,($rec->unit_price).' ',1,'',0,0);
											$pdf->MultiCell(0,5*$rowcount,formatMoney($amount),1,'R',0,1);	
																		
											$currency_name = $rec->currency_name;
											$total_amount = $total_amount+$amount;
											$i++;
										
									}
									 $pdf->SetFont('','B',10);
														$pdf->Cell(145,8,'Total Value in ('.$currency_name.')',1,0, 'R');
															$pdf->Cell(0,8,formatMoney($total_amount),1,1, 'R');
								}   $pdf->SetFont('','',10);
								
								
								 $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Name of the Supplier:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->suppler_name,0,1);
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Address:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->supplierpostal_address,0,1);
									
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Physical Location:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->suppler_address,0,1);
									 
									 
									  $pdf->SetFont('','B',10);
									 $pdf->Cell(55,7,'Country:',0,0);
									 $pdf->SetFont('','',10);
									 $pdf->Cell(0,7,$record->supplier_country,0,1);
		
		
			
			}
		
		
	}
	public function printApplicationInvoice($request,$permit_previewoption=null,$upload_directory=null)
    {
        $invoice_id = $request->input('invoice_id');
        $application_id = $request->input('application_id');
        $application_code = $request->input('application_code');
        $module_id = $request->input('module_id');
		 $sub_module_id = $request->input('sub_module_id');
		 if(!validateIsNumeric($module_id)){
			 $module_details = getTableData('sub_modules', array('id' => $sub_module_id));
			 if($module_details){
				  $module_id = $module_details->module_id;
		
			 }
       	 
		 }
		            
		if(!validateIsNumeric($invoice_id)){
			$invoice_record = DB::table('tra_application_invoices')->where('application_code',$application_code)->first();
			if($invoice_record){
					$invoice_id = $invoice_record->id;
			}
		 }
		  
		//check the paymetn Control Number 
		$rec = DB::table('tra_application_invoices as t1')
					->join('wb_trader_account as t2','t1.applicant_id', 't2.id')
					->leftJoin('par_countries as t3', 't2.country_id','t3.id')
					->leftJoin('par_regions as t4', 't2.region_id','t4.id')
					->leftJoin('modules as t5', 't1.module_id','t5.id')
					->leftJoin('sub_modules as t6', 't1.sub_module_id','t6.id')
					->leftJoin('tra_iremboinvoices_information as t8', 't1.invoice_no','t8.rfdaInvoiceNo')
					->select('t1.*','t2.name as applicant_name','t8.iremboInvoiceNumber', 't2.postal_address', 't2.email','t3.name as country_name','t4.name as region_name', 't5.name as module_name', 't6.name as sub_module')
					->where(array('t1.id'=>$invoice_id))->first();
					$module_id = $rec->module_id;
					$application_code = $rec->application_code;
					
					$invoice_details = getInvoiceDetails($module_id, $application_id,$application_code);
		 $app_description= '';
		if(isset($invoice_details)){
            $app_description = $invoice_details['module_desc'];
        }
		if($rec){
			$PayCntrNum = $rec->PayCntrNum;
				$sub_module_id = $rec->sub_module_id;
			
			 $module_name = getSingleRecordColValue('modules', array('id' => $rec->module_id), 'name');
			$sub_module_name = getSingleRecordColValue('sub_modules', array('id' => $rec->sub_module_id), 'name');
			$section_name = getSingleRecordColValue('par_sections', array('id' => $rec->section_id), 'name');
           
					$params = array(
						'invoice_id' => $invoice_id,
						'application_code'=>$application_code
					);
					
				$org_info = $this->getOrganisationInfo();
				$pdf = new PdfLettersProvider();
				$pdf->AddPage();
				$template_url = base_path('/');
				$pdf->setSourceFile($template_url."resources/templates/certificate_template.pdf");
																		// import page 1
				$tplId = $pdf->importPage(1);	
				$pdf->useTemplate($tplId,0,0);
				$pdf->setPageMark();
							
							
				$pdf->SetFont('times','B',9);
				$pdf->Cell(0,1,'',0,1);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				
				$org_rec = getSingleRecord('tra_organisation_information', array('id'=>1));
				$logo = getcwd() . '/resources/images/org-logo.jpg';
				$pdf->SetFont('times', 'B', 11);
				
				$this->generatePaymentInvoiceHeader($pdf,$org_rec,$rec,'Proforma Invoice');
			
					$pdf->SetFont('times', 'B', 11);
				$pdf->Cell(60,7,'Invoice Number: '.$rec->invoice_no,0,0);
				$pdf->Cell(0,7,'Invoice Date:'.$rec->date_of_invoicing,0,1, 'R');
				$bill_expiry_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d  H:i:s", strtotime($rec->date_of_invoicing)) . " + 1 month"));
				$pdf->Cell(0,7,'Invoice Due Date:'.$bill_expiry_date,0,1, 'R');
				$pdf->SetFont('times', 'BU', 11);
				
				$pdf->Cell(0,7,strtoupper($section_name.' '.$sub_module_name),0,1, 'C');
				
					$pdf->SetFont('times', 'B', 11);
				$pdf->Cell(0,7,'Customer Details',0,1, '');
				
				
				$pdf->SetFont('times', '', 11);
				
				
				$pdf->Cell(0,7,$rec->applicant_name,0,1);
				$pdf->Cell(0,7,$rec->region_name.', '.$rec->country_name,0,1);
				$pdf->Cell(0,7,$rec->email,0,1);
				$pdf->SetFont('times', 'B', 11);
			   
				$pdf->Cell(0,7,'Invoice Details',0,1);
				$pdf->SetFont('times', '', 11);
			   $invoice_no = $rec->invoice_no;
				$pdf->Cell(0,7,'Ref No:'. $rec->tracking_no ,0,1, '');
				
				$pdf->ln();
				$iremboInvoiceNumber = $rec->iremboInvoiceNumber;
					//		$pdf->Cell(0,7,'Irembo Payment No: '.$iremboInvoiceNumber,0,1,'');
						
				$pdf->SetFont('times', 'B', 11);
			
				
			$pdf->SetFont('times', 'B', 11);
			   
				$pdf->SetLineWidth(0.1);
				//invoice details 
				
				if($sub_module_id == 67){
						$pdf->MultiCell(0,7,'Invoice Details for '.$rec->module_name.' ('.$rec->sub_module,0,'',0,1);
						$pdf->Cell(15,10,'Sn',1,0);
				$pdf->Cell(100,10,'Item Description',1,0,'C');
				$pdf->Cell(40,10,'Price',1,0,'C');
				$pdf->Cell(0,10,'Total',1,1,'C');
					$inv_rec = DB::table('tra_invoice_details as t1')
								->leftJoin('par_currencies as t2','t1.paying_currency_id','t2.id')
								->leftJoin('tra_element_costs as t3','t1.element_costs_id','t3.id')
								->leftJoin('par_cost_elements as t4','t3.element_id','t4.id')
								->leftJoin('par_fee_types as t5','t3.feetype_id','t5.id')
								->leftJoin('par_cost_categories as t6','t3.cost_category_id','t6.id')
								->leftJoin('par_cost_sub_categories as t11','t3.sub_cat_id','t11.id')
								->leftJoin('tra_product_retentions as t7','t1.id','t7.invoice_element_id')
								->leftJoin('tra_registered_products as t8','t7.reg_product_id','t8.id')
								->leftJoin('tra_product_applications as t9','t7.application_code','t9.application_code')
								->leftJoin('tra_product_information as t10','t9.product_id','t10.id')
								->select(DB::raw("t8.registration_no as ma_no,t9.reference_no as application_no,t11.name as cost_subcategory, t10.product_strength,t10.brand_name,   t4.name AS cost_element, t5.name AS fee_type, t6.name AS cost_category, t1.total_element_amount AS invoice_amount, t1.paying_currency_id,t2.name as currency_name"))
								->where(array('t1.invoice_id'=>$invoice_id))
								->get();
				}
				else{
					$pdf->MultiCell(0,7,'Application details:'.$app_description,0,'',0,1);
					$pdf->Cell(15,10,'Sn',1,0);
				$pdf->Cell(100,10,'Item Description',1,0,'C');
				$pdf->Cell(40,10,'Price',1,0,'C');
				$pdf->Cell(0,10,'Total',1,1,'C');
				$inv_rec = DB::table('tra_invoice_details as t1')
								->leftJoin('par_currencies as t2','t1.paying_currency_id','t2.id')
								->leftJoin('tra_element_costs as t3','t1.element_costs_id','t3.id')
								->leftJoin('par_cost_elements as t4','t3.element_id','t4.id')
								->leftJoin('par_fee_types as t5','t3.feetype_id','t5.id')
								->leftJoin('par_cost_categories as t6','t3.cost_category_id','t6.id')
								->select(DB::raw(" t4.name AS cost_element, t5.name AS fee_type, t6.name AS cost_category, t1.total_element_amount AS invoice_amount, t1.paying_currency_id,t2.name as currency_name"))
								->where(array('t1.invoice_id'=>$invoice_id))
								->get();
				}
				if($inv_rec){
					
					
					$i = 1;
					$total_amount = 0;
					$usdtotal_amount = 0;
					$rwf_total_amount = 0;
					$currency_name = '';
					$paying_currency_id = '';
					$pdf->SetFont('times', '', 11);
					foreach($inv_rec as $inv){
						$currency_name = $inv->currency_name;
						//$inv->fee_type." ".
						if($sub_module_id == 67){
							$cost_item = 'Annual Retention Fee for'.$inv->brand_name." ".$inv->ma_no." ".$inv->product_strength;
						
						}
						else{
							$cost_item = $inv->cost_category." ".$inv->cost_element;
						}
						
						$paying_currency_id = $inv->paying_currency_id;
							$rowcount = max($pdf->getNumLines($cost_item, 92),$pdf->getNumLines($inv->invoice_amount, 40));
						$pdf->MultiCell(15,7*$rowcount,$i,1,'',0,0);
						$pdf->MultiCell(100,7*$rowcount,$cost_item,1,'',0,0);
						$pdf->MultiCell(40,7*$rowcount,formatMoney($inv->invoice_amount),1,'R',0,0);
						$pdf->MultiCell(0,7*$rowcount,formatMoney($inv->invoice_amount),1,'R',0,1);
						$total_amount = $total_amount+$inv->invoice_amount;
						if($paying_currency_id == 1){
								$usdtotal_amount = $usdtotal_amount+$inv->invoice_amount;
																	
						}
						else{
							$rwf_total_amount = $rwf_total_amount+$inv->invoice_amount;
						}
						$i++;
					}
					
					/*$pdf->MultiCell(155,10,'Sub-Total('.$currency_name.')',1,'R',0,0);
					$pdf->MultiCell(0,10,formatMoney($total_amount),1,'R',0,1);
						
					$pdf->MultiCell(155,10,'Total('.$currency_name.')',1,'R',0,0);
					$pdf->MultiCell(0,10,formatMoney($total_amount),1,'R',0,1);
					*/
						
					if($usdtotal_amount >0){
						$pdf->MultiCell(155,10,'Sub-Total(USD)',1,'R',0,0);
						$pdf->MultiCell(0,10,formatMoney($usdtotal_amount),1,'R',0,1);
					
					}
					if($rwf_total_amount >0){
						$pdf->MultiCell(155,10,'Sub-Total(RWF)',1,'R',0,0);
						$pdf->MultiCell(0,10,formatMoney($rwf_total_amount),1,'R',0,1);
					
					}
					
					
					if($usdtotal_amount >0){
						$pdf->MultiCell(155,10,'Total(USD)',1,'R',0,0);
						$pdf->MultiCell(0,10,formatMoney($usdtotal_amount),1,'R',0,1);
					}
					if($rwf_total_amount >0){
						$pdf->MultiCell(155,10,'Total(RWF)',1,'R',0,0);
						$pdf->MultiCell(0,10,formatMoney($rwf_total_amount),1,'R',0,1);
					}
				}
				$pdf->MultiCell(0,7,'Note that the payment should be done via Irembo online payment gateway using Billing ID and reference of the Bill Id on bank account as description for international transfer.',0,'',0,1);	
				//get the Bank Details based on the paying currency
				$pdf->Cell(0,7,'1. '.'Irembo Billing ID: '.$iremboInvoiceNumber,0,1,'');
							
				$bank_rec = DB::table('tra_orgbank_accounts as t1')
								->leftJoin('par_banks as t2', 't1.bank_id', 't2.id')
								->leftJoin('par_bankbranches as t3', 't1.branch_id', 't3.id')
								->leftJoin('par_currencies as t4', 't1.currency_id', 't4.id')
								->select(DB::raw("t4.name as currency_name, t1.account_name, t1.account_no, t1.swft_code, t2.name AS bank_name, t3.name AS branch_name"))
								->where(array('t1.currency_id'=>$paying_currency_id))
								->get();
					if($bank_rec){
						//
						$i = 2;
								foreach($bank_rec as $bank){
									$pdf->MultiCell(100,7,$i.'. '.$bank->account_name.' '.$bank->bank_name." ".$bank->branch_name.' '.$bank->currency_name." Account: ".$bank->account_no. " Swift Code: ".$bank->swft_code,0,'',0,1);	
									$i++;
								}
					}		
					
					$pdf->AddPage();
					if($module_id == 4 || $module_id == 12){
						$this->GetInmportExportProducts($pdf,$application_code,$sub_module);
						
					}							
										
				//$pdf->Output( $invoice_no.' Proforma Invoice.pdf', 'I');
					if($permit_previewoption =='preview' || $permit_previewoption == '' || $permit_previewoption == null){
											
											$pdf->Output($invoice_no.' Proforma Invoice.pdf');											
										}
										else{
											$pdf->Output($upload_directory, "F"); 
										}
										
			}
				
			
		else{
			echo "<h4>Invoice details Not Found</h4>";
		}
       
    }
	
	public function printPromotionalRegCertificate($req){
			
					try{
							$application_code = $req->application_code;
							$logo=getcwd().'/assets/images/logo.jpg';
							$pdf = new PdfLettersProvider();
							$this->getPromotionScreeningreportHeader($pdf);
							$pdf->ln();	
					$records = DB::table('tra_promotion_adverts_applications as t1')
											->leftJoin('par_system_statuses as q', 't1.application_status_id', '=', 'q.id')
											->leftJoin('tra_approval_recommendations as t2','t1.application_code', 't2.application_code')
											->join('wb_trader_account as t3', 't1.applicant_id', 't3.id')
											->leftJoin('par_countries as t4', 't3.country_id', 't4.id')
											->leftJoin('par_regions as t5', 't3.region_id', 't5.id')
											->leftJoin('par_sections as t6', 't1.section_id', 't6.id')
											->leftJoin('tra_payments as t7', 't1.application_code', 't7.application_code')
											
											->leftJoin('sub_modules as t8', 't1.sub_module_id', 't8.id')

											->leftJoin('tra_applications_comments as t11', function ($join) {
													$join->on('t1.application_code', '=', 't11.application_code')
															->where('t11.comment_type_id', 2);
											})
											->leftJoin('par_evaluation_recommendations as t12', 't11.recommendation_id', '=', 't12.id')
											->leftJoin('par_promotionmaterial_categories  as t9','t1.promotionmaterial_category_id','=','t9.id')
											
											->leftJoin('users as t17', 't2.permit_signatory', '=', 't17.id')
											->select(DB::raw("concat(decrypt(t17.first_name),' ',decrypt(t17.last_name)) as permit_signatoryname, t2.*, t2.permit_signatory,t8.name as application_type, t2.decision_id as recommendation_id, t12.name as assessment_recommendation,  t9.name as promotionmaterial_category, (t7.trans_date) as receipt_date,t11.comment as screening_comment, t1.*, t3.name as applicant_name,t3.email as email_address, t3.physical_address,t3.telephone_no, t3.postal_address, t4.name as country_name, t5.name as region_name,t6.name as section_name, t1.id as application_id, t2.expiry_date, t11.created_on as reviewed_date "))
											->where('t1.application_code',$application_code)
											->first();
						
								if($records){
									$row = $records;
									$recommendation_id = $row->recommendation_id;
									$ref = $row->reference_no;
									$applicant_name = $row->applicant_name;
									$email_address = $row->email_address;
									$physical_address = $row->physical_address;
									$postal_address = $row->postal_address;
									$region_name = $row->region_name;
									$country_name = $row->country_name;
									$section_id = $row->section_id;
									$section_name = $row->section_name;
									$expiry_date = $row->expiry_date;
									$telephone_no = $row->telephone_no;
									//$intended_user = $row->intended_user;
									$application_id = $row->application_id;
									$received_date = $row->receipt_date;
								
									$pdf->SetFont('','',11);
										$pdf->Cell(0,20,'',0,1);
										$pdf->Cell(60,5,'Ref.:'.$ref,0,0);
										$pdf->Cell(0,5,'Date.:'.date('Y-m-d'),0,1,'R');
											$pdf->ln();
											$pdf->Cell(0,8,'Managing Director,',0,1);
											$pdf->Cell(0,8,$applicant_name,0,1);
											$pdf->Cell(0,8,$email_address,0,1);
											
										
											$pdf->Cell(0,8,'Tel: '.$telephone_no,0,1);
											$pdf->Cell(0,5,$physical_address,0,1);
											//$pdf->Cell(0,8,$row->region_name.','.$row->country_name,0,1);
										//local agent
										$pdf->ln();
										if($section_id == 2){
											$section_name = 'medicines';
										}
										$pdf->SetLineWidth(3);
											$pdf->SetFont('','B',11);
					if($recommendation_id == 1 || $recommendation_id == ''){
												
												$pdf->Cell(0,7,'Subject: Approval of Promotional and Advertisement Materials',0,0);
											}
											else{
												$pdf->Cell(0,7,'Subject: Rejection of Promotiona and Advertisement Materials',0,0);
											
											}
											$pdf->SetFont('','',11);
											$pdf->ln();
											
									$pdf->setCellHeightRatio(1.8);
											$statement_1 = 'Reference is made to the law No 003/2018 or 09/02/2018 establishing Rwanda FDA, especially in its article 8, paragraph 11, whereby Rwanda FDA is mandated to regulate and analyze information used in the promotion, advsertising and marketing of products regulated under this law.' ;
											
											$pdf->writeHTML($statement_1, true, false, false, false, '');
											$pdf->ln();
										$material_rec =	DB::table('tra_promotion_materials_details as t1')
			 
												->join('par_promotion_material_items  as t2','t1.material_id','=','t2.id')
												->select(DB::raw("group_concat(concat(t2.name) separator ' / ') as promotion_material")) 
												->where('t1.application_id',$application_id)
												->first();
										
											$promotion_material = '';
											if($material_rec){
												$promotion_material = $material_rec->promotion_material;
												
											}
											$adverttype_rec =DB::table('tra_promotion_prod_particulars as t1')
													->leftJoin('par_common_names as t2','t1.common_name','=','t2.id')
													->leftJoin('par_product_categories as t3','t1.product_category_id','=','t3.id')
													->leftJoin('par_subproduct_categories as t4','t1.product_subcategory_id','=','t4.id')
													->leftJoin('par_advertisement_types as t5','t1.type_of_advertisement_id','=','t5.id')
													->select(DB::raw(" group_concat(concat(t1.brand_name)  separator ' ') as product_name"))
													->where('t1.application_id',$application_id)
													->first();
											$product_name ='';
											if($adverttype_rec){
												

												$product_name  = $adverttype_rec->product_name;

											}
											$material_rec =	DB::table('tra_promotion_advertisement_channels as t1')
			 
											->join('par_advertisement_channel  as t2','t1.advertisement_channel_id','=','t2.id')
											->select(DB::raw("group_concat(concat(t2.name) separator ' / ') as advertisement_channel")) 
											->where('t1.application_id',$application_id)
											->orderBy('t2.id')
											->first();
										
											$advertisement_channel = '';
											if($material_rec){
												$advertisement_channel = $material_rec->advertisement_channel;
												
											}
											$statement_2 = 'Reference is also made to your application received on '.formatDateRpt($received_date).', applying for approval of promotion and advertisement materials of '.$product_name.'. This letter servers as approval of the promotional and advertisement materials('.$promotion_material.') to be used to promote your product on '.$advertisement_channel.'.';
											
											$pdf->writeHTML($statement_2, true, false, false, false, '');
											$pdf->ln();
											$statement_2 = 'Rwanda FDA would like to remind you that you are not allowed to add another essage or health claim that is not approved. Accordingly,'.$applicant_name.' is responsible for the message and language used by journalists during media mentions. If they add any claim or message that was not approved by Rwanda FDA, administrative sanctions may be applied.';
											
											$pdf->writeHTML($statement_2, true, false, false, false, '');
											
											$pdf->Cell(0,5,'This approval is valid for five (5) years from the of issuance.',0,1);
											
											$pdf->ln();
											$pdf->Cell(0,5,'Sincerely,',0,1);
											
											$this->funcGenerateQrCode($row,$pdf);
								
											$this->getCertificateSignatoryDetail($row,$pdf);
								
								}
									$pdf->Output("Promotional Advertisement.pdf");
	
						return;
						} catch (\Exception $exception) {
								//$pdf->rollBack();
								$res = array(
									'success' => false,
									'message' => $exception->getMessage()
								);
						} catch (\Throwable $throwable) {
								//$pdf->rollBack();
								$res = array(
									'success' => false,
									'message' => $throwable->getMessage()
								);
						}
						print_r($res);

		
		
	}
	
}