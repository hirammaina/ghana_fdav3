Ext.define('Admin.view.view.promotionmaterials.views.grids.common.PromotionAndAdvertApprovalsGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'promotionmaterialviewcontroller',
    xtype: 'promotionandadvertapprovalsgrid',
    cls: 'dashboard-todo-list',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display',
        getRowClass: function (record, rowIndex, rowParams, store) {
            var recommendation_id = record.get('recommendation_id');
            if (recommendation_id > 0) {
                return 'valid-row';
            }else{
                return 'invalid-row';
            }
        },
        listeners: {
            refresh: function () {
                var gridView = this,
                    grid = gridView.grid;
                grid.fireEvent('moveRowTop', gridView);
            }
        }
    },
    selModel: {
        selType: 'checkboxmodel'
    },
    dockedItems: [
        {
            xtype: 'toolbar',
            ui: 'footer',
            dock: 'bottom',
            items: [
                {
                    xtype: 'pagingtoolbar',
                    displayInfo: true,
                    displayMsg: 'Showing {0} - {1} of {2} total records',
                    emptyMsg: 'No Records',
                    table_name: 'tra_promotion_adverts_applications',
                    beforeLoad: function () {
                        this.up('grid').fireEvent('refresh', this);
                    }
                },
                '->',
                {
                    xtype: 'button',
                    text: 'Submit Application(s)',
                    iconCls: 'x-fa fa-check',
                    ui: 'soft-purple',
                    name: 'submit_selected',
                    disabled: true,
                    storeID: 'promotionmaterialapplicationstr',
                    table_name: 'tra_promotion_adverts_applications',
                    action: 'process_submission_btn',
                    winWidth: '50%'
                }
            ]
        }
    ],
    features: [{
        ftype: 'searching',
        mode: 'local',
        minChars: 2
    }],
    listeners: {
        beforerender: {
            fn: 'custStoreConfig',
            config: {
                pageSize: 10000,
                storeId: 'approvalsstr',
                proxy: {
                    url: 'promotionmaterials/getPromotionAndAdvertsApplicationsAtApproval'
                }
            },
            isLoad: true
        },
        select: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                selCount = grid.getSelectionModel().getCount();
            if (selCount > 0) {
                grid.down('button[name=submit_selected]').setDisabled(false);
                
                //grid.down('button[name=batch_tc_recommendation]').setDisabled(false);
                grid.down('button[name=batch_approval_recommendation]').setDisabled(false);
            }
        },
        beforeselect: function (sel, record, index, eOpts) {
            var recommendation_id = record.get('recommendation_id');
            if (recommendation_id > 0) {
               // return true;
            } else {
             //   return false;
            }
        },
        deselect: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                selCount = grid.getSelectionModel().getCount();
            if (selCount < 1) {
                grid.down('button[name=submit_selected]').setDisabled(true);
              //  grid.down('button[name=batch_tc_recommendation]').setDisabled(true);
                grid.down('button[name=batch_approval_recommendation]').setDisabled(true);
            }
        }
    },
    tbar:[{
        text:'Batch Approval Recommendation',
            name:'batch_approval_recommendation',
            disabled: true,
            table_name: 'tra_promotion_adverts_applications',
            stores: '["approvalsstr"]',
            handler:'getBatchApplicationApprovalDetails',
            approval_frm: 'batchproductapprovalrecommfrm',
            iconCls: 'x-fa fa-chevron-circle-up',
            margin: 5
  }],
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'tracking_no',
            text: 'Tracking Number',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'reference_no',
            text: 'Ref Number',
            flex: 1
        },  {
            xtype: 'gridcolumn',
            text: 'From',
            dataIndex: 'from_user',
            flex: 1,
            tdCls: 'wrap'
        },
        {
            xtype: 'gridcolumn',
            text: 'To',
            dataIndex: 'to_user',
            flex: 1,
            tdCls: 'wrap'
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'applicant_name',
            text: 'Applicant',
            flex: 1
        },{
            xtype: 'gridcolumn',
            dataIndex: 'applicant_name',
            text: 'Applicant',
            flex: 1
        },
    
        {
            xtype: 'gridcolumn',
            dataIndex: 'advertisement_type',
            text: 'Advertisement Type',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'description_of_advert',
            text: 'Description of Advertisement',
            flex: 1
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'venue_of_exhibition',
            text: 'Venue of the Advertisement/Exhibition',
            flex: 1
        },{
            xtype: 'gridcolumn',
            dataIndex: 'exhibition_start_date',
            text: ' Advertisement/Exhibition Start Date',
            flex: 1
        },{
            xtype: 'gridcolumn',
            dataIndex: 'exhibition_start_date',
            text: ' Advertisement/Exhibition End Date',
            flex: 1
        },  {
            xtype: 'gridcolumn',
            dataIndex: 'sponsor_name',
            text: 'Sponsor Name',
            flex: 1
        }, 	
        {
            xtype: 'gridcolumn',
            dataIndex: 'workflow_stage',
            text: 'Workflow Stage',
            flex: 1
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'application_status',
            text: 'Application Status',
            flex: 1,
            tdCls: 'wrap'
        }, {
            xtype: 'gridcolumn',
            text: 'Date Received',
            dataIndex: 'date_received',
            flex: 1,
            tdCls: 'wrap-text',
            renderer: Ext.util.Format.dateRenderer('d/m/Y H:i:s')
        },{
        xtype: 'gridcolumn',
        dataIndex: 'recommendation',
        text: 'Recommendation',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'application_status',
        text: 'Status',
        flex: 1
    },{
        xtype: 'widgetcolumn',
        width: 160,
        widget: {
            width: 160,
            textAlign: 'left',
            xtype: 'button',
            ui: 'soft-green',
            text: 'Preview Certificate',
            iconCls: 'x-fa fa-certificate',
            backend_function: 'generatePromotionalRegCertificate',
            handler: 'generatePromotionalRegCertificate'
        }
    },  {
        xtype: 'widgetcolumn',
        width: 160,
        tdCls: 'wrap-text',
        widget: {
            width: 160,
            textAlign: 'left',
            xtype: 'button',
            ui: 'soft-red',
            text: 'View Remarks',
            iconCls: 'x-fa fa-weixin',
            //handler: 'getColRecommendationRemarksDetails',
            childXtype: 'applicationcommentspnl',
            winWidth: '80%',
            isReadOnly: 1,
            handler: 'showApplicationCommentsGeneric',
          //  childXtype: 'applicationprevcommentsgrid',
            winWidth: '60%',
            stores: '[]',
            isWin: 1
        }
    },  {
        text: 'Approval Recommendation',
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
                        text: 'Recommendation',
                        iconCls: 'x-fa fa-chevron-circle-up',
                        handler: 'getApplicationApprovalDetails',
                        stores: '["approvaldecisionsstr"]',
                        table_name: 'tra_promotion_adverts_applications'
                    },{
                        text: 'Preview Application Details',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'Preview Record',
                        action: 'edit',
                        childXtype: '',
                        winTitle: 'Promotional & Advertisements Information',
                        winWidth: '40%',
                        isReadOnly:1,
                        handler: 'showPromotionAndAdvertsApplicationMoreDetailsOnDblClick'
                    }, 
                    {
                        text: 'Print Screening Report',
                        iconCls: 'x-fa fa-print',
                        handler: 'printColumnPromotionalScreeningReport'
                    },{
                        text: 'View Screening Checklists & Recommendation',
                        iconCls: 'x-fa fa-check-square',
                        handler: 'showApplicationChecklists'
                    },{
                        text: 'Preview Application Queries',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'Preview Record',
                        action: 'edit',
                        childXtype: '',
                        winTitle: 'Preview Application Queries',
                        winWidth: '40%',
                        isReadOnly: 1,
                        handler: 'previewproductApplicationQueries'
                    },{
                        text: 'Application Documents',
                        iconCls: 'x-fa fa-file',
                        tooltip: 'Application Documents',
                        action: 'edit',
                        childXtype: '',
                        winTitle: 'Application Documents',
                        winWidth: '40%',
                        isReadOnly: 1,
                        document_type_id: '',
                        handler: 'showPreviousUploadedDocs'
                    }
                ]
            }
        }
    }]
});
