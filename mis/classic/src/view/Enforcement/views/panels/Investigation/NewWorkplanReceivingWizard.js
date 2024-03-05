Ext.define('Admin.view.Enforcement.views.panels.investigation.NewWorkplanReceivingWizard', {
    extend: 'Ext.panel.Panel',
    xtype: 'newworkplanreceivingwizard',
    layout: 'border',
    defaults: {
        split: true,
    },
    items: [
        {
            xtype: 'hiddenfield',
            name: 'active_application_code'
        },
        {
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
            name: 'joint_investigation_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'joint_operation_id'
        }, 
        {
            title: 'Case Details & Work pLan Details',
            region: 'center',
            xtype:'tabpanel', 
            autoScroll: true,
           
            items: [
                {
                    title: 'Paricular Details ',
                    layout:'fit', 
                    itemId:'work_panel',
                    xtype:'tabpanel', 
                    margin:5,
                    items:[{
                        title: 'Paricular Details',
                        xtype: 'workplanfrm'
                    },     
                ]
                }, 
                {
                    title: 'Offence  Details',
                    xtype: 'witnessgrid'
                }, 
                {
                    title: 'Potential charges Details',
                    xtype: 'investigationdiarygrid'
                },
                {
                    title: 'Complainant/ Witness Details',
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
            //     {
            //         title: 'Offence Charges & Witness',
            //          layout:'fit', 
            //          itemId:'diary_panel',
            //          xtype:'tabpanel', 
            //          margin:5,
            //          items:[ 
            //          {
            //             title: 'Offence Charge & Witness Details',
            //             xtype: 'witnessgrid'
            //         },     
            //     ]
            //  }  
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
