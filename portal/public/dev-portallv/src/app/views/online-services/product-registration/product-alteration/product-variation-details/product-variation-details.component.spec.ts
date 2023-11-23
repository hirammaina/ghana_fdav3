import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProductVariationDetailsComponent } from './product-variation-details.component';

describe('ProductVariationDetailsComponent', () => {
  let component: ProductVariationDetailsComponent;
  let fixture: ComponentFixture<ProductVariationDetailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProductVariationDetailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProductVariationDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
