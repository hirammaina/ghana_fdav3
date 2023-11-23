/**
 * Created by Kip on 10/18/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.common_interfaces.NewFoodProductApproval', {
    extend: 'Ext.panel.Panel',
    xtype: 'newfoodproductapproval',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',

    items: [
        {
            xtype: 'newProductApprovalPnl',
            itemId:'main_processpanel',
            productdetails_panel: 'foodproductsdetailspanel',
        }
    ]
});