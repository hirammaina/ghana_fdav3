/**
 * Created by Kip on 11/12/2018.
 */
Ext.define(
  "Admin.view.productregistration.views.sharedinterfaces.panels.medicaldevices.MedicalDevicesProductsDetailsPnl",
  {
    extend: "Ext.tab.Panel",
    xtype: "medicaldevicesProductsDetailsPnl",
    layout: {
      //
      type: "card", // change to card from fit Job 14.02.24
    },
    defaults: {
      margin: 3,
    },
    viewModel: {
      type: "productregistrationvm",
    },
    listeners: {
      tabchange: "funcActiveProductsOtherInformationTab",
    },
    items: [
      {
        xtype: "medicaldevicesProductsDetailsFrm",
        title: "Product Details",
      },
      {
        xtype: "medicaldevicesProductsOtherInformationFrm",
        title: "Product Other Details",
      },
      {
        xtype: "hiddenfield",
        name: "product_id",
      },
      {
        xtype: "hiddenfield",
        name: "section_id",
      },
      {
        xtype: "hiddenfield",
        name: "_token",
        value: token,
      },
    ],
  }
);
