
/**
 * Created by Softclans.
 */
Ext.define('Admin.view.premiseregistration.views.grids.PremiseLicenseReviewMeetingGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'premiselicensereviewmeetinggrid',//productReviewTCMeetingGrid
    table_name: 'tra_premises_applications',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display',
        getRowClass: function (record, rowIndex, rowParams, store) {
            var license_review_remarks_id = record.get('license_review_remarks_id');
            if (license_review_remarks_id >0) {
                return 'valid-row';
            } else {
                return 'invalid-row';
            }
        }
    },
    listeners: {
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 10000,
                storeId: 'premiseReviewTCMeetingStr',
                proxy: {
                    url: 'premiseregistration/getPremiseTcReviewMeetingApplications'//getClinicalTrialManagerMeetingApplications
                }
            },
            isLoad: false
        }, select: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                selCount = grid.getSelectionModel().getCount();
            if (selCount > 0) {
                grid.down('button[name=submit_selected]').setDisabled(false);
            }
        },
        beforeselect: function (sel, record, index, eOpts) {
            var license_review_remarks_id = record.get('license_review_remarks_id');
            if (license_review_remarks_id >0) {
                return true;
            } else {
                return false;
            }
        },
        deselect: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                selCount = grid.getSelectionModel().getCount();
            if (selCount < 1) {
                grid.down('button[name=submit_selected]').setDisabled(true);
            }
        }
    }, selModel: {
        selType: 'checkboxmodel',
        mode: 'MULTI'
    },
    tbar: [{
        xtype: 'exportbtn'
    }, {
        xtype: 'tbspacer'
    }],
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'reference_no',
        tdCls: 'wrap-text',
        text: 'Reference No',
        flex: 1
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'premise_name',tdCls: 'wrap-text',
        text: 'Premise Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'region_name',
        text: 'Region/Province Name',tdCls: 'wrap-text',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'region_name',tdCls: 'wrap-text',
        text: 'District Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'physical_address',tdCls: 'wrap-text',
        text: 'Physical Address',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'email_address',tdCls: 'wrap-text',
        text: 'Email Address',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'applicant_name',tdCls: 'wrap-text',
        text: 'Applicant',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'evaluator_recommendation',tdCls: 'wrap-text',
        text: 'Inspection Recommendation',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'tc_recomm',
        text: 'ILTC Recommendation',tdCls: 'wrap-text',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'certificate_expiry_statement',tdCls: 'wrap-text',
        text: 'Expiry Statement',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'license_review_remarks',tdCls: 'wrap-text',
        text: 'License Review Remarks',
        flex: 1
    },{
        xtype: 'widgetcolumn',
        width: 200,
        widget: {
            width: 160,
            textAlign: 'left',
            xtype: 'button',
            ui: 'soft-green',
            text: 'Preview License/Letter',
            iconCls: 'x-fa fa-certificate',
            backend_function: 'printPremiseRegistrationCertificate',
            handler: 'printColumnPremiseCertificate'
        }
    },  {
        xtype: 'widgetcolumn',
        width: 200,
        tdCls: 'wrap-text',
        widget: {
            width: 160,
            textAlign: 'left',
            xtype: 'button',
            ui: 'soft-red',
            text: 'Premises License Remarks',
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
    },{
        xtype: 'widgetcolumn',
        width: 200,
        widget: {
            width: 160,
            textAlign: 'left',
            xtype: 'button',
            iconCls: 'x-fa fa-check',
                ui: 'soft-green',
            text: 'Return Back', winWidth: '60%',
            table_name: 'tra_premises_applications',
            action: 'process_returnsubmission_btn',
            storeID: 'premiseReviewTCMeetingStr',

        }
    },  {
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
                items: [{
                    text: 'License Recommendations  Details',
                    iconCls: 'x-fa fa-retweet',
                    handler: 'showTcRecommendation',
                    childXtype: 'premisestcrecommendationpnl',
                    winTitle: 'ILTC Recommendation',
                    winWidth: '70%',
                    isReadOnly: true,
                    stores: '["tcrecommendationdecisionsstr"]'
                },{
                    text: 'Preview & Edit Application Details',
                    iconCls: 'x-fa fa-eye',
                    tooltip: 'Preview Record',
                    appDetailsReadOnly: 0,
                    handler: 'showPremApplicationMoreDetails'
                },{
                        text: 'Inspection',
                        iconCls: 'x-fa fa-exchange',
                        menu: {
                            xtype: 'menu',
                            items: [
                               
                                {
                                    text: 'Documents',
                                    iconCls: 'x-fa fa-upload',
                                    childXtype: 'premregappprevdocuploadsgenericgrid',
                                    winTitle: 'Inspection uploaded Documents',
                                    winWidth: '80%',
                                    handler: 'showPreviousUploadedDocs',
                                    target_stage: 'inspection'
                                },
                               
                                {
                                    text: 'Inspection Details',
                                    iconCls: 'x-fa fa-bars',
                                    childXtype: 'inspectiondetailstabpnl',
                                    winTitle: 'Inspection Details',
                                    winWidth: '60%',
                                    name: 'inspection_details',
                                    stores: '[]',
                                    isReadOnly: 1,
                                    handler: 'showInspectionDetails'
                                }
                            ]
                        }
                    }, {
                    text: 'Application Documents',
                    iconCls: 'x-fa fa-file',
                    tooltip: 'Application Documents',
                    action: 'edit',
                    childXtype: '',
                    winTitle: 'Application Documents',
                    winWidth: '40%',
                    isReadOnly: 1,
                    handler: 'funcPrevGridApplicationDocuments'
                }
                ]
            }
        }
    }]
});