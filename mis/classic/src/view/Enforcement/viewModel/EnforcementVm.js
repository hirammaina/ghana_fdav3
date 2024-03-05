Ext.define('Admin.view.Enforcement.viewModel.EnforcementVm', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.enforcementvm',
    itemId: 'enforcementvm',
    data: {
        atBeginning: true,
        atEnd: false,
        isReadOnly: false,
        prechecking_querytitle:'',
        model: {},
        hideDeleteButton: true
    },
    formulas: {
        isReadOnlyField: function (get) {
              return get('isReadOnly');
        }
    }
});