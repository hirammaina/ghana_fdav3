Ext.define("Admin.view.parameters.viewmodels.LocationVm", {
  extend: "Ext.app.ViewModel",
  alias: "viewmodel.locationvm",

  stores: {},
  data: {
    atBeginning: true,
    atEnd: false,
    isReadOnly: false,
    prechecking_querytitle: "",
  },
  formulas: {
    isReadOnlyField: function (get) {
      return get("isReadOnly");
    },
  },
});
