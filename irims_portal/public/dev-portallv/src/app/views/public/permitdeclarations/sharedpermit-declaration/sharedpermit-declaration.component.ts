import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewContainerRef } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { SpinnerVisibilityService } from 'ng-http-loader';
import { ToastrService } from 'ngx-toastr';
import { AppSettings } from 'src/app/app-settings';
import { Utilities } from 'src/app/services/common/utilities.service';
import { PublicService } from 'src/app/services/public/public.service';
import { ConfigurationsService } from 'src/app/services/shared/configurations.service';

@Component({
  selector: 'app-sharedpermit-declaration',
  templateUrl: './sharedpermit-declaration.component.html',
  styleUrls: ['./sharedpermit-declaration.component.css']
})
export class SharedpermitDeclarationComponent implements OnInit {
  maxDate:any;
  app_resp:any;
  application_code:number;
  loading:boolean;
  section_id:number;
  application_id:number;
  tracking_no:string;
  is_readonly:boolean= false;
  module_id:number=20;
  process_title:string;
  filterPermitVerificationForm:FormGroup;
  sectionsData:any;
  base_url = AppSettings.base_url;
  mis_url = AppSettings.mis_url;
  printReportTitle:string;
  isPrintReportVisible:boolean = false;
  printiframeUrl:string;
  dtImportExpApplicationData:any;
  declaredImpExpApplicationData:any;
  app_route:any;
  applicationGeneraldetailsfrm:FormGroup;
  applicationTypeData:any;
  applicant_id:number;
  producttypeDefinationData:any;
  applicationCategoryData:any;
  permitProductsCategoryData:any;
  portOfEntryExitData:any;
  modeOfTransportData:any;
  CountryData:any;
  sub_module_id:number;
  permitReasonData:any;
  confirmDataParam:any;
  currencyData:any;

  onApplicationSubmissionFrm:FormGroup;
  constructor(public httpClient:HttpClient, public config: ConfigurationsService,public publicService: PublicService, public spinner: SpinnerVisibilityService,public toastr: ToastrService,public viewRef: ViewContainerRef,public router: Router,public configService: ConfigurationsService,public utilityService:Utilities) { 
    
    this.maxDate = new Date();
    this.onLoadSections()
  
    this.process_title = "Import/Export Permit Verification";
    this.filterPermitVerificationForm = new FormGroup({
          license_no: new FormControl('', Validators.compose([])),
          importexport_name: new FormControl('', Validators.compose([])),
          supplier_name: new FormControl('', Validators.compose([])),
          proforma_invoice_no: new FormControl('', Validators.compose([]))
    });
    this.onApplicationSubmissionFrm = new FormGroup({
      paying_currency_id: new FormControl('', Validators.compose([])),
     submission_comments:new FormControl('', Validators.compose([]))
    });

    this.applicationGeneraldetailsfrm = new FormGroup({
          sub_module_id: new FormControl('', Validators.compose([Validators.required])),
          module_id: new FormControl(this.module_id, Validators.compose([Validators.required])),
          section_id: new FormControl('', Validators.compose([Validators.required])),
          producttype_defination_id: new FormControl('', Validators.compose([Validators.required])),
          permit_category_id: new FormControl('', Validators.compose([Validators.required])),
          permit_productscategory_id: new FormControl('', Validators.compose([Validators.required])),
          port_id: new FormControl('', Validators.compose([Validators.required])),
          mode_oftransport_id: new FormControl('', Validators.compose([Validators.required])),
          proforma_invoice_no: new FormControl('', Validators.compose([Validators.required])),
          proforma_invoice_date: new FormControl('', Validators.compose([Validators.required])),
          applicant_name: new FormControl('', Validators.compose([Validators.required])),
          proforma_currency_id: new FormControl('', Validators.compose([Validators.required])),
          application_code: new FormControl('', Validators.compose([])),
          consignment_value: new FormControl('', Validators.compose([Validators.required])),
          supplier_name: new FormControl('', Validators.compose([Validators.required])),
          country_of_origin: new FormControl('', Validators.compose([Validators.required])),
          applicant_telephone_no: new FormControl('', Validators.compose([])),
          applicant_email_address: new FormControl('', Validators.compose([Validators.required])),
          applicant_id: new FormControl('', Validators.compose([])),
          shipment_date: new FormControl('', Validators.compose([])),
          proposed_inspection_date: new FormControl('', Validators.compose([])),
            clearing_agent: new FormControl('', Validators.compose([])),
            custom_declaration_no: new FormControl('', Validators.compose([]))
    });

    this.onLoadSections();
    this.onLoadpermitProductsCategoryData(0);
    this.onLoadconfirmDataParm();
    this.onLoadmodeOfTransportData();
    this.onLoadCountries();
    this.onLoadCurrenciesData();
    this.onLoadportOfEntryExitData();
 this.onloadApplicationTypes()
 this.onLoadproducttypeDefinationData();
  }
  ngOnInit() {


  } onLoadconfirmDataParm() {
    var data = {
      table_name: 'par_confirmations',
    };

    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.confirmDataParam = data;
        });
  }
  onLoadpermitReasonData(section_id) {
    var data = {
      table_name: 'par_permit_reasons'
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.permitReasonData = data;
        });

  }
  onLoadpermitProductsCategoryData(permit_category_id) {
    var data = {
      table_name: 'par_permitsproduct_categories',
      permit_category_id:permit_category_id
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.permitProductsCategoryData = data;
        });

  }
  onApplicationCategorySelection($event){
    let permit_category_id = $event.selectedItem.id;
    this.onLoadpermitProductsCategoryData(permit_category_id);

  }
  onLoadportOfEntryExitData() {
    var data = {
      table_name: 'par_ports_information'
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.portOfEntryExitData = data;
        });

  } onLoadproducttypeDefinationData() {
    var data = {
      table_name: 'par_producttype_definations',
    };

    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.producttypeDefinationData = data;
        });
  }
 
  onLoadSections() {
    var data = {
      table_name: 'par_sections',
    };

    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.sectionsData = data;
        });
  }
  singleApplicationActionColClick(data){
    
    this.funcActionsProcess(data,data);

  }
 
  funcActionsProcess(action_btn, data) {
   
    if(action_btn.action == 'reg_certificate' || action_btn.action == 'reg_certificate'){
      
      this.funcgenenerateImportExportPermit(data);

    }
    else if(action_btn.action == 'approval_permit' || action_btn.action == 'print_permit'){
      
      this.funcgenenerateImportExportPermit(data);

    }else{
      this.toastr.error('The Permit have not been approved or missing approval details, current application Status '+data.status_name+' Contact Rwanda FDA for support.', 'Alert!');
    }

  }
  funcgenenerateImportExportPermit(app_data){

    let report_url = this.mis_url+'reports/genenerateImportExportPermit?application_code='+app_data.application_code+"&module_id="+app_data.module_id+"&sub_module_id="+app_data.sub_module_id+"&table_name=tra_importexport_applications";
    this.funcGenerateRrp(report_url,"License Preview")
    
  }
  funcGenerateRrp(report_url,title){
    
    this.printiframeUrl =  this.configService.returnReportIframe(report_url);
    this.printReportTitle= title;
    this.isPrintReportVisible = true;

}
onCellPrepared(e) {
  this.utilityService.onCellPrepared(e);
}
funcPrintApplicationInvoice(app_data){

  let report_url = this.mis_url+'reports/generateApplicationInvoice?application_code='+app_data.application_code+"&module_id="+app_data.module_id+"&sub_module_id="+app_data.sub_module_id+"&table_name=tra_importexport_applications";
  this.funcGenerateRrp(report_url,"Application Invoice")
  
}
funcPrintApplicationReceipts(app_data){

  let report_url = this.mis_url+'reports/generateApplicationReceipt?payment_id=' + app_data.receipt_id + '&&module_id=' + app_data.module_id + '&&application_id=' + app_data.application_id;
     
     this.funcGenerateRrp(report_url,"Application Receipt")

}
funcPrintApplicationReceipts112(app_data){
  this.utilityService.setApplicationDetail(app_data);
  this.app_route = ['./online-services/application-payments'];
  this.router.navigate(this.app_route);
}
reloadPermitApplicationsApplications() {
  let license_no = this.filterPermitVerificationForm.get('license_no').value;
  let importexport_name = this.filterPermitVerificationForm.get('importexport_name').value;
  this.spinner.show();
  this.publicService.onPermitApplicationLoading('publicaccess/getImportExpPermitsApplicationLoading',{license_no:license_no,importexport_name:importexport_name})
    .subscribe(
      resp_data => {
        if (resp_data.success) {
          this.dtImportExpApplicationData = resp_data.data;
        }
        else {
          this.toastr.error(resp_data.message, 'Alert!');
          this.dtImportExpApplicationData = resp_data.data;
        }
        
        this.spinner.hide();
      });
}reloadDeclaredImpExpApplicationsData() {
  let license_no = this.filterPermitVerificationForm.get('license_no').value;
  let importexport_name = this.filterPermitVerificationForm.get('importexport_name').value;
  let proforma_invoice_no = this.filterPermitVerificationForm.get('proforma_invoice_no').value;

  let supplier_name = this.filterPermitVerificationForm.get('supplier_name').value;

  this.spinner.show();
  this.publicService.onPermitApplicationLoading('publicaccess/getDeclaredImpExpApplicationsData',{license_no:license_no,importexport_name:importexport_name, supplier_name:supplier_name,proforma_invoice_no:proforma_invoice_no })
    .subscribe(
      resp_data => {
        if (resp_data.success) {
          this.declaredImpExpApplicationData = resp_data.data;
        }
        else {
          this.toastr.error(resp_data.message, 'Alert!');
          this.declaredImpExpApplicationData = resp_data.data;
        }
        
        this.spinner.hide();
      });
}

onApplicationDashboard() {
  this.app_route  = ['public/permitdeclaration_invoicingdash'];

  this.router.navigate(this.app_route);
}
onewImportPermitDeclaration() {
  this.app_route  = ['public/permitdeclaration_invoicing'];

  this.router.navigate(this.app_route);
}

onProductTypesDefinationSelection($event){
    let producttype_defination_id = $event.selectedItem.id;
    this.onLoadapplicationCategoryData(producttype_defination_id);

  }
  onLoadapplicationCategoryData(producttype_defination_id) {
    var data = {
      table_name: 'par_permit_category',
      producttype_defination_id:producttype_defination_id,
      sub_module_id: this.sub_module_id
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.applicationCategoryData = data;
        });

  }
  onProductTypesSelection(section_id){


  }
  onloadApplicationTypes() {
    var data = {
      table_name: 'sub_modules',
      module_id: this.module_id
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.applicationTypeData = data;
        });

  }
  onLoadmodeOfTransportData() {
    var data = {
      table_name: 'par_modesof_transport'
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.modeOfTransportData = data;
        });

  }

  onLoadCountries() {

    var data = {
      table_name: 'par_countries',
      // id: 36
    };
    this.config.onLoadConfigurationData(data)

      .subscribe(
        data => {
          this.CountryData = data;
        },
        error => {
          return false;
        });
  }
  onLoadCurrenciesData() {
    var data = {
      table_name: 'par_currencies'
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.currencyData = data;
        });

  }
  onSaveImportExportApplication() {

    const invalid = [];
    const controls = this.applicationGeneraldetailsfrm.controls;
    for (const name in controls) {
        if (controls[name].invalid) {
          this.toastr.error('Fill In All Mandatory fields with (*), missing value on '+ name.replace('_id',''), 'Alert');
            return;
        }
    }
    if (this.applicationGeneraldetailsfrm.invalid) {
      return;
    }

    this.spinner.show();
    this.publicService.onSavePermitApplication(this.application_id, this.applicationGeneraldetailsfrm.value, this.tracking_no, 'publicaccess/saveImportExportApplication','')
      .subscribe(
        response => {
          this.app_resp = response.json();
          //the details 
          this.spinner.hide();

          if (this.app_resp.success) {

            this.tracking_no = this.app_resp.tracking_no;
            this.application_id = this.app_resp.application_id;
            this.application_code = this.app_resp.application_code;
            this.applicant_id = this.app_resp.applicant_id;
            this.sub_module_id = this.app_resp.sub_module_id;
            this.section_id = this.app_resp.section_id;
            this.module_id = this.app_resp.module_id;
            this.applicant_id = this.app_resp.applicant_id;
            this.toastr.success(this.app_resp.message, 'Response');
          
          } else {
            this.toastr.error(this.app_resp.message, 'Alert');
          }

        },
        error => {
          this.loading = false;
        });
  }
//350*
}
