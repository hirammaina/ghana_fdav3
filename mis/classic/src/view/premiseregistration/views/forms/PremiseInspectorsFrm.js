/**
 * Created by Kip on 4/29/2019.
 */
Ext.define("Admin.view.premiseregistration.views.forms.PremiseInspectorsFrm", {
  extend: "Ext.form.Panel",
  xtype: "premiseinspectorsfrm",
  controller: "premiseregistrationvctr",
  bodyPadding: 5,
  layout: "form",
  frame: true,
  defaults: {
    margin: 5,
    allowBlank: false,
    labelStyle: "font-weight:bold",
  },
  items: [
    {
      xtype: "hiddenfield",
      name: "inspection_id",
    },
    {
      xtype: "combo",
      fieldLabel: "Inspector",
      store: "usersstr",
      valueField: "id",
      displayField: "fullnames",
      forceSelection: true,
      queryMode: "local",
      anyMatch: true,
      name: "inspector_id",
      beforerender: function (combo) {
        var usersStore = combo.getStore();
        if (!usersStore.isLoaded()) {
          usersStore.load({
            callback: function (records, operation, success) {
              if (!success) {
                // Handle loading failure
                console.error("Failed to load store");
              }
            },
          });
        }
      },
    },
    {
      xtype: "combo",
      fieldLabel: "Role",
      valueField: "id",
      displayField: "name",
      forceSelection: true,
      queryMode: "local",
      anyMatch: true,
      name: "role_id",
      listeners: {
        beforerender: {
          fn: "setPremiseRegCombosStore",
          config: {
            pageSize: 10000,
            proxy: {
              url: "commonparam/getCommonParamFromTable",
              extraParams: {
                table_name: "par_inspectors_roles",
              },
            },
          },
          isLoad: true,
        },
      },
    },
  ],
  buttons: [
    {
      xtype: "button",
      text: "Save Details",
      ui: "soft-purple",
      iconCls: "x-fa fa-save",
      formBind: true,
      action_url: "premiseregistration/saveInspectionInspectors",
      storeID: "inspectioninspectorsstr",
      table_name: "tra_premiseinspection_inspectors",
      handler: "doCreatePremiseRegParamWin",
    },
  ],
});
