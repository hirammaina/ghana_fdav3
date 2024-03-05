Ext.define('Admin.view.Enforcement.views.forms.investigation.WorkplanFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'workplanfrm',
    itemId: 'workplanfrm',
    layout: {
        type: 'column'
    },
  
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
       
    }, autoScroll: true,
    items: [
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'workplan_id'
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Particular Name',
            identity:'matter_name',
            name:'matter_name',
            margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Particular Details',
            name:'matter_details',
            identity:'matter_details',
            margin: '0 20 20 0',
            allowBlank: false
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Particular Subject',
            margin: '0 20 20 0',
            name: 'investigation_subject',
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Investigation Type',
            margin: '20 20 20 20',
            margin: '0 20 20 0',
            name: 'investigation_type',
            valueField: 'name',
            displayField: 'name',
            forceSelection: true,
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
                            extraParams: {
                                table_name: 'par_investigation_types'
                            }
                        }
                    },
                    isLoad: true
                }, 
                       }
            
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Start Date',
            name:'start_date',
            identity:'start_date',
            margin: '0 20 20 0',
            allowBlank: false,
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
        {
            xtype: 'datefield',
            fieldLabel: 'End Date',
            name:'end_date',
            identity:'end_date',
            margin: '0 20 20 0',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00',
            allowBlank: false
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Facts Alleged',
            name: 'facts_alleged',
            columnWidth: 1,
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Investigation Details',
            name: 'remarks',
            columnWidth: 1,
        },
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveNewWorkPlanDetails',
            table_name: 'tra_enforcement_workplan_details',
           // storeID: 'investigationDiaryStr',
            action: 'save_investigation_diary'
        },
    ],
});