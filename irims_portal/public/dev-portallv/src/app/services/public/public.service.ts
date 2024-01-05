import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { AppSettings } from '../../app-settings';
import { map } from 'rxjs/operators';


import { Http, Response, Headers } from '@angular/http';
import { Observable } from 'rxjs';
import { ModalDialogService } from 'ngx-modal-dialog';
import { ToastrService } from 'ngx-toastr';
import { AuthService } from '../auth.service';
import { SpinnerVisibilityService } from 'ng-http-loader';
@Injectable({
  providedIn: 'root'
})
export class PublicService {
  config: any;
  application_details:any;
  app_resp:any;
  loading:any;
  //constructor(public myRoute: Router, public http: Http, public httpClient: HttpClient) { }
 
  constructor(public router: Router, public toastr: ToastrService, public modalServ: ModalDialogService, private authService: AuthService, private myRoute: Router, private http: Http, private httpClient: HttpClient, public spinner: SpinnerVisibilityService) {
   
  }
  onSavePoorQualityReportDetails(productData,action_url) {
    var headers = new Headers({
      "Accept": "application/json",
    });
    return this.http.post(AppSettings.base_url + 'publicaccess/'+action_url, productData, { params: {  'sysemail_address': ''}, headers: headers })
      .pipe(map(data => {
        return data;
      }));
  }

  OnSearchRegistrationDataSets(searchFrmData,path) {
    var headers = new HttpHeaders({
      "Accept": "application/json"
    });

    this.config = {
     // headers: headers,
      params:searchFrmData
    };
    return this.httpClient.get(AppSettings.base_url + 'publicaccess/'+path, this.config)
      .pipe(map(data => {
        return <any>data;
      }));

  } onsaveApplicationUniformDetails(application_code, permitData,action_url) {

    var headers = new Headers({
      "Accept": "application/json"
    });
    return this.http.post(AppSettings.base_url + 'utilities/'+action_url, permitData, { params: { application_code: application_code,  'trader_id': ''}, headers: headers })
      .pipe(map(data => {
        return data;
      }));
  }onGetApplicationDetails(searchFrmData,path) {
    var headers = new HttpHeaders({
      "Accept": "application/json"
    });
    this.config = {
     // headers: headers,
      params:searchFrmData
    };
    return this.httpClient.get(AppSettings.base_url + path, this.config)
      .pipe(map(data => {
        return <any>data;
      }));

  }
  getApplicationDetail() {
    return this.application_details;
  }
  setApplicationDetail(data: any[]) {
    this.application_details = data;
  }
  
  onPermitApplicationLoading(action_url,filter_params){

    var headers = new HttpHeaders({
      "Accept": "application/json"
    });

    filter_params.mistrader_id = filter_params.applicant_id;
    
    this.config = {
      params: filter_params,
      headers: headers
    };

    return this.httpClient.get(AppSettings.base_url + action_url, this.config)
      .pipe(map(data => {

        return <any>data;

      }));
  }
  
  onSubPoorQualityReportDetails(viewRef, application_code, tracking_no, table_name, app_route,submission_data={}) {
  this.modalServ.openDialog(viewRef, {
    title: 'Do you want to submit the application with tracking no ' + tracking_no + ' for processing?',
    childComponent: '',
    settings: {
      closeButtonClass: 'fa fa-close'
    },
    actionButtons: [{
      text: 'Yes',
      buttonClass: 'btn btn-danger',
      onAction: () => new Promise((resolve: any, reject: any) => {
        this.spinner.show();
        this.onPermitApplicationSubmit(application_code, tracking_no, table_name,submission_data)
          .subscribe(
            response => {
              this.app_resp = response.json();
              //the details 
              this.spinner.hide();
              if (this.app_resp.success) {
                this.toastr.success(this.app_resp.message, 'Response');
                this.router.navigate(app_route);
              } else {
                this.toastr.error(this.app_resp.message, 'Alert');
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
  onPermitApplicationSubmit(application_code, tracking_no, table_name,submission_data) {
    var headers = new Headers({
      "Accept": "application/json",
    });
    let user = this.authService.getUserDetails();
    return this.http.post(AppSettings.base_url + 'utilities/onSubPoorQualityReportDetails', submission_data, { params: {'application_code': application_code, 'tracking_no': tracking_no, 'table_name': table_name}, headers: headers })
      .pipe(map(data => {
        return data;
      }));
  }

  onSavePermitApplication(application_id, permitData, tracking_no,action_url,uploadData ='') {

    var headers = new Headers({
      "Accept": "application/json",
     
    });
    
    return this.http.post(AppSettings.base_url +action_url, permitData, { params: { application_id: application_id, tracking_no: tracking_no, 'trader_id': '', 'trader_email': '' }, headers: headers })
      .pipe(map(data => {
        return data;
      }));
  }
  
}
