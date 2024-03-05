Ext.define('Admin.view.Enforcement.views.grids.JointOperation.JointOperationRegisterGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'jointOperationRegisterGrid',
	itemId: 'jointOperationRegisterGrid',
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

tbar:[
    {
    	xtype: 'textfield',
    	name: 'tracking_no',
    	fieldLabel: 'Tracking Number',
    	margin: '0 0 0 0'
    },
	{
    	xtype: 'button',
    	iconCls: 'fa fa-search',
    	handler: 'reloadParentGridOnChange',
    	ui: 'soft-blue'
    },

],

    bbar: [{
        xtype: 'pagingtoolbar',
        width: '80%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function(){
            this.up('jointOperationRegisterGrid').fireEvent('refresh', this); 
            var grid = this.up('grid'),
                tracking_no = grid.down('textfield[name=tracking_no]').getValue(),
                store = this.getStore();
            store.removeAll();
            store.getProxy().extraParams = {
                tracking_no: tracking_no
            }
        }
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    }],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 100,
                remoteFilter: true,
                storeId: 'jointOperationRegisterGridStr',
                proxy: {
                  url: 'enforcement/getApprovedJointOperations',
                }
            },
            isLoad: true
        },
	
    },
    columns: [
        {
	    	xtype: 'rownumberer'
	    },
        {
            xtype: 'gridcolumn',
            dataIndex: 'tracking_no',
            text: 'Tracking Number',
            flex: 1
        },  
         {
	        xtype: 'gridcolumn',
	        dataIndex: 'activity',
	        text: 'Activity',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'department_name',
	        text: 'Organizing Department',
	        flex: 1
	    },
        {
            xtype: 'gridcolumn',
            dataIndex: 'organizing_officer',
            text: 'Orginizing Officer',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'start_date',
            text: 'Start Date',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'end_date',
            text: 'End Date',
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
                            text: 'View Joint Operation details',
                            iconCls: 'fa fa-eye',
                            tooltip: 'View details',
                            handler: 'showJointOperationRegisterMoreDetails',
                            stores: '[]'
                        },
                          {
	                    text: 'View Associated Documents',
	                    iconCls: 'fa fa-file-download',
	                    tooltip: 'View associated documents',
	                    action: 'view',
	                    winWidth: '70%',
	                    handler: 'showApplicationUploadedDocument',
	                    stores: '[]'
	                },

                    ]
                }
            }
        }
],
});
