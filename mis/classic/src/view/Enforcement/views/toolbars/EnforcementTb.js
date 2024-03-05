Ext.define("Admin.view.Enforcement.views.toolbars.EnforcementTb", {
  extend: "Ext.toolbar.Toolbar",
  xtype: "enforcementb",
  ui: "footer",
  defaults: {
    ui: "soft-green",
    iconAlign: "top",
  },
  requires: ["Ext.ux.BoxReorderer"],
  plugins: "boxreorderer",
  overflowHandler: "scroller",
  items: [
    {
      text: "Home",
      iconCls: "x-fa fa-home",
      sec_dashboard: "enforcementdashPnl",
      name: "enforcementRegHomeBtn",
      homeDashWrapper: "#enforcementDashWrapper",
    },
    {
      text: "New Law Enforcement Application",
      iconCls: "x-fa fa-plus-square",
      menu: {
        xtype: "menu",
        items: [
          {
            text: "Reporting",
            iconCls: "x-fa fa-check",
            handler: "showNewEnforcementRegistration",
            app_type: 104,
            section_id: 1,
          },
          "-",
          {
            text: "Investigations",
            iconCls: "x-fa fa-check",
            handler: "showNewInvestigation",
            app_type: 105,
            section_id: 1,
          },
          "-",
          {
            text: "Monitoring and Compliance",
            iconCls: "x-fa fa-check",
            handler: "showMonitoringCompliance",
            app_type: 107,
            section_id: 1,
          },
          "-",
          {
            text: "Joint Operations",
            iconCls: "x-fa fa-check",
            handler: "onNewJointOperationsApplication",
            app_type: 106,
            section_id: 1,
          },
        ],
      },
    },

    {
      text: "Enforecement Register",
      iconCls: "x-fa fa-list",
      hidden: true,
      menu: {
        xtype: "menu",
        items: [
          {
            text: "Case Register",
            iconCls: "x-fa fa-list",
            wrapper_xtype: "enforcementDashWrapper",
            handler: "showCaseRegister",
            hidden: true,
          },
          "-",
          {
            text: "Monitoring Compliance Register",
            iconCls: "x-fa fa-list",
            wrapper_xtype: "enforcementDashWrapper",
            handler: "showMonitoringComplianceRegister",
          },
          "-",
          {
            text: "Monitoring Pending Cases",
            iconCls: "x-fa fa-list",
            wrapper_xtype: "enforcementDashWrapper",
            handler: "showMonitoringEnforcementAction",
          },
          "-",
          {
            text: "Joint Operations Register",
            iconCls: "x-fa fa-list",
            wrapper_xtype: "enforcementDashWrapper",
            handler: "showJointOperationsRegister",
          },
        ],
      },
    },
  ],
});
