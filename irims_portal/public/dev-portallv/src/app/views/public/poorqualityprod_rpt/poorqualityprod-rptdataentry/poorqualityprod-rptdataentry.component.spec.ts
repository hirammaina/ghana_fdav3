import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PoorqualityprodRptdataentryComponent } from './poorqualityprod-rptdataentry.component';

describe('PoorqualityprodRptdataentryComponent', () => {
  let component: PoorqualityprodRptdataentryComponent;
  let fixture: ComponentFixture<PoorqualityprodRptdataentryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PoorqualityprodRptdataentryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PoorqualityprodRptdataentryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
