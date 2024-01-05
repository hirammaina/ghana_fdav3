import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PublicapplicationDocumentsComponent } from './publicapplication-documents.component';

describe('PublicapplicationDocumentsComponent', () => {
  let component: PublicapplicationDocumentsComponent;
  let fixture: ComponentFixture<PublicapplicationDocumentsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PublicapplicationDocumentsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PublicapplicationDocumentsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
