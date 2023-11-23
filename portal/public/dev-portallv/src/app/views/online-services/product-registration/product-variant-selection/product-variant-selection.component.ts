import { Component, OnInit } from '@angular/core';
import { ProductApplicationService } from 'src/app/services/product-applications/product-application.service';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { ConfigurationsService } from 'src/app/services/shared/configurations.service';
import { SpinnerVisibilityService } from 'ng-http-loader';
import { HttpHeaders, HttpClient  } from '@angular/common/http';

import CustomStore from 'devextreme/data/custom_store';
import { AppSettings } from 'src/app/app-settings';
import { AuthService } from 'src/app/services/auth.service';
@Component({
  selector: 'app-product-variant-selection',
  templateUrl: './product-variant-selection.component.html',
  styleUrls: ['./product-variant-selection.component.css']
})
export class ProductVariantSelectionComponent implements OnInit {
  registeredProductsData:any ={};
  
  productapp_details:any;
  module_id:number;
  sub_module_id:number;
  process_title:number;
  status_id:number;
  status_name:number;
  productappTypeData:any;
  section_id:number;
  processData:any;
  title:string;
   router_link:string;
    productsapp_details:any;
                  app_route:any;
                  prodclass_category_id:number;
                  trader_id:number;
                  mistrader_id:number;
  constructor(private spinner: SpinnerVisibilityService,private config: ConfigurationsService,public toastr: ToastrService,public router: Router,public appService: ProductApplicationService,public authService: AuthService,public httpClient: HttpClient) { }

  ngOnInit() {
    let user = this.authService.getUserDetails();
    this.trader_id = user.trader_id;
    this.mistrader_id = user.mistrader_id;
    this.productapp_details = this.appService.getProductApplicationDetail();

    if (!this.productapp_details) {
      this.router.navigate(['./../online-services/productreg-dashboard']);
      return;
    }
    else {
      this.sub_module_id = this.productapp_details.sub_module_id;
      
      this.module_id = this.productapp_details.module_id;
      this.process_title = this.productapp_details.process_title;
     
      this.status_name = this.productapp_details.status_name;
      this.status_id = this.productapp_details.status_id;
    }

    this.onSearchRegisteredProductApplication();
  }
  onSearchRegisteredProductApplication12(){
    this.appService.getProductsOtherDetails({}, 'onSearchFoodProductProductApplication')
    .subscribe(
      data => {
        if (data.success) {
          this.registeredProductsData = data.data;
        }
        else {
          this.toastr.success(data.message, 'Alert');
        }
      },
      error => {
        return false
      });
  }
  onSearchRegisteredProductApplication(){
   
    //  this.spinner.show();

           let me = this;
            this.registeredProductsData.store = new CustomStore({
              load: function (loadOptions: any) {
                  var params = '?';
                  params += 'skip=' + loadOptions.skip;
                  params += '&take=' + loadOptions.take;//searchValue
                  var headers = new HttpHeaders({
                    "Accept": "application/json",
                    "Authorization": "Bearer " + me.authService.getAccessToken(),
                  });
                  console.log(loadOptions.filter);
                  this.configData = {
                    headers: headers,
                    params: { skip: loadOptions.skip,take:loadOptions.take, searchValue:loadOptions.filter, table_name: 'registered_products', 'module_id': me.module_id, 'sub_module_id': me.sub_module_id,section_id:me.section_id,trader_id:me.trader_id, mistrader_id:me.mistrader_id}
                  };
                  return me.httpClient.get(AppSettings.base_url + 'productregistration/onSearchFoodProductProductApplication',this.configData)
                            .toPromise()
                            .then((data: any) => {
                                return {
                                    data: data.data,
                                    totalCount: data.totalCount
                                }
                            })
                            .catch(error => {
                               throw 'Data Loading Error' 
                            });
              }
          });

}
  onProductsesappsToolbarPreparing(e) {
    e.toolbarOptions.items.unshift( {
        location: 'after',
        widget: 'dxButton',
        options: {
          icon: 'refresh',
          onClick: this.refreshDataGrid.bind(this)
        }
      });
  }
  refreshDataGrid() {
    this.onSearchRegisteredProductApplication();
  }
  
  funSelectRegisteredProdcustsApp(data){
    let productdata = data.data;
     
    delete productdata.product_id;
    delete productdata.module_id;
    delete productdata.sub_module_id;
    
   //  this.renAltproductGeneraldetailsfrm.patchValue(productdata);
   //  this.isRegisteredProductsWinshow = false;
    
          this.section_id = productdata.section_id;
          this.prodclass_category_id = productdata.prodclass_category_id;
          this.sub_module_id =  this.sub_module_id;

          this.config.getSectionUniformApplicationProcesWithValidation(this.sub_module_id, 1,this.section_id,this.prodclass_category_id,productdata.reg_product_id,'reg_product_id' )
            .subscribe(
              data => {
                this.processData = data;
                this.spinner.hide();
                if (this.processData.success) {
                  this.title = this.processData[0].name;
                  this.router_link = this.processData[0].router_link;
                  
                  //this.productsapp_details = {module_id: this.module_id, process_title: , sub_module_id: this.sub_module_id, section_id: this.section_id,status_id: 1,status_name: 'New'};
                  
                  productdata.module_id = this.module_id;
                  productdata.process_title = this.title;
                  productdata.sub_module_id = this.sub_module_id;
                  productdata.section_id = this.section_id;
                  productdata.status_id = 1;

                  productdata.status_name = 'New';
                  productdata.form_fields = this.processData.form_fields;
                  this.appService.setProductApplicationDetail(productdata);

                  this.app_route = ['./online-services/' + this.router_link];

                  this.router.navigate(this.app_route);
                }
                else {
                  this.toastr.error(this.processData.message, 'Alert!');

                }
              });
          return false;
   }
}
