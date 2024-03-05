Ext.define('Admin.view.Enforcement.views.grids.Investigation.SeizureGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'seizureGrid',
	itemId: 'seizureGrid',
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
            pnl = grid.up('seizurePnl'),
            module_id = pnl.down('hiddenfield[name=module_id]').getValue(),
            application_code = pnl.down('hiddenfield[name=active_application_code]').getValue(),
            workflow_stage_id = pnl.down('hiddenfield[name=workflow_stage_id]').getValue(),
            store = this.getStore();
        store.removeAll();
        store.getProxy().extraParams = {
            application_code: application_code,
            module_id: module_id,
            workflow_stage_id: workflow_stage_id
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
        // mode: 'local'
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
            storeId: 'seizureGridStr',
            proxy: {
                url: 'enforcement/getStageEnforcementApplications',
                
            }
        },
        isLoad: true
    },
 
},
    tbar: [
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
	    }, {
	        xtype: 'gridcolumn',
	        dataIndex: 'nature_of_report',
	        text: 'Nature of Report',
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
	        dataIndex: 'suspect_name',
	        text: 'Product Involved',
	        flex: 1
	    },
		{
	        xtype: 'gridcolumn',
	        dataIndex: 'suspect_address',
	        text: 'Facility Address',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'brand_name',
	        text: 'Product Brand Name',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'common_name',
	        text: 'Common Name',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'premise_name',
	        text: 'Facility Name',
	        flex: 1
	    },

         {
	        xtype: 'gridcolumn',
	        dataIndex: 'date_added',
	        text: 'Date Received',
	        flex: 1
	    }, {
	        xtype: 'gridcolumn',
	        dataIndex: 'submitted_by',
	        text: 'Reported By',
	        flex: 1
	    },
		{
	        xtype: 'gridcolumn',
	        dataIndex: 'internal_reporter',
	        text: 'Submitted By',
	        flex: 1
	    }, {
	        xtype: 'gridcolumn',
	        dataIndex: 'submitted_on',
	        text: 'Reported On',
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
                            text: 'View Report Details',
                            iconCls: 'fa fa-eye',
                            name: 'more_app_details',
                            ui: 'soft-blue',
                            isReadOnly: true,
                            handler: 'showSelectedApplicationMoreDetails'
                        },
                        {
                            text: 'Product Seizure Report',
                            iconCls: 'fa fa-eye',
                            tooltip: 'Product Seizure Report',
                            winWidth: '60%',
                            handler: 'showApplicationProductSeizureReport',
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
                        {
                            text: 'view Investigation Decisions',
                            iconCls: 'fa fa-list-alt',
                            tooltip: 'view Investigation Decision',
                            //name: 'view_report',
                            winTitle: 'Investigation Decisions',
                            winWidth: '70%',
                            ui: 'soft-blue',
                            handler: 'viewCaseDecisionLogs',
                            stores: '[]'
                        },
                        {
                            text: 'view/update Investigation Report',
                            iconCls: 'fa fa-list-alt',
                            tooltip: 'view investigation reports',
                            name: 'view_report',
                            winTitle: 'Investigation Report',
                            winWidth: '70%',
                            ui: 'soft-blue',
                            handler: 'viewApplicationInvestigationReport',
                            stores: '[]'
                        },
                      
                    ]
                }
            }
        }
],
});
