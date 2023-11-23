import { Component, Input, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ClinicalImpproductsComponent } from '../clinical-impproducts.component';

@Component({
  selector: 'app-clinical-placabolproducts',
  templateUrl: './clinical-placabolproducts.component.html',
  styleUrls: ['./clinical-placabolproducts.component.css']
})
export class ClinicalPlacabolproductsComponent  extends ClinicalImpproductsComponent implements OnInit {
  @Input() iMPProductDetailsFrm: FormGroup;
  @Input() application_id: any;
  @Input() countries: any;
  @Input() clinicalProductCategoryData: any;
  @Input() commonNameData: any;
  @Input() dosagFormData: any;
  @Input() routeOfAdminData: any;
  @Input() siUnitsData: any;
  @Input() marketlocationData: any;
  @Input() manufacturersData: any;

  ngOnInit() {
    this.iMPProductDetailsFrm = new FormGroup({
      product_category_id: new FormControl(3, Validators.compose([])),
      brand_name: new FormControl('', Validators.compose([Validators.required])),
      common_name_id: new FormControl('', Validators.compose([])),
      dosage_form_id: new FormControl('', Validators.compose([])),
      routes_of_admin_id: new FormControl('', Validators.compose([])),
      si_unit_id: new FormControl('', Validators.compose([])),
      identification_mark: new FormControl('', Validators.compose([])),
      registration_no: new FormControl('', Validators.compose([])),
      registration_date: new FormControl('', Validators.compose([])),
      market_location_id: new FormControl('', Validators.compose([Validators.required])),
      manufacturer_id: new FormControl('', Validators.compose([])),
      product_desc: new FormControl('', Validators.compose([Validators.required])),
      registered_product_id: new FormControl('', Validators.compose([])),
      id: new FormControl('', Validators.compose([])),
      product_strength: new FormControl('', Validators.compose([])),
      gmdn_code: new FormControl('', Validators.compose([])),
      classification_id: new FormControl('', Validators.compose([])),
      gmdn_term: new FormControl('', Validators.compose([])),
      gmdn_category: new FormControl('', Validators.compose([])),
      manufacturer_name: new FormControl('', Validators.compose([])),
      investigationproduct_section_id: new FormControl('', Validators.compose([Validators.required]))
      
    });
    this.onLoadclinicaltrailPlaceboProdData();
  }
  onsaveiMPProductDetailsDetails() {
    this.spinner.show();
    let table_name;
    table_name = 'wb_clinical_placebaproducts';

    this.appService.onsaveClinicaltrialOtherDetails(this.application_id, this.iMPProductDetailsFrm.value, 'savePlaceboProductDetailsDetails')
      .subscribe(
        response => {
          this.app_resp = response.json();
          //the details 
          if (this.app_resp.success) {
            this.IMPProductDetailsWinVisible = false;
            //reload
            this.onLoadclinicaltrailIMPProdData();
            this.onLoadclinicaltrailComparatorProdData();
            this.onLoadclinicaltrailPlaceboProdData();

            this.toastr.success(this.app_resp.message, 'Response');
            this.spinner.hide();

          } else {
            this.toastr.error(this.app_resp.message, 'Alert');
          }
          this.spinner.hide();
        },
        error => {
          this.toastr.error('Error Occurred', 'Alert');
        });
  }
}
