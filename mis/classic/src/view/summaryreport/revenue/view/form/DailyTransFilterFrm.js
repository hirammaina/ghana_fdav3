var date = new Date();
var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
Ext.define('Admin.view.summaryreport.revenue.form.DailyTransFilterFrm', {
	extend: 'Ext.form.Panel',
	xtype: 'dailyTransFilterFrm',
	layout: {
        type: 'column'
    },
	defaults:{
		margin: 5,
        columnWidth: 0.2
	},
   items: [{
            xtype: 'combo',
            fieldLabel: 'Module',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            labelAlign : 'top',
            displayField: 'name',
            name: 'module_id',
            allowBlank: true,
            fieldStyle: {
                'color': 'green',
                'font-weight': 'bold'
            },
            listeners: {
                beforerender: {
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                        url: 'configurations/getConfigParamFromTable',
                        extraParams: {
                            table_name: 'modules'
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
                change: 'func_setSubmodule'

            }
        },{
            xtype: 'combo',
            fieldLabel: 'Sub Module',
            emptyText: 'All',
        
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
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 1000,
                        proxy: {
                            url: 'configurations/getConfigParamFromTable',
                            extraParams: {
                                table_name: 'sub_modules',
                            }
                        }
                    },
                    isLoad: false
                },
                
            
            }

        },{
            xtype: 'combo',
            fieldLabel: 'Section',
         
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
                    fn: 'setConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                        url: 'configurations/getConfigParamFromTable',
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
                afterrender: function(combo) {
                            combo.select(combo.getStore().getAt(0));    
                        },

            }
        },{
            xtype: 'datefield',
            fieldLabel: 'From',
            allowBlank: true,
       
            labelAlign : 'top',
            name: 'from_date',
            value: firstDay,
            maxValue: new Date()  // limited to the current date or prior
        }, {
            xtype: 'datefield',
            fieldLabel: 'To',
            allowBlank: true,
            labelAlign : 'top',
            name: 'to_date',
            value: lastDay // defaults to today
        }],
        buttons:[{    
                 xtype: 'button',
                text: 'Search Filter',
                name: 'filter_Report',
                ui: 'soft-green',
                iconCls: 'fa fa-search',
                handler: 'func_LoadreportFilters',
                formBind: true,
        }]
});