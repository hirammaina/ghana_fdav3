
/**
 * Created by Softclans
 */
Ext.define('Admin.view.premiseregistration.views.forms.PremiseTcRecommendationFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'premiseTcRecommendationFrm',
    controller: 'premiseregistrationvctr',
    frame: true,
    bodyPadding: 5,
    layout: 'form',
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'application_code'
        },
        {
            xtype: 'hiddenfield',
            name: 'table_name',
            value: 'tc_recommendations'
        },
        {
            xtype: 'combo',
            queryMode: 'local',
            forceSelection: true,
            valueField: 'id',
            displayField: 'name',
            fieldLabel: 'Recommendation',
            name: 'decision_id',
            allowBlank: false,
            listeners: {
                beforerender: {
                    fn: 'setParamCombosStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'commonparam/getCommonParamFromTable',
                            extraParams: {
                                table_name: 'par_tcinspectionmeeting_decisions'
                            }
                        }
                    },
                    isLoad: true
                },
                
            }
        },
        {
            xtype: 'textarea',
            fieldLabel: 'Comments',
            name: 'comments',
            allowBlank: true
        },
		{
            xtype: 'textarea',
            fieldLabel: 'Certificate/License Expiry Statement',
            name: 'certificate_expiry_statement',
            allowBlank: false,
        },{
            xtype: 'datefield',
            fieldLabel: 'Certificate/License Expiry Date',
            //minValue: new Date(),
            
            name: 'certificate_expiry_date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            allowBlank: false,
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
		
    ],
    buttons: [
        {
            xtype: 'button',
            formBind: true,
            text: 'Save Details',
            iconCls: 'x-fa fa-save',
            ui: 'soft-purple',
            name: 'tc_recom_button',
            handler: 'doCreatePremiseRegParamWin',
            action_url: 'clinicaltrial/saveClinicalTrialCommonData',
            table_name: 'tc_recommendations',
            storeID: 'premiseReviewTCMeetingStr'
        }
    ]
});