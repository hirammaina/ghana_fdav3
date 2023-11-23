
/**
 * Created by Softclans
 */
Ext.define('Admin.view.productregistration.views.forms.common_forms.RetentionApplicationsDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'retentionApplicationsDetailsFrm',
    itemId:"productsDetailsFrm",
    layout: {
        type: 'column'
    },
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.99,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
       
    }, autoScroll: true,
    items: [{
            xtype: 'hiddenfield',
            value: 'tra_product_applications',
            name: 'table_name'
        },{
            xtype: 'combo',
            name: 'retention_year',
            allowBlank: false,
            store:'year_store',
            fieldLabel: 'Retention Year',
            queryMode: 'local',
            valueField: 'years',
            readOnly: true,
            displayField: 'years'
        },{
            xtype:'textarea',
            name: 'remarks',
            fieldLabel:'remarks'
        }
    ]
});