
Ext.define('Admin.view.dashboard.grids.UserCompletedAssPerformancSummaryGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'usercompletedassperformancsummarygrid',
    controller: 'dashboardvctr',
    viewConfig: {
        deferEmptyText: false,
        preserveScrollOnReload: true,
        enableTextSelection: true,
        emptyText: 'No Details Available'
    },
    margin: 3,
    tbar: [{
        xtype: 'tbspacer',
        width: 5
     }, {
        xtype: 'combo',
        emptyText: 'SECTION',
        flex: 1,
        //labelWidth: 80,
        width: 150,
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        name: 'section_id',
        queryMode: 'local',
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
                        extraParams: {
                            model_name: 'Section'
                        }
                    }
                },
                isLoad: true
            },
            change: function (cmbo, newVal) {
                var grid = cmbo.up('grid');
                grid.getStore().load();
            }
        },
        triggers: {
            clear: {
                type: 'clear',
                hideWhenEmpty: true,
                hideWhenMouseOut: false,
                clearOnEscape: true
            }
        }
    }, {
        xtype: 'combo',
        emptyText: 'MODULE',
        flex: 1,
        //labelWidth: 80,
        width: 150,
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        name: 'module_id',
        queryMode: 'local',
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
                        extraParams: {
                            model_name: 'Module'
                        }
                    }
                },
                isLoad: true
            },
            change: function (cmbo, newVal) {
                var grid = cmbo.up('grid'),
                    sub_module = grid.down('combo[name=sub_module_id]'),
                    sub_module_str = sub_module.getStore();
                sub_module_str.removeAll();
                sub_module_str.load({params: {module_id: newVal}});
                grid.getStore().load();
            }
        },
        triggers: {
            clear: {
                type: 'clear',
                hideWhenEmpty: true,
                hideWhenMouseOut: false,
                clearOnEscape: true
            }
        }
    }, {
        xtype: 'combo',
        emptyText: 'SUB MODULE',
        flex: 1,
        //labelWidth: 80,
        width: 150,
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        name: 'sub_module_id',
        queryMode: 'local',
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
                            model_name: 'SubModule'
                        }
                    }
                },
                isLoad: false
            },
            change: function (cmbo, newVal) {
                var grid = cmbo.up('grid'),
                    module_id = grid.down('combo[name=module_id]').getValue(),
                    section_id = grid.down('combo[name=section_id]').getValue(),
                    workflow_stage = grid.down('combo[name=workflow_stage_id]'),
                    workflow_stage_str = workflow_stage.getStore();
                workflow_stage_str.removeAll();
                workflow_stage_str.load({
                    params: {
                        module_id: module_id,
                        sub_module_id: newVal,
                        section_id: section_id
                    }
                });
                grid.getStore().load();
            }
        },
        triggers: {
            clear: {
                type: 'clear',
                hideWhenEmpty: true,
                hideWhenMouseOut: false,
                clearOnEscape: true
            }
        }
    }, {
        xtype: 'combo',
        emptyText: 'WORKFLOW STAGE',
        valueField: 'id',
        name: 'workflow_stage_id',
        displayField: 'name',
        queryMode: 'local',
        flex: 1,
        forceSelection: true,
        width: 150,
        fieldStyle: {
            'color': 'green',
            'font-weight': 'bold'
        },
        listeners: {
            beforerender: {
                fn: 'setWorkflowCombosStore',
                config: {
                    pageSize: 10000,
                    proxy: {
                        url: 'workflow/getProcessWorkflowStages'
                    }
                },
                isLoad: false
            },
            change: function (cmbo, newVal) {
                var grid = cmbo.up('grid');
                grid.getStore().load();
            }
        },
        triggers: {
            clear: {
                type: 'clear',
                flex: 1,
                hideWhenEmpty: true,
                hideWhenMouseOut: false,
                clearOnEscape: true
            }
        }
    },,{
        xtype: 'datefield',
        emptyText: 'Release From',
        name: 'received_from',
        format: 'Y-m-d' ,
        flex: 1,
        fieldStyle: {
            'color': 'green',
            'font-weight': 'bold'
        },
        listeners:{
          change: function (cmbo, newVal) {
              var grid = cmbo.up('grid');
              grid.getStore().load();
          }
  
        }
      },{
          xtype: 'datefield',
          emptyText: 'Released to',
          name: 'received_to',
          format: 'Y-m-d' ,
          flex: 1,
          fieldStyle: {
              'color': 'green',
              'font-weight': 'bold'
          },
          listeners:{
            change: function (cmbo, newVal) {
                var grid = cmbo.up('grid');
                grid.getStore().load();
            }
    
          }
        },{
        xtype: 'button',
        text: 'Filter',
        ui: 'soft-green',
        iconCls: 'x-fa fa-search',
        handler: function(btn) {
          var grid = btn.up('grid');
              grid.getStore().load();
        },
    },{
        xtype: 'button',
        text: 'Clear',
        ui: 'soft-red',
        iconCls: 'x-fa fa-close',
        handler: function(btn) {
          var grid = btn.up('grid'),
                gridStr = grid.getStore();
                grid.down('combo[name=section_id]').clearValue();
                grid.down('combo[name=module_id]').clearValue();
                grid.down('combo[name=sub_module_id]').clearValue();
                grid.down('combo[name=workflow_stage_id]').clearValue();
                gridStr.load();
        },
    }],
    bbar: [
        {
            xtype: 'pagingtoolbar',
            displayInfo: true,
            width: '60%',
            beforeLoad: function () {
                var store = this.getStore(),
                    grid = this.up('grid'),
                    section_id = grid.down('combo[name=section_id]').getValue(),
                    module_id = grid.down('combo[name=module_id]').getValue(),
                    sub_module_id = grid.down('combo[name=sub_module_id]').getValue(),
                    workflow_stage_id = grid.down('combo[name=workflow_stage_id]').getValue(),
                    received_from =grid.down('datefield[name=received_from]').getValue();
                    received_to =grid.down('datefield[name=received_to]').getValue();
                    store.getProxy().extraParams = {
                        section_id: section_id,
                        module_id: module_id,
                        sub_module_id: sub_module_id,
                        workflow_stage_id: workflow_stage_id,
                        received_from: received_from,
                        received_to: received_to
                    };
            }
        },
        '->','->',
        {
            xtype: 'exportbtn'
        }
    ],
    listeners: {
         beforerender: {
            fn: 'setConfigGridsStore',
            config: {
                pageSize: 500,
                storeId: 'usercompletedassperformancsummarygridstr',
                groupField: 'process_name',
                proxy: {
                    url: 'dashboard/getUserCompletedAssPerformancSummary'
                }
            },
            isLoad: true
        }
    },
  
    plugins: [{
        ptype: 'filterfield'
    },{
        ptype: 'gridexporter'
    }],
    features: [
        {
            ftype: 'groupingsummary',
            groupHeaderTpl: 'User: {[values.rows[0].data.process_name]},[{rows.length} {[values.rows.length > 1 ? "Items" : "Item"]}]',
            //groupHeaderTpl: 'Process: {[values.rows[0].data.process_name]},Stage: {[values.rows[0].data.stage_name]}, User: {[values.rows[0].data.user_name]},[{rows.length} {[values.rows.length > 1 ? "Items" : "Item"]}]',
            enableGroupingMenu: false,
            startCollapsed: false
        },{
            ftype: 'searching',
            minChars: 3,
            mode: 'local'
        }
    ],
    export_title: 'applicationassaignmentcounter',
    columns: [{
            xtype: 'gridcolumn',
            text: 'User',
            dataIndex: 'user_name',
            flex: 1,
            tdCls: 'wrap'
         },{
            xtype: 'gridcolumn',
            text: 'Process Name',
            dataIndex: 'process_name',
            flex: 1,
            tdCls: 'wrap'
        },
        {
            xtype: 'gridcolumn',
            text: 'Stage',
            dataIndex: 'stage_name',
            flex: 1,
            tdCls: 'wrap',
            summaryRenderer: function(value){
             return "<font color='green'>Total Applications<font>"; 

          }
        },
        {
            xtype: 'gridcolumn',
            text: 'Application Counter',
            dataIndex: 'application_counter',
            flex: 1,
            tdCls: 'wrap',
            summaryType: 'sum',
            summaryRenderer: function(value){
                 return "<font color='green'>"+value+"<font>";
              }
            
        },
        {
            text: 'Options',
            xtype: 'widgetcolumn',
            width: 200,
            widget: {
                width: 170,
                textAlign: 'left',
                xtype: 'button',
                ui: 'soft-green',
                text: 'View Applications',
                handler: 'viewCOmpletedAssignmentApplications'
            }
        }
    ]
});