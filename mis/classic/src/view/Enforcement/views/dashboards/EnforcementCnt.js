Ext.define('Admin.view.Enforcement.views.dashboards.EnforcementCnt', {
    extend: 'Ext.Container',
    xtype: 'enforcementCnt',
    controller: 'enforcementvctr',
    layout: 'border',
    items: [
        {
            xtype: 'hiddenfield',
            name: 'module_id',
            value: 30
        },
        {
            xtype: 'enforcementDashWrapper',
            region: 'center'
        },
        {
            xtype: 'enforcementb',
            region: 'south'
        }
    ]
});