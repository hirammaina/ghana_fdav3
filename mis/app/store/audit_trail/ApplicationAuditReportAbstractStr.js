Ext.define("Admin.store.audit_trail.ApplicationAuditReportAbstractStr", {
  extend: "Ext.data.Store",
  storeId: "applicationauditreportabstractStr",
  alias: "store.applicationauditreportabstractStr",
  autoLoad: false,
  enablePaging: true,

  proxy: {
    type: "ajax",
    headers: {
      Authorization: "Bearer " + access_token,
    },
    reader: {
      type: "json",
      rootProperty: "results",
      totalProperty: "total",
      idProperty: "id",
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
