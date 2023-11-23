Ext.define('Admin.view.reports.appsreports.controlleddrugsreport.grid.ControlledDrugsCertificateLicensesGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'productreportctr',
    xtype: 'controlleddrugscertificatelicensesgrid',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    export_title:'Controlled Drugs Reports',
    viewConfig: {
    deferEmptyText: false,
        emptyText: 'Nothing to display'
    },
     listeners: {
                    beforerender: {
                        fn: 'setConfigGridsStore',
                        config: {
                            pageSize: 1000,
                            groupField: 'SubModule',
                            storeId: 'controlleddrugscertificatelicensesgridstr',
                            proxy: {
                                url: 'newreports/getControlledDrugsImportProddetailsReport',
                                extraParams: {
                                        module_id: 12
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
                 ftype: 'grouping'
            }],
    columns: [{
                text: 'Permit Type',
                sortable: false,
                flex: 1,
                dataIndex: 'Permit_name',
                summaryRenderer: function(){
                        return '<b>Grand Total:</b>';
                    }
            }
        ],
        //Date of application, ref no./certificate number, importer(premise), brand name, generic name, manufacturer, manufacturer's country of origin, imported grams, supplier, supplier's country, appl.status
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
                                
                                   sub_module_id = panel.down('combo[name=sub_module_id]').getValue(),  
                                   from_date = panel.down('datefield[name=from_date]').getValue(),
                                   to_date = panel.down('textfield[name=to_date]').getValue(),
                                   permit_type = panel.down('combo[name=permit_type]').getValue(),  
                                   module_id=panel.down('hiddenfield[name=module_id]').getValue();
                              
                              frm = filter.getForm();
                              if (frm.isValid()) {
                             store.getProxy().extraParams = {

                                module_id: module_id,
                                sub_module_id: sub_module_id,
                                permit_type: permit_type,
                                from_date: from_date,
                                to_date: to_date
                        
                            }
                            } else {
                         return false;
                     }
                            
                        },
                      
                    
                }]
    
});
