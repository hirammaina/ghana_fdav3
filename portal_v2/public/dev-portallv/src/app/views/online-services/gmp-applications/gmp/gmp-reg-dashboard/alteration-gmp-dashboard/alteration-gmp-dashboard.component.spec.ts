import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AlterationGmpDashboardComponent } from './alteration-gmp-dashboard.component';

describe('AlterationGmpDashboardComponent', () => {
  let component: AlterationGmpDashboardComponent;
  let fixture: ComponentFixture<AlterationGmpDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AlterationGmpDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AlterationGmpDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
