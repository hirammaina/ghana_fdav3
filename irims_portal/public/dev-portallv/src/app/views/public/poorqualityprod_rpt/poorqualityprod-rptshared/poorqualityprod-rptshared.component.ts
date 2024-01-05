import { Component, OnInit, ViewChild, ViewContainerRef } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { SpinnerVisibilityService } from 'ng-http-loader';
import { ToastrService } from 'ngx-toastr';
import { PublicService } from 'src/app/services/public/public.service';
import { ConfigurationsService } from 'src/app/services/shared/configurations.service';
import { WizardComponent } from 'ng2-archwizard';
import { AppSettings } from 'src/app/app-settings';
import { HttpClient, HttpHeaders } from '@angular/common/http';

import CustomStore from 'devextreme/data/custom_store';
import { Router } from '@angular/router';
@Component({
  selector: 'app-poorqualityprod-rptshared',
  templateUrl: './poorqualityprod-rptshared.component.html',
  styleUrls: ['./poorqualityprod-rptshared.component.css']
})
export class PoorqualityprodRptsharedComponent implements OnInit {
  @ViewChild(WizardComponent)
  public wizard: WizardComponent;
  isViewApplicationReportingDetils:boolean;
  base_url = AppSettings.base_url;
  mis_url = AppSettings.mis_url;
  process_title:string; maxDate:any;
  poorqualityprodrptform:FormGroup;
  productCategoryData:any;
  countries:any;
  sectionsData:any;
  localcountry:any;
  regionsData:any;
  dosageFormData:any;
  productdescComplaintsData:any;
  confirmDataParamAll:any;
  actionTakenData:any;
  detectionOfpoorqualityData:any;
  reporterCategoryData:any;
  districtData:any;
  is_readonly:boolean;
  section_id:number;
   module_id:number=29;
    sub_module_id:number=94;
     application_code:number;
     product_resp:any;
     tracking_no:string;
  checkProductsSubmission:boolean;
  onApplicationSubmissionFrm:FormGroup;
  app_route:any;
  printReportTitle:string;
  isPrintReportVisible:boolean = false;
  printiframeUrl:string;
  filterReportingForm:FormGroup;
  suspectedProdReportingData:any ={};
  constructor(public httpClient:HttpClient, public config: ConfigurationsService,public publicService: PublicService, public spinner: SpinnerVisibilityService,public toastr: ToastrService,public viewRef: ViewContainerRef,public router: Router) { 
    this.onLoadSections()
    this.maxDate = '';
    this.process_title = "SUSPECTED POOR QUALITYPRODUCT REPORTING FORM";
    this.filterReportingForm = new FormGroup({
          reference_no: new FormControl('', Validators.compose([])),
          reporter_email_address: new FormControl('', Validators.compose([]))
    });
    this.poorqualityprodrptform =  new FormGroup({
        id: new FormControl('', Validators.compose([])),
        application_code: new FormControl('', Validators.compose([])),
        section_id: new FormControl('', Validators.compose([])),
        product_category_id: new FormControl('', Validators.compose([])),
        other_product_category: new FormControl('', Validators.compose([])),
        brand_name: new FormControl('', Validators.compose([Validators.required])),
        generic_name: new FormControl('', Validators.compose([Validators.required])),
        batch_no: new FormControl('', Validators.compose([Validators.required])),
        manufacturing_date: new FormControl('', Validators.compose([Validators.required])),
        expiry_date: new FormControl('', Validators.compose([Validators.required])),
        date_of_receipt: new FormControl('', Validators.compose([])),
        name_of_manufacturer: new FormControl('', Validators.compose([Validators.required])),
        manufacturerphysical_address: new FormControl('', Validators.compose([])),
        dosage_form_id: new FormControl('', Validators.compose([])),
        productdesc_complaints_id: new FormControl('', Validators.compose([])),
      
        other_product_formulation: new FormControl('', Validators.compose([])),
        country_of_origin: new FormControl('', Validators.compose([])),
        name_of_distributor: new FormControl('', Validators.compose([])),
        distributor_physical_address: new FormControl('', Validators.compose([])),
        distributor_region_id: new FormControl('', Validators.compose([])),
        distributor_country_id: new FormControl('', Validators.compose([])),
        complaint_description: new FormControl('', Validators.compose([Validators.required])),
        needs_refrigeration: new FormControl('', Validators.compose([Validators.required])),
        needs_protectionfromlight: new FormControl('', Validators.compose([Validators.required])),
        needs_protectionfrommoisture: new FormControl('', Validators.compose([Validators.required])),
        conforms_tostorage_guidelines: new FormControl('', Validators.compose([])),
        other_storage_details: new FormControl('', Validators.compose([])),
        detection_ofpoorquality_id: new FormControl('', Validators.compose([Validators.required])),//
        other_detection_ofpoorquality: new FormControl('', Validators.compose([])),//
        detections_actionstaken_id: new FormControl('', Validators.compose([])),
        otherdetections_actionstakens: new FormControl('', Validators.compose([Validators.required])),
        has_experiencedadverse_event: new FormControl('', Validators.compose([])),
        reporter_category_id: new FormControl('', Validators.compose([])),
        name_of_reporter: new FormControl('', Validators.compose([Validators.required])),
        reporter_qualification: new FormControl('', Validators.compose([])),
        health_facility: new FormControl('', Validators.compose([])),
        facility_district_id: new FormControl('', Validators.compose([])),
        facility_region_id: new FormControl('', Validators.compose([])),
        facility_contact_person: new FormControl('', Validators.compose([])),
        facility_contactpersons_details: new FormControl('', Validators.compose([])),
        facility_country_id: new FormControl('', Validators.compose([])),
        reporter_email_address: new FormControl('', Validators.compose([Validators.required])),
        reporter_telephone_no: new FormControl('', Validators.compose([Validators.required])),
        submission_comments: new FormControl('', Validators.compose([])),
        reporting_date: new FormControl('', Validators.compose([])),
        module_id: new FormControl(this.module_id, Validators.compose([])),
        sub_module_id: new FormControl(this.sub_module_id, Validators.compose([])),
    });
    this.onApplicationSubmissionFrm = new FormGroup({
      submission_comments:new FormControl('', Validators.compose([]))
    });
    this.onLoadSections();
    this.onLoadCountriesData();
    this.onLoadlocalcountryData();
    this.onLoadldosageFormDataData();
    this.onLoadlproductdescComplaintsData();
    this.onLoadconfirmDataParamAllData();
    this.onLoadactionTakenDataData();
    this.onLoaddetectionOfpoorqualityDataData();
    this.onLoadreporterCategoryDataData();
    
  }

  ngOnInit() {

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
  onProductTypesCboSelect($event) {
    if($event.selectedItem){
          let common_namedetails =$event.selectedItem;
          this.onLoadproductCategoryData(common_namedetails.id);
          this.section_id =common_namedetails.id;
    
    }
}

onregionsCboSelect($event) {
  if($event.selectedItem){
        let data =$event.selectedItem;
        this.onloaDistricts(data.id);
  
  }
}
onLoadproductCategoryData(section_id) {
  var data = {
    table_name: 'par_product_categories',
    section_id: section_id
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.productCategoryData = data;
      });
}
onLoadCountriesData() {
  var data = {
    table_name: 'par_countries'
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.countries = data;
      });
}
onLoadlocalcountryData() {
  var data = {
    table_name: 'par_countries',
    is_local: 1
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.localcountry = data;
      });
}

onLoadregionsData(country_id) {
  var data = {
    table_name: 'par_regions',
    country_id: country_id
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.regionsData = data;
      });
}
onloaDistricts(region_id) {
  var data = {
    table_name: 'par_districts',
    region_id: region_id
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.districtData = data;
      });
}

onLoadldosageFormDataData() {
  var data = {
    table_name: 'par_dosage_forms'
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.dosageFormData = data;
      });
}
onLoadlproductdescComplaintsData() {
  var data = {
    table_name: 'par_productdesc_complaints'
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.productdescComplaintsData = data;
      });
}
onLoadconfirmDataParamAllData() {
  var data = {
    table_name: 'par_confirmations'
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.confirmDataParamAll = data;
      });
}
onLoadactionTakenDataData() {
  var data = {
    table_name: 'par_detections_actionstakens'
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.actionTakenData = data;
      });
}
onLoaddetectionOfpoorqualityDataData() {
  var data = {
    table_name: 'par_detections_ofpoorquality'
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.detectionOfpoorqualityData = data;
      });
}

onLoadreporterCategoryDataData() {
  var data = {
    table_name: 'par_reporters_categories'
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.reporterCategoryData = data;
      });
}

par_reporters_categories
onlocalcountryCboSelect($event) {
  if($event.selectedItem){
        let data =$event.selectedItem;
        this.onLoadregionsData(data.id);
  
  }
}
newProductTermscheckbox(e) {

  this.checkProductsSubmission = e.value;

}
  onProductApplicationSubmit() {
    if (this.onApplicationSubmissionFrm.invalid) {
      this.toastr.error('Fill in all the submission details to proceed!!', 'Alert');
      return;
    }
    this.app_route = ['./public/poorqualityprod-rptdashboard'];
    this.publicService.onSubPoorQualityReportDetails(this.viewRef, this.application_code, this.tracking_no, 'wb_poorqualityproduct_reports', this.app_route,this.onApplicationSubmissionFrm.value);
  //  this.isApplicationSubmitwin = false;

  } 
  
  onSavePoorQualityReportDetails() {
    
    const invalid = [];
    const controls = this.poorqualityprodrptform.controls;
    for (const name in controls) {
        if (controls[name].invalid) {
          this.toastr.error('Fill In All Mandatory fields with (*), missing value on '+ name.replace('_id',''), 'Alert');
            return;
        }
    }
    if (this.poorqualityprodrptform.invalid) {
      this.spinner.hide();
      return;
    }
    this.spinner.show();
    this.publicService.onSavePoorQualityReportDetails(this.poorqualityprodrptform.value,  'onSavePoorQualityReportDetails')
      .subscribe(
        response => {
          this.product_resp = response.json();
          //the details 
          if (this.product_resp.success) {
            this.tracking_no = this.product_resp.tracking_no;
            this.application_code = this.product_resp.application_code;
            this.toastr.success(this.product_resp.message, 'Response');
            this.wizard.model.navigationMode.goToStep(1);
          } else {
            this.toastr.error(this.product_resp.message, 'Alert');
          }
          this.spinner.hide();
        },
        error => {
          this.toastr.error('Error Occurred, refresh and try again.', 'Alert');
          this.spinner.hide();
        });
  }
  onPrintPoorQualityReportDetails(){

    let report_url = this.mis_url+'reports/printPoorQualityReportDetails?application_code='+this.application_code+"&table_name=tra_poorqualityproduct_reports";
    this.funcGenerateRrp(report_url,"Print REport");

  }
  funcGenerateRrp(report_url,title){
    
    this.printiframeUrl =  this.config.returnReportIframe(report_url);
    this.printReportTitle= title;
    this.isPrintReportVisible = true;
}funcpopWidth(percentage_width) {
  return window.innerWidth * percentage_width/100;
}
funcpopheight(percentage_width) {
  return window.innerHeight * percentage_width/100;
}
onClearRegisteredproductsFilter(){
  this.filterReportingForm.reset();
 // this.dataGridInstance.refresh();
}

onLoadsuspectedProdReportingData(){
   
  this.spinner.show();
let me = this;
  var headers = new HttpHeaders({
    "Accept": "application/json"
  });
  this.suspectedProdReportingData.store = new CustomStore({
    load: function (loadOptions: any) {
      let extra_params = me.filterReportingForm.value;
      let extra_paramsdata = JSON.stringify(extra_params);
        var params = '?';
        params += 'skip=' + loadOptions.skip;
        params += '&take=' + loadOptions.take;
        params += '&extra_paramsdata=' + extra_paramsdata;
        return me.httpClient.get(AppSettings.base_url + 'publicaccess/onLoadsuspectedProdReportingData'+ params)
            .toPromise()
            .then((data: any) => {
                return {
                    data: data.data,
                    totalCount: data.totalCount
                }
            })
            .catch(error => { throw 'Data Loading Error' });
    }
});
}

onSearchSuspectedProdReporting() {
  let reference_no = this.filterReportingForm.get('reference_no').value;
  let reporter_email_address = this.filterReportingForm.get('reporter_email_address').value;
   
  this.publicService.onPermitApplicationLoading('publicaccess/onLoadsuspectedProdReportingData',{reporter_email_address:reporter_email_address,reference_no:reference_no})
    .subscribe(
      resp_data => {
        if (resp_data.success) {
          this.suspectedProdReportingData =  resp_data.data;
        }
        else {
          this.toastr.error(resp_data.message, 'Alert!');

        }
      });
}


onewSuspectedProdReporting(){

  this.router.navigate(['./public/poorqualityprod-rptsubmission']);
    return
}
funViewApplicationDocument(data){
  this.is_readonly = true;
  this.poorqualityprodrptform.patchValue(data);
  this.isViewApplicationReportingDetils = true;

}

}
