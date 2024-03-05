Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.ResponsibleProffesionalFrm.js', {
    extend: 'Ext.form.Panel',
    xtype: 'responsibleproffesionalFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
       
    },
     height: Ext.Element.getViewportHeight() - 118,
     //bodyPadding: 8,
   
    // layout: {
    //     type: 'table',
    //     columns: 3
    // },
    // layout: 'column',
    // autoScroll: true,
 
    items: [

        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'hiddenfield',
            name: 'enforcement_id',
        },
        {
            xtype:'textfield',
            name:'current_source',
            fieldLabel:'Full Name',
        },
        {
            xtype:'textfield',
            name:'id_no',
            fieldLabel:'Omang Number',
        }, 
        {
            xtype:'numberfield',
            name:'proffesion',
            fieldLabel:'Health Professions',
        },     
    ],
});