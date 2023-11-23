Ext.define('Admin.view.summaryreport.Documents_Reports.view.panel.ApplicationDocumentsReports', {
    extend: 'Ext.tab.Panel',
    xtype: 'applicationdocumentsreports',
    title: 'Document Report',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'applicationDocUploadsGrid'
        },{
            xtype: 'systemGeneratedReportsFrm'
        }
    ]
});
