/**
 * Created by Kip on 9/27/2018.
 */
Ext.define('Admin.view.variation_configurations.views.panels.VariationsRequestConfiguration', {
    extend: 'Ext.panel.Panel',
    xtype: 'variationsrequestconfiguration',
    title: 'Variation Summary Configurations & Guidelines',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'variationsrequestconfigurationgrid'
    }]
});