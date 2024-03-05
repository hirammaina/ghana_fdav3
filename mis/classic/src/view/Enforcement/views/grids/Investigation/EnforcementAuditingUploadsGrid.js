Ext.define('Admin.view.Enforcement.views.grids.Investigation.EnforcementAuditingUploadsGrid', {
    extend: 'Admin.view.commoninterfaces.grids.ApplicationDocUploadsGrid',
    xtype:'enforcementAuditingUploadsGrid',
    upload_tab:'enforcementAuditingUploadsGrid',
    table_name:'tra_enforcement_applications',
    viewModel: {
        type: 'enforcementvm'
    }
});