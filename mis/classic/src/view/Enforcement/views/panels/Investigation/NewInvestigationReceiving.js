Ext.define('Admin.view.Enforcement.views.panels.investigation.NewInvestigationReceiving', {
    extend: 'Ext.panel.Panel',
    xtype: 'newinvestigationreceiving',
    controller: 'enforcementvctr',
    viewModel: 'enforcementvm',
    layout: 'fit',
    
    items: [
        {
            xtype: 'newinvestigationreceivingwizard'
        }
    ]
});