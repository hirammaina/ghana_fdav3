Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.ControlledDispensingFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'controlledDispensingfrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    bodyPadding: 5,
    defaults: {
        margin: 4,
        columnWidth: 0.5,
        labelAlign: 'top'
    },
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
        // {
        //     xtype: 'hiddenfield',
        //     name: 'table_name',
        //     value: 'par_controlled_dispensing_data'
        // },
        {
            xtype: 'textfield',
            fieldLabel: 'Dispensed medicine`s name',
            allowBlank: false,
            name: 'medicine_name'
        },
        {
            xtype: 'combo', anyMatch: true,
            queryMode: 'local',
            forceSelection: true,
            valueField: 'id',
            displayField: 'name',
            fieldLabel: 'Schedule of Medicine',
            name: 'product_schedule',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                            extraParams: {
                                table_name: 'par_schedule_types'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Dispensers Name',
            allowBlank: false,
            name: 'dispensers_name'
        },   
        {
            xtype: 'textfield',
            fieldLabel: 'BHPC Registration No',
            allowBlank: false,
            name: 'reg_number'
        },
        {
            xtype: 'combo', anyMatch: true,
            queryMode: 'local',
            forceSelection: true,
            valueField: 'id',
            displayField: 'name',
            fieldLabel: 'Is Dispenser Authorized?',
            name: 'dispenser_authorized',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                            url: 'configurations/getConfigParamFromTable',
                            extraParams: {
                                table_name: 'par_confirmations'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },
    ],
    buttons: [
        {
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            action: 'genericsaveDetails',
            action_url: 'enforcement/genericSaveUpdate',
            table_name: 'par_controlled_dispensing_data',
            storeID: 'controlledDispensingDataGrid'
        },
        {
            xtype: 'button',
            text: 'Reset',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-close',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ]
});