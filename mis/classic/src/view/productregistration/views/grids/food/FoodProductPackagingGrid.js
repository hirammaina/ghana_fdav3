
/**
 * Created by Kip on 9/22/2018.
 */
Ext.define('Admin.view.productregistration.views.grids.food.FoodProductPackagingGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'productregistrationvctr',
    xtype: 'foodproductpackaginggrid',
    itemId: 'foodproductpackaginggrid',
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
        xtype: 'button',
        text: 'Add',
        iconCls: 'x-fa fa-plus',
        action: 'add',
        ui: 'soft-green',
        childXtype: 'foodproductpackagingfrm',
        winTitle: 'Product Packaging Details',
        winWidth: '40%',
        handler: 'showAddProductOtherdetailsWinFrm',
        stores: '[]',
        bind: {
            hidden: '{isReadOnly}'  // negated
        }

    }, {
        xtype: 'exportbtn'
    }, {
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    }],
    plugins: [
        {
            ptype: 'gridexporter'
        }
    ],
    export_title: 'Drugs Packaging',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {
            this.up('foodproductpackaginggrid').fireEvent('refresh', this);
        }
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    }],
    
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'container_type',
        text: 'Container Type',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'container_name',
        text: 'Primary Container',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'container_material',
        text: 'Container Material',
        flex: 1,
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'closure_material',
        text: 'Closure Material',
        flex: 1,
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'seal_type',
        text: 'Seal Type',
        flex: 1,
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'retail_packaging_size',
        text: 'Retail Packaging Size',
        flex: 1,
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'packaging_units',
        text: 'packaging Units',
        flex: 1,
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
                    childXtype: 'foodproductpackagingfrm',
                    winTitle: 'Product Packaging',
                    winWidth: '40%',
                    handler: 'showEditProductOtherdetailWinFrm',
                    stores: '[]',
                    bind: {
                        hidden: '{isReadOnly}'  // negated
                    }
                }, {
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'tra_product_packaging',
                    storeID: 'foodproductPackagingdetailsstr',
                    action_url: 'productregistration/onDeleteProductOtherDetails',
                    action: 'actual_delete',
                    handler: 'doDeleteProductOtherdetails',
                    hidden: Admin.global.GlobalVars.checkForProcessVisibility('actual_delete'),
                    bind: {
                        hidden: '{isReadOnly}'  // negated
                    }
                }]
            }
        }
    }]
});
