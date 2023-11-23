/**
 * Created by Softclans on 10/14/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.ProductRetentionFeesProductPayments', {
    extend: 'Ext.panel.Panel',
    xtype: 'productretentionfeesproductpayments',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'productpaymentpnl',
            itemId:'main_processpanel',
            productdetails_panel: 'retentionapplicationdetailspanel',
        }
    ]
});