Ext.define('Admin.view.Enforcement.views.grids.EnforcementRegisteredFacilityGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'enforcementregisteredfacilitydetailsgrids',
	// custom_config_form:'suspectinforFrm',
    controller: 'enforcementvctr',
    height: Ext.Element.getViewportHeight() - 118,
    autoScroll: true,
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 100,
                storeId: 'registeredpremisesstr',
                remoteFilter: true,
                enablePaging: true,
                proxy: {
                    url: 'enforcement/getApprovedPremises'
                }
            },
            isLoad: true
        }
    },
    plugins: [{
        ptype: 'filterfield'
    }],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        // beforeLoad: function () {
        //     var grid = this.up('registeredproductsgrid'),
        //         store = grid.store;
        //         section_id = grid.down('hiddenfield[name=section_id]').getValue();
        //         status_id = grid.down('hiddenfield[name=status_id]').getValue();
        //             store.getProxy().extraParams = {
        //                 section_id: section_id,
        //                 status_id: status_id
        //             };
        // }
    }],
    tbar: [{
      xtype:'hiddenfield',
      name:'section_id'  
    },{
        xtype:'hiddenfield',
        name:'status_id'  
      },{
        text: 'Double Click to select Facility',
        ui: 'soft-blue',
    }, '->', 
    {
        xtype: 'textfield',
        name: 'search_value',
        allowBlank: false,hidden: true,
        emptyText: 'Enter Search Value'
    }, {
        text: 'Search',hidden: true,
        iconCls: 'fa fa-search', allowBlank: false,
        ui: 'soft-purple',
        handler: 'funcSearchProductApplications'
    }, {
        text: 'Clear',
        iconCls: 'fa fa-cancel',
        ui: 'soft-red',hidden: true,
        handler: 'funcClearSearchApplications'
    }],
    columns: [{
        xtype: 'rownumberer'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'certificate_no',
        text: 'Certificate Number',
        width: 150,
        filter: {
            xtype: 'textfield',
        }
    }, 
    {
        xtype: 'gridcolumn',
        dataIndex: 'name',
        text: 'premise_Name',
        width: 150,
        filter: {
            xtype: 'textfield',
        }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'premise_type',
        text: 'Premise Type',
        width: 150,
        filter: {
            xtype: 'textfield',
        }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'applicant_name',
        text: 'Applicant Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'contact_person',
        text: 'Contact Person',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'approval_date',
        text: 'Approval Date',
        flex: 1
    },{
        xtype: 'gridcolumn',
        dataIndex: 'application_status',
        text: 'Status',
        flex: 1
    }]
});