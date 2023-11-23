Ext.define('Admin.view.dashboard.UserPerformanceCompleteAssignments', {
    extend: 'Ext.panel.Panel',
    xtype: 'userperformancecompleteassignments',
    margin: 2,
    itemId:'userperformancecompleteassignments',
    reference:'userperformancecompleteassignments',
    //id:'dashboard',
    requires: [
        'Ext.ux.layout.ResponsiveColumn'
    ],
    controller: 'dashboard',
    viewModel: {
        type: 'dashboard'
    },
    layout: 'fit',
    listeners: {
        hide: 'onHideView'
    }, 
    items: [{
            xtype:'tabpanel',
            layout: 'fit',
            items:[{
                xtype:'usercompletedassperformancsummarygrid',
                title: 'User Performance Summary Report'
            },{
                xtype: 'usertraycompletedtasksgrid',
                region: 'center',
                title: 'My Completed Assignment'
            }]

    }
    ]
});
