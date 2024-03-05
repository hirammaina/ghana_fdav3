Ext.define('Admin.view.Enforcement.views.grids.Investigation.ViewInvestigationdiaryGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'viewInvestigationdiaryGrid',
	itemId: 'viewInvestigationdiaryGrid',
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
            //     //pnl = grid.up('enforcementinvestigationpnl');
            //     //console.log(pnl);
            //     active_application_code = grid.down('hiddenfield[name=active_application_code]').getValue(),
            //     store = this.getStore();
            // store.removeAll();
            // store.getProxy().extraParams = {
            //     active_application_code: active_application_code
            // }
        }
    }],
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
                remoteFilter: true,
                groupField: 'charge_regulation',
                storeId: 'investigationDiaryStr',
                proxy: {
                    url: 'enforcement/getInvestigationDiaryDetails',
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
		name: 'active_application_code'
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
	        dataIndex: 'offence_charged',
	        text: 'Offence',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'charge_details',
	        text: 'Potential Charge Details',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'charge_regulation',
	        text: 'Charge Act',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'current_information',
	        text: 'Current Information',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'witness_name',
	        text: 'Witness',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'witness_information',
	        text: 'Witness Information',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'inquiry_name',
	        text: 'Type Inquiry',
	        flex: 1,
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'action',
	        text: 'Action',
	        flex: 1
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'date',
	        text: 'Date',
	        flex: 1
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'officer',
	        text: 'Officer',
	        flex: 1
	    },
        {
            xtype: 'gridcolumn',
            dataIndex: 'application_status',
            text: 'Case Status',
            flex: 1
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'approval_decision_id',
            text: 'Case Decision',
            flex: 1,
            // renderer: function (value, metaData) {
            //     if (value == 1) {
            //         metaData.tdStyle = 'color:white;background-color:green';
            //         return "Approved";
            //     }else if(value == 2){
            //         metaData.tdStyle = 'color:white;background-color:red';
            //         return "Rejected";
            //     }else{
            //         metaData.tdStyle = 'color:white;background-color:gray';
            //         return "Pending";
            //     }         
            // }
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
                        text: 'View More Items',
                        iconCls: 'x-fa fa-eye',
                        tooltip: 'View More Details',
                        action: 'edit',
                        childXtype:'investigationdiaryfrm',
                        winTitle: 'Edit Dairy',
                        winWidth:'40%',
                        handler: 'showEditConfigParamWinFrm',
                        stores: '[]'
                    },
                    ]
                }
            }
        }
],
});
