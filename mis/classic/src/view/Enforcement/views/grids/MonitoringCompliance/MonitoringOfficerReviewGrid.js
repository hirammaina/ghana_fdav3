Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.MonitoringOfficerReviewGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'monitoringOfficerReviewGrid',
	itemId: 'monitoringOfficerReviewGrid',
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
            pnl = grid.up('officerReviewPnl'),
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
            storeId: 'monitoringOfficerReviewGridStr',
            proxy: {
                url: 'enforcement/getMonitoringOfficerReview',
                
            }
        },
        isLoad: true
    },
    select: function(sel, record, index, eOpts) {
        var me = this,
            grid = sel.view.grid,
            panel = grid.up('panel'),
            selCount = grid.getSelectionModel().getCount();
        if (selCount > 0) {
            panel.down('button[name=process_submission_btn]').setDisabled(false);
        }else{
            panel.down('button[name=process_submission_btn]').setDisabled(true);
        }

     },
     beforeselect: function(sel, record, index, eOpts) {
        var me = this,
            grid = sel.view.grid,
            panel = grid.up('panel'),
            enforcement_decision_id = record.get('enforcement_decision_id'),
            selCount = grid.getSelectionModel().getCount();
        if (enforcement_decision_id == 1 || enforcement_decision_id == 2) {
            if(selCount > 0 ){
                panel.down('button[name=process_submission_btn]').setDisabled(false);
            }
        }else{
            return false;
            panel.down('button[name=process_submission_btn]').setDisabled(true);
        }

     }
},
    tbar: [
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},
    {
		xtype: 'hiddenfield',
		name: 'active_application_id'
	}],
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
			text: 'Rererence Number',
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
			dataIndex: 'start_date',
			text: 'Start Date',
			flex: 1
		},
        {
			xtype: 'gridcolumn',
			dataIndex: 'start_date',
			text: 'End Date',
			flex: 1
		},
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'date_added',
	        text: 'Date Received',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'enforcement_decision_id',
	        text: 'Enforcement Decision',
	        flex: 1,
	        renderer: function (value, metaData) {
	            if (value == 1) {
	                metaData.tdStyle = 'color:white;background-color:green';
	                return "Compliant";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "Non Compliant";
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
                            text: 'Enforcement Decision',
                            iconCls: 'fa fa-check',
                            tooltip: 'Decision',
                            action: 'edit',
                            childXtype: 'enforcementDecisionFrm',
                            winTitle: 'Enforcement Desicion',
                            winWidth: '50%',
                            handler: 'showMonitorApprovalwin',
                            stores: '[]'
                        },
                        {
                            text: 'View Application Details',
                            iconCls: 'fa fa-eye',
                            name: 'more_app_details',
                            ui: 'soft-blue',
                            isReadOnly: true,
                            handler: 'showSelectedApplicationMoreDetails'
                        },					
                        {
                            text: 'Compliance Data Assesment Tool',
                            iconCls: 'x-fa fa-toolbox',
                            name: 'compliance-tool',
                            ui: 'soft-blue',
                            isReadOnly: true,
                            handler: 'showSelectedApplicationComplianceData'
                        },
                        {
                            text: 'View Recommendations',
                            iconCls: 'fa fa-clipboard-check',
                            tooltip: 'view Monitoring recommendations',
                            name: 'view_recommendation',
                            winWidth: '70%',
                            ui: 'soft-blue',
                            handler: 'viewMonitoringRecommendationLogs',
                            stores: '[]'
                        },
                        // {
                        //     text: 'view/update Monitoring Report',
                        //     iconCls: 'fa fa-list-alt',
                        //     tooltip: 'view Monitoringreports',
                        //     name: 'view_report',
                        //     winTitle: 'Monitoring Report',
                        //     winWidth: '70%',
                        //     ui: 'soft-blue',
                        //     handler: 'viewApplicationInvestigationReport',
                        //     stores: '[]'
                        // },
                    ]
                }
            }
        }
],
});
