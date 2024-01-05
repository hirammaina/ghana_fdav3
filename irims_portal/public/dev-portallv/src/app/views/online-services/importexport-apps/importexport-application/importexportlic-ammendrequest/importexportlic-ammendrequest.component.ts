import { Component, OnInit } from '@angular/core';
import { SharedImportexportclassComponent } from '../../shared-importexportclass/shared-importexportclass.component';

@Component({
  selector: 'app-importexportlic-ammendrequest',
  templateUrl: './importexportlic-ammendrequest.component.html',
  styleUrls: ['./importexportlic-ammendrequest.component.css']
})
export class ImportexportlicAmmendrequestComponent extends SharedImportexportclassComponent implements OnInit {
  prodclass_category_id:number;
  ngOnInit() {
    if (!this.application_details) {
      this.router.navigate(['./../online-services/importexportlic-ammendrequest']);
       return
     }
  }
funcpopWidth(percentage_width) {
    return window.innerWidth * percentage_width/100;
  }
  onCloseQueryMode(){

    this.isInitalQueryResponseFrmVisible = false;
  }
  funcValidateApplicationVariationDetails(nextStep) {
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
    this.appService.onSavePermitApplication(this.application_id, this.applicationGeneraldetailsfrm.value, this.tracking_no, 'importexportapp/saveImportExportApplication','')
      .subscribe(
        response => {
          this.app_resp = response.json();
          //the details 
          this.spinner.hide();

          if (this.app_resp.success) {
            this.tracking_no = this.app_resp.tracking_no;
            this.application_id = this.app_resp.application_id;
            this.application_code = this.app_resp.application_code;
            this.toastr.success(this.app_resp.message, 'Response');
            this.wizard.model.navigationMode.goToStep(2);
          } else {
            this.toastr.error(this.app_resp.message, 'Alert');
          }
        },
        error => {
          this.loading = false;
        });
  }

}
