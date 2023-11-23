Ext.define('Admin.view.frontoffice.premise.forms.PremisesPfPersonnelDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'premisespfpersonneldetailsfrm',
    controller: 'spreadsheetpremisectr',
    layout: {
        type: 'column'
    },
    viewModel: {
        type: 'premisefrontofficevm'
    },
    bodyPadding: 5,
    defaults: {
        margin: 4,
        allowBlank: false,
        columnWidth: 0.33,
        labelAlign: 'top'
    },
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'isReadOnly'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {   
            xtype: 'hiddenfield',
            name: 'personnel_type',
            value: 'superintendent'
        },
        {
            xtype: 'hiddenfield',
            name: 'premise_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'personnel_id'
        },{
            xtype: 'hiddenfield',
            name: 'module_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'manufacturing_site_id'
        },   
        {
            xtype: 'hiddenfield',
            name: 'table_name',
            value: 'cln_premises_personnel'
        },
        {
            xtype: 'hiddenfield',
            name: 'trader_id'
        },
        {
            xtype: 'fieldcontainer',
            layout: 'column',
            defaults: {
                labelAlign: 'top'
            },
            fieldLabel: 'Name',
            items: [
                {
                    xtype: 'textfield',
                    name: 'name',
                    columnWidth: 0.9,
                    readOnly: true,
                    allowBlank: false
                },
                {
                    xtype: 'button',
                    iconCls: 'x-fa fa-link',
                    columnWidth: 0.1,
                    tooltip: 'Link Personnel',
                    name:'link_personnel',
                    handler:'showPfPersonnelSelectionGrid',
                    childXtype: 'pftraderpersonnelgrid',
                    winWidth: '70%'
                }
            ]
        },
        {
            xtype: 'textfield',
            name: 'postal_address',
            fieldLabel: 'Postal Address',
            readOnly: true
        },
        {
            xtype: 'textfield',
            name: 'telephone_no',
            fieldLabel: 'Telephone No',
            readOnly: true
        },
         {
            xtype: 'textfield',
            name: 'email_address',
            fieldLabel: 'Email Address',
            readOnly: true
          },
            
            {
                xtype: 'combo',
                fieldLabel: 'Position',
                name: 'position_id',
                valueField: 'id',
                displayField: 'name',
                queryMode: 'local',
                forceSelection: true,
                listeners: {
                beforerender: {
                    fn: 'setParamCombosStore',
                    config: {
                        pageSize: 1000,
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_personnel_positions'
                            }
                        }
                    },
                    isLoad: true
                }
            }
           },
            {
                xtype: 'textfield',
                name: 'registration_no',
                fieldLabel: 'Registration No'
            },

             {
                xtype: 'combo',
                name: 'study_field_id',
                fieldLabel: 'Field of Study',
                forceSelection: true,
                queryMode: 'local',
                allowBlank: true,
                valueField: 'id',
                displayField: 'name',
                 listeners: {
                beforerender: {
                    fn: 'setParamCombosStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_personnel_studyfield'
                            }
                        }
                    },
                    isLoad: true
                }
            }
           },

     
            {
                xtype: 'combo',
                name: 'qualification_id',
                fieldLabel: 'Qualification',
                forceSelection: true,
                queryMode: 'local',
                allowBlank: true,
                valueField: 'id',
                displayField: 'name',
                listeners: {
                 beforerender: {
                    fn: 'setParamCombosStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_personnel_qualifications'
                            }
                        }
                    },
                    isLoad: true
                }
            }
           },
            {
                xtype: 'textfield',
                allowBlank: true,
                name: 'institution',
                fieldLabel: 'Institution'
            },
            {
                xtype: 'datefield',
                name: 'start_date',
                fieldLabel: 'Start Date',
                submitFormat: 'Y-m-d',
                format: 'd/m/Y',
                allowBlank: true,
                altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
            },
            {
                xtype: 'datefield',
                name: 'end_date',
                fieldLabel: 'End Date',
                submitFormat: 'Y-m-d',
                format: 'd/m/Y',
                allowBlank: true,
                altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
            }
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            name: 'save_btn',
            storeID: 'pfpremisepersonneldetailsstr',
            action_url: 'premiseregistration/savePremisePersonnelLinkageDetails',
            handler: 'savePfPremisePermitPersonnelDetails'
        }
    ]
});
    



