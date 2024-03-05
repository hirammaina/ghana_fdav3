/**
 */
 Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.MystreyShoppingLogGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'mystreyShoppingLogGrid',
    controller: 'enforcementvctr',
    autoScroll: true,
    height: Ext.Element.getViewportHeight() - 118,
    tbar: [
    {
        xtype: 'exportbtn'
    }, {
        xtype: 'hiddenfield',
        name: 'application_code'
    }, {
        xtype: 'hiddenfield',
        name: 'module_id'
    }, {
        xtype: 'button',
        text: 'Add Mystrey Shopping',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        handler: 'showAddConfigParamWinFrm',
        winTitle: 'Mystrey Shopping Form',
        winWidth:'50%',
        childXtype: 'mystreyShoppingFrm',
        stores: '[]'
    },],
    plugins: [{
        ptype: 'gridexporter'
    }],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                storeId: 'mystreyShoppingLogGridStr',
                proxy: {
                    url: 'enforcement/getMystreyShoppingLogApplication'
                }
            },
            isLoad: true
        }
    },
    columns: [
    {
        xtype: 'rownumberer',
        text: 'S/N'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'name',
        text: 'Name/Identity',
        flex: 1,
        tdCls: 'wrap'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'description',
        text: 'Description',
        flex: 1,
        tdCls: 'wrap'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'date_of_shopping',
        text: 'Date of  Shopping',
        flex: 1,
        tdCls: 'wrap'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'time_of_shopping',
        text: 'Time of  Shopping',
        flex: 1,
        tdCls: 'wrap'
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
                items: [{
                    text: 'Edit Mystrey Shopping Detail',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'Edit Record',
                    action: 'edit',
                    handler: 'editmystreyshoppingDetails',
                    stores: '[]'
                },{
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'par_mystrey_shopping_details',
                    storeID: 'mystreyShoppingLogGridStr',
                    action_url: 'enforcement/genericDeleteRecord',
                    action: 'actual_delete',
                    handler: 'deleteRecord'
                }
                ]
            }
        },
    }
],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {
            var grid = this.up('grid'),
                store = this.getStore(),
                application_code = grid.down('hiddenfield[name=application_code]').getValue(),
                module_id = grid.down('hiddenfield[name=module_id]').getValue();

            store.getProxy().extraParams = {
                application_code: application_code,
                module_id: module_id
            };
        }
    }]
});
