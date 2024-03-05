
 Ext.define('Admin.view.Enforcement.views.panels.invetsigation.DiaryPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'diaryPnl',
    height: Ext.Element.getViewportHeight() - 118,
    controller: 'enforcementvctr',
    frame: true,
    scrollable: true,
    // width: '100%',
    layout: 'fit',
    tbar: [   
         {
        xtype: 'button',
        text: 'Add Potential Charge',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        handler: 'showAddWitnessFrm',
        //action:'addWitnessFrm',
        winTitle: 'Potential Charge Form',
        winWidth:'70%',
        childXtype: 'witnessfrm',
        stores: '[]',
        //hidden:true
    },],
    items: [
        {
            xtype: 'investigationdiarygrid',
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