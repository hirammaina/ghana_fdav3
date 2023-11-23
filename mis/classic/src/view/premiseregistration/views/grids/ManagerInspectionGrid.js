/**
 * Created by Kip on 11/1/2018.
 */
Ext.define('Admin.view.premiseregistration.views.grids.ManagerInspectionGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'premiseregistrationvctr',
    xtype: 'managerinspectiongrid',
    cls: 'dashboard-todo-list',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    appDetailsReadOnly:1,
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display',
        getRowClass: function (record, rowIndex, rowParams, store) {
            var is_enabled = record.get('is_enabled');
            if (is_enabled == 0 || is_enabled === 0) {
                return 'invalid-row';
            }
        }
        
    },
    selModel: {
        selType: 'checkboxmodel'
    },
    tbar:[{
        xtype: 'exportbtn'
    },
        {
            xtype: 'displayfield',
            value: 'Double click to view more details!!',
            hidden: true,
            fieldStyle: {
                'color':'green'
            }
        }
    ],
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
                    width: '40%',
                    table_name: 'tra_premises_applications',
                    managerInspection: 1,
                    beforeLoad: function () {
                        this.up('grid').fireEvent('refresh', this);
                    }
                },
                '->',
                {
                    xtype: 'button',
                    text: 'Save Inspection Scheduling Details',
                    ui: 'soft-blue',
                    toaster: 1,
                    iconCls: 'x-fa fa-save',
                    name: 'save_btn'
                },{
                    xtype: 'button',
                    text: 'Upload Concept Note',
                    iconCls: 'x-fa fa-upload',
                    ui: 'soft-purple',
                    //hidden: true,
                  //  name: 'save_btn',
                    reference_table_name: 'tra_premise_inspection_details',
                    table_name: 'tc_incepttionconcept_uploaddocuments',
                    handler: 'funcUploadTCMeetingtechnicalDocuments',
                    document_type_id: 23,
                    childXtype:'unstructureddocumentuploadsgrid',
                    winTitle: 'Concept Note Upload',
                    winWidth: '80%',
                    toaster: 0
                },
                {
                    xtype: 'button',
                    text: 'Submit Application(s)',
                    iconCls: 'x-fa fa-check',
                    ui: 'soft-purple',
                    name: 'submit_selected',
                    disabled: true,
                    toaster: 0,
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
    },{
        ftype: 'grouping',
        startCollapsed: false,
       
    }],
    listeners: {
        beforerender: {
            fn: 'setPremiseRegGridsStore',
            config: {
                pageSize: 10000,
                groupField: 'district_name',
                proxy: {
                    url: 'premiseregistration/getManagerInspectionApplications'
                }
            },
            isLoad: true
        },
        select: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                selCount = grid.getSelectionModel().getCount();
            if (selCount > 0) {
                grid.down('button[name=submit_selected]').setDisabled(false);
            }
        },
        deselect: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                selCount = grid.getSelectionModel().getCount();
            if (selCount < 1) {
                grid.down('button[name=submit_selected]').setDisabled(true);
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
        itemdblclick: 'showPremApplicationMoreDetailsOnDblClick'
    },
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'tracking_no',
        tdCls:'wrap-text',
        text: 'Tracking Number',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'reference_no',  tdCls:'wrap-text',
        text: 'Application No',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'premise_name',  tdCls:'wrap-text',
        text: 'Premise Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'telephone_no',  tdCls:'wrap-text',
        text: 'Telephone No',
        flex: 1
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'district_name',  tdCls:'wrap-text',
        text: 'District Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'sector_name',  tdCls:'wrap-text',
        text: 'Sector Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',  tdCls:'wrap-text',
        dataIndex: 'cell_name',
        text: 'Cell Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'physical_address',  tdCls:'wrap-text',
        text: 'Physical Address',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'applicant_name',  tdCls:'wrap-text',
        text: 'Applicant',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'date_received',  tdCls:'wrap-text',
        hidden: true,
        text: 'Date Received',
        flex: 1
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
                        text: 'Preview Details',
                        iconCls: 'x-fa fa-bars',
                        appDetailsReadOnly: 1,
                        handler: 'showPremApplicationMoreDetails'
                    },
                    {
                        text: 'Dismiss/Cancel Application',
                        iconCls: 'x-fa fa-thumbs-down',
                        handler: 'showApplicationDismissalForm'
                    }
                ]
            }
        }
    }]
});
