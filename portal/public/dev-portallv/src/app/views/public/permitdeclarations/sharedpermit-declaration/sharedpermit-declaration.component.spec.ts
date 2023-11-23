import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SharedpermitDeclarationComponent } from './sharedpermit-declaration.component';

describe('SharedpermitDeclarationComponent', () => {
  let component: SharedpermitDeclarationComponent;
  let fixture: ComponentFixture<SharedpermitDeclarationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SharedpermitDeclarationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SharedpermitDeclarationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
