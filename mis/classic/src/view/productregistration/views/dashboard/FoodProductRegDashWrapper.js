/**
 * Created by Softclans
 * User Robinson Odhiambo
 * on 9/23/2018.
 */
Ext.define('Admin.view.productregistration.views.dashboards.FoodProductRegDashWrapper', {
    extend: 'Ext.Container',
    xtype: 'foodproductRegDashWrapper',
	itemId:'productRegDashWrapper',
    layout: 'fit',
    items: [
        {
            xtype: 'foodproductregdash'
        }
    ]
});