Ext.define('Admin.view.Enforcement.views.grids.JointOperation.OperativesGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'operativesGrid',
	itemId: 'operativesGrid',
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
            this.up('operativesGrid').fireEvent('refresh', this); 
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
                storeId: 'operativesGridStr',
                proxy: {
                  url: 'enforcement/getOperatives',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
        {
            xtype: 'button',
            text: 'Add',
            iconCls: 'x-fa fa-plus',
            ui: 'soft-blue',
            handler: 'showAddConfigChargeWinFrm',
            winTitle: 'Operatives Form',
            winWidth:'70%',
            childXtype: 'operativesfrm',
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
	        dataIndex: 'internal',
	        text: 'Internal',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'internal_no_deployed',
	        text: 'No Deployed',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'external',
	        text: 'External',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'external_no_deployed',
	        text: 'No Deployed',
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
                            childXtype:'operativesfrm',
                            winTitle: 'Operatives Form',
                            winWidth:'70%',
                            handler: 'showEditConfigParamWinFrm',
                            stores: '[]'
                        },
                        {
                            text: 'Delete',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'par_operatives',
                            storeID: 'operativesGridStr',
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
