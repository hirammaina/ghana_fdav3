import { Component, OnInit } from '@angular/core';
import { SharedDashboardclassComponent } from '../../../shared-dashboardclass/shared-dashboardclass.component';

@Component({
  selector: 'app-siteapproval-gmp-dashboard',
  templateUrl: './siteapproval-gmp-dashboard.component.html',
  styleUrls: ['./siteapproval-gmp-dashboard.component.css']
})
export class SiteapprovalGmpDashboardComponent  extends SharedDashboardclassComponent implements OnInit {

  gmp_dashboardtitle:string;
  ngOnInit() {
    this.onLoadPremisesCounterDetails();
    this.onLoadSections();
    this.reloadGMPApplications({});
    this.sub_module_id = 81;
    this.sectionSelection = '1,2,3,4,7,15';
    this.sectionsdata = '1,2,3,4,7,15';
    this.gmp_dashboardtitle = 'New GMP Inspection Requests';
    this.onLoadGmpAppType(this.sub_module_id);
  }
  onClickSubModuleAppSelection(sub_module_id,sub_module_name){

      this.app_route = ['./online-services/gmp-applications-selection'];
      this.router.navigate(this.app_route);
   
  }

}