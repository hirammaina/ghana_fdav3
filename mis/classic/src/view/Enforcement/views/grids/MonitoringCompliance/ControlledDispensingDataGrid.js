Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.ControlledDispensingDataGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'controlledDispensingDataGrid',
    controller: 'enforcementvctr',
    height: Ext.Element.getViewportHeight() - 118,

    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Dispensing Section 38(2) Data Found',
	
    },
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 100,
                storeId: 'controlledDispensingDataGrid',
                enablePaging: true,
                proxy: {
                    url: 'enforcement/getControlledDispensingInformation'
                }
            },
            isLoad: true
        },
    },
    tbar: [
    {
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    }, {
        xtype: 'button',
        text: 'Add Professional',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        formBind: true,
        name: 'add_controlled_personnel',
        winTitle: 'Dispensing Section 38(2) Details',
        childXtype: 'controlledDispensingfrm',
        handler: 'showAddConfigParamWinFrm',
        winWidth: '60%',
        stores: '[]'
    }],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function(){
            this.up('grid').fireEvent('refresh', this);
        }
    }],
    columns: [
    {
        xtype: 'rownumberer',
        text:'Sample No',
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'medicine_name',
        text: 'Dispensed medicine`s name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'product_schedule_name',
        text: 'Schedule of Medicine',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'dispensers_name',
        text: 'Dispensers Name',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'reg_number',
        text: 'BHPC',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'dispenser_authorized_name',
        text: 'Dispenser Authorization',
        flex: 1
    },
    {
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
                    text: 'Edit Dispensing Section 38(2)',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'Edit Record',
                    action: 'edit',
                    handler: 'editControlledDispensingInformation',
                    stores: '[]'
                },{
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'par_controlled_dispensing_data',
                    storeID: 'controlledDispensingDataGrid',
                    action_url: 'enforcement/genericDeleteRecord',
                    action: 'actual_delete',
                    handler: 'deleteRecord'
                }
                ]
            }
        },
    }
]
});