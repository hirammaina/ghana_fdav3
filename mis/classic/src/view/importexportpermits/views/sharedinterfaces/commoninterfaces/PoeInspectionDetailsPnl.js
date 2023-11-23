
/**
 * Created by Kip on 11/12/2018.
 */
Ext.define('Admin.view.importexportpermits.views.commoninterfaces.PoeInspectionDetailsPnl', {
    extend: 'Ext.tab.Panel',
    xtype: 'poeinspectiondetailspnl',
    
    layout: {//
        type: 'fit'
    },
    defaults:{
        margin: 3
    },viewModel: {
        type: 'importexportpermitsvm'
    },
    items: [{
        xtype: 'poeinspectionpnlfrm',
        autoScroll: true,
        collapsible: true,
        height: 250,
        region: 'north',
        title: 'Import/Export Inspection Details',
        buttons:[{
            text:'Save Inspection Details',
            iconCls:'x-fa fa-save',
            ui:'soft-purple',
            action_url:'savePOEInspectionPermitDetails',
            handler:'funcSavePOEInspectionPermitDetails'
        }]
    },  {
        xtype: 'previousinspectionsgrid',
        autoScroll:true,
        region: 'south',
        title: 'Previous POE Inspections',
        
    },{
        xtype: 'hiddenfield',
        name: '_token',
        value: token
    }]
});


