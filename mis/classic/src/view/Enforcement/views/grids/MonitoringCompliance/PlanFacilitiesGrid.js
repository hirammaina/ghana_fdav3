Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.PlanFacilitiesGrid',{
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'planFacilitiesGrid',
    height: Ext.Element.getViewportHeight() - 118,

    tbar: [
    {
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    },
    {
        xtype: 'hiddenfield',
        name: 'monitoring_plan_id'
    },
   
    {
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
                storeId: 'planFacilityGridStr',
                proxy: {
                    url: 'enforcement/getMonitoringPlanFacilityInformation'
                }
            },
            isLoad: true
        }
    },
bbar: [{
        xtype: 'pagingtoolbar',
        width: '80%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function(){
            var grid = this.up('grid'),
            pnl = grid.up('panel'),
            monitoring_plan_id = grid.down('hiddenfield[name=monitoring_plan_id]').getValue();

            store = this.getStore(),
        store.removeAll();
        store.getProxy().extraParams = {
            monitoring_plan_id: monitoring_plan_id,
        }
    }
    }],
    columns: [{
        xtype: 'rownumberer'
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'name',
        text:'Work Plan Name',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        text: 'Facility',
        dataIndex: 'facility_name',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'email',
        text:'Email',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'physical_address',
        text:'Physical Address',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'postal_address',
        text:'Postal Address',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'telephone',
        text:'Telephone',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'facility_region',
        text:'Region',
        flex: 1
    },
   
    // {
    //     text: 'Options',
    //     xtype: 'widgetcolumn',
    //     width: 90,
    //     widget: {
    //         width: 75,
    //         textAlign: 'right',
    //         xtype: 'splitbutton',
    //         name:'options',
    //         iconCls: 'x-fa fa-th-list',
    //         ui: 'gray',
    //         menu: {
    //             xtype: 'menu',
    //             items: [
    //                 {
    //                     text: 'Delete',
    //                     iconCls: 'x-fa fa-trash',
    //                     tooltip: 'Delete Record',
    //                     table_name: 'par_monitoring_product_information',
    //                     storeID: 'productInformationGridStr',
    //                     action_url: 'enforcement/genericDeleteRecord',
    //                     action: 'actual_delete',
    //                     handler: 'deleteRecord',
    //                 },
    //             ]
    //         }
    //     }
    // }
],
});
