/**
 * Created by Softclans.
 */
 Ext.define('Admin.view.Enforcement.views.grids.Investigation.DirectorApprovalGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'enforcementvctr',
    xtype: 'directorapprovalGrid',
    autoScroll: true,
    width: '100%',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Applications Found',
    },

    tbar:[
        {
            xtype: 'displayfield',
            value: 'Double click to view more details!!',
            hidden: true,
            fieldStyle: {
                'color':'green'
            }
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
        mode: 'local'
    }],
    
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 10000,
                storeId: 'directorApprovalStr',
                proxy: {
                    url: 'enforcement/getApprovedApplication'
                }
            },
            isLoad: true
        },
        select: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                panel = grid.up('panel'),
                selCount = grid.getSelectionModel().getCount();
            if (selCount > 0) {
                panel.down('button[name=process_submission_btn]').setDisabled(false);
            }else{
                panel.down('button[name=process_submission_btn]').setDisabled(true);
            }
        },
        deselect: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                panel = grid.up('panel'),
                selCount = grid.getSelectionModel().getCount();
            if (selCount < 1) {
                panel.down('button[name=process_submission_btn]').setDisabled(true);
            }
        },
        afterrender: function () {
            var grid = this,
                sm = grid.getSelectionModel();
            grid.store.on('load', function (store, records, options) {
                Ext.each(records, function (record) {
                    var rowIndex = store.indexOf(record);
                    if (record.data.inspection_id) {
                        sm.select(rowIndex, true);
                    }
                });
            });
        },
        // itemdblclick: 'showPremApplicationMoreDetailsOnDblClick'
    },
    columns: [
    {
	    	xtype: 'rownumberer'
	},{
        xtype: 'gridcolumn',
        dataIndex: 'tracking_no',
        text: 'Tracking Number',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'reference_no',
        text: 'Reference Number',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'nature_of_report',
        text: 'Nature of Report',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'suspected_entity',
        text: 'Suspected Entity',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'applicant_name',
        text: ' External Reporter',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'internal_reporter',
        text: 'Internal Reporter',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'submitted_on',
        text: 'Reported On',
        flex: 1
    },
    // {
    //     xtype: 'gridcolumn',
    //     dataIndex: 'application_status',
    //     text: 'Status',
    //     flex: 1
    // },
    // {
    //     xtype: 'gridcolumn',
    //     dataIndex: 'approval_decision_id',
    //     text: 'Approval Decision',
    //     flex: 1,
    //     renderer: function (value, metaData) {
    //         if (value == 1) {
    //             metaData.tdStyle = 'color:white;background-color:green';
    //             return "Approved";
    //         }else if(value == 2){
    //             metaData.tdStyle = 'color:white;background-color:red';
    //             return "Rejected";
    //         }else{
    //             metaData.tdStyle = 'color:white;background-color:gray';
    //             return "Pending";
    //         }         
    //     }
    // },
        {
        xtype: 'gridcolumn',
        dataIndex: 'decision_id',
        text: 'Decision State',
        flex: 1,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:green';
                return "Approved";
            }else if(value == 2){
                metaData.tdStyle = 'color:white;background-color:red';
                return "Not Approved";
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
	                    text: 'Final Approval Decision',
	                    iconCls: 'fa fa-check',
	                    tooltip: 'Add Approval decision',
	                    action: 'view',
	                    ui: 'soft-blue',
	                    approval_frm: 'enforcementApprovalRecommFrm',
                        handler: 'getEnforcementApplicationApprovalDetails',
                        vwcontroller: 'enforcementvctr',
                        stores: '[]',
                        table_name: 'tra_enforcement_applications',
	                    winWidth: '70%',
	                },
                    {
	                    text: 'View Case Final Decisions',
	                    iconCls: 'fa fa-clipboard-check',
	                    tooltip: 'view process recommendations',
	                    name: 'view_recommendation',
	                    winWidth: '70%',
	                    ui: 'soft-blue',
	                    handler: 'viewApplicationRecommendationLogs',
	                    stores: '[]'
	                },
                    {
	                    text: 'View Processing Comments',
	                    ui: 'soft-blue', 
                        iconCls: 'fa fa-clipboard-check',
                        childXtype: 'investigationCommentsPnl',
                        winTitle: 'Investigation Decision & Remarks',
                        winWidth: '60%',
                        handler:'showInvestigationDecisionwin',
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
                    {
                        text: 'Investigation & product Seizure Report',
                        iconCls: 'fa fa-eye',
                        tooltip: 'Product Seizure Report',
                        winWidth: '60%',
                        handler: 'showApplicationUploadedDocument',
                        stores: '[]'
                    },
                    // {
					// 	text: 'Product Exhibit Report',
					// 	iconCls: 'fa fa-eye',
					// 	tooltip: 'Product Seizure Report',
					// 	winWidth: '60%',
					// 	handler: 'showApplicationExhibitReport',
					// 	stores: '[]'
					// },
                    {
		                text: 'View Report Details',
		                iconCls: 'fa fa-eye',
		                name: 'more_app_details',
		                ui: 'soft-blue',
		                isReadOnly: true,
		                handler: 'showSelectedApplicationMoreDetails'
		            },{
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
    }]
});
