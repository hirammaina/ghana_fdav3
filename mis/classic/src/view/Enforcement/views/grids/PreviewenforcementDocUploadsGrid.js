Ext.define('Admin.view.Enforcement.views.grids.PreviewenforcementDocUploadsGrid', {
    extend: 'Admin.view.commoninterfaces.grids.ApplicationPrevDocUploadsGrid',
    xtype: 'previewenforcementDocUploadsGrid',
    upload_tab: 'previewenforcementDocUploadsGrid',
    table_name: '',
    viewModel: {
        type: 'enforcementvm'
    }	
});