import { Component, Input, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { SharedpromotionalAdvertComponent } from '../../sharedpromotional-advert/sharedpromotional-advert.component';
import CustomStore from 'devextreme/data/custom_store';
import DataSource from 'devextreme/data/data_source';
import { HttpHeaders } from '@angular/common/http';
import { AppSettings } from 'src/app/app-settings';
@Component({
  selector: 'app-promotional-products-particulars',
  templateUrl: './promotional-products-particulars.component.html',
  styleUrls: ['./promotional-products-particulars.component.css']
})
export class PromotionalProductsParticularsComponent  extends SharedpromotionalAdvertComponent {
  @Input() promotionalProductparticularsfrm: FormGroup;
  @Input() promProductParticularsData: any;
  @Input() isRegisteredProductsWinshow: boolean;
  @Input() registeredProductsData: any;
  
  @Input() application_code: number;
  @Input() application_id: number;
  @Input() section_id: number;
  addproductGenericNamesFrm:FormGroup;
  addproductGenericNamesModal:boolean;
  manufacturersData:any = {};
  isManufacturerSitePopupVisible:boolean;
  isnewmanufacturerModalShow:boolean;
  manufacturerFrm:FormGroup;
  isproductManufacturerModalShow:boolean;

  ngOnInit() {
    console.log(this.application_id)
    this.addproductGenericNamesFrm = new FormGroup({
      name: new FormControl('', Validators.compose([Validators.required])),
      therapeutic_code: new FormControl('', Validators.compose([])),
      description: new FormControl('', Validators.compose([])),
      section_id: new FormControl('', Validators.compose([Validators.required])),
      tablename: new FormControl('', Validators.compose([Validators.required]))
    }); 
    this.manufacturerFrm = new FormGroup({
      name: new FormControl('', Validators.compose([Validators.required])),
      country_id: new FormControl('', Validators.compose([Validators.required])),
      region_id: new FormControl('', Validators.compose([])),
      district_id: new FormControl('', Validators.compose([])),
      email_address: new FormControl('', Validators.compose([Validators.required])),
      postal_address: new FormControl('', Validators.compose([Validators.required])),
      telephone_no: new FormControl('', Validators.compose([Validators.required])),
      mobile_no: new FormControl('', Validators.compose([])),
      physical_address: new FormControl('', Validators.compose([Validators.required]))

    });
   
    this.onLoadconfirmDataParm();
    //this.onLoadproductCategory(this.section_id);
 //   this.onLoadClassifications(this.section_id);
  //  this.onLoadCommonNames(this.section_id);
  }

  
  onProductParticularsPreparing(e,is_readonly=false) {

    this.functDataGridToolbar(e, this.funcAddProductParticulars, 'Promotional Product Particulars',is_readonly);

  }
  
  funcAddProductParticulars(){
    this.onLoadproductCategory(this.section_id);
    this.onLoadClassifications(this.section_id);
    this.onLoadCommonNames(this.section_id);
    this.onLoadrouteOfAdministration();
    this.onLoaddosageForms();
    this.promotionalProductparticularsfrm.reset();
    this.isPromotionalProductparticularswinadd = true;

}
funcEditProductParticularsDetails(data) {
  
  this.onLoadproductCategory(this.section_id);
  this.onLoadClassifications(this.section_id);
  this.onLoadCommonNames(this.section_id);
  this.onLoadrouteOfAdministration();
  this.onLoaddosageForms();
  this.promotionalProductparticularsfrm.patchValue(data.data);

  this.isPromotionalProductparticularswinadd = true;

}
onLoadCommonNames(section_id) {
  var data = {
    table_name: 'par_common_names',
    section_id: section_id
  };
 
  var data = {
    table_name: 'par_common_names',
    section_id: section_id
  };
  this.config.onLoadConfigurationData(data)
    .subscribe(
      data => {
        //this.commonNamesData = data;
        this.commonNamesData = new DataSource({
            paginate: true,
            pageSize: 200,
            store: {
              type: "array",
                data: data,
                key: "id"
            }
        });
      });
     
}
  onAddNewCommonNameDetails(){

    this.addproductGenericNamesFrm.reset();
    this.addproductGenericNamesModal = true;

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
  funcSelectManufacturer(data) {
    let data_resp = data.data;
    this.promotionalProductparticularsfrm.patchValue({manufacturer_name:data_resp.manufacturer_name,manufacturer_id:data_resp.manufacturer_id});
     
    this.isManufacturerSitePopupVisible = false;

  } onManufacturerPreparing(e) {
    this.functDataGridToolbar(e, this.funcAddManufacturerSite, 'Manufacturers');
  }
  functDataGridToolbar(e, funcBtn, btn_title, is_readonly=false) {
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
          onClick: this.refreshDataGrid.bind(this)
        }
      });
  }  refreshDataGrid() {
    this.dataGrid.instance.refresh();
    this.onloadManufacturingdetails();
  }
  onloadManufacturingdetails() {
    var me = this;
   
    this.manufacturersData.store = new CustomStore({
      load: function (loadOptions: any) {

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
            this.promotionalProductparticularsfrm.patchValue({manufacturer_name:manufacturer_name,manufacturer_id:manufacturer_id});
     
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
     
onSaveNewGenericName(){
  this.addproductGenericNamesFrm.get('tablename').setValue('par_common_names')
  this.addproductGenericNamesFrm.get('section_id').setValue(this.section_id);
  this.utilityService.onsaveApplicationUniformDetails('', this.addproductGenericNamesFrm.value, 'onsaveProductConfigData')
  .subscribe(
    response => {
      this.product_resp = response.json();
      //the details 
      if (this.product_resp.success) {
        this.onLoadCommonNames(this.section_id);
       
        this.addproductGenericNamesModal = false;
        this.promotionalProductparticularsfrm.get('common_name_id').setValue(this.product_resp.record_id)
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

onLoadClassifications(section_id) {
      
  this.params_where = {
    table_name: 'par_classifications',
    section_id: section_id
  };

this.config.onLoadConfigurationData(this.params_where)
  .subscribe(
    data => {
      this.classificationData = data;
    });
} 

onLoadproductCategory(section_id) {
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
}
