
 Ext.define('Admin.view.Enforcement.views.grids.JointOperation.ViewActivityGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'enforcementvctr',
    xtype: 'viewActivityGrid',
    cls: 'dashboard-todo-list',
    autoScroll: true,
	width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Applications Found',
    },

    tbar:[
    ],

    plugins: [{
        ptype: 'gridexporter'
    }, {
        ptype: 'cellediting',
        clicksToEdit: 1,
        editing: true
    }],
    features: [{
        ftype: 'grouping',
        startCollapsed: true,
        groupHeaderTpl: 'Activity : {[values.rows[0].data.activity]} ({rows.length})',
        hideGroupedHeader: false,
        enableGroupingMenu: false
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
                        this.up('viewActivityGrid').fireEvent('refresh', this); 
                    }
                }
            ]
        }
    ],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 10000,
                storeId: 'OfficerAssignedGridStr',
                groupField: 'activity',
                proxy: {
                    url: 'enforcement/getOfficerAssignedActivity'
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
    ]
});
