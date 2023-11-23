/**
 * Created by softclans
 */
Ext.define('Admin.view.productregistration.views.sharedinterfaces.panels.common_panels.OnlineProductsRetentionsDetailsPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'onlineproductsretentionsdetailspnl',
    layout: {//
        type: 'fit'
    },autoScroll: true,
    defaults:{
        margin: 3
    },viewModel: {
        type: 'productregistrationvm'
    },
   
    items: [{
        xtype: 'retentionApplicationsDetailsFrm',
        autoScroll: true,
        title: 'Marketing Authorisation retention Application'
    },{
        xtype: 'retentionProductsDetailsgrid',
        title: 'Retention Products Details'
        
    }, {
        xtype: 'hiddenfield',
        name: 'product_id'
    }, {
        xtype: 'hiddenfield',
        name: '_token',
        value: token
    }]
});