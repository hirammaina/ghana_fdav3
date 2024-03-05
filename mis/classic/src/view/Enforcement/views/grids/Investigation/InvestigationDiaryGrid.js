Ext.define('Admin.view.Enforcement.views.grids.Investigation.InvestigationDiaryGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'investigationdiarygrid',
	itemId: 'investigationdiarygrid',
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
        // beforeLoad: function(){
		// 	var grid = this.up('grid'),
        //         pnl = grid.up('enforcementinvestigationpnl');
        //         console.log(pnl);
        //         //application_id = pnl.down('hiddenfield[name=application_id]').getValue(),
        //         active_application_code= pnl.down('hiddenfield[name=active_application_code]').getValue(),
        //         console.log(active_application_code);
        //         store = this.getStore();
        //     store.removeAll();
        //     store.getProxy().extraParams = {
        //        // application_id: application_id
        //        active_application_code:active_application_code
        //     }
        // }
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        // mode: 'local'
    },
    // {
    //     ftype: 'grouping',
    //     startCollapsed: true,
    //     groupHeaderTpl: ': {[values.rows[0].data.charge_regulation]} [{rows.length}]',
    //     hideGroupedHeader: true,
    //     enableGroupingMenu: false
    // }
],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 100,
                remoteFilter: true,
                //groupField: 'charge_regulation',
                storeId: 'investigationDiaryStr',
                proxy: {
                    url: 'enforcement/getInvestigationDiaryDetails',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
    //     {
    //     xtype: 'button',
    //     text: 'Add Charge and Witness Item',
    //     iconCls: 'x-fa fa-plus',
    //     ui: 'soft-blue',
    //     //handler: 'showAddWitnessFrm',
    //     action:'addWitnessFrm',
    //     winTitle: 'Charge and Witness Form',
    //     winWidth:'70%',
    //     childXtype: 'witnessfrm',
    //     stores: '[]',
    //     //hidden:true
    // },
    {
        xtype: 'button',
        text: 'Add Charge',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        name:'add_charge',
        //handler: 'showAddConfigChargeWinFrm',
        action: 'showOffenceChargeFrm',
        winTitle: ' Charge1 Form',
        winWidth:'70%',
        childXtype: 'chargefrm',
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
        //offence_charged
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'offence_charged',
	        text: 'Offence Reported',
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
	        text: 'Charge Regulation',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'charge_elements',
	        text: 'Charge Elements',
	        flex: 1,
	    },
        {
	        xtype: 'gridcolumn',
	        dataIndex: 'charge_section',
	        text: 'Charge Sections',
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
                        childXtype:'chargefrm',
                        winTitle: 'Edit Charge',
                        winWidth:'70%',
                        handler: 'showEditConfigParamWinFrm',
                        stores: '[]'
                    },
                    {
                        text: 'Delete',
                        iconCls: 'x-fa fa-trash',
                        tooltip: 'Delete Record',
                        table_name: 'par_investigation_diary',
                        storeID: 'investigationDiaryStr',
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
