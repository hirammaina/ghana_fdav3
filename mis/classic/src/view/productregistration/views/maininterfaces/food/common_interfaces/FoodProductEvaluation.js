/**
 * Created by Kip on 10/16/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.common_interfaces.FoodProductEvaluation', {
    extend: 'Ext.panel.Panel',
    xtype: 'foodproductevaluation',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'foodevaluationpnl',
            itemId: 'main_processpanel',
            productdetails_panel: 'foodproductsdetailspanel',
        }
    ]
});