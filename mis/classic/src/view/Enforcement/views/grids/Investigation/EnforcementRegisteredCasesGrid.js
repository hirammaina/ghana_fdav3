Ext.define('Admin.view.Enforcement.views.grids.Investigation.EnforcementRegisteredCasesGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'enforcementRegisteredCasesGrid',
	itemId: 'enforcementRegisteredCasesGrid',
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
                proxy: {
                    url: 'enforcement/getRegisteredCases',
                }
            },
            isLoad: true
        },
    },
    tbar: [{
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
		name: 'section_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'region_id'
	},{
		xtype: 'hiddenfield',
		name: 'district_id'
	}],
    columns: [{
	    	xtype: 'rownumberer'
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'tracking_no',
	        text: 'Tracking No',
	        flex: 1
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'reference_no',
	        text: 'Reference Number',
	        flex: 1,
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'report_type',
	        text: 'Report Type',
	        flex: 1
	    },
		{
	        xtype: 'gridcolumn',
	        dataIndex: 'reporter',
	        text: 'Reporter',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'suspected_entity',
	        text: 'Suspected Entity',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'investigator',
	        text: 'Investigator',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'date_added',
	        text: 'Date Received',
			format: 'Y-m-d',
			altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00',
	        flex: 1
	    },
		
		{
	        xtype: 'gridcolumn',
	       	dataIndex: 'case_status_id',
	        text: 'Case Status',
	        flex: 1,
	        renderer: function (value, metaData) {
	            if (value == 1) {
	                metaData.tdStyle = 'color:white;background-color:green';
	                return "OPEN";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "CLOSED";
	            }else{
					metaData.tdStyle = 'color:white;background-color:grey';
	                return "PENDING";
				}    
	        }
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
		                text: 'View Report Details',
		                iconCls: 'fa fa-eye',
		                name: 'more_app_details',
		                ui: 'soft-blue',
		                isReadOnly: true,
		                handler: 'showSelectedReportDetails'
		        },
				{
					text: 'View Associated Documents',
					iconCls: 'fa fa-file-download',
					tooltip: 'View associated documents',
					action: 'view',
					winWidth: '70%',
					handler: 'showApplicationUploadedDocument',
					stores: '[]'
				}
                ]
            }
        }
    }
],
});
