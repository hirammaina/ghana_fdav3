Ext.define('Admin.view.dashboard.ProdUserPerformanceTasksAssignment', {
    extend: 'Ext.panel.Panel',
    xtype: 'produserperformancetasksassignment',
    margin: 2,
    itemId:'produserperformancetasksassignment',
    reference:'produserperformancetasksassignment',
    //id:'dashboard',
    requires: [
        'Ext.ux.layout.ResponsiveColumn'
    ],
    controller: 'userperformancereportsvctr',
    viewModel: {
        type: 'dashboard'
    },
    module_id: 1,
    layout: 'fit',
    listeners: {
        hide: 'onHideView'
    }, 
    items: [{
            xtype:'tabpanel',
            layout: 'fit',
            listeners:{
                    beforerender:function(pnl){
                            var panel = pnl.up('panel'),
                                module_id = panel.module_id, 
                                userperformancetasksasssummarygrid = panel.down('userperformancetasksasssummarygrid'),
                                usercompletedperformancetasksassgrid = panel.down('usercompletedperformancetasksassgrid');

                                userperformancetasksasssummarygrid.down('combo[name=module_id]').setValue(module_id).setDisabled(true);

                                usercompletedperformancetasksassgrid.down('combo[name=module_id]').setValue(module_id).setDisabled(true);


                    }
            },
            items:[{
                xtype:'userperformancetasksasssummarygrid', 
                title: 'User Performance Summary Report'
            },{
                xtype: 'usercompletedperformancetasksassgrid',
                region: 'center',
                title: 'My Completed Assignment'
            }]
    }
    ]
});
