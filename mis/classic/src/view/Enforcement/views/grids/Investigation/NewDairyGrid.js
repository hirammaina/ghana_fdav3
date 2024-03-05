Ext.define('Admin.view.Enforcement.views.grids.Investigation.NewDairyGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'newDairyGrid',
	itemId: 'newDairyGrid',
    cls: 'dashboard-todo-list',
	autoScroll: true,
	width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
    sub_module_id: 0,
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Applications Found',
	
},
plugins: [
	{
		ptype: 'gridexporter'
	}
],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '80%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function(){
            this.up('grid').fireEvent('refresh', this);
            // var grid = this.up('grid'),
            //     pnl = grid.up('enforcementinvestigationpnl'),
            //     application_code = pnl.down('hiddenfield[name=active_application_code]').getValue(),
            //     store = this.getStore();
            // store.removeAll();
            // store.getProxy().extraParams = {
            //     active_application_code: application_code,
            // }
        }
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        // mode: 'local'
    }],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 100,
                remoteFilter: true,
                storeId: 'newDairyGridStr',
                proxy: {
                    url: 'enforcement/getNewDiaryItem',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
        {
        xtype: 'button',
        text: 'Add Dairy Item',
        iconCls: 'x-fa fa-plus',
        name:'add',
        ui: 'soft-blue',
        handler: 'showAddConfigChargeWinFrm',
        winTitle: 'Investigation Diary Form',
        winWidth:'70%',
        childXtype: 'newDairyFrm',
        stores: '[]'
    },
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},],
    columns: [{
        xtype: 'rownumberer'
    },
   
    {
        xtype: 'gridcolumn',
        dataIndex: 'action',
        text: 'Action',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'remarks',
        text: 'Remarks',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'date',
        text: 'Action Date',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'action_time',
        text: 'Time',
        flex: 1,
    },
  
    {
        text: 'Options',
        xtype: 'widgetcolumn',
        width: 90,
        widget: {
            width: 75,
            textAlign: 'right',
            xtype: 'splitbutton',
            name:'options',
            iconCls: 'x-fa fa-th-list',
            ui: 'gray',
            menu: {
                xtype: 'menu',
                items: [

                          {
                            text: 'Edit',
                            iconCls: 'x-fa fa-edit',
                            tooltip: 'Edit Record',
                            action: 'edit',
                            childXtype:'newDairyFrm',
                            winTitle: 'Edit Dairy',
                            winWidth:'70%',
                            handler: 'showEditConfigParamWinFrm',
                            stores: '[]'
                    },
                    {
                        text: 'Delete',
                        iconCls: 'x-fa fa-trash',
                        tooltip: 'Delete Record',
                        table_name: 'par_new_diary',
                        storeID: 'newDairyGridStr',
                        action_url: 'configurations/deleteConfigRecord',
                        action: 'actual_delete',
                        handler: 'deleteRecord',
                    },
                ]
            }
        }
    }
],
});
