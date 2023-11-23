import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PoorqualityprodRptsubmissionComponent } from './poorqualityprod-rptsubmission.component';

describe('PoorqualityprodRptsubmissionComponent', () => {
  let component: PoorqualityprodRptsubmissionComponent;
  let fixture: ComponentFixture<PoorqualityprodRptsubmissionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PoorqualityprodRptsubmissionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PoorqualityprodRptsubmissionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
