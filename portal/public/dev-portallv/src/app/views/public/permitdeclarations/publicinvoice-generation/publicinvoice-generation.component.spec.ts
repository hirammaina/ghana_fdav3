import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PublicinvoiceGenerationComponent } from './publicinvoice-generation.component';

describe('PublicinvoiceGenerationComponent', () => {
  let component: PublicinvoiceGenerationComponent;
  let fixture: ComponentFixture<PublicinvoiceGenerationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PublicinvoiceGenerationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PublicinvoiceGenerationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
