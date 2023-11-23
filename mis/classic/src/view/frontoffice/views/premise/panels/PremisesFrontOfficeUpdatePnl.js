
Ext.define('Admin.view.frontoffice.premise.panels.PremisesFrontOfficeUpdatePnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'premisesfrontofficeupdatepnl',
    itemId: 'premisesfrontofficeupdatepnl',
    controller: 'spreadsheetpremisectr',
    viewModel: 'premisefrontofficevm',
    layout: 'fit',
    items:[ {
            xtype: 'hiddenfield',
            name: 'application_code'
        },{
            xtype: 'hiddenfield',
            name: 'premise_id'
        },{
            xtype: 'hiddenfield',
            name: 'application_status_id'
        },{
            xtype: 'hiddenfield',
            name: 'isPreview'
        },{
        xtype:'premisesfrontofficeupdatewizzardpnl',
        viewModel: 'premisefrontofficevm',
    }]
});

