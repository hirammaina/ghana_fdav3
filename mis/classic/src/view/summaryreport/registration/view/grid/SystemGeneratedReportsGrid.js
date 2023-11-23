Ext.define('Admin.view.summaryreport.registration.view.grid.SystemGeneratedReportsGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'systemgeneratedreportsGrid',
    controller: 'registrationreportviewctr',
    height: Ext.Element.getViewportHeight() - 118,
    listeners: {
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 100,
                storeId: 'systemgeneratedreportStr',
                groupField: 'file_type',
                proxy: {
                    url: 'summaryreport/getApplicationReceiptsReport'
                }
            },
            isLoad: true
        }
           
    },
    tbar: [{
        xtype: 'hiddenfield',
        fieldLabel: 'module_id',
        name: 'module_id',
        readOnly: true
    },{
        xtype: 'textfield',
        fieldLabel: 'Reference',
        name: 'reference_no',
        readOnly: true
    },{
        xtype: 'combo',
        fieldLabel: 'Applicable Documents',
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        name: 'doc_type',
        queryMode: 'local',
        readOnly: true,
        labelStyle: "font-weight:bold",
        listeners: {
            beforerender: {
                    fn: 'setOrgConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                        url: 'configurations/getConfigParamFromTable',
                        extraParams: {
                            table_name: 'par_document_types'
                        }
                       }
                    },
                    isLoad: true
                }
        }
       }],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        //store: 'uploadedDocStr', set by controller
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {
             var filters=this.up('grid'),
                 type=filters.down('combo[name=doc_type]').getValue(),
                 module_id=filters.down('hiddenfield[name=module_id]').getValue(),
                 Reference=filters.down('textfield[name=reference_no]').getValue(),
                 Store=this.getStore();

            Store.getProxy().extraParams = {
                        doc_type:type,
                        module_id:module_id,
                        reference_no:Reference
                }
            }
    }],
    columns: [
   {
        xtype: 'gridcolumn',
        dataIndex: 'receipt_no',
        text: 'Receipt No',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'manual_receipt_no',
        text: 'Manual Receipt No',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'generated_by',
        text: 'Generated By',
        flex: 1,
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'amount_paid',
        text: 'Amount Paid',
        flex: 1,
    }, {
        xtype: 'datecolumn',
        dataIndex: 'trans_date',
        text: 'Transaction Date',
        format: 'Y-m-d',
        flex: 1
    }, {
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
                    text: 'View',
                    iconCls: 'x-fa fa-eye',
                    handler: 'previewGeneratedReceipts',
                    download: 0
                }]
            }
        }
    }],
    
    
});