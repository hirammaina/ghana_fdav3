/**
 * created by softclans
 * user robinson odhiambo
 */
Ext.define('Admin.view.importexportpermits.views.containers.NarcoticImportPermits', {
    extend: 'Ext.Container',
    xtype: 'narcoticimportpermits',
    controller: 'importexportpermitsvctr',
    layout: 'border',
   
    items: [
        {
            xtype: 'hiddenfield',
            name: 'module_id',
            value: 4
        },
        {
            xtype: 'hiddenfield',
            name: 'section_id',
            value: 2
        },
        {
            xtype: 'narcoticimportpermitsdashwrapper',
            region: 'center'
        },
        {
            xtype: 'narcoticimportpermitstb',
            region: 'south'
        }
    ]
});

