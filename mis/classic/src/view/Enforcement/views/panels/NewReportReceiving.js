Ext.define("Admin.view.Enforcement.views.panels.NewReportReceiving", {
  extend: "Ext.panel.Panel",
  xtype: "newreportreceiving",
  controller: "enforcementvctr",
  viewModel: "enforcementvm",
  layout: "fit",

  items: [
    {
      xtype: "newreportreceivingwizard",
    },
  ],
});
