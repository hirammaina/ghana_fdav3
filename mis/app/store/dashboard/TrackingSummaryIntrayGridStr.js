/**
 * Created by Kip on 10/16/2018.
 */
Ext.define("Admin.store.dashboard.TrackingSummaryIntrayGridStr", {
  extend: "Ext.data.Store",
  storeId: "trackingsummaryintraygridstr",
  alias: "store.trackingsummaryintraygridstr",
  requires: ["Admin.model.dashboard.DashboardMdl"],
  pageSize: 1000000,
  model: "Admin.model.dashboard.DashboardMdl",
  autoLoad: false,
  remoteFilter: true,
  grouper: {
    groupFn: function (item) {
      return item.get("sub_module_id");
    },
  },
  proxy: {
    type: "ajax",
    url: "dashboard/getApplicationTrackingSummaryIntrayItems",
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
