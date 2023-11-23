Ext.define('Admin.view.dashboard.OnlineSubmissionsPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'onlinesubmissionspnl',
    margin: 2,
    itemId:'onlinesubmissionspnl',
    reference:'intraypnl',
    //id:'dashboard',
    requires: [
        'Ext.ux.layout.ResponsiveColumn'
    ],
    controller: 'dashboard',
    viewModel: {
        type: 'dashboard'
    },
    listeners: {
        hide: 'onHideView'
    }, 
    layout: 'fit',
    items: [{
            xtype:'tabpanel',
            layout:'fit',
            items:[{
                xtype: 'onlineapplicationdashboardgrid',
                region:'center'
                
            },{

                xtype: 'onlineevaluationqueryresponseappdashboardgrid',
                title:'Online Application Evaluation/Inspection Query Response)'
                
            },{
                          xtype: 'onlineappssubmissioncountergrid',
                        title:'Online Application Submissions Counter(Summary Data)',
                        region: 'south',                      
                        autoScroll: true


            }]

    }
    ]
});
