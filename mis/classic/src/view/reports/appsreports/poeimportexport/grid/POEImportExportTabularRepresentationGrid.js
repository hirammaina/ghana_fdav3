Ext.define('Admin.view.reports.appsreports.poeimportexportreport.grid.POEImportExportTabularRepresentationGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'productreportctr',
    xtype: 'poeimportexporttabularrepresentationgrid',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    export_title:'Import & Export Summary',
    viewConfig: {
    deferEmptyText: false,
        emptyText: 'Nothing to display'
    },

   
              listeners: {
                    beforerender: {
                        fn: 'setConfigGridsStore',
                        config: {
                            pageSize: 1000,
                            groupField: 'port_of_entry',
                            storeId: 'importtabularrepresentationgridstr',
                            proxy: {
                                url: 'newreports/getPOEImportExportSummaryReport',
                                extraParams: {
                                        module_id: 4
                                    }
                            }
                        },
                        isLoad: false
                      }
                   },
            plugins: [{
                    ptype: 'gridexporter'
                }
                ],
            features: [{
                 startCollapsed: true,
                 ftype: 'groupingsummary'
            }],
    columns: [{
                text: 'Port of Entry/Exit',
                sortable: false,
                flex: 1,
                tdCls:'wrap-text',
                dataIndex: 'port_of_entry',
                summaryRenderer: function(){
                        return '<b>Grand Total:</b>';
                    }
            },{
                text: 'Product Type',
                sortable: false,
                flex: 1,
                tdCls:'wrap-text',
                dataIndex: 'section_name',
                summaryRenderer: function(){
                        return '<b>Grand Total:</b>';
                    }
            },
            
            {
                text: 'Passed Inspection & released',
                flex: 1,
                dataIndex: 'passed_inspection',
                        summaryType: 'sum',
                summaryRenderer: function(value){
                             return(value);
                  },

            }, {
                text: 'Released Under Seal',
                flex: 1,
                dataIndex: 'released_under_seal',
                summaryType: 'sum',
                summaryRenderer: function(value){
                             return(value);
                  },
            }, {
                text: 'Passed Inspection at Owners Premises',
                flex: 1,
                dataIndex: 'passsed_inspectionat_ownerspremises',
                summaryType: 'sum',
                summaryRenderer: function(value){
                             return(value);
                  },
            }, {
                text: 'Pending Inspection Under Seal',
                flex: 1,
                dataIndex: 'pendingreleased_under_seal',
                summaryType: 'sum',
                summaryRenderer: function(value){
                             return(value);
                  },
            }, {
                text: 'Quarantined for Rejection',
                flex: 1,
                dataIndex: 'quarantined_for_rejection',
                summaryType: 'sum',
                summaryRenderer: function(value){
                             return(value);
                  },
            },{
                text: 'Quarantined for Disposal',
                flex: 1,
                dataIndex: 'quarantined_for_disposal',
                summaryType: 'sum',
                summaryRenderer: function(value){
                             return(value);
                  },
            },
            
            
            {
                text: 'Recommended for Re-Export',
                flex: 1,
                dataIndex: 'recommended_forreexport',
                summaryType: 'sum',
                summaryRenderer: function(value){
                             return(value);
                  },
            }
        ],
          bbar: [ 
         {
                    xtype:'exportbtn',
                    ui: 'soft-green',
                    text: 'Export',
                    iconCls: 'x-fa fa-file'
           
          },{
                    xtype: 'pagingtoolbar',
                    width: '80%',
                    displayInfo: true,
                    hidden: false,
                    displayMsg: 'Showing {0} - {1} out of {2}',
                    emptyMsg: 'No Records',
                    beforeLoad: function() {
                        var store=this.getStore();
                        var grid=this.up('grid'),
                        tab = grid.up('tabpanel'),
                        panel = tab.up('panel'),
                        filter=panel.down('form'),
                                
                                   section_id = panel.down('combo[name=section_id]').getValue(),  
                                   from_date = panel.down('datefield[name=from_date]').getValue(),
                                   to_date = panel.down('textfield[name=to_date]').getValue(),
                                   permit_type = panel.down('combo[name=permit_type]').getValue(), 
                                   country_id = panel.down('combo[name=country_id]').getValue(), 
                                   port_id = panel.down('combo[name=port_id]').getValue(),  
                                   module_id=panel.down('hiddenfield[name=module_id]').getValue();
                              
                              frm = filter.getForm();
                              if (frm.isValid()) {
                             store.getProxy().extraParams = {

                                module_id: module_id,
                                section_id:section_id,
                                permit_type: permit_type,
                                country_id: country_id,
                                port_id: port_id,
                                from_date: from_date,
                                to_date: to_date
                        
                            }
                            } else {
                        toastr.error('Please select all Filters first ', 'Failure Response');
                         return false;
                     }
                            
                        },
                      
                    
                }]
    
});
