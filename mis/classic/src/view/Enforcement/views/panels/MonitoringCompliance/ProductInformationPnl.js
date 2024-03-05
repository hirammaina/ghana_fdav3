/**
 * Created by Softclans.
 */
 Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.ProductInformationPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'productInformationpnl',
    controller: 'enforcementvctr',
    listeners: {
        // beforetabchange: function (tabPnl, newTab) {
        //     var enforcement_id = tabPnl.down('hiddenfield[name=enforcement_id]').getValue();
           
        //     if (tabPnl.items.indexOf(newTab) > 0) {
        //         if (enforcement_id < 1) {
        //             toastr.warning('Save Applicant details first!!', 'Warning Response');
        //             return false;
        //         }
        //     }
        // }
    },
    tbar: [
        {
            xtype: 'hiddenfield',
            name: 'section_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'process_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'workflow_stage_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'module_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'sub_module_id'
        }
    ],
    items: [
        {
            title: 'PRODUCT DETAILS',
            xtype: 'suspectinforFrm'
        },
        {
            title: 'RESPOSNISIBLE PROFESSIONAL',
            xtype: 'responsibleproffesionalFrm'
        }
    ]
});