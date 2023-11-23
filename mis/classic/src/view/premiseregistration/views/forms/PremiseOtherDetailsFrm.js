/**
 * Created by Kip on 9/24/2018.
 */
Ext.define('Admin.view.premiseregistration.views.forms.PremiseOtherDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'premiseotherdetailsfrm',
    controller: 'premiseregistrationvctr',
    frame: true,
    layout: {
        type: 'form'
    },
    bodyPadding: 5,
    defaults: {
        margin: 5,
        allowBlank: false
    },
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'is_temporal'
        },
        {
            xtype: 'hiddenfield',
            name: 'table_name',
            value: 'tra_premises_otherdetails'
        },
        {
            xtype: 'hiddenfield',
            name: 'premise_id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'combo',
            fieldLabel: 'Business Type',
            name: 'business_type_id',
            forceSelection: true,
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id',

            listeners: {
                beforerender: {
                    fn: 'setConfigCombosStoreWithSectionFilter',
                    config: {
                        pageSize: 10000,
                        storeId:'businesstypesstr',
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_business_types'
                            }
                        }
                    },
                    isLoad: true
                },
                change: function (cmb, newVal) {
                    var form = cmb.up('form'),
                        detailsStore = form.down('combo[name=business_type_detail_id]').getStore(),
                        filters = {business_type_id: newVal},
                        filter = JSON.stringify(filters);
                    detailsStore.removeAll();
                    detailsStore.load({params: {filters: filter}});
                }
            }
        },{
            xtype: 'combo',
            fieldLabel: 'Business Type Details',
            name: 'business_type_detail_id',
            store: 'businesstypedetailsstr',
            forceSelection: true,
            queryMode: 'local',
            allowBlank: true,
            displayField: 'name',
            valueField: 'id',
            listeners: {
                beforerender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 10000,
                        storeId:'businesstypesstr',
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_business_type_details'
                            }
                        }
                    },
                    isLoad: false
                },
            }
        }, {
            xtype: 'combo',
            fieldLabel: 'Product Category',
            name: 'product_category_id',
            forceSelection: true,
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id',
            allowBlank: true,
            listeners: {
                beforerender: {
                    fn: 'setConfigCombosStoreWithSectionFilter',
                    config: {
                        pageSize: 10000,
                        storeId:'product_categorystr',
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_product_categories'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },{
            xtype: 'textfield',
            name: 'manufacturing_activities',
            fieldLabel: 'Manufacturing Activities(i.e.Production, Packaging, Labelling, Storage and Distribution)',
            allowBlank: true
        },{
            xtype: 'textfield',
            name: 'product_details',
            fieldLabel: 'Product Details/Descriptions(for Manufacturing Premises)',
            allowBlank: true
        },
        
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            table_name: 'tra_premises_otherdetails',
            storeID: 'premiseotherdetailsstr',
            action_url: 'premiseregistration/savePremiseOtherDetails',
            handler: 'doCreatePremiseRegParamWin'
        }
    ]
});