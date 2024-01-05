import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NewGmpDashboardComponent } from './new-gmp-dashboard.component';

describe('NewGmpDashboardComponent', () => {
  let component: NewGmpDashboardComponent;
  let fixture: ComponentFixture<NewGmpDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NewGmpDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NewGmpDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
