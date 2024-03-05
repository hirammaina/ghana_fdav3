
 Ext.define('Admin.view.Enforcement.views.panels.JointOperations.ConductJointOperation', {
    extend: 'Ext.panel.Panel',
    xtype: 'conductjointoperation',
    controller: 'enforcementvctr',
    layout: {
        type: 'border'
    },
    defaults: {
        split: true
    },
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            ui: 'footer',
            height: 60,
            defaults: {
                labelAlign: 'top',
                margin: '-12 5 0 5',
                labelStyle: "color:#595959;font-size:13px"
            },
            items: ['->', {
                xtype: 'displayfield',
                name: 'process_name',
                fieldLabel: 'Process',
                fieldStyle: {
                    'color': 'green',
                    'font-weight': 'bold',
                    'font-size': '12px'
                }
            }, {
                    xtype: 'tbseparator',
                    width: 20
                }, {
                    xtype: 'displayfield',
                    name: 'workflow_stage',
                    fieldLabel: 'Workflow Stage',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                }, {
                    xtype: 'tbseparator',
                    width: 20
                }, {
                    xtype: 'displayfield',
                    name: 'application_status',
                    fieldLabel: 'Case Status',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                }, {
                    xtype: 'tbseparator',
                    width: 20
                }, {
                    xtype: 'displayfield',
                    name: 'tracking_no',
                    fieldLabel: 'Tracking No',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                }, {
                    xtype: 'displayfield',
                    name: 'reference_no',
                    fieldLabel: 'Ref No',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                },{
                    xtype: 'hiddenfield',
                    name: 'process_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'workflow_stage_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'active_application_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'active_application_code'
                }, {
                    xtype: 'hiddenfield',
                    name: 'application_status_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'module_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'sub_module_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'section_id'
                },
                 {
                    xtype: 'hiddenfield',
                    name: 'enforcement_id'
                },
                {
                    xtype: 'hiddenfield',
                    name: 'joint_operation_id'
                },
                 {
                    xtype: 'hiddenfield',
                    name: 'applicant_id'
                },
                {
                    xtype: 'hiddenfield',
                    name: 'report_type_id'
                }, 
                {
                    xtype: 'hiddenfield',
                    name: 'application_id'
                },
              
            ]
        }
    ],
    items: [
        {
            title: 'Joint Operation Reports Uploads',
            region: 'center',
            xtype:'tabpanel', 
            autoScroll: true,
           
            items: [
              
            // {
            //     title: 'Joint Operation Plan',
            //     layout:'fit', 
            //     xtype:'newDairyGrid', 
            //     margin:5,

            // },
            // {
            //     title: 'Operatives',
            //     layout:'fit', 
            //     xtype:'operativesGrid', 
            //     margin:5,
            // },
            {
                title: 'Offences Detected',
                layout:'fit', 
                xtype:'jointOperationOffenceGrid', 
                margin:5,

            },
            {
                title: 'Product Seized Report',
                layout:'fit', 
                xtype:'jointOperationProductGrid', 
                margin:5,
            },
            {
                title: 'Joint Operation Summary',
                layout:'fit', 
                xtype:'summaryGrid', 
                margin:5,
            },
            {
                title: 'Joint Operation Report Uploads',
                itemId:'evaluation_panel',
                layout:'fit', margin:5,
                items:[{
                    xtype: 'enforcementEvaluationUploadsGrid'
                }]
            },
        ]
        },
     
        {
            xtype: 'toolbar',
            ui: 'footer',
            region: 'south',
            height: 45,
            split: false,
            defaults: {
                margin: 5
            },
            items: [
                {
                    xtype: 'transitionsbtn'
                },
          
            {
                    text: 'Associated Documents',
                    iconCls: 'x-fa fa-file',
                    action: 'edit',
                    childXtype: '',
                    winTitle: ' Documents',
                    winWidth: '60%',
                    isReadOnly: 1,
                    document_type_id: '',
                    handler: 'showPreviousNonGridPanelUploadedDocs'
            },
            {
                text: 'View Plan details Details',
                iconCls: 'fa fa-eye',
                name: 'more_app_details',
                ui: 'soft-blue',
                isReadOnly: true,
                handler: 'showJointPlanMoreDetails'
            },
            '->',{
                text: 'Submit Application',
                ui: 'soft-blue',
                iconCls: 'fa fa-check',
                name: 'process_submission_btn',
                //storeID: 'managerEvaluationStr',
                table_name: '',
                winWidth: '50%'
            }]
        }
    ]
});