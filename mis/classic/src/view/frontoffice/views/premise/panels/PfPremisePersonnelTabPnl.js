
Ext.define('Admin.view.frontoffice.premise.panels.PfPremisePersonnelTabPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'pfpremisepersonneltabpnl',
    margin: 3,
    items: [
       
        {
            title: 'Premises Technical Personnel',
            xtype: 'premisepfpersonneldetailsgrid',
            scrollable: true
        } ,{
            title: 'Contact Person',
            xtype: 'premisecontactpersonfrm',
            hidden: true,
            scrollable: true
        },
         {
            xtype: 'hiddenfield',
            name: 'premise_id'
        },
    ]
});