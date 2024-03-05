Ext.define('Admin.view.Enforcement.views.panels.MonitoringCasesRegisterPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'monitoringCasesRegisterPnl',
    controller: 'enforcementvctr',
    height: Ext.Element.getViewportHeight() - 118,
    tbar: [
        {
            xtype: 'hiddenfield',
            name: 'section_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'process_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'workflow_stage_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'module_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'sub_module_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'active_application_code'
        },
        {
            xtype: 'hiddenfield',
            name: 'active_application_id'
        }
    ],
    items: [
        {
            xtype: 'monitoringenforcementActionGrid'
        },
    ]
});