Ext.define("Admin.view.Enforcement.views.forms.ComplainantFrm", {
  extend: "Ext.form.Panel",
  controller: "enforcementvctr",
  xtype: "complainantfrm",
  itemId: "complainantfrm",
  layout: {
    type: "column",
  },

  bodyPadding: 5,
  defaults: {
    columnWidth: 0.33,
    margin: 5,
    labelAlign: "top",
    // allowBlank: false,
  },
  autoScroll: true,
  items: [
    {
      xtype: "hiddenfield",
      name: "_token",
      value: token,
    },
    {
      xtype: "hiddenfield",
      value: "tra_enforcement_applications",
      name: "table_name",
    },
    {
      xtype: "hiddenfield",
      name: "applicant_id",
    },
    {
      xtype: "hiddenfield",
      name: "enforcement_id",
    },
    {
      xtype: "combo",
      anyMatch: true,
      fieldLabel: "Report Type:",
      margin: "20 20 20 20",
      margin: "0 20 20 0",
      name: "report_type_id",
      valueField: "id",
      displayField: "name",
      forceSelection: true,
      fieldStyle: {
        color: "green",
        "font-weight": "bold",
      },
      queryMode: "local",
      listeners: {
        beforerender: {
          fn: "setCompStore",
          config: {
            pageSize: 1000,
            proxy: {
              url: "commonparam/getCommonParamFromTable",
              extraParams: {
                table_name: "par_report_type",
              },
            },
          },
          //   config: {
          //     proxy: {
          //       extraParams: {
          //         table_name: "par_report_type",
          //       },
          //     },
          //   },
          isLoad: true,
        },
        change: function (combo, newVal, oldval, eopts) {
          var form = combo.up("form"),
            reported_by = form.down("combo[identity=reported_by]"),
            department = form.down("combo[identity=department]"),
            designation = form.down("textfield[identity=reporter_designation]");
          reporter_signature = form.down(
            "textfield[identity=reporter_signature]"
          );
          approved_by_id = form.down("combo[identity=approved_by_id]");
          approver_designation = form.down(
            "textfield[identity=approver_designation]"
          );
          approver_signature = form.down(
            "textfield[identity=approver_signature]"
          );
          external_reporter = form.down(
            "textfield[identity=external_reporter]"
          );
          captured_by = form.down("combo[identity=captured_by]");
          captured_by_department = form.down(
            "combo[identity=captured_by_department]"
          );
          external_applicant = form.down("fieldcontainer[name=external]");
          applicant_name = form.down("textfield[name=applicant_name]");
          link_applicant = form.down("button[name=link_applicant]");
          internal_applicant = form.down("fieldcontainer[name=internal]");
          fullnames = form.down("textfield[name=fullnames]");
          link_internal_applicant = form.down(
            "button[name=link_internal_applicant]"
          );
          email = form.down("textfield[name=email]");
          gender = form.down("combo[name=gender]");
          complainant_gender = form.down("combo[name=complainant_gender]");
          app_physical_address = form.down(
            "textfield[name=app_physical_address]"
          );
          country_name = form.down("combo[name=country_name]");
          app_email = form.down("textfield[name=app_email]");
          app_telephone = form.down("numberfield[name=app_telephone]");
          complainant_age = form.down("numberfield[name=complainant_age]");
          phone = form.down("numberfield[name=phone]");
          age = form.down("numberfield[name=age]");
          country = form.down("combo[name=country]");
          approver_designation = form.down(
            "textfield[name=approver_designation]"
          );
          if (newVal == 1) {
            designation.setVisible(true);
            phone.setVisible(true);
            age.setVisible(true);
            country.setVisible(true);
            //    approver_designation.setVisible(true);
            //    approved_by_id.setVisible(true);
            internal_applicant.setVisible(true);
            fullnames.setVisible(true);
            email.setVisible(true);
            gender.setVisible(true);
            link_internal_applicant.setVisible(true);
            //  reported_by.setVisible(true);
            department.setVisible(true);
            complainant_gender.setVisible(false);
            app_physical_address.setVisible(false);
            country_name.setVisible(false);
            app_email.setVisible(false);
            app_telephone.setVisible(false);
            complainant_age.setVisible(false);
            external_applicant.setVisible(false);
            applicant_name.setVisible(false);
            link_applicant.setVisible(false);
            designation.allowBlank = false;
            //  reported_by.allowBlank = false;
            department.allowBlank = false;
            phone.allowBlank = false;
            age.allowBlank = false;
            country.allowBlank = false;
            //    approver_designation.allowBlank = false;
            //    approved_by_id.allowBlank = false;
            fullnames.allowBlank = true;
            applicant_name.allowBlank = true;
            email.allowBlank = false;
            gender.allowBlank = false;
            complainant_gender.allowBlank = true;
            app_physical_address.allowBlank = true;
            country_name.allowBlank = true;
            app_email.allowBlank = true;
            app_telephone.allowBlank = true;
            complainant_age.allowBlank = true;
          }
          if (newVal == 2) {
            //captured_by.setVisible(true);
            external_applicant.setVisible(true);
            applicant_name.setVisible(true);
            link_applicant.setVisible(true);
            designation.setVisible(false);
            complainant_gender.setVisible(true);
            app_physical_address.setVisible(true);
            country_name.setVisible(true);
            app_email.setVisible(true);
            app_telephone.setVisible(true);
            complainant_age.setVisible(true);
            department.setVisible(false);
            internal_applicant.setVisible(false);
            fullnames.setVisible(false);

            link_internal_applicant.setVisible(false);
            email.setVisible(false);
            gender.setVisible(false);
            phone.setVisible(false);
            age.setVisible(false);
            country.setVisible(false);
            // approver_designation.setVisible(false);
            // approved_by_id.setVisible(false);
            applicant_name.allowBlank = false;
            complainant_gender.allowBlank = false;
            department.allowBlank = true;
            designation.allowBlank = true;
            email.allowBlank = true;
            gender.allowBlank = true;
            fullnames.allowBlank = true;
            app_physical_address.allowBlank = false;
            country_name.allowBlank = false;
            app_email.allowBlank = false;
            app_telephone.allowBlank = false;
            complainant_age.allowBlank = true;
            phone.allowBlank = true;
            age.allowBlank = true;
            country.allowBlank = true;
            // approver_designation.allowBlank = true;
            // approved_by_id.allowBlank = true;
          }
        },
      },
    },
    //internal users field
    {
      xtype: "fieldcontainer",
      layout: "column",
      hidden: true,
      //allowBlank: true,
      name: "internal",
      defaults: {
        labelAlign: "top",
      },
      fieldLabel: "Complainant/Reporter Name",
      items: [
        {
          xtype: "textfield",
          name: "fullnames",
          readOnly: true,
          hidden: true,
          allowBlank: true,
          columnWidth: 0.9,
        },
        {
          xtype: "button",
          iconCls: "x-fa fa-link",
          columnWidth: 0.1,
          hidden: true,
          //allowBlank: true,
          tooltip: "Link Applicant",
          name: "link_internal_applicant",
          bind: {
            disabled: "{isReadOnly}",
          },
          handler: "showInternalUserSelectionList",
        },
      ],
    },
    {
      xtype: "combo",
      anyMatch: true,
      fieldLabel: "Department",
      identity: "department",
      margin: "20 20 20 20",
      margin: "0 20 20 0",
      name: "department_name",
      valueField: "department",
      displayField: "department",
      allowBlank: true,
      hidden: true,
      forceSelection: true,
      readOnly: false,
      fieldStyle: {
        color: "green",
        "font-weight": "bold",
      },
      queryMode: "local",
      listeners: {
        beforerender: {
          fn: "setCompStore",
          config: {
            proxy: {
              url: "enforcement/getusers",
            },
          },
          isLoad: true,
        },
      },
    },

    {
      xtype: "textfield",
      fieldLabel: "Email",
      identity: "email",
      margin: "0 20 20 0",
      name: "email",
      hidden: true,
      allowBlank: true,
    },
    {
      xtype: "numberfield",
      fieldLabel: "PhoneNumber",
      identity: "phone",
      margin: "0 20 20 0",
      name: "phone",
      hidden: true,
      allowBlank: true,
    },
    {
      xtype: "combo",
      anyMatch: true,
      fieldLabel: "Gender:",
      margin: "20 20 20 20",
      margin: "0 20 20 0",
      name: "gender",
      valueField: "name",
      hidden: true,
      displayField: "name",
      forceSelection: true,
      fieldStyle: {
        color: "green",
        "font-weight": "bold",
      },
      queryMode: "local",
      listeners: {
        beforerender: {
          fn: "setCompStore",
          //   config: {
          //     proxy: {
          //       extraParams: {
          //         table_name: "par_gender",
          //       },
          //     },
          //   },
          config: {
            pageSize: 1000,
            proxy: {
              url: "commonparam/getCommonParamFromTable",
              extraParams: {
                table_name: "par_gender",
              },
            },
          },
          isLoad: true,
        },
      },
    },
    {
      xtype: "textfield",
      fieldLabel: "Designation",
      identity: "reporter_designation",
      margin: "0 20 20 0",
      hidden: true,
      name: "reporter_designation",
      allowBlank: true,
    },
    {
      xtype: "combo",
      anyMatch: true,
      fieldLabel: "Approved By",
      identity: "approved_by_id",
      margin: "20 20 20 20",
      margin: "0 20 20 0",
      //name: 'approved_by',
      name: "approved_by_id",
      valueField: "id",
      displayField: "name",
      allowBlank: true,
      hidden: true,
      forceSelection: true,
      fieldStyle: {
        color: "green",
        "font-weight": "bold",
      },
      queryMode: "local",
      listeners: {
        beforerender: {
          fn: "setCompStore",
          config: {
            proxy: {
              url: "enforcement/getusers",
            },
          },
          isLoad: true,
        },
      },
    },
    {
      xtype: "textfield",
      fieldLabel: "Approver Designation",
      identity: "approver_designation",
      margin: "0 20 20 0",
      name: "approver_designation",
      hidden: true,
      allowBlank: true,
    },
    {
      xtype: "numberfield",
      fieldLabel: "Age",
      identity: "age",
      margin: "0 20 20 0",
      name: "age",
      hidden: true,
      allowBlank: true,
    },

    {
      xtype: "combo",
      anyMatch: true,
      fieldLabel: "Nationality:",
      margin: "20 20 20 20",
      margin: "0 20 20 0",
      name: "country",
      valueField: "id",
      displayField: "name",
      forceSelection: true,
      hidden: true,
      fieldStyle: {
        color: "green",
        "font-weight": "bold",
      },
      queryMode: "local",
      listeners: {
        beforerender: {
          fn: "setCompStore",
          //   config: {
          //     proxy: {
          //       extraParams: {
          //         table_name: "par_countries",
          //       },
          //     },
          //   },

          config: {
            pageSize: 1000,
            proxy: {
              url: "commonparam/getCommonParamFromTable",
              extraParams: {
                table_name: "par_countries",
              },
            },
          },
          isLoad: true,
        },
      },
    },

    //External users fields

    {
      xtype: "fieldcontainer",
      layout: "column",
      hidden: true,
      //allowBlank: true,
      name: "external",
      defaults: {
        labelAlign: "top",
      },
      fieldLabel: "Complainant/Reporter Name",
      items: [
        {
          xtype: "textfield",
          name: "applicant_name",
          readOnly: true,
          hidden: true,
          allowBlank: true,
          columnWidth: 0.9,
        },
        {
          xtype: "button",
          iconCls: "x-fa fa-link",
          columnWidth: 0.1,
          hidden: true,
          //allowBlank: true,
          tooltip: "Link Applicant",
          name: "link_applicant",
          bind: {
            disabled: "{isReadOnly}",
          },
          handler: "showApplicantSelectionList",
        },
      ],
    },
    {
      xtype: "textfield",
      fieldLabel: "Residential Address",
      margin: "0 20 20 0",
      name: "app_physical_address",
    },

    {
      xtype: "combo",
      anyMatch: true,
      fieldLabel: "Nationality:",
      margin: "20 20 20 20",
      margin: "0 20 20 0",
      name: "country_name",
      valueField: "id",
      displayField: "name",
      forceSelection: true,
      fieldStyle: {
        color: "green",
        "font-weight": "bold",
      },
      queryMode: "local",
      listeners: {
        beforerender: {
          fn: "setCompStore",
          config: {
            proxy: {
              url: "commonparam/getCommonParamFromTable",
              extraParams: {
                table_name: "par_countries",
              },
            },
          },
          isLoad: true,
        },
      },
    },

    {
      xtype: "numberfield",
      fieldLabel: "Telephone",
      margin: "0 20 20 0",
      name: "app_telephone",
    },

    {
      xtype: "textfield",
      fieldLabel: "Email",
      margin: "0 20 20 0",
      name: "app_email",
    },
    {
      xtype: "numberfield",
      fieldLabel: "Age",
      margin: "0 20 20 0",
      name: "complainant_age",
    },
    {
      xtype: "combo",
      anyMatch: true,
      fieldLabel: "gender",
      margin: "20 20 20 20",
      margin: "0 20 20 0",
      name: "complainant_gender",
      valueField: "id",
      displayField: "name",
      forceSelection: true,
      fieldStyle: {
        color: "green",
        "font-weight": "bold",
      },
      queryMode: "local",
      listeners: {
        beforerender: {
          fn: "setCompStore",
          config: {
            proxy: {
              url: "commonparam/getCommonParamFromTable",
              extraParams: {
                table_name: "par_gender",
              },
            },
          },
          isLoad: true,
        },
      },
    },
    {
      xtype: "textfield",
      fieldLabel: "National Identification Number",
      margin: "0 20 20 0",
      name: "id_no",
    },
  ],
});
