import { Component, Input, OnInit } from '@angular/core';
import { PoorqualityprodRptsharedComponent } from '../poorqualityprod-rptshared/poorqualityprod-rptshared.component';
import { FormGroup } from '@angular/forms';

@Component({
  selector: 'app-poorqualityprod-rptdataentry',
  templateUrl: './poorqualityprod-rptdataentry.component.html',
  styleUrls: ['./poorqualityprod-rptdataentry.component.css']
})
export class PoorqualityprodRptdataentryComponent extends PoorqualityprodRptsharedComponent implements OnInit {
  @Input() poorqualityprodrptform: FormGroup;
  @Input() is_readonly: boolean;
  
  ngOnInit() {
    
  }

}
