Ext.define("Admin.view.Enforcement.viewController.EnforcementVctr", {
  extend: "Ext.app.ViewController",
  alias: "controller.enforcementvctr",

  init: function () {},
  setGridStore: function (obj, options) {
    this.fireEvent("setGridStore", obj, options);
  },
  setConfigGridsStore: function (obj, options) {
    this.fireEvent("setConfigGridsStore", obj, options);
  },
  setDynamicTreeGridStore: function (obj, options) {
    this.fireEvent("setDynamicTreeGridStore", obj, options);
  },

  setWorkflowCombosStore: function (obj, options) {
    this.fireEvent("setWorkflowCombosStore", obj, options);
  },

  setCompStore: function (obj, options) {
    this.fireEvent("setCompStore", obj, options);
  },
  showNewPv: function (btn) {
    var application_type = btn.app_type,
      me = this;
    me.fireEvent("onNewPvApplication", application_type, btn, 8);
  },
  showMonitoringApplicationMoreDetails: function (btn) {
    this.fireEvent("showApplicationMoreDetails", btn);
  },
  getEnforcementApplicationApprovalDetails: function (item) {
    this.fireEvent("getEnforcementApplicationApprovalDetails", item);
  },

  setParamCombosStore: function (obj, options) {
    this.fireEvent("setParamCombosStore", obj, options);
  },

  showRecommendationWin: function (item) {
    var btn = item.up("button"),
      record = btn.getWidgetRecord(),
      form = Ext.widget("applicationcommentsFrm");
    form.loadRecord(record);

    funcShowCustomizableWindow(
      "Edit Recommendations",
      "60%",
      form,
      "customizablewindow"
    );
  },
  addApplicationRecommendationLogs: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      grid = Ext.widget("applicationcommentspnl"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      module_id = record.get("module_id");
    grid.down("hiddenfield[name=application_code]").setValue(application_code);
    // grid.down('hiddenfield[name=module_id]').setValue(module_id);

    funcShowCustomizableWindow(
      "Approval Decision",
      "60%",
      grid,
      "customizablewindow",
      btn
    );
  },
  addInvestigationCommentsLogs: function (btn) {
    var button = btn.up("button"),
      // grid = button.up('grid'),
      grid = Ext.widget("investigationCommentsPnl");
    //     record = button.getWidgetRecord(),
    //     application_code = record.get('application_code'),
    //     module_id = record.get('module_id');
    // grid.down('hiddenfield[name=application_code]').setValue(application_code);
    // grid.down('hiddenfield[name=module_id]').setValue(module_id);

    funcShowCustomizableWindow(
      "Investigation Comments",
      "60%",
      grid,
      "customizablewindow",
      btn
    );
  },
  viewApplicationRecommendationLogs: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      grid = Ext.widget("caseDecisionPnl"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    // module_id = record.get('module_id');

    grid.down("hiddenfield[name=application_code]").setValue(application_code);
    // grid.down('hiddenfield[name=module_id]').setValue(module_id);

    funcShowCustomizableWindow(
      "Approval Decision",
      "60%",
      grid,
      "customizablewindow",
      btn
    );
  },
  viewMonitoringRecommendationLogs: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      grid = Ext.widget("monitoringRecommendationGrid"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      module_id = record.get("module_id");

    grid.down("hiddenfield[name=application_code]").setValue(application_code);
    grid.down("hiddenfield[name=module_id]").setValue(module_id);

    funcShowCustomizableWindow(
      "Recommendation Grid",
      "60%",
      grid,
      "customizablewindow",
      btn
    );
  },
  viewPeerRecommendationLogs: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      grid = Ext.widget("peerRecommendationGrid"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      module_id = record.get("module_id");

    grid.down("hiddenfield[name=application_code]").setValue(application_code);
    grid.down("hiddenfield[name=module_id]").setValue(module_id);

    funcShowCustomizableWindow(
      "Peer Recommendation Grid",
      "60%",
      grid,
      "customizablewindow",
      btn
    );
  },
  viewApplicationEvaluationReport: function (item) {
    //if (this.fireEvent('checkFullAccess') || this.fireEvent('checkWriteUpdate')) {
    var me = this,
      btn = item.up("button"),
      record = btn.getWidgetRecord(),
      panel = Ext.widget("productReportViewPnl");
    panel.down("hiddenfield[name=report_type]").setValue(1);
    panel
      .down("hiddenfield[name=section_id]")
      .setValue(record.get("section_id"));
    panel.down("hiddenfield[name=module_id]").setValue(record.get("module_id"));
    panel
      .down("hiddenfield[name=sub_module_id]")
      .setValue(record.get("sub_module_id"));
    panel
      .down("hiddenfield[name=process_id]")
      .setValue(record.get("process_id"));
    panel
      .down("hiddenfield[name=workflow_stage_id]")
      .setValue(record.get("workflow_stage_id"));
    panel
      .down("hiddenfield[name=application_code]")
      .setValue(record.get("application_code"));

    funcShowCustomizableWindow(
      item.winTitle,
      "70%",
      panel,
      "customizablewindow"
    );
  },
  showSelectedApplicationMoreDetails: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      container = grid.up("panel"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    container
      .down("hiddenfield[name=active_application_code]")
      .setValue(application_code);
    console.log("yyyyyyy");
    if (record.get("joint_investigation_id")) {
      joint_investigation_id = record.get("joint_investigation_id");
      container
        .down("hiddenfield[name=joint_investigation_id]")
        .setValue(joint_investigation_id);
    } else {
    }
    this.fireEvent("showEnforcementApplicationMoreDetails", btn);
  },

  showSelectedApplicationComplianceData: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      container = grid.up("panel"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");

    container
      .down("hiddenfield[name=active_application_code]")
      .setValue(application_code);
    this.fireEvent("showApplicationComplianceDetails", btn);
  },
  funcActiveOtherInformationTab: function (tab) {
    this.fireEvent("funcActiveOtherEnforcementInformationTab", tab);
  },
  showApplicantSelectionList: function (btn) {
    var grid = Ext.widget("enforcementApplicantSelectionGrid");
    if (btn.applicantType == "local") {
      grid.applicantType = btn.applicantType;
    } else {
      grid.applicantType = "nonlocal";
    }
    funcShowCustomizableWindow(
      "Applicant Selection List",
      "90%",
      grid,
      "customizablewindow"
    );
  },
  showInternalUserSelectionList: function (btn) {
    var grid = Ext.widget("internalUsersSelectionGrid");
    if (btn.applicantType == "local") {
      grid.applicantType = btn.applicantType;
    } else {
      grid.applicantType = "nonlocal";
    }
    funcShowCustomizableWindow(
      "Applicant Selection List",
      "80%",
      grid,
      "customizablewindow"
    );
  },
  funcUniformTradersearch: function (btn) {
    var grid = btn.up("grid"),
      store = grid.getStore();
    search_value = grid.down("textfield[name=search_value]").getValue();
    search_field = grid.down("combo[name=search_field]").getValue();

    store.removeAll();
    store.load({
      params: { search_value: search_value, search_field: search_field },
    });
  },
  showMystreyShopingFrm: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      container = grid.up("panel"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    console.log(container);
    container
      .down("hiddenfield[name=application_code]")
      .setValue(application_code);
    funcShowCustomizableWindow(
      "Mystrey Shopping Form",
      "60%",
      grid,
      "customizablewindow"
    );
  },
  showAddConfigParamWinFrm: function (btn) {
    var me = this,
      childXtype = btn.childXtype,
      winTitle = btn.winTitle,
      winWidth = btn.winWidth,
      child = Ext.widget(childXtype);

    funcShowCustomizableWindow(winTitle, winWidth, child, "customizablewindow");
  },
  onViewMirApplication: function (grid, record) {
    this.fireEvent("viewApplicationDetails", record);
  },
  onPremisePersonnelDblClick: function (item) {
    this.fireEvent("onPremisePersonnelDblClick", item);
  },
  funcActiveOtherPvInformationTab: function (tab) {
    this.fireEvent("funcActiveOtherPvInformationTab", tab);
  },
  showPreviousNonGridPanelUploadedDocs: function (item) {
    this.fireEvent("showPreviousNonGridPanelUploadedDocs", item);
  },
  onNextCardClick: function (btn) {
    var wizard = btn.wizard,
      wizardPnl = btn.up(wizard),
      motherPnl = wizardPnl.up("panel");
    motherPnl.getViewModel().set("atBeginning", false);
    this.navigate(btn, wizardPnl, "next");
  },
  onJointNextCardClick: function (btn) {
    var wizard = btn.wizard,
      wizardPnl = btn.up(wizard),
      motherPnl = wizardPnl.up("panel");
    motherPnl.getViewModel().set("atBeginning", false);
    this.jointnavigate(btn, wizardPnl, "next");
  },
  onPrevCardClick: function (btn) {
    var wizard = btn.wizard,
      wizardPnl = btn.up(wizard),
      motherPnl = wizardPnl.up("panel");

    motherPnl.getViewModel().set("atEnd", false);
    this.navigate(btn, wizardPnl, "prev");
  },
  navigate: function (button, wizardPanel, direction) {
    var layout = wizardPanel.getLayout(),
      progress = this.lookupReference("progress"),
      motherPnl = wizardPanel.up("panel"),
      application_id = motherPnl
        .down("hiddenfield[name=active_application_id]")
        .getValue(),
      model = motherPnl.getViewModel(),
      progressItems = progress.items.items,
      item,
      i,
      activeItem,
      activeIndex,
      nextStep = wizardPanel.items.indexOf(layout.getNext());
    activeItem = layout.getActiveItem();
    activeIndex = wizardPanel.items.indexOf(activeItem);
    if (direction == "next") {
      activeIndex++;
    } else {
      activeIndex--;
    }

    if (activeIndex > 1 && direction == "next") {
      if (application_id) {
        //fgdg
      } else {
        toastr.warning(
          "Please save application details first!!",
          "Warning Response"
        );
        return false;
      }
    }
    layout[direction]();
    for (i = 0; i < progressItems.length; i++) {
      item = progressItems[i];

      if (activeIndex === item.step) {
        item.setPressed(true);
      } else {
        item.setPressed(false);
      }

      if (Ext.isIE8) {
        item.btnIconEl.syncRepaint();
      }
    }
    activeItem.focus();
    // beginning disables previous
    if (activeIndex === 0) {
      wizardPanel.down("button[name=save_btn]").setDisabled(true);
      model.set("atBeginning", true);
    } else {
      wizardPanel.down("button[name=save_btn]").setDisabled(false);
      model.set("atBeginning", false);
    }

    if (activeIndex === 2) {
      model.set("atEnd", true);
      wizardPanel.down("button[name=save_btn]").setDisabled(true);
    } else {
      model.set("atEnd", false);
    }
  },
  jointnavigate: function (button, wizardPanel, direction) {
    var layout = wizardPanel.getLayout(),
      progress = this.lookupReference("progress"),
      motherPnl = wizardPanel.up("panel"),
      application_id = motherPnl
        .down("hiddenfield[name=active_application_id]")
        .getValue(),
      model = motherPnl.getViewModel(),
      progressItems = progress.items.items,
      item,
      i,
      activeItem,
      activeIndex,
      nextStep = wizardPanel.items.indexOf(layout.getNext());
    activeItem = layout.getActiveItem();
    activeIndex = wizardPanel.items.indexOf(activeItem);
    if (direction == "next") {
      activeIndex++;
    } else {
      activeIndex--;
    }

    if (activeIndex > 1 && direction == "next") {
      if (application_id) {
        //fgdg
      } else {
        toastr.warning(
          "Please save application details first!!",
          "Warning Response"
        );
        return false;
      }
    }
    layout[direction]();
    for (i = 0; i < progressItems.length; i++) {
      item = progressItems[i];

      if (activeIndex === item.step) {
        item.setPressed(true);
      } else {
        item.setPressed(false);
      }

      if (Ext.isIE8) {
        item.btnIconEl.syncRepaint();
      }
    }
    activeItem.focus();
    // beginning disables previous
    if (activeIndex === 0) {
      wizardPanel.down("button[name=save_btn]").setDisabled(true);
      model.set("atBeginning", true);
    } else {
      wizardPanel.down("button[name=save_btn]").setDisabled(false);
      model.set("atBeginning", false);
    }

    if (activeIndex === 3) {
      model.set("atEnd", true);
      wizardPanel.down("button[name=save_btn]").setDisabled(true);
    } else {
      model.set("atEnd", false);
    }
  },
  onViewEnforcementApplication: function (grid, record) {
    this.fireEvent("viewApplicationDetails", record);
  },
  // showNewEnforcementRegistration: function (btn)
  // {
  //     var application_type = btn.app_type,
  //         me = this;

  //     Ext.getBody().mask('Loading');
  //     var win = Ext.create('Ext.window.Window', {

  //         title: 'Nature of Report',
  //         animateTarget: btn,
  //         controller: 'enforcementvctr',
  //         layout: 'fit',
  //         width: '40%',
  //         listeners: {
  //             close: function(me){
  //                 Ext.getBody().unmask();
  //             }
  //         },
  //         items: {
  //             xtype: 'form',
  //             layout: 'form',
  //             viewModel: 'enforcmentvm',
  //             items: [{
  //                 xtype: 'combo', anyMatch: true,
  //                 fieldLabel: 'Report Type:',
  //                 margin: '20 20 20 20',
  //                 margin: '0 20 20 0',
  //                 name: 'report_type_id',
  //                 valueField: 'id',
  //                 displayField: 'name',
  //                 forceSelection: true,
  //                 fieldStyle: {
  //                     'color': 'green',
  //                     'font-weight': 'bold'
  //                 },
  //                 queryMode: 'local',
  //                 listeners: {
  //                     beforerender: {
  //                         fn: 'setCompStore',
  //                         config: {
  //                             proxy: {
  //                                 extraParams: {
  //                                     table_name: 'par_report_type'
  //                                 }
  //                             }
  //                         },
  //                         isLoad: true
  //                     },
  //                     select: function(combo, record, eopts){
  //                         var report_type_id = record.get('id'),
  //                             form = combo.up('form');

  //                         me.fireEvent('onNewEnforcementApplication',application_type, btn, report_type_id);
  //                         win.close();

  //                     }
  //                 }

  //             },
  //         ]
  //         }
  //     });
  //     win.show();
  // },
  showNewEnforcementRegistration: function (btn) {
    console.log("here");
    var application_type = btn.app_type,
      me = this;
    me.fireEvent("onNewEnforcementApplication", application_type, btn);
  },
  onNewJointOperationsApplication: function (btn) {
    var application_type = btn.app_type,
      me = this;
    me.fireEvent("onNewJointOperationsApplication", application_type, btn);
  },
  quickNavigation: function (btn) {
    var step = btn.step,
      wizard = btn.wizard,
      wizardPnl = btn.up(wizard),
      motherPnl = wizardPnl.up("panel"),
      application_id = motherPnl
        .down("hiddenfield[name=active_application_id]")
        .getValue(),
      progress = wizardPnl.down("#progress_tbar"),
      progressItems = progress.items.items;

    if (step > 1) {
      var thisItem = progressItems[step];
      if (!application_id) {
        thisItem.setPressed(false);
        toastr.warning(
          "Please save report details first!!",
          "Warning Response"
        );
        return false;
      }
    }
    if (step == 0) {
      wizardPnl.down("button[name=save_btn]").setDisabled(true);
      motherPnl.getViewModel().set("atBeginning", true);
    } else if (step == 1) {
      wizardPnl.down("button[name=save_btn]").setDisabled(false);
      motherPnl.getViewModel().set("atBeginning", false);
    } else {
      motherPnl.getViewModel().set("atBeginning", false);
    }

    if (step === 2) {
      motherPnl.getViewModel().set("atEnd", true);
      // wizardPnl.down('button[name=save_screening_btn]').setVisible(false);
      // motherPnl.getViewModel().set('atEnd', true);
    } else {
      wizardPnl
        .down("button[name=save_btn]")
        .setText("Save Application Details");
      wizardPnl.down("button[name=save_btn]").handler =
        "saveEnforcementReceivingDetails";
      motherPnl.getViewModel().set("atEnd", false);
    }
    wizardPnl.getLayout().setActiveItem(step);
    var layout = wizardPnl.getLayout(),
      item = null,
      i = 0,
      activeItem = layout.getActiveItem();

    for (i = 0; i < progressItems.length; i++) {
      item = progressItems[i];

      if (step === item.step) {
        item.setPressed(true);
      } else {
        item.setPressed(false);
      }

      if (Ext.isIE8) {
        item.btnIconEl.syncRepaint();
      }
    }
    activeItem.focus();
  },
  MonitoringquickNavigation: function (btn) {
    var step = btn.step,
      wizard = btn.wizard,
      wizardPnl = btn.up(wizard),
      motherPnl = wizardPnl.up("panel"),
      application_id = motherPnl
        .down("hiddenfield[name=active_application_id]")
        .getValue(),
      progress = wizardPnl.down("#progress_tbar"),
      progressItems = progress.items.items;

    if (step > 0) {
      var thisItem = progressItems[step];
      if (!application_id) {
        thisItem.setPressed(false);
        toastr.warning(
          "Please save report details first!!",
          "Warning Response"
        );
        return false;
      }
    }
    if (step == 0) {
      motherPnl.getViewModel().set("atBeginning", true);
    } else if (step == 1) {
      motherPnl.getViewModel().set("atBeginning", false);
    } else {
      motherPnl.getViewModel().set("atBeginning", false);
    }

    if (step === 3) {
      motherPnl.getViewModel().set("atEnd", true);
    } else {
      motherPnl.getViewModel().set("atEnd", false);
    }
    wizardPnl.getLayout().setActiveItem(step);
    var layout = wizardPnl.getLayout(),
      item = null,
      i = 0,
      activeItem = layout.getActiveItem();

    for (i = 0; i < progressItems.length; i++) {
      item = progressItems[i];

      if (step === item.step) {
        item.setPressed(true);
      } else {
        item.setPressed(false);
      }

      if (Ext.isIE8) {
        item.btnIconEl.syncRepaint();
      }
    }
    activeItem.focus();
  },
  jointquickNavigation: function (btn) {
    var step = btn.step,
      wizard = btn.wizard,
      wizardPnl = btn.up(wizard),
      motherPnl = wizardPnl.up("panel"),
      application_id = motherPnl
        .down("hiddenfield[name=active_application_id]")
        .getValue(),
      progress = wizardPnl.down("#progress_tbar"),
      progressItems = progress.items.items;

    if (step > 0) {
      var thisItem = progressItems[step];
      if (!application_id) {
        thisItem.setPressed(false);
        toastr.warning("Please Save Plan details first!!", "Warning Response");
        return false;
      }
    }
    if (step == 0) {
      wizardPnl.down("button[name=save_btn]").setDisabled(false);
      motherPnl.getViewModel().set("atBeginning", true);
    } else {
      wizardPnl.down("button[name=save_btn]").setDisabled(true);
      motherPnl.getViewModel().set("atBeginning", false);
    }

    if (step === 3) {
      motherPnl.getViewModel().set("atEnd", true);
    } else {
      wizardPnl
        .down("button[name=save_btn]")
        .setText("Save Application Details");
      motherPnl.getViewModel().set("atEnd", false);
    }
    wizardPnl.getLayout().setActiveItem(step);
    var layout = wizardPnl.getLayout(),
      item = null,
      i = 0,
      activeItem = layout.getActiveItem();

    for (i = 0; i < progressItems.length; i++) {
      item = progressItems[i];

      if (step === item.step) {
        item.setPressed(true);
      } else {
        item.setPressed(false);
      }

      if (Ext.isIE8) {
        item.btnIconEl.syncRepaint();
      }
    }
    activeItem.focus();
  },
  showRegistererdProductSelectionList: function (btn) {
    var grid = Ext.widget("enforcementregisteredproductsdetailsgrid"),
      form = btn.up("form");
    funcShowCustomizableWindowWithObject(
      "Registered Product Selection List",
      "65%",
      grid,
      "customizablewindow",
      form
    );
  },
  showRegistererdFacilitySelectionList: function (btn) {
    var grid = Ext.widget("enforcementregisteredfacilitydetailsgrids"),
      form = btn.up("form");
    funcShowCustomizableWindowWithObject(
      "Licensed Facility Selection List",
      "65%",
      grid,
      "customizablewindow",
      form
    );
  },
  showLinkResposiblePersonnel: function (btn) {
    var grid = Ext.widget("monitoringpremisepersonnelgrid"),
      form = btn.up("form");
    funcShowCustomizableWindowWithObject(
      "Facility Personnel",
      "65%",
      grid,
      "customizablewindow",
      form
    );
  },
  showAnnualWorkplanSelectionList: function (btn) {
    var grid = Ext.widget("monitoringworkplangrid"),
      form = btn.up("form");
    funcShowCustomizableWindowWithObject(
      "Annual Workplan List",
      "65%",
      grid,
      "customizablewindow",
      form
    );
  },
  showInvestigationApprovalwin: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget(btn.childXtype),
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      decision_id = record.get("decision_id");
    form.loadRecord(record);
    funcShowCustomizableWindow(
      "Investigation Approval form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  showMonitorApprovalwin: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget(btn.childXtype),
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      decision_id = record.get("decision_id");
    form.loadRecord(record);
    funcShowCustomizableWindow(
      "Enforcement Decision form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  showApplicationUploadedDocument: function (btn) {
    // showApplicationMoreDetails
    this.fireEvent("showPreviousUploadedDocs", btn);
  },
  showSuspectedOffenceForm: function (btn) {
    var me = this,
      grid = btn.up("grid"),
      win = grid.up("window"),
      // section_id =2 ,

      title = btn.winTitle,
      form = Ext.widget("suspectedoffenceform"),
      storeArray = eval(btn.stores),
      arrayLength = storeArray.length;
    // filter = "section_id:" + section_id;

    funcShowCustomizableWindow(title, "50%", form, "customizablewindow");
  },
  showAnnualWorkplanForm: function (btn) {
    var me = this,
      grid = btn.up("grid"),
      win = grid.up("window"),
      // section_id =2 ,

      title = btn.winTitle,
      form = Ext.widget("annualWorkplanFrm"),
      storeArray = eval(btn.stores),
      arrayLength = storeArray.length;
    // filter = "section_id:" + section_id;

    funcShowCustomizableWindow(title, "55%", form, "customizablewindow");
  },
  editResponsiblProfessionalDetails: function (item) {
    var form = Ext.widget("monitoringpremisepersonnelfrm"),
      btn = item.up("button"),
      record = btn.getWidgetRecord();

    form.loadRecord(record);
    funcShowCustomizableWindow(
      "Edit Responsible Professional",
      "55%",
      form,
      "customizablewindow"
    );
  },
  editProductInformationDetails: function (item) {
    var form = Ext.widget("productInformationFrm"),
      btn = item.up("button"),
      record = btn.getWidgetRecord();

    form.loadRecord(record);
    funcShowCustomizableWindow(
      "Edit Product Information",
      "70%",
      form,
      "customizablewindow"
    );
  },
  editControlledDispensingInformation: function (item) {
    var form = Ext.widget("controlledDispensingfrm"),
      btn = item.up("button"),
      record = btn.getWidgetRecord();

    form.loadRecord(record);
    funcShowCustomizableWindow(
      "Edit Dispensing Section 38(2)",
      "55%",
      form,
      "customizablewindow"
    );
  },
  saveAnnualWorkplanDetails: function (btn) {
    var me = this,
      url = btn.action_url,
      table_name = btn.table_name,
      form = btn.up("form"),
      storeID = btn.storeID,
      win = form.up("window"),
      store = Ext.getStore(storeID),
      frm = form.getForm();
    if (frm.isValid()) {
      frm.submit({
        url: url,
        params: {
          table_name: table_name,
          _token: token,
        },
        waitMsg: "Please wait...",
        headers: {
          Authorization: "Bearer " + access_token,
        },
        success: function (fm, action) {
          var response = Ext.decode(action.response.responseText),
            success = response.success,
            message = response.message;
          if (success == true || success === true) {
            if (store) {
              store.removeAll();
              store.load();
            }
            win.close();
            toastr.success(message, "Success Response");
          } else {
            toastr.error(message, "Failure Response");
          }
        },
        failure: function (form, action) {
          var resp = action.result;
          toastr.error(resp.message, "Failure Response");
        },
      });
    }
  },
  editSuspectedOffenceDetails: function (item) {
    var form = Ext.widget("suspectedoffenceform"),
      btn = item.up("button"),
      record = btn.getWidgetRecord();

    form.loadRecord(record);
    funcShowCustomizableWindow(
      "Edit Suspected Offence Details",
      "55%",
      form,
      "customizablewindow"
    );
  },
  // showReceivingApplicationSubmissionWin: function (btn) {

  //     this.fireEvent('showReceivingApplicationSubmissionWin', btn);
  // },
  saveEnforcementReceivingDetails: function (btn) {
    var wizard = btn.wizardpnl,
      wizardPnl = btn.up(wizard),
      action_url = btn.action_url,
      mainTabPnl = btn.up("#contentPanel"),
      containerPnl = mainTabPnl.getActiveTab(),
      process_id = containerPnl.down("hiddenfield[name=process_id]").getValue(),
      module_id = containerPnl.down("hiddenfield[name=module_id]").getValue(),
      sub_module_id = containerPnl
        .down("hiddenfield[name=sub_module_id]")
        .getValue(),
      section_id = containerPnl.down("hiddenfield[name=section_id]").getValue(),
      workflow_stage_id = containerPnl
        .down("hiddenfield[name=workflow_stage_id]")
        .getValue(),
      active_application_id = containerPnl
        .down("hiddenfield[name=active_application_id]")
        .getValue(),
      active_application_code = containerPnl
        .down("hiddenfield[name=active_application_code]")
        .getValue(),
      checkapplication_id = containerPnl
        .down("hiddenfield[name=active_application_id]")
        .getValue(),
      enforcement_id = containerPnl
        .down("hiddenfield[name=enforcement_id]")
        .getValue(),
      complainantfrm = containerPnl.down("complainantfrm"),
      report_type_id = complainantfrm
        .down("combo[name=report_type_id]")
        .getValue(),
      suspectinforfrm = containerPnl.down("suspectinforFrm"),
      //suspect_name = suspectinforfrm.down('textfield[name=suspect_name]').getValue(),
      // suspect_address = suspectinforfrm.down('textfield[name=suspect_address]').getValue(),
      // suspect_omang = suspectinforfrm.down('numberfield[name=suspect_omang]').getValue(),
      // suspect_occupation = suspectinforfrm.down('textfield[name=suspect_occupation]').getValue(),
      // place_of_offence = suspectinforfrm.down('textfield[name=place_of_offence]').getValue(),
      // car_reg_no = suspectinforfrm.down('textfield[name=car_reg_no]').getValue(),
      entity_type_id = suspectinforfrm
        .down("combo[name=entity_type_id]")
        .getValue(),
      is_product_registered = suspectinforfrm
        .down("combo[name=is_registered]")
        .getValue(),
      certificate_no = suspectinforfrm
        .down("textfield[name=certificate_no]")
        .getValue(),
      brand_name = suspectinforfrm
        .down("textfield[name=brand_name]")
        .getValue(),
      common_name = suspectinforfrm
        .down("textfield[name=common_name]")
        .getValue(),
      product_section_id = suspectinforfrm
        .down("combo[name=section_id]")
        .getValue(),
      prodclass_category_id = suspectinforfrm
        .down("combo[name=prodclass_category_id]")
        .getValue(),
      batch_number = suspectinforfrm
        .down("textfield[name=batch_number]")
        .getValue(),
      expiry_date = suspectinforfrm
        .down("datefield[name=expiry_date]")
        .getValue(),
      is_facility_registered = suspectinforfrm
        .down("combo[name=is_facility_registered]")
        .getValue(),
      permit_no = suspectinforfrm.down("textfield[name=permit_no]").getValue(),
      premise_name = suspectinforfrm
        .down("textfield[name=premise_name]")
        .getValue(),
      premise_type = suspectinforfrm
        .down("combo[name=premise_type]")
        .getValue(),
      country_id = suspectinforfrm.down("combo[name=country_id]").getValue(),
      region_id = suspectinforfrm.down("combo[name=region_id]").getValue(),
      district_id = suspectinforfrm.down("combo[name=district_id]").getValue(),
      suspect_physical_address = suspectinforfrm
        .down("textfield[name=suspect_physical_address]")
        .getValue(),
      suspect_postal_address = suspectinforfrm
        .down("textfield[name=suspect_postal_address]")
        .getValue(),
      suspect_telephone = suspectinforfrm
        .down("numberfield[name=suspect_telephone]")
        .getValue(),
      other_details = suspectinforfrm
        .down("htmleditor[name=other_details]")
        .getValue(),
      batch_number = suspectinforfrm
        .down("textfield[name=batch_number]")
        .getValue(),
      expiry_date = suspectinforfrm
        .down("datefield[name=expiry_date]")
        .getValue(),
      suspectinfoFrm = suspectinforfrm.getForm();

    if (!report_type_id) {
      toastr.warning("Please fill the complainant form!!", "Warning Response");
      return false;
    }
    if (!suspectinforfrm.isValid()) {
      toastr.warning(
        "Please fill the Suspect Information form!!",
        "Warning Response"
      );
      return false;
    }

    if (complainantfrm.isValid()) {
      complainantfrm.submit({
        url: "enforcement/" + action_url,
        waitMsg: "Please wait...",
        params: {
          _token: token,
          process_id: process_id,
          workflow_stage_id: workflow_stage_id,
          active_application_id: active_application_id,
          report_type_id: report_type_id,
          module_id: module_id,
          sub_module_id: sub_module_id,
          section_id: section_id,
          enforcement_id: enforcement_id,
          // suspect_name:suspect_name,
          // suspect_address:suspect_address,
          // suspect_omang:suspect_omang,
          // suspect_occupation:suspect_occupation,
          // place_of_offence:place_of_offence,
          // car_reg_no:car_reg_no,
          entity_type_id: entity_type_id,
          is_product_registered: is_product_registered,
          certificate_no: certificate_no,
          brand_name: brand_name,
          common_name: common_name,
          product_section_id: product_section_id,
          prodclass_category_id: prodclass_category_id,
          batch_number: batch_number,
          expiry_date: expiry_date,
          is_facility_registered: is_facility_registered,
          permit_no: permit_no,
          premise_name: premise_name,
          premise_type: premise_type,
          country_id: country_id,
          region_id: region_id,
          district_id: district_id,
          suspect_physical_address: suspect_physical_address,
          suspect_postal_address: suspect_postal_address,
          suspect_telephone: suspect_telephone,
          other_details: other_details,
          batch_number: batch_number,
          expiry_date: expiry_date,
        },
        headers: {
          Authorization: "Bearer " + access_token,
        },
        success: function (frm, action) {
          var resp = action.result,
            message = resp.message,
            success = resp.success,
            active_application_id = resp.active_application_id,
            active_application_code = resp.active_application_code,
            enforcement_id = resp.enforcement_id,
            reference_no = resp.reference_no;
          tracking_no = resp.tracking_no;
          if (success == true || success === true) {
            toastr.success(message, "Record Updated Successfully");
            suspectinforfrm
              .down("hiddenfield[name=enforcement_id]")
              .setValue(enforcement_id);

            if (complainantfrm.down("hiddenfield[name=enforcement_id]")) {
              complainantfrm
                .down("hiddenfield[name=enforcement_id]")
                .setValue(enforcement_id);
            }
            if (checkapplication_id == "") {
              containerPnl
                .down("hiddenfield[name=active_application_id]")
                .setValue(active_application_id);
              containerPnl
                .down("hiddenfield[name=active_application_code]")
                .setValue(active_application_code);
              containerPnl
                .down("hiddenfield[name=enforcement_id]")
                .setValue(enforcement_id);
              containerPnl
                .down("displayfield[name=reference_no]")
                .setValue(reference_no);
              containerPnl
                .down("displayfield[name=tracking_no]")
                .setValue(tracking_no);
            }
            // if(sub_module_id == 9){

            //     containerPnl.down('productsvariationrequestsgrid').down('hiddenfield[name=application_code]').setValue(active_application_code);
            //     containerPnl.down('productsvariationrequestsgrid').down('hiddenfield[name=application_id]').setValue(active_application_id);
            // }
            // else if(sub_module_id == 17){
            //     containerPnl.down('productswithdrawalreasonsgrid').down('hiddenfield[name=application_code]').setValue(active_application_code);
            //     containerPnl.down('productswithdrawalreasonsgrid').down('hiddenfield[name=application_id]').setValue(active_application_id);
            // }
            // else if(sub_module_id == 20){
            //     containerPnl.down('productdataappealrequestsgrid').down('hiddenfield[name=application_code]').setValue(active_application_code);
            //     containerPnl.down('productdataappealrequestsgrid').down('hiddenfield[name=application_id]').setValue(active_application_id);
            // }
          } else {
            toastr.error(message, "Failure Response");
          }
        },
        failure: function (frm, action) {
          var resp = action.result;

          message = resp.message;
          console.log(message);
          toastr.error(message, "Failure Response");
        },
      });
    } else {
      toastr.warning(
        "Please fill all the required fields!!",
        "Warning Response"
      );
    }
  },
  saveinvestigationApprovaldetails: function (btn) {
    var me = this,
      url = btn.action_url,
      table = btn.table_name,
      form = btn.up("form"),
      win = form.up("window"),
      storeID = btn.storeID,
      store = Ext.getStore(storeID),
      frm = form.getForm();

    if (frm.isValid()) {
      frm.submit({
        url: url,
        waitMsg: "Please wait...",
        headers: {
          Authorization: "Bearer " + access_token,
        },
        success: function (form, action) {
          var response = Ext.decode(action.response.responseText),
            success = response.success,
            message = response.message;
          if (success == true || success === true) {
            store.removeAll();
            store.load();
            win.close();
          } else {
            toastr.error(message, "Failure Response");
          }
        },
        failure: function (form, action) {
          var resp = action.result;
          toastr.error(resp.message, "Failure Response");
        },
      });
    }
  },
  deleteRecord: function (item) {
    var me = this,
      btn = item.up("button"),
      record = btn.getWidgetRecord(),
      id = record.get("id"),
      storeID = item.storeID,
      table_name = item.table_name,
      url = item.action_url;

    this.fireEvent(
      "deleteRecord",
      id,
      table_name,
      storeID,
      url,
      "POST",
      record
    );
  },
  showNewInvestigation: function (btn) {
    var application_type = btn.app_type;
    var applist = Ext.widget("investigationApprovedApplicationGrid");
    applist.sub_module_id = btn.app_type;
    funcShowCustomizableWindow(
      "Investigation Case Register",
      "70%",
      applist,
      "customizablewindow",
      btn
    );
  },
  loadEnforcementReportingWizardFromRecord: function (view, record) {
    this.fireEvent("loadEnforcementReportingWizardFromRecord", view, record);
  },
  showMonitoringCompliance: function (btn) {
    var application_type = btn.app_type,
      me = this;
    me.fireEvent("onMonitoringComplianceApplication", application_type, btn);
  },

  loadMonitoringComplianceWizardFromRecord: function (view, record) {
    this.fireEvent("loadMonitoringComplianceWizardFromRecord", view, record);
  },

  showProductInformationForm: function (btn) {
    var panel = Ext.widget("productInformationParticularsPanel"),
      grid = btn.up("grid"),
      application_id = grid.application_id;
    (form = panel.down("productInformationFrm")),
      (btn = form.down("button[name=add_productDeatils]")),
      (store = Ext.getStore("productInformationGridStr"));
    store.removeAll();
    console.log(grid.getStore());
    store.load({ params: { application_id: application_id } });
    funcShowCustomizableWindow(
      "Product Information",
      "75%",
      panel,
      "customizablewindow"
    );
  },
  showEditConfigParamWinFrm: function (item) {
    var me = this,
      btn = item.up("button"),
      record = btn.getWidgetRecord(),
      childXtype = item.childXtype,
      winTitle = item.winTitle,
      winWidth = item.winWidth,
      form = Ext.widget(childXtype),
      storeArray = eval(item.stores),
      arrayLength = storeArray.length;
    if (arrayLength > 0) {
      me.fireEvent("refreshStores", storeArray);
    }
    if (btn.has_params) {
      var param_value = item
        .up("grid")
        .down("hiddenfield[name=" + btn.param_name + "]")
        .getValue();
      child
        .down("hiddenfield[name=" + btn.param_name + "]")
        .setValue(param_value);
    }
    form.loadRecord(record);
    funcShowCustomizableWindow(winTitle, winWidth, form, "customizablewindow");
  },
  showSelectedApplicationInvestigationDiary: function (btn) {
    var button = btn.up("button"),
      //    grid = button.up('grid'),
      //    container = grid.up('panel'),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    panel = Ext.widget("newDairyGrid");
    //    panel = Ext.widget('viewInvestigationdiaryGrid');
    // pnl = grid.up('enforcementinvestigationpnl'),
    //panel.down('hiddenfield[name=active_application_id]').setValue(record.get('active_application_id'));
    panel.down("button[name=add]").setDisabled(true);
    funcShowCustomizableWindow(
      btn.winTitle,
      "70%",
      panel,
      "customizablewindow"
    );
  },
  viewApplicationInvestigationReport: function (item) {
    //if (this.fireEvent('checkFullAccess') || this.fireEvent('checkWriteUpdate')) {
    var me = this,
      btn = item.up("button"),
      record = btn.getWidgetRecord(),
      panel = Ext.widget("investigationReportViewPnl");
    panel.down("hiddenfield[name=report_type]").setValue(1);
    panel
      .down("hiddenfield[name=section_id]")
      .setValue(record.get("section_id"));
    panel.down("hiddenfield[name=module_id]").setValue(record.get("module_id"));
    panel
      .down("hiddenfield[name=sub_module_id]")
      .setValue(record.get("sub_module_id"));
    panel
      .down("hiddenfield[name=process_id]")
      .setValue(record.get("process_id"));
    panel
      .down("hiddenfield[name=workflow_stage_id]")
      .setValue(record.get("workflow_stage_id"));
    panel
      .down("hiddenfield[name=application_code]")
      .setValue(record.get("application_code"));

    funcShowCustomizableWindow(
      item.winTitle,
      "70%",
      panel,
      "customizablewindow"
    );
  },
  showRecommendationWin: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget("recommendationfrm"),
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      stage_category_id = record.get("stage_category_id"),
      module_id = record.get("module_id");

    form.loadRecord(record);
    form.down("hiddenfield[name=application_code]").setValue(application_code);
    form
      .down("hiddenfield[name=stage_category_id]")
      .setValue(stage_category_id);
    form.down("hiddenfield[name=module_id]").setValue(module_id);

    funcShowCustomizableWindow(
      "Recommendation Form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  showMonitoringRecommendationWin: function (btn) {
    var button = btn.up("button"),
      isReadOnly = btn.isReadOnly,
      grid = button.up("grid"),
      form = Ext.widget("monitoringrecommendationfrm"),
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      stage_category_id = record.get("stage_category_id"),
      module_id = record.get("module_id");
    if (isReadOnly) {
      form
        .down("button[name=save_monitoring_Recommendation]")
        .setVisible(false);
    }
    form.loadRecord(record);
    form.down("hiddenfield[name=application_code]").setValue(application_code);
    form
      .down("hiddenfield[name=stage_category_id]")
      .setValue(stage_category_id);
    form.down("hiddenfield[name=module_id]").setValue(module_id);

    funcShowCustomizableWindow(
      "Debriefing Form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  showMonitoringnWin: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget("enforcementDecisionFrm"),
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      stage_category_id = record.get("stage_category_id"),
      module_id = record.get("module_id");

    form.loadRecord(record);
    form.down("hiddenfield[name=application_code]").setValue(application_code);
    form
      .down("hiddenfield[name=stage_category_id]")
      .setValue(stage_category_id);
    form.down("hiddenfield[name=module_id]").setValue(module_id);

    funcShowCustomizableWindow(
      "Recommendation Form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  showEnforcementActionsWin: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget("enforcementActionfrm"),
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      stage_category_id = record.get("stage_category_id"),
      module_id = record.get("module_id");

    form.loadRecord(record);
    form.down("hiddenfield[name=application_code]").setValue(application_code);
    form
      .down("hiddenfield[name=stage_category_id]")
      .setValue(stage_category_id);
    form.down("hiddenfield[name=module_id]").setValue(module_id);

    funcShowCustomizableWindow(
      "Enforcement Actions",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  showReportApplicationMoreDetails: function (btn) {
    this.fireEvent("showEnforcementApplicationMoreDetails", btn);
  },
  showInvestigationDecisionwin: function (btn) {
    var me = this,
      // mainTabPanel = me.getMainTabPanel(),
      // activeTab = mainTabPanel.getActiveTab(),
      //button = btn.up('button'),
      //panel = me.up('panel');
      //onsole.log(panel);
      panel = Ext.widget("enforcementinvestigationpnl");
    active_application_code = panel
      .down("hiddenfield[name=active_application_code]")
      .getValue();
    //application_id=panel.down('hiddenfield[name=active_application_id]').getValue();
    form = Ext.widget(btn.childXtype);
    console.log(active_application_code);
    form
      .down("hiddenfield[name=application_code]")
      .setValue(active_application_code);

    funcShowCustomizableWindow(
      "Investigation Decision form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  viewCaseDecisionLogs: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      grid = Ext.widget("caseDecisionsLogGrid"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      module_id = record.get("module_id");
    grid.down("hiddenfield[name=application_code]").setValue(application_code);
    grid.down("hiddenfield[name=module_id]").setValue(module_id);
    funcShowCustomizableWindow(
      "Case Decision",
      "60%",
      grid,
      "customizablewindow",
      btn
    );
  },
  showAddOffenceChargeWinFrm: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      container = grid.up("panel"),
      //orm=Ext.widget('offenceChargesFrm'),
      form = Ext.widget(btn.childXtype);
    (winTitle = btn.winTitle),
      (winWidth = btn.winWidth),
      (record = button.getWidgetRecord()),
      (application_code = record.get("application_code"));
    application_id = record.get("application_id");
    offence_type_id = record.get("id");
    offennce_type = record.get("offennce_type");
    console.log(container);
    //form.down('hiddenfield[name=active_application_code]').setValue(application_code);
    form.down("hiddenfield[name=application_id]").setValue(application_id);
    form.down("hiddenfield[name=offence_type_id]").setValue(offence_type_id);
    form.down("textfield[name=offennce_type]").setValue(offennce_type);
    funcShowCustomizableWindow(
      winTitle,
      winWidth,
      form,
      "customizablewindow",
      btn
    );
  },
  showAddOffenceChargeWitnessWinFrm: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      container = grid.up("panel"),
      //orm=Ext.widget('offenceChargesFrm'),
      form = Ext.widget(btn.childXtype);
    (winTitle = btn.winTitle),
      (winWidth = btn.winWidth),
      (record = button.getWidgetRecord()),
      (application_code = record.get("application_code"));
    application_id = record.get("application_id");
    offence_id = record.get("id");
    offennce_type = record.get("offennce_type");
    console.log(container);
    console.log(offence_id);
    //form.down('hiddenfield[name=active_application_code]').setValue(application_code);
    form.down("hiddenfield[name=application_id]").setValue(application_id);
    form.down("hiddenfield[name=offence_id]").setValue(offence_id);
    form.down("textfield[name=offennce_type]").setValue(offennce_type);
    funcShowCustomizableWindow(
      winTitle,
      winWidth,
      form,
      "customizablewindow",
      btn
    );
  },
  showAddConfigChargeWinFrm: function (btn) {
    var button = btn.up("button"),
      //grid = button.up('offenceChargeGrid'),
      container = Ext.widget("enforcementinvestigationpnl");
    console.log(container);
    form = Ext.widget(btn.childXtype);
    (winTitle = btn.winTitle),
      (winWidth = btn.winWidth),
      // application_id = container.down('hiddenfield[name=active_application_id]').getValue();
      //    console.log(application_id);
      //form.down('hiddenfield[name=active_application_code]').setValue(application_code);
      //form.down('hiddenfield[name=application_id]').setValue(application_id);

      funcShowCustomizableWindow(
        winTitle,
        winWidth,
        form,
        "customizablewindow",
        btn
      );
  },

  showSezuireConfirmation: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget(btn.childXtype),
      winTitle = btn.winTitle,
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      product_id = record.get("id");
    form.down("hiddenfield[name=application_code]").setValue(application_code);
    form.down("hiddenfield[name=product_id]").setValue(product_id);
    funcShowCustomizableWindow(
      winTitle,
      "40%",
      form,
      "customizablewindow",
      btn
    );
  },

  MystreyShoppingLog: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      grid = Ext.widget("mystreyShoppingLogGrid"),
      record = button.getWidgetRecord(),
      // application_code = record.get('application_code'),
      module_id = record.get("module_id");
    // grid.down('hiddenfield[name=application_code]').setValue(application_code);
    grid.down("hiddenfield[name=module_id]").setValue(module_id);
    funcShowCustomizableWindow(
      "Approval Decision",
      "60%",
      grid,
      "customizablewindow",
      btn
    );
  },
  showAddOffenceChargeWitnessWinGrid: function (btn) {
    var button = btn.up("button"),
      pnl = Ext.widget(btn.childXtype);
    grid = pnl.down("investigationdiarygrid");
    console.log(grid);
    (winTitle = btn.winTitle),
      (winWidth = btn.winWidth),
      (record = button.getWidgetRecord()),
      (application_code = record.get("application_code"));
    application_id = record.get("application_id");
    offence_id = record.get("id");
    offennce_type = record.get("offennce_type");
    console.log(offence_id);
    //form.down('hiddenfield[name=active_application_code]').setValue(application_code);
    grid.down("displayfield[name=offence_id]").setValue(offence_id);
    pnl.down("hiddenfield[name=application_id]").setValue(application_id);
    pnl.down("displayfield[name=offence_id]").setValue(offence_id);
    pnl.down("hiddenfield[name=offennce_type]").setValue(offennce_type);
    pnl.down("hiddenfield[name=application_code]").setValue(application_code);
    funcShowCustomizableWindow(
      winTitle,
      winWidth,
      pnl,
      "customizablewindow",
      btn
    );
  },
  showAddWitnessFrm: function (btn) {
    var button = btn.up("button"),
      pnl = Ext.widget("diaryPnl");
    console.log(pnl);
    grid = pnl.down("investigationdiarygrid");
    console.log(grid);
    //application_code = grid.down('hiddenfield[name=application_code]').getValue()
    application_id = pnl.down("hiddenfield[name=application_id]").getValue();
    offence_id = grid.down("displayfield[name=offence_id]").getValue();
    offennce_type = pnl.down("hiddenfield[name=offennce_type]").getValue();
    console.log(offence_id);
    console.log(offennce_type);
    form = Ext.widget(btn.childXtype);
    (winTitle = btn.winTitle),
      (winWidth = btn.winWidth),
      (childXtype = btn.childXtype),
      (winTitle = btn.winTitle),
      (winWidth = btn.winWidth),
      form.down("hiddenfield[name=application_id]").setValue(application_id);
    form.down("displayfield[name=offence_id]").setValue(offence_id);
    funcShowCustomizableWindow(winTitle, winWidth, form, "customizablewindow");
  },
  viewSeizureProducts: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      grid = Ext.widget("seizureProductsListGrid"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      module_id = record.get("module_id");
    grid.down("hiddenfield[name=application_code]").setValue(application_code);
    grid.down("hiddenfield[name=module_id]").setValue(module_id);
    funcShowCustomizableWindow(
      "Approval Decision",
      "60%",
      grid,
      "customizablewindow",
      btn
    );
  },
  showAddSezuireProductWin: function (btn) {
    var button = btn.up("button"),
      form = Ext.widget(btn.childXtype),
      winTitle = btn.winTitle;
    funcShowCustomizableWindow(
      winTitle,
      "40%",
      form,
      "customizablewindow",
      btn
    );
  },
  showApplicationProductSeizureReport: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      container = grid.up("panel"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    panel = Ext.widget("viewProductSeizureReportGrid");
    funcShowCustomizableWindow(
      btn.winTitle,
      "70%",
      panel,
      "customizablewindow"
    );
  },
  printColumnGmpApprovalLetter: function (item) {
    var record = item.getWidgetRecord(),
      application_code = record.get("application_code"),
      section_id = record.get("section_id");
    module_id = record.get("module_id");
    sub_module_id = record.get("sub_module_id");
    previewCorrespondence(
      application_code,
      module_id,
      "correspodence",
      JSON.stringify({ sub_module_id: sub_module_id })
    );
    // this.fireEvent('generateGmpApprovalLetter', application_code, section_id);
  },
  showApplicationExhibitReport: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      container = grid.up("panel"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    panel = Ext.widget("exhibitReportGrid");
    funcShowCustomizableWindow(
      btn.winTitle,
      "70%",
      panel,
      "customizablewindow"
    );
  },
  showCaseRegister: function (btn) {
    var application_type = btn.app_type,
      wrapper_xtype = btn.wrapper_xtype;
    this.fireEvent("showCaseRegister", application_type, wrapper_xtype);
  },
  showMonitoringComplianceRegister: function (btn) {
    var application_type = btn.app_type,
      wrapper_xtype = btn.wrapper_xtype;
    this.fireEvent(
      "showMonitoringComplianceRegister",
      application_type,
      wrapper_xtype
    );
  },
  showMonitoringEnforcementAction: function (btn) {
    var application_type = btn.app_type,
      wrapper_xtype = btn.wrapper_xtype;
    this.fireEvent(
      "showMonitoringEnforcementAction",
      application_type,
      wrapper_xtype
    );
  },
  showJointOperationsRegister: function (btn) {
    var application_type = btn.app_type,
      wrapper_xtype = btn.wrapper_xtype;
    this.fireEvent(
      "showJointOperationsRegister",
      application_type,
      wrapper_xtype
    );
  },
  reloadParentGridOnChange: function (combo) {
    var grid = combo.up("grid"),
      store = grid.getStore();
    store.load();
  },
  showSelectedReportDetails: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    module_id = record.get("module_id");
    sub_module_id = record.get("sub_module_id");
    section_id = record.get("section_id");
    ref_no = record.get("tracking_no");
    active_application_id = record.get("active_application_id");
    mainTabPnl = btn.up("#contentPanel");
    containerPnl = mainTabPnl.getActiveTab();
    containerPnl
      .down("hiddenfield[name=active_application_id]")
      .setValue(active_application_id);
    this.fireEvent(
      "showSelectedReportDetails",
      btn,
      application_code,
      module_id,
      sub_module_id,
      section_id,
      ref_no
    );
  },
  showManagerApprovalWin: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget("managerApprovalFrm"),
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      stage_category_id = record.get("stage_category_id"),
      module_id = record.get("module_id");
    form.loadRecord(record);
    form.down("hiddenfield[name=application_code]").setValue(application_code);
    form.down("hiddenfield[name=module_id]").setValue(module_id);

    funcShowCustomizableWindow(
      "Approval Form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  showDecisionReportUploadWin: function (btn) {
    var button = btn.up("button"),
      pnl = Ext.widget(btn.childXtype);
    (winTitle = btn.winTitle),
      (winWidth = btn.winWidth),
      (record = button.getWidgetRecord()),
      (application_code = record.get("application_code"));
    pnl.down("hiddenfield[name=application_code]").setValue(application_code);
    funcShowCustomizableWindow(
      winTitle,
      winWidth,
      pnl,
      "customizablewindow",
      btn
    );
  },

  showAddExternalParticipant: function (btn) {
    var me = this,
      grid = btn.up("grid"),
      // meeting_id = grid.down('hiddenfield[name=meeting_id]').getValue(),
      childXtype = btn.childXtype,
      winTitle = btn.winTitle,
      winWidth = btn.winWidth,
      storeArray = eval(btn.stores),
      arrayLength = storeArray.length,
      childObject;
    // if (!meeting_id) {
    //     toastr.warning('Please save meeting details first!!', 'Warning Response');
    //     return false;
    // }
    childObject = Ext.widget(childXtype);
    // childObject.down('hiddenfield[name=meeting_id]').setValue(meeting_id);
    if (arrayLength > 0) {
      me.fireEvent("refreshStores", storeArray);
    }
    funcShowCustomizableWindow(
      winTitle,
      winWidth,
      childObject,
      "customizablewindow"
    );
  },
  doCreateCommonParamWin: function (btn) {
    var me = this,
      url = btn.action_url,
      table = btn.table_name,
      form = btn.up("form"),
      win = form.up("window"),
      storeID = btn.storeID,
      store = Ext.getStore(storeID),
      frm = form.getForm();
    if (frm.isValid()) {
      frm.submit({
        url: url,
        params: { model: table },
        waitMsg: "Please wait...",
        headers: {
          Authorization: "Bearer " + access_token,
        },
        success: function (form, action) {
          var response = Ext.decode(action.response.responseText),
            success = response.success,
            message = response.message;
          if (success == true || success === true) {
            toastr.success(message, "Success Response");
            store.removeAll();
            store.load();
            win.close();
          } else {
            toastr.error(message, "Failure Response");
          }
        },
        failure: function (form, action) {
          var resp = action.result;
          toastr.error(resp.message, "Failure Response");
        },
      });
    }
  },
  showJointDetectedOffences: function (btn) {
    var button = btn.up("button"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    panel = Ext.widget("jointOperationOffenceGrid");
    panel.down("button[name=add]").setDisabled(true);
    funcShowCustomizableWindow(
      btn.winTitle,
      "70%",
      panel,
      "customizablewindow"
    );
  },
  showJointSeizedProducts: function (btn) {
    var button = btn.up("button"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    panel = Ext.widget("jointOperationProductGrid");
    panel.down("button[name=add_product]").setDisabled(true);
    funcShowCustomizableWindow(
      btn.winTitle,
      "70%",
      panel,
      "customizablewindow"
    );
  },
  showJointSummary: function (btn) {
    var button = btn.up("button"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    panel = Ext.widget("summaryGrid");
    panel.down("button[name=add_summary]").setDisabled(true);
    funcShowCustomizableWindow(
      btn.winTitle,
      "70%",
      panel,
      "customizablewindow"
    );
  },
  showJointPlanMoreDetails: function (btn) {
    this.fireEvent("showApplicationMoreDetails", btn);
  },
  showJointOperationRegisterMoreDetails: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    console.log(application_code);
    module_id = record.get("module_id");
    sub_module_id = record.get("sub_module_id");
    section_id = record.get("section_id");
    ref_no = record.get("tracking_no");
    mainTabPnl = btn.up("#contentPanel");
    containerPnl = mainTabPnl.getActiveTab();
    containerPnl
      .down("hiddenfield[name=active_application_code]")
      .setValue(application_code);
    this.fireEvent(
      "showJointOperationRegisterMoreDetails",
      btn,
      application_code,
      module_id,
      sub_module_id,
      section_id,
      ref_no
    );
  },

  signPlan: function (item) {
    this.fireEvent("signPlanDetails", item);
  },
  addAuthSignature: function (btn) {
    this.fireEvent("addAuthSignature", btn);
  },

  showJointRecommendationWin: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget("jointMeetingRecommendationFrm"),
      frm = form.getForm(),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code"),
      stage_category_id = record.get("stage_category_id"),
      module_id = record.get("module_id");
    form.loadRecord(record);
    form.down("hiddenfield[name=application_code]").setValue(application_code);
    form
      .down("hiddenfield[name=stage_category_id]")
      .setValue(stage_category_id);
    form.down("hiddenfield[name=module_id]").setValue(module_id);
    funcShowCustomizableWindow(
      "Recommendation Form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },
  showMonitoringRegisterMoreDetails: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      record = button.getWidgetRecord(),
      application_code = record.get("application_code");
    console.log(application_code);
    module_id = record.get("module_id");
    sub_module_id = record.get("sub_module_id");
    section_id = record.get("section_id");
    application_id = record.get("application_id");
    process_id = record.get("process_id");
    workflow_stage_id = record.get("workflow_stage_id");
    ref_no = record.get("tracking_no");
    view = "monitoringDetailsPnl";
    (isReadOnly = button.isReadOnly), (mainTabPnl = btn.up("#contentPanel"));
    containerPnl = mainTabPnl.getActiveTab();
    containerPnl.down("hiddenfield[name=module_id]").setValue(module_id);
    containerPnl
      .down("hiddenfield[name=sub_module_id]")
      .setValue(sub_module_id);
    containerPnl
      .down("hiddenfield[name=active_application_id]")
      .setValue(application_id);
    containerPnl
      .down("hiddenfield[name=active_application_code]")
      .setValue(application_code);
    containerPnl.down("hiddenfield[name=process_id]").setValue(process_id);
    containerPnl
      .down("hiddenfield[name=workflow_stage_id]")
      .setValue(workflow_stage_id);
    this.fireEvent(
      "showMonitoringRegisterMoreDetails",
      application_code,
      view,
      module_id,
      sub_module_id,
      section_id,
      isReadOnly,
      ref_no
    );
  },
  showRegionFacilitiesWin: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      form = Ext.widget("workPlanRegionsFrm"),
      record = button.getWidgetRecord(),
      monitoring_plan_id = record.get("id");
    form.loadRecord(record);
    form
      .down("hiddenfield[name=monitoring_plan_id]")
      .setValue(monitoring_plan_id);
    funcShowCustomizableWindow(
      "Regions and Facilities Form",
      "50%",
      form,
      "customizablewindow",
      btn
    );
  },

  doCreateSurveillanceParamWin: function (btn) {
    var me = this,
      url = btn.action_url,
      table = btn.table_name,
      form = btn.up("form"),
      tabpanel = btn.up("tabpanel"),
      win = form.up("window"),
      storeID = btn.storeID,
      store = Ext.getStore(storeID),
      frm = form.getForm();
    if (frm.isValid()) {
      frm.submit({
        url: url,
        params: { model: table },
        waitMsg: "Please wait...",
        headers: {
          Authorization: "Bearer " + access_token,
        },
        success: function (form, action) {
          var response = Ext.decode(action.response.responseText),
            success = response.success,
            record_id = response.record_id,
            message = response.message;
          if (success == true || success === true) {
            toastr.success(message, "Success Response");
            store.removeAll();
            store.load();

            win.close();
          } else {
            toastr.error(message, "Failure Response");
          }
        },
        failure: function (form, action) {
          var resp = action.result;
          toastr.error(resp.message, "Failure Response");
        },
      });
    }
  },
  showPlanMoreDetails: function (btn) {
    var button = btn.up("button"),
      grid = button.up("grid"),
      childXtype = btn.childXtype,
      winTitle = btn.winTitle,
      childObject = Ext.widget(childXtype);
    (record = button.getWidgetRecord()),
      (monitoring_plan_id = record.get("id"));
    childObject
      .down("hiddenfield[name=monitoring_plan_id]")
      .setValue(monitoring_plan_id);
    funcShowCustomizableWindow(
      winTitle,
      "70%",
      childObject,
      "customizablewindow",
      btn
    );
  },
  printEnforcementCertificate: function (btn) {
    var button = btn.up("button"),
      mainTabPnl = btn.up("#contentPanel");
    containerPnl = mainTabPnl.getActiveTab();
    (application_code = containerPnl
      .down("hiddenfield[name=active_application_code]")
      .getValue()),
      (module_id = containerPnl.down("hiddenfield[name=module_id]").getValue()),
      (sub_module_id = containerPnl
        .down("hiddenfield[name=sub_module_id]")
        .getValue()),
      previewCorrespondence(
        application_code,
        module_id,
        "correspodence",
        JSON.stringify({ sub_module_id: sub_module_id })
      );
  },
});
