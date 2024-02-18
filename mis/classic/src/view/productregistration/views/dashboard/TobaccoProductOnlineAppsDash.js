/**
 * Created by Job on 02/17/2024.
 */
Ext.define(
  "Admin.view.productregistration.views.dashboards.TobaccoProductOnlineAppsDash",
  {
    extend: "Ext.Container",
    xtype: "tobaccoproductonlineappsdash",
    layout: "border",
    items: [
      {
        xtype: "hiddenfield",
        name: "module_id",
        value: 1,
      },
      {
        xtype: "hiddenfield",
        name: "section_id",
        value: 8,
      },
      {
        xtype: "onlineproductregistrationgrid",
        region: "center",
        title: "Online Application Submission",
        wizard_pnl: "onlinefoodproductreceivingwizard",
        margin: 2,
      },
      {
        xtype: "dashboardguidelinesgrid",
        region: "south",
        collapsible: true,
        collapsed: true,
        titleCollapse: true,
      },
    ],
  }
);
