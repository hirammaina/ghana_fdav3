
 Ext.define('Admin.view.Enforcement.views.grids.JointOperation.JointOperationActivitiesGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'enforcementvctr',
    xtype: 'jointOperationActivitiesGrid',
    cls: 'dashboard-todo-list',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Applications Found',
    },

    tbar:[
        {
            xtype: 'button',
            text: 'Add Activities',
            name: 'add_activity',
            iconCls: 'x-fa fa-plus',
            ui: 'soft-blue',
            handler: 'showAddConfigParamWinFrm',
            childXtype: 'jointActivitiesform',
            winTitle: 'Joint Operations Activities',
            winWidth: '50%',
            stores: '[]'
        }
    ],

    plugins: [{
        ptype: 'gridexporter'
    }, {
        ptype: 'cellediting',
        clicksToEdit: 1,
        editing: true
    }],
    dockedItems: [
        {
            xtype: 'toolbar',
            ui: 'footer',
            dock: 'bottom',
            items: [
                {
                    xtype: 'pagingtoolbar',
                    displayInfo: true,
                    width: '80%',
                    emptyMsg: 'No Records',
                    beforeLoad: function () {
                        var store = this.getStore(),
                            grid = this.up('grid'),
                            pnl = grid.up('panel'),
                            mainTabPnl = pnl.up('#contentPanel'),
                            activeTab = mainTabPnl.getActiveTab();
                            application_code = activeTab.down('hiddenfield[name=active_application_code]').getValue(),
                            store = this.getStore();
                        store.getProxy().extraParams = {
                            application_code: application_code,
                            
                        }
                    }
                }
            ]
        }
    ],
    features: [{
        ftype: 'grouping',
        startCollapsed: true,
        groupHeaderTpl: 'Activity : {[values.rows[0].data.activity]} ({rows.length})',
        hideGroupedHeader: false,
        enableGroupingMenu: false
    }],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 10000,
                groupField: 'activity',
                storeId: 'jointOperationActivitiesGridStr',
                proxy: {
                    url: 'enforcement/getJointOperativeActivities'
                }
            },
            isLoad: true
        },
    },
    columns: [
        {
            text: 'Activity',
            dataIndex: 'activity',
            flex: 1
        },
        {
            text: 'Objective ',
            dataIndex: 'objective',
            flex: 1
        },
        {
            text: 'Start Date',
            dataIndex: 'start_date',
            flex: 1
        },
        {
            text: 'End Date',
            dataIndex: 'end_date',
            flex: 1
        },
        {
            text: 'End Date',
            dataIndex: 'end_date',
            flex: 1
        },        
        {
            text: 'Officer',
            dataIndex: 'officer',
            flex: 1
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
                            childXtype: 'jointActivitiesform',
                            winTitle: 'Edit Activity',
                            winWidth: '40%',
                            handler: 'showEditConfigParamWinFrm',
                            stores: '[]'
                        },{
                            text: 'Delete',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'par_joint_activities_details',
                            storeID: 'logisticsstr',
                            action_url: 'enforcement/genericDeleteRecord',
                            action: 'actual_delete',
                            handler: 'deleteRecord'
                            
                        }
                    ]
                }
            }, onWidgetAttach: function (col, widget, rec) {
                var grid =widget.up('grid'),
                    is_meeting = grid.is_meeting;
                if (is_meeting === 1 || is_meeting == 1) {
                    widget.setVisible(false);;
                } 
                else {
                    widget.setVisible(true);
                }
            }
        }
    ]
});
