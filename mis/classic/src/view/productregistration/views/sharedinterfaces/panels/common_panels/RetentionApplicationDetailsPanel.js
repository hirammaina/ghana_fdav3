/**
 * Created by softclans.
 */
Ext.define('Admin.view.productregistration.views.sharedinterfaces.common_panels.RetentionApplicationDetailsPanel', {
    extend: 'Ext.tab.Panel',
    xtype: 'retentionapplicationdetailspanel',
    controller: 'productregistrationvctr',
    itemId:'product_detailspanel',
    autoScroll: true,
    layout: {
        type: 'fit'
    },
    defaults:{
        margin: 3
    },
    viewModel: {
        type: 'productregistrationvm'
    },
    height: 550,
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
            },//drugproductdocuploadsgrid
            items: ['->', {
                xtype: 'displayfield',
                name: 'process_name',
                fieldLabel: 'Process',
                hidden: true,
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
                    name: 'workflow_stage', hidden: true,
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
                    name: 'application_status', hidden: true,
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
                    name: 'tracking_no', hidden: true,
                    fieldLabel: 'Tracking No',
                    fieldStyle: {
                        'color': 'green',
                        'font-weight': 'bold',
                        'font-size': '12px'
                    }
                },  {
                    xtype: 'displayfield',
                    name: 'reference_no', hidden: true,
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
                    name: 'product_id'
                }, {
                    xtype: 'hiddenfield',
                    name: 'applicant_id'
                }
            ]
        }
    ],
    items: [{
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
            xtype: 'hiddenfield',
            name: 'section_id'
        },{
            xtype: 'hiddenfield',
            name: 'prodclass_category_id'
        },{
            xtype: 'hiddenfield',
            name: 'product_id'
        }, {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        }
    ]
});