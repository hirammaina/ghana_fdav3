/**
 * Created by Kip on 10/8/2018.
 */
Ext.define("Admin.store.workflowmanagement.SubmissionNextStagesStr", {
  extend: "Ext.data.Store",
  storeId: "submissionnextstagesstr",
  alias: "store.submissionnextstagesstr",
  requires: ["Admin.model.workflowmanagement.WorkflowManagementMdl"],
  model: "Admin.model.workflowmanagement.WorkflowManagementMdl",
  autoLoad: false,
  proxy: {
    type: "ajax",
    url: "workflow/getSubmissionWorkflowStages",
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
