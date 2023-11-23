/**
 * Created by Kip on 6/27/2019.
 */
Ext.define('Admin.view.usermanagement.views.dashboards.DigitalSignatureManagement', {
    extend: 'Ext.panel.Panel',
    xtype: 'digitalsignaturemanagement',
    title: 'Digital Signature SetUp',
    controller: 'usermanagementvctr',
    viewModel: 'usermanagementvm',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'digitalsignaturemanagementgrid'
        }
    ]
});

