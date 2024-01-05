import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImpexppermitDeclarationinvoicingComponent } from './impexppermit-declarationinvoicing.component';

describe('ImpexppermitDeclarationinvoicingComponent', () => {
  let component: ImpexppermitDeclarationinvoicingComponent;
  let fixture: ComponentFixture<ImpexppermitDeclarationinvoicingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImpexppermitDeclarationinvoicingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImpexppermitDeclarationinvoicingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
