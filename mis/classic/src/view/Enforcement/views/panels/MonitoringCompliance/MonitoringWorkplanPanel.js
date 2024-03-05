 Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.MonitoringWorkplanPanel', {
    extend: 'Ext.panel.Panel',
    xtype: 'monitoringWorkplanpnl',
    requires: [
        'Ext.layout.container.*',
        'Ext.toolbar.Fill'
    ],
    height: Ext.Element.getViewportHeight() - 118,
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
            xtype: 'tabpanel',
            layout: 'fit',
            defaults: {
                margin: 3
            },
            items: [ 
                //monitoringworkplangrid
                {
                    xtype: 'monitoringworkplangrid',
                    title: 'Work plan Detail',
                },
                // {
                //     title:'Assessment Checklist',
                //     xtype: 'clinicalTrialAssessmentFrm'
                // }
           ]
        },

    ]
});
