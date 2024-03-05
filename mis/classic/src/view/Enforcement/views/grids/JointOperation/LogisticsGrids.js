Ext.define('Admin.view.Enforcement.views.grids.LogisticsGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'logisticsgrid',
   
	tbar: [{
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    },{
        xtype: 'button',
        text: 'Add',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        name: 'add_logistics',
        handler: 'showAddConfigParamWinFrm',
        winTitle: 'Logistics Details',
        childXtype: 'logisticsform',
        winWidth: '50%',
        stores: '[]'
    }, {
        xtype: 'exportbtn'
    }],
    listeners:{
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                autoLoad: false,
                defaultRootId: 'root',
                enablePaging: true,
                storeId: 'logisticsstr',
                proxy: {
                    url: 'enforcement/getJointOperationLogistics'
                }
            },
            isLoad: true
        }
    },
	 bbar: [{
			xtype: 'pagingtoolbar',
			width: '100%',
			displayInfo: true,
			displayMsg: 'Showing {0} - {1} of {2} total records',
			emptyMsg: 'No Records',
            beforeLoad: function () {
                var store = this.getStore(),
                    grid = this.up('grid'),
                    pnl = grid.up('panel'),
                    mainTabPnl = pnl.up('#contentPanel');
                    //console.log(mainTabPnl);
                    activeTab = mainTabPnl.getActiveTab();
                    application_code = activeTab.down('hiddenfield[name=active_application_code]').getValue(),
                    store = this.getStore();
                store.getProxy().extraParams = {
                    active_application_code: application_code,
                    
                }
            }
			
		}],
    
    columns: [ 
    {
        xtype: 'gridcolumn',
        text: ' Name of Item',
        dataIndex: 'name',
        flex: 1
    },      
	{
        xtype: 'gridcolumn',
        text: ' Description',
        dataIndex: 'description',
        flex: 1
    }, 
    {
        xtype: 'gridcolumn',
        text: 'Quantity',
        dataIndex: 'quantity',
        flex: 1
    },
	{
        xtype: 'gridcolumn',
        text: 'Approximate Amount',
        dataIndex: 'amount',
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
                    text: 'Edit',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'Edit Record',
                    action: 'edit',
                    childXtype: 'logisticsform',
                    winTitle: 'Edit Logistics',
                    winWidth: '40%',
                    handler: 'showEditConfigParamWinFrm',
                    stores: '[]'
                }, {
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'par_joint_logistics_details',
                    storeID: 'logisticsstr',
                    action_url: 'enforcement/genericDeleteRecord',
                    action: 'actual_delete',
                    handler: 'deleteRecord'
					
                }
                ]
            }
        }
    }
	
	
	]
});