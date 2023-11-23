/**
 * Created by Kip on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.alteration.AlterationFoodProductsReceiving', {
    extend: 'Ext.panel.Panel',
    xtype: 'alterationfoodproductreceiving',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [{
        xtype: 'alterationfoodproductsreceivingwizard'
    }]
});