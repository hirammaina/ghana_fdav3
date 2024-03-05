/**
 * Created by Softclans
 */
 Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.ProductInformationFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'productInformationFrm',
    itemId:'productInformationFrm',
    controller:'enforcementvctr',

    layout: {
        type: 'column'
    },
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
       
    }, 
    autoScroll: true,
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveMonitoringProductInformation',
            table_name: 'par_monitoring_product_information',
            storeID: 'productInformationGridStr',
            action: 'genericsaveDetails'
        },{
            xtype: 'button',
            text: 'Clear',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-close',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ],
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'enforcement_id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },  
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Is Product Registered',
            name: 'is_registered',
            store: 'confirmationstr',
            valueField: 'id',
            displayField: 'name',
            queryMode: 'local',
            forceSelection: true,
            listeners:{
                afterrender: function(){
                    var store=this.getStore();
                    store.removeAll();
                    store.load();
                },
			 change:function(combo,value)
			 {
				var	link_registered_product_btn=combo.up('form').down('button[name=link_registered_product]');

                    if(value==1){
						link_registered_product_btn.enable();
					}else{
						link_registered_product_btn.disable();		
					}
			 }
            }
        },
		{
            xtype: 'fieldcontainer',
			readOnly:true,
            layout: 'column',
            defaults: {
                labelAlign: 'top'
            },
            fieldLabel: 'Registered No',
            items: [
                {
                    xtype: 'textfield',
                    name: 'certificate_no',
                    readOnly: true,
                    columnWidth: 0.9
                },
                {
					bind:{disabled:'readOnly'},
                    xtype: 'button',
                    iconCls: 'x-fa fa-link',
                    columnWidth: 0.1,
                    tooltip: 'Link Registered Product',
                    name: 'link_registered_product',
				    handler: 'showRegistererdProductSelectionList',
					
                }
            ]
        },
		{   
			xtype:'textfield',
			name:'brand_name',
			fieldLabel:'Product Name',
		},
        {   
			xtype:'textfield',
			name:'common_name',
			fieldLabel:'Common Name',
		}, 
        {
            xtype: 'combo', anyMatch: true,
            queryMode: 'local',
            forceSelection: true,
            valueField: 'id',
            displayField: 'name',
            fieldLabel: 'Product Schedule',
            name: 'product_schedule',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                            extraParams: {
                                table_name: 'par_schedule_types'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Expiry Date',
            name: 'expiry_date',
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Batch Number',
            name: 'batch_number',
        },  
        {
            xtype: 'combo', anyMatch: true,
            queryMode: 'local',
            forceSelection: true,
            valueField: 'id',
            displayField: 'name',
            fieldLabel: 'Is the temperature Monitored?',
            name: 'temperature_monitoring',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 100,
                        proxy: {
                            url: 'configurations/getConfigParamFromTable',
                            extraParams: {
                                table_name: 'par_confirmations'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Storage condition',
            name: 'storage_condition',
        }, 
    ],
});