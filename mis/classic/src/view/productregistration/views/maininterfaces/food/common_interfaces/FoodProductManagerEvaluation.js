/**
 * Created by Kip on 10/16/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.common_interfaces.FoodProductManagerEvaluation', {
    extend: 'Ext.panel.Panel',
    xtype: 'foodproductmanagerevaluation',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'productmanagerevaluationpnl',
            itemId: 'main_processpanel',
            productdetails_panel: 'foodproductsdetailspanel',
        }
    ]
});