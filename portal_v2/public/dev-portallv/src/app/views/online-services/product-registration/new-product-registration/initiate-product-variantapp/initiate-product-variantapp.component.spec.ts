import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InitiateProductVariantappComponent } from './initiate-product-variantapp.component';

describe('InitiateProductVariantappComponent', () => {
  let component: InitiateProductVariantappComponent;
  let fixture: ComponentFixture<InitiateProductVariantappComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InitiateProductVariantappComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InitiateProductVariantappComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
