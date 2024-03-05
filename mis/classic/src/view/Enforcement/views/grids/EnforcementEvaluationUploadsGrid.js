Ext.define('Admin.view.Enforcement.views.grids.EnforcementEvaluationUploadsGrid', {
    extend: 'Admin.view.commoninterfaces.grids.ApplicationDocUploadsGrid',
    xtype:'enforcementEvaluationUploadsGrid',
    upload_tab:'enforcementEvaluationUploadsGrid',
    table_name:'tra_enforcement_applications',
    viewModel: {
        type: 'enforcementvm'
    }
});