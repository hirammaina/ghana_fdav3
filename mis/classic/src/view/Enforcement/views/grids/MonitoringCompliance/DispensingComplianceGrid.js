Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.DispensingComplianceGrid',{
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'dispensingComplianceGrid',
    height: Ext.Element.getViewportHeight() - 118,

    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Dispensing Data Found',
	
},
    plugins: {
        cellediting: {
            clicksToEdit: 1
        }
    },
    listeners:{
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                autoLoad: false,
                defaultRootId: 'root',
                enablePaging: true,
                storeId: 'dispensingComplianceGridStr',
                proxy: {
                    url: 'enforcement/getDispensingComplianceInformation'
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
            this.up('grid').fireEvent('refresh', this);
        }
    },
    '->',
    {
        xtype: 'button',
        text: 'Save Details',
        ui: 'soft-purple',
        iconCls: 'x-fa fa-save',
        formBind: true,
        action_url: 'enforcement/saveDispensingComplianceDetails',
        table_name: 'par_dispensing_compliance_data',
        storeID: 'dispensingComplianceGridStr',
        action: 'save_dispensing_data'
    },{
        xtype: 'button',
        text: 'Clear',
        ui: 'soft-purple',
        iconCls: 'x-fa fa-close',
        handler: function () {
            this.up('form').getForm().reset();
        }
    },
],
    columns: [
    {
        xtype: 'rownumberer',
        text:'Sample No',
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'dispensing_name',
        text:'Dispenser Name',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'reg_number',
        text:'BHPC',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'invoice_no',
        text:'Invoice Number',
        editor: {
            xtype: 'textfield',
            typeAhead: true,
            triggerAction: 'all',
            selectOnFocus: false,
            minValue:0,
            maxValue:1,
        },
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        text: 'Patients Name',
        dataIndex: 'patient_name',
        editor: {
            xtype: 'numberfield',
            typeAhead: true,
            triggerAction: 'all',
            selectOnFocus: false,
            minValue:0,
            maxValue:1,
        },
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        text: 'Dispensing Date',
        dataIndex: 'dispensing_date',
        editor: {
            xtype: 'numberfield',
            typeAhead: true,
            triggerAction: 'all',
            selectOnFocus: false,
            minValue:0,
            maxValue:1,
        },
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        text: 'Dispensed pack size',
        dataIndex: 'dispensed_packsize',
        editor: {
            xtype: 'numberfield',
            typeAhead: true,
            triggerAction: 'all',
            selectOnFocus: false,
            minValue:0,
            maxValue:1,
        },
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        text: 'Dispenser`s name and signature',
        dataIndex: 'dispenser_name_signature',
        editor: {
            xtype: 'numberfield',
            typeAhead: true,
            triggerAction: 'all',
            selectOnFocus: false,
            minValue:0,
            maxValue:1,
        },
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'calculated_average',
        text: 'Calculated Average %',
        flex: 1,
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
    //                     text: 'Edit',
    //                     iconCls: 'x-fa fa-edit',
    //                     tooltip: 'Edit Record',
    //                     action: 'edit',
    //                     childXtype:'dispensingcomplianceFrm',
    //                     winTitle: 'Edit Dispensing Data',
    //                     winWidth:'60%',
    //                     handler: 'showEditConfigParamWinFrm',
    //                     stores: '[]'
    //                 },
    //                 {
    //                     text: 'Delete',
    //                     iconCls: 'x-fa fa-trash',
    //                     tooltip: 'Delete Record',
    //                     table_name: 'par_dispensing_compliance_data',
    //                     storeID: 'dispensingComplianceGridStr',
    //                     action_url: 'configurations/deleteConfigRecord',
    //                     action: 'actual_delete',
    //                     handler: 'deleteRecord',
    //                 },
    //             ]
    //         }
    //     }
    // }
],
});
