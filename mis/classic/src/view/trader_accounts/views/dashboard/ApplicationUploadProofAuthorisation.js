Ext.define('Admin.view.trader_accounts.views.dashboard.ApplicationUploadProofAuthorisation', {
    extend: 'Ext.container.Container',
    xtype: 'applicationuploadproofauthorisation',
    title: 'Upload Payment Proof Authorisation',
    
    layout: 'responsivecolumn',
    controller: 'traderaccountsvctr',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout: {
        type: 'fit'
    },
    items: [
        {
            xtype: 'applicationuploadproofauthorisationgrid'
        }
    ]
});
