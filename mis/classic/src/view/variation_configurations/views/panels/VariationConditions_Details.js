/**
 * Created by Kip on 9/27/2018.
 */
Ext.define('Admin.view.variation_configurations.views.panels.VariationConditions_Details', {
    extend: 'Ext.panel.Panel',
    xtype: 'variationconditions_details',
    title: 'Variation Categories',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'variationconditions_detailsgrid'
    }]
});
