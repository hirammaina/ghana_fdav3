import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProductVariantSelectionComponent } from './product-variant-selection.component';

describe('ProductVariantSelectionComponent', () => {
  let component: ProductVariantSelectionComponent;
  let fixture: ComponentFixture<ProductVariantSelectionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProductVariantSelectionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProductVariantSelectionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
