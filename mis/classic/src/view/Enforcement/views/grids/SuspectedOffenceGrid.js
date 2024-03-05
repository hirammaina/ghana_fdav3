Ext.define('Admin.view.Enforcement.views.grids.SuspectedOffenceGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'suspectedoffencegrid',
   
	tbar: [{
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    },{
        xtype: 'button',
        text: 'Add',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        name: 'add_offence',
        handler: 'showSuspectedOffenceForm',
        winTitle: 'Suspected Offence Details',
        childXtype: 'suspectedoffenceform',
        stores: '[]'
    }, {
        xtype: 'exportbtn'
    }],
    listeners:{
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                autoLoad: false,
                defaultRootId: 'root',
                enablePaging: true,
                storeId: 'suspectedoffencestr',
                proxy: {
                    url: 'enforcement/getSuspectedOffenceDetails'
                }
            },
            isLoad: true
        }
    },
	 bbar: [{
			xtype: 'pagingtoolbar',
			width: '100%',
			displayInfo: true,
			displayMsg: 'Showing {0} - {1} of {2} total records',
			emptyMsg: 'No Records',
			      beforeLoad: function () {
                    this.up('suspectedoffencegrid').fireEvent('refresh', this);
                    
        }
			
		}],
    
    columns: [ 
	{
        xtype: 'gridcolumn',
        text: ' Offence Type',
        dataIndex: 'offennce_type',
        flex: 1
    }, 
    {
        xtype: 'gridcolumn',
        text: 'Date Offence Reported',
        dataIndex: 'offence_date',
        flex: 1
    },
	{
        xtype: 'gridcolumn',
        text: 'Details',
        dataIndex: 'details',
        flex: 1
    },
	{
        text: 'Options',
        xtype: 'widgetcolumn',
        width: 90,
        widget: {
            width: 75,
            textAlign: 'left',
            xtype: 'splitbutton',
            iconCls: 'x-fa fa-th-list',
            ui: 'gray',
            menu: {
                xtype: 'menu',
                items: [{
                    text: 'Edit',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'View Task',
                    action: 'edit',
                    handler: 'editSuspectedOffenceDetails',
                    stores: '[]'
					
                }, {
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'par_suspected_offence',
                    storeID: 'suspectedoffencestr',
                    action_url: 'enforcement/genericDeleteRecord',
                    action: 'actual_delete',
                    handler: 'deleteRecord'
					
                }
                ]
            }
        }
    }
	
	
	]
});