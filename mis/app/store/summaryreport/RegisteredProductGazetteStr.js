/**
 * Created by Eng. Sadam on 16/03/2021.
 *
 * motivation, coding with stress -> https://www.youtube.com/watch?v=sZmtypVN0uc
 */
Ext.define("Admin.store.summaryreport.RegisteredProductGazetteStr", {
  extend: "Ext.data.Store",
  storeId: "registeredProductGazetteStr",
  alias: "store.registeredProductGazetteStr",
  pageSize: 50,
  autoLoad: false,
  remoteFilter: true,
  grouper: {
    groupFn: function (item) {
      return item.get("applicant_name");
    },
  },
  proxy: {
    type: "ajax",
    url: "summaryreport/getRegisteredProductGazette",
    headers: {
      Authorization: "Bearer " + access_token,
    },
    reader: {
      type: "json",
      idProperty: "id",
      rootProperty: "results",
      totalProperty: "total",
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
