Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.ComplainceRegisterGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'complainceRegisterGrid',
	itemId: 'complainceRegisterGrid',
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
        var grid = this.up('grid'),
            pnl = grid.up('panel'),
            tracking_no = pnl.down('textfield[name=tracking_no]').getValue(),
            store = this.getStore();
        store.removeAll();
        store.getProxy().extraParams = {
            tracking_no: tracking_no,
        }
    }
}],
selModel:{
    selType: 'checkboxmodel',
    mode: 'MULTI'
},
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    },
    {
        ftype: 'grouping',
        startCollapsed: true,
        groupHeaderTpl: ': {[values.rows[0].data.charge_regulation]} [{rows.length}]',
        hideGroupedHeader: true,
        enableGroupingMenu: false
    }
],
listeners: {
    beforerender: {
        fn: 'setGridStore',
        config: {
            pageSize: 100,
            storeId: 'CompliantRegisterGridStr',
            proxy: {
                url: 'enforcement/getCompliantRegisterApplications',
                
            }
        },
        isLoad: true
    },
},
    tbar: [
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
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},

    {
		xtype: 'hiddenfield',
		name: 'active_application_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},],
    columns: [{
	    	xtype: 'rownumberer'
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'tracking_no',
	        text: 'Tracking No',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'reference_no',
	        text: 'Refrence No',
	        flex: 1
	    }, 
        {
			xtype: 'gridcolumn',
			dataIndex: 'premise_name',
			text: 'Entity Trade Name',
			flex: 1
		},
		{
			xtype: 'gridcolumn',
			dataIndex: 'assesment_start_date',
			text: 'Received On',
			flex: 1
		},
        {
			xtype: 'gridcolumn',
			dataIndex: 'start_date',
			text: 'Expected Start Date',
			flex: 1
		},		
        {
			xtype: 'gridcolumn',
			dataIndex: 'end_date',
			text: 'Assesment End Date',
			flex: 1
		},
		{
	        xtype: 'gridcolumn',
	        dataIndex: 'assesment_end_date',
	        text: 'Approved On',
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
                            text: 'View Application Details',
                            iconCls: 'fa fa-eye',
                            name: 'more_app_details',
                            ui: 'soft-blue',
                            isReadOnly: true,
                            handler: 'showMonitoringRegisterMoreDetails'
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
