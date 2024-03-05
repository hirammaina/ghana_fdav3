/**
 * Created by Softclans.
 */
Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.ManagerApprovalGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'enforcementvctr',
    xtype: 'monitoringManagerApprovalGrid',
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
                storeId: 'managerApprovalStr',
                proxy: {
                    url: 'enforcement/getMonitoringApplications'
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
    },
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
        dataIndex:'decision_id',
        text: 'Approve status',
        flex: 1,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:green';
                return "Approved";
            }else if(value == 2){
                metaData.tdStyle = 'color:white;background-color:red';
                return "Reject";
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
                        text: 'Approve',
                        iconCls: 'fa fa-check',
                        tooltip: 'Approval Decision',
                        action: 'edit',
                        approval_frm: 'enforcementApprovalRecommFrm',
                        handler: 'getEnforcementApplicationApprovalDetails',
                        vwcontroller: 'enforcementvctr',
                        stores: '[]',
                        table_name: 'tra_enforcement_applications',
	                    winWidth: '70%',
                    },
                    {
                        text: 'View Monitoring Details',
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
                    // {
                    //     text: 'View Recommendations',
                    //     iconCls: 'fa fa-clipboard-check',
                    //     tooltip: 'view Monitoring recommendations',
                    //     name: 'view_recommendation',
                    //     winWidth: '70%',
                    //     ui: 'soft-blue',
                    //     handler: 'viewMonitoringRecommendationLogs',
                    //     stores: '[]'
                    // },
                    {
                        text: 'View Debriefing Reccomendation',
                        iconCls: 'fa fa-clipboard-check',
                        tooltip: 'view debrief recommendations',
                        name: 'view_recommendation',
                        winWidth: '70%',
                        ui: 'soft-blue',
                        isReadOnly: true,
                        handler: 'showMonitoringRecommendationWin',
                        stores: '[]'
                    },
                    {
                        text: 'View Peer Recommendations',
                        iconCls: 'fa fa-clipboard-check',
                        tooltip: 'view Monitoring recommendations',
                        name: 'view_recommendation',
                        winWidth: '70%',
                        ui: 'soft-blue',
                        handler: 'viewPeerRecommendationLogs',
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
