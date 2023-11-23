
Ext.define('Admin.view.frontoffice.premise.panels.PremisesFrontOfficeUpdateTabPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'premisesfrontofficeupdatetabpnl',
    itemId:'premisesfrontofficeupdatetabpnl',
    items: [
        {
            title: 'Main Details',
            xtype: 'premisupdatefrontofficedetailsfrm'
        }
        ,
        {
            title: 'Personnel Details',
            xtype: 'pfpremisepersonneltabpnl',
            scrollable: true
        } ,
        {
             title: 'Business Details',
             xtype: 'premisefrontOfficeotherdetailsgrid'
        }
    ]
});