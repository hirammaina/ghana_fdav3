 Ext.define('Admin.view.frontoffice.premise.grids.PremiseBsnInfo', {
 extend: 'Ext.grid.Panel',  
   scroll: true,
   width: '100%',
   collapsible: true,
   titleCollapse: true,
    xtype: 'premisebsninfo',
    layout:{
        type:'fit',
        align:'stretch',
        pack:'start'
      },
    title: 'Premise Business Details',
     listeners: {
        beforerender: {
            fn: 'setConfigCombosStore',
            config: {
                pageSize: 1000,
                storeId: 'spreadsheetpremisebsninfostr',
                proxy: {
                    url: 'openoffice/getPremisebsnInfo'
                }
            },
            isLoad: false
        }
    },
     viewConfig: {
            emptyText: 'No information found for the premise under set creteria'
        },
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'Type',
        name: 'Type',
        text: 'Business Type',
        flex: 1
       
    },{
        xtype: 'gridcolumn',
        dataIndex: 'details',
        name: 'details',
        text: 'Details',
        flex: 1
    }
    ]


  });