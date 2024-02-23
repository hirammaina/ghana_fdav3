/**
 * Created by Kip on 2/26/2019.
 */
Ext.define("Admin.store.surveillance.SurveillanceComboAbstractStore", {
  extend: "Ext.data.Store",
  storeId: "surveillancecomboabstractStore",
  alias: "store.surveillancecomboabstractStore",
  requires: ["Admin.model.surveillance.SurveillanceMdl"],
  model: "Admin.model.surveillance.SurveillanceMdl",
  autoLoad: false,
  proxy: {
    type: "ajax",
    url: "surveillance/getSurveillanceParamFromModel",
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
  //Job on 21.02.24
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
