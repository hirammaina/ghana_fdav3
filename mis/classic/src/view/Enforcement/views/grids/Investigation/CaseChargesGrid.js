Ext.define('Admin.view.Enforcement.views.grids.Investigation.CaseChargesGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'casechargesgrid',
	itemId: 'casechargesgrid',
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
                storeId: 'caseChargesStr',
                proxy: {
                   url: 'enforcement/getCaseCharges',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
        {
        xtype: 'button',
        text: 'Add Case Details',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        handler: 'showAddConfigParamWinFrm',
        winTitle: 'Case Charges Form',
        winWidth:'70%',
        childXtype: 'casechargesfrm',
        stores: '[]'
    },
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},],
    columns: [{
	    	xtype: 'rownumberer'
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'charge_details',
	        text: 'Charge Details',
	        flex: 1,
	        hidden: true
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'charge_regulation',
	        text: 'Charge Regulation',
	        flex: 1
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'charge_section',
	        text: 'Charge Section',
	        flex: 1
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'charge_elements',
	        text: 'Charge Elements',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'created_on',
	        text: 'Date of Charge',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'charged_by',
	        text: 'Charged BY',
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
                            iconCls: 'fa fa-eye',
                            name: 'more_app_details',
                            ui: 'soft-blue',
                            isReadOnly: true,
                            handler: 'showSelectedApplicationMoreDetails'
                        },{
                            text: 'Delete',
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
