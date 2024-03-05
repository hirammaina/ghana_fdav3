Ext.define('Admin.view.Enforcement.views.grids.investigation.DestructionProductsGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'destructionProductsGrid',
	itemId: 'destructionProductsGrid',
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
    }
},
'->',
{
    xtype: 'button',
    text: 'Save Details',
    ui: 'soft-purple',
    toaster: 1,
    iconCls: 'x-fa fa-save',
    name: 'save_btn'
},
{
    text: 'Submit Application',
    ui: 'soft-blue',
    iconCls: 'fa fa-check',
    name: 'process_submission_btn',
    storeID: 'saveDestructionPlanDetails',
    table_name: '',
    winWidth: '50%'
}
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
            storeId: 'ProductDestructionGridStr',
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
        //requisitioning_officer
        {
            xtype: 'gridcolumn',
            dataIndex: 'expiry_date',
            text: 'Product Expiry Date',
            flex: 1
        },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'seizure_confirmation_id',
	        text: 'Seizure Confirmation',
	        flex: 1,
	        renderer: function (value, metaData) {
	            if (value == 1) {
	                metaData.tdStyle = 'color:white;background-color:green';
	                return "Siezed";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "Not Seized";
	            }else{
	            	metaData.tdStyle = 'color:white;background-color:gray';
	            	return "Pending";
	            }

	            
	        }
	    },
      
],
});
