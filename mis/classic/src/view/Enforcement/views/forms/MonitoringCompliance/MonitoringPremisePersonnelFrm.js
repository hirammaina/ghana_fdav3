 Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.PremisePersonnelDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'monitoringpremisepersonnelfrm',
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
        {
            xtype: 'hiddenfield',
            name: 'table_name',
            value: 'par_monitoring_premise_personnel'
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Full Name',
            allowBlank: false,
            name: 'name'
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Telephone',
            allowBlank: false,
            name: 'telephone'
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Email',
            allowBlank: false,
            name: 'email'
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Position',
            name: 'position_id',
            valueField: 'id',
            displayField: 'name',
            queryMode: 'local',
            forceSelection: true,
            allowBlank: false,
            listeners   : {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                            extraParams: {
                                table_name: 'par_personnel_positions'
                            }
                        }
                    },
                    isLoad: true
                },
            }
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Qualification',
            name: 'qualification_id',
            valueField: 'id',
            displayField: 'name',
            queryMode: 'local',
            forceSelection: true,
            allowBlank: false,
            listeners   : {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                            extraParams: {
                                table_name: 'par_personnel_qualifications'
                            }
                        }
                    },
                    isLoad: true
                },

                beforequery: function(record){
                    record.query = new RegExp(record.query, 'ig');
                    record.forceAll = true;
                }
            }
        },       
        {
            xtype: 'textfield',
            fieldLabel: 'Registration No',
            allowBlank: false,
            name: 'reg_number'
        },
        {
            xtype: 'datefield',
            name: 'start_date',
            fieldLabel: 'Start Date',
            allowBlank: false,
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
        {
            xtype: 'datefield',
            name: 'end_date',
            fieldLabel: 'End Date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        }
    ],
    buttons: [
        {
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            action: 'genericsaveDetails',
            action_url: 'enforcement/saveMonitoringPremisePersonnel',
            table_name: 'par_monitoring_premise_personnel',
            storeID: 'monitoringpremisepersonnelgridstr'
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