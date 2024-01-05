import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImpexppermitDeclarationinvdashboardComponent } from './impexppermit-declarationinvdashboard.component';

describe('ImpexppermitDeclarationinvdashboardComponent', () => {
  let component: ImpexppermitDeclarationinvdashboardComponent;
  let fixture: ComponentFixture<ImpexppermitDeclarationinvdashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImpexppermitDeclarationinvdashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImpexppermitDeclarationinvdashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
