Ext.define('Admin.view.Enforcement.views.forms.JointOperation.Operativesfrm', {
    extend: 'Ext.form.Panel',
    xtype: 'operativesfrm',
    itemId: 'operativesfrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 1,
        margin: 5,
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
			xtype:'textfield',
			name:'internal',
			fieldLabel:'Internal',
		},
        {   
			xtype:'numberfield',
			name:'internal_no_deployed',
			fieldLabel:'No deployed',
		},
       
        {   
			xtype:'textfield',
			name:'external',
			fieldLabel:'External',
		},
        {   
			xtype:'numberfield',
			name:'external_no_deployed',
			fieldLabel:'No deployed',
		},
        
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveOperatives',
            table_name: 'par_operatives',
            storeID: 'operativesGridStr',
            action: 'save_operatives_details'
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