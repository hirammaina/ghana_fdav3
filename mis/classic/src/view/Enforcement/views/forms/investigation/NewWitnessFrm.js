Ext.define('Admin.view.Enforcement.views.forms.investigation.NewWitnessFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'newWitnessFrm',
    itemId: 'newWitnessFrm',
    controller: 'enforcementvctr',
    layout:'column',
    frame: true,
     autoScroll: true,
     scrollable: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.5,
        margin: 2,
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
            name: 'enforcement_id',
        },
        {
            xtype: 'hiddenfield',
            name: 'application_id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Offence',
            identity:'Offence_type',
            name: 'offence_id',
            allowBlank: false,
            valueField: 'id',
            displayField: 'name',
            forceSelection: true,
            columnWidth: 1,
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
                            url:'enforcement/getCaseOffences'
                        },
                    },
                    isLoad: true
                },
            },         
        }, 
        {
            xtype: 'combo', anyMatch: true,
            name: 'witness_type_id',
            allowBlank: false,
            columnWidth: 1,
            queryMode: 'local',
            fieldLabel: 'Complainant/Witness Type',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            extraParams:{
                                table_name: 'par_witness_types'

                            }
                        }
                    },
                    isLoad: true
                }
            }
        },
        {
            xtype:'textfield',
            name:'witness_name',
            fieldLabel:'Complainant/ Witness Name',
            columnWidth: 1,
        },
        {
            xtype:'htmleditor',
            name:'witness_information',
            fieldLabel:'Complainant/ witness Information',
        },
        {
            xtype:'htmleditor',
            name:'witness_relevance',
            fieldLabel:'Complainant/ witness Relevance'
        }, 
        // {
        //     xtype:'textareafield',
        //     name:'inquiry_name',
        //     fieldLabel:'Inquiry Type',
        //     margin: '20 20 20 20',
        // },
        // {
        //     xtype:'textareafield',
        //     name:'inquiry_information',
        //     fieldLabel:'inquiry Information',
        //     margin: '20 20 20 20',
        // },
        // {
        //     xtype:'textareafield',
        //     name:'inquiry_relevance',
        //     fieldLabel:'inquiry Relevance',
        //     margin: '20 20 20 20',
        // },  
      
//
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveCaseWitnessDetails',
            table_name: 'par_case_witness_details',
            storeID: 'newWitnessGridStr',
            action: 'save_caseCharges_details',
        },
        {
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