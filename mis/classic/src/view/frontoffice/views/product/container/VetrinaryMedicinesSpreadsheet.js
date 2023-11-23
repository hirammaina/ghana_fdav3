Ext.define('Admin.view.frontoffice.product.container.VetrinaryMedicinesSpreadsheet', {
    extend: 'Ext.form.Panel',
    xtype: 'vetrinarymedicinesspreadsheet',
    layout:'border',
    controller: 'spreadsheetproductctr',
    tbar: [{
      xtype:'hiddenfield',
      name: 'regulated_producttype_id',
      value: 7
    },{
                    xtype: 'combobox',
                    forceSelection: true,
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    name: 'sub_module',
                    widht: 320,
                    fieldLabel: 'Application Type',
                    labelAlign: 'top',
                    //value: 'New',
                    listeners:
                     {
                         beforerender: {//getConfigParamFromTable
                            fn: 'setConfigCombosStore',
                            config: {
                                pageSize: 10000,
                                proxy: {
                                    url: 'configurations/getConfigParamFromTable',
                                    extraParams: {
                                        table_name: 'sub_modules',
                                        filters: '{"module_id":1}'
                                    }
                                    
                                }
                            },
                            isLoad: true
                        },
                        beforequery: function() {
                          var store=this.getStore();
                            var all={name: 'All Product Applications(new,renewal ....)',id:0};
                              store.insert(0, all);
                              var all={name: ' Products Registry(Active Applications)',id:103};
                               store.insert(1, all);
                            },
                        change: 'reloadSheetStore',
                       }
                 },{
                  xtype: 'combobox',
                  forceSelection: true,
                  queryMode: 'local',
                  displayField: 'name',
                  valueField: 'id',
                  name: 'section_id',
                  widht: 320,
                  fieldLabel: 'Product Type',
                  labelAlign: 'top',
                  //value: 'New',
                  listeners:
                   {
                       beforerender: {//getConfigParamFromTable
                          fn: 'setConfigCombosStore',
                          config: {
                              pageSize: 10000,
                              proxy: {
                                  url: 'configurations/getConfigParamFromTable',
                                  extraParams: {
                                      table_name: 'par_sections',
                                      filters: '{"regulated_producttype_id":7}'
                                  }
                                  
                              }
                          },
                          isLoad: true
                      },
                      beforequery: function() {
                        var store=this.getStore();
                          var all={name: 'All Product Types',id:0};
                            store.insert(0, all);
                           
                          },
                      change: 'reloadSheetStore',
                     }
               },{
                  xtype: 'datefield',
                  format: 'Y-m-d',
                  fieldLabel: 'Received From Date',
                  name: 'received_from_date',
                  width: 250,
                  submitFormat: 'Y-m-d',
                  labelAlign: 'top',
                  listeners:{
                      change: 'reloadSheetStore',
                
                  }
          },{
                    xtype: 'datefield',
                    format: 'Y-m-d',
                    fieldLabel: 'Received From To',
                    name: 'received_to_date',
                    width: 250,
                    submitFormat: 'Y-m-d',
                    labelAlign: 'top', 
                    listeners:{
                        change: 'reloadSheetStore',
                  
                    }
              
            },{
                    xtype: 'datefield',
                    format: 'Y-m-d',
                    fieldLabel: 'Approved From Date',
                    name: 'approved_from_date',
                    width: 250,
                    labelAlign: 'top',
                    listeners:{
                        change: 'reloadSheetStore',
                  
                    }
              
            },{
                        xtype: 'datefield',
                        format: 'Y-m-d',
                        fieldLabel: 'Approved From To',
                        name: 'approved_to_date',
                        width: 250,
                        labelAlign: 'top',
                        listeners:{
                          change: 'reloadSheetStore',
                    
                      }
                  
              },'->',
                  { 
                       xtype: 'button', 
                       text: 'Export Summary',
                       ui: 'soft-purple',
                       name: 'summary',
                       gridXtype: 'spreadsheetview',
                       iconCls: 'x-fa fa-cloud-upload', 
                       handler: 'func_exportproductspreadsheet'
                    },{ 
                       xtype: 'button', 
                       text: 'Export Detailed Report(All)',
                       ui: 'soft-purple',
                       gridXtype: 'spreadsheetview',
                       name: 'detailed',
                       iconCls: 'x-fa fa-cloud-upload', 
                       handler: 'func_exportproductspreadsheet'
                    },{
                       xtype: 'button', 
                       text: 'clear Filter',
                       ui: 'soft-purple',
                       name: 'clearFilter',
                       iconCls: 'x-fa fa-refresh', 
                       handler: 'func_clearfilters'
                    },{
                      xtype: 'hiddenfield',
                      name: 'section_id',
                      value: 7
                    }
                  ],
               items: [{
                  xtype: 'panel',
                  titleCollapse: true,
                  title: 'View free Options',
                  region:'west',
                  collapsible: true, 
                   height: '100%',
                  preventHeader: true, 
                  width: 200,
                  border: true,
                  split: true,
                  layout:'border',
                  items: [{
                    xtype: 'spreadsheetproductvisiblecolumns',
                    region: 'center'
                 }]
               },{
                  xtype: 'spreadsheetview',
                  region:'center',
                  listeners: {
                    beforerender: {
                        fn: 'setCommonGridsStore',
                        config: {
                            pageSize: 1000,
                            storeId: 'vetrinarymedicinesspreadsheetstr',
                            remoteFilter: true,
                            proxy: {
                              url: 'openoffice/getProductsApplicationColumns',
                              headers: {
                                  'Authorization':'Bearer '+access_token
                              },
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
                  }
                  
               },
               {
                  title: 'Additional Information',
                  xtype: 'panel',
                  collapsible: true, 
                  collapsed: true,
                  titleCollapse: true,
                  width:250,
                  split: true,
                  autoScroll : true,
                //  preventHeader: true, 
                  border: true,
                  region: 'east',
                  layout: 'accordion',
                  items:[{
                          xtype: 'productingridientsview',
                          height: 250
                  },{
                  	xtype: 'productnutrientsview',
                  	height: 250
                  },
                  {
                  	xtype: 'productpackagingview',
                  	height: 250
                  },
                  {
                  	xtype: 'productmanufacturerview',
                  height: 250
                  },{
                  	xtype: 'productinspectionview',
                  	height: 150
                  },{
                  xtype: 'productsampleinfoview',
                  height: 250
                },{
                  xtype: 'productImageview',
                  height: 250
                }]
                 
          }]
});