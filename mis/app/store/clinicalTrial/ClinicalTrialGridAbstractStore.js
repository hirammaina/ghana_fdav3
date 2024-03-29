/**
 * Created by Kip on 1/15/2019.
 */
Ext.define("Admin.store.clinicalTrial.ClinicalTrialGridAbstractStore", {
  extend: "Ext.data.Store",
  storeId: "clinicaltrialgridabstractstore",
  alias: "store.clinicaltrialgridabstractstore",
  requires: ["Admin.model.clinicalTrial.ClinicalTrialMdl"],
  model: "Admin.model.clinicalTrial.ClinicalTrialMdl",
  autoLoad: false,
  proxy: {
    type: "ajax",
    url: "clinicaltrial/getClinicalTrialParamFromModel",
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
