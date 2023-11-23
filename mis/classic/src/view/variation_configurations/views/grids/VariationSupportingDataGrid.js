

/**
 * Created by Kip on 10/2/2018.
 */
Ext.define('Admin.view.variation_configurations.views.grids.VariationSupportingDataGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'variationconfigurationsvctr',
    xtype: 'variationsupportingdatagrid',
    cls: 'dashboard-todo-list',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
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
        childXtype: 'variationsupportingdatafrm',
        winTitle: 'Variations Supporting Data',
        winWidth: '40%',
        handler: 'showAddConfigParamWinFrm',
        stores: '[]'
    }, {
        xtype: 'exportbtn'
    }],
    plugins: [{
        ptype: 'gridexporter'
    }],
    export_title: 'Variation Conditions',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records'
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    }, {
        ftype: 'grouping',
        startCollapsed: false
    }],
    listeners: {
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 1000,
                storeId: 'variationsupportingdatastr', 
                groupField: 'module_name',
                proxy: {
                    url: 'commonparam/getCommonParamFromTable',
                    extraParams: {
                        table_name: 'par_variationsupporting_datadocs'
                    }
                }
            },
            isLoad: true
        }
    },
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'id',
        text: 'Ref ID',
        width: 100
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'module_name',
        text: 'Module Name',
        flex: 1,
        tdCls: 'wrap-text'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'sub_module_name',
        text: 'Sub-Module Name',
        flex: 1,
        tdCls: 'wrap-text'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'product_category',
        text: 'Product Category',
        flex: 1,
        tdCls: 'wrap-text'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'section_name',
        text: 'Section Name',
        flex: 1,
        tdCls: 'wrap-text'
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'variation_category',
        text: 'Variation Category',
        flex: 1,
        tdCls: 'wrap-text'
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'name',
        text: 'Name',
        flex: 1,
        tdCls: 'wrap-text'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'code',
        text: 'code',
        flex: 1,
        tdCls: 'wrap-text'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'description',
        text: 'Description',
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
                    childXtype: 'variationsupportingdatafrm',
                    winTitle: 'Variation Supporting Documents',
                    winWidth: '40%',
                    handler: 'showEditConfigParamWinFrm',
                    stores: '[]'
                }, {
                    text: 'Disable',
                    iconCls: 'x-fa fa-repeat',
                    table_name: 'par_variationsupporting_datadocs',
                    storeID: 'variationsupportingdatastr',
                    action_url: 'workflow/softDeleteWorkflowRecord',
                    action: 'soft_delete',
                    handler: 'doDeleteConfigWidgetParam'
                }, {
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'par_variationsupporting_datadocs',
                    storeID: 'variationsupportingdatastr',
                    action_url: 'workflow/deleteWorkflowRecord',
                    action: 'actual_delete',
                    handler: 'doDeleteConfigWidgetParam',
                    hidden: Admin.global.GlobalVars.checkForProcessVisibility('actual_delete')

                }, {
                    text: 'Enable',
                    iconCls: 'x-fa fa-undo',
                    tooltip: 'Enable Record',
                    table_name: 'par_variationsupporting_datadocs',
                    storeID: 'variationsupportingdatastr',
                    action_url: 'workflow/undoWorkflowSoftDeletes',
                    action: 'enable',
                    disabled: true,
                    handler: 'doDeleteConfigWidgetParam'
                }
                ]
            }
        }
    }]
});
