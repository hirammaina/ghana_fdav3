Ext.define('Admin.view.Enforcement.views.panels.JointOperations.JointInvestigationDetails', {
    extend: 'Ext.panel.Panel',
    xtype: 'jointInvestigationDetails',
    controller: 'enforcementvctr',
    autoScroll: true,
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
        title: 'JOINT OPERATION REPORT',
        autoScroll: true,
        items:[
            {
             xtype: 'jointOffenceDetailsFrm',
             title: 'Premise Offence Details',
             autoScroll: true,
            },
            {
            xtype: 'jointSeizedProductsGrid',
            title: 'Product Seized Details',
            autoScroll: true,
            },
            {
                xtype: 'hiddenfield',
                name: 'active_application_code'
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
        name: 'process_id'
    },
    {
        xtype: 'hiddenfield',
        name: 'enforcement_id'
    }, 
    {
        xtype: 'hiddenfield',
        name: 'joint_operation_id'
    }, 
    {
        xtype: 'hiddenfield',
        name: 'joint_investigation_id'
    }, 
    {
        xtype: 'hiddenfield',
        name: 'workflow_stage_id'
    }, {
        xtype: 'hiddenfield',
        name: 'active_application_id'
    }, 
    {
        xtype: 'hiddenfield',
        name: 'active_application_code'
    },
     {
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