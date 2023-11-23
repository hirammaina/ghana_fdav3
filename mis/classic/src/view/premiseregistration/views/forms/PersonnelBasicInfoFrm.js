/**
 * Created by Kip on 11/9/2018.
 */
Ext.define('Admin.view.premiseregistration.views.forms.PersonnelBasicInfoFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'personnelbasicinfofrm',
    controller: 'premiseregistrationvctr',
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
    listeners: {
        afterrender: function () {
            var form = this,
                isReadOnly = form.down('hiddenfield[name=isReadOnly]').getValue();
            if ((isReadOnly) && (isReadOnly == 1 || isReadOnly === 1)) {
                form.getForm().getFields().each(function (field) {
                    field.setReadOnly(true);
                });
                form.query('.button').forEach(function (c) {
                    c.setHidden(true);
                });
            }
        }
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
            name: 'table_name',
            value: 'tra_personnel_information'
        },
        {
            xtype: 'hiddenfield',
            name: 'trader_id'
        },
         {
            xtype: 'textfield',
            name: 'name',  fieldLabel: 'Name',
        },
        {
            xtype: 'textfield',
            name: 'postal_address',
            fieldLabel: 'Postal Address'
        },
        
        {
            xtype: 'textfield',
            name: 'email_address',
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
            storeID: 'traderpersonnelstr',
            action_url: 'premiseregistration/savePremisePersonnelDetails',
            handler: 'savePremisePersonnelBasicInfo',
            name: 'save_btn'
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