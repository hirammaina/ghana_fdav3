Ext.define('Admin.view.Enforcement.views.forms.investigation.Chargefrm', {
    extend: 'Ext.form.Panel',
    xtype: 'chargefrm',
    itemId: 'chargefrm',
    controller: 'enforcementvctr',
    layout:'column',
    frame: true,
     autoScroll: true,
     scrollable: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.5,
        margin: 3,
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
            name: 'application_id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Offence',
            identity:'Offence_type',
            name: 'offence_id',
            allowBlank: false,
            valueField: 'id',
            displayField: 'name',
            forceSelection: true,
            columnWidth: 1,
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
                            url:'enforcement/getCaseOffences'
                        },
                    },
                    isLoad: true
                },
            },         
        },
        {
            xtype:'htmleditor',
            name:'charge_details',
            fieldLabel:'Details',
        },
        {
            xtype:'htmleditor',
            name:'charge_regulation',
            fieldLabel:'Act/regulation',
        },
        {
            xtype:'textareafield',
            name:'charge_section',
            fieldLabel:'Section/reg number',
        },
        {
            xtype:'textareafield',
            name:'charge_elements',
            fieldLabel:'Elements',
        }
        
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