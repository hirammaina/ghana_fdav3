
/**
 * Created by Softclans
 * User robinson odhiambo
 * on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.forms.food.FoodProductsDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'foodproductdetailsfrm',
    itemId: 'productsDetailsFrm',
    layout: {
        type: 'column'
    },
    viewModel: {
        type: 'productregistrationvm'
    },
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
       
    }, autoScroll: true,
    items: [
        {
            xtype: 'hiddenfield',
            name: 'product_id'
        },{
            xtype: 'hiddenfield',
            name: 'reg_product_id'
        },  {
            xtype: 'hiddenfield',
            value: 'tra_product_information',
            name: 'table_name'
        },{
            xtype: 'combo',
            fieldLabel: 'Product Class Category',
            name: 'prodclass_category_id',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setConfigCombosSectionfilterStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'configurations/getproductApplicationParameters',
                            extraParams: {
                                table_name: 'par_prodclass_categories'
                            }
                        }
                    },
                    isLoad: true
                }
            }, bind: {
                readOnly: '{isReadOnly}'  // negated
            }

        },  {
            xtype: 'textfield',
            name: 'brand_name',
            fieldLabel: 'Brand Name'
        },
        {
            xtype: 'combo',
            fieldLabel: 'Classification',
            name: 'classification_id',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setConfigCombosSectionfilterStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'configurations/getproductApplicationParameters',
                            extraParams: {
                                table_name: 'par_classifications'
                            }
                        }
                    },
                    isLoad: false
                }
            }
        },{
            xtype:'fieldcontainer',
            layout: {
                type: 'hbox'
            },
            items:[{
                xtype: 'combo',
                fieldLabel: 'Common Names',
                name: 'common_name_id',
                forceSelection: true,
                queryMode: 'local',
                valueField: 'id',
                width: '80%',
                allowBlank: false,
                labelAlign: 'top',
                displayField: 'name'
                , bind: {
                    readOnly: '{isReadOnly}'  // negated
                },
                listeners: {
                    afterrender: {
                        fn: 'setConfigCombosSectionfilterStore',
                        config: {
                          
                            storeId: 'par_commonnamesstr',
                            proxy: {
                                url: 'configurations/getproductApplicationParameters',
                                extraParams: {
                                    table_name: 'par_common_names'
                                }
                            }
                        },
                        isLoad: true
                    }
                }
            },{
                xtype: 'button',
                iconCls:'x-fa fa-plus',
                name: 'btn_addcommonnames',
                childXtype:'productcommonNamefrm',
                width: '15%', margin:'28 0 0',
                table_name: 'par_common_names',
                storeId: 'par_commonnamesstr',
                bind: {
                    disabled: '{isReadOnly}'  // negated
                }
            }]
        },{
            xtype: 'combo',
            fieldLabel: 'Product Origin',
            name: 'product_origin_id',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'configurations/getRegistrationApplicationParameters',
                            extraParams: {
                                table_name: 'par_product_origins'
                            }
                        }
                    },
                    isLoad: true
                }
            }

        }, {
            xtype:'fieldcontainer',
            layout: {
                type: 'hbox'
            },
            items:[{
                xtype: 'combo',
                fieldLabel: 'Product category',
                name: 'product_category_id',
                forceSelection: true,
                queryMode: 'local',
                valueField: 'id',width: '80%',
                allowBlank: false,
                labelAlign: 'top',
                displayField: 'name',
                listeners: {
                    afterrender: {
                        fn: 'setConfigCombosSectionfilterStore',
                        config: {
                            pageSize: 10000,
                            storeId:'productcategoriesstr',
                            proxy: {
                                url: 'configurations/getproductApplicationParameters',
                                extraParams: {
                                    table_name: 'par_product_categories'
                                }
                            }
                        },
                        isLoad: true
                    },
                    change: function (cbo, value) {
                        var form = cbo.up('form'),
                            product_subcategory = form.down('combo[name=product_subcategory_id]'),
                            filters = { product_category_id: value },
                            filters = JSON.stringify(filters),
                            store = product_subcategory.store;
                        store.removeAll();
                        store.load({ params:{filters: filters}});
    
                    }
                }
            },{
                xtype: 'button',
                iconCls:'x-fa fa-plus',
                handler:'funcAddProductApplicationParamter',
                 width: '15%', margin:'28 0 0',
                childXtype:'productCategoriesfrm',
                name: 'btn_addproducategoriess',
                table_name: 'par_product_categories',
                storeId: 'productcategoriesstr',
                bind: {
                    disabled: '{isReadOnly}'  // negated
                }
            }]
    },{
            xtype:'fieldcontainer',
            layout: {
                type: 'hbox'
            },
            items:[{
                xtype: 'combo',
                fieldLabel: 'Product Sub-category',
                name: 'product_subcategory_id',
                forceSelection: true,
                queryMode: 'local',
                valueField: 'id',
                allowBlank: false,
                labelAlign: 'top',width: '80%',
                displayField: 'name',
                listeners: {
                    afterrender: {
                        fn: 'setConfigCombosSectionfilterStore',
                        config: {
                            pageSize: 10000,
                            storeId:'productsubcategoriesstr',
                            proxy: {
                                url: 'configurations/getproductApplicationParameters',
                                extraParams: {
                                    table_name: 'par_subproduct_categories'
                                }
                            }
                        },
                        isLoad: false
                    }
                }
            },{
                xtype: 'button',
                iconCls:'x-fa fa-plus',
                handler:'funcAddSubCategoryProductParamter',
                width: '15%', margin:'28 0 0',
                childXtype:'productSubCategoriesfrm',
                width: '15%', margin:'28 0 0',
                table_name: 'par_subproduct_categories',
                storeId: 'productsubcategoriesstr',
                bind: {
                    disabled: '{isReadOnly}'  // negated
                }
            }]
    },  {
            xtype: 'combo',
            fieldLabel: 'Product Form',
            name: 'product_form_id',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setConfigCombosSectionfilterStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'configurations/getproductApplicationParameters',
                            extraParams: {
                                table_name: 'par_product_forms'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        }, {
            xtype:'fieldcontainer',
            layout: {
                type: 'hbox'
            },
            items:[{
                xtype: 'combo',
                fieldLabel: 'Method of use',
                name: 'method_ofuse_id',
                forceSelection: true,
                queryMode: 'local',
                valueField: 'id',
                labelAlign: 'top',width: '80%',
                displayField: 'name',
                listeners: {
                    afterrender: {
                        fn: 'setConfigCombosSectionfilterStore',
                        config: {
                            pageSize: 10000,
                            storeId: 'methodofusestr',
                            proxy: {
                                url: 'configurations/getRegistrationApplicationParameters',
                                extraParams: {
                                    table_name: 'par_methodof_use'
                                }
                            }
                        },
                        isLoad: true
                    }
                }
            },{
                xtype: 'button',
                iconCls:'x-fa fa-plus',
               // handler:'funcAddProductApplicationParamter',
                 width: '15%', margin:'28 0 0',
                childXtype:'productMethodofUsefrm',
                table_name: 'par_methodof_use',
                name: 'btn_addmethodofuser',
                storeId: 'methodofusestr',
                bind: {
                    disabled: '{isReadOnly}'  // negated
                }
            }]
    }, {
            xtype: 'combo',
            fieldLabel: 'Intended End User',
            name: 'intended_enduser_id',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setConfigCombosSectionfilterStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'configurations/getproductApplicationParameters',
                            extraParams: {
                                table_name: 'par_intended_enduser'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        }, {
            xtype: 'textfield',
            fieldLabel: 'Other conditions or contraindications',
            name: 'contraindication',
            allowBlank: true,
            bind: {
                readOnly: '{isReadOnly}'  // negated
            }
        },  {
            xtype: 'textfield',
            fieldLabel: 'Shelf Life',
            allowBlank: true,
            name: 'shelf_life'
        }, {
            xtype: 'textfield',
            fieldLabel: 'Shelf Life after Opening(Optional)',
            allowBlank: true,
            name: 'shelf_lifeafter_opening'
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Product Warning',
            allowBlank: true,
            name: 'warnings'
        },{
            xtype: 'textfield',
            fieldLabel: 'Nicotine Content',
            allowBlank: true,
            hidden: true,
            name: 'nicotine_content'
        },{
            xtype: 'textfield',
            fieldLabel: 'Tar Content',
            allowBlank: true,hidden: true,
            name: 'tar_content'
        }, {
            xtype:'fieldcontainer',
            layout: {
                type: 'hbox'
            },
            items:[{
                xtype: 'combo',
                fieldLabel: 'Flavour',
                name: 'tobacco_flavour_id',
                forceSelection: true,
                allowBlank: true,hidden: true,
                queryMode: 'local',
                labelAlign: 'top',width: '80%',
                valueField: 'id',
                displayField: 'name',
                listeners: {
                    afterrender: {
                        fn: 'setConfigCombosSectionfilterStore',
                        config: {
                            pageSize: 10000,
                            storeId:'flavourstr',
                            proxy: {
                                url: 'configurations/getproductApplicationParameters',
                                extraParams: {
                                    table_name: 'par_tobacco_flavours'
                                }
                            }
                        },
                        isLoad: true
                    }
                }
            },{
                xtype: 'button',
                iconCls:'x-fa fa-plus',
               // handler:'funcAddProductApplicationParamter',
                 width: '15%', margin:'28 0 0',hidden: true,
                childXtype:'tobaccoFlavoursFrm,',
                table_name: 'par_tobacco_flavours',
                name: 'btn_addtobaccoflavous',
                storeId: 'flavourstr',
                bind: {
                    disabled: '{isReadOnly}'  // negated
                }
            }]
         },{
            xtype: 'textfield',
            fieldLabel: 'Recommended storage conditions before opening',
            name: 'storage_condition',
            allowBlank: true,
            bind: {
                readOnly: '{isReadOnly}'  // negated
            }
        }, {
            xtype: 'textarea',
            fieldLabel: 'Instructions Of Use',colspan: 1,
            name: 'instructions_of_use'
        }, 

        {
            xtype: 'textarea',
            name: 'physical_description',
            colspan: 1,
            allowBlank: false,
            columnWidth: 0.99,
            fieldLabel: 'Brief description of the physical characteristics of the food (form, colour etc.)'
        }
    ]
});