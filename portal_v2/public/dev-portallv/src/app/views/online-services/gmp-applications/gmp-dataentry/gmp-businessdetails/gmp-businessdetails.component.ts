
import { Component, OnInit,  ViewContainerRef,  Input } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { NgxSmartModalService } from 'ngx-smart-modal';
import { ToastrService } from 'ngx-toastr';

import { SpinnerVisibilityService } from 'ng-http-loader';
import { ModalDialogService } from 'ngx-modal-dialog';

import { GmpApplicationServicesService } from 'src/app/services/gmp-applications/gmp-application-services.service';
import { DocumentManagementService } from 'src/app/services/document-management/document-management.service';
import { Utilities } from 'src/app/services/common/utilities.service';

import { ConfigurationsService } from 'src/app/services/shared/configurations.service';
import { AuthService } from 'src/app/services/auth.service';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-gmp-businessdetails',
  templateUrl: './gmp-businessdetails.component.html',
  styleUrls: ['./gmp-businessdetails.component.css']
})
export class GmpBusinessdetailsComponent implements OnInit {
  
  @Input() premisesOtherDetailsRows: any;
  @Input() isBusinessTypePopupVisible: boolean;
  @Input() businessTypesData: any;
  @Input() businessTypeDetailsData: any;
  @Input() gmpOtherDetailsfrm: FormGroup;
  
  @Input() is_readonly: boolean;


  @Input() business_type_id: number;
  @Input() gmp_type_id: number;

  
  @Input() manufacturing_site_id: number;
  addBusinessTypeDetailsfrm:FormGroup;
  addBusinessTypeDetailsMdl: boolean=false;
  gmp_resp:any;
  product_resp:any;
  regions:any;
  districts:any;
  region_id:number;
  countries:any;addRegionDetailsFrm:FormGroup;
  addRegionDetailsWin:boolean;
  country_id:any;
  constructor(public modalServ: ModalDialogService, public viewRef: ViewContainerRef, public spinner: SpinnerVisibilityService, public configService: ConfigurationsService, public appService: GmpApplicationServicesService, public router: Router, public formBuilder: FormBuilder, public config: ConfigurationsService, public modalService: NgxSmartModalService, public toastr: ToastrService, public authService: AuthService,public dmsService:DocumentManagementService,public utilityService:Utilities,public httpClient: HttpClient) { 
    this.addRegionDetailsFrm = new FormGroup({
      name: new FormControl('', Validators.compose([Validators.required])),
      country_id: new FormControl('', Validators.compose([Validators.required])),
      tablename: new FormControl('', Validators.compose([Validators.required]))

    });
    this.addBusinessTypeDetailsfrm = new FormGroup({
      name: new FormControl('', Validators.compose([Validators.required])),
      description: new FormControl('', Validators.compose([Validators.required])),
      business_type_id: new FormControl('', Validators.compose([Validators.required])),
      tablename: new FormControl('', Validators.compose([Validators.required]))
    
    });
    this.onLoadCountries();
  }
  ngOnInit() {
   
    

  } onCoutryCboSelect($event) {
    this.country_id = $event.selectedItem.id;

    this.onLoadRegions(this.country_id);

  }  OnAddNewManufacturerReionDetails(){
    let country_id = this.gmpOtherDetailsfrm.get('country_id').value;
    this.addRegionDetailsFrm.reset();
    if(country_id >0){
      this.addRegionDetailsFrm.get('country_id').setValue(country_id);
     
      this.addRegionDetailsWin = true;
    }
    else{
      this.toastr.error('Select Country before you add a new Region', 'Alert');
    }
  
  }
  
onSaveRegiondetails(){
  this.spinner.show();
  this.addRegionDetailsFrm.get('tablename').setValue('par_regions');
  this.utilityService.onsaveApplicationUniformDetails('', this.addRegionDetailsFrm.value, 'onSaveUniformConfigData')
  .subscribe(
    response => {
      this.product_resp = response.json();
      //the details 
      if (this.product_resp.success) {
        this.onLoadRegions(this.country_id);
        this.addRegionDetailsWin = false;
        this.toastr.success(this.product_resp.message, 'Response');
      } else {
        this.toastr.error(this.product_resp.message, 'Alert');
      }
      this.spinner.hide();
    },
    error => {
      this.toastr.error('Error Occurred', 'Alert');
    });

} 
  onSaveProductTypeDetails(){
    this.addBusinessTypeDetailsfrm.get('tablename').setValue('par_business_type_details')
    this.addBusinessTypeDetailsfrm.get('business_type_id').setValue(this.business_type_id);
    this.utilityService.onsaveApplicationUniformDetails('', this.addBusinessTypeDetailsfrm.value, 'onSaveUniformConfigData')
    .subscribe(
      response => {
        this.product_resp = response.json();
        //the details 
        if (this.product_resp.success) {
          this.onBusinessTypesDetailsLoad(this.business_type_id);
         
          this.addBusinessTypeDetailsMdl = false;
          this.gmpOtherDetailsfrm.get('business_type_detail_id').setValue(this.product_resp.record_id)
          this.toastr.success(this.product_resp.message, 'Response');
  
        } else {
          this.toastr.error(this.product_resp.message, 'Alert');
        }
        this.spinner.hide();
      },
      error => {
        this.toastr.error('Error Occurred', 'Alert');
      });
  
  }    onBusinessTypesLoad() {

    var data = {
      table_name: 'par_business_types',
      gmp_type_id:this.gmp_type_id
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.businessTypesData = data;
        },
        error => {
          return false
        });
  } 
  
  onRegionsCboSelect($event) {
    this.region_id = $event.selectedItem.id;

    this.onLoadDistricts(this.region_id);

  }
  onLoadCountries() {

    var data = {
      table_name: 'par_countries'
    };
    this.config.onLoadConfigurationData(data)

      .subscribe(
        data => {
          this.countries = data;
        },
        error => {
          return false;
        });
  }

  onLoadRegions(country_id) {

    var data = {
      table_name: 'par_regions',
      country_id: country_id
    };
    this.config.onLoadConfigurationData(data)
      //.pipe(first())
      .subscribe(
        data => {
          console.log(data);
          this.regions = data;
        },
        error => {
          return false
        });
  }

  onLoadDistricts(region_id) {
    var data = {
      table_name: 'par_districts',
      region_id: region_id
    };
    this.config.onLoadConfigurationData(data)
      //.pipe(first())
      .subscribe(
        data => {
          this.districts = data
        },
        error => {
          return false;
        });
  }
  onAddBusinessTypeDetails(){
    this.addBusinessTypeDetailsfrm.reset();
    this.addBusinessTypeDetailsMdl = true;
  
  } funcEditPremisesDetails(data) {
    this.gmpOtherDetailsfrm.patchValue(data.data)

    this.isBusinessTypePopupVisible = true;
  } funcpopWidth(percentage_width) {
    return window.innerWidth * percentage_width/100;
  }
  functDataGridToolbar(e, funcBtn, btn_title,is_readonly= false) {
    e.toolbarOptions.items.unshift({
      location: 'before',
      widget: 'dxButton',
      options: {
        text: btn_title,
        type: 'default',
        icon: 'fa fa-plus',
        disabled:is_readonly,
        onClick: funcBtn.bind(this)

      }
    }, {
        location: 'after',
        widget: 'dxButton',
        options: {
          icon: 'refresh',
          onClick: this.refreshDataGrid.bind(this)
        }
      });
  }  refreshDataGrid() {
    //this.dataGrid.instance.refresh();
  }onPremisesBusinesDetailsToolbar(e,is_readonly=false) {
    this.functDataGridToolbar(e, this.funAddPremisesOtherDetails, 'Other Manufacturing Site',is_readonly);
  }
  funAddPremisesOtherDetails() {
    this.isBusinessTypePopupVisible = true;
    //reset the form 
    this.gmpOtherDetailsfrm.reset();
    this.gmpOtherDetailsfrm.get('business_type_id').setValue(this.business_type_id);
    this.onBusinessTypesDetailsLoad(this.business_type_id);

  }
 
  onBusinessTypesDetailsLoad(business_type_id) {

    var data = {
      table_name: 'par_business_type_details',
      business_type_id: business_type_id
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          console.log(data);
          this.businessTypeDetailsData = data;
        },
        error => {
          return false
        });
  } onSaveGmpOtherDetails() {
    if (this.gmpOtherDetailsfrm.invalid) {
      return;
    }
    //also get the premises ID
    this.spinner.show();
    this.appService.onSaveGmpOtherDetails('wb_mansite_otherdetails', this.gmpOtherDetailsfrm.value,this.manufacturing_site_id)
      .subscribe(
        response => {
          this.gmp_resp = response.json();
          if (this.gmp_resp.success) {
            this.toastr.success(this.gmp_resp.message, 'Response');
            this.isBusinessTypePopupVisible = false;
            this.onLoadPremisesOtherDetails(this.manufacturing_site_id);
          } else {
            this.toastr.error(this.gmp_resp.message, 'Alert');
          }
          this.spinner.hide();
        },
        error => {
          this.spinner.hide();
        });
  }
   //reload the premsies Other Details 
   onLoadPremisesOtherDetails(manufacturing_site_id) {

    this.appService.onLoadGmpOtherDetails(manufacturing_site_id)
      //.pipe(first())
      .subscribe(
        data => {
          this.premisesOtherDetailsRows = data;
        },
        error => {
          return false
        });
  } funcDeletePremisesBusinessDetails(data) {
    //func_delete records 
    let record_id = data.data.id;
    let manufacturing_site_id = data.data.manufacturing_site_id;
    let table_name = 'wb_mansite_otherdetails';
  
    this.modalServ.openDialog(this.viewRef, {
      title: 'Are You sure You want to delete Business Details?',
      childComponent: '',
      settings: {
        closeButtonClass: 'fa fa-close'
      },
      actionButtons: [
        {
          text: 'Yes',
          buttonClass: 'btn btn-danger',
          onAction: () => new Promise((resolve: any, reject: any) => {
            this.appService.onDeleteGMPDetails(record_id, table_name, manufacturing_site_id, 'Business Details')
              //.pipe(first())
              .subscribe(
                data_response => {
                  let resp = data_response.json();
                 
                  if (resp.success) {
                    this.onLoadPremisesOtherDetails(manufacturing_site_id);

                    this.toastr.success(resp.message, 'Response');
                  }
                  else {
                    this.toastr.error(resp.message, 'Alert');
                  }
                },
                error => {
                  return false
                });
            resolve();
          })
        },
        {
          text: 'no',
          buttonClass: 'btn btn-default',
          onAction: () => new Promise((resolve: any) => {

            resolve();

          })
        }
      ]
    });
  }
}
