Ext.define('Admin.view.configurations.views.panels.ProductNotificationFeeConfigPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'productnotificationfeeconfigpnl',
    title: 'Product Notifications Fee Configurations',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'productnotificationfeeconfiggrid'
        }
    ],

});
