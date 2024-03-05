Ext.define('Admin.view.Enforcement.views.panels.Investigation.InvestigationPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'investigationpnl',
    controller: 'enforcementvctr',
    viewModel: 'enforcementvm',
    layout: 'fit',
 
    items: [
        {
            xtype: 'enforcementinvestigationpnl',
            itemId:'main_processpanel',
        },
        {
            xtype: 'hiddenfield',
            name: 'process_id'
        }, {
            xtype: 'hiddenfield',
            name: 'workflow_stage_id'
        }, {
            xtype: 'hiddenfield',
            name: 'active_application_id'
        }, {
            xtype: 'hiddenfield',
            name: 'active_application_code'
        }, {
            xtype: 'hiddenfield',
            name: 'application_status_id'
        }, {
            xtype: 'hiddenfield',
            name: 'module_id'
        }, {
            xtype: 'hiddenfield',
            name: 'sub_module_id'
        }, {
            xtype: 'hiddenfield',
            name: 'section_id'
        }, {
            xtype: 'hiddenfield',
            name: 'enforcement_id'
        }, {
            xtype: 'hiddenfield',
            name: 'applicant_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'report_type_id'
        }, 
        {
            xtype: 'hiddenfield',
            name: 'application_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'joint_operation_id'
        }, 
        {
            xtype: 'hiddenfield',
            name: 'joint_investigation_id'
        }, 
    ]

    
});