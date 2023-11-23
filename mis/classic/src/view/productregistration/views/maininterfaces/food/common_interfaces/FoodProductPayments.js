/**
 * Created by Kip on 10/14/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.common_interfaces.FoodProductPayments', {
    extend: 'Ext.panel.Panel',
    xtype: 'foodproductpayments',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'productpaymentpnl',
            itemId:'main_processpanel',
            productdetails_panel: 'foodproductsdetailspanel',
        }
    ]
});