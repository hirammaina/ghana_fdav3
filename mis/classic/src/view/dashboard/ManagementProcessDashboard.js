Ext.define('Admin.view.dashboard.ManagementProcessDashboard', {
    extend: 'Ext.panel.Panel',
    xtype: 'managementprocessdashboard',
    margin: 2,
    requires: [
        'Ext.ux.layout.ResponsiveColumn'
    ],
    controller: 'dashboardvctr',
    viewModel: {
        type: 'dashboard'
    },
    layout: 'fit',
    listeners: {
        hide: 'onHideView'
    }, 
    items: [ {
            xtype: 'tabpanel',
            listeners: {
                beforeRender: 'loadApplicationAssaignmentTab'
            },
            items: [  {
                    xtype: 'panel',
                    title: 'Intray(Assignments)',is_receipting_stage:0,
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
            },{
                title: 'Out-Tray',
                xtype: 'outtraygrid',
                height: Ext.Element.getViewportHeight() - 161
            },{
                    title:'Application Enquiries(Tracking Applications Processing)',
                    xtype:'application_enquiriesGrid'
                },
               {
                    title: 'Application Revenue Reports',
                    xtype:'revenueSummaryReportPnl'
               },{
                        title: 'Application Summary Reports',
                        xtype: 'tabpanel',
                        items:[{
                            title:'Product Summary Reports',
                            xtype:'productreportpnl'
                        },{
                            title:'Premises Summary Reports',
                            xtype:'premisesreportpnl'
                        },{
                            title:'GMP Summary Reports',
                            xtype:'gmpreportpnl'
                        },{
                            title:'Clinical Trial Summary Reports',
                            xtype:'clinicaltrialreportpnl'
                        },{
                            title:'Import & Export Summary',
                            xtype:'importexportreportpnl'
                        },{
                            title:'Promotional & Advertisements',
                            xtype:'promotionadvertisementreportpnl'
                        },{
                            title:'Disposal Application Summary Report',
                            xtype:'disposalreportpnl'
                        }]
                },{
                    xtype:'controllleddocumentsaccessdashboard',
                    title:'Shared Documents (Controlled Documents Dashboard)',
                    layout:'fit'
                }
                
            ]
        }
		
    ]
});
