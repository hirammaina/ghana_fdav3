/**
 * Created by Kip on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.withdrawal.WithdrawalFoodProductsReceiving', {
    extend: 'Ext.panel.Panel',
    xtype: 'withdrawalfoodproductsreceiving',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [{
        xtype: 'foodprodwithdrawalreceivingwizard'
    }]
});