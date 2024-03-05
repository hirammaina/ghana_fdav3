Ext.define('Admin.view.Enforcement.views.panels.Investigation.InvestigationReportViewPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'investigationReportViewPnl',
    controller: 'enforcementvctr',
    viewModel: 'enforcementvm',
    height: Ext.Element.getViewportHeight() - 118,
    layout: 'fit',
 
    tbar: [{
        xtype: 'hiddenfield',
        name: 'section_id'
    },{
        xtype: 'hiddenfield',
        name: 'module_id'
    },{
        xtype: 'hiddenfield',
        name: 'sub_module_id'
    },{
        xtype: 'hiddenfield',
        name: 'process_id'
    },{
        xtype: 'hiddenfield',
        name: 'workflow_stage_id'
    },
    {
        xtype: 'hiddenfield',
        name: 'premise_type_id'
    },{
        xtype: 'hiddenfield',
        name: 'application_code'
    },
    {
        xtype: 'hiddenfield',
        name: 'active_application_id'
    },
    {
        xtype: 'hiddenfield',
        name: 'joint_operation_id'
    }, 
    {
        xtype: 'hiddenfield',
        name: 'joint_investigation_id'
    }, 
    {
        xtype: 'hiddenfield',
        name: 'report_type' //1 for evaluation report 2 for rc report
    }],
    items: [
        {
            title: 'Uploaded Investigation Report',
            xtype: 'enforcementAuditingUploadsGrid'
        },
        // {
        //     title: 'Checklist Report',
        //     xtype: 'productCheckReportGrid'
        // }
    ]

    
});