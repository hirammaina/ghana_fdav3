import { Component, OnInit} from '@angular/core';

import { FormGroup } from '@angular/forms';

import { SharedProductregistrationclassComponent } from '../../shared-productregistrationclass/shared-productregistrationclass.component';

@Component({
  selector: 'app-initiate-product-variantapp',
  templateUrl: './initiate-product-variantapp.component.html',
  styleUrls: ['./initiate-product-variantapp.component.css']
})
export class InitiateProductVariantappComponent extends SharedProductregistrationclassComponent implements OnInit {
  productGeneraldetailsfrm: FormGroup;
  productNutrientsdetailsfrm:FormGroup;

  productapp_details: any;
  drugsingredientsData:any;
  drugsPackagingData:any;
  productManufacturersData:any;
  apiManufacturersData:any;
  terms_conditions: any;

  ngOnInit() {

    this.onLoadGuidelines(this.sub_module_id, this.section_id);
    this.productapp_details = this.appService.getProductApplicationDetail();

    if (!this.productapp_details) {
      this.router.navigate(['./../online-services/product-variant-dashboard']);
      return;
    }
      if (this.productapp_details.product_id != '') {
      //reload the other stores
      this.productGeneraldetailsfrm.patchValue(this.productapp_details);

    }
    
    this.productapp_details = {}; //drugs
    this.drugsingredientsData = {};
    this.drugsPackagingData = {};
    this.productManufacturersData = {};
    this.apiManufacturersData = {};
    this.onApplicationSubmissionFrm.get('is_fast_track').setValue(2);//medical devices
    this.autoLoadedParameters(this.section_id);


  }onLoadGuidelines(sub_module_id, section_id) {
    this.configService.onLoadAppSubmissionGuidelines(sub_module_id, section_id)
      //.pipe(first())
      .subscribe(
        data => {
          this.terms_conditions = data.data;
        },
        error => {
          return false
        });
  }

  
  
  //on save the details
  onSaveVariantProductApplication() {

    const invalid = [];
    const controls = this.productGeneraldetailsfrm.controls;
    for (const name in controls) {
        if (controls[name].invalid) {
          this.toastr.error('Fill In All Mandatory fields with (*), missing value on '+ name.replace('_id',''), 'Alert');
            return;
        }
    }
    if (this.productGeneraldetailsfrm.invalid) {
      return;
    }
    let registrant_details = this.applicationApplicantdetailsfrm.value;//applicant values

    this.spinner.show();
    this.appService.onSaveProductApplication(this.productGeneraldetailsfrm.value, registrant_details, 'onSaveVariantProductApplication')
      .subscribe(
        response => {
          this.product_resp = response.json();
          //the details 
          if (this.product_resp.success) {
            this.tracking_no = this.product_resp.tracking_no;
            this.product_id = this.product_resp.product_id;
            this.application_code = this.product_resp.application_code;
            this.productGeneraldetailsfrm.patchValue({ product_id: this.product_id })
            this.toastr.success(this.product_resp.message, 'Response');

            this.wizard.model.navigationMode.goToStep(1);

            this.onLoadProductApplciations();
            
          } else {
            this.toastr.error(this.product_resp.message, 'Alert');
          }
          this.spinner.hide();
        },
        error => {
          this.loading = false;
          
          this.spinner.hide();
        });
  }
  //drugs
  onMoveNextWizardDrugs(nextStep) {
    //validate details 
    if (nextStep == 1+this.initWizardPanel) {
      this.wizard.model.navigationMode.goToStep(nextStep);

    }
    else if (nextStep == 2+this.initWizardPanel) {
      this.wizard.model.navigationMode.goToStep(nextStep);

    }
    else if (nextStep == 3+this.initWizardPanel) {
      this.spinner.show();
      this.appService.onValidateProductOtherdetails(this.product_id,this.section_id)
        .subscribe(
          response => {
            if (response.success) {
              this.wizard.model.navigationMode.goToStep(nextStep);
            } else {
              this.toastr.error(response.message, 'Alert');
            }
            this.spinner.hide();
          },
          error => {
            this.toastr.error('Error Occurred', 'Alert');
            this.spinner.hide();
          });
     
      
    }else{
      this.wizard.model.navigationMode.goToStep(nextStep);
    }
  }
  onProductApplicationSubmit() {
    if (this.onApplicationSubmissionFrm.invalid) {
      this.toastr.error('Fill in all the submission details to proceed!!', 'Alert');
      return;
    }
    this.app_route = ['./online-services/newprodreg-dashboard'];
    this.utilityService.onPermitsApplicationSubmit(this.viewRef, this.application_code, this.tracking_no, 'wb_product_applications', this.app_route,this.onApplicationSubmissionFrm.value);
    this.isApplicationSubmitwin = false;

  } onProductDashboard() {
    //check for unsaved changes 
   this.router.navigate(['../online-services/newprodreg-dashboard']);

}
}
