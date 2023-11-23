 Ext.define('Admin.view.frontoffice.controlleddrugsproductspreadsheet.grids.CtrDrgSpreadSheetIEPermitView', {
 extend: 'Ext.grid.Panel',  
   scroll: true,
   width: '100%',
    xtype: 'ctrdrgspreadsheetiepermitview',
   layout: 'fit',
    title: 'Controlled Drugs Import/Export Permit SpreadSheet',
    referenceHolder: true,
   reference:'iepermitgridpanel',
   plugins: [{
            ptype: 'filterfield'
        }],
         viewConfig: {
            emptyText: 'No products information found under this creteria',
            deferEmptyText: false,
            preserveScrollOnReload: true,
            enableTextSelection: true,
            emptyText: 'No Details Available',
        },
    columns: [{
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
                        text: 'Documents',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'View Documents',
                        handler: 'func_viewUploadedDocs'
                       },{
                        text: 'Preview Application Details',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'Preview Record',
                        action: 'edit',
                        childXtype: 'previewcontroldrugsimppermitdetails',
                        winTitle: 'Import/Export Permit Applications',
                        winWidth: '40%',
                        isReadOnly:1,
                        handler: 'editpreviewPermitinformation'
                    },{
                        text: 'Application Processing Enquiries',
                        iconCls: 'x-fa fa-edit',
                        handler: 'funcViewApplicationProcess'
                    }]
              }
            }
         },{
            xtype: 'gridcolumn',
            dataIndex: 'tracking_no',
            name: 'tracking_no',
            text: 'Tracking No',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        },
         {
            xtype: 'gridcolumn',
            dataIndex: 'reference_no',
            name: 'reference_no',
            text: 'Reference No',
            tdCls:'wrap-text',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'section_name',
            name: 'section_name',
            text: 'Permit Product Type',
            tdCls:'wrap-text',
            width: 200, hidden: true,
        },{
            xtype: 'gridcolumn',
            dataIndex: 'permitreason',
            name: 'permitreason',
            text: 'Permit Use',
            tdCls:'wrap-text',
            width: 200, hidden: true,
            filter: {
                xtype: 'combobox',
                        queryMode: 'local',
                        displayField: 'name',
                        valueField: 'id',
                        name: 'permit_reason_id',
                        listeners:
                         {
                             beforerender: {//getConfigParamFromTable
                                fn: 'setConfigCombosStore',
                                config: {
                                    pageSize: 10000,
                                    proxy: {
                                        url: 'configurations/getConfigParamFromTable',
                                        extraParams: {
                                            table_name: 'par_permit_category'
                                        }
                                    }
                                },
                                isLoad: true
                            },
                         change: function() {
                            Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                         }
                     }
                    
                }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'permit_productscategory',
            name: 'permit_productscategory',
            text: 'Permit Product Category',
            tdCls:'wrap-text',
            width: 200, hidden: true,
            filter: {
                xtype: 'combobox',
                        queryMode: 'local',
                        displayField: 'name',
                        valueField: 'id',
                        name: 'permit_productscategory_id',
                        listeners:
                         {
                             beforerender: {//getConfigParamFromTable
                                fn: 'setConfigCombosStore',
                                config: {
                                    pageSize: 10000,
                                    proxy: {
                                        url: 'configurations/getConfigParamFromTable',
                                        extraParams: {
                                            table_name: 'par_permitsproduct_categories'
                                        }
                                    }
                                },
                                isLoad: true
                            },
                         change: function() {
                            Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                         }
                     }
                    
                }
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'permitbrand_name', tdCls:'wrap-text',
            text: 'Drug Name',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'controlleddrugs_type',
            text: 'Drug Type',
            tdCls:'wrap-text',hidden: true,
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'controlled_drugssubstances',
            tdCls:'wrap-text',
            text: 'Drugs Substance',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'controlleddrugs_basesalt',
            text: 'Drugs Base Salt',hidden: true,
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'dosage_form',
            text: 'Dosage Form',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'product_strength',
            text: 'Product Strength',
            width: 150,
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'strength_asgrams',
            text: 'Strength As Grams',hidden: true,
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'pack_unitdetails', 
            text: 'Pack Unit Details',
            hidden: true,
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        },{
            
            xtype: 'gridcolumn',
            dataIndex: 'controlleddrug_base',
            text: 'Base (g)',hidden: true,
            tdCls:'wrap-text',
            width: 200,
            filter: {
                    xtype: 'textfield',
                }
        },{
        xtype: 'gridcolumn',
        dataIndex: 'manufacturer_name',
        name: 'manufacturer_name',hidden: true,
        text: 'Product Manufacturer',
        tdCls:'wrap-text',
        width: 200,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'country_oforigin',
        name: 'country_oforigin',
        text: 'Country of Origin',hidden: true,
        tdCls:'wrap-text',
        width: 200, hidden: true,
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'country_oforigin_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_countries'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'product_batch_no',
        name: 'product_batch_no',
        text: 'Product Batch Nos',hidden: true,
        tdCls:'wrap-text',
        width: 200,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'product_manufacturing_date',
        name: 'product_manufacturing_date',
        text: 'Manufacturing Date',hidden: true,
        tdCls:'wrap-text',
        width: 200,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'product_expiry_date',
        name: 'product_expiry_date',
        text: 'Expiry Date',
        tdCls:'wrap-text',
        width: 200,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    },
      {
        xtype: 'gridcolumn',
        dataIndex: 'quantity',
        tdCls:'wrap-text',
        name: 'quantity',hidden: true,
        text: 'Quantity',
        width: 200,
        filter: {
                xtype: 'textfield',
            }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'packaging_unit',
        name: 'packaging_unit',
        tdCls:'wrap-text',
        text: 'Packaging Units',
        width: 200, hidden: true,
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'packaging_unit_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_packaging_units'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'weight',
        name: 'weight',
        tdCls:'wrap-text',
        text: 'Total Weight',
        width: 200,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'weight_unit',
        name: 'weight_unit',
        tdCls:'wrap-text',
        text: 'Weight Units',
        width: 200,
         filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'weight_unit',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_weights_units'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'unit_price',
        name: 'unit_price',
        tdCls:'wrap-text',
        text: 'Unit Price',
        width: 200,hidden: true,
        filter: {
                xtype: 'textfield'
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'total',
        name: 'total',
        tdCls:'wrap-text',
        text: 'Total Value',
        width: 200,hidden: true,
        filter: {
                xtype: 'textfield'
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'currency',
        name: 'currency',
        tdCls:'wrap-text',
        text: 'Payment Currency',
        width: 200, hidden: true,
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'currency_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_currencies'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'Applicant',
        name: 'Applicant',
        tdCls:'wrap-text',
        text: 'Applicant',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantPostalA',
        name: 'ApplicantPostalA',
        tdCls:'wrap-text',
        text: 'Applicant Postal Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantPhysicalA',
        name: 'ApplicantPhysicalA',
        tdCls:'wrap-text',
        text: 'Applicant Physical Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantTell',
        name: 'ApplicantTell',
        tdCls:'wrap-text',
        text: 'Applicant Telephone No',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantMobile',
        name: 'ApplicantMobile',
        tdCls:'wrap-text',
        text: 'Applicant Mobile No',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantEmail',
        name: 'ApplicantEmail',
        tdCls:'wrap-text',
        text: 'Applicant Email Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantCountry',
        name: 'ApplicantCountry',
        tdCls:'wrap-text',
        text: 'Applicant Country',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantRegion',
        name: 'ApplicantRegion',
        tdCls:'wrap-text',
        text: 'Applicant Region',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'premisename',
        name: 'premisename',
        tdCls:'wrap-text',
        text: 'Premises Name',
        width: 200,hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'premisePostalA',
        name: 'premisePostalA',
        tdCls:'wrap-text',
        text: 'Premises Postal Address',
        width: 210,hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'premisePhysicalA',
        name: 'premisePhysicalA',
        tdCls:'wrap-text',
        text: 'Premises Physical Address',
        width: 210,hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'premiseTell',
        name: 'premiseTell',
        tdCls:'wrap-text',
        text: 'Premises Telephone',
        width: 200,hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'premiseMobile',
        name: 'premiseMobile',
        tdCls:'wrap-text',
        text: 'Premises Mobile',
        width: 200,hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'datecolumn',
        dataIndex: 'premiseExpiryDate',
        name: 'premiseExpiryDate',
        tdCls:'wrap-text',
        text: 'Premises Expiry Date',
        format: 'Y-m-d',
        width: 200,hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'consignee',
        name: 'consignee',
        tdCls:'wrap-text',
        text: 'Consignee',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'Cpostal_address',
        name: 'Cpostal_address',
        tdCls:'wrap-text',
        text: 'Consignee Postal Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'Cphysical_address',
        name: 'Cphysical_address',
        tdCls:'wrap-text',
        text: 'Consignee Physical Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'Ctelephone_no',
        name: 'Ctelephone_no',
        tdCls:'wrap-text',
        text: 'Consignee Telephone No',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'Cmobile_no',
        name: 'Cmobile_no',
        tdCls:'wrap-text',
        text: 'Consignee Mobile No',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'Cemail_address',
        name: 'Cemail_address',
        tdCls:'wrap-text',
        text: 'Consignee Email Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'Ccountry',
        tdCls:'wrap-text',
        name: 'Ccountry',
        text: 'Consignee Country',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'Cregion',
        name: 'Cregion',
        tdCls:'wrap-text',
        text: 'ConsigneeRegion',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'consigneeoption',
        name: 'consigneeoption',
        tdCls:'wrap-text',
       text: 'Consignee Options',
       width: 200, hidden: true,
       filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'consignee_options_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_consignee_options'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'senderreceiver',
        name: 'senderreceiver',
        tdCls:'wrap-text',
        text: 'Sender/Receiver',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'SRpostal_address',
        name: 'SRpostal_address',
        tdCls:'wrap-text',
        text: 'Sender/Receiver Postal Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'SRphysical_address',
        name: 'SRphysical_address',
        tdCls:'wrap-text',
        text: 'Sender/Receiver Physical Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'SRtelephone_no',
        name: 'SRtelephone_no',
        tdCls:'wrap-text',
        text: 'Sender/Receiver Telephone No',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
                }
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'SRmobile_no',
        name: 'SRmobile_no',
        tdCls:'wrap-text',
        text: 'Sender/Receiver Mobile No',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'SRemail_address',
        name: 'SRemail_address',
        tdCls:'wrap-text',
        text: 'Sender/Receiver Email Address',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'SRcountry',
        name: 'SRcountry',
        tdCls:'wrap-text',
        text: 'Sender/Receiver Country',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'SRregion',
        name: 'SRregion',
        tdCls:'wrap-text',
        text: 'Sender/Receiver Region',
        width: 210, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },
      {
        xtype: 'gridcolumn',
        dataIndex: 'category',
        name: 'category',
        tdCls:'wrap-text',
        text: 'Application Category/Reason',
        width: 200,hidden: true,
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'permit_category_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                     extraParams: {
                                        table_name: 'par_permit_category'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'typecategory',
        name: 'typecategory',
        tdCls:'wrap-text',
        text: 'Product Category',
        width: 210, hidden: true,
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'import_typecategory_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_permit_typecategories'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'port',
        name: 'port',
        tdCls:'wrap-text',
        text: 'Port of Entry/Exit',
        width: 200, hidden: true,
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'port_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_ports_information'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'mode_oftransport',
        name: 'mode_oftransport',
        text: 'Modes of Transport',
        tdCls:'wrap-text',
        width: 200, hidden: true,
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'mode_oftransport_id',
                    listeners:{
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_modesof_transport'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'issueplace',
        name: 'issueplace',
        tdCls:'wrap-text',
        text: 'Certificate/Permit Issue Place',
        width: 210, hidden: true,
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
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'proforma_invoice_no',
        name: 'proforma_invoice_no',
        tdCls:'wrap-text',
        text: 'Proforma/Commercial Invoice No',
        width: 200, hidden: true,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'datecolumn',
        dataIndex: 'proforma_invoice_date',
        name: 'proforma_invoice_date',
        text: 'Proforma Invoice Date',
        tdCls:'wrap-text',
        format: 'Y-m-d',
        width: 200, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    }, {
        xtype: 'datecolumn',
        dataIndex: 'ReceivedFrom',
        name: 'ReceivedFrom',
        format: 'Y-m-d',
        tdCls:'wrap-text',
        text: 'Received From',
        width: 210, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    }, {
        xtype: 'datecolumn',
        dataIndex: 'CertIssueDate',
        name: 'CertIssueDate',
         format: 'Y-m-d',
         tdCls:'wrap-text',
        text: 'Permit Issue Date',
        width: 210, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    }, {
        xtype: 'datecolumn',
        dataIndex: 'CertExpiryDate',
        name: 'CertExpiryDate',
         format: 'Y-m-d',
         tdCls:'wrap-text',
        text: 'Permit Expiry Date',
        width: 210, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    },
   {
        xtype: 'gridcolumn',
        dataIndex: 'receipt_no',
        name: 'receipt_no',
        tdCls:'wrap-text',
        text: 'Receipt No',
        width: 150, hidden:true,
         filter: {
                 xtype: 'textfield',
             }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'invoice_no',
        name: 'invoice_no',
        tdCls:'wrap-text',
        text: 'Invoice No',
        width: 150, hidden:true,
         filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'invoice_amount',
        name: 'invoice_amount',
        tdCls:'wrap-text',
        text: 'Invoice Amount',
        width: 150, hidden:true,
    },{
        xtype: 'gridcolumn',
        dataIndex: 'amount_paid',
        name: 'amount_paid',
        tdCls:'wrap-text',
        text: 'Amount Paid',
        width: 150, hidden:true,
    },{
        xtype: 'gridcolumn',
        dataIndex: 'pay_currency',
        name: 'pay_currency',
        tdCls:'wrap-text',
        text: 'Pay Currency',
        width: 100, hidden:true,
    }, {
        xtype: 'datecolumn',
        dataIndex: 'ReceivedTo',
        name: 'ReceivedTo',
        tdCls:'wrap-text',
         format: 'Y-m-d',
        text: 'Received To',
        width: 210, hidden: true,
        filter: {
                xtype: 'datefield',
                format: 'Y-m-d'
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'dosage_form',
        name: 'dosage_form',
        tdCls:'wrap-text',
        text: 'Dosage Form',
        width: 150,
        tdCls: 'wrap-text',
        hidden: true,
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
                                        table_name: 'par_dosage_forms'
                                    }
                                }
                            },
                            isLoad: true
                        },
                     change: function() {
                        Ext.data.StoreManager.lookup('spreadsheetiepermitapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'productapproval_type_id',
        name: 'productapproval_type_id',
        text: 'Product Reg-Category',
        width: 150,
        tdCls: 'wrap-text',
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'productapproval_type_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_productapproval_types'
                                    }
                                }
                            },
                            isLoad: true
                        },
                    change: function(cmb, newValue, oldValue, eopts) {
                        var grid = cmb.up('grid');
                            grid.getStore().reload(); }
                 }
                
            }
    },{   
        xtype: 'gridcolumn',
        dataIndex: 'permitprod_recommendation_id',
        tdCls:'wrap-text',
        text: 'Screening Recommendation',
        width: 150,hidden: true,
        tdCls: 'wrap-text',
         filter: {
                xtype: 'combo',
                store: 'permitprod_recommendationstr',
                valueField: 'id',   name: 'permitprod_recommendation_id',
                displayField: 'name',
                queryMode: 'local',
                listeners: {
                    change: function(cmb, newValue, oldValue, eopts) {
                        var grid = cmb.up('grid');
                            grid.getStore().reload(); }
                }
            },
            renderer: function (val, meta, record, rowIndex, colIndex, store, view) {
                var textVal = 'Select Recommendation';
              /*  if (view.grid.columns[colIndex].getEditor().getStore().getById(val)) {
                   // textVal = view.grid.columns[colIndex].getEditor().getStore().getById(val).data.name;
                }
                */
                if (val == 2) {
                    meta.tdStyle = 'color:white;background-color:green';
                    textVal = 'Accepted';
                    
                }else if(val == 3){
                    meta.tdStyle = 'color:white;background-color:red';
                    textVal = 'Rejected';
                   
                }else if(val == 3){
                    meta.tdStyle = 'color:white;background-color:yellow';
                    textVal = 'Queried';
                   
                }else{
                    meta.tdStyle = 'color:white;background-color:blue';
                    textVal = 'Accepted';
                }
                
                return textVal;
            }
      },{   
        xtype: 'gridcolumn',
        dataIndex: 'permitprod_recommendation_remarks',
        tdCls:'wrap-text',
        text: 'Screening Remarks',
        width: 150,hidden: true,
        tdCls: 'wrap-text',
        filter: {
            xtype:'textfield'
        }
      },{
        xtype: 'gridcolumn',
        dataIndex: 'review_recommendation',
        name: 'review_recommendation',
        text: 'Approval Recommendation',
        width: 150,hidden: true,
        tdCls: 'wrap-text',
        filter: {
            xtype: 'combobox',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'review_recommendation_id',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'par_permits_reviewrecommendations'
                                    }
                                }
                            },
                            isLoad: true
                        },
                    change: function(cmb, newValue, oldValue, eopts) {
                        var grid = cmb.up('grid');
                            grid.getStore().reload(); }
                 }
                
            }
    },
    ],bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} out of {2}',
        emptyMsg: 'No Records',
        beforeLoad: function () {
                    var store = this.getStore(),
                     range = this.down('combo[name=Range]').getValue();
                     var grid=this.up('grid'),
                        
                         form = grid.up('#spreadsheetpermitcnt'),
                      permit_category_id=grid.down('combo[name=permit_category_id]').getValue(),
                      import_typecategory_id=grid.down('combo[name=import_typecategory_id]').getValue(),
                      permit_reason_id=grid.down('combo[name=permit_reason_id]').getValue(),
                      port_id=grid.down('combo[name=port_id]').getValue(),
                      currency_id=grid.down('combo[name=currency_id]').getValue(),
                      consignee_options_id=grid.down('combo[name=consignee_options_id]').getValue(),
                      zone_id=grid.down('combo[name=zone_id]').getValue();
                      weight_unit=grid.down('combo[name=weight_unit]').getValue();
                      mode_oftransport_id=grid.down('combo[name=mode_oftransport_id]').getValue();
                      review_recommendation_id=grid.down('combo[name=review_recommendation_id]').getValue();
                      
                      productapproval_type_id=grid.down('combo[name=productapproval_type_id]').getValue();
                      permitprod_recommendation_id=grid.down('combo[name=permitprod_recommendation_id]').getValue();
                      
                      received_from_date=form.down('datefield[name=received_from_date]').getValue(),
                      received_to_date=form.down('datefield[name=received_to_date]').getValue(),
                      
                      approved_from_date=form.down('datefield[name=approved_from_date]').getValue(),
                      approved_to_date=form.down('datefield[name=approved_to_date]').getValue();


               //acquire original filters
               var filter = {'t1.regulated_producttype_id':IEP_sectionid,'t1.sub_module_id':IEP_sub_module};
               var   filters = JSON.stringify(filter);

              //pass to store
                store.getProxy().extraParams = {
                    pageSize:range, 
                    approved_to_date:approved_to_date,
                    approved_from_date:approved_from_date,
                    received_to_date:received_to_date,
                    received_from_date:received_from_date,
                    permit_category: permit_category_id,
                    import_typecategory: import_typecategory_id,
                    permit_reason: permit_reason_id,
                    port: port_id,
                    currency: currency_id,
                    review_recommendation_id:review_recommendation_id,
                    productapproval_type_id:productapproval_type_id,
                    permitprod_recommendation_id:permitprod_recommendation_id,
                    consignee_options:consignee_options_id,
                    issueplace:zone_id,
                    mode_oftransport_id:mode_oftransport_id,
                    weight_unit:weight_unit,
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
     listeners: {
        afterrender: 'funcReloadspreadSheetStrs',
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 10000,
                storeId: 'controldrgspreadsheetieapplicationcolumnsstr',
                proxy: {
                    url: 'openoffice/getControlledDrugsProductsIESpreadSheet',
                }
            },
            isLoad: true
        },
    },
});