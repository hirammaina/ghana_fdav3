import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SiteapprovalGmpDashboardComponent } from './siteapproval-gmp-dashboard.component';

describe('SiteapprovalGmpDashboardComponent', () => {
  let component: SiteapprovalGmpDashboardComponent;
  let fixture: ComponentFixture<SiteapprovalGmpDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SiteapprovalGmpDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SiteapprovalGmpDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
