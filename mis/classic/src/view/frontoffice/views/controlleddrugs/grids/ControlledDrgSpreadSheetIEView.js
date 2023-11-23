 Ext.define('Admin.view.frontoffice.importexport.grids.ControlledDrgSpreadSheetIEView', {
 extend: 'Ext.grid.Panel',  
   scroll: true,
   width: '100%',
    xtype: 'controlleddrgspreadsheetieview',
   layout: 'fit',
    title: 'Controlled Drugs Application SpreadSheet',
    referenceHolder: true,
   reference:'iegridpanel',
   plugins: [{
            ptype: 'filterfield'
        }],
       
         viewConfig: {
            emptyText: 'No products information found under this creteria'
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
                        childXtype: 'previewimportexportpermitdetails',
                        winTitle: 'Import/Export Permit Applications',
                        winWidth: '40%',
                        isReadOnly:1,
                        handler: 'editpreviewPermitinformation'
                    }]
              }
            }
         },{
            xtype: 'gridcolumn',
            dataIndex: 'tracking_no',
            name: 'tracking_no',
            text: 'Tracking No',
            tdCls:'wrap-text',
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
            tdCls:'wrap-text',
            width: 150,
            filter: {
                    xtype: 'textfield',
                }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'section_name',
            name: 'section_name',
            text: 'Permit Product Type',
            tdCls:'wrap-text',
            width: 200, hidden: true
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
                            Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
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
                        listeners:{
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
                            Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
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
                        Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
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
        text: 'Application Category',
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
                        Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'typecategory',
        name: 'typecategory',
        tdCls:'wrap-text',
        text: 'Application Type Category',
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
                        Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
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
                        Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
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
                        Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
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
                        Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
                     }
                 }
                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'proforma_invoice_no',
        name: 'proforma_invoice_no',
        tdCls:'wrap-text',
        text: 'Proforma Invoice No',
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
    }
    ],
    listeners: {
        select: 'loadadditionalinfo',
        afterrender: 'funcReloadspreadSheetStrs',
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 10000,
                storeId: 'controldrgspreadsheetieapplicationcolumnsstr',
                proxy: {
                    url: 'openoffice/getControlledDrugsIESpreadSheet',
                }
            },
            isLoad: true
        },
    },
    
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} out of {2}',
        emptyMsg: 'No Records',
        beforeLoad: function () {
                    var store = this.getStore(),
                     range = this.down('combo[name=Range]').getValue();
                     var grid=this.up('grid'),
                     form = grid.up('#spreadsheetpermitcnt');
                      permit_category_id=grid.down('combo[name=permit_category_id]').getValue(),
                      import_typecategory_id=grid.down('combo[name=import_typecategory_id]').getValue(),
                      permit_reason_id=grid.down('combo[name=permit_reason_id]').getValue(),
                      port_id=grid.down('combo[name=port_id]').getValue(),
                      //currency_id=grid.down('combo[name=currency_id]').getValue(),
                      consignee_options_id=grid.down('combo[name=consignee_options_id]').getValue(),
                      permit_productscategory_id=grid.down('combo[name=permit_productscategory_id]').getValue(),
                      mode_oftransport_id=grid.down('combo[name=mode_oftransport_id]').getValue(),

                      received_from_date=form.down('datefield[name=received_from_date]').getValue(),
                      received_to_date=form.down('datefield[name=received_to_date]').getValue(),
                      
                      approved_from_date=form.down('datefield[name=approved_from_date]').getValue(),
                      approved_to_date=form.down('datefield[name=approved_to_date]').getValue(),


                      zone_id=grid.down('combo[name=zone_id]').getValue();

                     
               //acquire original filters
               var filter = {'t1.regulated_producttype_id':IE_sectionid,'t1.sub_module_id':IE_sub_module};
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
                    permit_productscategory_id:permit_productscategory_id,
                    port: port_id,
                    
                    mode_oftransport_id:mode_oftransport_id,
                   // currency: currency_id,
                    consignee_options:consignee_options_id,
                    issueplace:zone_id,
                   // registration_status:registration_status,
                    //validity_status: validity_status,
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
    }]
});