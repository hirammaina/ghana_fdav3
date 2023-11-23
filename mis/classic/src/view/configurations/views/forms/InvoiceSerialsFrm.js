Ext.define('Admin.view.configurations.views.forms.InvoiceSerialsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'invoiceserialsFrm',
    controller: 'configurationsvctr',
    autoScroll: true,
    layout: 'form',
    frame: true,
    bodyPadding: 8,
    defaults: {
        labelAlign: 'top',
        allowBlank: false
    },
    
    items: [{
        xtype: 'hiddenfield',
        margin: '0 20 20 0',
        name: 'table_name',
        value: 'invoice_serials',
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        margin: '0 20 20 0',
        name: '_token',
        value: token,
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        fieldLabel: 'id',
        margin: '0 20 20 0',
        name: 'id',
        allowBlank: true
    },{
        xtype: 'textfield',
        fieldLabel: 'Registration Year',
        margin: '0 20 20 0',
        name: 'registration_year',
        allowBlank: false
    },{
        xtype: 'textfield',
        fieldLabel: 'last Serial',
        margin: '0 20 20 0',
        name: 'last_serial',
        allowBlank: true
    }],
    dockedItems:[
        {
            xtype: 'toolbar',
            ui: 'footer',
            dock: 'bottom',
            items:[
                '->',{
                    text: 'Set Serial',
                    iconCls: 'x-fa fa-save',
                    action: 'save',
                    table_name: 'invoice_serials',
                    storeID: 'invoiceserialsStr',
                    formBind: true,
                    ui: 'soft-purple',
                    action_url: 'configurations/saveConfigCommonData',
                    handler: 'doCreateConfigParamWin'
                }
            ]
        }
    ]
});