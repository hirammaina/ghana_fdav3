Ext.define('Admin.view.dashboard.Application_EnquiriesPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'application_enquiriespnl',
    margin: 2,
    itemId:'application_enquiriespnl',
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
            xtype:'application_enquiriesGrid',
            title: 'Application Enquiries(Processing Details)'
    },{
            xtype:'applicationtrackingviewpnl',
            title: 'Active Applications Tracking'
    }
    ]
});
