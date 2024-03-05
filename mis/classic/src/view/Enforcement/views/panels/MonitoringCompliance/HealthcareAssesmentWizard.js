Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.HealthcareAssesmentWizard', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.healthcareassesmentwizard',
    padding: '2 0 2 0',
    requires: [
        'Ext.layout.container.*',
        'Ext.toolbar.Fill'
    ],
    reference: 'wizardpnl',
    itemId: 'wizardpnl',
    layout: 'card',
    flex: 1,
    autoScroll: true,
    cls: 'wizard three shadow',
    colorScheme: 'soft-blue',
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            ui: 'footer',
            height: 40,
            defaults: {
                labelAlign: 'top',
                margin: '-12 5 0 5',
                labelStyle: "color:#595959;font-size:11px"
            },
            items: ['->', {
                xtype: 'displayfield',
                name: 'process_name',
                fieldLabel: 'Process',
                fieldStyle: {
                    'color': 'green',
                    'font-weight': 'bold',
                    'font-size': '12px',
                    'margin-top': '-2px'
                }
            }, {
                xtype: 'tbseparator'
            }, {
                xtype: 'displayfield',
                name: 'workflow_stage',
                fieldLabel: 'Workflow Stage',
                fieldStyle: {
                    'color': 'green',
                    'font-weight': 'bold',
                    'font-size': '12px',
                    'margin-top': '-2px'
                }
            }, {
                xtype: 'tbseparator',
            }, {
                xtype: 'displayfield',
                name: 'application_status',
                fieldLabel: 'App Status',
                fieldStyle: {
                    'color': 'green',
                    'font-weight': 'bold',
                    'font-size': '12px',
                    'margin-top': '-2px'
                }
            },{
                xtype: 'tbseparator',
            }, {
                xtype: 'displayfield',
                name: 'tracking_no',
                fieldLabel: 'Tracking No',
                fieldStyle: {
                    'color': 'green',
                    'font-weight': 'bold',
                    'font-size': '12px',
                    'margin-top': '-2px'
                }
            }, {
                xtype: 'tbseparator',
            }, {
                xtype: 'displayfield',
                name: 'reference_no',
                fieldLabel: 'Reference No',
                fieldStyle: {
                    'color': 'green',
                    'font-weight': 'bold',
                    'font-size': '12px',
                    'margin-top': '-2px'
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
            },{
                xtype: 'hiddenfield',
                name: 'module_id'
            }, {
                xtype: 'hiddenfield',
                name: 'sub_module_id'
            }, {
                xtype: 'hiddenfield',
                name: 'section_id'
            },{
                xtype: 'hiddenfield',
                name: 'invoice_id'
            },{
                xtype: 'hiddenfield',
                name: 'enforcement_id'
            }
            ]
        }
    ],
    items: [
        {
            xtype: 'prescribinginformationtabPnl',
        },
        {
            xtype: 'healthAssesmentPnl',
        },
        {
            xtype: 'tabpanel',
            items: [{
                xtype: 'productscreeninggrid',
                title: 'PRECHECKING'
            }]
        },
        {
            xtype: 'tabpanel',
            items: [{
                xtype: 'enforcementDocUploadsGrid',
                title: 'UPLOAD DOCUMENTS'
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
                    iconCls: 'fa fa-capsules',
                    enableToggle: true,
                    pressed: true,
                    text: 'PRESCRIBING INFORMATION',
                    action: 'quickNav',
                    wizard:'healthcareassesmentwizard',
                    handler: 'MonitoringquickNavigation'
                },
                {
                    step: 1,
                    iconCls: 'fa fa-clipboard-list',
                    enableToggle: true,
                    text: 'DISPENSING INFORMATION',
                    action: 'quickNav',
                    wizard:'healthcareassesmentwizard',
                    handler: 'MonitoringquickNavigation'
                },
                {
                    step: 2,
                    iconCls: 'fa fa-check-square',
                    enableToggle: true,
                    text: 'COMPLIANCE PRECHECKING',
                    action: 'quickNav', 
                    wizard:'healthcareassesmentwizard',
                    handler: 'MonitoringquickNavigation'
                },
                {
                    step: 3,
                    iconCls: 'fa fa-upload',
                    enableToggle: true,
                    text: 'UPLOAD DOCUMENTS',
                    action: 'quickNav', 
                    wizard:'healthcareassesmentwizard',
                    handler: 'MonitoringquickNavigation'
                },
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
                    wizard:'healthcareassesmentwizard',
                    handler: 'onPrevCardClick'
                },
                {
                    text: 'View Plan Details',
                    iconCls: 'fa fa-eye',
                    name: 'more_app_details',
                    ui: 'soft-blue',
                    handler: 'showMonitoringApplicationMoreDetails'
                },
                {
                    text: 'Submit Application',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-check',
                    name: 'process_submission_btn',
                    table_name: 'tra_enforcement_applications',
                    winWidth: '50%',
                    wizard:'healthcareassesmentwizard',
                },
                {
                    text: 'Next',
                    ui: 'soft-blue',
                    reference: 'nextbutton',
                    iconCls: 'fa fa-arrow-right',
                    iconAlign: 'right',
                    bind: {
                        disabled: '{atEnd}'
                    },wizard:'healthcareassesmentwizard',
                    handler: 'onNextCardClick'
                }
            ]
        };
        me.callParent(arguments);
    }
});