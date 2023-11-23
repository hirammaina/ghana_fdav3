/**
 * Created by Kip on 11/22/2018.
 */
Ext.define('Admin.view.variation_configurations.views.forms.VariationsRequestConfigurationFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'variationsrequestconfigurationfrm',
    itemId: 'variationsrequestconfigurationfrm',
    controller: 'variationconfigurationsvctr',
    autoScroll: true,
    
    frame: true,
    bodyPadding: 8,
    layout: {
        type: 'column'
    },autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.49,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
    },
    items: [{
        xtype: 'hiddenfield',
        margin: '0 20 20 0',
        name: 'table_name',
        value: 'tra_variationsummary_guidelinesconfig',
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        margin: '0 20 20 0',
        name: '_token',
        value: token,
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        fieldLabel: 'id',
        margin: '0 20 20 0',
        name: 'id',
        allowBlank: true
    }, {
        xtype: 'combo',
        fieldLabel: 'Module',
        margin: '0 20 20 0',
        name: 'module_id',
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        allowBlank: false,
     
        queryMode: 'local',
        listeners: {
            afterrender: {
                fn: 'setWorkflowCombosStore',
                config: {
                    pageSize: 10000,
                    proxy: {
                        extraParams: {
                            model_name: 'Module'
                        }
                    }
                },
                isLoad: true
            },
            change: function (cmbo, newVal) {
                var form = cmbo.up('form'),
                    subModuleStore = form.down('combo[name=sub_module_id]').getStore();
                subModuleStore.removeAll();
                subModuleStore.load({ params: { module_id: newVal } });
            }
        }
    }, {
        xtype: 'combo',
        fieldLabel: 'Sub Module',
        margin: '0 20 20 0',
        name: 'sub_module_id',
        valueField: 'id',
        displayField: 'name', 
        forceSelection: true,
        allowBlank: false,
        queryMode: 'local',
        listeners: {
            afterrender: {
                fn: 'setWorkflowCombosStore',
                config: {
                    pageSize: 10000,
                    proxy: {
                        url: 'workflow/getSystemSubModules',
                        extraParams: {
                            model_name: 'SubModule'
                        }
                    }
                },
                isLoad: false
            }
        }
    }, {
        xtype: 'combo',
        fieldLabel: 'Section',
        margin: '0 20 20 0',
        name: 'section_id',
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        allowBlank: false,
        queryMode: 'local',
        listeners: {
            afterrender: {
                fn: 'setOrgConfigCombosStore',
                config: {
                    pageSize: 10000,
                    proxy: {
                        extraParams: {
                            model_name: 'Section'
                        }
                    }
                },
                isLoad: true
            },  change: function (cmbo, newVal) {
                var form = cmbo.up('form'),
                product_categorystore = form.down('combo[name=product_category_id]').getStore();

                filters=JSON.stringify({section_id: newVal});

                product_categorystore.removeAll();
                product_categorystore.load({ params: { filters: filters } });

            }
        }
    }, {
        xtype: 'combo',
        fieldLabel: 'Product Categories',
        margin: '0 20 20 0',
        name: 'product_category_id',
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        allowBlank: true,
        queryMode: 'local',
        listeners: {
            beforerender: {
                fn: 'setConfigCombosStore',
                config: {
                    pageSize: 1000,
                    proxy: {
                        url: 'commonparam/getCommonParamFromTable',
                        extraParams:{
                            table_name: 'par_product_categories'
                        }
                    }
                },
                isLoad: true
            },  change: function (cmbo, newVal) {
                var form = cmbo.up('form'),
                variation_categorystr = form.down('combo[name=variation_category_id]').getStore();
                filters=JSON.stringify({product_category_id: newVal});

                variation_categorystr.removeAll();
                variation_categorystr.load({ params: { filters: filters } });
            }
        }
    },{
        xtype:'fieldcontainer',
        columnWidth: 0.99,
        layout: {
            type: 'column'
        },
        items:[{
            xtype: 'combo',
            fieldLabel: 'Variation Categories',
            margin: '0 20 20 0',
            columnWidth: 0.97,
            name: 'variation_category_id',
            valueField: 'id',
            labelAlign: 'top',
            displayField: 'name',
            forceSelection: true,
            allowBlank: true,
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 1000,
                        storeId:'variationcategoriesdetailsstr', 
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams:{
                                table_name: 'par_variation_categories'
                            }
                        }
                    },
                    isLoad: false
                },  change: function (cmbo, newVal) {
                    var form = cmbo.up('form'),
                    variation_subcategorystr = form.down('combo[name=variation_subcategory_id]').getStore();
                    variation_subcategorystr.removeAll();
                    filters=JSON.stringify({variation_category_id: newVal});
    
                    variation_subcategorystr.removeAll();
                    variation_subcategorystr.load({ params: { filters: filters} });
    
                    variationconditions_detailstr = form.down('combo[name=variationconditions_detail_id]').getStore();
                    variationconditions_detailstr.removeAll();
                    variationconditions_detailstr.load({ params: { filters: filters} });
    
                    variationsupporting_datadocstr = form.down('combo[name=variationsupporting_datadoc_id]').getStore();
                    variationsupporting_datadocstr.removeAll();
                    variationsupporting_datadocstr.load({ params: { filters: filters} });
                    
                }
            }
        },{
            xtype: 'button',
            iconCls:'x-fa fa-plus',
            name: 'btn_addcommonnames',
            childXtype:'variationcategoriesdetailsfrm',
            margin:'28 0 0',
            table_name: 'par_variation_categories',
            storeId: 'variationcategoriesdetailsstr',
            bind: {
                disabled: '{isReadOnly}'  // negated
            }
        }]
    },
    {
        xtype:'fieldcontainer',
        columnWidth: 0.99,
        layout: {
            type: 'column'
        },
        items:[{
            xtype: 'combo',
            fieldLabel: 'Variation Sub-Categories',
            margin: '0 20 20 0',
            name: 'variation_subcategory_id',
            valueField: 'id',columnWidth: 0.97,
            displayField: 'name',
            forceSelection: true,
            allowBlank: true,
            labelAlign: 'top',
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 1000,
                        
                        storeId:'variationsubcategoriesstr', 
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams:{
                                table_name: 'par_variation_subcategories'
                            }
                        }
                    },
                    isLoad: false
                }, change: function (cmbo, newVal) {
                    var form = cmbo.up('form'),
                    variation_descriptionstr = form.down('combo[name=variation_description_id]').getStore();
                    variation_descriptionstr.removeAll();
    
                    filters=JSON.stringify({variation_subcategory_id: newVal});
    
                    variation_descriptionstr.load({ params: { filters: filters} });
    
                }
            }
        }, {
            xtype: 'button',
            iconCls:'x-fa fa-plus',
            name: 'btn_addcommonnames',
            childXtype:'variationsubcategoriesfrm',
            margin:'28 0 0',
            table_name: 'par_variation_subcategories',
            storeId: 'variationsubcategoriesstr',
            bind: {
                disabled: '{isReadOnly}'  // negated
            }
        }]
    },
    {
        xtype:'fieldcontainer',
        columnWidth: 0.99,
        layout: {
            type: 'column'
        },
        items:[{
            xtype: 'combo',
            fieldLabel: 'Variation Description',
            margin: '0 20 20 0',columnWidth: 0.97,
            name: 'variation_description_id',
            valueField: 'id',
            displayField: 'name',
            forceSelection: true,
            allowBlank: true,
            labelAlign: 'top',
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 1000,
                        storeId:'variationdescriptiondetailsstr', 
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams:{
                                table_name: 'par_variation_description'
                            }
                        }
                    },
                    isLoad: false
                }, change: function (cmbo, newVal) {
                    var form = cmbo.up('form'),
                    variation_subdescriptionstr = form.down('combo[name=variation_subdescription_id]').getStore();
                    variation_subdescriptionstr.removeAll();
    
                    filters=JSON.stringify({variation_description_id: newVal});
                    variation_subdescriptionstr.load({ params: { filters: filters} });

                    //check if have the sub

                }
            }
        }, {
            xtype: 'button',
            iconCls:'x-fa fa-plus',
            name: 'btn_addcommonnames',
            childXtype:'variationdescriptiondetailsfrm',
            margin:'28 0 0',
            table_name: 'par_variation_description',
            storeId: 'variationdescriptiondetailsstr',
            bind: {
                disabled: '{isReadOnly}'  // negated
            }
        }]
    },
    {
        xtype:'fieldcontainer',
        columnWidth: 0.99,
        name: 'variation_subdescriptionpnl',
        layout: {
            type: 'column'
        },
        items:[{
            xtype: 'combo',
            fieldLabel: 'Variation Sub-Description',
            margin: '0 20 20 0',columnWidth: 0.97,
            name: 'variation_subdescription_id',
            valueField: 'id',
            displayField: 'name',
            forceSelection: true,
            allowBlank: true,
            labelAlign: 'top',
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 1000,
                        storeId:'variationsubdescriptiondetailsstr', 
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams:{
                                table_name: 'par_variation_subdescription'
                            }
                        }
                    },
                    isLoad: false
                }
            }
        }, {
            xtype: 'button',
            iconCls:'x-fa fa-plus',
            name: 'btn_addcommonnames',
            childXtype:'variationsubdescriptiondetailsfrm',
            margin:'28 0 0',
            table_name: 'par_variation_subdescription',
            storeId: 'variationsubdescriptiondetailsstr',
            bind: {
                disabled: '{isReadOnly}'  // negated
            }
        }]
    },
    {
        xtype:'fieldcontainer',
        columnWidth: 0.99,
        layout: {
            type: 'column'
        },
        items:[{
            xtype: 'tagfield',
            fieldLabel: 'Conditions to be fulfilled',
            name: 'variationconditions_detail_id',
            allowBlank: true,columnWidth: 0.97,
            forceSelection: true,
            filterPickList: true,
            encodeSubmitValue: true,
            emptyText: 'Select Conditions to be fulfilled',
            growMax: 100,
            queryMode: 'local',
            valueField: 'id',
            labelAlign: 'top',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 10000,
                        storeId:'variationconditions_detailsstr', 
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_variationconditions_details'
                            }
                        }
                    },
                    isLoad: false
                }
            }, bind: {
                readOnly: '{isReadOnly}'  // negated
            }
        }, {
            xtype: 'button',
            iconCls:'x-fa fa-plus',
            name: 'btn_addcommonnames',
            childXtype:'variationconditions_detailsfrm',
            margin:'28 0 0',
            table_name: 'par_variationconditions_details',
            storeId: 'variationconditions_detailsstr',
            bind: {
                disabled: '{isReadOnly}'  // negated
            }
        }]
    },
    
    {
        xtype:'fieldcontainer',
        columnWidth: 0.99,
        layout: {
            type: 'column'
        },
        items:[ {
            xtype: 'tagfield',
            fieldLabel: 'Supporting data',
            name: 'variationsupporting_datadoc_id',
            allowBlank: true,columnWidth: 0.97,
            forceSelection: true,
            filterPickList: true,
            encodeSubmitValue: true,
            emptyText: 'Select Conditions to be fulfilled',
            growMax: 100,
            queryMode: 'local',
            valueField: 'id',
            labelAlign: 'top',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 10000,
                        storeId:'variationsupportingdatastr',
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_variationsupporting_datadocs'
                            }
                        }
                    },
                    isLoad: false
                }
            }, bind: {
                readOnly: '{isReadOnly}'  // negated
            }
        }, {
            xtype: 'button',
            iconCls:'x-fa fa-plus',
            name: 'btn_addcommonnames',
            childXtype:'variationsupportingdatafrm',
            margin:'28 0 0',
            table_name: 'par_variationsupporting_datadocs',
            storeId: 'variationsupportingdatastr',
            bind: {
                disabled: '{isReadOnly}'  // negated
            }
        }]
    },
   {
        xtype: 'combo',
        fieldLabel: 'Reporting Category/Type',
        margin: '0 20 20 0',
        name: 'variation_reportingtype_id',
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        allowBlank: true,
        queryMode: 'local',
        listeners: {
            beforerender: {
                fn: 'setConfigCombosStore',
                config: {
                    pageSize: 1000,
                    proxy: {
                        url: 'commonparam/getCommonParamFromTable',
                        extraParams:{
                            table_name: 'par_variation_reportingtypes'
                        }
                    }
                },
                isLoad: true
            }
        }
    }],
    dockedItems: [
        {
            xtype: 'toolbar',
            ui: 'footer',
            dock: 'bottom',
            items: [
                '->', {
                    text: 'Save Details',
                    iconCls: 'x-fa fa-save',
                    action: 'save',
                    table_name: 'tra_variationsummary_guidelinesconfig',
                    storeID: 'variationsrequestconfigurationstr',
                    formBind: true,
                    ui: 'soft-purple',
                    hasformFilter: false,
                    action_url: 'configurations/saveConfigVariationsRequestDetails',
                    handler: 'doCreateConfigParamWin'
                },{
                    text: 'Reset',
                    iconCls: 'x-fa fa-close',
                    ui: 'soft-purple',
                    handler: function (btn) {
                        btn.up('form').getForm().reset();
                    }
                }
            ]
        }
    ]
});