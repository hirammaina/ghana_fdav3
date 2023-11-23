Ext.define('Admin.view.trader_accounts.views.panels.ApplicationUploadFroofAuthorisationGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'applicationuploadproofauthorisationgrid',
    autoScroll: true,
    autoHeight: true,
    controller: 'traderaccountsvctr',
    width: '100%',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display'
    },
    scroll: true,
    autoHeight: true,
    width: '100%',
    requires: [
        'Ext.button.Button',
        'Ext.menu.Menu',
        'Ext.toolbar.Paging',
        'Admin.view.plugins.Searching',
        'Ext.grid.*'
    ],
    listeners: {
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 100, remoteFilter: true,
                storeId: 'applicationuploadproofauthorisationgridstr',
                proxy: {
                    url: 'tradermanagement/getApplicationUploadProofAuthorisation'
                }
            },
            isLoad: true
        },
    },
    tbar:[{
            text:'Add Upload Option New Authorisation',
            iconCls:'x-fa fa-plus',
            margin:5,ui: 'soft-purple',
            childXtype: 'applicationuploadproofauthorisationfrm',
            winTitle:' Upload Payment Proof Authorisation',
            winWidth: '60%',
            handler:'funcAdduploadproofauthorisationfrm'
    }],
    columns: [{
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
                    text: 'Edit Upload Option New Authorisation',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'View/Set-Up Add Upload Option New Authorisation',
                    action: 'edit',
                    winTitle:'Upload Option New Authorisation',
                    childXtype: 'applicationuploadproofauthorisationfrm',
                    winTitle:' Upload Payment Proof Authorisation',
                    winWidth: '60%',
                    handler: 'funcEdituploadproofauthorisationfrm',
                    stores: '[]'
                }]
            }
        }
    },{
        header: 'Upload Proof Auth Type',
        dataIndex: 'payuploadproofauth_type',
        flex: 1
    },{
        header: 'Trader Name',
        dataIndex: 'name',
        flex: 1
    }, {
        header: 'Account No',
        dataIndex: 'identification_no',
        flex: 1
    },  {
        header: 'Authorised From Date',
        dataIndex: 'authorised_from',
        flex: 1
    },   {
        header: 'Authorised To Date',
        dataIndex: 'authorised_to',
        flex: 1
    },  {
        header: 'Reference No',
        dataIndex: 'reference_no',
        flex: 1
    },    {
        header: 'Requested By',
        dataIndex: 'requested_by',
        flex: 1
    }, {
        header: 'Authorised From',
        dataIndex: 'authorised_from',
        flex: 1
    }, {
        header: 'Authorised To',
        dataIndex: 'authorised_by',
        flex: 1
    }, {
        header: 'Authorisation Status',
        dataIndex: 'authorisation_status',
        flex: 1
    },{
        header: 'Effected On',
        dataIndex: 'effected_on',
        flex: 1
    },{
        header: 'Effected By',
        dataIndex: 'effected_by',
        flex: 1
    }],
    features:[{
        ftype:'searching'
    }],
    plugins: [{
        ptype: 'filterfield'
    },{
        ptype: 'gridexporter'
    }],
    export_title: 'Upload Option New Authorisation',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad:function(){
            this.up('grid').fireEvent('refresh', this);
        }
    }]
});