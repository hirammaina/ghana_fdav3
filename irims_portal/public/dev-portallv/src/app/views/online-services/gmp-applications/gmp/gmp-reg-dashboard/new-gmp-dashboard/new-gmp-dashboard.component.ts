import { Component, OnInit } from '@angular/core';
import { SharedDashboardclassComponent } from '../../../shared-dashboardclass/shared-dashboardclass.component';

@Component({
  selector: 'app-new-gmp-dashboard',
  templateUrl: './new-gmp-dashboard.component.html',
  styleUrls: ['./new-gmp-dashboard.component.css']
})
export class NewGmpDashboardComponent extends SharedDashboardclassComponent  implements OnInit {

  gmp_dashboardtitle:string;
  ngOnInit() {
    this.onLoadPremisesCounterDetails();
    this.onLoadSections();
    this.reloadGMPApplications({});
   
    this.sectionSelection = '1,2,3,4,7,15';
    this.sectionsdata = '1,2,3,4,7,15';
    this.gmp_dashboardtitle = 'New GMP Inspection Requests';
    this.sub_module_id = 5;
    this.onLoadGmpAppType(this.sub_module_id);

    this.reloadGMPApplications({sub_module_id:this.sub_module_id});
  }
  onClickSubModuleAppSelection(sub_module_id,sub_module_name){

      this.app_route = ['./online-services/gmp-applications-selection'];
      this.router.navigate(this.app_route);
   
  }
  onGMPApplicationDashboard() {
    //check for unsaved changes 
    this.app_route = ['./online-services/new-gmp-applications'];
    this.router.navigate( this.app_route);
  }
}
