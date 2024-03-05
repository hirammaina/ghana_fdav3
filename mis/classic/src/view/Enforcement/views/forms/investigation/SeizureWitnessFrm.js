Ext.define('Admin.view.Enforcement.views.forms.investigation.SeizureWitnessFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'seizureWitnessFrm',
    itemId: 'seizureWitnessFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'form'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
       
    },
    // height: Ext.Element.getViewportHeight() - 118,
     //bodyPadding: 8,
   
    // layout: {
    //     type: 'table',
    //     columns: 3
    // },
    // layout: 'column',
    // autoScroll: true,
 
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
            name: 'application_id',
        },
        {
            xtype: 'hiddenfield',
            name: 'application_code',
        },
    
        {
            xtype:'textareafield',
            name:'witness_name',
            fieldLabel:'Witness Name',
        },
        {
            xtype:'textareafield',
            name:'witness_designation',
            fieldLabel:'witness Designation',
        }, 
        {
            xtype:'textareafield',
            name:'responsible_person',
            fieldLabel:'Responsible Person',
        },
          
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveSeizureWitnessDetails',
            table_name: 'par_seizure_witness_details',
            storeID: 'seizureWitnessGridStr',
            action: 'save_caseCharges_details'
        },{
            xtype: 'button',
            text: 'Clear',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-close',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ],
});