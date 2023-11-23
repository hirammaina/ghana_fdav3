<?php
/**
 * Created by PhpStorm.
 * User: Kip
 * Date: 4/9/2019
 * Time: 8:41 PM
 */

namespace App\Modules\Reports\Providers;

use setasign\Fpdi\TcpdfFpdi;
class PdfPermitProvider extends TcpdfFpdi
{
  public $params = array();
	public function __construct($qr_data=array()){
			parent::__construct();
		$this->params = $qr_data;
		
	}
  function Header(){
   $this->setMargins(10,10,10,true);
  
		if ($this->PageNo() ==1) {
						$org_info = $this->getOrganisationInfo();
			//	$logo = getcwd() . '/resources/images/org-logo.jpg';
			//$this->Image($logo,90,13,30,33);
		 
		}
	}
	
	
  function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-20);
		//Arial italic 8
    $this->SetFont('times','',8);
  
    $this->Cell(0,4,'Website: www.rwandafda.gov.rw, Email: info@rwandafda.gov.rw',0,1,'C');
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