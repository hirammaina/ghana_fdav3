/**
 * Created by Softclans
 */
Ext.define(
  "Admin.view.productregistration.views.forms.common_forms.ProductRegInOtherCountriesFrm",
  {
    extend: "Ext.form.Panel",
    xtype: "productreginothercountriesfrm",
    controller: "configurationsvctr",
    viewModel: "productregistrationvm",
    height: Ext.Element.getViewportHeight() - 118,
    layout: {
      type: "column",
    },
    bodyPadding: 5,
    defaults: {
      columnWidth: 0.5,
      margin: 5,
      labelAlign: "top",
    },
    scrollable: true,
    autoScroll: true,
    items: [
      {
        xtype: "hiddenfield",
        margin: "0 20 20 0",
        name: "table_name",
        value: "tra_otherstates_productregistrations",
        allowBlank: true,
      },
      {
        xtype: "hiddenfield",
        name: "id",
      },
      {
        xtype: "hiddenfield",
        name: "application_code",
      },
      {
        xtype: "hiddenfield",
        name: "_token",
        value: token,
      },

      {
        xtype: "combo",
        //fieldLabel: 'Current Registration Status(Active, Withdrawn etc)',
        fieldLabel: "Current Registration Status",
        name: "current_registrationstatus_id",
        forceSelection: true,
        queryMode: "local",
        valueField: "id",
        displayField: "name",
        listeners: {
          afterrender: {
            fn: "setParamCombosStore",
            config: {
              pageSize: 10000,
              proxy: {
                url: "commonparam/getCommonParamFromTable",
                extraParams: {
                  table_name: "par_marketing_authorizations_decisions",
                },
              },
            },
            isLoad: true,
          },
          change: function (combo, newValue, oldValue, eOpts) {
            var productViewModel = combo
              .up("productreginothercountriesfrm")
              .getViewModel();
            // .getData().productregistrationvm;
            console.log(productViewModel);
            var dateTitle = "",
              reasonTitle = "",
              reasonVisible = false,
              proprietaryNameVisible = false;

            switch (newValue) {
              case 1:
                dateTitle = "Date of authorisation";
                reasonVisible = false;
                proprietaryNameVisible = true;
                break;
              case 2:
                dateTitle = "Date of withdrawal";
                reasonTitle = "Reason for withdrawal";
                reasonVisible = true;
                proprietaryNameVisible = true;
                break;
              case 3:
                dateTitle = "Date of refusal";
                reasonTitle = "Reason for Refusal";
                reasonVisible = true;
                proprietaryNameVisible = false;
              case 4:
                dateTitle = "Date of suspension/revocation";
                reasonTitle = "Reason for suspension/revocation";
                reasonVisible = true;
                proprietaryNameVisible = true;
                break;
            }

            // Update the labelField property
            productViewModel.set("dateLabelTitle", dateTitle);
            productViewModel.set("reasonLabelTitle", reasonTitle);

            var reasonField = combo
              .up("productreginothercountriesfrm")
              .down("[name=authorization_decision_reason]");
            reasonField.setVisible(reasonVisible);
            var proprietaryNameField = combo
              .up("productreginothercountriesfrm")
              .down("[name=proprietary_name]");
            proprietaryNameField.setVisible(proprietaryNameVisible);
          },
        },
      },

      {
        xtype: "combo",
        fieldLabel: "Country",
        name: "country_id",
        forceSelection: true,
        queryMode: "local",
        valueField: "id",
        displayField: "name",
        listeners: {
          beforerender: {
            fn: "setConfigCombosStore",
            config: {
              pageSize: 1000,
              proxy: {
                url: "commonparam/getCountriesByStateRegions",
              },
            },
            isLoad: true,
          },
        },
      },
      {
        xtype: "textfield",
        fieldLabel: "Approving Authority",
        name: "approving_authority",
      },
      {
        xtype: "textfield",
        fieldLabel: "Proprietary Name",
        name: "proprietary_name",
      },

      {
        xtype: "datefield",
        //fieldLabel: 'Registration Date',
        //fieldLabel: "{labelTitle}",

        format: "Y-m-d",
        altFormats:
          "d,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00",
        name: "date_of_registration",
        maxValue: new Date(),
        bind: {
          fieldLabel: "{dateLabelTitle}", // negated
        },
      },
      {
        xtype: "textfield",
        //fieldLabel: "Registration Reference",
        fieldLabel: "Authorisation number",
        name: "registration_ref",
      },

      //   {
      //     xtype: "textfield",
      //     fieldLabel: "Current Registration Status(Active, Withdrawn etc)",
      //     name: "current_registrationstatus",
      //   },

      {
        xtype: "textfield",
        name: "authorization_decision_reason",
        bind: {
          fieldLabel: "{reasonLabelTitle}", // negated
        },
      },
    ],
    dockedItems: [
      {
        xtype: "toolbar",
        ui: "footer",
        dock: "bottom",
        items: [
          "->",
          {
            text: "Save Details",
            iconCls: "x-fa fa-save",
            action: "save",
            table_name: "tra_otherstates_productregistrations",
            storeID: "productreginothercountriesStr",
            formBind: true,
            ui: "soft-purple",
            action_url: "configurations/saveConfigCommonData",
            handler: "doCreateConfigParamWin",
          },
        ],
      },
    ],
  }
);
