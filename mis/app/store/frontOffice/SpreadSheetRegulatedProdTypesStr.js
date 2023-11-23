Ext.define("Admin.store.frontOffice.SpreadSheetRegulatedProdTypesStr", {
  extend: "Ext.data.Store",
  alias: "store.spreadsheetregulatedprodtypesstr",
  storeId: "spreadsheetregulatedprodtypesstr",
  autoLoad: true,
  defaultRootId: "root",
  enablePaging: true,

  proxy: {
    type: "ajax",
    url: "commonparam/getCommonParamFromTable?table_name=par_regulated_productstypes",
    headers: {
      Authorization: "Bearer " + access_token,
    },
    reader: {
      type: "json",
      idProperty: "id",
      rootProperty: "results",
      messageProperty: "message",
    },
  },
  listeners: {
    load: function (store, operation, eOpts) {
      var all = { name: "All", id: " " };
      store.insert(0, all);
    },
  },
});
