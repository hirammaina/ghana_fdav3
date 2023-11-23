Ext.define('Admin.view.reports.appsreport.poeimportexportreport.panel.POEImportExportReportPnl', {
   extend: 'Ext.panel.Panel',
    xtype: 'poeimportexportreportpnl',
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
            value: 4
        },
      {
            xtype: 'poeimportexportreportfiltersfrm',
            region: 'north',
            title: 'Filters',
            collapsible:true,
            collapsed: false
         },{
            xtype: 'poeimportexporttabpnl',
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
            text: 'Print Summary Report',
            iconCls: 'x-fa fa-print',
            handler: 'printPOEImportExportSummary',
           
            
        },
         {
            xtype:'button',
            ui: 'soft-green',
            text: 'Export Summary Report',
            iconCls: 'x-fa fa-file',
            handler: 'func_exportPOEImportExportSummaryReport',
           
        },
        '->',
        {
            xtype:'button',
            ui: 'soft-green',
            text: 'Export Detailed Report',
            iconCls: 'x-fa fa-file',
            handler: 'func_exportPOEImportExportDetailedReport',
           
        },
        {
            xtype:'button',
            ui: 'soft-green',
            hidden: true,
            text: 'Preview & Export Detailed Report',
            handler: 'func_POEExpImportExportWinShow',
            childXtype: 'poedetailedimportexportreportfrm',
            winTitle: 'Export Detailed Report',
            name: 'DetailedExport',
            module: 'importexportWin',
            winWidth: '70%',
            xFileName: 'POE Import Export Detailed Report',
            xPrintFunc: 'poeimportExportDetailedReportPreview',
            xspreadsheet: 'poedetailedimportexportviewgrid',
            xvisibleColumns: 'poedetailedimportexportcolumnsfrm',
            xheading:'POE Import & Export Application Detailed Report'
            
            
        }

       
    ]
     }
    ],

 });