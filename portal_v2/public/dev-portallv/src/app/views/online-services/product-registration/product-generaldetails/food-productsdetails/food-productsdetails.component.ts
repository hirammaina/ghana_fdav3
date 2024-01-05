
import { Component,  Input, ViewContainerRef, EventEmitter, Output, OnInit } from '@angular/core';
import { SharedProductregistrationclassComponent } from '../../shared-productregistrationclass/shared-productregistrationclass.component';
import { ModalDialogService } from 'ngx-modal-dialog';
import { SpinnerVisibilityService } from 'ng-http-loader';
import { ConfigurationsService } from 'src/app/services/shared/configurations.service';
import { ProductApplicationService } from 'src/app/services/product-applications/product-application.service';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { AuthService } from 'src/app/services/auth.service';
import { Utilities } from 'src/app/services/common/utilities.service';
import { NgxSmartModalService } from 'ngx-smart-modal';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-food-productsdetails',
  templateUrl: './food-productsdetails.component.html',
  styleUrls: ['./food-productsdetails.component.css']
})
export class FoodProductsdetailsComponent extends SharedProductregistrationclassComponent   implements OnInit {

  @Input() productGeneraldetailsfrm: FormGroup;
  @Input() assessmentProcedureData: any;
  @Input() classificationData: any;
  @Input() commonNamesData: any;
  @Input() siUnitsData: any;
  @Input() distributionCategoryData: any;
  @Input() storageConditionData: any;
  @Input() dosageFormsData: any;
  @Input() routeOfAdministrationData: any;
  @Input() productCategoryData: any;
  @Input() durationDescData: any;
  @Input() productTypeData: any;
  @Input() confirmDataParam: any;
  @Input() prodclass_category_id: number;
  
  @Input() productFormData: any;
  @Input() methodOfUseData: any;
  @Input() tabaccoFlavourData: any;
  @Input() intendedEndUserData: any;
  @Input() productSubCategoryData: any;
  @Input() productSpecialCategoryData: any;
  @Input() devicesTypeData: any;

  @Input() zonesData: any;
  @Input() section_id: number;
  @Input() atc_code_id: number;

  @Input() sub_module_id: number;
  @Input() product_id: number;
  @Input() application_code: number;

  @Input() isReadOnly: boolean;
  @Input() reg_product_id: number;
  @Input() tra_product_id: number;
  
  isReadOnlyTraderasLtr:boolean;
  country_id:number;
  trader_name: string;
  registrant_option_id:number;
  trader_id:number;
  registrant_optionDisabled:boolean;
  traderAccountsDetailsData:any;
  eacRregisteringbodiesData:any;
  trader_title:string;
  isRegistrantDetailsWinshow:boolean = false;
  @Input() fastTrackOptionsData: number;
  @Input() payingCurrencyData: number;
  @Output() productTypeEvent = new EventEmitter();

  isRegistrantAddDetailsWinshow:boolean;
  
  ngOnInit() {
    let user_details = this.authService.getUserDetails();
    this.country_id = user_details.country_id;
    this.trader_id = user_details.trader_id;
    this.trader_name = user_details.company_name;
    this.is_local = user_details.is_local;

    this.onLoadSections();
    this.onLoadeacRregisteringbodiesData();
    this.onLoadManufacrunginCountries();

    
    this.onLoadCountries(1);
  }
  onLoadManufacrunginCountries() {
    let data = {
      table_name: 'par_countries'
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.countriesData = data;
        },
        error => {
          return false;
        });
  }
  onTraderasLocalAgentChange($event) {
    
    if($event.value == 1){
        this.isReadOnlyTraderasLtr = true;
        this.productGeneraldetailsfrm.patchValue({ local_agent_name: this.trader_name, local_agent_id: this.trader_id });
    }else{
      this.isReadOnlyTraderasLtr = false;

      this.productGeneraldetailsfrm.patchValue({ local_agent_name: 'Select Local Agent', local_agent_id: '', trader_aslocal_agent: 2 })
    }
   

  }onLoadSections() {
    var data = {
      table_name: 'par_sections',
    };

    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.sectionsData = data;
        });
  }
  onLoadeacRregisteringbodiesData() {
    var data = {
      table_name: 'par_eac_registeringbodies',
    };

    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.eacRregisteringbodiesData = data;
        });
  }

  
  onSelectRegistrantOptions($event) {

    this.registrant_option_id = $event.selectedItem.id;
    if (this.registrant_option_id == 1) {
      this.registrant_optionDisabled = true;

    }
    else {
      this.registrant_optionDisabled = false;
      this.productGeneraldetailsfrm.patchValue({ applicant_name: 'Search Product Registrant' })

    }

  }
  funcSearchRegistrantDetails(is_local_agent) {

    this.appService.getProductsOtherDetails({ is_local_agent: is_local_agent }, 'getTraderInformationDetails')
      .subscribe(
        data => {
          if (data.success) {
            if (is_local_agent == 1) {
              this.is_local_agent = is_local_agent;
              this.trader_title = 'Local Representative';
            }
            else {
              this.is_local_agent = is_local_agent;
              this.trader_title = 'Product Registrant';
            }
            this.traderAccountsDetailsData = data.data;
            this.isRegistrantDetailsWinshow = true;
          }
          else {
            this.toastr.success(data.message, 'Alert');
          }
        },
        error => {
          return false
        });
  }
  funcSelectTraderDetails(data) {
    let record = data.data;
    this.productGeneraldetailsfrm.get('local_agent_name').setValue(record.trader_name);
    this.productGeneraldetailsfrm.get('local_agent_id').setValue(record.id);
    
    this.isRegistrantDetailsWinshow = false;
  }
  
  onProductCategoryCboSelect($event) {

    this.onLoadproductSubCategory($event.selectedItem.id);

  }
  onLoadproductSubCategory(product_category_id) {
    var data = {
      table_name: 'par_subproduct_categories',
      product_category_id: product_category_id
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.productSubCategoryData = data;
        });
  }
  onProductTypeSelection($event){

    this.productTypeEvent.emit($event.selectedItem.id);

}
onRegistrantDetailsPreparing(e) {
  //this.tbisReadOnly = this.isReadOnly;
  this.functDataGridToolbar(e, this.funconAddRegistrantDetail, 'Registered Company/Business Entity');
}
funconAddRegistrantDetail(){
      this.isRegistrantAddDetailsWinshow = true;

}


onSaveNewREgisteredCompany(){
  //nem 
  const invalid = [];
  const controls = this.newREgisteredCompanyFrm.controls;
  for (const name in controls) {
      if (controls[name].invalid) {
        this.toastr.error('Fill In All Mandatory fields with (*), missing value on '+ name.replace('_id',''), 'Alert');
          return;
      }
  }
  if (this.newREgisteredCompanyFrm.invalid) {
    return;
  }
  let local_agent_name = this.newREgisteredCompanyFrm.get('premises_name').value;
  this.spinner.show();
  this.utilityService.onsaveApplicationUniformDetails('', this.newREgisteredCompanyFrm.value, 'onSaveNewRegsisteredCompanyLtr')
  .subscribe(
    response => {
      this.product_resp = response.json();
      //the details 
      if (this.product_resp.success) {
        this.isRegistrantAddDetailsWinshow = false;
        this.productGeneraldetailsfrm.get('local_agent_id').setValue(this.product_resp.record_id)

        this.productGeneraldetailsfrm.get('local_agent_name').setValue(local_agent_name)
        this.isRegistrantDetailsWinshow =false;
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

onLoadCountries(is_local = 0) {

  let data = {
    table_name: 'par_countries',
    is_local: 1
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
onCoutryCboSelect($event) {

this.country_id = $event.selectedItem.id;

this.onLoadRegions(this.country_id);

} onLoadDistricts(region_id) {
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
onRegionsCboSelect($event) {
  this.region_id = $event.selectedItem.id;

  this.onLoadDistricts(this.region_id);

}
}
