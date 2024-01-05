import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PoorqualityprodRptdashboardComponent } from './poorqualityprod-rptdashboard.component';

describe('PoorqualityprodRptdashboardComponent', () => {
  let component: PoorqualityprodRptdashboardComponent;
  let fixture: ComponentFixture<PoorqualityprodRptdashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PoorqualityprodRptdashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PoorqualityprodRptdashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
