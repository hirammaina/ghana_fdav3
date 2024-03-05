Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.ComplianceAssesmentDataPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'complianceAssesmentDataPnl',
    controller: 'enforcementvctr',
    defaults:{
        margin: 3
    },
    viewModel: {
        type: 'enforcementvm'
    },
    listeners: {
        tabchange: 'funcActiveOtherInformationTab'
    },
    items: [
        {
            
        xtype: 'tabpanel',
        itemId:'detailspanel',
        title: 'Compliance Data',
        items:[
            {
                xtype: 'prescribingComplianceGrid',
                title:'Prescribing Compliance Data',
                autoScroll: true,
            },
            {
                xtype: 'dispensingComplianceGrid',
                title:'Dispensing Compliance Data',
                autoScroll: true,
            },
            {
                xtype: 'controlledDispensingDataGrid',
                title:'Dispensing Section 38(2)',
                autoScroll: true,
            },
            {
                xtype: 'productscreeninggrid',
                title:'License Information',
                autoScroll: true,
            },
           ]
        },
 
    {
        xtype: 'hiddenfield',
        name: 'section_id'
    },{
        xtype: 'hiddenfield',
        name: 'prodclass_category_id'
    }, {
        xtype: 'hiddenfield',
        name: '_token',
        value: token
    },
    {
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    },
    {
        xtype: 'hiddenfield',
        name: 'process_id'
    },
    {
        xtype: 'hiddenfield',
        name: 'enforcement_id'
    }, {
        xtype: 'hiddenfield',
        name: 'workflow_stage_id'
    }, {
        xtype: 'hiddenfield',
        name: 'active_application_id'
    }, {
        xtype: 'hiddenfield',
        name: 'active_application_code'
    }, {
        xtype: 'hiddenfield',
        name: 'application_status_id'
    }, {
        xtype: 'hiddenfield',
        name: 'module_id'
    }, {
        xtype: 'hiddenfield',
        name: 'sub_module_id'
    }, {
        xtype: 'hiddenfield',
        name: 'section_id'
    }, {
        xtype: 'hiddenfield',
        name: 'status_type_id'
    }, {
        xtype: 'hiddenfield',
        name: 'report_type_id'
    }, {
        xtype: 'hiddenfield',
        name: 'reported_by_id'
    }]
});