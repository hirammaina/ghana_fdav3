Ext.define('Admin.view.Enforcement.views.forms.investigation.TimelineFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'timelineFrm',
    itemId: 'timelineFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'form'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 2,
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
            xtype:'htmleditor',
            name:'action',
            fieldLabel:'Action',
        },
        {
            xtype:'datefield',
            name:'date',
            fieldLabel:'Date of Action',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
        {
            xtype: 'timefield',
            fieldLabel:'Time',
            name: 'action_time',
            format: 'H:i',
            altFormats:'H:i',
            increment: 30,
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Assigned officer',
            name: 'assigned_officer',
            valueField: 'id',
            displayField: 'name',
            forceSelection: true,
            readOnly:false,
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
                            url:'enforcement/getusers'
                        }
                    },
                    isLoad: true
                }, 
            }
            
        },
       
        {
            xtype:'numberfield',
            name:'action_duration',
            fieldLabel:'Action Duration(Days)', 
        },
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveTimelineDetails',
            table_name: 'par_investigation_timeline',
            storeID: 'timelineGridStr',
            action: 'save_caseCharges_details',
        },
        {
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