Ext.define('Admin.view.Enforcement.views.forms.investigation.ViewWorkPlanFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'viewWorkPlanFrm',
    itemId: 'viewWorkPlanFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
       
    },
   
 
    items: [

        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'isReadOnly'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'hiddenfield',
            name: 'workplan_id'
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Matter Name',
            identity:'matter_name',
            name:'matter_name',
           // margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Matter Details',
            name:'matter_details',
            identity:'matter_details',
            //margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Investigation Subject',
          //  margin: '0 20 20 0',
            name: 'investigation_subject',
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Investigation Type',
            name:'investigation_type',
            identity:'investigation_type',
            //margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Start Date',
            name:'start_date',
            //identity:'start_date',
           // margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'datefield',
            fieldLabel: 'End Date',
            name:'end_date',
            //identity:'end_date',
           // margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Facts Alleged',
            name: 'facts_alleged',
            columnWidth: 1,
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Investigation Details',
            name: 'remarks',
            columnWidth: 1,
        },

    ],
  
});