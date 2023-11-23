Ext.define('Admin.view.dashboard.Dashboard', {
    extend: 'Ext.panel.Panel',
    xtype: 'dashboard',
    margin: 2,
    itemId:'dashboard',
    reference:'dashboard',
    //id:'dashboard',
    requires: [
        'Ext.ux.layout.ResponsiveColumn'
    ],
    controller: 'dashboard',
    viewModel: {
        type: 'dashboard'
    },
    layout: 'border',
    listeners: {
        hide: 'onHideView'
    }, 
    items: [ {
            xtype: 'tabpanel',
            region: 'center',
            userCls: 'big-100 small-100',
            items: [
                {
                    title: 'In-Tray',
                    xtype: 'intraygrid',
                    height: Ext.Element.getViewportHeight() - 161
                },
                {
                    title: 'Out-Tray',
                    xtype: 'outtraygrid',
                    height: Ext.Element.getViewportHeight() - 161
                },
                {
                    xtype:'controllleddocumentsaccessdashboard',
                    title:'Controlled Documents Dashboard',
                    layout:'fit'
                }
            ]
        }, {
            xtype: 'panel',
            title: 'Notifications & Messages',
            region: 'south',
            height: 250,
            titleCollapse: true,
            collapsed: true,
            collapsible: true
        },
		
    ]
});
