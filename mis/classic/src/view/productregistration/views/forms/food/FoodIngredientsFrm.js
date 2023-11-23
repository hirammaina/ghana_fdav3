
/**
 * Created by Softclans
 * User robinson odhiambo
 * on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.forms.food.FoodIngredientsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'foodingredientsfrm',
    layout: {
        type: 'vbox'
    },
    bodyPadding: 5,
    controller: 'productregistrationvctr',
    defaults: {
        margin: 5,
        labelAlign: 'top',
        width: '100%',
        allowBlank: false,
    },
    items: [{
        xtype: 'hiddenfield',
        name: 'id',
        allowBlank: true
    },
    {
        xtype: 'hiddenfield',
        name: 'product_id'
    }, {
        xtype: 'hiddenfield',
        name: 'table_name',
        value: 'tra_product_ingredients'
    }, {
        xtype: 'combo',
        name: 'ingredient_type_id',
        allowBlank: true,
        queryMode: 'local',
        fieldLabel: 'Type Of Ingredient',
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
                            table_name: 'par_ingredients_types'
                        }
                    }
                },
                isLoad: true
            }
        }
    },  {
        xtype:'fieldcontainer',
        layout: {
            type: 'hbox'
        },
        items:[{
            xtype: 'combo',
            name: 'ingredient_id',
            allowBlank: true,
            fieldLabel: 'Ingredient',
            queryMode: 'local',
            valueField: 'id', width: '90%',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setConfigCombosStore',
                config: {
                    pageSize: 10000,
                    storeId:'ingredientsDetailsstr',
                    proxy: {
                        url: 'configurations/getRegistrationApplicationParameters',
                            extraParams:{
                                table_name: 'par_ingredients_details'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },{
            xtype: 'button',
            iconCls:'x-fa fa-plus',
            handler:'funcAddProductApplicationParamter',
            section_id: 2,
            childXtype:'productingredientsfrm',
            width: '15%', margin:'28 0 0',
            table_name: 'par_ingredients_details',
            storeId: 'ingredientsDetailsstr'
        }]

}, 
    {
        xtype: 'textfield',
        name: 'proportion',
        fieldLabel: 'Proportion'

    }, {
        xtype: 'combo',
        name: 'inclusion_reason_id',
        allowBlank: true,
        fieldLabel: 'Reason for Inclusion',
        valueField: 'id',
        displayField: 'name',
        queryMode: 'local',
        listeners: {
            afterrender: {
                fn: 'setConfigCombosStore',
                config: {
                    pageSize: 10000,
                    proxy: {
                        url: 'configurations/getRegistrationApplicationParameters',
                        extraParams: {
                            table_name: 'par_inclusions_reasons'
                        }
                    }
                },
                isLoad: true
            }
        }
    }
    ],
    dockedItems: [{
        xtype: 'toolbar',
        ui: 'footer',
        dock: 'bottom',
        items: [
            '->', {
                text: 'Save Ingredients',
                iconCls: 'x-fa fa-save',
                action: 'save',
                table_name: 'tra_product_ingredients',
                storeID: 'foodproductingredientsstr',
                formBind: true,
                ui: 'soft-purple',
                action_url: 'productregistration/onSaveProductOtherDetails',
                handler: 'saveproductOtherdetails'
            }
        ]
    }
    ]
});