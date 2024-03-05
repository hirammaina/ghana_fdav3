Ext.define('Admin.view.Enforcement.views.forms.JointOperation.JointActivitiesFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'jointActivitiesform',
    itemId: 'jointActivitiesform',
    controller: 'enforcementvctr',
    height: Ext.Element.getViewportHeight() - 118,
    layout: {
        type: 'column'
    },
    autoScroll: true,
    bodyPadding: 1,
    defaults: {
        columnWidth: 0.5,
        margin: 2,
        labelAlign: 'top',
        allowBlank: false,
       
    },
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveActivityDetails',
            table_name: 'par_joint_activities_details',
            storeID: 'jointOperationActivitiesGridStr',
            action: 'genericsaveDetails'
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
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'activity_id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Activity',
            name: 'activity',
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Objective of Activity',
            name: 'objective',
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'Scope of Activity',
            name: 'scope',
           
        },
        {   
			xtype:'htmleditor',
			name:'other_details',
			fieldLabel:'Other Details',
		},
        
        {
            xtype: 'datefield',
            fieldLabel: 'Start Date of Activity',
            name:'start_date',
            columnWidth: 1,
        },
        {
            xtype: 'datefield',
            fieldLabel: 'End Date of Activity',
            name:'end_date',
            columnWidth: 1,
        },
        {
            xtype: 'tagfield',
            fieldLabel: 'Officer',
            name: 'officer',
            valueField: 'id',
            columnWidth: 1,
            displayField: 'name',
            forceSelection: true,
            filterPickList: true,
            encodeSubmitValue: true,
            queryMode: 'local',
            growMax: 10,
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
     
    ]
});