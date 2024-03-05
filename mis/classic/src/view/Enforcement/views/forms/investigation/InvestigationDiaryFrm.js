Ext.define('Admin.view.Enforcement.views.forms.investigation.InvestigationDiaryFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'investigationdiaryfrm',
    itemId: 'investigationdiaryfrm',
    controller: 'enforcementvctr',
    height: Ext.Element.getViewportHeight() - 118,
    bodyPadding: 8,
   
    layout: {
        type: 'table',
        columns: 3
    },
    layout: 'column',
    autoScroll: true,
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'enforcement_id'
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
            xtype:'fieldset',
            columnWidth: 1,
            title: 'Potential Charges',
            collapsible: true,
            defaults: {
                labelAlign: 'top',
                allowBlank: false,
                labelAlign: 'top',
                margin: 5,
                xtype: 'textfield',
                allowBlank: false,
                columnWidth: 0.33,
            },
            layout: 'form',
            items :[ 
                {
                xtype:'htmleditor',
                name:'charge_details',
                fieldLabel:'Details',
                margin: '20 20 20 20',
            },
            {
                xtype:'htmleditor',
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
            }
        ]
        },
        {
            xtype:'fieldset',
            columnWidth: 1,
            title: 'Current Information',
            collapsible: true,
            defaults: {
                labelAlign: 'top',
                allowBlank: false,
                labelAlign: 'top',
                margin: 5,
                xtype: 'textfield',
                allowBlank: false,
                columnWidth: 0.33,
            },
            layout: 'form',
            items :[ 
                {
                    xtype:'textfield',
                    name:'current_source',
                    fieldLabel:'Current Source',
                },
                {
                    xtype:'htmleditor',
                    name:'current_information',
                    fieldLabel:'Current Information',
                },
                {
                    xtype:'textfield',
                    name:'current_relevance',
                    fieldLabel:'Relevance',
                },
        ]
        },
        {
            xtype:'fieldset',
            columnWidth: 1,
            title: 'Witness Details',
            collapsible: true,
            defaults: {
                labelAlign: 'top',
                allowBlank: false,
                labelAlign: 'top',
                margin: 5,
                xtype: 'textfield',
                allowBlank: false,
                columnWidth: 0.33,
            },
            layout: 'form',
            items :[ 
             {
                xtype:'textfield',
                name:'witness_name',
                fieldLabel:'Witness Name',
            },
            {
                xtype:'htmleditor',
                name:'witness_information',
                fieldLabel:'witness Information',
            },
             
            {
                xtype:'textfield',
                name:'witness_relevance',
                fieldLabel:'witness Relevance',
            },
        ]
        },
        {
            xtype:'fieldset',
            columnWidth: 1,
            title: 'Avenue of Inquiry Details',
            collapsible: true,
            defaults: {
                labelAlign: 'top',
                allowBlank: false,
                labelAlign: 'top',
                margin: 5,
                xtype: 'textfield',
                allowBlank: false,
                columnWidth: 0.33,
            },
            layout: 'form',
            items :[ 
                {
                    xtype:'textfield',
                    name:'inquiry_name',
                    fieldLabel:'Inquiry Type',
                },
                {
                    xtype:'htmleditor',
                    name:'inquiry_information',
                    fieldLabel:'inquiry Information',
                },
                {
                    xtype:'textfield',
                    name:'inquiry_relevance',
                    fieldLabel:'inquiry Relevance',
                }, 
        ]
        },
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveInvestigationDairy',
            table_name: 'par_investigation_diary',
            storeID: 'investigationDiaryStr',
            action: 'save_investigation_diary'
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