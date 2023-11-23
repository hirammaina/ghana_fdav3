/**
 * created by softclans
 * user robinson odhiambo
 */
Ext.define('Admin.view.productregistration.views.containers.TobaccoRegistrationProductCtn', {
    extend: 'Ext.Container',
    xtype: 'tobaccoregistrationproductctn',
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
            value: 8
        },
        {
            xtype: 'foodproductRegDashWrapper',
            region: 'center'
        },
        {
            xtype: 'tobaccoregistrationproductregtb',
            region: 'south'
        }
    ]
});