Ext.define('Admin.view.Enforcement.views.grids.investigation.ApprovalDestructionProductsGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'approvalDestructionProductsGrid',
	itemId: 'approvalDestructionProductsGrid',
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
            pnl = grid.up('destructionPnl'),
            module_id = pnl.down('hiddenfield[name=module_id]').getValue(),
            application_code = pnl.down('hiddenfield[name=active_application_code]').getValue(),
            workflow_stage_id = pnl.down('hiddenfield[name=workflow_stage_id]').getValue(),
            store = this.getStore();
        store.removeAll();
        store.getProxy().extraParams = {
            application_code: application_code,
            module_id: module_id,
            workflow_stage_id: workflow_stage_id
        }
    }
},
]
,
selModel:{
    selType: 'checkboxmodel',
    mode: 'MULTI'
},
    features: [{
        ftype: 'searching',
        minChars: 2,
        // mode: 'local'
    },
],
listeners: {
    beforerender: {
        fn: 'setGridStore',
        config: {
            pageSize: 100,
            storeId: 'ProductDestructionApprovalGridStr',
            proxy: {
                url: 'enforcement/getseizureProductsListGrid',
                
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
		name: 'active_application_id'
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
            dataIndex: 'premise_name',
            text: 'Premise Name',
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
            dataIndex: 'common_name',
            text: 'Common Name',
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
            dataIndex: 'quantity',
            text: 'Quantity',
            flex: 1
        },
        //requisitioning_officer
        {
            xtype: 'gridcolumn',
            dataIndex: 'expiry_date',
            text: 'Product Expiry Date',
            flex: 1
        }, 
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'destruction_id',
	        text: 'Destruction Confirmation',
	        flex: 1,
	        renderer: function (value, metaData) {
	            if (value == 1) {
	                metaData.tdStyle = 'color:white;background-color:red';
	                return "destroyed";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:green';
	            	return "Not destroyed";
	            }else{
	            	metaData.tdStyle = 'color:white;background-color:gray';
	            	return "Pending";
	            }

	            
	        }
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
                    items: [
                        {
                            text: 'Destruction Confirmation',
                            iconCls: 'fa fa-check',
                            tooltip: 'Confirm Destruction',
                            action: 'edit',
                            childXtype: 'destructionApprovaFrm',
                            winTitle: 'Destruction Confirmation Form',
                            winWidth: '40%',
                            handler: 'showSezuireConfirmation',
                            stores: '[]'
                        }
                    ]
                }
            }
        }
],
});
