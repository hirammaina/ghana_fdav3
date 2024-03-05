Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.PeerMeetingMonitoringApplicationListGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'peerMeetingMonitoringApplicationListGrid',
    cls: 'dashboard-todo-list',
    // header: false,
    controller: 'enforcementvctr',
    autoScroll: true,
    // autoHeight: true,
    width: '100%',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Applications Found'
    },bbar: [{
        xtype: 'pagingtoolbar',
        width: '80%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function(){
            var grid = this.up('grid'),
                pnl = grid.up('panel'),
                module_id = pnl.down('hiddenfield[name=module_id]').getValue(),
                application_code = pnl.down('hiddenfield[name=active_application_code]').getValue(),
                workflow_stage_id = pnl.down('hiddenfield[name=workflow_stage_id]').getValue(),
                store = this.getStore(),
                meeting_id;
            if(pnl.up('panel').down('form').down('hiddenfield[name=id]')){
                meeting_id = pnl.up('panel').down('form').down('hiddenfield[name=id]').getValue();
            }
            store.removeAll();
            store.getProxy().extraParams = {
                application_code: application_code,
                module_id: module_id,
                workflow_stage_id: workflow_stage_id,
                meeting_id: meeting_id
            }
        }
    },'->',{
        xtype: 'button',
        text: 'Save Meeting Details',
        iconCls: 'fa fa-save',
        ui: 'soft-blue',
        name: 'save_btn',
        table_name: 'tra_enforcement_applications',
        toaster: 1
    }],
    selModel:{
        selType: 'checkboxmodel',
        mode: 'MULTI'
    },
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
                storeId: 'monitoringmeetingGridStr',
                proxy: {
					url: 'enforcement/getEnforcementActionApplications'
                }
            },
            isLoad: true
        },
        afterrender: function(grid){
        	var is_meeting = grid.is_meeting;
        	if(is_meeting == 1){
        		var save_btn = grid.down('button[name=save_btn]');
        		save_btn.setVisible(false);
        	}

        },
        beforeselect: function(sel, record, index, eOpts) {
            var me = this,
                grid = sel.view.grid,
                panel = grid.up('panel'),
                stage_category_id = record.get('stage_category_id'),
                selCount = grid.getSelectionModel().getCount(),
           		decision_id = record.get('recommendation_id');

           	if(stage_category_id == 7){
           		if (decision_id == 1 || decision_id == 2 || decision_id == 3 || decision_id == 4 || decision_id == 5 ) {
	            	if(selCount > 0 ){
	            		panel.down('button[name=process_submission_btn]').setDisabled(false);
	            	}
	            }else{
	            	return false;
				}
           	}
           
            	
         },
         select: function(sel, record, index, eOpts) {
         	var me = this,
                grid = sel.view.grid,
                panel = grid.up('panel'),
                selCount = grid.getSelectionModel().getCount();
                
            if(selCount > 0 ){
        		panel.down('button[name=process_submission_btn]').setDisabled(false);
        	}else{
        		panel.down('button[name=process_submission_btn]').setDisabled(true);
        	}
         },
         deselect: function(sel, record, index, eOpts) {
         	var me = this,
                grid = sel.view.grid,
                panel = grid.up('panel'),
                selCount = grid.getSelectionModel().getCount();
                
            if(selCount > 0 ){
        		panel.down('button[name=process_submission_btn]').setDisabled(false);
        	}else{
        		panel.down('button[name=process_submission_btn]').setDisabled(true);
        	}
         }
    },
    columns: [{
	    	xtype: 'rownumberer'
	    },{
			xtype: 'gridcolumn',
			dataIndex: 'tracking_no',
			text: 'Tracking Number',
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
			dataIndex: 'end_date',
			text: 'End Date',
			flex: 1
		},
		{
			xtype: 'gridcolumn',
			dataIndex: 'application_status',
			text: 'Status',
			flex: 1
		}, 
		{
	        xtype: 'gridcolumn',
	        dataIndex: 'recommendation_id',
	        text: 'Peer Recommendation',
	        name: 'recommendation_id',
	        width: 150,
	        renderer: function (value, metaData, record) {
	            if(record.get('stage_category_id') == 10 || record.get('stage_category_id') == 11){
	            	return "Check under Options";
	            }
	            else if (value == 1) {
	                metaData.tdStyle = 'color:white;background-color:red';
	                return "Fine/Sanction";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "Investigation";
	            }
				else if(value == 3){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "Corrective Action";
	            }
				else if(value == 4){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "External referral";
	            }
				else if(value == 5){
	            	metaData.tdStyle = 'color:white;background-color:green';
	            	return "Compliant";
	            }else{
	            	metaData.tdStyle = 'color:white;background-color:grey';
	            	return "Pending";
	            }
        	}
	    },{
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
		                text: 'Peer Recommendation',
		                iconCls: 'fa fa-clipboard-check',
		                name: 'peer_recommendation',
		                ui: 'soft-blue',
		                hidden: true,
		                // review_type: 1,
		                handler: 'showEnforcementActionsWin'
		            },
					{
		                text: 'View Plan Details',
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
					{
	                    text: 'View Associated Documents',
	                    iconCls: 'fa fa-file-download',
	                    tooltip: 'View associated Case documents',
	                    action: 'view',
	                    winWidth: '70%',
	                    handler: 'showApplicationUploadedDocument',
	                    stores: '[]'
	                },					
	                ]
	            }
	        }, onWidgetAttach: function (col, widget, rec) {
	            var grid =widget.up('grid'),
	            	is_meeting = grid.is_meeting,
	            	stage_category_id = rec.get('stage_category_id');
	            if (is_meeting === 0 || is_meeting == 0) {
	                widget.down('menu menuitem[name=peer_recommendation]').setVisible(false);;
	            } 
	            else {
	            	widget.down('menu menuitem[name=peer_recommendation]').setVisible(true);
	            }
	            if(stage_category_id == 10 || stage_category_id == 11){//report preparation
	            	widget.down('menu menuitem[name=peer_recommendation]').setVisible(false);
	            	//widget.down('menu menuitem[name=view_rc_report]').setVisible(true);
	            	//disable participant updates 
	            	if(widget.up('grid')){
	            		if(widget.up('grid').up('panel')){
	            			if(widget.up('grid').up('panel').down('button[name=update_attendance]')){
	            				widget.up('grid').up('panel').down('button[name=update_attendance]').setVisible(false);
	            			}
	            		}
	            	}
	            	
	            	//hide recom column
	            }
            }
    }],
});
