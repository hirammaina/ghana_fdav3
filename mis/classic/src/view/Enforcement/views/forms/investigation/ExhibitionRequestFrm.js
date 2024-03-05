Ext.define('Admin.view.Enforcement.views.forms.investigation.ExhibitionRequestFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'exhibitionRequestFrm',
    itemId: 'exhibitionRequestFrm',
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
            name: 'requisitioning_officer',
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
        // {
        //     xtype: 'textfield',
        //     fieldLabel: 'Names of requisitioning officer',
        //     name:'requisitioning_officer',
        //     allowBlank: false
        // },
        {
            xtype: 'textfield',
            fieldLabel: 'Department',
            name:'department',
            allowBlank: false
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Exhibit destination',
            name:'Exhibit_destination',
            allowBlank: false
        },
      
        {
            xtype: 'datefield',
            fieldLabel: 'Expected return date',
            name: 'return_date',
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Date needed',
            name: 'needed_date',
        },
        {
            xtype: 'textareafield',
            fieldLabel: 'Reasons for requisition',
            name:'requisition_reason',
            columnWidth: 1,
        },

    ],
  
});