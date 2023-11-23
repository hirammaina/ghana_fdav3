<?php
/**
 * Created by PhpStorm.
 * User: Kip
 * Date: 4/9/2019
 * Time: 8:41 PM
 */

namespace App\Modules\Reports\Providers;

use setasign\Fpdi\TcpdfFpdi;
class PdfOfficialcertficateProvider extends TcpdfFpdi
{
  public $params = array();
	public function __construct($qr_data=array()){
			parent::__construct();
		$this->params = $qr_data;
		
	}
  function Header(){
		$this->setMargins(6,14,6,true);
  
        $this->SetFillColor(0, 20, 88, 11);
        $this->Rect(0, 0, $this->getPageWidth(), $this->getPageHeight(), 'DF', "");
		if ($this->PageNo() ==1) {
				$org_info = $this->getOrganisationInfo();
				$logo = getcwd() . '/resources/images/org-logo.jpg';
					$this->Image($logo,20,14,24,27);$this->SetFont('times','B',11);
				$this->ln(6);
				
			$this->SetFont('times','',9);
		//	$qms_details = "QMS No:DIS/FMT/119";
		//	$this->WriteHTML($qms_details, true, false, true, true, 'R');
		 
		}
		$template_url = base_path('/');
		$this->setSourceFile($template_url."resources/templates/yellow_certificate.pdf");
										// import page 1
		$tplId = $this->importPage(1);	
									
		$this->useTemplate($tplId,0,0);
		$this->setPageMark();
		if ($this->PageNo() ==1) {
			$this->SetFont('times','',9);
			//$qms_details = "QMS No:DIS/FMT/119";
			//$this->WriteHTML($qms_details, true, false, true, true, 'R');
			
			$this->cell(0, 4, 'QMS No:DIS/FMT/119', 0,1, 'R');
			$this->cell(0, 4, 'Revision No: 1', 0,1, 'R');
			$this->cell(0, 4, 'Effetive Date: 27th Oct 2021: 1', 0,1, 'R');
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