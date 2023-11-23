Ext.define('Admin.view.dashboard.ImportExportPOEDashboard', {
    extend: 'Ext.panel.Panel',
    xtype: 'importexportpoedashboard',
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
            userCls: 'big-100 small-100',height: Ext.Element.getViewportHeight() - 161,
            listeners: {
                beforeRender: 'loadApplicationAssaignmentTab'
            },
            items: [{
                        xtype: 'panel',
                        title: 'Intray(Assignments)',
                        is_receipting_stage:0,
                        is_importexp_stage:1,
                        layout: 'border',
                        items:[{
                            xtype: 'impexpsummaryintraygrid',
                            title: 'Summary of Assigned or Active Applications ',
                            region: 'west',
                            width: 500,
                            autoScroll: true,
                            split: true,
                            titleCollapse: true,
                            collapsed: false,
                            collapsible: true
                        }, {
                                xtype: 'impexpintraygrid',
                                region: 'center',
                                title: 'Assigned or Active Applications Pending or Completed Processing',
                                userCls: 'big-100 small-100'
                        }] 
                },{
                    title: 'Out-Tray',
                    xtype: 'outtraygrid',
                    height: Ext.Element.getViewportHeight() - 161
                },{
                    xtype: 'querysubmissionstraytaskgrid',
                    title:'Query Submissions'
                },{
                    xtype: 'completedtraytaskgrid',
                    title:'Approved Applications'
                },
                {
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
