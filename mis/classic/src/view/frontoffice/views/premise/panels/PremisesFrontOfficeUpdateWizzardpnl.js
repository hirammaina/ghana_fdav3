
Ext.define('Admin.view.frontoffice.premise.panels.PremisesFrontOfficeUpdateWizzardpnl', {
    extend: 'Ext.panel.Panel',
    xtype:'premisesfrontofficeupdatewizzardpnl',
    padding: '2 0 2 0',
    height: Ext.Element.getViewportHeight() - 118,
    requires: [
        'Ext.layout.container.*',
        'Ext.toolbar.Fill'
    ],
    viewModel: 'premisefrontofficevm',
    reference: 'wizardpnl',
    itemId: 'premisesfrontofficeupdatewizzardpnl',
    layout: 'card',
    //bodyPadding: 3,
    flex: 1,
    autoScroll: true,
    cls: 'wizard three shadow',
    colorScheme: 'soft-green',
     dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            ui: 'footer',
            defaults: {
                labelAlign: 'top',
                margin: '-12 5 0 5',
                labelStyle: "color:#595959;font-size:13px"
            },
            items: ['->', {
                    xtype: 'hiddenfield',
                    name: 'process_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'tracking_no'
                },{
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
                    name: 'is_manager_query'
                },
                {
                    xtype: 'hiddenfield',
                    name: 'is_updated'
                }
            ]
        }
    ],
    items: [
    {
        xtype: 'premisesfrontofficeupdatetabpnl'

      }, 
      {
        xtype: 'tabpanel',
        layout: 'fit',

        defaults: {
            margin: 3
        },
        items: [{
            xtype: 'frontofficeapplicantdetailsfrm',
            title: 'APPLICANT DETAILS'
        }]
    },{
        xtype: 'tabpanel',
        layout: 'fit',

        defaults: {
            margin: 3
        },
        items: [{
            xtype: 'premisefrontofficeapprovaldetailsfrm',
            title: 'APPROVAL DETAILS'
        }]
    },
    {
        xtype: 'hiddenfield',
        name: 'active_application_id'
    }
    ],
    initComponent: function () {
        var me = this;
        this.tbar = {
            reference: 'progress',
            itemId: 'progress_tbar',
            defaultButtonUI: 'wizard-' + this.colorScheme,
            cls: 'wizardprogressbar',
            style: {
                "background-color": "#90c258"
            },
            bodyStyle: {
                "background-color": "#90c258"
            },
            layout: {
                pack: 'center'
            },
            items: [{
                step: 0,
                iconCls: 'fa fa-university',
                enableToggle: true,
                text: 'Premise Details',
                action: 'quickNav',
                 wizard: 'premisesfrontofficeupdatewizzardpnl',
                handler: 'quickNavigation'
            },
            {
                step: 1,
                iconCls: 'fa fa-user',
                enableToggle: true,
                pressed: true,
                text: 'Applicant  Details',
                action: 'quickNav',
                wizard: 'premisesfrontofficeupdatewizzardpnl',
                handler: 'quickNavigation'
            },
            {
                step: 2,
                iconCls: 'fa fa-check-square-o',
                enableToggle: true,
                pressed: true,
                text: 'Approval Details',
                action: 'quickNav',
                wizard: 'premisesfrontofficeupdatewizzardpnl',
                handler: 'quickNavigation'
            }  ]
        };
        this.bbar = {
            reference: 'navigation-toolbar',
            ui: 'footer',
            items: [
                 {
                    text: 'Previous',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-arrow-left',
                    name: 'previous_btn',
                     bind: {
                         hidden: '{isReadOnly}'
                    },
                    bind: {
                        disabled: '{atBeginning}'
                    },
                    wizard: 'premisesfrontofficeupdatewizzardpnl',
                    handler: 'onPrevCardClick'
                },
                '->',
                {
                    text: 'Update Application Details',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-save',
                    name: 'save_btn',
                     bind: {
                         hidden: '{isReadOnly}'
                   },
                    wizardpnl: 'premisesfrontofficeupdatewizzardpnl',
                    handler: 'updatePremiseApplicationDetails'
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
                    wizard: 'premisesfrontofficeupdatewizzardpnl',
                    handler: 'onNextCardClick'
                } 
             
            ]
        };
        me.callParent(arguments);
    }
});
