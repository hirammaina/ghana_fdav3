/**
 * Created by Softclans
 */
 Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.PrescribingComplianceFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'prescribingcomplianceFrm',
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
            fieldLabel: 'Prescription Name',
            name: 'medicine_name'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Patient`s Particulars',
            name: 'patient_particulars'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Medicine Details',
            name: 'medicine_details'
        }, 
        {
            xtype: 'numberfield',
            fieldLabel: 'Prescriber Details',
            name: 'prescriber_details'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Prescription Date',
            name: 'prescription_date'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Facility Stamp',
            name: 'facility_stamp'
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
                    table_name: 'par_prescribing_compliance_data',
                    storeID: 'prescribingComplianceGridStr'
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