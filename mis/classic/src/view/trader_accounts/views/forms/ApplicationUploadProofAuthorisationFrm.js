Ext.define('Admin.view.trader_accounts.views.forms.ApplicationUploadProofAuthorisationFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'applicationuploadproofauthorisationfrm',
    controller: 'traderaccountsvctr',
    autoScroll: true,
    layout: 'form',
    frame: true,
    bodyPadding: 8,
    defaults: {
        labelAlign: 'top',
        allowBlank: false
    },
    items: [{
        xtype: 'hiddenfield',
        margin: '0 20 20 0',
        name: 'table_name',
        value: 'tra_applicationuploadproof_authorisation',
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        margin: '0 20 20 0',
        name: '_token',
        value: token,
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        fieldLabel: 'id',
        margin: '0 20 20 0',
        name: 'id',
        allowBlank: true
    }, {
        xtype: 'hiddenfield',
        fieldLabel: 'applicant_id',
        margin: '0 20 20 0',
        name: 'applicant_id',
        allowBlank: true
    },{
        xtype: 'combo',
        fieldLabel: 'Customer Authorisation Type',
        margin: '0 20 20 0',
        name: 'payuploadproofauth_type_id',
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        queryMode: 'local',
        listeners: {
            beforerender: {
                fn: 'setConfigCombosStore',
                config: {
                    pageSize: 1000,
                    proxy: {
                        url: 'commonparam/getCommonParamFromTable',
                        extraParams: {
                            table_name: 'par_payuploadproofauth_types'
                        }
                    }
                },
                isLoad: true
            },
            change:function(cbo, newValue){
                     var frm = cbo.up('form'),
                     module_id = frm.down('combo[name=module_id]'),
                     tracking_no = frm.down('textfield[name=tracking_no]'),
                     authorised_from = frm.down('datefield[name=authorised_from]'),
                     authorised_to = frm.down('datefield[name=authorised_to]');
                    if(newValue == 3){
                        module_id.setVisible(true);
                        tracking_no.setVisible(true);

                        authorised_from.setVisible(false);
                        authorised_to.setVisible(false);
                    }else{
                        module_id.setVisible(false);
                        tracking_no.setVisible(false);
                        authorised_from.setVisible(true);
                        authorised_to.setVisible(true);
                    }

            }
        }
    },{
        xtype: 'textfield',
        fieldLabel:'Authorised Trader',
        allowBlank: false,
        readOnly: true,
        labelWidth: 108,
        name: 'authorised_trader'
    },{
        xtype: 'button',
        iconCls:'x-fa fa-search',
        text: 'Search Trader',
        childXtype: 'authsearchtraderdetailsgrid',
        winTitle:' Search Trader Details',
        winWidth: '70%',
        handler: 'funcSearchTraderDetails'
    },{
        xtype: 'combo',
        fieldLabel: 'Module',
        margin: '0 20 20 0',
        name: 'module_id',
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        allowBlank: true,
        hidden: true,
        queryMode: 'local',
        listeners: {
            beforerender: {
                fn: 'setConfigCombosStore',
                config: {
                    pageSize: 1000,
                    proxy: {
                        url: 'commonparam/getCommonParamFromTable',
                        extraParams: {
                            table_name: 'modules'
                        }
                    }
                },
                isLoad: true
            }
        }
    },{
        xtype: 'textfield',
        fieldLabel: 'Tracking/Reference No',
        margin: '0 20 20 0',
        name: 'tracking_no', allowBlank: true,
        hidden: true
    },
    {
        xtype: 'datefield',
        fieldLabel: 'Authorised from',
        margin: '0 20 20 0',
        name: 'authorised_from',
        allowBlank: true
    },{
        xtype: 'datefield',
        fieldLabel: 'Authorised To',
        margin: '0 20 20 0',
        name: 'authorised_to',
        allowBlank: true
    },{
        xtype: 'textfield',
        fieldLabel: 'Requested By',
        margin: '0 20 20 0',
        name: 'requested_by', allowBlank: true,
       
    },
    {
        xtype: 'textarea',
        fieldLabel: 'Reason for Authorisation',
        margin: '0 20 20 0',
        name: 'reason_for_authorisation',
        allowBlank: true
    },{
        xtype: 'combo',
        fieldLabel: 'Status',
        margin: '0 20 20 0',
        name: 'authorisation_status_id',
        valueField: 'id',
        displayField: 'name',
        forceSelection: true,
        queryMode: 'local',
        listeners: {
            beforerender: {
                fn: 'setConfigCombosStore',
                config: {
                    pageSize: 1000,
                    proxy: {
                        url: 'commonparam/getCommonParamFromTable',
                        extraParams: {
                            table_name: 'par_authorisation_statuses'
                        }
                    }
                },
                isLoad: true
            }
        }
    }],
    dockedItems:[
        {
            xtype: 'toolbar',
            ui: 'footer',
            dock: 'bottom',
            items:[
                '->',{
                    text: 'Save Details',
                    iconCls: 'x-fa fa-save',
                    action: 'save',
                    table_name: 'tra_applicationuploadproof_authorisation',
                    storeID: 'applicationuploadproofauthorisationgridstr',
                    requeststoreID: 'applicationuploadproofauthorisationgridstr',
                    formBind: true,
                    ui: 'soft-purple',
                    action_url: 'configurations/saveProofUpAuthorisationConfigCommonData',
                    handler: 'doCreateapplicationuploadproofauthorisationgrid'
                }
            ]
        }
    ]
});