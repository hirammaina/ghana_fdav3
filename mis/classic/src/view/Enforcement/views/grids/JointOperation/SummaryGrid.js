Ext.define('Admin.view.Enforcement.views.grids.JointOperation.SummaryGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'summaryGrid',
	itemId: 'summaryGrid',
    cls: 'dashboard-todo-list',
	autoScroll: true,
	width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
    sub_module_id: 0,
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Applications Found',
	
},
plugins: [
	{
		ptype: 'gridexporter'
	}
],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '80%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function(){
            this.up('summaryGrid').fireEvent('refresh', this); 
        }
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        // mode: 'local'
    }],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 100,
                remoteFilter: true,
                storeId: 'summaryGridStr',
                proxy: {
                  url: 'enforcement/getOperationSummary',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
        {
            xtype: 'button',
            text: 'Add',
            name:'add_summary',
            iconCls: 'x-fa fa-plus',
            ui: 'soft-blue',
            handler: 'showAddConfigChargeWinFrm',
            winTitle: 'Operation Summary Form',
            winWidth:'70%',
            childXtype: 'summaryfrm',
            stores: '[]'
        },
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},
    
    ,],
    columns: [
        {
	    	xtype: 'rownumberer'
	    },  
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'summary',
	        text: 'Summary',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'conclusion',
	        text: 'Conclusion',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'recommendations',
	        text: 'Recommendations',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'facility',
	        text: 'Facility',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'action',
	        text: 'Action',
	        flex: 1
	    },
    
        {
            text: 'Options',
            xtype: 'widgetcolumn',
            width: 90,
            widget: {
                width: 75,
                textAlign: 'right',
                xtype: 'splitbutton',
                iconCls: 'x-fa fa-th-list',
                ui: 'gray',
                menu: {
                    xtype: 'menu',
                    items: [
                        {
                            text: 'Edit',
                            iconCls: 'x-fa fa-edit',
                            tooltip: 'Edit Record',
                            action: 'edit',
                            childXtype:'summaryfrm',
                            winTitle: 'Operation Summary Form',
                            winWidth:'70%',
                            handler: 'showEditConfigParamWinFrm',
                            stores: '[]'
                        },
                        {
                            text: 'Delete',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'par_operation_summary',
                            storeID: 'summaryGridStr',
                            action_url: 'configurations/deleteConfigRecord',
                            action: 'actual_delete',
                            handler: 'deleteRecord',
                        },
                    ]
                }
            }
        }
],
});
