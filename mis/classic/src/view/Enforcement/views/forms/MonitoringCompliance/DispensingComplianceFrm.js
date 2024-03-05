/**
 * Created by Softclans
 */
 Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.DispensingComplianceFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'dispensingcomplianceFrm',
    controller:'enforcementvctr',
    layout: {
        type: 'column'
    },
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
       
    }, 
    autoScroll: true,
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
            fieldLabel: 'Dispensing Name',
            name: 'dispensing_name'
        },
        {
            xtype: 'textfield',
            fieldLabel: 'BHPC Registration Number',
            name: 'reg_number'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Invoice number of assessed copy',
            name: 'invoice_no'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Patients Full Name',
            name: 'patient_name'
        }, 
        {
            xtype: 'numberfield',
            fieldLabel: 'Dispensing Date',
            name: 'dispensing_date'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Dispensed pack size',
            name: 'dispensed_packsize'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Dispenser’s name and signature',
            name: 'dispenser_name_signature'
        },
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
                    action: 'saveDetails',
                    action_url: 'enforcement/genericSaveUpdate',
                    table_name: 'par_dispensing_compliance_data',
                    storeID: 'dispensingComplianceGridStr'
                },
                {
                    xtype: 'button',
                    text: 'Clear Form',
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