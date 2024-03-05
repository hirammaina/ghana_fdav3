Ext.define('Admin.view.Enforcement.views.grids.Investigation.ExhibitionRequestEvaluationGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'exhibitionRequestEvaluationGrid',
	itemId: 'exhibitionRequestEvaluationGrid',
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
            pnl = grid.up('exhibitionRequestEvaluationPnl'),
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
},
'->',
{
    text: 'Submit Application',
    ui: 'soft-blue',
    iconCls: 'fa fa-check',
    name: 'process_submission_btn',
    storeID: 'exhibitRequestEvaluationGridStr',
    table_name: '',
    winWidth: '50%'
}
],
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
            storeId: 'exhibitRequestEvaluationGridStr',
            proxy: {
                url: 'enforcement/getseizureProductsListGrid',
                
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
    columns: [
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'tracking_no',
	        text: 'Tracking No',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'batch_number',
	        text: 'Batch Number',
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
	        dataIndex: 'suspect_address',
	        text: 'Facility Address',
	        flex: 1
	    },
		 {
	        xtype: 'gridcolumn',
	        dataIndex: 'expiry_date',
	        text: 'expiry date',
	        flex: 1
	    },
        {
            xtype: 'gridcolumn',
            dataIndex: 'seizure_confirmation_id',
            text: 'Seizure Confirmation',
            flex: 1,
            renderer: function (value, metaData) {
                if (value == 1) {
                    metaData.tdStyle = 'color:white;background-color:green';
                    return "Siezed";
                }else if(value == 2){
                    metaData.tdStyle = 'color:white;background-color:red';
                    return "Not Seize";
                }else{
                    metaData.tdStyle = 'color:white;background-color:gray';
                    return "Pending";
                }
    
                
            }
        },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'exhibit_request_approval_id',
	        text: 'Exhibit Approval',
	        flex: 1,
	        renderer: function (value, metaData) {
	            if (value == 1) {
	                metaData.tdStyle = 'color:white;background-color:green';
	                return "Approved";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "Rejected";
	            }else{
	            	metaData.tdStyle = 'color:white;background-color:gray';
	            	return "Pending";
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
                            text: 'Product Seizure Report',
                            iconCls: 'fa fa-eye',
                            tooltip: 'Product Seizure Report',
                            winWidth: '60%',
                            handler: 'showApplicationProductSeizureReport',
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
                            text: 'Exhibit Request Approval',
                            iconCls: 'fa fa-check',
                            tooltip: 'Approve Exhibit Request',
                            action: 'edit',
                            childXtype: 'exhibitRequestApprovaFrm',
                            winTitle: 'Exhibit Request Approval Form',
                            winWidth: '40%',
                            handler: 'showSezuireConfirmation',
                            stores: '[]'
                        }
                    ]
                }
            }
        }
],
});
