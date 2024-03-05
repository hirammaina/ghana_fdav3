Ext.define('Admin.view.Enforcement.views.grids.Investigation.ExhibitReportGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'exhibitReportGrid',
	itemId: 'exhibitReportGrid',
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
}],
selModel:{
    selType: 'checkboxmodel',
    mode: 'MULTI'
},
    features: [{
        ftype: 'searching',
        minChars: 2,
        // mode: 'local'
    },
    {
        ftype: 'grouping',
        startCollapsed: true,
        groupHeaderTpl: ': {[values.rows[0].data.charge_regulation]} [{rows.length}]',
        hideGroupedHeader: true,
        enableGroupingMenu: false
    }
],
listeners: {
    beforerender: {
        fn: 'setGridStore',
        config: {
            pageSize: 100,
            storeId: 'ReleaseProductGridStr',
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
            dataIndex: 'requisitioning_officer',
            text: 'Incharge Officer',
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
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'exhibit_request_approval_id',
	        text: 'Exhibit Approval',
	        flex: 1,
	        renderer: function (value, metaData) {
	            if (value == 1) {
	                metaData.tdStyle = 'color:white;background-color:green';
	                return "Exhibit Approved";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:red';
	            	return "Rejected";
	            }else{
	            	metaData.tdStyle = 'color:white;background-color:gray';
	            	return "Pending";
	            }

	            
	        }
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'released_confirmation_id',
	        text: 'Products Released Confirmation',
	        flex: 1,
	        renderer: function (value, metaData) {
	            if (value == 1) {
	                metaData.tdStyle = 'color:white;background-color:red';
	                return "Released";
	            }else if(value == 2){
	            	metaData.tdStyle = 'color:white;background-color:green';
	            	return "Not Released";
	            }else{
	            	metaData.tdStyle = 'color:white;background-color:gray';
	            	return "Pending";
	            }

	            
	        }
	    },
],
});
