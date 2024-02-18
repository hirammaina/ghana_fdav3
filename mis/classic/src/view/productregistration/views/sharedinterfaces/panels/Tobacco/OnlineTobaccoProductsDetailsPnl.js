/**
 * Created by Job on 02/17/2024.
 */
Ext.define(
  "Admin.view.productregistration.views.sharedinterfaces.panels.food.OnlineTobaccoProductsDetailsPnl",
  {
    extend: "Ext.tab.Panel",
    xtype: "onlinetobaccpproductsdetailspnl",
    layout: {
      //
      type: "card",
    },
    defaults: {
      margin: 3,
    },
    viewModel: {
      type: "productregistrationvm",
    },
    items: [
      {
        xtype: "foodproductdetailsfrm",
        autoScroll: true,
        title: "Product Details",
      },
      {
        xtype: "onlinefoodproductsotherinformationfrm", //foodproductsotherinformationfrm
        title: "Product Other Details",
      },
      {
        xtype: "hiddenfield",
        name: "product_id",
      },
      {
        xtype: "hiddenfield",
        name: "_token",
        value: token,
      },
    ],
  }
);
