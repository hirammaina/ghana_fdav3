<?php
/**
 * Created by PhpStorm.
 * User: Kip
 * Date: 4/9/2019
 * Time: 8:41 PM
 */

namespace App\Modules\Reports\Providers;

use setasign\Fpdi\TcpdfFpdi;
class PdfLettersProvider extends TcpdfFpdi
{
  public $params = array();
	public function __construct($qr_data=array()){
			parent::__construct();
		$this->params = $qr_data;
		
	}
 function Header(){
   $this->setMargins(7,25,10,true);
  
		if ($this->PageNo() ==1) {
						$org_info = $this->getOrganisationInfo();
						$logo = getcwd() . '/resources/images/org-logo.jpg';
					$this->Image($logo,20,14,24,27);$this->SetFont('times','B',11);
				$this->ln(6);
				
$this->SetFont('times','',9);
			$qms_details = "";
			$this->WriteHTML($qms_details, true, false, true, true, 'R');
		 
$this->SetFont('times','B',13);
			$this->cell(65, 6, '', 0,0, 'C');
				$this->cell(0, 6, $org_info->name, 0,1, '');
				$this->SetFont('times','',9);
			$this->cell(65, 6, '', 0,0, 'C');
				$this->cell(0, 6, $org_info->physical_address, 0,1, '');
			$this->cell(65, 6, '', 0,0, 'C');
				$this->cell(0, 6, $org_info->postal_address, 0,1, '');
			$this->cell(65, 6, '', 0,0, 'C');
				$this->cell(0, 6,'Email: '. $org_info->email_address, 0,1, '');
				
		$this->cell(65, 6, '', 0,0, 'C');

$this->cell(0, 6,'Website: '. $org_info->website, 0,1, '');

		}
	}
  function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-20);
		//Arial italic 8
    $this->SetFont('times','',8);
   
    $this->Cell(0,4,'Website: www.rwandafda.gov.rw, Email: info@rwandafda.gov.rw',0,1,'C');
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