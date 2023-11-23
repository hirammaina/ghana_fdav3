Ext.define('Admin.view.dashboard.FinanceProcessDashboard', {
    extend: 'Ext.panel.Panel',
    xtype: 'financeprocessdashboard',
    margin: 2,
    requires: [
        'Ext.ux.layout.ResponsiveColumn'
    ],
    controller: 'dashboardvctr',
    viewModel: {
        type: 'dashboard'
    },
    layout: 'border',
    listeners: {
        hide: 'onHideView',
    }, 
    items: [ {
            xtype: 'tabpanel',
            region: 'center',
            userCls: 'big-100 small-100',
            listeners: {
                beforeRender: 'loadApplicationAssaignmentTab'
            },
            
            items: [
                    {
                        xtype: 'panel',
                        title: 'Intray(Assignments)',
                        is_receipting_stage:1,
                        layout: 'border',
                        items:[{
                            xtype: 'summaryintraygrid',
                            title: 'Summary of Assigned or Active Applications ',
                            region: 'west',
                            width: 500,
                            autoScroll: true,
                            split: true,
                            titleCollapse: true,
                            collapsed: false,
                            collapsible: true
                        }, {
                                xtype: 'intraygrid',
                                region: 'center',
                                title: 'Assigned or Active Applications Pending or Completed Processing',
                                userCls: 'big-100 small-100'
                        }] 
                },
                {
                    title: 'Out-Tray',
                    xtype: 'outtraygrid',
                    height: Ext.Element.getViewportHeight() - 161
                },{
                    xtype:'gepgbillinvoicepostingpnl',
                    layout:'fit',
                    title: 'Billing and Payment Details',

                },{
                    xtype:'tabpanel',
                    title:'Revenue Reports',
                    layout:'fit',
                    items:[{
                        xtype:'dailyfinancetrans',
                        title: 'Daily revenue Transaction(Periodic Payments)'
                    },{
                        xtype:'revenuesummaryreports',
                        title:'Applications Summary Revenue Report'
                    }]

                },
                {
                    xtype:'tabpanel',
                    layout:'fit',
                    title: 'Configurations',
                    height: Ext.Element.getViewportHeight() - 161,
                    items:[{
                        xtype: 'exchangeratepnl',
                        region:'Exchange Rates'
                    },{
                        xtype: 'elementscost',
                        title: 'Fees Taariff Guideline'
                    },{
                        xtype: 'applicationuploadproofauthorisation'
                    }]
                },{
                    title:'Application Enquiries(Tracking Applications Processing)',
                    xtype:'application_enquiriesGrid'
                },{
                    xtype:'controllleddocumentsaccessdashboard',
                    title:'Shared Documents (Controlled Documents Dashboard)',
                    layout:'fit'
                }
            ]
        }, {
            xtype: 'panel',
            title: 'Notifications & Messages',
            region: 'south',
            height: 250,
            titleCollapse: true,
            collapsed: true,
            collapsible: true
        },
		
    ]
});
