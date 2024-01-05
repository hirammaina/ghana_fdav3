import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImpexppermitVerificationComponent } from './impexppermit-verification.component';

describe('ImpexppermitVerificationComponent', () => {
  let component: ImpexppermitVerificationComponent;
  let fixture: ComponentFixture<ImpexppermitVerificationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImpexppermitVerificationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImpexppermitVerificationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
