
/**
 * Created by Softclans
 * User robinson odhiambo
 * on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.forms.food.OnlineFoodProductsOtherInformationFrm', {
    extend: 'Ext.tab.Panel',
    xtype: 'onlinefoodproductsotherinformationfrm',
    layout: {
        // layout-specific configs go here
        type: 'fit'
    },
    defaults: {
        margin: 3
    },
    items: [{
        xtype: 'foodingredientsgrid',
        title: 'Product Ingredients',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'foodproductingredientsstr',
                    proxy: {
                        url: 'productregistration/onLoadOnlineproductIngredients',
                    }
                },
                isLoad: true
            }
        }
    }, {
        xtype: 'foodproductnutrientsgrid',
        title: 'Product Nutrients details',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'foodproductnutrientsstr',
                    proxy: {
                        url: 'productregistration/onLoadOnlineproductNutrients',
                    }
                },
                isLoad: true
            }
        },
    }, {
        xtype: 'foodproductpackaginggrid',
        title: 'Product Packaging details',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'foodproductPackagingdetailsstr',
                    proxy: {
                        url: 'productregistration/onLoadOnlineproductPackagingDetails',
                    }
                },
                isLoad: true
            }
        },
    }, {
        xtype: 'productManuctureringGrid',
        title: 'Product Manufacturing Details',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'productManuctureringStr',
                    proxy: {
                        url: 'productregistration/onLoadOnlineproductManufacturer',
                    }
                },
                isLoad: true
            }
        }
    }, {
        xtype: 'productGmpInspectionDetailsGrid',
        title: 'GMP Inspection Details',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'gmpInspectionApplicationsDetailsStr',
                    proxy: {
                        url: 'productregistration/onLoadOnlinegmpInspectionApplicationsDetails',
                        
                    }
                },
                isLoad: true
            }
        },
    },{
        xtype: 'productImagesUploadsGrid',
        title: 'Product Images',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'productimagesUploadsStr',
                    groupField: 'document_type_id',
                    proxy: {
                        url: 'documentmanagement/onLoadOnlineProductImagesUploads',
                    }
                },
                isLoad: true
            }
        },
    }]
});