Ext.define('Admin.view.Enforcement.views.panels.NewReportReceivingWizard', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.newreportreceivingwizard',
    padding: '2 0 2 0',
    requires: [
        'Ext.layout.container.*',
        'Ext.toolbar.Fill'
    ],
    reference: 'wizardpnl',
    itemId: 'wizardpnl',
    layout: 'card',
    //bodyPadding: 3,
    flex: 1,
    autoScroll: true,
    cls: 'wizard three shadow',
    colorScheme: 'soft-blue',
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            // hidden: true,
            ui: 'footer',
            height: 60,
            defaults: {
                labelAlign: 'top',
                margin: '-12 5 0 5',
                labelStyle: "color:#595959;font-size:13px"
            },//drugproductdocuploadsgrid
            items: ['->', {
                xtype: 'displayfield',
                name: 'process_name',
                fieldLabel: 'Process',
                fieldStyle: {
                    'color': 'green',
                    'font-weight': 'bold',
                    'font-size': '12px'
                }
            }, {
                    xtype: 'tbseparator',
                    width: 20
                }, {
                    xtype: 'displayfield',
                    name: 'workflow_stage',
                    fieldLabel: 'Workflow Stage',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                }, {
                    xtype: 'tbseparator',
                    width: 20
                }, {
                    xtype: 'displayfield',
                    name: 'application_status',
                    fieldLabel: 'App Status',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                }, {
                    xtype: 'tbseparator',
                    width: 20
                },{
                    xtype: 'displayfield',
                    name: 'tracking_no',
                    fieldLabel: 'Tracking No',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                },{
                    xtype: 'tbseparator',
                    width: 20
                },{
                    xtype: 'displayfield',
                    name: 'reference_no',
                    fieldLabel: 'Ref No',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                }, {
                    xtype: 'hiddenfield',
                    name: 'process_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'workflow_stage_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'active_application_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'active_application_code'
                }, {
                    xtype: 'hiddenfield',
                    name: 'application_status_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'module_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'sub_module_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'section_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'status_type_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'report_type_id'
                },{
                    xtype: 'hiddenfield',
                    name: 'enforcement_id'
                }
            ]
        }
    ],
    items: [
        {
            xtype: 'tabpanel',
            layout: 'fit',
            defaults: {
                margin: 3
            },
            items: [ 
                {
                    xtype: 'complainantfrm',
                    title: 'REPORTED BY',
                },
           ]
        },
        {
            xtype: 'suspectinformationtabPnl',
        },
        {
            xtype: 'tabpanel',
            items: [{
                xtype: 'enforcementDocUploadsGrid',
                title: 'UPLOAD DOCUMENTS'
            }]
        },
        {
            xtype: 'tabpanel',
            items: [{
                xtype: 'productscreeninggrid',
                title: 'PRECHECKING'
            }]
        },
      
    ],
    initComponent: function () {
        var me = this;
        this.tbar = {
            reference: 'progress',
            itemId: 'progress_tbar',
            defaultButtonUI: 'wizard-blue',
            cls: 'wizardprogressbar',
            style: {
                "color": "#90c258"
            },
            bodyStyle: {
                "background-color": "#90c258"
            },
            layout: {
                pack: 'center'
            },
            items: [
                {
                    step: 0,
                    iconCls: 'fa fa-user',
                    enableToggle: true,
                    pressed: true,
                    text: 'REPORT /REFARRAL INFORMATION',
                    action: 'quickNav',
                    wizard:'newreportreceivingwizard',
                    handler: 'quickNavigation'
                },
                {
                    step: 1,
                    iconCls: 'fa fa-upload',
                    enableToggle: true,
                    text: 'SUSPECT',
                    action: 'quickNav', 
                    wizard:'newreportreceivingwizard',
                    handler: 'quickNavigation'
                },
                {
                    step: 2,
                    iconCls: 'fa fa-upload',
                    enableToggle: true,
                    text: 'UPLOAD DOCUMENTS',
                    action: 'quickNav', 
                    wizard:'newreportreceivingwizard',
                    handler: 'quickNavigation'
                },
                {
                    step: 3,
                    iconCls: 'fa fa-upload',
                    enableToggle: true,
                    text: 'DETAILS PRECHECKING',
                    action: 'quickNav', 
                    wizard:'newreportreceivingwizard',
                    handler: 'quickNavigation'
                }
            ]
        };
        this.bbar = {
            reference: 'navigation-toolbar',
            ui: 'footer',
            items: [
                {
                    text: 'Back to List',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-bars',
                    name: 'back_to_list',
                    hidden: true
                },
                '->',
                {
                    text: 'Previous',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-arrow-left',
                    bind: {
                        disabled: '{atBeginning}'
                    },
                    wizard:'newreportreceivingwizard',
                    handler: 'onPrevCardClick'
                },
                {
                    text: 'Save Application Details',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-save',
                    name: 'save_btn',
                    bind: {
                        disabled: '{isReadOnly}'
                    },
                    action_url: 'saveNewEnforcementReceivingDetails',
                    handler:'saveEnforcementReceivingDetails',
                    wizard: 'newreportreceivingwizard'
                },
                {
                    text: 'Submit Report',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-check',
                    name: 'process_submission_btn',
                    // storeID: 'productregistrationstr',
                    table_name: 'tra_enforcement_applications',
                    winWidth: '50%',
                    wizard:'newreportreceivingwizard',
                    // handler: 'showReceivingApplicationSubmissionWin'
                },
                {
                    text: 'Next',
                    ui: 'soft-blue',
                    reference: 'nextbutton',
                    iconCls: 'fa fa-arrow-right',
                    iconAlign: 'right',
                    bind: {
                        disabled: '{atEnd}'
                    },wizard:'newreportreceivingwizard',
                    handler: 'onNextCardClick'
                }
            ]
        };
        me.callParent(arguments);
    }
});
