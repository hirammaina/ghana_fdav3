/**
 * Created by Kip on 10/17/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.common_interfaces.FoodProductManagerAuditing', {
    extend: 'Ext.panel.Panel',
    xtype: 'foodproductmanagerauditing',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'productmanagerauditingpnl',
            itemId:'main_processpanel',
            productdetails_panel: 'foodproductsdetailspanel',
        }
    ]
});