
Ext.define('Admin.view.frontoffice.premise.forms.PremisePfOtherDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'premisepfotherdetailsfrm',
    autoScroll: true,
    controller: 'spreadsheetpremisectr',
    bodyPadding: 8,
    defaults: {
        labelAlign: 'top',
        labelAlign: 'right',
        labelWidth: 108,
        margin: 5,
        xtype: 'textfield',
        width: '100%',
        margin: 5
    },
    viewModel: {
        type: 'premisefrontofficevm'
    },
    layout: {
        type: 'vbox'
    },
    layout: 'vbox',
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'is_temporal'
        },
        {
            xtype: 'hiddenfield',
            name: 'table_name',
            value: 'tra_premises_otherdetails'
        },
        {
            xtype: 'hiddenfield',
            name: 'premise_id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
                {
            xtype: 'combo',
            fieldLabel: 'Business Type',
            name: 'business_type_id',
            forceSelection: true,
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id',
            listeners: {
                beforerender: {
                    fn: 'setOrgConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                        url: 'commonparam/getCommonParamFromTable',
                        extraParams: {
                            table_name: 'par_business_types'
                        }
                       }
                    },
                    isLoad: true
                },
                change: 'func_LoadBusinessTypeDetailsCombo',
            }
        },
        {
            xtype: 'combo',
            fieldLabel: 'Business Type Details',
            name: 'business_type_detail_id',
            store: 'businesstypedetailsstr',
            forceSelection: true,
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id',
            listeners: {
                beforerender: {
                    fn: 'setOrgConfigCombosStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                        url: 'commonparam/getCommonParamFromTable',
                        extraParams: {
                            table_name: 'par_business_type_details' 
                        }
                       }
                    },
                    isLoad: false
                }
            }
        }
         
       ],
    buttons: [{
        xtype: 'button',
        text: 'Save Details',
        ui: 'soft-purple',
        iconCls: 'x-fa fa-save',
        formBind: true,
        table_name: 'tra_premises_otherdetails',
        storeID: 'pfpremiseotherdetailsstr',
        action_url: 'premiseregistration/savePremiseOtherDetails',
        handler: 'doCreatePfConfigParamWin'
    }]
});





