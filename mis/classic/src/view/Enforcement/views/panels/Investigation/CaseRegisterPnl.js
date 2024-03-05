
Ext.define('Admin.view.Enforcement.views.panels.invetsigation.CaseRegisterPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'caseRegisterPnl',
    height: Ext.Element.getViewportHeight() - 118,
    controller: 'enforcementvctr',
    // frame: true,
    // scrollable: true,
    // width: '100%',
    layout: 'fit',
    tbar: [   
       ],
    items: [
        {
            xtype: 'enforcementRegisteredCasesGrid',
			region:'center'
        },
        {
            xtype: 'hiddenfield',
            name: 'enforcement_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'active_application_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'application_id'
        },
        {
            xtype: 'displayfield',
            name: 'offence_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'offennce_type'
        },
        {
            xtype: 'hiddenfield',
            name: 'application_code'
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