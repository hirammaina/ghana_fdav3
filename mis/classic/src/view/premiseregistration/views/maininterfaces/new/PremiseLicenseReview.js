Ext.define('Admin.view.premiseregistration.views.maininterfaces.new.PremiseLicenseReview', {
    extend: 'Ext.panel.Panel',
    xtype: 'premiselicensereview',//drugsreviewrecommendation
    controller: 'premiseregistrationvctr',
    viewModel: 'premiseregistrationvm',
    layout: 'fit',
    items: [
        {
            xtype: 'premiselicensereviewpnl',//newProductTcReviewMeetingpnlnewPremiseTcReviewMeetingpnl
            itemId:'main_processpanel'
        }
    ]
});