import { Component, OnInit } from '@angular/core';
import { SharedImportexportclassComponent } from '../../shared-importexportclass/shared-importexportclass.component';

@Component({
  selector: 'app-specialimportvisa-application',
  templateUrl: './specialimportvisa-application.component.html',
  styleUrls: ['./specialimportvisa-application.component.css']
})
export class SpecialimportvisaApplicationComponent extends SharedImportexportclassComponent implements OnInit {

  ngOnInit() {
    if (!this.application_details) {
      this.router.navigate(['./../online-services/specialimportvisa-dashboard']);
       return
     }
  }
funcpopWidth(percentage_width) {
    return window.innerWidth * percentage_width/100;
  }
  onCloseQueryMode(){

    this.isInitalQueryResponseFrmVisible = false;
  }
}
//