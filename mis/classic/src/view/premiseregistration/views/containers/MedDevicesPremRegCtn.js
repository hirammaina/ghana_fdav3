/**
 * Created by Kip on 11/11/2018.
 */
Ext.define('Admin.view.premiseregistration.views.containers.MedDevicesPremRegCtn', {
    extend: 'Ext.Container',
    xtype: 'meddevicespremregctn',
    controller: 'premiseregistrationvctr',
    layout: 'border',
    items: [
        {
            xtype: 'hiddenfield',
            name: 'module_id',
            value: 2
        },
        {
            xtype: 'hiddenfield',
            name: 'section_id',
            value: 4
        },
        {
            xtype: 'meddevicespremregdashwrapper',
            region: 'center'
        },
        {
            xtype: 'meddevicespremregtb',
            region: 'north'
        }
    ]
});