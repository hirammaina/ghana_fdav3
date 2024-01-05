
import { Component, OnInit, ViewChild, ViewContainerRef, Inject, Input } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { NgxSmartModalService } from 'ngx-smart-modal';
import { ToastrService } from 'ngx-toastr';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';
import { SpinnerVisibilityService } from 'ng-http-loader';
import { DxDataGridComponent } from 'devextreme-angular';
import { ModalDialogService, SimpleModalComponent } from 'ngx-modal-dialog';
import { ArchwizardModule } from 'ng2-archwizard';
import { WizardComponent } from 'ng2-archwizard';
import { GmpApplicationServicesService } from 'src/app/services/gmp-applications/gmp-application-services.service';
import { DocumentManagementService } from 'src/app/services/document-management/document-management.service';
import { Utilities } from 'src/app/services/common/utilities.service';
import { SharedGmpapplicationclassComponent } from '../../shared-gmpapplicationclass/shared-gmpapplicationclass.component';
import { ConfigurationsService } from 'src/app/services/shared/configurations.service';
import { AuthService } from 'src/app/services/auth.service';
import CustomStore from 'devextreme/data/custom_store';

import { HttpHeaders, HttpClient, HttpParams } from '@angular/common/http';
import { AppSettings } from 'src/app/app-settings';
@Component({
  selector: 'app-gmp-manufatcuringdetails',
  templateUrl: './gmp-manufatcuringdetails.component.html',
  styleUrls: ['./gmp-manufatcuringdetails.component.css']
})
export class GmpManufatcuringdetailsComponent implements OnInit {
  
  @Input() gmpManufacturingBlocksDataRows: any;
  @Input() gmpProductLineDataRows: any;
  gmpProductLinkedLineDataRows:any;
  @Input() gmpproductDetailsInformationData: any;
  @Input() businessTypeDetailsData: any;
  @Input() manufatcuringSiteBlocksfrm: FormGroup;
  @Input() prodgmpAddinspectionFrm: FormGroup;
  
  
  @Input() isManufatcuringSiteBlocks: boolean;
 
  @Input() is_readonly: boolean;
  @Input() product_lineData: any;
  @Input() productlineCategoryData: any;
  @Input() productlineDescriptionData: any;
  
  @Input() isProductLinePopupVisible: any;
  @Input() isManufacturingSiteProductsDetails: boolean;
  @Input() manSiteRegisteredProductsData: any;
  @Input() isgmpAddProductsModalShow: boolean;
  
  @Input() gmpProductLineDetailsfrm: FormGroup;
  @Input() gmpapplicationGeneraldetailsfrm: FormGroup;
  @Input() manufacturing_site_id: number;
  @Input() section_id: number;
  title:string = 'Product Line';
  confirmDataParam:any;
  betaLactamData:any;
  gmpproductTypeData:any;
  events: Array<string> = [];
  gmpAddProductLineDataRows:any;
  constructor(public modalServ: ModalDialogService, public viewRef: ViewContainerRef, public spinner: SpinnerVisibilityService, public configService: ConfigurationsService, public appService: GmpApplicationServicesService, public router: Router, public formBuilder: FormBuilder, public config: ConfigurationsService, public modalService: NgxSmartModalService, public toastr: ToastrService, public authService: AuthService,public dmsService:DocumentManagementService,public utilityService:Utilities,public httpClient: HttpClient) { 
      

  }
  ngOnInit() {
    if(this.section_id == 4){

        this.title = 'Medical Devices ';
    }
    this.onLoadgmpManufacturingBlocksData(this.manufacturing_site_id);
    this.onLoadgmpproductTypeData();
    this.onLoadbetaLactamData();
    this.onLoadconfirmDataParm();
    this.onLoadgmpLinkedProductLineDataRows(this.manufacturing_site_id) ;
                      
  } onGMPBlocksProductsLineToolbar(e,is_readonly=false) {

    this.functDataGridToolbar(e, this.funAddGMPblocks, 'Manufacturing Site Block',is_readonly);

  }
  funAddGMPproductLineDetails() {
    //reset the form  
    this.onLoadgmpAddProductLineDataRows(this.manufacturing_site_id) ;
    
    this.isProductLinePopupVisible = true;

  }funcpopWidth(percentage_width) {
    return window.innerWidth * percentage_width/100;
  }
  funAddGMPblocks() {
    this.isManufatcuringSiteBlocks = true;
    //reset the form  
    this.manufatcuringSiteBlocksfrm.reset();

  }  funcEditManSiteBlockDetails(data){
    this.manufatcuringSiteBlocksfrm.patchValue(data.data);
    this.isManufatcuringSiteBlocks = true;
  }funcDeleteManSiteBlockDetails(data) {
    //func_delete records 
    let record_id = data.data.id;
    let manufacturing_site_id = data.data.manufacturing_site_id;
    let table_name = 'wb_manufacturingsite_blocks';
    this.funcDeleteDetailhelper(record_id, manufacturing_site_id, table_name, 'site_block', 'Manufacturing Blocks');

  }
  onLoadgmpManufacturingBlocksData(manufacturing_site_id) {
   // alert(manufacturing_site_id)
    this.appService.getGMPDataDetails({ manufacturing_site_id: manufacturing_site_id }, 'gmpinspection/onLoadgmpManufacturingBlocksData')
    .subscribe(
      data => {
        if (data.success) {
          this.gmpManufacturingBlocksDataRows = data.data;
        }
        else {
          this.toastr.success(data.message, 'Alert');
        }
      },
      error => {
        return false
      });
  }  onPremisesProductsLineToolbar(e,is_readonly=false) {

    this.functDataGridToolbar(e, this.funAddGMPproductLineDetails, 'Product Line Details ',is_readonly);

  }  funcEditProductLineDetails(data){
    this.gmpProductLineDetailsfrm.patchValue(data.data);
    this.isProductLinePopupVisible = true;
  }
  funcDeleteProductLineDetails(data) {
    //func_delete records 
    let record_id = data.data.record_id;
    let manufacturing_site_id = data.data.manufacturing_site_id;
    let table_name = 'wb_gmp_productline_details';
    this.funcDeleteDetailhelper(record_id, manufacturing_site_id, table_name, 'product_line', 'Manufacturing Product Line');

  } onManufacturingSiteProductsToolbar(e,is_readonly=false) {

    this.functDataGridToolbar(e, this.funcAddManufacturingSiteProducts, 'Manufacturing Site Products Details',is_readonly);

  }
  funcAddManufacturingSiteProducts() {
    //get the man_site_id 
    let man_site_id = this.gmpapplicationGeneraldetailsfrm.get('man_site_id').value;
    //the loading
    this.onLoadgmpLinkedProductLineDataRows(this.manufacturing_site_id);
      this.isManufacturingSiteProductsDetails = true;
      let me = this;
      this.manSiteRegisteredProductsData.store = new CustomStore({
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
              params: { skip: loadOptions.skip,take:loadOptions.take, searchValue:loadOptions.filter,man_site_id:man_site_id ,section_id:me.section_id}
            };
            return me.httpClient.get(AppSettings.base_url + 'gmpinspection/getManufacturingSiteRegisteredProductsData',this.configData)
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
    
    //reset the form  

  }
  
  funcDeleteGMPProductLineDetails(data) {
    //func_delete records 
    let record_id = data.data.id;
    let manufacturing_site_id = data.data.manufacturing_site_id;
    let table_name = 'wb_product_gmpinspectiondetails';
    this.funcDeleteDetailhelper(record_id, manufacturing_site_id, table_name, 'gmpproducts', 'Manufacturing Product Line');

  }
  funcDeleteDetailhelper(record_id, manufacturing_site_id, table_name, reload_type, title) {
    this.modalServ.openDialog(this.viewRef, {
      title: 'Are You sure You want to delete ' + title + '?',
      childComponent: '',
      settings: {
        closeButtonClass: 'fa fa-close'
      },
      actionButtons: [
        {
          text: 'Yes',
          buttonClass: 'btn btn-danger',
          onAction: () => new Promise((resolve: any, reject: any) => {
            this.appService.onDeleteGMPDetails(record_id, table_name, manufacturing_site_id, title)
              //.pipe(first())
              .subscribe(
                data_response => {
                  let resp = data_response.json();

                  if (resp.success) {
                    if(reload_type == 'product_line'){
                      this.onLoadgmpProductLineDataRows(manufacturing_site_id) ;
                      this.onLoadgmpLinkedProductLineDataRows(manufacturing_site_id) ;
                      
                      this.onLoadgmpAddProductLineDataRows(manufacturing_site_id);
                    }
                    else if(reload_type == 'site_block'){
                      this.onLoadgmpManufacturingBlocksData(manufacturing_site_id) 
                    } else if(reload_type == 'gmpproducts'){
                      this.onLoadgmpproductDetailsInformationData(manufacturing_site_id) 
                    }
                    
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
  }   onSavemanufatcuringSiteBlocks() {
    if (this.manufatcuringSiteBlocksfrm.invalid) {
      return;
    }
    //also get the premises ID
    this.appService.onSavemanufatcuringSiteBlocks(this.manufacturing_site_id, this.manufatcuringSiteBlocksfrm.value)
      .subscribe(
        response => {
          let gmp_resp = response.json();
          if (gmp_resp.success) {
            this.toastr.success(gmp_resp.message, 'Response');
            this.isManufatcuringSiteBlocks = false;
            this.onLoadgmpManufacturingBlocksData(this.manufacturing_site_id);
          } else {
            this.toastr.error(gmp_resp.message, 'Alert');
          }
        },
        error => {
          //this.loading = false;
        });
  }onSaveGmpProductlineDetails(change=null) {
    
    this.appService.onSaveGmpProductLineDetails(this.manufacturing_site_id, this.gmpProductLineDetailsfrm.value)
      .subscribe(
        response => {
          let gmp_resp = response.json();
          if (gmp_resp.success) {
            this.toastr.success(gmp_resp.message, 'Response');
            this.isProductLinePopupVisible = false;
            this.onLoadgmpAddProductLineDataRows(this.manufacturing_site_id);
            this.onLoadgmpProductLineDataRows(this.manufacturing_site_id);
            this.onLoadgmpLinkedProductLineDataRows(this.manufacturing_site_id) ;
                      
          } else {
            this.toastr.error(gmp_resp.message, 'Alert');
          }
        },
        error => {
      //    this.loading = false;
        });
  }onLoadgmpproductDetailsInformationData(manufacturing_site_id) {

    this.appService.getGMPDataDetails({ manufacturing_site_id: manufacturing_site_id }, 'gmpinspection/getgmpproductDetailsInformationData')
    .subscribe(
      data => {
        if (data.success) {
          this.gmpproductDetailsInformationData = data.data;
        }
        else {
          this.toastr.success(data.message, 'Alert');
        }
      },
      error => {
        return false
      });
    
}

onLoadgmpAddProductLineDataRows(manufacturing_site_id) {

  this.appService.getGMPDataDetails({ manufacturing_site_id: manufacturing_site_id,section_id:this.section_id}, 'gmpinspection/getAddGmpProductLinedetails')
  .subscribe(
    data => {
      if (data.success) {
        this.gmpAddProductLineDataRows = data.data;
      }
      else {
        this.toastr.success(data.message, 'Alert');
      }
    },
    error => {
      return false
    });
  
}


funcProduEditProductLineDetails(e){
  let params = new HttpParams();alert();
  for (let key in e.newData) {
      params = params.set(key, e.newData[key]);
  }
  console.log(params)

}

funcProduDeleteProductLineDetails(e){alert();
  let params = new HttpParams();
  for (let key in e.newData) {
      params = params.set(key, e.newData[key]);
  } console.log(params)
}
funcProduSavingProductLineDetails(e: any){
 
    let changed_data = e.changes[0];
console.log(changed_data)
      e.cancel = true;
      e.promise = this.onSaveGmpProductlineDetails(e.changes[0]);
   
} //onManufacturingSiteProductsToolbar
onLoadgmpProductLineDataRows(manufacturing_site_id) {

    this.appService.getGMPDataDetails({ manufacturing_site_id: manufacturing_site_id,section_id:this.section_id }, 'gmpinspection/getGmpProductLinedetails')
    .subscribe(
      data => {
        if (data.success) {
          this.gmpProductLineDataRows = data.data;
        }
        else {
          this.toastr.success(data.message, 'Alert');
        }
      },
      error => {
        return false
      });
    
}onLoadgmpLinkedProductLineDataRows(manufacturing_site_id) {

  this.appService.getGMPDataDetails({ manufacturing_site_id: manufacturing_site_id,section_id:this.section_id }, 'gmpinspection/gmpProductLinkedLineDataRows')
  .subscribe(
    data => {
      if (data.success) {
        this.gmpProductLinkedLineDataRows = data.data;
      }
      else {
        this.toastr.success(data.message, 'Alert');
      }
    },
    error => {
      return false
    });
  
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
  }
  funcSelectProductDetails(data){
    let productdata = data.data;
     
    this.prodgmpAddinspectionFrm.patchValue({applicant_name:productdata.applicant_name, brand_name:productdata.brand_name, reference_no:productdata.reference_no,product_id:productdata.product_id,reg_product_id:productdata.reg_product_id });

    this.isgmpAddProductsModalShow = true;

  }onSaveprodgmpAddinspection(){

    this.spinner.show();
   this.appService.onSaveGmpOtherDetails('wb_product_gmpinspectiondetails', this.prodgmpAddinspectionFrm.value, this.manufacturing_site_id)
     .subscribe(
       response => {
         let gmp_resp = response.json();
         //the details 
         if (gmp_resp.success) {
           this.isgmpAddProductsModalShow = false;
           this.isManufacturingSiteProductsDetails = false;
           this.onLoadgmpproductDetailsInformationData(this.manufacturing_site_id)
           this.toastr.success(gmp_resp.message, 'Response');
         } else {
           this.toastr.error(gmp_resp.message, 'Alert');
         }
         this.spinner.hide();
       },
       error => {
         this.toastr.error('Error Occurred', 'Alert');
       });
 }
 
 onLoadconfirmDataParm() {
  var data = {
    table_name: 'par_confirmations',
  };

  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.confirmDataParam = data;
      });
}

onLoadbetaLactamData() {
  var data = {
    table_name: 'par_beta_lactams',
  };

  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.betaLactamData = data;
      });
}

onLoadgmpproductTypeData(){

  var data = {
    table_name: 'par_gmpproduct_types',
  };

  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        this.betaLactamData = data;
      });
}

onCellProductLinePrepared(e) {
    
  if(e.rowType === "data" && e.column.dataField === "product_line_namecheck") {
    let product_line_namecheck =e.data.product_line_namecheck;

     
      if(product_line_namecheck ==1){
        e.cellElement.style.color = 'black';
        e.cellElement.style.backgroundColor = '#64B0F2';    
      }
      else{
        e.cellElement.style.color = 'white';
        e.cellElement.style.backgroundColor = '#fff';  
      
      }

    }
  }
}
