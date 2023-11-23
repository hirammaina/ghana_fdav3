
Ext.define('Admin.view.frontoffice.premise.forms.PersonnelPfBasicInfoFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'personnelpfbasicinfofrm',
    controller: 'spreadsheetpremisectr',
    layout: {
        type: 'column'
    },
    bodyPadding: 5,
    defaults: {
        margin: 4,
        allowBlank: false,
        columnWidth: 0.5,
        labelAlign: 'top'
    },
  
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'hiddenfield',
            name: 'table_name',
            value: 'tra_personnel_information'
        },
        {
            xtype: 'hiddenfield',
            name: 'trader_id'
        },
        {
            xtype: 'fieldcontainer',
            layout: 'column',
            defaults: {
                labelAlign: 'top'
            },
            fieldLabel: 'Name',
            items: [
                {
                    xtype: 'textfield',
                    name: 'name',
                    columnWidth: 1
                }
            ]
        },
        {
            xtype: 'textfield',
            name: 'postal_address',
            fieldLabel: 'Postal Address'
        },
        {
            xtype: 'textfield',
            name: 'telephone_no',
            fieldLabel: 'Telephone No'
        },
        {
            xtype: 'textfield',
            name: 'email_address',
            vtype:'email',
            fieldLabel: 'Email Address'
        }
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            table_name: 'tra_personnel_information',
            storeID: 'traderpfpersonnelstr',
            action_url: 'premiseregistration/savePremisePersonnelDetails',
            handler: 'saveBasicPersonnelDetails'
        },
        {
            xtype: 'button',
            text: 'Reset',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-close',
            name: 'reset_btn',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ]
});



