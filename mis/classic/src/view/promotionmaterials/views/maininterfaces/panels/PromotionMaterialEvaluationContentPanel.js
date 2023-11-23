Ext.define('Admin.view.promotionmaterials.views.maininterfaces.panels.PromotionMaterialEvaluationContentPanel', {
    extend: 'Ext.panel.Panel',
    xtype: 'promotionmaterialevaluationcontentpanel',
        layout: {
        type: 'border'
    },
    defaults: {
        split: true
    },
    items: [
        {
            title: 'Evaluation Checklists',
            region: 'center',
            layout: 'fit',
            items: [
                {
                    xtype: 'premisescreeninggrid'
                }
            ]
        },
        {
            title: 'Other Details',
            region: 'east',
            width: 400,
            collapsed: true,
            collapsible: true,
            titleCollapse: true,
            items: [
                {
                    xtype: 'form',
                    bodyPadding: 5,
                    defaults: {
                        margin: 2,
                        labelAlign: 'top'
                    },
                    fieldDefaults: {
                        fieldStyle: {
                            'color': 'green',
                            'font-weight': 'bold'
                        }
                    },
                    items: [
                        {
                            xtype: 'displayfield',
                            fieldLabel: 'Applicant Details ',
                            name: 'applicant_details'
                        },
                        {
                            xtype: 'displayfield',
                            fieldLabel: 'Product Details',
                            name: 'product_details',
                            hidden: true
                        },
                        {
                            xtype: 'displayfield',
                            fieldLabel: 'Promotion And Advertisement Details',
                            name: 'promotion_materials_details',
                            hidden: true
                        },
                        {
                            xtype: 'toolbar',
                            ui: 'footer',
                            items: [
                                {
                                    text: 'More Details',
                                    iconCls: 'fa fa-bars',
                                    name: 'more_app_details',
                                    isReadOnly: 0,
                                    is_temporal: 0
                                }
                            ]
                        }
                    ]
                }
            ]
        },
        {
            xtype: 'toolbar',
            ui: 'footer',
            region: 'south',
            height: 45,
            split: false,
            items: [
                {
                    xtype: 'transitionsbtn'
                },{
                    text: 'Comments',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-weixin',
                    childXtype: 'applicationcommentspnl',
                    winTitle: 'Evaluation Comments',
                    winWidth: '60%',
                    comment_type_id: 2,
                    name: 'comments_btn',
                    stores: '[]'
                },
                {
                    text: 'Documents/Reports ',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-upload',
                    childXtype: 'premregappdocuploadsgenericgrid',
                    winTitle: 'Evaluation Documents',
                    name: 'docs_btn',
                    winWidth: '80%',
                    stores: '[]',
                    isWin: 1
                },
                {
                    text: 'Inspection Template',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-th-large',
                    childXtype: 'evaluationtemplatefrm',
                    winTitle: 'Inspection Template',
                    winWidth: '50%',
                    name: 'show_template',
                    stores: '[]',
                    hidden: true
                },
                '->',
                {
                    text: 'Save Evaluation Details ',
                    ui: 'soft-purple',
                    hidden: true,
                    iconCls: 'fa fa-save',
                    name: 'save_btn'
                },
                {
                    text: 'Submit Application',
                    ui: 'soft-purple',
                    iconCls: 'fa fa-check',
                    name: 'process_submission_btn',
                    storeID: 'foodpremiseregistrationstr',
                    table_name: 'tra_premises_applications',
                    winWidth: '50%'
                }
            ]
        }
    ]
});