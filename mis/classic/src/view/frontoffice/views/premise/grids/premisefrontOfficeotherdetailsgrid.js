
Ext.define('Admin.view.frontoffice.premise.grids.premisefrontOfficeotherdetailsgrid', {
    extend: 'Ext.grid.Panel',
    controller: 'spreadsheetpremisectr',
    xtype: 'premisefrontOfficeotherdetailsgrid',
    itemId:'premisefrontOfficeotherdetailsgrid',
    cls: 'dashboard-todo-list',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
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
    tbar: [{
            xtype: 'hiddenfield',
            name: 'isReadOnly'
        }, {
            xtype: 'hiddenfield',
            name: 'is_temporal',
            value: 0
        }, 
        {
            xtype: 'hiddenfield',
            name: 'premise_id'
        }, {
            xtype: 'button',
            text: 'Add',
            iconCls: 'x-fa fa-plus',
            ui: 'soft-green',
            bind: {
                hidden: '{isReadOnly}'
             },
            handler: 'showPfAddPremiseOtherdetailsWinFrm',
            winTitle: 'Premise Other Details',
            childXtype: 'premisepfotherdetailsfrm',
            storeID: 'businesstypesstr',
            winWidth: '35%',
            stores: '[]'
        }, {
            xtype: 'exportbtn'
        }],
        plugins: [
            {
                ptype: 'gridexporter'
            }
        ],
        export_title: 'Premise Other Details',
         bbar: [{
            xtype: 'pagingtoolbar',
            width: '100%',
            displayInfo: true,
            displayMsg: 'Showing {0} - {1} of {2} total records',
            emptyMsg: 'No Records',
            beforeLoad: function () {
                var store = this.getStore(),
                grid=this.up('grid'),
                panel=grid.up('panel'),
                mainpnl=panel.up('panel'),
                parentpanel=mainpnl.up('panel'),
                premise_id = parentpanel.down('hiddenfield[name=premise_id]').getValue();
                grid.down('hiddenfield[name=premise_id]').setValue(premise_id);
               
                    store.getProxy().extraParams = {
                    premise_id:premise_id
                  };
            }
        }],
        features: [{
            ftype: 'searching',
            minChars: 2,
            mode: 'local'
        }],
        listeners: {
        beforerender: {
            fn: 'setPremiseRegGridsStore',
            config: {
                pageSize: 1000,
                storeId: 'pfpremiseotherdetailsstr',
                proxy: {
                    url: 'premiseregistration/getPremiseOtherDetails'
                }
            },
            isLoad: true
        }
    },
        columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'business_type_detail',
        text: 'Business Type Details',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'business_type',
        text: 'Business Type',
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
                   text: 'Edit',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'Edit Record',
                    action: 'edit',
                    childXtype: 'premisepfotherdetailsfrm',
                    winTitle: 'Premise Other Details',
                    storeID: 'pfpremiseotherdetailsstr',
                    winWidth: '35%',
                    handler: 'showPfEditPremiseOtherdetailWinFrm',
                    stores: '[]'
                }, {
                     text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'tra_premises_otherdetails',
                    storeID: 'pfpremiseotherdetailsstr',
                    action_url: 'premiseregistration/deletePremiseRegRecord',
                    action: 'actual_delete',
                    handler: 'deletePfPremiseOtherdetails'
                }]
            }
        }
    }]
});

