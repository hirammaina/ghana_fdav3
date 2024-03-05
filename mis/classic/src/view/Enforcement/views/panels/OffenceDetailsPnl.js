Ext.define('Admin.view.Enforcement.views.panels.OffenceDetailsPnl', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.offenceDetailsPnl',
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
    items: [
        {
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
        },
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
            xtype: 'tabpanel',
            items: [
                {
                    xtype: 'suspectinforFrm',
                    title: 'SUPSECT INFORMATION'
                },
                {
                xtype: 'suspectedoffencegrid',
                title: 'SUSPECTED OFFENCE /S'
             }
        ]
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
            ]
        };
        this.bbar = {
            reference: 'navigation-toolbar',
            ui: 'footer',
            items: [
                {
                    text: 'Previous',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-arrow-left',
                    bind: {
                        disabled: '{atBeginning}'
                    },
                    handler: 'onNextCardClick',
                    wizard:'offenceDetailsPnl',
                },
                '->',
                {
                    text: 'Update Application Details',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-save',
                    name: 'save_btn',
                    toaster: 1
                },
                {
                    text: 'Next',
                    ui: 'soft-purple',
                    reference: 'nextbutton',
                    iconCls: 'fa fa-arrow-right',
                    iconAlign: 'right',
                    bind: {
                        disabled: '{atEnd}'
                    },
                    handler: 'onNextCardClick',
                    wizard:'offenceDetailsPnl',
                }
            ]
        };
        me.callParent(arguments);
    }
});
