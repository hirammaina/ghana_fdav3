Ext.define('Admin.view.Enforcement.views.grids.Investigation.WitnessGrid', {
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'witnessgrid',
	itemId: 'witnessgrid',
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
            this.up('witnessgrid').fireEvent('refresh', this); 
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
                storeId: 'witnessGridStr',
                proxy: {
                   url: 'enforcement/getSuspectedOffenceDetails',
                }
            },
            isLoad: true
        },
	
    },
    tbar: [
    {
        xtype: 'button',
        text: 'Add Offence ',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        name: 'add_offence',
        handler: 'showAddConfigChargeWinFrm',
        winTitle: 'Offence  Details Form',
        winWidth:'70%',
        childXtype: 'newOffenceFrm',
        stores: '[]'
    },
	{
		xtype: 'hiddenfield',
		name: 'enforcement_id'
	},
	{
		xtype: 'hiddenfield',
		name: 'report_type_id'
	},
    
    ,],
    columns: [{
	    	xtype: 'rownumberer'
	    }, {
            xtype: 'gridcolumn',
            dataIndex: 'offennce_type',
            text: 'Offence Type',
            flex: 1,
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'details',
            text: 'Offence Details',
            flex: 1,
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'offence_date',
            text: 'Date Offence Reported',
            flex: 1,
        },{
	        xtype: 'gridcolumn',
	        dataIndex: 'created_on',
	        text: 'Reported Date',
	        flex: 1
	    },{
	        xtype: 'gridcolumn',
	        dataIndex: 'created_by',
	        text: 'Reported By',
	        flex: 1,
            hidden:true
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
                                //   {
                                //     text: 'Add Pontential Charge',
                                //     iconCls: 'x-fa fa-plus',
                                //      ui: 'soft-blue',
                                //       handler: 'showAddOffenceChargeWitnessWinGrid',
                                //       winTitle: 'Case Pontential Charges',
                                //       winWidth:'60%',
                                //      // childXtype: 'investigationdiarygrid',
                                //      childXtype: 'diaryPnl',
                                //       stores: '[]'
                                //       },
                                    //   {
                                    //     text: 'Add Witness Details',
                                    //     iconCls: 'x-fa fa-plus',
                                    //      ui: 'soft-blue',
                                    //       handler: 'showAddOffenceChargeWitnessWinGrid',
                                    //       winTitle: 'Investigation Diary',
                                    //       winWidth:'60%',
                                    //      // childXtype: 'investigationdiarygrid',
                                    //      childXtype: 'diaryPnl',
                                    //       stores: '[]'
                                    //       },
                                    //       {
                                    //         text: 'Add Avenue Of Inquiry',
                                    //         iconCls: 'x-fa fa-plus',
                                    //          ui: 'soft-blue',
                                    //           handler: 'showAddOffenceChargeWitnessWinGrid',
                                    //           winTitle: 'Investigation Diary',
                                    //           winWidth:'60%',
                                    //          // childXtype: 'investigationdiarygrid',
                                    //          childXtype: 'diaryPnl',
                                    //           stores: '[]'
                                    //           },
                        {
                            text: 'Edit Offence',
                            iconCls: 'x-fa fa-edit',
                            tooltip: 'Edit Record',
                            action: 'edit',
                            childXtype:'newOffenceFrm',
                            winTitle: 'Edit offence details',
                            winWidth:'70%',
                            handler: 'showEditConfigParamWinFrm',
                            stores: '[]'
                        },
                        {
                            text: 'Drop Offence Charge',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'par_suspected_offence',
                            storeID: 'witnessGridStr',
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
