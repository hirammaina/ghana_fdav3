Ext.define('Admin.view.frontoffice.importexport.grids.ControlledDrgSpreadSheetIEApplicationType', {
    extend: 'Ext.grid.Panel',  
    scroll: true,
    titleCollapse: true,
   width: '100%',
    xtype: 'controlleddrgspreadsheetieapplicationtypes',
    layout: 'fit',
    store: 'spreadsheetieapplicationsectionsstr',
    title: 'Select Application Sections',
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'id',
        name: 'id',
        hidden: true
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'name',
        name: 'name',
        flex:1
    }],
     listeners:{
        select: 'loadIEApplicationColumnsOnType'
     }
});