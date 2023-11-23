Ext.define('Admin.view.configurations.views.panels.PersonnelInstitutionsPnl', {
    extend: 'Ext.panel.Panel',
    xtype: 'personnelInstitutions',
    title: 'Personnel Institutions',
    userCls: 'big-100 small-100',
    height: Ext.Element.getViewportHeight() - 118,
    layout:{
        type: 'fit'
    },
    items: [
        {
            xtype: 'personnelInstitutionsGrid'
        }
    ]
});
