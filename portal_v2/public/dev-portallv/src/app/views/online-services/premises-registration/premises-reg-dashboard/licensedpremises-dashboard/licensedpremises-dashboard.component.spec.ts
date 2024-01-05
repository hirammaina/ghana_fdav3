import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicensedpremisesDashboardComponent } from './licensedpremises-dashboard.component';

describe('LicensedpremisesDashboardComponent', () => {
  let component: LicensedpremisesDashboardComponent;
  let fixture: ComponentFixture<LicensedpremisesDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicensedpremisesDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicensedpremisesDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
