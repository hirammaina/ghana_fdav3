Ext.define('Admin.view.dashboard.IntrayPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'intraypnl',
    margin: 2,
    itemId:'intraypnl',
    reference:'intraypnl',
    //id:'dashboard',
    requires: [
        'Ext.ux.layout.ResponsiveColumn'
    ],
    controller: 'dashboard',
    viewModel: {
        type: 'dashboard'
    },
    listeners: {
        hide: 'onHideView'
    }, 
    layout: 'fit',
    items: [{
            xtype:'tabpanel',
            layout:'fit',
            items:[{
                    xtype: 'panel',
                    title: 'Assignments & Active Applications',
                    layout: 'border',
                    items:[{
                        xtype: 'summaryintraygrid',
                        title: 'Summary of Assigned or Active Applications ',
                        region: 'west',
                        width: 500,
                        autoScroll: true,
                        split: true,
                        titleCollapse: true,
                        collapsed: false,
                        collapsible: true
                    }, {
                            xtype: 'intraygrid',
                            region: 'center',
                            title: 'Assigned or Active Applications Pending or Completed Processing',
                            userCls: 'big-100 small-100'
                    }] 

            },{
                xtype: 'querysubmissionstraytaskgrid',
                title:'Query Submissions'
            },{
                xtype: 'completedtraytaskgrid',
                title:'Approved Applications'
            }]

    }
    ]
});
