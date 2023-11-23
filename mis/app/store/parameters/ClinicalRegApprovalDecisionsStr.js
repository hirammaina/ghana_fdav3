/**
 * Created by Kip on 1/26/2019.
 */
Ext.define('Admin.store.parameters.ClinicalRegApprovalDecisionsStr', {
    extend: 'Ext.data.Store',
    alias: 'store.clinicalregapprovaldecisionsstr',
    storeId: 'clinicalregapprovaldecisionsstr',
    requires: [
        'Admin.model.parameters.ParametersMdl'
    ],
    model: 'Admin.model.parameters.ParametersMdl',
    autoLoad: false,
    defaultRootId: 'root',
    enablePaging: true,
    proxy: {
        type: 'ajax',
        url: 'commonparam/getCommonParamFromModel',
        extraParams: {
            model_name: 'ClinicalApprovalDecision'
        },
        headers: {
            'Authorization': 'Bearer ' + access_token
        },
        reader: {
            type: 'json',
            idProperty: 'id',
            rootProperty: 'results',
            messageProperty: 'message'
        }
    }
});
