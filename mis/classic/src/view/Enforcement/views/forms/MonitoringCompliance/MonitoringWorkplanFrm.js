/**
 * Created by Softclans
 */
 Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.MonitoringWorkplanFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'annualWorkplanFrm',
    
    layout: 'column',
    controller:'enforcementvctr',
    bodyPadding: 5,
    defaults: {
        columnWidth: 1,
        margin: 2,
        labelAlign: 'top',
        allowBlank: false
    },
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        // {
        //     xtype: 'hiddenfield',
        //     name: 'annual_workplan_id'
        // },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Name/Identity',
            name: 'name'
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Description',
            name: 'description'
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Start Date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            name: 'start_date',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
        {
            xtype: 'datefield',
            fieldLabel: 'End Date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            name: 'end_date',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Other details',
            columnWidth: 1,
            name: 'details'
        }
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
                    handler: 'saveAnnualWorkplanDetails',
                    action_url: 'enforcement/saveUpdateAnnualWorkplanDetails',
                    table_name: 'par_annual_workplan_details',
                    storeID: 'annualworkplanstr'
                },{
                    xtype: 'button',
                    text: 'Clear',
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