Ext.define('Admin.view.Enforcement.views.grids.Investigation.InquiryGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'inquiryGrid',
	itemId: 'inquiryGrid',
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
        beforeLoad: function () {
            this.up('grid').fireEvent('refresh', this.up('grid'));
        }
    }],
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
                remoteFilter: true,
                storeId: 'inquiryGridStr',
                proxy: {
                    url: 'enforcement/getInquiryDetails',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
    {
        xtype: 'button',
        text: 'Add inquiry',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        name:'add_inquiry',
        action: 'showOffenceChargeFrm',
        winTitle: 'inquiry Form',
        winWidth:'50%',
        childXtype: 'inquiryFrm',
        stores: '[]'
    },
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
		name: 'application_id'
	},
    {
		xtype: 'displayfield',
		name: 'offence_id'
	},
    {
		xtype: 'hiddenfield',
		name: 'offennce_type'
	},
    {
		xtype: 'hiddenfield',
		name: 'application_code'
	},
    //
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},],
    columns: [{
	    	xtype: 'rownumberer'
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'offence_charged',
	        text: 'Offence',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'inquiry_name',
	        text: 'Inquiry Name',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'inquiry_information',
	        text: 'Inquiry Information',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'inquiry_relevance',
	        text: 'Inquiry Relevance',
	        flex: 1,
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
                        text: 'Edit',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'Edit Record',
                        action: 'edit',
                        childXtype:'inquiryFrm',
                        winTitle: 'Edit',
                        winWidth:'70%',
                        handler: 'showEditConfigParamWinFrm',
                        stores: '[]'
                    },
                    {
                        text: 'Delete',
                        iconCls: 'x-fa fa-trash',
                        tooltip: 'Delete Record',
                        table_name: 'par_case_inquiry_details',
                        storeID: 'inquiryGridStr',
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
