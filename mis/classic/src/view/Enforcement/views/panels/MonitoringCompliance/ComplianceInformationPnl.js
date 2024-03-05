Ext.define('Admin.view.Enforcement.views.panels.ComplianceInformationPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'complianceinformationtabPnl',
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
        }
    ],
    items: [
        {
            title: 'RESPONSIBLE PROFESSIONAL',
            xtype: 'monitoringpremisepersonnelgrid'
        },
        {
            title: 'PRODUCT DETAILS',
            xtype: 'productInformationGrid'
        },
    ]
});