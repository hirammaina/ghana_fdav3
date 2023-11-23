
Ext.define('Admin.view.frontoffice.premise.forms.PremiseFrontOfficeApprovalDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'premisefrontofficeapprovaldetailsfrm',
    itemId:'spreadsheetpremisectr',
    scrollable:true,
    items:[
        {
            xtype: 'fieldset',
            title: 'Approval Details',
            style: 'background:white',
            collapsible: false,
            //checkboxToggle:true,
            layout: {
                type: 'column'
            },
            bodyPadding: 5,
            defaults: {
                columnWidth: 0.33,
                margin: 5,
                labelAlign: 'top'
            },
            items: [
                 {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
         {
            xtype: 'datefield',
            format: 'Y-m-d H:i:s',
            altFormats: 'Y-m-d H:i:s|Y-m-d',
            name: 'approval_date',
            fieldLabel: 'Approval Date',
            allowBlank: true,
            readOnly:true,
            maxValue: new Date() 
        },
        {
            xtype: 'datefield',
            format: 'Y-m-d H:i:s',
            altFormats: 'Y-m-d H:i:s|Y-m-d',
            name: 'expiry_date',
            fieldLabel: 'Expiry Date',
            allowBlank: true,
           readOnly:true
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Approved By',
            readOnly: true,
            name: 'approved_by_name'
        },
        {
            xtype: 'datefield',
            format: 'Y-m-d H:i:s',
            altFormats: 'Y-m-d H:i:s|Y-m-d',
            name: 'certificate_issue_date',
            fieldLabel: 'Certificate Issue Date Date',
            hidden:true,
            readOnly:true,
            maxValue: new Date() 
        },  
        {
            xtype: 'textarea',
            fieldLabel: 'Approval Comment',
            name: 'comment',
            allowBlank: true,
            readOnly: true
         },
        {
            xtype: 'textfield',
            fieldLabel: 'Permit No',
            name: 'permit_no',
            readOnly:true
         }

        ]
        }
    ]
});