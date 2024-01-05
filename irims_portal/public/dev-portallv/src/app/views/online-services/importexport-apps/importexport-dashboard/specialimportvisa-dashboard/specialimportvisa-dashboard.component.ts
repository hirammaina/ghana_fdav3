import { Component, OnInit } from '@angular/core';
import { ImportexportDashboardComponent } from '../importexport-dashboard.component';

@Component({
  selector: 'app-specialimportvisa-dashboard',
  templateUrl: './specialimportvisa-dashboard.component.html',
  styleUrls: ['./specialimportvisa-dashboard.component.css']
})
export class SpecialimportvisaDashboardComponent extends ImportexportDashboardComponent implements OnInit  {
  sectionSelection:string;
  
  ngOnInit() {
    this.sub_module_id = '83';
    this.reloadPermitApplicationsApplications({'sub_module_id':this.sub_module_id});
    this.onLoadProductAppType(this.sub_module_id);
    this. onLoadApplicationCounterDetails(this.sub_module_id);
    this.FilterDetailsFrm.get('sub_module_id').setValue(this.sub_module_id);

   // this.applicationSelectionfrm.get('section_id').setValue(2);
    this.sectionSelection = '2,7';
    this.onLoadSections();
    this.application_title = 'Medicines Special Import Visa Dashboard';  
  }
  onImportappsToolbarPreparing(e) {
    e.toolbarOptions.items.unshift({
      location: 'before',
      widget: 'dxButton',
      options: {
        text: 'Help & Guidelines',
        type: 'normal', stylingMode: 'outlined',
        icon: 'fa fa-question-circle',
        width:150,
        onClick: this.onClickSubModulehelpGuidelines.bind(this)

      }
    },{
      location: 'before',
      widget: 'dxButton',
      options: {
        text: 'Initiate Special Import Visa Application',
        tooltip: 'Initialisation of Import/Export Visa Application on Importation of Non-Registered and Non-Authorised Products.',
        type: 'default',
        icon: 'fa fa-plus',
        onClick: this.funcApplicationSelectcion.bind(this)
      }
    },{
        location: 'after',
        widget: 'dxButton',
        options: {
          icon: 'refresh',
          onClick: this.refreshDataGrid.bind(this)
        }
      });
  }
  funcApplicationSelectcion() {
    this.isPermitInitialisation = true;
   // this.app_route = ['./online-services/importexportapp-sel'];
    //this.router.navigate(this.app_route);
  }
  onApplicationSelectionSpcial() {

    if (this.applicationSelectionfrm.invalid) {
      return;
    }
    
    this.spinner.show();
    this.sectionItem = this.applicationSelectionfrm.controls['section_id'];
    let has_registered_products = this.applicationSelectionfrm.get('has_registered_products').value;
    let has_approved_visa = this.applicationSelectionfrm.get('has_approved_visa').value;
    this.producttype_defination_id= this.applicationSelectionfrm.get('producttype_defination_id').value;
    
    this.sub_module_idsel = 83;
    this.section_id = this.sectionItem.value;

    if( this.section_id < 1){
      this.toastr.error('Select Product Type to proceed', 'Alert!');

      return;
    }
    this.configService.getSectionUniformApplicationProces(this.sub_module_idsel, 1)
      .subscribe(
        data => {
          this.processData = data;
          this.spinner.hide();
          if (this.processData.success) {
            this.title = this.processData[0].name;
            this.router_link = this.processData[0].router_link;
            
            this.application_details = {producttype_defination_id: this.producttype_defination_id,  module_id: this.module_id, process_title: this.title, sub_module_id: this.sub_module_idsel, section_id: this.section_id,application_status_id: 1,status_name: 'New' };
            this.appService.setApplicationDetail(this.application_details);

            this.app_route = ['./online-services/' + this.router_link];

            this.router.navigate(this.app_route);

          }
          else {
            this.toastr.error(this.processData.message, 'Alert!');

          }


        });
    return false;
  } 
  onLoadSections() {
    var data = {
      table_name: 'par_sections',
      sectionSelection:this.sectionSelection,
      is_product_type:1
    };

    this.configService.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.sectionsData = data;
        });
  }
}
