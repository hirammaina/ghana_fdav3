/**
 * created by softclans
 * user robinson odhiambo
 */
Ext.define('Admin.view.productregistration.views.containers.FoodSupplementProductRegistrationCtn', {
    extend: 'Ext.Container',
    xtype: 'foodSupplementProductRegistrationCtn',
    controller: 'productregistrationvctr',
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
            value: 12
        },
        {
            xtype: 'foodproductRegDashWrapper',
            region: 'center'
        },
        {
            xtype: 'foodProductRegTb',
            region: 'south'
        }
    ]
});