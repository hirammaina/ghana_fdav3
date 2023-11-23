/**
 * created by softclans
 * user robinson odhiambo
 */
Ext.define('Admin.view.productregistration.views.containers.MedicaldevicesProductRegistrationCtn', {
    extend: 'Ext.Container',
    xtype: 'medicaldevicesProductRegistrationCtn',
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
            value: 4
        },
        {
            xtype: 'medicaldevicesregdashWrapper',
            region: 'center'
        },
        {
            xtype: 'medicaldevicesProductRegTb',
            region: 'south'
        }
    ]
});