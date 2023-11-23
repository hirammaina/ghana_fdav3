
Ext.define('Admin.view.applicationsdatacleanup_requests.viewmodels.PremiseFrontOfficeVm', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.premisefrontofficevm',
    itemId: 'premisefrontofficevm',
    stores: {
       //
    },
    data: {
        atBeginning: true,
        atEnd: false,
        isReadOnly: false,
        showTab: true,
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