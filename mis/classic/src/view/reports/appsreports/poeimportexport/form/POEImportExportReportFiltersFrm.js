Ext.define('Admin.view.reports.appsreport.importexportreport.form.POEImportExportReportFiltersFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'poeimportexportreportfiltersfrm',
    layout: 'column',
    defaults:{
        bodyPadding: 1,
        margins: '0 0 0 0',
    },
    defaults: {
        columnWidth: 0.25
    },
      items:[{
        xtype: 'hiddenfield',
        name: 'module_id',
        value: 4,
        hidden: true
    },{
            xtype: 'combo',
            emptyText: 'Select Import/Export Product Type(Sections)',
             margin: 2,
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            labelAlign : 'top',
            displayField: 'name',
            name: 'section_id',
            allowBlank: false,
            fieldStyle: {
                'color': 'green',
                'font-weight': 'bold'
            },
                listeners: {
                beforerender: {
                    fn: 'setOrgConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                        url: 'newreports/getSectionParams',
                        extraParams: {
                            table_name: 'par_sections'
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


            }
        },

        {
            xtype: 'combo',
            emptyText: 'Permit Use',
             margin: 2,
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            labelAlign : 'top',
            displayField: 'name',
            name: 'permit_type',
            allowBlank: false,
            fieldStyle: {
                'color': 'green',
                'font-weight': 'bold'
            },
             listeners: {
                beforerender: {
                    fn: 'setOrgConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                        url: 'newreports/getImportExportPermitType',
                        extraParams: {
                            table_name: 'par_permit_category'
                        }
                       }
                    },
                    isLoad: true
                },
                beforequery: function() {
                      var store = this.getStore();

                      var all = { name: 'All Permit Uses', id: 0 };
                      store.insert(0, all);
                  },
                  afterrender: function(combo) {
                      combo.select(combo.getStore().getAt(0));
                  },
                  
              }       
                
        },
          {
            xtype: 'combo',
            emptyText: 'Sender/Receiver Country (Optional)',
             margin: 2,
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            labelAlign : 'top',
            displayField: 'name',
            name: 'country_id',
            allowBlank: true,
            fieldStyle: {
                'color': 'blue',
                'font-weight': 'bold'
            },
             listeners: {
                beforerender: {
                    fn: 'setOrgConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                        url: 'configurations/getConfigParamFromTable',
                        extraParams: {
                          table_name: 'par_countries'
                        }
                       }
                    },
                    isLoad: true
                },
                beforequery: function() {
                      var store = this.getStore();

                      var all = { name: 'All Countries', id: 0 };
                      store.insert(0, all);
                  },
                  afterrender: function(combo) {
                      combo.select(combo.getStore().getAt(0));
                  },
                  
              }       
                
        },
          {
            xtype: 'combo',
            emptyText: 'Port of Entry/Exit (Optional)',
             margin: 2,
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            labelAlign : 'top',
            displayField: 'name',
            name: 'port_id',
            allowBlank: true,
            fieldStyle: {
                'color': 'blue',
                'font-weight': 'bold'
            },
             listeners: {
                beforerender: {
                    fn: 'setOrgConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                    url: 'configurations/getConfigParamFromTable',
                    extraParams: {
                        table_name: 'par_ports_information'
                        }
                       }
                    },
                    isLoad: true
                },
                beforequery: function() {
                      var store = this.getStore();

                      var all = { name: 'All Ports', id: 0 };
                      store.insert(0, all);
                  },
                  afterrender: function(combo) {
                      combo.select(combo.getStore().getAt(0));
                  },
                  
              }       
                
        },

        {
            xtype: 'datefield',
            emptyText: 'Date From',
              margin: 2,
            columnWidth: 0.25,
            labelAlign : 'top',
            format: 'Y-m-d',
            name: 'from_date',
            allowBlank: true,
            minValue: new Date(2020, 6)
        },{
            xtype: 'datefield',
            name: 'to_date',  margin: 2,
            format: 'Y-m-d',
            emptyText: 'Date To',
            labelAlign : 'top',
            allowBlank: true,
            minValue: new Date(2020, 6),
            maxValue: new Date()
        },{ 
            xtype: 'button',
            text: 'Filter Report',  margin: 2,
            name: 'filter_SummaryReport',
            ui: 'soft-green',
            iconCls: 'fa fa-search',
            handler: 'loadPOEImportExportReportFilters',
            formBind: true,
        }
       ]
});