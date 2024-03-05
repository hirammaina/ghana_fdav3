 Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.MonitoringPremisePersonnelGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'monitoringpremisepersonnelgrid',
    controller: 'enforcementvctr',
    height: Ext.Element.getViewportHeight() - 108,
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 100,
                storeId: 'monitoringpremisepersonnelgridstr',
                remoteFilter: true,
                enablePaging: true,
                proxy: {
                    url: 'enforcement/getPremiseResponsibleProfessional'
                }
            },
            isLoad: true
        },
        // itemdblclick: 'onPremisePersonnelDblClick'
    },
    tbar: [{
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    }, {
        xtype: 'button',
        text: 'Add Professional',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-blue',
        name: 'add_personnel',
        winTitle: 'Premise Personnel Details',
        childXtype: 'monitoringpremisepersonnelfrm',
        handler: 'showAddConfigParamWinFrm',
        winWidth: '60%',
        stores: '[]'
    }, {
        xtype: 'exportbtn'
    }],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function(){
            this.up('grid').fireEvent('refresh', this);
        }
    }],
    columns: [
    {
        xtype: 'gridcolumn',
        dataIndex: 'name',
        text: 'Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'telephone',
        text: 'Telephone',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'email',
        text: 'Email',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'position',
        text: 'Position',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'qualification',
        text: 'Qualification',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'start_date',
        text: 'Start Date',
        flex: 1,
        renderer: Ext.util.Format.dateRenderer('d/m/Y')
    }, 
    {
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
                items: [{
                    text: 'Edit Resposnisble Professional ',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'Edit Record',
                    action: 'edit',
                    handler: 'editResponsiblProfessionalDetails',
                    stores: '[]'
                },{
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'par_monitoring_premise_personnel',
                    storeID: 'monitoringpremisepersonnelgridstr',
                    action_url: 'enforcement/genericDeleteRecord',
                    action: 'actual_delete',
                    handler: 'deleteRecord'
                }
                ]
            }
        },
    }
]
});