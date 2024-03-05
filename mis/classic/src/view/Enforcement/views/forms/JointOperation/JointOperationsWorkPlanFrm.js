Ext.define('Admin.view.Enforcement.views.forms.JointOperation.JointOperationsWorkPlanFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'jointOperationsWorkPlanFrm',
    itemId: 'jointOperationsWorkPlanFrm',
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
       // {
        //     xtype: 'hiddenfield',
        //     name: 'enforcement_id'
        // },
        {
            xtype: 'hiddenfield',
            name: 'joint_operation_id'
        },
        {
            xtype: 'hiddenfield',
            value: 'tra_enforcement_applications',
            name: 'table_name'
        },
        {
            xtype: 'hiddenfield',
            name: 'applicant_id'
        },
        {
            xtype: 'fieldcontainer',
            layout: 'column',
            name:'internal',
            defaults: {
                labelAlign: 'top'
            },
            fieldLabel: 'Organizing Officer',
            items: [
                {
                    xtype: 'textfield',
                    name: 'fullnames',
                    readOnly: true,
                    columnWidth: 0.9
                },
                {
                    xtype: 'button',
                    iconCls: 'x-fa fa-link',
                    columnWidth: 0.1,
                    tooltip: 'Link Applicant',
                    name: 'link_internal_applicant',
                    bind: {
                        disabled: '{isReadOnly}'
                    },
                    handler: 'showInternalUserSelectionList'
                }
            ]
        }, 
        {
            xtype: 'textfield',
            fieldLabel: 'Organizing Officer Title',
            name:'title',
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Organizing Department',
            identity:'department',
            name: 'department_name',
            valueField: 'department',
            displayField: 'department',
            forceSelection: true,
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
            xtype: 'textfield',
            fieldLabel: 'Officer Email',
            name: 'email',
            identity:'email',
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Officer Phone Number',
            name: 'phone',
            identity:'phone',
        },
        // {
        //     xtype: 'combo', anyMatch: true,
        //     fieldLabel: 'Organizing Department',
        //     identity:'department',
        //     name: 'department_id',
        //     valueField: 'id',
        //     displayField: 'name',
        //     allowBlank: true,
        //     forceSelection: true,
        //     fieldStyle: {
        //         'color': 'green',
        //         'font-weight': 'bold'
        //     },
        //     queryMode: 'local',
        //     listeners: {
        //         beforerender: {
        //             fn: 'setCompStore',
        //             config: {
        //                 proxy: {
        //                     extraParams:{
        //                         table_name: 'par_departments'
        //                     }
        //                 }
        //             },
        //             isLoad: true
        //         }, 
        //     }
            
        // },
        // {
        //     xtype: 'combo', anyMatch: true,
        //     fieldLabel: 'Organizing Officer',
        //     name: 'organizing_officer',
        //     valueField: 'name',
        //     displayField: 'name',
        //     forceSelection: true,
        //     readOnly:false,
        //     fieldStyle: {
        //         'color': 'green',
        //         'font-weight': 'bold'
        //     },
        //     queryMode: 'local',
        //     listeners: {
        //         beforerender: {
        //             fn: 'setCompStore',
        //             config: {
        //                 proxy: {
        //                     url:'enforcement/getusers'
        //                 }
        //             },
        //             isLoad: true
        //         }, 
        //     }
            
        // }, 
        {
            xtype: 'textfield',
            fieldLabel: 'Physical Address',
            name: 'address',
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'General Activities',
            name: 'activity',
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'General Objective',
            name: 'objective',
        },
        {
            xtype: 'htmleditor',
            fieldLabel: 'General Scope',
            name: 'scope',
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Start Date',
            name:'start_date',
        },
        {
            xtype: 'datefield',
            fieldLabel: 'End Date',
            name:'end_date',
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Other Internal Operative',
            name: 'internal_operative',
            allowBlank:true
        },
        {
            xtype: 'textfield',
            fieldLabel: 'External Operative',
            name: 'external_operative',
            allowBlank:true
        },
    
    ],
});