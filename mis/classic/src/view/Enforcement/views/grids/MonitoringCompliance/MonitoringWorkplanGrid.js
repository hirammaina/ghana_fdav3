 Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.MonitoringWorkplanGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'enforcementvctr',
    xtype: 'monitoringworkplangrid',
    autoScroll: true,
    autoHeight: true,
    headers: false,
    width: '100%',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display',
        getRowClass: function (record, rowIndex, rowParams, store) {
            var is_enabled = record.get('is_enabled');
            if (is_enabled == 0 || is_enabled === 0) {
                return 'invalid-row';
            }
        }
    },
    tbar: [{
        xtype: 'button',
        text: 'Add Annual Wokplan',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        name: 'add_annual_workplan',
        handler: 'showAnnualWorkplanForm',
        winTitle: 'Annual Wokplan Details',
        childXtype: 'annualWorkplanFrm',
        stores: '[]'
    },{
        xtype: 'exportbtn'
    }],
    plugins: [
        {
            ptype: 'gridexporter'
        }
    ],
    export_title: 'Annual Workplan',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '80%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        // beforeLoad: function(){
        //     var store=this.getStore(),
        //         grid = this.up('grid'),
        //         homePnl = grid.up('panel'),
        //         containerPnl=homePnl.up('container'),
        //         section_id=containerPnl.down('hiddenfield[name=section_id]').getValue();
        //     store.getProxy().extraParams={
        //         section_id: section_id
        //     }
        // }
    },
],
    selModel:{
        selType: 'checkboxmodel',
        mode: 'MULTI'
    },
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    }/*, {
        ftype: 'grouping',
        startCollapsed: true,
        groupHeaderTpl: 'Section: {[values.rows[0].data.section_name]} [{rows.length} {[values.rows.length > 1 ? "Items" : "Item"]}]',
        hideGroupedHeader: true,
        enableGroupingMenu: false
    }*/],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                storeId: 'annualworkplanstr',
                proxy: {
                    url: 'enforcement/getAnnualWorkplanDetails'
                }
            },
            isLoad: true
        },
       
    },
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'name',
        text: 'Name/Identity',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'description',
        text: 'Description',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'start_date',
        text: 'Start Date',
        flex: 1,
        renderer: Ext.util.Format.dateRenderer('d/m/Y')
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'end_date',
        text: 'End Date',
        flex: 1,
        renderer: Ext.util.Format.dateRenderer('d/m/Y')
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
                    text: 'Edit Workplan',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'Edit Record',
                    action: 'edit',
                    childXtype:'annualWorkplanFrm',
                    winTitle: 'Edit Work Plan',
                    winWidth:'70%',
                    handler: 'showEditConfigParamWinFrm',
                    stores: '[]'
                },{
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'par_annual_workplan_details',
                    storeID: 'annualworkplanstr',
                    action_url: 'enforcement/genericDeleteRecord',
                    action: 'actual_delete',
                    handler: 'deleteRecord'
                },
                {
                    text: 'Add Regions,Facilities and Inspectors',
                    iconCls: 'x-fa fa-plus',
                    tooltip: 'Add regions',
                    action: 'add',
                    handler: 'showRegionFacilitiesWin',
                    stores: '[]'
                },
                {
                    text: 'View Regions',
                    iconCls: 'x-fa fa-eye',
                    tooltip: 'View Plan regions',
                    childXtype:'planRegionsGrid',
                    winTitle:'Plan Regions',
                    handler: 'showPlanMoreDetails',
                    stores: '[]'
                },
                {
                    text: 'View Facilities',
                    iconCls: 'x-fa fa-eye',
                    tooltip: 'View Plan Facilities',
                    childXtype:'planFacilitiesGrid',
                    winTitle:'Plan Facilities',
                    handler: 'showPlanMoreDetails',
                    stores: '[]'
                },
                {
                    text: 'View Inspectors',
                    iconCls: 'x-fa fa-eye',
                    tooltip: 'View Plan Inspectors',
                    childXtype:'planInspectorsGrid',
                    winTitle:'Plan Inspectors',
                    handler: 'showPlanMoreDetails',
                    stores: '[]'
                }
                ]
            }
        },
      
    }]
});
