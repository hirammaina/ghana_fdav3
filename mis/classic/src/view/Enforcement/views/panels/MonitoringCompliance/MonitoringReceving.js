Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.MonitoringReceving', {
    extend: 'Ext.panel.Panel',
    xtype: 'monitoringreceiving',
    controller: 'enforcementvctr',
    viewModel: 'enforcementvm',
    layout: 'fit',
    
    items: [
        {
            xtype: 'monitoringreceivingwizard'
        }
    ]
});