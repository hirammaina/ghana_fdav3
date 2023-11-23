import { Component, OnInit } from '@angular/core';
import { SharedpermitDeclarationComponent } from '../sharedpermit-declaration/sharedpermit-declaration.component';

@Component({
  selector: 'app-impexppermit-verification',
  templateUrl: './impexppermit-verification.component.html',
  styleUrls: ['./impexppermit-verification.component.css']
})
export class ImpexppermitVerificationComponent extends 
SharedpermitDeclarationComponent implements OnInit {


  ngOnInit() {
  }

}
