Ext.define('Admin.view.Enforcement.views.grids.Investigation.SeizurePersonnelGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'seizurepersonnelgrid',
	itemId: 'seizurepersonnelgrid',
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
            this.up('grid').fireEvent('refresh', this);
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
                storeId: 'seizureWitnessGridStr',
                proxy: {
                   url: 'enforcement/getSeizureWitnessDetails',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
        {
        xtype: 'button',
        text: 'Add Seizure Witness details',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        handler: 'showAddConfigParamWinFrm',
        winTitle: 'Product Seizure Witness Form',
        winWidth:'40%',
        childXtype: 'seizureWitnessFrm',
        stores: '[]'
    },
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},
    {
		xtype: 'hiddenfield',
		name: 'active_application_code'
	},
    {
		xtype: 'hiddenfield',
		name: 'application_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},],
    columns: [{
	    	xtype: 'rownumberer'
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'witness_name',
	        text: 'Name of witness',
	        flex: 1,
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'witness_designation',
	        text: 'Witness Designation',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'created_on',
	        text: 'Date',
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
                    items: [
                        {
                         text: 'Edit',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'Edit Record',
                        action: 'edit',
                        childXtype:'seizureWitnessFrm',
                        winTitle: 'Edit ',
                        winWidth:'40%',
                        handler: 'showEditConfigParamWinFrm',
                        stores: '[]'
                        },
                        {
                            text: 'Delete',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'par_seizure_witness_details',
                            storeID: 'seizureWitnessGridStr',
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
