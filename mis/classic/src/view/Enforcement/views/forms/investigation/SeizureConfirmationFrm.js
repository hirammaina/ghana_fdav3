Ext.define('Admin.view.Enforcement.views.forms.investigation.SeizureConfirmationFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'seizureConfirmationFrm',
    layout: 'column',
    bodyPadding: 5,
    controller: 'enforcementvctr',
    defaults: {
        margin: 5,
        labelAlign: 'top',
        width: '100%',
        allowBlank: false,
        columnWidth: 1
    },
    scrollable: true,
    autoScroll: true,
    items: [{   
            xtype: 'hiddenfield',
            name: 'approval_id',
            allowBlank: true
        },
        {   
            xtype: 'hiddenfield',
            name: 'application_code'
        },
        {   
            xtype: 'hiddenfield',
            name: 'sub_module_id'
        },
        {   
            xtype: 'hiddenfield',
            name: 'module_id'
        },
        {   
            xtype: 'hiddenfield',
            name: 'product_id'
        },
        {   
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        }, {
            xtype: 'combo', anyMatch: true,
            name: 'seizure_confirmation_id',
            allowBlank: true,
            queryMode: 'local',
            fieldLabel: 'Seizure Confirmation',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            extraParams:{
                                table_name: 'par_seizure_confirmation'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },
        {
            xtype: 'htmleditor',
            columnWidth: 1,
            name: 'remarks',
            isFocusable: true,
            fieldLabel:'Remarks',
            emptyText: 'Any Remarks...',
            allowBlank: false,
        }
    ],
    dockedItems: [{
        xtype: 'toolbar',
        ui: 'footer',
        dock: 'bottom',
        items: [
            '->', {
                text: 'Save',
                iconCls: 'x-fa fa-save',
                action: 'save',
                table_name: '',
                storeID: 'newProductseizureGridStr',
                formBind: true,
                ui: 'soft-blue',
                action_url: 'enforcement/onSaveSeizureConfirmation',
                handler: 'saveinvestigationApprovaldetails'
            }
        ]
    }
    ]
});