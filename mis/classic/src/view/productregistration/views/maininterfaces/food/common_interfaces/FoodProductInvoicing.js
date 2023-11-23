/**
 * Created by Kip on 10/12/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.common_interfaces.FoodProductInvoicing', {
    extend: 'Ext.panel.Panel',
    xtype: 'foodproductinvoicing',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [{
        xtype: 'productInvoicingPnl',
        itemId: 'main_processpanel',
        productdetails_panel: 'foodproductsdetailspanel',
    }]
});