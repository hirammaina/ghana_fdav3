Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.MonitoringWorkplan', {
    extend: 'Ext.panel.Panel',
    xtype: 'monitoringWorkplan',//premiseNewApproval
    controller: 'enforcementvctr',
    margin: '3 0 0 0',
    viewModel: 'enforcementvm',
    items: [
        {
            xtype: 'hiddenfield',
            name: 'section_id',
            value: 2
        },
        {
            xtype: 'hiddenfield',
            name: 'module_id',
            value: 8
        },
        {
            xtype: 'hiddenfield',
            name: 'active_application_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'active_application_code'
        },
        {
            xtype: 'hiddenfield',
            name: 'workflow_stage_id'
        },
        {
            xtype: 'monitoringWorkplanpnl'
        }
    ]
});