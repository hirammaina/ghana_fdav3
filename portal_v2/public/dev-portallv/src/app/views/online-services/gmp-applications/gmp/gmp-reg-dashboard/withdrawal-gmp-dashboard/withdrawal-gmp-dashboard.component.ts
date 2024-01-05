import { Component, OnInit } from '@angular/core';
import { SharedDashboardclassComponent } from '../../../shared-dashboardclass/shared-dashboardclass.component';

@Component({
  selector: 'app-withdrawal-gmp-dashboard',
  templateUrl: './withdrawal-gmp-dashboard.component.html',
  styleUrls: ['./withdrawal-gmp-dashboard.component.css']
})
export class WithdrawalGmpDashboardComponent extends SharedDashboardclassComponent  implements OnInit {

 
  gmp_dashboardtitle:string;
  ngOnInit() {
    this.onLoadPremisesCounterDetails();
    this.onLoadSections();
    this.reloadGMPApplications({});
    this.sub_module_id = 39;
    this.sectionSelection = '1,2,3,4,7,15';
    this.sectionsdata = '1,2,3,4,7,15';
    this.gmp_dashboardtitle = 'Widthdrawal GMP Inspection Requests';
    this.onLoadGmpAppType(this.sub_module_id);
    this.reloadGMPApplications({sub_module_id:this.sub_module_id});
  }
  onClickSubModuleAppSelection(sub_module_id,sub_module_name){

  
      this.gmpapp_details = {module_id: this.module_id, process_title: sub_module_name, sub_module_id: sub_module_id};
      this.appService.setGmpApplicationDetail(this.gmpapp_details);
      this.app_route = ['./online-services/registered-gmpselection'];
      this.router.navigate(this.app_route);
    
  }

}