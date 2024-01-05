import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { SharedpromotionalAdvertComponent } from '../../sharedpromotional-advert/sharedpromotional-advert.component';

import DataSource from 'devextreme/data/data_source';

import CustomStore from 'devextreme/data/custom_store';
import { HttpHeaders } from '@angular/common/http';
import { AppSettings } from 'src/app/app-settings';
@Component({
  selector: 'app-promotional-general-info',
  templateUrl: './promotional-general-info.component.html',
  styleUrls: ['./promotional-general-info.component.css']
})
export class PromotionalGeneralInfoComponent extends SharedpromotionalAdvertComponent  {
  @Output() advertisementProductsEvent = new EventEmitter();
  
  @Input() promotionalappGeneraldetailsfrm: FormGroup;
  @Input() promProductParticularsData: any;
  @Input() isRegisteredProductsWinshow: boolean;
  @Input() registeredProductsData: any;
  @Input() section_id: number;
  @Input() module_id: number;
  @Input() status_id: number;
  @Input() sub_module_id: number;
  @Input() application_code: number;
  isReadOnly:boolean;
  is_readonly:boolean;
  isRegistrantDetailsWinshow:boolean;
  is_local_agent:any;
  onAdvertisementProductsCboSelect($event) {
    if($event.selectedItem){
      this.section_id = $event.selectedItem.id;
      this.advertisementProductsEvent.emit(this.section_id);
    }
 
  }
  onAdvertisementMaterialCategoryCboSelect($event) {
    if($event.selectedItem){
      let category_id = $event.selectedItem.id;
      if(category_id ==4){
        this.isBookCatalogueSelection = true;
      }
      else{
        this.isBookCatalogueSelection = false;
      }

    }
    else{
      this.isBookCatalogueSelection = false;

    }
   

  }
  
  funcSearchRegistrantDetails(is_local_agent) {

    //  this.spinner.show();

        this.isRegistrantDetailsWinshow = true;
        if (is_local_agent == 1) {
          this.is_local_agent = is_local_agent;
          this.trader_title = 'Local Representative';
        }
        else {
          this.is_local_agent = is_local_agent;
          this.trader_title = 'Product Registrant';
        }
        let me = this;
        this.traderAccountsDetailsData.store = new CustomStore({
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
                params: { skip: loadOptions.skip,take:loadOptions.take, searchValue:loadOptions.filter,is_local_agent:is_local_agent }
              };
              return me.httpClient.get(AppSettings.base_url + 'productregistration/getTraderInformationDetails',this.configData)
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
    
     // this.traderAccountsDetailsData.load();

  }
  funcSelectTraderDetails(data) {
      let record = data.data;
    
      this.promotionalappGeneraldetailsfrm.get('local_agent_name').setValue(record.trader_name);
      this.promotionalappGeneraldetailsfrm.get('local_agent_id').setValue(record.id);
      this.isRegistrantDetailsWinshow = false;

  }
}