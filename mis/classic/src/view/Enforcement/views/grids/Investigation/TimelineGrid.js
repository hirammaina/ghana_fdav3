Ext.define('Admin.view.Enforcement.views.grids.Investigation.TimelineGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'timelineGrid',
	itemId: 'timelineGrid',
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
            this.up('timelineGrid').fireEvent('refresh', this); 
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
                storeId: 'timelineGridStr',
                proxy: {
                   url: 'enforcement/getTimelineDetails',
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
        name:'add_timeline',
        handler: 'showAddConfigChargeWinFrm',
        winTitle: 'Timeline Details Form',
        winWidth:'80%',
        childXtype: 'timelineFrm',
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
	        dataIndex: 'action',
	        text: 'Action',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'date',
	        text: 'Date',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'action_time',
	        text: 'Time',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'officer',
	        text: 'Assigned Officer',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'action_duration',
	        text: 'Duration',
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
                            childXtype:'timelineFrm',
                            winTitle: 'Edit timeline details',
                            winWidth:'70%',
                            handler: 'showEditConfigParamWinFrm',
                            stores: '[]'
                        },
                        {
                            text: 'Delete',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'par_investigation_timeline',
                            storeID: 'timelineGridStr',
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
