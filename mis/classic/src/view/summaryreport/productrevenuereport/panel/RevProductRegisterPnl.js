Ext.define('Admin.view.summaryreport.productrevenuereport.panel.RevProductRegisterPnl', {
   extend: 'Ext.panel.Panel',
    xtype: 'revproductregisterpnl',
    margin: 2,
    layout: 'border',
    controller: 'registrationreportviewctr',
    defaults: {
        bodyPadding: 1,
        scrollable: true,
    },
    items: [
         {
            xtype: 'hiddenfield',
            name: 'module_id',
            value: 1
        }, 
       {
            xtype: 'revproductregisterfiltersfrm',
            region: 'north',
            title: 'Filters',
            collapsible:true,
            collapsed: false
         },
      {
            xtype: 'revproductregistergrid',
            region: 'center'
        }],
  bbar: [{
        xtype: 'toolbar',
        width: '100%',
        ui: 'footer',
        items: [
         
        '->',
         {
            xtype:'button',
            ui: 'soft-green',
            text: 'Export Register',
            iconCls: 'x-fa fa-cloud-upload', 
            handler: 'exportProductRevenueRegister'   
        }
    ]
     }
    ],

 });