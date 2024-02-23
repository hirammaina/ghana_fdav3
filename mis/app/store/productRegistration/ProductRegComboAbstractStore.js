/**
 * Created by Kip on 9/22/2018.
 */
Ext.define("Admin.store.productRegistration.ProductRegComboAbstractStore", {
  extend: "Ext.data.Store",
  storeId: "productregcomboabstractstore",
  alias: "store.productregcomboabstractstore",
  requires: ["Admin.model.productRegistration.ProductRegMdl"],
  model: "Admin.model.productRegistration.ProductRegMdl",
  autoLoad: false,
  proxy: {
    type: "ajax",
    url: "productregistration/getProductRegParamFromModel",
    headers: {
      Authorization: "Bearer " + access_token,
    },
    reader: {
      type: "json",
      idProperty: "id",
      rootProperty: "results",
      messageProperty: "msg",
    },
  },
  // listeners: {
  //     load: function (store, records, success, operation) {
  //         var reader = store.getProxy().getReader(),
  //             response = operation.getResponse(),
  //             successID = reader.getResponseData(response).success,
  //             message = reader.getResponseData(response).message;
  //         if (!success || (successID == false || successID === false)) {
  //             toastr.warning(message, 'Warning Response');
  //         }
  //     }
  // }
  listeners: {
    load: function (store, records, success, operation) {
      var reader = store.getProxy().getReader();
      if (operation && operation.getResponse) {
        var response = operation.getResponse();
        if (response) {
          var successID = reader.getResponseData(response).success;
          var message = reader.getResponseData(response).message;
          if (!success || successID == false || successID === false) {
            toastr.warning(message, "Warning Response");
          }
        } else {
          console.error("Response object is null");
        }
      } else {
        console.error("Invalid operation object");
      }
    },
  },
});
