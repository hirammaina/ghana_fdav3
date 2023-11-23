<?php
/**
 * Created by PhpStorm.
 * User: Kip
 * Date: 4/9/2019
 * Time: 8:41 PM
 */

namespace App\Modules\Reports\Providers;

use setasign\Fpdi\TcpdfFpdi;
class PdfPremisesLicenseProvider extends TcpdfFpdi
{
  public $params = array();
	public function __construct($qr_data=array()){
			parent::__construct();
		$this->params = $qr_data;
		
	}
 function Header(){
   $this->setMargins(12,25,10,true);
  
		if ($this->PageNo() ==1) {
						$org_info = $this->getOrganisationInfo();
						$logo = getcwd() . '/resources/images/org-logo.jpg';
					$this->Image($logo,16,11,30,33);$this->SetFont('times','B',11);
				$this->ln(6);
				
$this->SetFont('times','',9);
$this->ln();
$this->ln();
			$qms_details = "QMS No: FDISM/FDIC/FMT/0048<br/>Revision No:2<br/>Effective Date: 01 Dec. 2022";
			$this->WriteHTML($qms_details, true, false, true, true, 'R');
		 
$this->SetFont('times','B',13);
			$this->cell(55, 5, '', 0,0, 'C');
				$this->cell(0, 5, $org_info->name, 0,1, '');
				$this->SetFont('times','',9);
			$this->cell(55, 5, '', 0,0, 'C');
				$this->cell(0, 5, $org_info->physical_address, 0,1, '');
			$this->cell(55, 5, '', 0,0, 'C');
				$this->cell(0, 5, $org_info->postal_address, 0,1, '');
			$this->cell(55, 5, '', 0,0, 'C');
			$this->SetTextColor(0,0,255);
				$this->cell(0,5,'Email: '. $org_info->email_address, 0,1, '');
				
		$this->cell(55, 6, '', 0,0, 'C');

$this->cell(0, 6,'Website: '. $org_info->website, 0,1, '');

		}
	}
	
	/*

Rwanda Food and Drugs Authority
          Nyarutarama Plaza, KG 9 Avenue
          P.O. Box: 1948 Kigali - Rwanda 
          Email: info@rwandafda.gov.rw    
          website: www.rwandafda.gov.rw 



*/
  function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-20);
		//Arial italic 8
    $this->SetFont('times','',8);
   
   // $this->Cell(0,4,'Website: www.rwandafda.gov.rw, Email: info@rwandafda.gov.rw',0,1,'C');
    /*
		 if ($this->page == 1) {
          $this->get_Docqrcode($this->params);
          $postion = $this->params['position'];
          $qr_code  = getcwd().'/assets/uploads/app_detail.png';
          $this->Image($qr_code,178,$postion,16);
		 }
     */
		 $this->SetY(-90);
		 
	}public function get_Docqrcode($params){
		$qr_code = new Ciqrcode($params);
		//get the details 
		
		$qr_code->generate($params); 
		
	 }
function getOrganisationInfo(){
			
						$org_info = getSingleRecord('tra_organisation_information', array('id'=>1));
			
			return $org_info;
		}
}