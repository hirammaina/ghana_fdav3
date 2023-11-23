Ext.define('Admin.view.registers.views.importexportregister.form.ImportExportRegisterFiltersFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'importexportregisterfiltersfrm',
    style: 'margin:0px',
    layout: 'column',
    defaults: {
        columnWidth: 0.2
    },
   items: [ {
    xtype: 'hiddenfield',
    name: 'module_id',
    value: 4,
    hidden: true
},{
xtype: 'combo',
emptyText: 'Sub Process(Sub module)',
 margin: 2,
labelAlign : 'top',
valueField: 'id',
displayField: 'name',
forceSelection: true,
name: 'sub_module_id',
queryMode: 'local',
allowBlank: true,
fieldStyle: {
    'color': 'green',
    'font-weight': 'bold'
},
listeners: {
    beforerender: {
        fn: 'setWorkflowCombosStore',
        config: {
            pageSize: 1000,
            proxy: {
                url: 'workflow/getSystemSubModules',
                extraParams: {
                    model_name: 'SubModule',
                    module_id: 4
                }
            }
        },
        isLoad: true
    },
     beforequery: function() {
        var store=this.getStore();
        
        var all={name: 'All',id:0};
          store.insert(0, all);
        },
     afterrender: function(combo) {
                combo.select(combo.getStore().getAt(0));    
            },
        //change: 'loadPermitTypeCombo',
 }

    },{
            xtype: 'combo',
            emptyText: 'Select Import Export Product Type(Sections)',
             margin: 2,
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            labelAlign : 'top',
            displayField: 'name',
            name: 'section_id',
            allowBlank: true,
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
                    
                    var all={name: 'All',id:0};
                      store.insert(0, all);
                    },


            }
        },{
        xtype: 'combo',
        emptyText: 'Select Import/Export Permit Use',
         margin: 2,
        forceSelection: true,
        queryMode: 'local',
        valueField: 'id',
        labelAlign : 'top',
        displayField: 'name',
        name: 'permit_type',
        allowBlank: true,
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

                  var all = { name: 'All', id: 0 };
                  store.insert(0, all);
              },
              afterrender: function(combo) {
                  combo.select(combo.getStore().getAt(0));
              },
              
          }       
            
    },{
        xtype: 'datefield',
        emptyText: 'Released From',
         margin: 2,
        columnWidth: 0.2,
        labelAlign : 'top',
        format: 'Y-m-d',
        name: 'released_from',
        allowBlank: true,
       // minValue: new Date(2020, 6),
        maxValue: new Date()
    },{
        xtype: 'datefield',
        name: 'released_to', 
        margin: 2,
        format: 'Y-m-d',
        emptyText: 'Released To',
        labelAlign : 'top',
        allowBlank: true,
       // minValue: new Date(2020, 6),
        maxValue: new Date()
    },{ 
        xtype: 'button',
        text: 'Filter Report',
        margin: 2,
        ui: 'soft-green',
        iconCls: 'fa fa-search',
        handler: 'loadImportExportRegisterFilters'
    } ]
   

});