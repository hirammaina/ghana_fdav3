Ext.define('Admin.view.Enforcement.views.forms.JointOperation.LogisticsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'logisticsform',
    itemId: 'logisticsform',
    controller: 'enforcementvctr',
    height: Ext.Element.getViewportHeight() - 118,
    layout: {
        type: 'column'
    },
    // autoScroll: true,
    bodyPadding: 5,
    autoScroll: true,
    defaults: {
        columnWidth: 1,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
       
    },
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/genericSaveUpdate',
            table_name: 'par_joint_logistics_details',
            storeID: 'logisticsstr',
            action: 'genericsaveDetails'
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
			xtype:'textfield',
			name:'name',
			fieldLabel:'Name of Item',
		},
        {   
			xtype:'textfield',
			name:'description',
			fieldLabel:'Description',
		},
        {   
			xtype:'numberfield',
			name:'quantity',
			fieldLabel:'Quantity',
		},
        {   
			xtype:'numberfield',
			name:'amount',
			fieldLabel:'Approximate Amount',
		},
        {   
			xtype:'htmleditor',
			name:'other_details',
			fieldLabel:'Other Details',
		},
        
    ]
});