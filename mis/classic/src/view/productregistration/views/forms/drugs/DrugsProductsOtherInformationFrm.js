
/**
 * Created by Softclans
 * User robinson odhiambo
 * on 9/24/2018.
 */
Ext.define('Admin.view.productregistration.views.forms.drugs.DrugsProductsOtherInformationFrm', {
    extend: 'Ext.tab.Panel',
    xtype: 'drugsProductsOtherInformationFrm',
    layout: {
        // layout-specific configs go here
        type: 'fit'
    },
    defaults:{
        margin: 3
    },
    items: [{
        xtype: 'drugsIngredientsGrid',
        title: 'Product Ingredients',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'drugproductIngredientsstr',
                    proxy: {
                        url: 'productregistration/onLoadproductIngredients',
                    }
                },
                isLoad: true
            }
        },
    }, {
        xtype: 'drugsProductPackagingGrid',
        title: 'Product Packaging details',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'drugproductPackagingdetailsstr',
                    proxy: {
                        url: 'productregistration/onLoadproductPackagingDetails',
                    }
                },
                isLoad: true
            }
        },
    }, {
        xtype: 'drugsMaximumResidueLimitsGrid',
        title: 'Maximum Residue Limits (MRLs)',
        hidden: true,
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'drugsMaximumResidueLimitsstr',
                    proxy: {
                        url: 'productregistration/onLoaddrugsMaximumResidueLimitsGrid',
                    }
                },
                isLoad: true
            }, 
            afterrender:function(grid){
                    var section_id = grid.down('hiddenfield[name=section_id]').getValue();
                    if(section_id != 7){
                        grid.hide();
                    }else{
                        grid.show();
                    }
            }
        },
    },   {
        xtype: 'productManuctureringGrid',
        title: 'Product Manufacturing Details',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'productManuctureringStr',
                    proxy: {
                        url: 'productregistration/onLoadproductManufacturer',
                    }
                },
                isLoad: true
            }
        },

    }, {
        xtype: 'productApiManuctureringGrid',
        title: 'Product API Manufacturer',
        listeners: {
            beforerender: {
                fn: 'setConfigGridsStore',
                config: {
                    pageSize: 1000,
                    storeId: 'productApiManuctureringStr',
                    proxy: {
                        url: 'productregistration/onLoadproductApiManufacturer',
                    }
                },
                isLoad: true
            }
        },
    }, {
        xtype: 'productGmpInspectionDetailsGrid',hidden: true,
        title: 'GMP Inspection Details (Inspected Sites)'
        
    },{
        xtype: 'inspectioninothercountriesGrid',
        title: 'it GMP inspection from other countries'
        
    },{
        xtype: 'productreginothercountriesGrid',
        title: 'Registration In Other Countries '
        
    },{
        xtype: 'conductedproductclinicaltrialGrid',
        title: 'clinical trial information'
        
    }, {
        xtype: 'productImagesUploadsGrid',
        title: 'Product Images'
    }]
});