/**
 * Created by Kip on 10/14/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.common_interfaces.FoodProductMeeting', {
    extend: 'Ext.panel.Panel',
    xtype: 'foodProductMeeting',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'newProductTcMeetingpnl',
            itemId:'main_processpanel',
            productdetails_panel: 'foodproductsdetailspanel',
        }
    ]
});