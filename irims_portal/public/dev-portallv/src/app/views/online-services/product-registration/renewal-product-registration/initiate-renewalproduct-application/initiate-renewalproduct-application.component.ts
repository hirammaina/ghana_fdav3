import { Component, OnInit, ViewChild, ViewContainerRef } from '@angular/core';
import { Router } from '@angular/router';

//model

import { FormBuilder, FormGroup, Validators, ValidatorFn, AbstractControl, FormControl } from '@angular/forms';
import { HttpParams, HttpClient } from '@angular/common/http';
import { NgxSmartModalService } from 'ngx-smart-modal';

import { ToastrService } from 'ngx-toastr';
import { ArchwizardModule } from 'ng2-archwizard';
import { WizardComponent } from 'ng2-archwizard';
import { DxDataGridComponent } from 'devextreme-angular';
import { SpinnerVisibilityService } from 'ng-http-loader';
import { ModalDialogService } from 'ngx-modal-dialog';
import { Utilities } from 'src/app/services/common/utilities.service';
import { SharedProductregistrationclassComponent } from '../../shared-productregistrationclass/shared-productregistrationclass.component';
import { ConfigurationsService } from 'src/app/services/shared/configurations.service';
import { ProductApplicationService } from 'src/app/services/product-applications/product-application.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-initiate-renewalproduct-application',
  templateUrl: './initiate-renewalproduct-application.component.html',
  styleUrls: ['./initiate-renewalproduct-application.component.css']
})
export class InitiateRenewalproductApplicationComponent extends SharedProductregistrationclassComponent implements OnInit {
  productNutrientsdetailsfrm: FormGroup;

  productapp_details: any;

  ngOnInit() {
    this.productapp_details = this.appService.getProductApplicationDetail();
    if (!this.productapp_details) {
      this.router.navigate(['./../online-services/prodrenewalreg-dashboard']);
      return;
    }
      if (this.productapp_details.tra_product_id > 0) {
      //reload the other stores
      this.productGeneraldetailsfrm.patchValue(this.productapp_details);
      //this.autoLoadProductsOtherDetails(this.productapp_details.product_id);

    }
    
    this.is_readonly = false;
    this.isReadOnly = false;
   this.autoLoadedParameters(this.section_id);


  }
  onProductDashboard() {
    //check for unsaved changes 
   this.router.navigate(['../online-services/prodrenewalreg-dashboard']);

}

onProductApplicationSubmit() {
  if (this.onApplicationSubmissionFrm.invalid) {
    this.toastr.error('Fill in all the submission details to proceed!!', 'Alert');
    return;
  }
  this.app_route = ['./online-services/prodrenewalreg-dashboard'];
  this.utilityService.onPermitsApplicationSubmit(this.viewRef, this.application_code, this.tracking_no, 'wb_product_applications', this.app_route,this.onApplicationSubmissionFrm.value);
  this.isApplicationSubmitwin = false;

} 
}
