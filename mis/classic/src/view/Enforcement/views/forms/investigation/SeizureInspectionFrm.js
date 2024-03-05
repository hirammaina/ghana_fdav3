Ext.define('Admin.view.Enforcement.views.forms.investigation.SeizureInspectionFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'seizureInspectionFrm',
    itemId: 'seizureInspectionFrm',
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
     height: Ext.Element.getViewportHeight() - 118,
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
            name: 'active_application_id',
        },
        {
            xtype: 'hiddenfield',
            name: 'active_application_code',
        },
        {
            xtype: 'hiddenfield',
            name: 'offence_type_id',
        },
        // {
        //     xtype:'textfield',
        //     name:'offennce_type',
        //     fieldLabel:'Offence Type',
        //     margin: '20 20 20 20',
        //     readOnly:true
        // },
        //•	Name of premises/port of entry
        {
            xtype:'textfield',
            name:'premise',
            fieldLabel:'Name of premises/port of entry',
        },
        {
            xtype:'datefield',
            name:'date_of_inspection',
            fieldLabel:'Date of inspections',
        },
      
        {
            xtype:'htmleditor',
            name:'premise_registration_number',
            fieldLabel:'Registration number',
        },
         
        {
            xtype:'textareafield',
            name:'personnel',
            fieldLabel:'Name(Premise working personnel)',
        },
       // •	Names of persons found working in premises 
       
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveSeizureInspectionDetails',
            table_name: 'par_seizure_inspection_details',
            storeID: 'seizureInspectionGridStr',
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