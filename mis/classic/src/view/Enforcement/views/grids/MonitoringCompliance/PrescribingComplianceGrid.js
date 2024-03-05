Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.PrescribingComplianceGrid',{
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'prescribingComplianceGrid',
    height: Ext.Element.getViewportHeight() - 118,
    
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'No Prescribing Data Found',
	
},
    plugins: {
        cellediting: {
            clicksToEdit: 1
        }
    },
    tbar: [
        {
            xtype: 'hiddenfield',
            name: 'application_code'
        }, {
            xtype: 'hiddenfield',
            name: 'module_id'
        }, 
        // {
        //     xtype: 'button',
        //     text: 'Add Prescription',
        //     iconCls: 'x-fa fa-plus',
        //     ui: 'soft-blue',
        //     handler: function () {
        //         let store = this.up('grid').getStore();
        //         store.insert(0, {
        //             product_id: 0,
        //             medicine_name: '',
        //             patient_particulars: '',
        //             medicine_details: '',
        //             prescriber_details:'',
        //             prescription_date:'',
        //             facility_stamp:'',
        //             calculated_average:''
        //         })
        //     }
        // },
    ],
    listeners:{
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                autoLoad: false,
                defaultRootId: 'root',
                enablePaging: true,
                storeId: 'prescribingComplianceGridStr',
                proxy: {
                    url: 'enforcement/getPrescribingComplianceInformation'
                }
            },
            isLoad: true
        }
    },
bbar: [
    {
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
        action_url: 'enforcement/savePrescribingComplianceDetails',
        table_name: 'par_prescribing_compliance_data',
        storeID: 'prescribingComplianceGridStr',
        action: 'save_prescribing_data'
    },{
        xtype: 'button',
        text: 'Clear',
        ui: 'soft-purple',
        iconCls: 'x-fa fa-close',
        handler: function () {
            this.up('form').getForm().reset();
        }
    },],
    columns: [      
    {
        xtype: 'rownumberer',
        text:'Sample No',
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'medicine_name',
        text:'Prescription',
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
        xtype:'gridcolumn',
        dataIndex:'patient_particulars',
        text:'Patient`s Particulars',
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
        xtype:'gridcolumn',
        dataIndex:'medicine_details',
        text:'Medicine Details',
        editor: {
            xtype: 'numberfield',
            typeAhead: true,
            triggerAction: 'all',
            selectOnFocus: false,
            minValue:0,
            maxValue:1,
        },
        flex: 1,
    },
    {
        xtype: 'gridcolumn',
        text: 'Prescriber Details',
        dataIndex: 'prescriber_details',
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
        text: 'Prescription Date',
        dataIndex: 'prescription_date',
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
        text: 'Facility Stamp',
        dataIndex: 'facility_stamp',
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
        text: 'Calculated Avarage %',
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
    //                     childXtype:'prescribingcomplianceFrm',
    //                     winTitle: 'Edit Prescribing Data',
    //                     winWidth:'60%',
    //                     handler: 'showEditConfigParamWinFrm',
    //                     stores: '[]'
    //                 },
    //                 {
    //                     text: 'Delete',
    //                     iconCls: 'x-fa fa-trash',
    //                     tooltip: 'Delete Record',
    //                     table_name: 'par_prescribing_compliance_data',
    //                     storeID: 'prescribingComplianceGridStr',
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
