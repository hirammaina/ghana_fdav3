import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WithdrawalGmpDashboardComponent } from './withdrawal-gmp-dashboard.component';

describe('WithdrawalGmpDashboardComponent', () => {
  let component: WithdrawalGmpDashboardComponent;
  let fixture: ComponentFixture<WithdrawalGmpDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WithdrawalGmpDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WithdrawalGmpDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
