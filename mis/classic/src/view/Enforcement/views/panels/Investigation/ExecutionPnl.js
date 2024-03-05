
 Ext.define('Admin.view.Enforcement.views.panels.Investigation.ExecutionPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'executionPnl',
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
                }, {
                    xtype: 'hiddenfield',
                    name: 'enforcement_id'
                }, {
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
                {
                    xtype: 'hiddenfield',
                    name: 'joint_operation_id'
                }, 
                {
                    xtype: 'hiddenfield',
                    name: 'joint_investigation_id'
                }, 
              
            ]
        }
    ],
    items: [
        {
            title: 'Investigation Decisions Execution Report Uploads',
            region: 'center',
            xtype:'tabpanel', 
            autoScroll: true,
           
            items: [                
                {
                    title: 'Execution  Report uploads',
                    itemId:'evaluation_panel1',
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
                text: 'View Report Details',
                iconCls: 'fa fa-eye',
                name: 'more_app_details',
                ui: 'soft-blue',
                isReadOnly: true,
                handler: 'showReportApplicationMoreDetails'
            },
            {
                    text: 'Case Documents',
                    iconCls: 'x-fa fa-file',
                    action: 'edit',
                    childXtype: '',
                    winTitle: 'Case Documents',
                    winWidth: '60%',
                    isReadOnly: 1,
                    document_type_id: '',
                    handler: 'showPreviousNonGridPanelUploadedDocs'
            },
        {
            text: 'Final Investigation Decision',
            ui: 'soft-blue', 
            iconCls: 'fa fa-clipboard-check',
            childXtype: 'caseDecisionPnl',
            //childXtype: 'decisionFrm',
            winTitle: 'Investigation Decision & Remarks',
            winWidth: '60%',
            handler:'showInvestigationDecisionwin',
         },
           {
            text: 'Print Investigation Report',
            xtype: 'button',
            ui: 'soft-blue',
            iconCls: 'x-fa fa-certificate',
            handler:'printEnforcementCertificate',
         },
            '->',{
                text: 'Submit',
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