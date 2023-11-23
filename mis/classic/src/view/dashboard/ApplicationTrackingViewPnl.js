Ext.define('Admin.view.dashboard.ApplicationTrackingViewPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'applicationtrackingviewpnl',
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
    layout: 'border',
    items:[{
        xtype: 'trackingsummaryintraygrid',
        title: 'Summary of Assigned or Active Applications ',
        region: 'west',
        width: 600,
        autoScroll: true,
        split: true,
        titleCollapse: true,
        collapsed: false,
        collapsible: true
    }, {
            xtype: 'trackingintraygrid',
            region: 'center',
            title: 'Assigned or Active Applications Pending or Completed Processing',
            userCls: 'big-100 small-100'
    }] 
});
