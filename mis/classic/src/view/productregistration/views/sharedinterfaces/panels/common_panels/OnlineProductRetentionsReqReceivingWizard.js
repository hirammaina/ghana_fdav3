/**
 * Created by softclans
 */
Ext.define('Admin.view.productregistration.views.sharedinterfaces.panels.common_panels.OnlineProductRetentionsReqReceivingWizard', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.onlineproductretentionsreqreceivingwizard',
    padding: '2 0 2 0',
    requires: [
        'Ext.layout.container.*',
        'Ext.toolbar.Fill'
    ],
    reference: 'wizardpnl',
    layout: 'card',
    height: 600,
    //bodyPadding: 3,
    flex: 1,
    controller: 'productregistrationvctr',
    autoScroll: true,
    cls: 'wizard three shadow',
    colorScheme: 'soft-green',
    viewModel: {
        type: 'productregistrationvm'
    },
    dockedItems: [{
            xtype: 'toolbar',
            dock: 'top',
            ui: 'footer',
            height: 40,
            defaults: {
                labelAlign: 'top',
                margin: '-12 5 0 5',
                labelStyle: "color:#595959;font-size:11px"
            },
            items: ['->', {
                xtype: 'tbspacer',
                width: 20
            },{
                xtype: 'displayfield',
                name: 'process_name',
                value: '****',
                fieldLabel: 'Process',
                fieldStyle: {
                    'color': 'green',
                    'font-weight': 'bold',
                    'font-size': '12px',
                    'margin-top': '-2px'
                }

            },  {
                    xtype: 'tbspacer',
                    width: 20
                }, {
                    xtype: 'displayfield',
                    name: 'application_status',
                    fieldLabel: 'App Status',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                                             'font-size': '12px',                     'margin-top': '-2px'
                    }
                }, {
                    xtype: 'tbspacer',
                    width: 20
                }, {
                    xtype: 'displayfield',
                    name: 'tracking_no',
                    fieldLabel: 'Tracking No',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                                             'font-size': '12px',                     'margin-top': '-2px'
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
                    name: 'module_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'sub_module_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'section_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'active_application_code'
                }, {
                    xtype: 'hiddenfield',
                    name: 'application_status_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'status_type_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'is_manager_query'
                }, {
                    xtype: 'hiddenfield',
                    name: 'last_query_ref_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'prodclass_category_id'
                }]
        }
    ],
    items: [
        {
            xtype: 'tabpanel',
            layout: 'fit',
            defaults: {
                margin: 3
            },
            items: [{
                xtype: 'productapplicantdetailsfrm',
                title: 'APPLICANT DETAILS'
            },
            {
                xtype: 'productlocalapplicantdetailsfrm',
                hidden: true,
                title: 'LOCAL AGENT DETAILS'
            }]
        },
        {
            xtype: 'onlineproductsretentionsdetailspnl',
            dockedItems: [
                {
                    xtype: 'toolbar',
                    ui: 'footer',
                    dock: 'top',
                    margin: 3,
                    items: [
                        {
                            xtype: 'tbspacer',
                            width: 2
                        },
                        {
                            xtype: 'combo',
                            fieldLabel: 'Branch',
                            labelWidth: 50,
                            width: 400,
                            name: 'zone_id',
                            hidden: true,
                            valueField: 'id',
                            displayField: 'name',
                            queryMode: 'local',
                            bind: {
                                readOnly: '{isReadOnly}'
                            },
                            forceSelection: true,
                            listeners: {
                                afterrender: {
                                    fn: 'setConfigCombosStore',
                                    config: {
                                        pageSize: 10000,
                                        proxy: {
                                            url: 'configurations/getRegistrationApplicationParameters',
                                            extraParams: {
                                                table_name: 'par_zones'
                                            }
                                        }
                                    },
                                    isLoad: true
                                }
                            },
                            labelStyle: 'font-weight:bold'
                        }, {
                            xtype: 'tbseparator',
                            width: 2
                        }
                    ]
                }
            ],
        }, {
            xtype: 'tabpanel',
            autoScroll: true,
            title:'Documents Submissions',
            items: [{
                xtype: 'onlineproductdocuploadsgrid',
                title: 'Product Application Documents Submission'
            },{
                xtype: 'productapplicationqueriesgrid',//'premisescreeninggrid'
                name: 'querieschecklistgrid',
                bind:{
                    title:'{prechecking_querytitle}'
                }
            }]
        },{
            title: 'Invoice & Payment Details',
            xtype: 'onlineappinvoicepaymentspanel',
        }
    ],
    initComponent: function () {
        var me = this;
        this.tbar = {
            reference: 'progress',
            itemId: 'progress_tbar',
            defaultButtonUI: 'wizard-' + this.colorScheme,
            cls: 'wizardprogressbar',
            style: {
                "background-color": "#90c258"
            },
            bodyStyle: {
                "background-color": "#90c258"
            },
            layout: {
                pack: 'center'
            },
            items: [
                {
                    step: 0,
                    iconCls: 'fa fa-user',
                    enableToggle: true,
                    pressed: true,
                    text: 'Applicant & Local Agent Details',
                    action: 'quickNav',
                    wizard: 'onlineproductretentionsreqreceivingwizard',
                    handler: 'quickNavigationonlineprev'
                },
                {
                    step: 1,
                    iconCls: 'fa fa-university',
                    enableToggle: true,
                    text: 'Products Retention Selection Details',
                    action: 'quickNav', 
                    wizard: 'onlineproductretentionsreqreceivingwizard',
                    handler: 'quickNavigationonlineprev'
                }, {
                    step: 2,
                    iconCls: 'fa fa-upload',
                    enableToggle: true,
                    text: 'Application Documents Submission',
                    action: 'quickNav', 
                    wizard: 'onlineproductretentionsreqreceivingwizard',
                    handler: 'quickNavigationonlineprev',
                    bind:{
                        text:'{prechecking_querytitle}'
                    }
                },{
                    step: 3,
                    iconCls: 'fa fa-money',
                    enableToggle: true,
                    text: 'Invoice & Payment Details',
                    action: 'quickNav',
                    wizard: 'onlineproductretentionsreqreceivingwizard',
                    handler: 'quickNavigationonlineprev',
                }
                
            ]
        };
       
        this.bbar = {
            reference: 'navigation-toolbar',
            ui: 'footer',
            items: [
                {
                    text: 'Previous',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-arrow-left',
                    bind: {
                        disabled: '{atBeginning}'
                    }, wizard: 'onlineproductretentionsreqreceivingwizard',
                    handler: 'onPrevCardClickOnline'
                },
                {
                    text: 'Actions',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-bars',
                    name: 'actions',
                    hidden: true,
                    menu:{
                        xtype: 'menu',
                        items:[
                            {
                                text: 'Dismiss Rejection And Receive Application',
                                iconCls: 'x-fa fa-arrow-right',
                                name: 'action_dismiss1',
                                handler: 'receiveOnlineApplicationDetailsFrmBtn',
                                storeID: 'onlinepremregistrationstr',
                                winWidth: '50%'
                            },
                            '-',
                            {
                                text: 'Dismiss Rejection And Send To Rejecting Officer',
                                iconCls: 'x-fa fa-arrow-right',
                                name: 'action_dismiss2',
                                handler: 'submitManagerRejectedOnlineApplicationFrmBtn',
                                application_status: 24
                            },
                            '-',
                            {
                                text: 'Approve Rejection And Send Application To Trader',
                                iconCls: 'x-fa fa-arrow-right',
                                name: 'action_approve',
                                handler: 'submitManagerRejectedOnlineApplicationFrmBtn',
                                application_status: 18
                            }
                        ]
                    }
                },
                {
                    text: 'Previous Rejections',
                    ui: 'soft-purple',
                    iconCls: 'x-fa fa-thumbs-down',
                    hidden: true,
                    name: 'prev_rejections',
                    handler: 'showOnlineApplicationRejections',
                    childXtype: 'onlineappsrejectionsgrid',
                    winWidth: '60%'
                },{
                    text: 'Preview Queries',
                    ui: 'soft-purple',
                    hidden: true,
                    iconCls: 'x-fa fa-bars',
                    name: 'preview_queries_btn',
                    handler: 'previewQueriesFromOnlineApp'
                }, {
                    text: 'Reject Application',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-thumbs-down',
                    name: 'save_btn',hidden: true,
                    handler: 'submitWinRejectedOnlineApplication',
                    action: 'reject_app',
                   
                }, {
                    text: 'Update Product Application',
                    ui: 'soft-green',
                    iconCls: 'fa  fa-thumbs-up',
                    winWidth: '50%', hidden: true,
                    storeID: 'onlineproductregistrationstr',
                    winWidth: '50%',
                    wizard: 'onlineproductretentionsreqreceivingwizard',
                    name: 'save_productapplications',
                }, 
                '->',
              
                {
                    text: ' Pre-Checking Recommendation',
                    ui: 'soft-red',
                    iconCls: 'fa fa-save',
                    name: 'prechecking_recommendation',
                    
                }
                
                ,{
                    text: 'Receive & Submit Application',
                    ui: 'soft-green',
                    iconCls: 'fa  fa-thumbs-up',
                    winWidth: '50%',
                    hidden: true,
                    storeID: 'onlineproductregistrationstr',
                    winWidth: '50%',
                    name: 'submit_btn',
                    
                },
                
                {
                    text: 'Next',
                    ui: 'soft-purple',
                    reference: 'nextbutton',
                    iconCls: 'fa fa-arrow-right',
                    iconAlign: 'right',
                    bind: {
                        disabled: '{atEnd}'
                    },
                    wizard: 'onlineproductretentionsreqreceivingwizard',
                    handler: 'onNextCardClickOnline'
                }
            ]
        },
        me.callParent(arguments);
    }
});
