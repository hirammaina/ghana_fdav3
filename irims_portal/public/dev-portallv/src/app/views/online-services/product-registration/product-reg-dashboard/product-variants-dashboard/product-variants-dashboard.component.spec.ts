import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProductVariantsDashboardComponent } from './product-variants-dashboard.component';

describe('ProductVariantsDashboardComponent', () => {
  let component: ProductVariantsDashboardComponent;
  let fixture: ComponentFixture<ProductVariantsDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProductVariantsDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProductVariantsDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
