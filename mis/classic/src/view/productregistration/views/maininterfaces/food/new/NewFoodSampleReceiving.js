/**
 * Created by Kip on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.NewFoodSampleReceiving', {
    extend: 'Ext.form.Panel',
    xtype: 'newfoodproductsamplereceiving',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items:[{
        xtype:'foodproductsamplereceivingpnl',
        viewModel: 'productregistrationvm'
    }]
});