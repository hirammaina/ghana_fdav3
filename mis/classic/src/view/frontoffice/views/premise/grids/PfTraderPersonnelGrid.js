

Ext.define('Admin.view.frontoffice.premise.grids.PfTraderPersonnelGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'spreadsheetpremisectr',
    xtype: 'pftraderpersonnelgrid',
    autoScroll: true,
    autoHeight: true,
    height: 450,
    frame: true,
    width: '100%',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display'
    },
    config:{
        moreDetails: 0
    },
    tbar: [{
        xtype: 'button',
        text: 'Add Personnel',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-green',
        bind: {
            hidden: '{isReadOnly}'
        },
        childXtype: 'personnelpfbasicinfofrm',
        winWidth: '40%',
        handler: 'showPfAddTraderPersonnelForm'
    }, {
        xtype: 'hiddenfield',
        name: 'trader_id'
    }, {
        xtype: 'hiddenfield',
        name: 'personnel_type'
    }, {
        xtype: 'exportbtn'
    }, {
        xtype: 'displayfield',
        value: 'Double click to select',       
        fieldStyle: {
            'color': 'green'
        }
    }],
    selModel: {
        selType: 'checkboxmodel',
        mode: 'SINGLE'
    },
    plugins: [
        {
            ptype: 'gridexporter'
        }
    ],
    export_title: 'Trader Personnel Details',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {
            var store = this.getStore(),
            grid = this.up('grid'),
            trader_id = grid.down('hiddenfield[name=trader_id]').getValue();
           
            store.getProxy().extraParams = {
                trader_id: trader_id
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
                storeId: 'traderpfpersonnelstr',
                proxy: {
                    url: 'premiseregistration/getTraderPersonnel'
                }
            },
            isLoad: true
        },
  
    },
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'contact_name',
        text: 'Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'contact_telephone_no',
        text: 'Telephone No',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'contact_email_address',
        text: 'Email address',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'contact_postal_address',
        text: 'Postal Address',
        flex: 1
    }, {
        xtype: 'widgetcolumn',
        text: 'Options',
        width: 90,
        bind: {
                hidden: '{isReadOnly}'
        },
        widget: {
            textAlign: 'left',
            xtype: 'splitbutton',
            ui: 'gray',
            width: 75,
            iconCls: 'x-fa fa-th-list',
            menu: {
                xtype: 'menu',
                items: [{
                    text: 'Edit',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'Edit Record',
                    action: 'edit',
                    handler: 'showEditBasePersonneldetail',
                    winWidth: '40%',
                    stores: '[]',
                    childXtype: 'personnelpfbasicinfofrm',
                    winTitle: 'Premise Personnel'
                }, {
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    table_name: 'tra_personnel_information',
                    storeID: 'traderpfpersonnelstr',
                    action_url: 'premiseregistration/deletePremiseRegRecord',
                    action: 'actual_delete',
                    handler: 'deletePfPremisePersonnelBasicdetails'
                }]
            }
        }
    }
    ]
});
