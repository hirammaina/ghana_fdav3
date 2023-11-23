/**
 * Created by Kip on 11/12/2018.
 */
Ext.define('Admin.view.premiseregistration.views.sharedinterfaces.panels.PremInspectionUploadDetailsPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'preminspectionuploaddetailspnl',
    itemId:'preminspectionuploaddetailstab',
    layout: {
        type: 'fir'
    },
    defaults: {
        split: true
    },
   items:[{
        xtype: 'premiseinspectionrecommfrm',
        itemId: 'premiseinspectionrecommfrm',
        title:'Premises Inspection Details'
   },{
       xtype: 'premregappdocuploadsgenericgrid',
       title: 'Inspection Documents Upload'
   }]
});