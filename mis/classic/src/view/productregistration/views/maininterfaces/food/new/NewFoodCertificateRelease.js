/**
 * Created by Kip on 10/18/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.food.new.NewFoodCertificateRelease', {
    extend: 'Ext.panel.Panel',
    xtype: 'newmfoodcertificateRelease',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',

    items: [
        {
            xtype: 'productCertificateReleasePnl',
            itemId:'main_processpanel',
            productdetails_panel: 'foodproductsdetailspanel',
        }
    ]
});