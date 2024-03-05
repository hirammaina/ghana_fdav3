Ext.define('Admin.view.Enforcement.views.forms.CaseChargesFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'casechargesfrm',
    itemId: 'casechargesfrm',
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
    // bodyPadding: 8,
   
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
            name: 'enforcement_id',
        },
        // {
        //     xtype: 'hiddenfield',
        //     name: 'application_id',
        // },
        {
            xtype:'htmleditor',
            name:'Facts_alleged',
            fieldLabel:'Facts alleged',
            margin: '20 20 20 20',
        },
      
        //potential charges
        {

        },
        {
            xtype:'textfield',
            name:'charge_details',
            fieldLabel:'Potetial charge 1 Details',
            margin: '20 20 20 20',
        },
        {
            xtype:'textfield',
            name:'charge_regulation',
            fieldLabel:'Act/regulation',
            margin: '20 20 20 20',
        },
        {
            xtype:'textfield',
            name:'charge_section',
            fieldLabel:'Section/reg number',
            margin: '20 20 20 20',
        },
        {
            xtype:'textfield',
            name:'charge_elements',
            fieldLabel:'Elements',
            margin: '20 20 20 20',
        },
       
        // {
        //     xtype:'fieldset',
        //     columnWidth: 1,
        //     title: 'Potential Charge number 2',
        //     collapsible: true,
        //     defaults: {
        //         labelAlign: 'top',
        //         allowBlank: false,
        //         labelAlign: 'top',
        //         margin: 5,
        //         xtype: 'textfield',
        //         allowBlank: false,
        //         columnWidth: 0.33,
        //     },
        //     layout: 'column',
        //     items :[ {
        //         xtype:'textfield',
        //         name:'charge2_details',
        //         fieldLabel:'Details',
        //         margin: '20 20 20 20',
        //     },
        //     {
        //         xtype:'textfield',
        //         name:'charge2_regulation',
        //         fieldLabel:'Act/regulation',
        //         margin: '20 20 20 20',
        //     },
        //     {
        //         xtype:'textfield',
        //         name:'charge2_section',
        //         fieldLabel:'Section/reg number',
        //         margin: '20 20 20 20',
        //     },
        //     {
        //         xtype:'textfield',
        //         name:'charge2_elements',
        //         fieldLabel:'Elements',
        //         margin: '20 20 20 20',
        //     }
        // ]
        // },
        // {
        //     xtype:'fieldset',
        //     columnWidth: 1,
        //     title: 'Potential Charge number 3',
        //     collapsible: true,
        //     defaults: {
        //         labelAlign: 'top',
        //         allowBlank: false,
        //         labelAlign: 'top',
        //         margin: 5,
        //         xtype: 'textfield',
        //         allowBlank: false,
        //         columnWidth: 0.33,
        //     },
        //     layout: 'column',
        //     items :[ {
        //         xtype:'textfield',
        //         name:'charge3_details',
        //         fieldLabel:'Details',
        //         margin: '20 20 20 20',
        //     },
        //     {
        //         xtype:'textfield',
        //         name:'charge3_regulation',
        //         fieldLabel:'Act/regulation',
        //         margin: '20 20 20 20',
        //     },
        //     {
        //         xtype:'textfield',
        //         name:'charge3_section',
        //         fieldLabel:'Section/reg number',
        //         margin: '20 20 20 20',
        //     },
        //     {
        //         xtype:'textfield',
        //         name:'charge3_elements',
        //         fieldLabel:'Elements',
        //         margin: '20 20 20 20',
        //     }
        // ]
        // },     

    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveCaseCharges',
            table_name: 'par_case_charges',
            storeID: 'caseChargesStr',
            action: 'save_caseCharges_details',
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