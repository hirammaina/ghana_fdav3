/**
 * Created by Softclans on 10/14/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.ProductRetentionProductRegister', {
    extend: 'Ext.panel.Panel',
    xtype: 'productretentionproductregister',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [ {
            xtype: 'productretentionproductregisterwizard',
            itemId:'main_processpanel',
            productdetails_panel: 'retentionapplicationdetailspanel',
        }
    ]
});