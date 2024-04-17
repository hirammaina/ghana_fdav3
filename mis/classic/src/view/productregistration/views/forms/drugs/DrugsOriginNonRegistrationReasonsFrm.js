/**
 * Created by Job
 * on 04/04/2024
 */
Ext.define(
  "Admin.view.productregistration.views.forms.drugs.DrugsOriginNonRegistrationReasonsFrm",
  {
    extend: "Ext.form.Panel",
    xtype: "drugsOriginNonRegistrationReasonsFrm",
    layout: {
      type: "vbox",
    },
    bodyPadding: 5,
    controller: "productregistrationvctr",
    defaults: {
      margin: 5,
      labelAlign: "top",
      width: "100%",
      allowBlank: false,
    },
    items: [
      {
        xtype: "hiddenfield",
        name: "id",
        allowBlank: true,
      },
      {
        xtype: "hiddenfield",
        name: "product_id",
      },
      {
        xtype: "hiddenfield",
        name: "table_name",
        value: "tra_product_reasons_not_registred_in_origin",
      },

      {
        xtype: "textarea",
        name: "reason_details",
        fieldLabel: "Reason",
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
            text: "Save Reason",
            iconCls: "x-fa fa-save",
            action: "save",
            table_name:
              "tra_product_ingrtra_product_reasons_not_registred_in_originedients",
            storeID: "drugproductoriginNonRegReasonsstr",
            formBind: true,
            ui: "soft-purple",
            action_url: "productregistration/onSaveProductOtherDetails",
            handler: "saveproductOtherdetails",
          },
        ],
      },
    ],
  }
);
