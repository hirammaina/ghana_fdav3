Ext.define('Admin.view.main.DigitalSignatureConfigFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'digitalsignatureconfigfrm',
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
        value: 'tra_users_digitalsignatures',
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        margin: '0 20 20 0',
        name: '_token',
        value: token,
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        fieldLabel: 'user_id',
        margin: '0 20 20 0',
        name: 'user_id',
        allowBlank: false
    },{
        xtype: 'textfield',
        fieldLabel: 'Digital Signature Account Address',
        margin: '0 20 20 0', labelAlign: 'top',
        name: 'email_address',
        allowBlank: false
    },{
        xtype: 'filefield',
        fieldLabel: 'Signature File (Personal Sign with Organisation Stampp)',
        name: 'uploaded_doc', labelAlign: 'top',
        allowBlank: false
    },{
        xtype: 'textfield',
        fieldLabel: 'Digital Signature Key',
        margin: '0 20 20 0', labelAlign: 'top',
        name: 'dn',
        readOnly: true,
        allowBlank: false
    }],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Digital Sign Config and Upload Personal Signature',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-upload',
            name:'upload_sign_btn',
            formBind: true
        }
    ]
});