/**
 * Created by Kip on 11/22/2018.
 */
Ext.define('Admin.view.variation_configurations.views.forms.VariationSubDescriptionDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'variationsubdescriptiondetailsfrm',
    controller: 'variationconfigurationsvctr',
    autoScroll: true,
    layout: 'form',
    frame: true,
    bodyPadding: 8,
    defaults: {
        labelAlign: 'top',
        allowBlank: false,
        split: true
    },
    items: [{
        xtype: 'hiddenfield',
        margin: '0 20 20 0',
        name: 'table_name',
        value: 'par_variation_description',
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
            },   change: function (cmbo, newVal) {
                var form = cmbo.up('form'),
                product_categorystr = form.down('combo[name=product_category_id]').getStore();
                filters=JSON.stringify({section_id: newVal});

                product_categorystr.removeAll();
                product_categorystr.load({ params: { filters: filters } });
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
                variation_categorystr.removeAll();
                variation_categorystr.load({ params: { product_category_id: newVal} });
            }
        }
    },{
        xtype: 'combo',
        fieldLabel: 'Variation Categories',
        margin: '0 20 20 0',
        name: 'variation_category_id',
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
                            table_name: 'par_variation_categories'
                        }
                    }
                },
                isLoad: true
            },  change: function (cmbo, newVal) {
                var form = cmbo.up('form'),
                variation_subcategorystr = form.down('combo[name=variation_subcategory_id]').getStore();
                variation_subcategorystr.removeAll();
                variation_subcategorystr.load({ params: { variation_category_id: newVal} });
            }
        }
    },{
        xtype: 'combo',
        fieldLabel: 'Variation Sub-Categories',
        margin: '0 20 20 0',
        name: 'variation_subcategory_id',
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
                            table_name: 'par_variation_subcategories'
                        }
                    }
                },
                isLoad: true
            }
        }
    }, {
        xtype: 'combo',
        fieldLabel: 'Variation Description',
        margin: '0 20 20 0',
        name: 'variation_description_id',
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
                            table_name: 'par_variation_description'
                        }
                    }
                },
                isLoad: true
            }
        }
    }, {
        xtype: 'textfield',
        fieldLabel: 'Name',
        margin: '0 20 20 0',
        name: 'name',
        allowBlank: false
    }, {
        xtype: 'textfield',
        fieldLabel: 'Code',
        margin: '0 20 20 0',
        name: 'code',
        allowBlank: false
    }, {
        xtype: 'textarea',
        fieldLabel: 'Description',
        margin: '0 20 20 0',
        name: 'description',
        allowBlank: true
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
                    table_name: 'par_variation_subdescription',
                    storeID: 'variationsubdescriptiondetailsstr',
                    formBind: true,
                    ui: 'soft-purple',
                    hasformFilter: false,
                    action_url: 'configurations/saveConfigCommonData',
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