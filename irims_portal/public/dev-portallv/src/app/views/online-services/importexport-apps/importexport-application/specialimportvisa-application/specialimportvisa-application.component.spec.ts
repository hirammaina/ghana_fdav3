import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SpecialimportvisaApplicationComponent } from './specialimportvisa-application.component';

describe('SpecialimportvisaApplicationComponent', () => {
  let component: SpecialimportvisaApplicationComponent;
  let fixture: ComponentFixture<SpecialimportvisaApplicationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SpecialimportvisaApplicationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SpecialimportvisaApplicationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
