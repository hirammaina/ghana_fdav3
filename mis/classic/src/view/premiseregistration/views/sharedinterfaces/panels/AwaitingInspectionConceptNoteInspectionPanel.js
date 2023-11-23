/**
 * Created by Kip on 11/12/2018.
 */
Ext.define('Admin.view.premiseregistration.views.sharedinterfaces.panels.AwaitingInspectionConceptNoteInspectionPanel', {
    extend: 'Ext.panel.Panel',
    xtype: 'awaitinginspectionconceptnoteinspectionpanel',
    layout: 'border',
    defaults: {
        split: true,
    },
    items: [
        {
            title: 'Inspection Details',
            region: 'north',
            height: 250,
            collapsible: true,
            xtype: 'inspectionbasicdetailsfrm'
        },
        {
            title: 'Applications',
            region: 'center',
            xtype: 'tabpanel',
            items:[{
                xtype: 'awaitingconceptnoteinspectiongrid',
                title: 'Premises Applications'
            },{
                title: 'Approved Concept Note(Upload)',
                hidden: true,
                xtype: 'unstructureddocumentuploadsgrid'
            }]
        },
        {
            title: 'Inspectors',
            xtype: 'inspectioninspectorsgrid',
            region: 'west',
            width: 400,
            collapsible: true,
            titleCollapse: true
        }
    ]
});