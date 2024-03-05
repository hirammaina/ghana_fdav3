Ext.define('Admin.view.Enforcement.views.forms.investigation.DecisionFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'casedecisionFrm',
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
            name: 'id',
        },
        {   
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        }, {
            xtype: 'combo', anyMatch: true,
            name: 'case_decision_id',
            allowBlank: true,
            queryMode: 'local',
            fieldLabel: 'Investigation Decision',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            extraParams:{
                                table_name: 'par_case_decisions'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },
        {
            xtype: 'datefield',
            name: 'investigation_start_date',
            columnWidth: 1,
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            maxValue: new Date(),
            fieldLabel: 'Investigation Start Date',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00',
            allowBlank: true,
        },{
            xtype: 'datefield',
            name: 'investigation_end_date',
            columnWidth: 1,
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            maxValue: new Date(),
            fieldLabel: 'Investigation  End Date',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00',
            allowBlank: true
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
                text: 'Save Decision',
                iconCls: 'x-fa fa-save',
                table_name: '',
                storeID: 'casedecisionsstr',
                formBind: true,
                name:'save_comment',
                ui: 'soft-blue',
            }
        ]
    }
    ]
});