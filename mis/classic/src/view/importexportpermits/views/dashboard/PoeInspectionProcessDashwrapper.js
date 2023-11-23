/**
 * Created by Softclans
 * User Robinson Odhiambo
 * on 9/23/2018.
 */
Ext.define('Admin.view.importexportpermits.views.dashboard.PoeInspectionProcessDashwrapper', {
    extend: 'Ext.Container',
    xtype: 'poeinspectionprocessdashwrapper',
	itemId:'poeinspectionprocessdashwrapper',
    layout: 'fit',
    items: [
        {
            xtype: 'poeinspectionprocessdash'
        }
    ]
});