
 Ext.define('Admin.view.Enforcement.views.panels.Investigation.EnforcementInvestigationPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'enforcementinvestigationpnl',
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
            title: 'Case Details & Investigation Diary & Uploads',
            region: 'center',
            xtype:'tabpanel', 
            autoScroll: true,
           
            items: [
                {
                    title: 'View Work Plan Details ',
                    layout:'fit', 
                    itemId:'work_panel',
                    xtype:'tabpanel', 
                    margin:5,
                    items:[{
                        title: 'Work Plan Details',
                        xtype: 'viewWorkPlanFrm'
                    },

                     {
                        title: 'Offence Details',
                        xtype: 'witnessgrid'
                    },
                    {
                        title: 'Charge Details',
                        xtype: 'investigationdiarygrid'
                    },
                    {
                        title: 'Witness Details',
                        xtype: 'newWitnessGrid'
                    },
                    {
                        title: 'Inquiry Details',
                        xtype: 'inquiryGrid'
                    },
                    {
                        title: 'Timeline Details',
                        xtype: 'timelineGrid'
                    }, 
                      
                   
                ]
                },
            
         
            //  {
            //         title: 'Offence Charge & Witness,Investigation Dairy',
            //          layout:'fit', 
            //          itemId:'diary_panel',
            //          xtype:'tabpanel', 
            //          margin:5,
            //          items:[
                    
            //          {
            //             title: 'Offence Charge & Witness Details',
            //             xtype: 'witnessgrid'
            //         },
            //         //     {
            //         //      title: 'Investigation Dairy',
            //         //      xtype: 'investigationdiarygrid'
            //         //   },
                      
            //     ]
                    
            //  },
            {
                title: 'Investigation Diary',
                layout:'fit', 
                xtype:'newDairyGrid', 
                margin:5,

            },
             
            // {
            //     title: 'Product Involved Report',
            //     layout:'fit', 
            //     xtype:'viewProductSeizureReportGrid', 
            //     margin:5,

            // },
            // {
            //     title: 'Product Exhibit Report',
            //     layout:'fit', 
            //     xtype:'exhibitReportGrid', 
            //     margin:5,
            // },
            {
                title: 'Investigation Report Uploads',
                itemId:'evaluation_panel',
                layout:'fit', margin:5,
                items:[{
                    xtype: 'enforcementEvaluationUploadsGrid'
                }]
            },
             {
                title: 'Investigation Decision',
                layout:'fit', 
                xtype:'caseDecisionPnl', 
                margin:5,
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
          
        // {
        //     text: 'Add Case Investigation Comments',
        //     ui: 'soft-blue',
        //     iconCls: 'fa fa-clipboard-check',
        //     childXtype: 'investigationCommentsPnl',
        //     winTitle: 'Investigation Process Comments',
        //     winWidth: '60%',
        //     //name:'prev_comments',
        //     comment_type_id: 1,
        //     stores: '[]',
        //     handler:'addInvestigationCommentsLogs'
        // },
       
            '->',{
                text: 'Submit Investigation Report',
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