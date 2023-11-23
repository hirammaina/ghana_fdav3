/**
 * Created by Kip on 8/15/2018.
 */
Ext.define('Admin.view.dashboard.grids.ImpExpIntrayGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'impexpintraygrid',
    controller: 'dashboardvctr',
   
    viewConfig: {
        deferEmptyText: false,
        preserveScrollOnReload: true,
        enableTextSelection: true,
        emptyText: 'No Details Available',
        getRowClass: function (record, rowIndex, rowParams, store) {
            var is_receipting_stage =  record.get('is_receipting_stage'),
                application_status_id =  record.get('application_status_id');
          // if (is_receipting_stage == 1) {
                if(application_status_id == 10) {
                    return 'invalid-row';
                } else if(application_status_id == 11) {
                    return 'valid-row';
                }

          // }
        }
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
        width: 130,
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
        width: 130,
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
        width: 130,
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
        emptyText: 'Port of Entry',
        name: 'port_id',
        forceSelection: true,
        queryMode: 'local',
        valueField: 'id',
        displayField: 'name',
        listeners: {
            beforerender: {
                fn: 'setWorkflowCombosStore',
                config: {
                    pageSize: 10000,
                    proxy: {
                        url: 'configurations/getNonrefParameter',
                        extraParams: {
                            table_name: 'par_ports_information'
                        }
                    }
                },
                isLoad: true
            },
            change:function(cmbo){
                var grid = cmbo.up('grid');
                
                    grid.getStore().load();
            }
        }
    },{
      xtype: 'datefield',
      emptyText: 'Received From',
      name: 'received_from',
      format: 'Y-m-d' ,
      width: 130,
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
        emptyText: 'Received to',
        name: 'received_to',
        format: 'Y-m-d' ,
        width: 130,
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
        text: 'Clear',
        ui: 'soft-red',
        iconCls: 'x-fa fa-close',
        handler: function(btn) {
          var grid = btn.up('grid'),
                gridStr = grid.getStore();
                grid.down('combo[name=section_id]').clearValue();
                grid.down('combo[name=module_id]').clearValue();
                grid.down('combo[name=sub_module_id]').clearValue();
                grid.down('datefield[name=received_from]').clearValue();
                grid.down('datefield[name=received_to]').clearValue();
                gridStr.load();
        },
    }],
    bbar: [
        {
            xtype: 'pagingtoolbar',
            displayInfo: true,
            store: 'intraystr',
            width: '60%',
            beforeLoad: function () {
                var store = this.getStore(),
                    grid = this.up('grid'),
                    pnl = grid.up('panel'),
                    is_receipting_stage = pnl.is_receipting_stage,
                    section_id = grid.down('combo[name=section_id]').getValue(),
                    module_id = grid.down('combo[name=module_id]').getValue(),
                    sub_module_id = grid.down('combo[name=sub_module_id]').getValue(),
                    port_id = grid.down('combo[name=port_id]').getValue(),
                    is_importexp_stage= pnl.is_importexp_stage,
                    application_status_id=grid.down('combo[name=application_status_id]').getValue();
                    received_from =grid.down('datefield[name=received_from]').getValue();
                    received_to =grid.down('datefield[name=received_to]').getValue();

                    store.getProxy().extraParams = {
                        section_id: section_id,
                        module_id: module_id,
                        sub_module_id: sub_module_id,
                        port_id: port_id,
                        is_receipting_stage:is_receipting_stage,
                        is_importexp_stage:is_importexp_stage,
                        received_from: received_from,
                        received_to: received_to,
                        application_status_id:application_status_id
                    };
            }
        },
        '->',{
          xtype: 'checkbox',
          name:'enable_grouping',
          boxLabel:'Enable Grouping',
          listeners:{
                change:function(chk,value){
                        var grid = chk.up('grid');
                            grouping = grid.getView().findFeature('grouping');
                            if(value == 1){
                                grouping.enable();
                            }
                            else{
                                grouping.disable();
                            }
                }
          }
        },
        {
            xtype: 'button',
            text: 'Export Intray',
            type: 1,
            is_internaluser: 1,
            ui:'soft-green',
            iconCls: 'x-fa fa-print',
            handler: 'exportDashboard'
        }
    ],
    listeners: {
        afterrender: 'funcIntrayBeforerenderDetails',
        beforerender: {
            fn: 'setCommonGridsStore',
            config: {
                pageSize: 10000,
                storeId: 'impintraystr',
                remoteFilter: true,
                grouper: {
                    groupFn: function (item) {
                        if(item.get('is_receipting_stage') == 1){
                            return item.get('module_id') + item.get('workflow_stage_id')+ item.get('application_status_id');
                        }else{
                            return item.get('sub_module_id') + item.get('workflow_stage_id');
                        }
                    }
                },
                proxy: {
                    type: 'ajax',
                    url: 'dashboard/getInTrayItems',
                    headers: {
                        'Authorization':'Bearer '+access_token
                    },
                    reader: {
                        type: 'json',
                        idProperty: 'id',
                        rootProperty: 'results',
                        messageProperty: 'msg'
                    }
                },
            },
            isLoad: true
        },
        itemdblclick: 'onIntrayItemDblClick'
    },
    features: [
        {
            ftype: 'grouping',
            startCollapsed: false,
            groupHeaderTpl: '{[values.rows[0].data.workflow_stage]}, Sub-Process: {[values.rows[0].data.sub_module]}, [{rows.length} {[values.rows.length > 1 ? "Items" : "Item"]}]',
            hideGroupedHeader: false,
            enableGroupingMenu: false
        }
    ],
    plugins: [{
        ptype: 'filterfield'
    },{
        ptype: 'gridexporter'
    }],
    export_title: 'Intray',
    //store: 'intraystr',
    
    columns: [
        {
            xtype: 'gridcolumn',
            width: 80,
            renderer: function (val, meta, record) {
                var isRead = record.get('isRead');
                if (isRead == 1 || isRead === 1) {
                    meta.tdStyle = 'color:white;background-color:#2e8B57';
                    return 'Reviewed';
                } else {
                    meta.tdStyle = 'color:white;background-color:#900603';
                    return 'New Submission';
                }
            }
        },
        {
            xtype: 'gridcolumn',
            text: 'Delivery Timeline (Status)',
            dataIndex: 'deliverytimeline_reminder',
            width:100,
            tdCls: 'wrap',
            renderer: function (val, meta, record) {
                var servicedelivery_timeline = record.get('servicedelivery_timeline');
                var time_span = record.get('time_span');
                if(servicedelivery_timeline >0){
                    if(val < 1){
                        meta.tdStyle = 'color:white;background-color:#900603';
                        return ' Application OverDue for ' +val +'(days)';
                    }else if (val < 3) {
                        meta.tdStyle = 'color:white;background-color:#89cff0';
                        
                        return ' Application Due in ' +val +'(days)';
                    } else {
                        meta.tdStyle = 'color:white;background-color:#2e8B57';
                        return 'Within Delivery Timeline';
                    }

                }
                else{
                    meta.tdStyle = 'color:white;background-color:#2e8B57';
                    return 'Within Delivery Timeline Span ('+time_span+')';

                }
               
            }
        },
        {
            xtype: 'gridcolumn',
            text: 'Tracking No',
            dataIndex: 'tracking_no',
            width: 180,
            tdCls: 'wrap',
            filter: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'gridcolumn',
            text: 'Reference',
            dataIndex: 'reference_no',
            width: 180,
            tdCls: 'wrap',
            filter: {
                xtype: 'textfield'
            }
        }, {
            xtype: 'gridcolumn',
            text: 'Permit Category',
            dataIndex: 'permit_category',
            width: 180,
            tdCls: 'wrap'
        },  {
            xtype: 'gridcolumn',
            text: 'Product Category',
            dataIndex: 'product_category',
            width: 180,
            tdCls: 'wrap'
        }, {
            xtype: 'gridcolumn',
            text: 'Proforma Invoice',
            dataIndex: 'proforma_invoice',
            width: 180,
            tdCls: 'wrap'
        },{
            xtype: 'gridcolumn',
            text: 'Proposed Inspection Date',
            dataIndex: 'proposed_inspection_date',
            width: 180,
            tdCls: 'wrap'
        },{
            xtype: 'gridcolumn',
            text: 'Inspection Date',
            dataIndex: 'inspection_date',
            width: 180,
            tdCls: 'wrap'
        },{
            xtype: 'gridcolumn',
            text: 'Port of Entry',
            dataIndex: 'port_of_entry',
            width: 180,
            tdCls: 'wrap'
        }, {
            xtype: 'gridcolumn',
            text: 'Applicant',
            dataIndex: 'applicant_name',
            width: 200,
            tdCls: 'wrap',
            filter: {
                xtype: 'textfield'
            }
        },{
            xtype: 'gridcolumn',
            text: 'Process',
            dataIndex: 'process_name',
            width:100,
            tdCls: 'wrap-text',
            hidden: true
        }, {
            xtype: 'gridcolumn',
            text: 'Previous Stage',
            dataIndex: 'prev_stage',
            width: 100,
            hidden: true,
            tdCls: 'wrap'
        },{
            xtype: 'gridcolumn',
            text: 'Current Stage',
            dataIndex: 'workflow_stage',
            width: 100,
            tdCls: 'wrap'
        },
        {
            xtype: 'gridcolumn',
            text: 'From',
            dataIndex: 'from_user',
            filter: {
                xtype: 'textfield'
            },
            width: 150,
            tdCls: 'wrap'            
        },
        {
            xtype: 'gridcolumn',
            text: 'Admin Owner',
            dataIndex: 'to_user',
            width: 150,
            tdCls: 'wrap',
            filter: {
                xtype: 'textfield'
            }
        },{
            xtype: 'gridcolumn',
            text: 'Remarks/Comment',
            dataIndex: 'remarks',
            with: 150,
            tdCls: 'wrap'
        },
        {
            xtype: 'gridcolumn',
            text: 'Date Received',
            dataIndex: 'date_received',
            width: 100,
            tdCls: 'wrap-text',
            renderer: Ext.util.Format.dateRenderer('Y-m-d')
        },
        {
            xtype: 'gridcolumn',
            text: 'App Status',
            dataIndex: 'application_status',
            width: 100,
            tdCls: 'wrap',
            filter: {
                xtype: 'combo',
                name:'application_status_id',
                displayField: 'name',
                valueField:'id',queryMode:'local',
                listeners: {
                    beforerender: {
                        fn: 'setConfigCombosStore',
                        config: {
                            pageSize: 1000,
                            proxy: {
                                url: 'commonparam/getCommonParamFromTable',
                                extraParams: {
                                    table_name: 'par_system_statuses'
                                }
                            }
                        },
                        isLoad: true
                    },change: function(cmb, newValue, oldValue, eopts) {
                        var grid = cmb.up('grid');
                            grid.getStore().reload();
                     }
                   
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
        },{
            xtype: 'gridcolumn',
            text: 'Time Span',
            dataIndex: 'time_span',
            flex: 0.5,
            tdCls: 'wrap',
            renderer: function (val, meta, record) {
                var time_spanexpected = record.get('time_spanexpected'),
                time_span = record.get('time_span');
               
                    return time_span;
              
            }
        }
    ]
});