/**
 * Created by Softclans
 */
 Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.MystreyShoppingFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'mystreyShoppingFrm',
    layout: 'column',
    controller:'enforcementvctr',
    bodyPadding: 5,
    autoScroll: true,
    height: Ext.Element.getViewportHeight() - 118,
    defaults: {
        columnWidth: 1,
        margin: 2,
        labelAlign: 'top',
        allowBlank: false
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
            xtype: 'textfield',
            fieldLabel: 'Names of Shedules',
            name: 'name'
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'How the record of sale was documented',
            name: 'description'
        },
        {
            xtype: 'textfield',
            fieldLabel: 'The person who sold the Product',
            name: 'personnel'
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Date of shopping (per receipt)',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            name: 'date_of_shopping',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
        {
            xtype: 'timefield',
            fieldLabel: 'Time of Shoppings (per receipt)',
            format: 'H:i',
            altFormats:'H:i',
            increment: 30,
            name: 'time_of_shopping',
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Other details',
            columnWidth: 1,
            name: 'details'
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'bottom',
            height: 40,
            ui: 'footer',
            items: [
                {
                    text: 'Save Details',
                    ui: 'soft-purple',
                    iconCls: 'x-fa fa-save',
                    action: 'genericsaveDetails',
                    action_url: 'enforcement/genericSaveUpdate',
                    table_name: 'par_mystrey_shopping_details',
                    storeID: 'mystreyShoppingLogGridStr'
                },{
                    xtype: 'button',
                    text: 'Clear',
                    ui: 'soft-purple',
                    iconCls: 'x-fa fa-close',
                    handler: function () {
                        this.up('form').getForm().reset();
                    }
                }
            ]
        }
    ]
});