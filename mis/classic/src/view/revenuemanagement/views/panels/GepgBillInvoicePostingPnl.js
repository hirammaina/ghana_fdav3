
/**
 * Created by Kip on 10/17/2018.
 */
Ext.define('Admin.view.revenuemanagement.views.panels.GepgBillInvoicePostingPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'gepgbillinvoicepostingpnl',
    layout: 'fit',
    items: [
        {
            xtype: 'gepgbillpaymentspostinggrid',
            title: 'Payments Remittances'
        },{
            xtype: 'postpaymentbillpostinggrid',
            title: 'Post Payment Requests'
        },{
            xtype: 'gepgbillinvoicepostinggrid',
            title: 'Bills/Invoicing'
        }
    ]
});