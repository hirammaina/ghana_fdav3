Ext.define('Admin.view.reports.appsreport.poeimportexportreport.panel.POEImportExportTabPnl', {
	extend: 'Ext.tab.Panel',
	xtype: 'poeimportexporttabpnl',
	margin: 2,
    controller: 'productreportctr',
    defaults: {
        bodyPadding: 1,
        scrollable: true,
    },
    items: [{
    	xtype: 'poeimportexporttabularrepresentationgrid',
    	title: 'Tabular Representation'
    },{
    	xtype: 'poeimportexportgraphicalrepresentationgraph',
    	title: 'Graphical Representation'
    }],
});