
import { Component, OnInit,   Input, Output, EventEmitter } from '@angular/core';

import {  FormControl, FormGroup, Validators } from '@angular/forms';

import { DxDataGridComponent } from 'devextreme-angular';

import CustomStore from 'devextreme/data/custom_store';
import DataSource from 'devextreme/data/data_source';
import { AppSettings } from 'src/app/app-settings';

import { HttpHeaders } from '@angular/common/http';
import { ControlleddrugsSharedtaentryComponent } from '../controlleddrugs-sharedtaentry/controlleddrugs-sharedtaentry.component';
@Component({
  selector: 'app-controlleddrugslicense-prodsdetails',
  templateUrl: './controlleddrugslicense-prodsdetails.component.html',
  styleUrls: ['./controlleddrugslicense-prodsdetails.component.css']
})
export class ControlleddrugslicenseProdsdetailsComponent extends ControlleddrugsSharedtaentryComponent  implements OnInit {
  requireUnitPackData:boolean=false;
  readOnly_drugcontent:boolean=true;
  trader_id:number;
  mistrader_id:number;
  quantity:number;
  isnewmanufacturerModalShow:boolean;
  manufacturerFrm:FormGroup;
  isproductManufacturerModalShow:boolean;
  strength_asgrams:number; manufacturersData: any ={}; 
  drugs_content:number;
  @Input() controlledDrugsTypesData:any;
  @Input() controlDrugsSubstanceData:any;
  @Input() controlledDrugsBaseSaltData:any;
  @Input() gramsBaseSiUnitData:any;
  @Input() drugsPackagingTypeData:any;
  isregistered_product:boolean=false;
  dataGrid: DxDataGridComponent;
  @Input() permitProductsData: any;
  permitsDrugsProductsDetails:any;
  @Input() isPermitproductsPopupVisible: boolean;
  @Input() registeredProductsData: any;
  @Input() isPermitproductsAddPopupVisible: boolean;
  @Input() confirmDataParam: any;
  @Input() premisesOtherDetailsRows: any;
  @Input() is_regulatedproducts: boolean;
  @Input() productCategoryData: any;
  @Input() importexport_permittype_id: any;
  dosageFormsData:any;
   @Input() deviceTypeData: any;
   @Input() packagingUnitsData: any;
   @Input() siUnitsData: any;
   @Input() weightsUnitData: any;
   @Input() currencyData: any;
   @Input() permitProductsFrm: FormGroup;
   @Input() productGeneraldetailsfrm: FormGroup;
   @Input() classificationData: any;
   @Input() commonNamesData: any;
   @Input() application_code: number;
   @Input() enabled_newproductadd: boolean;
   @Input() sectionsData: any;
   @Input() sub_module_id: number;
   @Input() tracking_no: number;
   isManufacturerSitePopupVisible:boolean;

   @Input() module_id: number;
   @Input() section_id: number;
   @Input() proforma_currency_id: number;
   permit_product_id:number;
  @Output() premitProductIdEvent = new EventEmitter();
   ismedicinesproductdetails:boolean;
   prodClassCategoriesData:any;
   isRegisteredproductsPopupVisible:boolean;
   isnewproductAddWinVisible:boolean;
   loading:boolean;
   isDocumentPreviewDownloadwin:boolean;
   permitProductMenuItems = [
    {
      text: "Action",
      icon: 'menu',
      items: [
        { text: "Preview/Edit Record", action: 'edit_record', icon: 'fa fa-edit' },
        { text: "Delete Record", action: 'delete_record', icon: 'fa fa-trash' }
      ]
    }
  ];
  consignee_sendertitle:string;
  drugs_data:any;
  issenderreceiverSearchWinVisible: boolean;
  issenderreceiverAddWinVisible:boolean;
  app_resp:any;
  product_resp:any;
  
  ngOnInit(){
    this.onLoaddosageFormsData();
    if(this.section_id == 2){
      this.requireUnitPackData = true;
    }
    else{
      this.requireUnitPackData = false;
    }
    let user = this.authService.getUserDetails();
 
    this.trader_id = user.trader_id;
    this.mistrader_id = user.mistrader_id;
       
    this.manufacturerFrm = new FormGroup({
      name: new FormControl('', Validators.compose([Validators.required])),
      country_id: new FormControl('', Validators.compose([Validators.required])),
      region_id: new FormControl('', Validators.compose([])),
      district_id: new FormControl('', Validators.compose([])),
      email_address: new FormControl('', Validators.compose([])),
      postal_address: new FormControl('', Validators.compose([])),
      telephone_no: new FormControl('', Validators.compose([])),
      mobile_no: new FormControl('', Validators.compose([])),
      physical_address: new FormControl('', Validators.compose([Validators.required]))
    });
    this.onLoadPermitProductsData();
  }onLoaddosageFormsData() {
    var data = {
      table_name: 'par_dosage_forms'
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.dosageFormsData = data;
        });

  }
  funcPermitsProductPreviewedit(data) {
    this.permitProductsFrm.patchValue(data);
    this.isPermitproductsPopupVisible = true;

    this.permit_product_id = data.id;
    this.premitProductIdEvent.emit(this.permit_product_id);

  }
  funcpopHeight(percentage_height) {
    return window.innerHeight * percentage_height/100;
  }
  funcpopWidth(percentage_width) {
    return window.innerWidth * percentage_width/100;
  }
  permitProductsActionColClick(e, data) {
    var action_btn = e.itemData;
    if (action_btn.action === 'edit_record') {
      this.funcPermitsProductPreviewedit(data.data);
    }
    else if (action_btn.action == 'delete_record') {
      this.funcDeletePermitsProducts(data.data);
    }
  }
  funcDeletePermitsProducts(app_data) {

    let record_id = app_data.id;
    this.modalServ.openDialog(this.viewRef, {
      title: 'Do you want deleted the selected Permit Product with ' + app_data.brand_name + '?',
      childComponent: '',
      settings: {
        closeButtonClass: 'fa fa-close'
      },
      actionButtons: [{
        text: 'Yes',
        buttonClass: 'btn btn-danger',
        onAction: () => new Promise((resolve: any, reject: any) => {
          this.spinner.show();
          this.appService.onDeletePermitProductsDetails(record_id, 'wb_permits_products', this.application_code, 'Permit products Details')
            .subscribe(
              response => {
                this.spinner.hide();
                let response_data = response.json();
                if (response_data.success) {
                  this.onLoadPermitProductsData();
                  this.toastr.success(response_data.message, 'Response');
                }
                else {
                  this.toastr.success(response_data.message, 'Response');
                }
              },
              error => {
                this.loading = false;
              });
          resolve();
        })
      }, {
        text: 'no',
        buttonClass: 'btn btn-default',
        onAction: () => new Promise((resolve: any) => {
          resolve();
        })
      }
      ]
    });

  }
  onLoadPermitProductsData() {
    this.spinner.show();
    this.appService.getPermitsOtherDetails({ 'application_code': this.application_code }, 'getControlledDrugsLicensesProdDetails')
      .subscribe(
        data => {
          if (data.success) {

            this.permitsDrugsProductsDetails = data.data;

          }
          else {
            this.toastr.success(data.message, 'Alert');
          }
          this.spinner.hide();
        },
        error => {
          return false
        });
  }
  onRegisteredProductGridToolbar(e) {
    e.toolbarOptions.items.unshift({
      location: 'before',
      widget: 'dxButton',
      options: {
        text: 'Add New Products',
        type: 'default',
        icon: 'fa fa-plus',
        visible: this.enabled_newproductadd,
        onClick: this.funAddNewPermitProducts.bind(this)
      }
    }, {
        location: 'after',
        widget: 'dxButton',
        options: {
          icon: 'refresh',
          onClick: this.refreshProductsDataGrid.bind(this)
        }
      });

  }
  refreshProductsDataGrid() {
    this.onLoadPermitProductsData();
  }
  funAddNewPermitProducts(){
    this.isnewproductAddWinVisible = true;
  }
  
  functDataGridToolbar(e, funcBtn, btn_title) {
    e.toolbarOptions.items.unshift({
      location: 'before',
      widget: 'dxButton',
      options: {
        text: btn_title,
        type: 'default',
        icon: 'fa fa-plus',
        onClick: funcBtn.bind(this)

      }
    }, {
        location: 'after',
        widget: 'dxButton',
        options: {
          icon: 'refresh',
          onClick: this.refreshProductsDataGrid.bind(this)
        }
      });
  }
  onsavePermitProductdetails() {
    const controls = this.permitProductsFrm.controls;
        for (const name in controls) {
            if (controls[name].invalid) {
              this.toastr.error('Fill In All Mandatory fields with (*), missing value on '+ name.replace('_id',''), 'Alert');
                return;
            }
        }
    if (this.permitProductsFrm.invalid) {
      return;
    }
    this.spinner.show();
    this.appService.onsavePermitProductdetails(this.application_code, this.permitProductsFrm.value, this.tracking_no, 'saveControlDrugsLicensedetails')
      .subscribe(
        response => {
          this.app_resp = response.json();
          //the details 
          this.spinner.hide();
          if (this.app_resp.success) {
            this.permitProductsFrm.reset();
            this.isPermitproductsAddPopupVisible = false;
            this.isPermitproductsPopupVisible = false;
            this.onLoadPermitProductsData();

            this.permit_product_id = this.app_resp.record_id;
            
            this.premitProductIdEvent.emit(this.permit_product_id);
            
            this.toastr.success(this.app_resp.message, 'Response');
          } else {
            this.toastr.error(this.app_resp.message, 'Alert');
          }
        },
        error => {
          this.loading = false;
          this.spinner.hide();

        });
  }
  onupdatePermitProductdetails() {
    if (this.permitProductsFrm.invalid) {
      return;
    }
    this.spinner.show();
    this.appService.onsavePermitProductdetails(this.application_code, this.permitProductsFrm.value, this.tracking_no,'saveControlDrugsLicensedetails')
      .subscribe(
        response => {
          this.app_resp = response.json();
          //the details 
          this.spinner.hide();

          if (this.app_resp.success) {

            this.onLoadPermitProductsData();
            this.toastr.success(this.app_resp.message, 'Response');
            this.isPermitproductsAddPopupVisible = false;

          } else {
            this.toastr.error(this.app_resp.message, 'Alert');
          }
        },
        error => {
          this.loading = false;
        });
  }
  onPermitProductGridToolbar(e) {

    e.toolbarOptions.items.unshift({
      location: 'before',
      widget: 'dxButton',
      options: {
        text: 'Add Controll Drugs Particulars',
        type: 'default',
        icon: 'fa fa-plus',
        onClick: this.funAddPermitProducts.bind(this)
      }
    }, {
        location: 'after',
        widget: 'dxButton',
        options: {
          icon: 'refresh',
          onClick: this.refreshProductsDataGrid.bind(this)
        }
      });
  }
  funAddPermitProducts() {
    this.isPermitproductsPopupVisible = true;
    
  }

  funcChangeisRegisteredDrug($event) {
    if($event.value ==1){
      this.isregistered_product = true;
    }
    else{
      this.isregistered_product = false;
    }
  }
  onLoadcontrolDrugsSubstanceData(controlleddrug_type_id) {
    var data = {
      table_name: 'par_controlled_drugssubstances',
      controlleddrug_type_id:controlleddrug_type_id
    };
    this.config.onLoadConfigurationData(data)
      .subscribe(
        data => {
          this.controlDrugsSubstanceData = data;
        });
  }
  funcChangeControlDrugType($event) {
    this.onLoadcontrolDrugsSubstanceData($event.value);
    this.funcDrugsContentsCalculations();
  }
  funcChangecontrolledDrugsBaseSaltData(){
    this.funcDrugsContentsCalculations();
  }
  funcChangeProductStrength($event){
    this.permitProductsFrm.get('gramsbasesiunit_id').setValue('');
  } funcDownloadUploadedDocuments(data) {
    
    this.premitProductIdEvent.emit(data.id);
    this.isDocumentPreviewDownloadwin = true;
      
  }
  funcChangeProductStrengthUnits($event){
    let strengthsunits =$event.selectedItem;
    let conversion_unit = strengthsunits.conversion_unit
    let product_strength = this.permitProductsFrm.get('product_strength').value
    let pack_unit = this.permitProductsFrm.get('pack_unit').value

    this.permitProductsFrm.get('conversion_unit').setValue(conversion_unit);
    this.calculateProductStrengthinGrams();
  }
  calculateProductStrengthinGrams(){
    let product_strength = this.permitProductsFrm.get('product_strength').value
    let pack_unit = this.permitProductsFrm.get('pack_unit').value
    let conversion_unit = this.permitProductsFrm.get('conversion_unit').value

    if(product_strength >0){
      
      this.permitProductsFrm.get('strength_asgrams').setValue(product_strength/conversion_unit*pack_unit);
      this.baseStrengthCalculation();
    }else{
      this.permitProductsFrm.get('strength_asgrams').setValue("");
      this.permitProductsFrm.get('product_strength').setValue("");
      this.permitProductsFrm.get('conversion_unit').setValue("");
     // this.toastr.error('Enter the Product Strength, and proceed.','Alert');
    }

  }
  baseStrengthCalculation(){
   this.quantity = this.permitProductsFrm.get('quantity').value;
   this.strength_asgrams =this.permitProductsFrm.get('strength_asgrams').value;
   this.drugs_content =this.permitProductsFrm.get('drugs_content').value;
    let controlleddrug_base =this.quantity*this.strength_asgrams*this.drugs_content/100;

    this.permitProductsFrm.get('controlleddrug_base').setValue(controlleddrug_base);

  }
  funcChangecontrolDrugsSubstance($event){
    this.funcDrugsContentsCalculations();
  }
  funcDrugsContentsCalculations(){
    let controlleddrugs_type_id =   this.permitProductsFrm.get('controlleddrugs_type_id').value;
    let controlled_drugssubstances_id =   this.permitProductsFrm.get('controlled_drugssubstances_id').value;
    let controlleddrugs_basesalt_id =   this.permitProductsFrm.get('controlleddrugs_basesalt_id').value;
    if(controlleddrugs_type_id >0 && controlled_drugssubstances_id >0){
     
      if(controlleddrugs_basesalt_id >0){
        this.drugs_data = {
          table_name: 'par_controlleddrugsconv_factorsconfig',
          controlleddrugs_type_id:controlleddrugs_type_id,
          controlled_drugssubstances_id:controlled_drugssubstances_id,
          controlleddrugs_basesalt_id:controlleddrugs_basesalt_id
        };
      }
      else{
        this.drugs_data = {
          table_name: 'par_controlleddrugsconv_factorsconfig',
          controlleddrugs_type_id:controlleddrugs_type_id,
          controlled_drugssubstances_id:controlled_drugssubstances_id
        };

      }

      this.config.onLoadConfigurationData(this.drugs_data)
        .subscribe(
          data => {
            if(data.appr_pureanhydrousdrug_contents){
              this.permitProductsFrm.get('drugs_content').setValue(data.appr_pureanhydrousdrug_contents);
              this.readOnly_drugcontent =true;
              this.toastr.success('The Drugs Contents(%) has been configured for the selected Drugs ', 'Response');
            }
            else{
              this.toastr.error('The Drugs Contents(%) has not been configured for the selected product, enter Drugs Contents(%) ', 'Alert');
              this.permitProductsFrm.get('drugs_content').setValue('');
              this.readOnly_drugcontent =false;
            }
            
            
          });
     

    }
    
  }
  funcSelectRegisteredProduct(data) {
        
    this.permitProductsFrm.get('product_registration_no').setValue(data.data.certificate_no);
    this.permitProductsFrm.get('brand_name').setValue(data.data.brand_name);
    this.isRegisteredproductsPopupVisible = false;
    this.toastr.success('Product Selection', 'The following product has been selected: ' + data.data.brand_name);

}

funcSearchManufacturingSite() {

  this.isManufacturerSitePopupVisible = true;
  var me = this;
 

this.manufacturersData.store = new CustomStore({
  load: function (loadOptions: any) {
    console.log(loadOptions)
      var params = '?';
      params += 'skip=' + loadOptions.skip;
      params += '&take=' + loadOptions.take;//searchValue
      var headers = new HttpHeaders({
        "Accept": "application/json",
        "Authorization": "Bearer " + me.authService.getAccessToken(),
      });
    
      this.configData = {
        headers: headers,
        params: { skip: loadOptions.skip,take:loadOptions.take, searchValue:loadOptions.filter }
      };
      return me.httpClient.get(AppSettings.base_url + 'productregistration/getManufacturersInformation',this.configData)
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

funcAddManufacturerSite() {
  this.isnewmanufacturerModalShow = true;
  this.manufacturerFrm.reset();
}
funcSelectManufacturer(data) {
  let data_resp = data.data;
  this.permitProductsFrm.patchValue({manufacturer_name:data_resp.manufacturer_name,manufacturer_id:data_resp.manufacturer_id,country_oforigin_id:data_resp.country_id});
   
  this.isManufacturerSitePopupVisible = false;

}
onManufacturerPreparing(e) {
  this.functDataGridToolbar(e, this.funcAddManufacturerSite, 'Manufacturers');
}
onAddManufacturerDetails() {
  this.spinner.show();
  let manufacturer_name = this.manufacturerFrm.get('name').value;
  this.appProdService.onAddManufacturingSite('tra_manufacturers_information',  this.manufacturerFrm.value)
    .subscribe(
      response => {
        this.product_resp = response.json();
        //the details 
        if (this.product_resp.success) {
          this.isnewmanufacturerModalShow = false;
          this.isproductManufacturerModalShow = false;
          let manufacturer_id =this.product_resp.record_id;
          //load Manufactureing Sites 
          this.permitProductsFrm.patchValue({manufacturer_name:manufacturer_name,manufacturer_id:manufacturer_id});
         this.isManufacturerSitePopupVisible = false;
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
}
