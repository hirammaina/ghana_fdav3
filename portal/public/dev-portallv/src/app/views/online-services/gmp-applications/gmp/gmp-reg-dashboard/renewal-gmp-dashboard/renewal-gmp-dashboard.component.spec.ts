import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RenewalGmpDashboardComponent } from './renewal-gmp-dashboard.component';

describe('RenewalGmpDashboardComponent', () => {
  let component: RenewalGmpDashboardComponent;
  let fixture: ComponentFixture<RenewalGmpDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RenewalGmpDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RenewalGmpDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
