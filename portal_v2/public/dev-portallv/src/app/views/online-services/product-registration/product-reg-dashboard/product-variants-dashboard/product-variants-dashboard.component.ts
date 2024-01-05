import { Component, OnInit } from '@angular/core';
import { ProductRegDashboardComponent } from '../product-reg-dashboard.component';

@Component({
  selector: 'app-product-variants-dashboard',
  templateUrl: './product-variants-dashboard.component.html',
  styleUrls: ['./product-variants-dashboard.component.css']
})
export class ProductVariantsDashboardComponent extends ProductRegDashboardComponent implements OnInit {

  
  ngOnInit() {
    
    this.appprocess_title = "Food Product Variant Registration";
    this.sub_module_id = 95;
    this.onLoadProductAppType(this.sub_module_id) ;
    this.onLoadProductApplciations({sub_module_id: this.sub_module_id});
    this.onLoadProductsCounterDetails(this.sub_module_id);

  }
  onClickSubModuleAppSelection(sub_module_id,sub_module_name){

      this.productsapp_details = {module_id: this.module_id, process_title: sub_module_name, sub_module_id: sub_module_id};
      this.appService.setProductApplicationDetail(this.productsapp_details);

      this.app_route = ['./online-services/product-variantapp-selection'];

      this.router.navigate(this.app_route);
   
  }
  
}
