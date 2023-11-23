/**
 * Created by Kip on 9/27/2018.
 */
Ext.define('Admin.view.variation_configurations.views.panels.VariationReportingTypes', {
    extend: 'Ext.panel.Panel',
    xtype: 'variationreportingtypes',
    title: 'Type of Variations',
    userCls: 'big-100 small-100',
    controller: 'variationconfigurationsvctr',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'variationreportingtypesgrid'
        }
    ]
});
