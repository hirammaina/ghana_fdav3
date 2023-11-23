/**
 * Created by Softclans on 10/18/2018.
 */
Ext.define('Admin.view.productregistration.views.maininterfaces.LegacyProcessProductApproval', {
    extend: 'Ext.panel.Panel',
    xtype: 'legacyprocessproductapproval',
    controller: 'productregistrationvctr',
    viewModel: 'productregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'legacyprocessproductapprovalpnl',
            itemId: 'main_processpanel',
            productdetails_panel: 'drugsProductsDetailsPanel',
        }
    ]
});

