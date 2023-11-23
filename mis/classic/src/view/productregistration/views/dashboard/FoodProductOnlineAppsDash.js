/**
 * Created by Kip on 11/20/2018.
 */
Ext.define('Admin.view.premiseregistration.views.dashboards.FoodProductOnlineAppsDash', {
    extend: 'Ext.Container',
    xtype: 'foodproductonlineappsdash',
    layout: 'border',
    items: [
        {
            xtype: 'hiddenfield',
            name: 'module_id',
            value: 1
        },
        {
            xtype: 'hiddenfield',
            name: 'section_id',
            value: 1
        },
        {
            xtype: 'onlineproductregistrationgrid',
            region: 'center',
            title: 'Online Application Submission',
            wizard_pnl: 'onlinefoodproductreceivingwizard',
            margin: 2
        }, {
            xtype: 'dashboardguidelinesgrid',
            region: 'south',
            collapsible: true,
            collapsed: true,
            titleCollapse: true
        }
    ]
});