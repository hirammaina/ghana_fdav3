/**
 * Created by Softclans on 11/12/2018.
 */
Ext.define(
  "Admin.view.importexportpermits.views.commoninterfaces.ControlledDrugsImpDetailsPnl",
  {
    extend: "Ext.tab.Panel",
    xtype: "controlleddrugsimpdetailspnl",
    layout: {
      //
      type: "card",
    },
    defaults: {
      margin: 3,
    },
    viewModel: {
      type: "importexportpermitsvm",
    },
    items: [
      {
        xtype: "tabpanel",
        title: "Permit Information",
        autoScroll: true,
        layout: "card",
        items: [
          {
            xtype: "controldrugsimpdetailsfrm",
            autoScroll: true,
            title: "Import/Export Permit Information",
          },
          {
            xtype: "tabpanel",
            layout: "card",
            title: "Consignor & Premises Details",
            autoScroll: true,
            height: 250,
            items: [
              {
                xtype: "senderreceiverdetailsfrm",
                autoScroll: true,
                title: "Consignor Details",
              },
              {
                xtype: "importexportpremisesfrm",
                autoScroll: true,
                title: "Premises Details",
              },
            ],
          },
        ],
      },
      {
        xtype: "controldrugsimppermitsproductsgrid",
        title: "Import/Export Permit Products Details",
      },
      {
        xtype: "hiddenfield",
        name: "_token",
        value: token,
      },
    ],
  }
);
