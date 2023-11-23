
/**
 * Created by Kip on 11/12/2018.
 */
Ext.define('Admin.view.clinicaltrial.views.sharedinterfaces.panels.CtrGcpInspectionSchedulingPanel', {
    extend: 'Ext.panel.Panel',
    xtype: 'ctrgcpinspectionschedulingpanel',
    layout: 'border',
    defaults: {
        split: true,
    },
    items: [
        {
            title: 'GCP Inspection Schedules',
            region: 'north',
            height: 250,
            autoScroll: true,
            collapsible: true,
            xtype: 'ctrgcpinspectionscheduledetailsfrm'
        },
        {
            title: 'Applications',
            region: 'center',
            xtype: 'ctrgcpinspectionschedulegrid'
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