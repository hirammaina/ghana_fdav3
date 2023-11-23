

/**
 * Created by Kip on 10/2/2018.
 */
Ext.define('Admin.view.variation_configurations.views.grids.VariationsRequestConfigurationGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'variationconfigurationsvctr',
    xtype: 'variationsrequestconfigurationgrid',
    cls: 'dashboard-todo-list',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display',
        getRowClass: function (record, rowIndex, rowParams, store) {
            var is_enabled = record.get('is_enabled');
            if (is_enabled == 0 || is_enabled === 0) {
                return 'invalid-row';
            }
        }
    },
    tbar: [{
        xtype: 'button',
        text: 'Add',
        iconCls: 'x-fa fa-plus',
        action: 'add',
        ui: 'soft-green',
        childXtype: 'variationsrequestconfigurationfrm',
        winTitle: 'Variation Summary Configurations & Guidelines',
        winWidth: '80%',
        handler: 'showAddConfigParamWinFrm',
        stores: '[]'
    }, {
        xtype: 'exportbtn'
    }],
    plugins: [{
        ptype: 'gridexporter'
    }],
    export_title: 'Variation Summary Configurations',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records'
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    }, {
        ftype: 'grouping',
        startCollapsed: false
    }],
    listeners: {
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 1000,
                storeId: 'variationsrequestconfigurationstr', 
                groupField: 'variation_category',
                proxy: {
                    url: 'commonparam/getVariationsRequestConfiguration',
                    extraParams: {
                        table_name: 'tra_variationsummary_guidelines'
                    }
                }
            },
            isLoad: true
        }
    },
    columns: [ {
        xtype: 'gridcolumn',
        dataIndex: 'sub_module_name',
        text: 'Sub-Module Name',
        flex: 1,
        tdCls: 'wrap-text'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'product_category',
        text: 'Product Category',
        flex: 1,
        tdCls: 'wrap-text'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'section_name',
        text: 'Section Name',
        flex: 1,
        tdCls: 'wrap-text'
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'variation_category',
        text: 'Variation Category',
        flex: 1,
        tdCls: 'wrap-text'
    },   {
        xtype: 'gridcolumn',
        dataIndex: 'variation_subcategory',
        text: 'Variation Sub-Category',
        flex: 1,
        tdCls: 'wrap-text'
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'variation_description',
        text: 'Description of the change',
        flex: 1,
        tdCls: 'wrap-text'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'variation_subdescription',
        text: 'Sub-Description of the change',
        flex: 1,
        tdCls: 'wrap-text'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'variationconditions_detailscodes',
        text: 'Conditions to be fulfilled',
        flex: 2,
        tdCls: 'wrap-text'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'variationsupporting_datadocs_code',
        text: 'Supporting data',
        flex: 2,
        tdCls: 'wrap-text'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'variation_reportingtype',
        text: 'Reporting category/Type',
        flex: 1,
        tdCls: 'wrap-text'
    },  {
        text: 'Options',
        xtype: 'widgetcolumn',
        width: 90,
        widget: {
            width: 75,
            textAlign: 'left',
            xtype: 'splitbutton',
            iconCls: 'x-fa fa-th-list',
            ui: 'gray',
            menu: {
                xtype: 'menu',
                items: [{
                    text: 'Edit',
                    iconCls: 'x-fa fa-edit',
                    tooltip: 'Edit Record',
                    action: 'edit',
                    childXtype: 'variationsrequestconfigurationfrm',
                    winTitle: 'Variation Summary Configurations & Guidelines',
                    winWidth: '80%',
                    handler: 'showEditConfigParamWinFrm',
                    stores: '[]'
                }, {
                    text: 'Disable',
                    iconCls: 'x-fa fa-repeat',
                    table_name: 'tra_variationsummary_guidelinesconfig',
                    storeID: 'variationsrequestconfigurationstr',
                    action_url: 'workflow/softDeleteWorkflowRecord',
                    action: 'soft_delete',
                    handler: 'doDeleteConfigWidgetParam'
                }, {
                    text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'tra_variationsummary_guidelinesconfig',
                    storeID: 'variationsrequestconfigurationstr',
                    action_url: 'workflow/deleteWorkflowRecord',
                    action: 'actual_delete',
                    handler: 'doDeleteConfigWidgetParam',
                    hidden: Admin.global.GlobalVars.checkForProcessVisibility('actual_delete')

                }, {
                    text: 'Enable',
                    iconCls: 'x-fa fa-undo',
                    tooltip: 'Enable Record',
                    table_name: 'tra_variationsummary_guidelinesconfig',
                    storeID: 'variationsrequestconfigurationstr',
                    action_url: 'workflow/undoWorkflowSoftDeletes',
                    action: 'enable',
                    disabled: true,
                    handler: 'doDeleteConfigWidgetParam'
                }
                ]
            }
        }
    }],

    leadingBufferZone: 8,
    trailingBufferZone: 8,
   
   plugins: [{
       ptype: 'rowwidget',
           widget: {
                   xtype: 'tabpanel',
                   bind: {
                       title: 'Conditions and Supporting data details'
                   },header:false,
                   tbar:[{
                       xtype: 'hiddenfield',
                       name:'module_id',
                       bind: {
                           value: '{record.module_id}'
                       }
                   },{
                        xtype: 'hiddenfield',
                        name:'variationsummary_guidelinesconfig_id',
                        bind: {
                            value: '{record.variationsummary_guidelinesconfig_id}'
                        }
                    },
                   
                   {
                       xtype: 'hiddenfield',
                       name:'sub_module_id',
                       bind: {
                           value: '{record.sub_module_id}'
                       }
                   },{
                       xtype: 'hiddenfield',
                       name:'section_id',
                       bind: {
                           value: '{record.section_id}'
                       }
                   },{
                       xtype: 'hiddenfield',
                       name:'product_category_id',
                       bind: {
                           value: '{record.product_category_id}'
                       }
                   },{
                       xtype: 'hiddenfield',
                       name:'variation_description_id',
                       bind: {
                           value: '{record.variation_description_id}'
                       }
                   },{
                       xtype: 'hiddenfield',
                       name:'variation_subdescription_id',
                       bind: {
                           value: '{record.variation_subdescription_id}'
                       }
                   }],
                   items:[{
                       xtype:'grid',
                       height: 200,
                       width: '100%',
                       bind: {
                           title: 'Supporting data details'
                       },
                       tbar:[{
                            xtype: 'hiddenfield',
                            name:'variationsummary_guidelinesconfig_id',
                            bind: {
                                value: '{record.variationsummary_guidelinesconfig_id}'
                            }
                        }],
                        features: [{
                            ftype: 'searching',
                            minChars: 2,
                            mode: 'local'
                        }],
                        listeners: {
                            beforerender: {
                                fn: 'setConfigGridsStore',
                                config: {
                                    pageSize: 1000,
                                    storeId: 'variationsupportingdatastr', 
                                   
                                    proxy: {
                                        url: 'commonparam/getVariationSupportingDataDetails',
                                        extraParams: {
                                            table_name: 'par_variationsupporting_datadocs'
                                        }
                                    }
                                },
                                isLoad: false
                            },
                            afterrender:function(grid){
                                    grid.getStore().load();
                            }
                        },
                        columns: [{
                            xtype: 'gridcolumn',
                            dataIndex: 'code',
                            text: 'code/Number',
                            flex: 0.2,
                            tdCls: 'wrap-text'
                        },  {
                            xtype: 'gridcolumn',
                            dataIndex: 'name',
                            text: 'Supporting data',
                            flex: 1,
                            tdCls: 'wrap-text'
                        }],
                       bbar: [{
                           xtype: 'pagingtoolbar',
                           width: '60%',
                           displayInfo: true,
                           displayMsg: 'Showing {0} - {1} of {2} total records',
                           emptyMsg: 'No Records',
                      
                           beforeLoad: function() {
                               var grid = this.up('grid'),
                                   store= grid.getStore(),
                                   variationsummary_guidelinesconfig_id = grid.down('hiddenfield[name=variationsummary_guidelinesconfig_id]').getValue();
                                  
                                   store.removeAll();
                                   store.getProxy().extraParams = {
                                    variationsummary_guidelinesconfig_id: variationsummary_guidelinesconfig_id,
                                          
                                   };
                           }
                       },'->']
                   },{
                    xtype:'grid',
                    height: 200,
                    width: '100%',
                    bind: {
                        title: 'Conditions to be fulfilled'
                    },
                    features: [{
                        ftype: 'searching',
                        minChars: 2,
                        mode: 'local'
                    }],
                    tbar:[{
                         xtype: 'hiddenfield',
                         name:'variationsummary_guidelinesconfig_id',
                         bind: {
                             value: '{record.variationsummary_guidelinesconfig_id}'
                         }
                     }],
                     listeners: {
                        beforerender: {
                            fn: 'setConfigGridsStore',
                            config: {
                                pageSize: 1000,
                                storeId: 'variationsupportingdatastr', 
                                proxy: {
                                    url: 'commonparam/getVariationConditionsDetails',
                                    extraParams: {
                                        table_name: 'par_variationsupporting_datadocs'
                                    }
                                }
                            },
                            isLoad: false
                        },
                        afterrender:function(grid){
                                grid.getStore().load();
                        }
                    },
                    columns: [{
                        xtype: 'gridcolumn',
                        dataIndex: 'id',
                        text: 'code/Number',
                        flex: 0.2,
                        tdCls: 'wrap-text'
                    },  {
                        xtype: 'gridcolumn',
                        dataIndex: 'name',
                        text: 'Conditions',
                        flex: 1,
                        tdCls: 'wrap-text'
                    }],
                    bbar: [{
                        xtype: 'pagingtoolbar',
                        width: '60%',
                        displayInfo: true,
                        displayMsg: 'Showing {0} - {1} of {2} total records',
                        emptyMsg: 'No Records',
                      
                        beforeLoad: function() {
                            var grid = this.up('grid'),
                                store= grid.getStore(),
                                variationsummary_guidelinesconfig_id = grid.down('hiddenfield[name=variationsummary_guidelinesconfig_id]').getValue();
                                store.removeAll();
                                store.getProxy().extraParams = {
                                 variationsummary_guidelinesconfig_id: variationsummary_guidelinesconfig_id,
                                       
                                };
                        }
                    },'->']
                }]
           }
       
   }]
});
