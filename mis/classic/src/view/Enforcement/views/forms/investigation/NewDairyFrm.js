Ext.define('Admin.view.Enforcement.views.forms.investigation.NewDairyFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'newDairyFrm',
    itemId: 'newDairyFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.5,
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
            xtype: 'hiddenfield',
            name: 'enforcement_id',
        },
        {
            xtype: 'hiddenfield',
            name: 'application_id',
        },
        {
            xtype: 'hiddenfield',
            name: 'offence_type_id',
        },
        {
            xtype:'htmleditor',
            name:'action',
            fieldLabel:'Action',
            columnWidth: 0.5,
        },
        {
            xtype:'htmleditor',
            name:'remarks',
            fieldLabel:'Remarks',
            columnWidth: 0.5,

        },    
        {
            xtype:'datefield',
            name:'date',
            fieldLabel:'Date of Action',
            columnWidth: 0.5,
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'

        },
        {
            xtype: 'timefield',
            fieldLabel:'Time',
            name: 'action_time',
            columnWidth: 0.5,
            format: 'H:i',
            altFormats:'H:i',
            increment: 30,
        }
//
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveNewDairyitem',
            table_name: 'par_case_witness_details',
            storeID: 'newDairyGridStr',
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