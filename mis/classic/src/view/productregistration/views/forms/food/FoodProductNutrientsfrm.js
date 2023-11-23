
/**
 * Created by Softclans
 * User robinson odhiambo
 * on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.forms.food.FoodProductNutrientsfrm', {
    extend: 'Ext.form.Panel',
    xtype: 'foodproductnutrientsfrm',
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
        value: 'tra_product_nutrients'
    }, {
        xtype: 'combo',
        name: 'nutrients_id',
        allowBlank: true,
        queryMode: 'local',
        fieldLabel: 'Nutrient',
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
                            table_name: 'par_nutrients'
                        }
                    }
                },
                isLoad: true
            }
        }
    }, {
        xtype: 'combo',
        name: 'nutrients_category_id',
        allowBlank: true,
        fieldLabel: 'Ingredient Category',
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
                            table_name: 'par_nutrients_category'
                        }
                    }
                },
                isLoad: true
            }
        }
    },
    {
        xtype: 'textfield',
        name: 'proportion',
        fieldLabel: 'Proportion'

    }, {
        xtype: 'combo',
        name: 'units_id',
        allowBlank: true,
        fieldLabel: 'Si-Units',
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
                            table_name: 'par_si_units'
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
                text: 'Save Nutrient',
                iconCls: 'x-fa fa-save',
                action: 'save',
                table_name: 'tra_product_nutrients',
                storeID: 'foodproductnutrientsstr',
                formBind: true,
                ui: 'soft-purple',
                action_url: 'productregistration/onSaveProductOtherDetails',
                handler: 'saveproductOtherdetails'
            }
        ]
    }
    ]
});