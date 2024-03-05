Ext.define('Admin.view.Enforcement.views.grids.Investigation.PeerReviewEnforcementApplicationsGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'peerMeetingEnforcementApplicationListGrid',
    cls: 'dashboard-todo-list',
    // header: false,
    controller: 'enforcementvctr',
    autoScroll: true,
    // autoHeight: true,
    width: '100%',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Cases Found'
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
                storeId: 'enforcementApplicationsGridStr',
                proxy: {
					url: 'enforcement/getStageEnforcementApplications'
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
           		if (decision_id == 1 || decision_id == 2) {
	            	if(selCount > 0 ){
	            		panel.down('button[name=process_submission_btn]').setDisabled(false);
	            	}
	            }else{
	            	return false;
	                panel.down('button[name=process_submission_btn]').setDisabled(true);
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
			dataIndex: 'nature_of_report',
			text: 'Nature of Report',
			flex: 1
		},
		
		{
			xtype: 'gridcolumn',
			dataIndex: 'suspect_name',
			text: 'Suspect Name',
			flex: 1
		},
		{
			xtype: 'gridcolumn',
			dataIndex: 'suspect_address',
			text: 'Suspect Address',
			flex: 1
		},
		{
			xtype: 'gridcolumn',
			dataIndex: 'submitted_by',
			text: 'Reported By',
			flex: 1
		},
		{
			xtype: 'gridcolumn',
			dataIndex: 'reporter',
			text: 'Submitted By',
			flex: 1
		},
		{
			xtype: 'gridcolumn',
			dataIndex: 'submitted_on',
			text: 'Reported On',
			flex: 1
		},
	  
		{
			xtype: 'gridcolumn',
			dataIndex: 'application_status',
			text: 'Status',
			flex: 1
		}, {
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
	                metaData.tdStyle = 'color:white;background-color:green';
	                return "Recommended";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "Not Recommended";
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
		                handler: 'showRecommendationWin'
		            },
					{
	                    text: 'View Investigation Diary',
	                    iconCls: 'fa fa-clipboard-list',
	                    tooltip: 'view process recommendations',
	                    name: 'view_recommendation',
	                    winWidth: '70%',
	                    ui: 'soft-blue',
	                    handler: 'showSelectedApplicationInvestigationDiary',
						//handler: 'showSelectedApplicationMoreDetails',
	                    stores: '[]'
	                },
					// {
					// 	text: 'Product Seizure Report',
					// 	iconCls: 'fa fa-eye',
					// 	tooltip: 'Product Seizure Report',
					// 	winWidth: '60%',
					// 	handler: 'showApplicationUploadedDocument',
					// 	stores: '[]'
					// },
					{
                        text: 'Case Investigation & Comments',
                        ui: 'soft-blue', 
                        iconCls: 'fa fa-clipboard-check',
                        childXtype: 'investigationCommentsPnl',
                        winTitle: 'Investigation Decision & Remarks',
                        winWidth: '60%',
                        handler:'showInvestigationDecisionwin',
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
	                    text: 'view/update Investigation & Product Seizure Report',
	                    iconCls: 'fa fa-list-alt',
	                    tooltip: 'view investigation reports',
	                    name: 'view_report',
	                    winTitle: 'Investigation Report',
	                    winWidth: '70%',
	                    ui: 'soft-blue',
	                    handler: 'viewApplicationInvestigationReport',
	                    stores: '[]'
	                },
					{
		                text: 'View Report Details',
		                iconCls: 'fa fa-eye',
		                name: 'more_app_details',
		                ui: 'soft-blue',
		                isReadOnly: true,
		                handler: 'showSelectedApplicationMoreDetails'
		            },
					{
	                    text: 'View Associated Case Documents',
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
