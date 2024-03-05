Ext.define('Admin.view.Enforcement.views.grids.EnforcementDocUploadsGrid', {
    extend: 'Admin.view.commoninterfaces.grids.ApplicationDocUploadsGrid',
    xtype:'enforcementDocUploadsGrid',
    upload_tab:'enforcementDocUploadsGrid',
    table_name:'tra_enforcement_applications',
    viewModel: {
        type: 'enforcementvm'
    }
});