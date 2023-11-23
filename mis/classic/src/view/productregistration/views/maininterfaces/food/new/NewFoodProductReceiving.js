/**
 * Created by Kip on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.new.NewFoodProductReceiving', {
    extend: 'Ext.panel.Panel',
    xtype: 'newfoodproductreceiving',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'newfoodproductreceivingwizard'
        }
    ]
});