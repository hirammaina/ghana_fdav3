Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.ControlledDispensingPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'controlledPrescribingtabPnl',
    controller: 'enforcementvctr',
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
        }
    ],
    items: [
        {
            title: 'Dispensing Section 38(2)',
            xtype: 'controlledDispensingDataGrid'
        }
    ]
});