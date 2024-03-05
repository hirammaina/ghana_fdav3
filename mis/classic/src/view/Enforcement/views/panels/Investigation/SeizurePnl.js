Ext.define('Admin.view.Enforcement.views.panels.investigation.SeizurePnl', {
    extend: 'Ext.panel.Panel',
    xtype:'seizurePnl',
    controller: 'enforcementvctr',
    padding: '2 0 2 0',
    requires: [
        'Ext.layout.container.*',
        'Ext.toolbar.Fill'
    ],
    reference: 'wizardpnl',
    itemId: 'wizardpnl',
    layout: 'border',
    //bodyPadding: 3,
    flex: 1,
    autoScroll: true,
    cls: 'wizard three shadow',
    colorScheme: 'soft-blue',
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            // hidden: true,
            ui: 'footer',
            height: 60,
            defaults: {
                labelAlign: 'top',
                margin: '-12 5 0 5',
                labelStyle: "color:#595959;font-size:13px"
            },//drugproductdocuploadsgrid
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
                    fieldLabel: 'App Status',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                }, {
                    xtype: 'tbseparator',
                    width: 20
                },{
                    xtype: 'displayfield',
                    name: 'tracking_no',
                    fieldLabel: 'Tracking No',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                },  {
                    xtype: 'displayfield',
                    name: 'reference_no',
                    fieldLabel: 'Ref No',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                }, {
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
                    name: 'status_type_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'report_type_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'reported_by_id'
                },
                {
                    xtype: 'hiddenfield',
                    name: 'joint_investigation_id'
                },
                {
                    xtype: 'hiddenfield',
                    name: 'joint_operation_id'
                }, 
            ]
        }
    ],
            
    items: [
        {
            title: ' Product Seizure Details',
            region: 'center',
            xtype:'tabpanel', 
            autoScroll: true,
            items:[   
        
            // {
            //     title: 'Seizure Inspection Details',
            //     layout:'fit', 
            //     itemId:'inspection_panel',
            //     xtype: 'seizureinspectiongrid',
            //    // xtype:'seizureinspectiongrid', 
            //     margin:5,

            // }, 
            {
                title: 'Seizure Work Plan',
                xtype: 'seizureWorkPlanFrm'
            },
            {
                title: 'Particulars and Witness Details',
                layout:'fit', 
                itemId:'witness_panel',
                //xtype: 'seizureGrid',
                xtype:'seizurepersonnelgrid', 
                margin:5,

            },
            {
                title: 'Seizure Product Details',
                xtype: 'newProductseizureGrid'
            },
            {
                title: 'Seizure Case Details',
                xtype: 'seizureGrid'
            },
            {
                title: 'Seizure Checklist',
                xtype: 'productscreeninggrid'
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
            items: [{
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
                text: 'Investigation Decisions & Comments',
                ui: 'soft-blue', 
                iconCls: 'fa fa-clipboard-check',
                childXtype: 'caseDecisionPnl',
                //childXtype: 'decisionFrm',
                winTitle: 'Inspection/Seizure & Remarks',
                winWidth: '60%',
                handler:'showInvestigationDecisionwin',
        },
            '->',{
                text: 'Submit',
                ui: 'soft-blue',
                iconCls: 'fa fa-check',
                name: 'process_submission_btn',
                //storeID: 'managerEvaluationStr',
                table_name: '',
                winWidth: '50%'
            }

        ]
        }
        ],  
         
         
});
