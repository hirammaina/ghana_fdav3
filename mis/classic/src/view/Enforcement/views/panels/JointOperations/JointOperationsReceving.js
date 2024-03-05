Ext.define('Admin.view.Enforcement.views.panels.JointOperations.JointOperationsReceving', {
    extend: 'Ext.panel.Panel',
    xtype: 'jointOperationreceiving',
    controller: 'enforcementvctr',
    viewModel: 'enforcementvm',
    layout: 'fit',
    
    items: [
        {
            xtype: 'jointOperationsWorkPlan'
        }
    ]
});