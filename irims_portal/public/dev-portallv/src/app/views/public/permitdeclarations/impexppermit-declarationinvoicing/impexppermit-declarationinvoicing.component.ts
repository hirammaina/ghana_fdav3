import { Component, OnInit } from '@angular/core';
import { SharedpermitDeclarationComponent } from '../sharedpermit-declaration/sharedpermit-declaration.component';

@Component({
  selector: 'app-impexppermit-declarationinvoicing',
  templateUrl: './impexppermit-declarationinvoicing.component.html',
  styleUrls: ['./impexppermit-declarationinvoicing.component.css']
})

  export class ImpexppermitDeclarationinvoicingComponent extends SharedpermitDeclarationComponent implements OnInit {

    process_title:string;
    tracking_no:string;
  ngOnInit() {
    this.process_title ="Permit Declaration and Invoicing";
    
  }
  
}
