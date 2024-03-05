Ext.define('Admin.view.Enforcement.views.panels.MonitoringCompliance.ProductInformationParticulars', {
    extend: 'Ext.panel.Panel',
    viewModel:'enforcementvm',
    xtype: 'productInformationParticularsPanel',
	layout:'fit',
   
	product_id:0,
    items: [
         {
            xtype:'productInformationFrm',
			height:'75%'
        }
    ]
});