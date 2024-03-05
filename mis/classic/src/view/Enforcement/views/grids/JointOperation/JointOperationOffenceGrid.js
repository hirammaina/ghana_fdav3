Ext.define('Admin.view.Enforcement.views.grids.JointOperation.JointOperationOffenceGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'jointOperationOffenceGrid',
	itemId: 'jointOperationOffenceGrid',
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
            this.up('jointOperationOffenceGrid').fireEvent('refresh', this); 
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
                storeId: 'jointOperationOffenceGridStr',
                proxy: {
                  url: 'enforcement/getjointOperationOffenceDetails',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
        {
            xtype: 'button',
            text: 'Add Offence ',
            iconCls: 'x-fa fa-plus',
            ui: 'soft-blue',
            name: 'add',
            handler: 'showAddConfigChargeWinFrm',
            winTitle: 'Offence  Details Form',
            winWidth:'70%',
            childXtype: 'jointOperationOffencefrm',
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
	        dataIndex: 'premise',
	        text: 'Facility/Individual',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'offence',
	        text: 'Offence',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'address',
	        text: 'Physical Address',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'telephone_number',
	        text: 'Telephone Number',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'action',
	        text: 'Action',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'remarks',
	        text: 'Remarks',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'officer',
	        text: 'Responsible Officer',
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
                            childXtype:'jointOperationOffencefrm',
                            winTitle: 'Offence  Details Form',
                            winWidth:'70%',
                            handler: 'showEditConfigParamWinFrm',
                            stores: '[]'
                        },
                        {
                            text: 'Delete',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'par_joint_detected_offences',
                            storeID: 'jointOperationOffenceGridStr',
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
