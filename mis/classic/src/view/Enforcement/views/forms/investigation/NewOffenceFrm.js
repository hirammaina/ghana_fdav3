Ext.define('Admin.view.Enforcement.views.forms.investigation.NewOffenceFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'newOffenceFrm',
    itemId: 'newOffenceFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    autoScroll: true,
    scrollable:true,
    bodyPadding: 5,
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
            action_url: 'enforcement/saveUpdateSuspectedOffence',
            table_name: 'par_suspected_offence',
            storeID: 'witnessGridStr',
            action: 'save_suspected_offence_details'
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
            name: 'isReadOnly'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Offence Type',
            identity:'Offence_type',
            name: 'offence_type_id',
            allowBlank: true,
            valueField: 'id',
            displayField: 'name',
            forceSelection: true,
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
                            extraParams: {
                                table_name: 'par_offence_types'
                            }
                        }
                    },
                    isLoad: true
                }, 
            }
            
        },{   
			xtype:'textfield',
			name:'place',
			fieldLabel:'Place of Offence',
		},
        {
            xtype: 'datefield',
            fieldLabel: 'Date Offence Reported',
            name: 'offence_date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },{   
			xtype:'htmleditor',
			name:'details',
			fieldLabel:'Other Details',
		},
        
    ]
});