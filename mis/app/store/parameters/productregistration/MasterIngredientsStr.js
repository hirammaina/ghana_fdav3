/**
 * Created by Kip on 1/21/2019.
 */
Ext.define('Admin.store.parameters.productregistration.MasterIngredientsStr', {
        extend: 'Ext.data.Store',
        alias: 'store.masteringredientsstr',
        storeId: 'masteringredientsstr',
        requires: [
            'Admin.model.parameters.ParametersMdl'
        ],
        model: 'Admin.model.parameters.ParametersMdl',
        autoLoad: false,
        proxy: {
            type: 'ajax',
            url: 'commonparam/getCommonParamFromTable',
            headers: {
                'Authorization':'Bearer '+access_token
            },
            reader: {
                type: 'json',
                idProperty: 'id',
                rootProperty: 'results',
                messageProperty: 'message'
            }
        },
        listeners:{
            beforeload:function(store,op){
                op.setParams(Ext.apply(op.getParams()||{},{
                    table_name:'master_ingredients'
                }));
            }
        }
    });
