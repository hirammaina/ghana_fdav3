Ext.define('Admin.view.Enforcement.views.forms.investigation.DestructionPlanFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'destructionPlanFrm',
    itemId: 'destructionPlanFrm',
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
            name: 'request_id'
        },
        //•	•	•	Names of requisitioning officer
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Inspector',
            name: 'inspector',
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
            xtype: 'textfield',
            fieldLabel: 'pharmacist',
            name:'pharmacist',
            allowBlank: false
        },
        {
            xtype: 'textfield',
            fieldLabel: 'police officer',
            name:'police',
            allowBlank: false
        },
      
        {
            xtype: 'datefield',
            fieldLabel: 'Destruction date',
            name: 'date',
        },
       
        {
            xtype: 'textareafield',
            fieldLabel: 'Remarks',
            name:'remarks',
            columnWidth: 1,
        },

    ],
  
});