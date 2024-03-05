Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.HealthAssemesmentReceving', {
    extend: 'Ext.panel.Panel',
    xtype: 'heathassesmentreceiving',
    controller: 'enforcementvctr',
    viewModel: 'enforcementvm',
    layout: 'fit',
    
    items: [
        {
            xtype: 'healthcareassesmentwizard'
        }
    ]
});