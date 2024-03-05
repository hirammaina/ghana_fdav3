Ext.define('Admin.view.Enforcement.views.panels.JointOperations.JointOperationsWorkPlan', {
    extend: 'Ext.panel.Panel',
    alias:'widget.jointOperationsWorkPlan',
    controller: 'enforcementvctr',
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
                },  {
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
                }, {
                    xtype: 'hiddenfield',
                    name: 'reported_by_id'
                },
                {
                    xtype: 'hiddenfield',
                    name: 'enforcement_id'
                },
                {
                    xtype: 'hiddenfield',
                    name: 'joint_operation_id'
                },
                {
                    name: 'planning_id',
                    xtype: 'hiddenfield'
                },
            ]
        }
    ],
    items: [
        {
            xtype: 'tabpanel',
            items: [{
                xtype: 'jointOperationsWorkPlanFrm',
                title: 'PLAN DETAILS'
            }]
        },
        {
            xtype: 'tabpanel',
            items: [{
                xtype: 'jointOperationActivitiesGrid',
                title: 'ACTIVITIES'
            }]
        },
        {
            xtype: 'tabpanel',
            items: [{
                xtype: 'logisticsgrid',
                title: 'LOGISTICS'
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
                    iconCls: 'fa fa-money-check-pen',
                    enableToggle: true,
                    pressed: true,
                    text: 'PLAN DETAILS',
                    action: 'quickNav',
                    wizard:'jointOperationsWorkPlan',
                    handler: 'jointquickNavigation'
                },
                {
                    step: 1,
                    iconCls: 'fa fa-clipboard',
                    enableToggle: true,
                    text: 'ACTIVITY INFORMATION',
                    action: 'quickNav', 
                    wizard:'jointOperationsWorkPlan',
                    handler: 'jointquickNavigation'
                },
                {
                    step: 2,
                    iconCls: 'fa fa-money-bill-wave',
                    enableToggle: true,
                    text: 'LOGISTICS INFORMATION',
                    action: 'quickNav', 
                    wizard:'jointOperationsWorkPlan',
                    handler: 'jointquickNavigation'
                },
                {
                    step: 3,
                    iconCls: 'fa fa-upload',
                    enableToggle: true,
                    text: 'UPLOAD DOCUMENTS',
                    action: 'quickNav', 
                    wizard:'jointOperationsWorkPlan',
                    handler: 'jointquickNavigation'
                }
            ]
        };
        this.bbar = {
            reference: 'navigation-toolbar',
            ui: 'footer',
            items: [
              
                '->',
                {
                    text: 'Previous',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-arrow-left',
                    bind: {
                        disabled: '{atBeginning}'
                    },
                    wizard:'jointOperationsWorkPlan',
                    handler: 'onPrevCardClick'
                },
                {
                    text: 'Sign Plan',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-pencil-square-o',
                    wizard:'jointOperationsWorkPlan',
                    approval_frm: 'signPlanFrm',
                    vwcontroller: 'enforcementvctr',
                    stores: '[]',
                    table_name: 'tra_enforcement_applications',
                    winWidth: '70%',
                    handler: 'signPlan'
                },
                {
                    text: 'Save Application Details',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-save',
                    name: 'save_btn',
                    bind: {
                        disabled: '{isReadOnly}'
                    },
                    wizard: 'jointOperationsWorkPlan'
                },
                {
                    text: 'Submit Application',
                    ui: 'soft-blue',
                    iconCls: 'fa fa-check',
                    name: 'process_submission_btn',
                    table_name: 'tra_enforcement_applications',
                    winWidth: '50%',
                    wizard:'jointOperationsWorkPlan',
                },
                {
                    text: 'Next',
                    ui: 'soft-blue',
                    reference: 'nextbutton',
                    iconCls: 'fa fa-arrow-right',
                    iconAlign: 'right',
                    bind: {
                        disabled: '{atEnd}'
                    },wizard:'jointOperationsWorkPlan',
                    handler: 'onJointNextCardClick'
                }
            ]
        };
        me.callParent(arguments);
    }       
});
