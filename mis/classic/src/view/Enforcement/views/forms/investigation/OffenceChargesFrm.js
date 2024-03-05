Ext.define('Admin.view.Enforcement.views.forms.investigation.OffenceChargesFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'offenceChargesFrm',
    itemId: 'offenceChargesFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'form'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
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
            xtype: 'combo', anyMatch: true,
            name: 'offence_type',
            allowBlank: true,
            queryMode: 'local',
            fieldLabel: 'offence details',
            valueField: 'id',
            displayField: 'details',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    // var grid = this.up('grid'),
                    // pnl = grid.up('enforcementinvestigationpnl');
                    // console.log(pnl);
                    // application_id = pnl.down('hiddenfield[name=application_id]').getValue();
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url:'enforcement/getSuspectedOffenceDetails',
                            extraParams:{
                                application_id:''
                                //table_name: 'par_case_decisions'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },
        {
            xtype:'textfield',
            name:'offennce_type',
            fieldLabel:'Offence Type',
            readOnly:true
        },
       
        {
            xtype:'textareafield',
            name:'Facts_alleged',
            fieldLabel:'Facts alleged',
        },
        {
            xtype:'textareafield',
            name:'charge_details',
            fieldLabel:'Potetial charge 1 Details',
        },
        {
            xtype:'textareafield',
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
        },      
//
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveCaseCharges',
            table_name: 'par_case_charges',
            storeID: 'offenceChargeGridStr',
            action: 'save_caseCharges_details',
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