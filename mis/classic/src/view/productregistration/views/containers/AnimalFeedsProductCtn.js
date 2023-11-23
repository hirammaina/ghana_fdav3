/**
 * created by softclans
 * user robinson odhiambo
 */
Ext.define('Admin.view.productregistration.views.containers.AnimalFeedsProductCtn', {
    extend: 'Ext.Container',
    xtype: 'animalfeedsproductctn',
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
            value: 9
        },
        {
            xtype: 'foodproductRegDashWrapper',
            region: 'center'
        },
        {
            xtype: 'animalfeedsproductRegTb',
            region: 'south'
        }
    ]
});