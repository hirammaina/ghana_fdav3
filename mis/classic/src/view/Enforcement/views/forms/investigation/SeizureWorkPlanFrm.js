Ext.define('Admin.view.Enforcement.views.forms.investigation.SeizureWorkPlanFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'seizureWorkPlanFrm',
    itemId: 'seizureWorkPlanFrm',
    layout: {
        type: 'column'
    },
  
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
       
    }, autoScroll: true,
    items: [
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'hiddenfield',
            name: 'seizureplan_id'
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Lead Inspector',
            name: 'lead_inspector',
            valueField: 'name',
            displayField: 'name',
            forceSelection: true,
            readOnly:false,
            fieldStyle: {
                'color': 'green',
                'font-weight': 'bold'
            },
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            url:'enforcement/getusers'
                        }
                    },
                    isLoad: true
                }, 
            }
            
        },
    
        {
            xtype: 'datefield',
            fieldLabel: 'Start Date',
            name:'start_date',
            identity:'start_date',
            margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'datefield',
            fieldLabel: 'End Date',
            name:'end_date',
            identity:'end_date',
            margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Details',
            name: 'remarks',
            columnWidth: 1,
        },
       
    ]
});