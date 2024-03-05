Ext.define('Admin.view.Enforcement.views.grids.Investigation.JointSeizedProductsGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'jointSeizedProductsGrid',
	itemId: 'jointSeizedProductsGrid',
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
           var grid = this.up('grid'),
           pnl = grid.up('panel'),
           application_code = pnl.down('hiddenfield[name=active_application_code]').getValue(),
           store = this.getStore();
       store.removeAll();
       store.getProxy().extraParams = {
           application_code: application_code,
       }
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
                storeId: 'jointSeizedProductsGridStr',
                proxy: {
                  url: 'enforcement/getjointInvestigationReportedProducts',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},
    
    ,],
    columns: [
        {
	    	xtype: 'rownumberer'
	    },  
        {
            xtype: 'gridcolumn',
            dataIndex: 'joint_offence_id',
            text: 'Facility/Premise',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'brand_name',
            text: 'Product Brand Name',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'quantity',
            text: 'Quantity',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'batch_number',
            text: 'Batch Number',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'expiry_date',
            text: 'Product Expiry Date',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'manufacturer',
            text: 'Manufacturer',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'offence',
            text: 'Offence',
            flex: 1
        },
        {
            text: 'Options',
            xtype: 'widgetcolumn',
            width: 90,
            widget: {
                width: 75,
                textAlign: 'right',
                xtype: 'splitbutton',
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
                            childXtype:'jointOperationProductfrm',
                            winTitle: 'Product Details Form',
                            winWidth:'70%',
                            handler: 'showEditConfigParamWinFrm',
                            stores: '[]'
                        },
                        {
                            text: 'Delete',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'par_joint_seized_products',
                            storeID: 'jointSeizedProductsGridStr',
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
