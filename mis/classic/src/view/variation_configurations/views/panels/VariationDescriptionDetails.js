/**
 * Created by Kip on 9/27/2018.
 */
Ext.define('Admin.view.variation_configurations.views.panels.VariationDescriptionDetails', {
    extend: 'Ext.panel.Panel',
    xtype: 'variationdescriptiondetails',
    title: 'Variations Descriptions',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'variationdescriptiondetailsgrid'
        }
    ]
});
