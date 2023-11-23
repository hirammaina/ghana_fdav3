Ext.define('Admin.view.summaryreport.productrevenuereport.grid.RevProductRegisterGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'registerctr',
    xtype: 'revproductregistergrid',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    viewConfig: {
    deferEmptyText: false,
        emptyText: 'Nothing to display'
    },
    listeners: {
        beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 25,
                //groupField: 'SubModule',
                storeId: 'productregistergridstr', remoteFilter:true,
                proxy: {
                    url: 'registers/getProductRevenueRegister',
                      reader: {
                         type: 'json',
                         rootProperty: 'results',
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
     features: [
        {
            ftype: 'grouping',
            startCollapsed: false,
            hideGroupedHeader: true,
            enableGroupingMenu: false
        }
    ],
    columns: [{
        text: 'Product Type',
        sortable: false,
        width: 150,
        dataIndex: 'section_name',
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'reference_no',
        name: 'reference_no',
        text: 'Reference No',
        width: 150,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'added_on',
        name: 'added_on',
        text: 'Date Received',
        width: 150
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'brand_name',
        name: 'brand_name',
        text: 'Brand Name',
        width: 150,
        filter: {
                xtype: 'textfield',
            }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'commonName',
        name: 'commonName',
        text: 'CommonName',
        width: 200,
         filter: {
                xtype: 'textfield',
            }
    },
      {
        xtype: 'gridcolumn',
        dataIndex: 'Classification',
        name: 'Classification',
        text: 'Classification',
        width:200,
        growToLongestValue : true,
        filter: {
           xtype: 'textfield',
         }
    },
        {
        xtype: 'gridcolumn',
        dataIndex: 'product_strength',
        name: 'product_strength',
        text: 'Product Strength',
        width: 150,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'active_api',
        name: 'active_api',
        text: 'Active API',
        width: 150,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ProductForm',
        name: 'ProductForm',
        text: 'Dosage Form',
        width: 150,
        filter: {
           xtype: 'textfield',
        }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'Applicant',
        name: 'Applicant',
        text: 'MA Holder',
        width: 150, 
         filter: {
                    xtype: 'textfield'                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantPhysicalA',
        name: 'ApplicantPhysicalA',
        text: 'MAH Physical Address',
        width: 210,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantEmail',
        name: 'ApplicantEmail',
        text: 'MA Email',
        width: 200, 
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ApplicantCountry',
        name: 'ApplicantCountry',
        text: 'MAH Country',
        width:200, 
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'LocalAgent',
        name: 'LocalAgent',
        text: 'Local Agent',
        width: 200, 
        filter: {
                    xtype: 'textfield'
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'LocalAgentPhysicalA',
        name: 'LocalAgentPhysicalA',
        text: 'Local Agent Physical Address',
        width: 210,
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'LocalAgentEmail',
        name: 'LocalAgentEmail',
        text: 'Local Agent Email',
        width: 210, 
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'AgentCountry',
        name: 'AgentCountry',
        text: 'Local Agent Country',
        width: 210, 
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'Manufacturer',
        name: 'Manufacturer',
        text: 'Manufacturer',
        width: 150, 
         filter: {
                    xtype: 'textfield'                
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ManufacturerPhysicalA',
        name: 'ManufacturerPhysicalA',
        text: 'Manufacturer Physical Address',
        width: 210, 
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ManufacturerEmail',
        name: 'ManufacturerEmail',
        text: 'Manufacturer Email',
        width: 200, 
        filter: {
                xtype: 'textfield',
            }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'ManufacturerCountry',
        name: 'ManufacturerCountry',
        text: 'Manufacturer Country',
        width:200,
        filter: {
                xtype: 'textfield',
            },
        },  {
            xtype: 'gridcolumn',
            dataIndex: 'invoice_no',
            text: 'Invoice No',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                xtype: 'textfield'
            }
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'receipt_no',
            text: 'Receipt No',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                xtype: 'textfield'
            }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'trans_date',
            text: 'Payment Date',
            width:100,
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'iremboInvoiceNumber',
            tdCls:'wrap-text',
            text: 'iREMBO Invoice Number',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                xtype: 'textfield'
            }
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'paymentStatus',
            tdCls:'wrap-text',
            text: 'iREMBO Payment Status',
            tdCls:'wrap-text',
            width: 200,
             renderer: function (value, metaData,record) {
                var paymentStatus = record.get('paymentStatus')
                if (paymentStatus == 'PAID') {
                    metaData.tdStyle = 'color:white;background-color:green';
                    return value;
                }
                metaData.tdStyle = 'color:white;background-color:red';
                return value;
            }
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'bank_name',
            text: 'Bank Name',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                xtype: 'textfield'
            }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'payment_ref_no',
            text: 'Payment Reference No',
            tdCls:'wrap-text',
            width: 200,
            filter: {
                xtype: 'textfield'
            }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'invoice_amount',align:'right',
            style: 'text-align:left',
            text: 'Invoice Amount',
            tdCls:'wrap-text',
            width: 120,
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'invcurrency_name',align:'right',
            style: 'text-align:left',
            text: 'Currency Name',
            tdCls:'wrap-text',
            width: 80,
        },  {
            xtype: 'gridcolumn',
            dataIndex: 'exchange_rate',align:'right',
            style: 'text-align:left',
            text: 'Exchange Rate',
            tdCls:'wrap-text',
            width: 100
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'amount_paid',
            align:'right',
            style: 'text-align:left',
            text: ' Amount Paid',
            tdCls:'wrap-text',
            width: 120,
            filter: {
                xtype: 'textfield'
            }
        },{
            xtype: 'gridcolumn',
            dataIndex: 'currency_name',align:'right',
            style: 'text-align:left',
            text: 'Currency Name',
            width:80
        }, {
            xtype: 'gridcolumn',
            dataIndex: 'amount_paidtshs',align:'right',
            style: 'text-align:left',
            text: 'Amount Paid(Converted)',
            width:120
        },{
            xtype: 'gridcolumn',
            dataIndex: 'balance',align:'right',
            style: 'text-align:left',
            text: 'Balance',
            width:120
        }, {
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
                        text: 'Print Payments',
                        iconCls: 'x-fa fa-print',
                        handler: 'funcPrintApplicationREceipts'
                    },{
                        text: 'Print Invoice',
                        iconCls: 'x-fa fa-print',
                        handler: 'funcPrintApplicationInvoice'
                    }]
                }
            }
        }],
         bbar: [


         {
                          xtype: 'pagingtoolbar',
                          width: '100%',
                          displayInfo: true,
                          displayMsg: 'Showing {0} - {1} out of {2}',
                          emptyMsg: 'No Records',


                          //filter
                            beforeLoad: function () {
                                      var store = this.getStore(),
                                      range = this.down('combo[name=Range]').getValue();
                                       var store=this.getStore();
                                       var grid=this.up('grid'),
                                        panel = grid.up('panel'),
                                        filter=panel.down('form'), 
                                        sub_module_id = panel.down('combo[name=sub_module_id]').getValue(),
                                        prodclass_category = panel.down('combo[name=prodclass_category]').getValue(),
                                        section_id = panel.down('combo[name=section_id]').getValue(),
                                        classification_category = panel.down('combo[name=classification_category]').getValue(),
                                        paid_fromdate = panel.down('datefield[name=paid_fromdate]').getValue(),
                                        paid_todate = panel.down('datefield[name=paid_todate]').getValue();

                                        module_id=panel.down('hiddenfield[name=module_id]').getValue();

                                  store.getProxy().extraParams = {
                                        limit:range,
                                        sub_module_id:sub_module_id,
                                        module_id: module_id,
                                        section_id: section_id,
                                        classification_category: classification_category,
                                        paid_fromdate: paid_fromdate,
                                        paid_todate: paid_todate,
                                        prodclass_category: prodclass_category
        
                                     
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
                      }
                      ]
    
});
