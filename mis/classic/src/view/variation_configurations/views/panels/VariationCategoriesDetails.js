/**
 * Created by Kip on 9/27/2018.
 */
Ext.define('Admin.view.variation_configurations.views.panels.VariationCategoriesDetails', {
    extend: 'Ext.panel.Panel',
    xtype: 'variationcategoriesdetails',
    title: 'Variation Categories',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'variationcategoriesdetailsgrid'
    }]
});
