Ext.define('Admin.view.reports.appsreport.controlleddrugsreport.panel.ControlledDrugsCertificateLicensesRpt', {
   extend: 'Ext.panel.Panel',
    xtype: 'controlleddrugscertificatelicensesrpt',
    margin: 2,
    layout: 'border',
    controller: 'productreportctr',
    defaults: {
        bodyPadding: 1,
        scrollable: true,
    },
    items: [
     {
            xtype: 'hiddenfield',
            name: 'module_id',
            value: 12
        },
      {
            xtype: 'approvalcertificatereportfiltersfrm',
            region: 'north',
            title: 'Filters',
            collapsible:true,
            collapsed: false
         },{
            xtype: 'controlleddrugscertificatelicensesgrid',
            region: 'center'
    }],
  bbar: [{
        xtype: 'toolbar',
        width: '100%',
        ui: 'footer',
        items: [
        
         {
            xtype:'button',
            ui: 'soft-green',
            text: 'Export Report',
            iconCls: 'x-fa fa-file',
            handler: 'exportApprovalCertificateSummaryReport',
           
        }
       
    ]
     }
    ],

 });