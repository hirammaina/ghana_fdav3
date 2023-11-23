/**
 * Created by Kip on 10/17/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.query_process.FoodManagerQueryProcess', {
    extend: 'Ext.panel.Panel',
    xtype: 'foodmanagerqueryprocess',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'productqueryqpprovalpnl',
            itemId:'main_processpanel',
            productdetails_panel: 'foodproductsdetailspanel',
            productqueriespanel: 'allqueryresponsesgrid'
        }
    ]
});