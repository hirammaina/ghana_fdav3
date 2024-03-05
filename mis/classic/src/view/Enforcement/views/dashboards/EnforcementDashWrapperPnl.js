Ext.define('Admin.view.mir.views.dashboards.EnforcementDashWrapperPnl', {
    extend: 'Ext.Container',
    xtype: 'enforcementDashWrapper',
	itemId:'enforcementDashWrapper',
    layout: 'fit',
    items: [
        {
            xtype: 'enforcementdashPnl'
        }
    ]
});