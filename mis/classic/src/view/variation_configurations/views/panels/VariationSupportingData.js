/**
 * Created by Kip on 9/27/2018.
 */
Ext.define('Admin.view.variation_configurations.views.panels.VariationSupportingData', {
    extend: 'Ext.panel.Panel',
    xtype: 'variationsupportingdata',
    title: 'Variation Categories',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'variationsupportingdatagrid'
    }]
});