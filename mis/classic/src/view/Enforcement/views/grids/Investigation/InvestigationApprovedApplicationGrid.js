Ext.define('Admin.view.Enforcement.views.grids.Investigation.InvestigationApprovedApplicationGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'investigationApprovedApplicationGrid',
	itemId: 'investigationApprovedListGrid',
    cls: 'dashboard-todo-list',
	autoScroll: true,
	width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
    sub_module_id: 0,
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Applications Found',
	// 	enableTextSelection: true,
    //     getRowClass: function (record, rowIndex, rowParams, store) {
    //         var is_enabled = record.get('is_enabled');
    //         if (is_enabled == 0 || is_enabled === 0) {
    //             return 'invalid-row';
    //         }
    // },
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
        // mode: 'local'
    }],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 100,
                remoteFilter: true,
                proxy: {
                    url: 'enforcement/getApprovedInvestigationApplications',
                }
            },
            isLoad: true
        },
		itemdblclick: 'loadEnforcementReportingWizardFromRecord'
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
		xtype: 'displayfield',
		value: 'Double click to select!!',
		fieldStyle: {
			'color': 'green',
			'font-weight': 'bold'}
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
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'suspected_entity',
	        text: 'Suspected Entity',
	        flex: 1
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'date_added',
	        text: 'Date Received',
			format: 'Y-m-d',
			altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00',
	        flex: 1
	    },
		// {
	    //     xtype: 'gridcolumn',
	    //     dataIndex: 'case_opened',
	    //     text: 'Case Opened On',
	    //     flex: 1,
		// 	format: 'Y-m-d',
		// 	altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00',	        
		// 	renderer: function (value) {
	    //         if (value == 'null') {
	    //             return "NOT YET";
	    //         }else{
	    //             return value;
		// 		}    
	    //     }
	    // },{
	    //     xtype: 'gridcolumn',
	    //     dataIndex: 'case_closed',
	    //     text: 'Case Closed On',
	    //     flex: 1,
		// 	format: 'Y-m-d',
		// 	renderer: function (value) {
	    //         if (value == 'null') {
	    //             return "NOT YET";
	    //         }else{
	    //             return value;
		// 		}      
	    //     }
	    // }, 
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
    }],
});
