Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.PrescribingInfomrationPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'prescribinginformationtabPnl',
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
            title: 'Product Information',
            xtype: 'productInformationGrid'
        },
        {
            title: 'Prescribing Data',
            xtype: 'prescribingComplianceGrid'
        },
    ]
});