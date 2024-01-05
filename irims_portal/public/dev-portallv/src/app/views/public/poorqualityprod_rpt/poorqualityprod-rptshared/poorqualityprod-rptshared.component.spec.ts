import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PoorqualityprodRptsharedComponent } from './poorqualityprod-rptshared.component';

describe('PoorqualityprodRptsharedComponent', () => {
  let component: PoorqualityprodRptsharedComponent;
  let fixture: ComponentFixture<PoorqualityprodRptsharedComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PoorqualityprodRptsharedComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PoorqualityprodRptsharedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
