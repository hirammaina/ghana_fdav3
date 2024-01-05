import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SpecialimportvisaDashboardComponent } from './specialimportvisa-dashboard.component';

describe('SpecialimportvisaDashboardComponent', () => {
  let component: SpecialimportvisaDashboardComponent;
  let fixture: ComponentFixture<SpecialimportvisaDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SpecialimportvisaDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SpecialimportvisaDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
