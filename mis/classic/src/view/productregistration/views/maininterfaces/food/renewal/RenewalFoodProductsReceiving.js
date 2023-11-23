/**
 * Created by Kip on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.renewal.RenewalFoodProductsReceiving', {
    extend: 'Ext.panel.Panel',
    xtype: 'renewalfoodproductsreceiving',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [{
        xtype: 'renewalfoodproductreceivingWizard'
    }]
});