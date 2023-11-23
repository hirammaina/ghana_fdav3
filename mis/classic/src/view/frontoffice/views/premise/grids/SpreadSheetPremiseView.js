 Ext.define('Admin.view.frontoffice.premise.grids.SpreadSheetPremiseView', {
    extend: 'Ext.grid.Panel',  
    scroll: true,
    width: '100%',
    xtype: 'spreadsheetpremiseview',
    layout: 'fit',
    title: 'Premise Application SpreadSheet',
    referenceHolder: true,
    reference:'premisegridpanel',
   listeners: {
        select: 'loadadditionalinfo',
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 100,
                storeId: 'spreadsheetpremiseapplicationcolumnsstr', 
                remoteFilter:true,
                proxy: {
                        type: 'ajax',
                        url: 'openoffice/getPremiseApplicationColumns',
                        reader: {
                            type: 'json',
                            idProperty: 'id',
                            rootProperty: 'results',
                            messageProperty: 'message',
                            totalProperty: 'totalResults'
                        }                             
                }
            },
            isLoad: true
             }
             
        },
    plugins: [{
            ptype: 'filterfield'
        }],
         viewConfig: {
            emptyText: 'No information found under this creteria',
            deferEmptyText: false,
            preserveScrollOnReload: true,
            enableTextSelection: true,
            emptyText: 'No Details Available',
        },
    columns: [ {
        text: 'Action',
        xtype: 'widgetcolumn',
        width: 90,
        widget: {
            width: 75,
            ui: 'gray',
            iconCls: 'x-fa fa-th-list',
            textAlign: 'left',
            xtype: 'splitbutton',
            menu: {
                xtype: 'menu',
                items: [{
                        text: 'Update Details',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'Update Details',
                        handler: 'func_updateDetails'
                       },{
                        text: 'Update to Licensed Recommendation ',
                        iconCls: 'x-fa fa-chevron-circle-up',
                        handler: 'getApplicationApprovalDetails',
                        stores: '["approvaldecisionsstr","spreadsheetpremiseapplicationcolumnsstr"]',
                        table_name: 'tra_premises_applications'
                    },{
                        text: 'Documents',
                        iconCls: 'x-fa fa-file',
                        tooltip: 'View Documents',
                        handler: 'func_viewUploadedDocs'
                    },{
                        text: 'View Application Process',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'View Application Process',
                        handler: 'funcViewApplicationProcess'
                    }]
              }
            }
         },
        
    {
        xtype: 'gridcolumn',
        dataIndex: 'premise_id',
        name: 'id',
        hidden: true
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'tracking_no',
        name: 'tracking_no',
        text: 'Tracking No',
        tdCls: 'wrap',
        tdCls: 'wrap',
        width: 150,
        filter: {
                xtype: 'textfield',
            }
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'reference_no',
        name: 'reference_no',
        text: 'Reference No',
        tdCls: 'wrap',
        width: 150,
        filter: {
                xtype: 'textfield',
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'name',
        name: 'name',
        text: 'Premise Name',
        tdCls: 'wrap',
        width: 200,
        filter: {
                xtype: 'textfield',
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'postal_address',
        name: 'postal_address',
        text: 'Postal Address',
        tdCls: 'wrap',
        width: 200,
        filter: {
                xtype: 'textfield',
            }
    },
      {
        xtype: 'gridcolumn',
        dataIndex: 'physical_address',
        name: 'physical_address',
        text: 'Physical Address',
        tdCls: 'wrap',
        width: 200,
        filter: {
                xtype: 'textfield',
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'premise_category',
        name: 'premise_category',
        text: 'Category',
        width: 250, hidden: true,
         filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'Category_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_premises_types'
                                    }
                                }
                            },
                            isLoad: true
                        },
                    change: function(cmb, newValue, oldValue, eopts) {
                var grid = cmb.up('grid');
                    grid.getStore().reload();}
                 }
                
            }
    },
    
    {
        xtype: 'gridcolumn',
        dataIndex: 'email',
        name: 'email',
        text: 'Email',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'telephone',
        name: 'telephone',
        text: 'Telephone No',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'mobile_no',
        name: 'mobile_no',
        text: 'Mobile No',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'premise_country',
        name: 'premise_country',
       text: 'Premise Country',
       tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'premise_region',
        name: 'premise_region',
        text: 'Premise Region',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'premise_district',
        name: 'premise_district',
        text: 'Premise District',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'business_type',
        name: 'business_type',
        text: 'Business Type',
        width: 250, hidden: true,
         filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'BsnType_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                     extraParams: {
                                        table_name: 'par_business_types'
                                    }
                                }
                            },
                            isLoad: true
                        },
                    change: function(cmb, newValue, oldValue, eopts) {
                var grid = cmb.up('grid');
                    grid.getStore().reload();}
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'business_category',
        name: 'business_category',
        text: 'Business Category',
        width: 250, hidden: true,
         filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'BsnCategory_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                     extraParams: {
                                        table_name: 'par_business_categories'
                                    }
                                }
                            },
                            isLoad: true
                        },
                   change: function(cmb, newValue, oldValue, eopts) {
                var grid = cmb.up('grid');
                    grid.getStore().reload();}
                 }
                
            }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'business_scale',
        name: 'business_scale',
        text: 'Business Scale',
        width: 250, hidden: true,
         filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'BsnScale_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                     extraParams: {
                                        table_name: 'par_business_scales'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function(cmb, newValue, oldValue, eopts) {
                var grid = cmb.up('grid');
                    grid.getStore().reload();}
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'business_type_details',
        name: 'business_type_details',
        text: 'Business Type Details',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'contact_person',
        name: 'contact_person',
        text: 'Contact Person',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'contact_telephone_no',
        name: 'contact_telephone_no',
        text: 'Contact Telephone',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },
{
        xtype: 'gridcolumn',
        dataIndex: 'contact_email_address',
        name: 'contact_email_address',
        text: 'Contact Email',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'datecolumn',
        dataIndex: 'contact_person_startdate',
        name: 'contact_person_startdate',
        text: 'contact Startdate',
        submitFormat: 'Y-m-d',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d',
                altFormats: 'Y-m-d',
            }
    },{
        xtype: 'datecolumn',
        dataIndex: 'contact_person_enddate',
        name: 'contact_person_enddate',
        text: 'Contact EndDate',
        format: 'Y-m-d',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d',
                altFormats: 'Y-m-d',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'trader',
        name: 'trader',
        text: 'Trader',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'trader_postal_address',
        name: 'trader_postal_address',
        text: 'Trader Postal Address',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'trader_physical_address',
        name: 'trader_physical_address',
        text: 'Trader Physical Address',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'trader_telephone',
        name: 'trader_telephone',
        text: 'Trader Telephone',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'trader_mobile_no',
        name: 'trader_mobile_no',
        text: 'Trader Mobile No',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'trader_email',
        name: 'trader_email',
        text: 'Trader Email',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'trader_country',
        name: 'trader_country',
        text: 'Trader Country',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'trader_region',
        name: 'trader_region',
        text: 'Trader Region',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'gps_coordinate',
        name: 'gps_coordinate',
        text: 'Premise Geo Coordinates',
        tdCls: 'wrap',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'place_of_issue',
        name: 'place_of_issue',
        text: 'Place of Issue',
        width: 250, hidden: true,
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'zone_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_zones'
                                    }
                                }
                            },
                            isLoad: true
                        },
                   change: function(cmb, newValue, oldValue, eopts) {
                var grid = cmb.up('grid');
                    grid.getStore().reload();
                }
                 }
                
            }
    },{
        xtype: 'datecolumn',
        dataIndex: 'certificate_issue_date',
        name: 'certificate_issue_date',
         format: 'Y-m-d',
        text: 'Certificate Issue Date',
        width: 250, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    }, {
        xtype: 'datecolumn',
        dataIndex: 'certificate_expiry_date',
        name: 'certificate_expiry_date',
         format: 'Y-m-d',
        text: 'Certificate Expiry Date',
        width: 250, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'certificate_no',
        name: 'certificate_no',
        text: 'Permit/Licenses No',
        tdCls: 'wrap',
        width: 150,
        hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'registration_status',
        name: 'registration_status',
        text: 'Registration Status',
        width: 250,
        hidden: true, 
       filter: {
                    xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'registration_status',
                    listeners:
                     {
                         afterrender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                     extraParams: {
                                        table_name: 'par_registration_statuses'
                                    }
                                }
                            },
                           isLoad: true
                        },
                                   
                     
                    change: function(cmb, newValue, oldValue, eopts) {
                var grid = cmb.up('grid');
                    grid.getStore().reload();
                }
                 }                
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'validity_status',
        name: 'validity_status',
        text: 'validity Status',
        hidden: true,
        width: 250,
        filter: {
                    xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'validity_status',
                    listeners:
                     {
                         afterrender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                     extraParams: {
                                        table_name: 'par_validity_statuses'
                                    }
                                }
                            },
                           isLoad: true
                        },
                                   
                     
                     change: function(cmb, newValue, oldValue, eopts) {
                        var grid = cmb.up('grid');
                            grid.getStore().reload();
                     }
                 }                
            }
     }, 
     {
        xtype: 'datecolumn',
        dataIndex: 'received_from',
        name: 'received_from',
        format: 'Y-m-d',
        text: 'Received From',
        width: 250, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    }, {
        xtype: 'datecolumn',
        dataIndex: 'received_to',
        name: 'received_to',
         format: 'Y-m-d',
        text: 'Received To',
        width: 250, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    },{
        xtype: 'datecolumn',
        dataIndex: 'issue_from',
        name: 'issue_from',
        format: 'Y-m-d',
        text: 'Issue From',
        width: 250, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    }, {
        xtype: 'datecolumn',
        dataIndex: 'issue_to',
        name: 'issue_to',
         format: 'Y-m-d',
        text: 'Issue To',
        width: 250, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    }

    ],bbar: [{
        xtype: 'pagingtoolbar',
        store: 'spreadsheetpremiseapplicationcolumnsstr',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} out of {2}',
        emptyMsg: 'No Records',
         beforeLoad: function () {
                    var store = this.getStore(),
                     range = this.down('combo[name=Range]').getValue();
                     var grid=this.up('grid'),
                      BsnCategory_id=grid.down('combo[name=BsnCategory_id]').getValue(),
                      BsnType_id=grid.down('combo[name=BsnType_id]').getValue(),
                      Category_id=grid.down('combo[name=Category_id]').getValue(),
                      BsnScale_id=grid.down('combo[name=BsnScale_id]').getValue(),
                      registration_status=grid.down('combo[name=registration_status]').getValue(),
                      validity_status=grid.down('combo[name=validity_status]').getValue(),
                      zone_id=grid.down('combo[name=zone_id]').getValue();
                      

                     
               //acquire original filters
               var filter = {'t1.premise_type_id':premise_type_id,'t1.sub_module_id':sub_module};
              var   filters = JSON.stringify(filter);

              //pass to store
                store.getProxy().extraParams = {
                    pageSize:range,
                    BsnCategory: BsnCategory_id,
                    BsnType: BsnType_id,
                    Category: Category_id,
                    BsnScale: BsnScale_id,
                    issueplace:zone_id,
                    registration_status:registration_status,
                    validity_status: validity_status,
                    filters: filters
                          };
                    },
            items:[{
                 xtype: 'combobox',
                 forceSelection: true,
                 fieldLabel: 'Range',
                 displayField: 'size',
                 valueField: 'size',
                 name: 'Range',
                 queryMode: 'local',
                 value: 25,
                 listeners:{
                    afterrender: {//getConfigParamFromTable
                             fn: 'setConfigCombosStore',
                            config: {
                                proxy: {
                                    url: 'commonparam/getCommonParamFromTable',
                                    extraParams: {
                                        table_name: 'par_page_sizes'
                                    }
                                }
                            },
                            isLoad: true
                        },
                    select: 'setPageSize'
                   }
            }]
    }],
});