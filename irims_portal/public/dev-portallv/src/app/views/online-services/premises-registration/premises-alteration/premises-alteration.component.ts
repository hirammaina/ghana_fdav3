import { Component, OnInit, ViewChild, ViewContainerRef, Inject, ChangeDetectorRef } from '@angular/core';
import { AuthService } from '../../../../services/auth.service';
import { ConfigurationsService } from '../../../../services/shared/configurations.service';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { NgxSmartModalService } from 'ngx-smart-modal';
import { ToastrService } from 'ngx-toastr';
import { PremisesApplicationsService } from '../../../../services/premises-applications/premises-applications.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';
import { SpinnerVisibilityService } from 'ng-http-loader';
import { DxDataGridComponent } from 'devextreme-angular';
import { ModalDialogService, SimpleModalComponent } from 'ngx-modal-dialog';
import { ArchwizardModule } from 'ng2-archwizard';
import { WizardComponent } from 'ng2-archwizard';
import { SharedPremisesregistrationclassComponent } from '../shared-premisesregistrationclass/shared-premisesregistrationclass.component';
import { DocumentManagementService } from 'src/app/services/document-management/document-management.service';
import { Utilities } from 'src/app/services/common/utilities.service';


@Component({
  selector: 'app-premises-alteration',
  templateUrl: './premises-alteration.component.html',
  styleUrls: ['./premises-alteration.component.css']
})
export class PremisesAlterationComponent extends SharedPremisesregistrationclassComponent implements OnInit  {


  @ViewChild(DxDataGridComponent, ArchwizardModule)
  @ViewChild(WizardComponent)
  public wizard: WizardComponent;
  app_routing:any;
  //@Inject(WizardState) public wizard: WizardState,
  premisesGeneraldetailsfrm: FormGroup;
  premisesAmmendmentsrequestFrm: FormGroup;
  premAmmendementsRequestData:any ={};
  ammendementSectionData:any;
  isPremisesAmmendementPopup:boolean = false;


  ngOnInit() {
   
    this.premisesapp_details = this.appService.getPremisesApplicationDetail();

    if (!this.premisesapp_details) {

      this.router.navigate(['./../online-services/premisesvariation-dashboard']);
      return;
    }
    else {
      this.app_routing = ['./../online-services/premisesvariation-dashboard'];

      this.sub_module_id = this.premisesapp_details.sub_module_id;
      this.process_title = this.premisesapp_details.process_title;
      this.section_id = this.premisesapp_details.section_id;
      this.premise_id = this.premisesapp_details.premise_id;
      this.tracking_no = this.premisesapp_details.tracking_no;
      this.country_id = this.premisesapp_details.country_id;
      this.region_id = this.premisesapp_details.region_id;

      this.status_name = this.premisesapp_details.status_name;
      this.status_id = this.premisesapp_details.status_id;
      this.init_premise_id = this.premisesapp_details.init_premise_id;
      this.module_id = this.premisesapp_details.module_id;

    }
    if(this.status_id < 1){
      this.status_name = "New"
      this.status_id = 1;
    }
    this.onLoadStudyFieldsDetails();
    this.onLoadQualificationDetails();

    this.onLoadSections();
    this.onLoadCountries();


    this.onLoadZones();
    this.onLoadBusinessScales();

    this.onLoadbusinessCategories();
    this.onLoadPersonnerDetails();
    this.onLoadPersonnelPositionDetails();
this.onLoadVariationCategories();


this.premisesAmmendmentsrequestFrm = new FormGroup({
      part_id: new FormControl('', Validators.compose([Validators.required])),
      remarks: new FormControl('', Validators.compose([Validators.required])),
      id: new FormControl('', Validators.compose([]))
    });

 //   this.premisesGeneraldetailsfrm.patchValue(this.premisesapp_details);

  }

  onSavePremisesApplication() {
   
    const invalid = [];
    const controls = this.premisesGeneraldetailsfrm.controls;
    for (const name in controls) {
        if (controls[name].invalid) {
         this.toastr.error('Fill In All Mandatory fields with (*), missing value on '+ name.replace('_id',''), 'Alert');
            return;
        }
    }
    if (this.premisesGeneraldetailsfrm.invalid) {
     // return;
    }

    
    this.spinner.show();
    this.appService.onSaveRenPremisesApplication(this.premise_id, this.premisesGeneraldetailsfrm.value, this.tracking_no)
      .subscribe(
        response => {
          this.premises_resp = response.json();
          //the details 
          this.spinner.hide();
          this.tracking_no = this.premises_resp.tracking_no;
          this.premise_id = this.premises_resp.premise_id;
          this.tracking_no = this.premises_resp.tracking_no;
          this.premise_id = this.premises_resp.premise_id;
          this.application_code = this.premises_resp.application_code;

          if (this.premises_resp.success) {
            this.toastr.success(this.premises_resp.message, 'Response');
            this.wizard.model.navigationMode.goToStep(1);
          } else {
            this.toastr.error(this.premises_resp.message, 'Alert');
          }
        },
        error => {
          this.loading = false;
        });
  }
  onLoadVariationCategories() {
    var data = {
      module_id:this.module_id,
      table_name: 'par_variations_categories',
    };

    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.ammendementSectionData = data;
        });

  }

  funAddPremisesAmmendementsRquest(){
    this.premisesAmmendmentsrequestFrm.reset();

    this.isPremisesAmmendementPopup = true;

  }
  funcSelectPremiseDetails(data) {
    //check if there any pending detail
    this.appService.onCheckPendingPremisesRenewal(data.data.init_premise_id)
      .subscribe(
        data_response => {
          if (data_response.success) {
            this.premisesGeneraldetailsfrm.patchValue(data.data);

            this.init_premise_id = data.data.initial_premise_id;
            this.ispremisesSearchWinVisible = false;
            this.premisesGeneraldetailsfrm.get('init_premise_id').setValue(data.data.initial_premise_id);

          }
          else {
            this.toastr.error(data_response.message, 'Alert');

          }
        },
        error => {
          return false
        });
  }
  onLoadpremAmmendementsRequestData(premise_id) {
    this.appService.onLoadpremAmmendementsRequests(premise_id)
      //.pipe(first())
      .subscribe(
        data => {
          if(data.success){

            this.premAmmendementsRequestData = data.data;
          }
          else{
            this.toastr.error(data.message, 'Alert');
          }
        },
        error => {
          return false
        });
  } funcValidateApplicationVariationDetails(nextStep) {
    this.utilityService.validateApplicationotherDetails(this.application_code, 'wb_application_variationsdata')
      .subscribe(
        response => {
          this.spinner.hide();
          let response_data = response;
          if (response_data.success) {
            this.wizard.model.navigationMode.goToStep(nextStep);

          }
          else {

            this.toastr.error(response_data.message, 'Response');
          }
         
          this.spinner.hide();
        });

  }
  
  onSavePremisesAmmendmentsRequest() {
    if (this.premisesAmmendmentsrequestFrm.invalid) {
      return;
    }
    //also get the premises ID
    this.appService.onSavePremisesAmmendmentsRequest(this.premise_id,this.premisesAmmendmentsrequestFrm.value)
      .subscribe(
        response => {
          this.premises_resp = response.json();
          if (this.premises_resp.success) {
            this.toastr.success(this.premises_resp.message, 'Response');
            this.isPremisesAmmendementPopup = false;
            this.onLoadpremAmmendementsRequestData(this.premise_id);
          } else {
            this.toastr.error(this.premises_resp.message, 'Alert');
          }
        },
        error => {
          this.loading = false;
        });

  }funcpopWidth(percentage_width) {
    return window.innerWidth * percentage_width/100;
  }
}
