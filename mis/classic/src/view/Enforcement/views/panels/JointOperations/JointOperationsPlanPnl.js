Ext.define('Admin.view.Enforcement.views.panels.JointOperations.JointOperationsPlanPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'jointOperationsPlanPnl',
    controller: 'enforcementvctr',
    viewModel: 'enforcementvm',
    layout: 'fit',
    requires: [
        'Ext.layout.container.*',
        'Ext.toolbar.Fill'
    ],
    height: Ext.Element.getViewportHeight() - 118,
    reference: 'wizardpnl',
    itemId: 'wizardpnl',
    //layout: 'card',
    //bodyPadding: 3,
    flex: 1,
    autoScroll: true,
    cls: 'wizard three shadow',
    colorScheme: 'soft-blue',
    items: [
        {
            xtype: 'tabpanel',
            layout: 'fit',
            defaults: {
                margin: 3
            },
            items: [ 
                {
                    xtype: 'jointOperationsPlansGrid',
                    title: 'Joint Operations Approved Work plan Detail',
                },
           ]
        },

    ]
});
