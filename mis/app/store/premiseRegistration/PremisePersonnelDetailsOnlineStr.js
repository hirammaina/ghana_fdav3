/**
 * Created by Kip on 11/21/2018.
 */
Ext.define("Admin.store.premiseRegistration.PremisePersonnelDetailsOnlineStr", {
  extend: "Ext.data.Store",
  storeId: "premisepersonneldetailsonlinestr",
  alias: "store.premisepersonneldetailsonlinestr",
  requires: ["Admin.model.premiseRegistration.PremiseRegMdl"],
  model: "Admin.model.premiseRegistration.PremiseRegMdl",
  autoLoad: false,
  proxy: {
    type: "ajax",
    url: "premiseregistration/getOnlineAppPremisePersonnelDetails",
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
