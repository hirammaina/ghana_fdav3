Ext.define('Admin.view.Enforcement.views.forms.investigation.AddSeizureProductsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'addSeizureProductsFrm',
    itemId: 'addSeizureProductsFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'form'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 5,
        labelAlign: 'top',
        allowBlank: false,
       
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
            name: 'application_id',
        },
        {
            xtype: 'hiddenfield',
            name: 'application_code',
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Brand Name(Product Name)',
            identity:'brand_name',
            name: 'brand_name',
            valueField: 'id',
            displayField: 'brand_name',
            forceSelection: false,
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            url:'enforcement/getRegisteredProduct'
                        }
                    },
                    isLoad: true
                },
             change: function(combo, value){
                var form = combo.up('form'),
                    store = form.down('combo[name=common_name_id]').getStore(),
                    filters = JSON.stringify({'id': value});

                    store.removeAll();
                    store.load({params: {filters: filters}});

                } 
            } 
        },
        {   
			xtype:'combo', anyMatch: true,
			name:'common_name_id',
			fieldLabel:'Common Name',
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                   fn: 'setCompStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'configurations/getConfigParamFromTable',
                            extraParams: {
                                table_name: 'par_common_names'
                            }
                        }
                    },
                    isLoad: true
                },
                change: function(combo, newVal, oldVal, eopts){
                  
                }
            }
		},{
			allowBlank:false,
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Product Type',
            name: 'section_id',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                   fn: 'setCompStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'configurations/getConfigParamFromTable',
                            extraParams: {
                                table_name: 'par_sections'
                            }
                        }
                    },
                    isLoad: true
                },
                change: function(combo, newVal, oldVal, eopts){
                    var form = combo.up('form'),
                        store = form.down('combo[name=prodclass_category_id]').getStore(),
                        filters = JSON.stringify({'section_id': newVal});
                    store.removeAll();
                    store.load({params: {filters: filters}});
                }
            }
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Product category',
            name: 'prodclass_category_id',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            url: 'configurations/getConfigParamFromTable',
                            extraParams: {
                                table_name: 'par_prodclass_categories'
                            }
                        }
                    },
                    isLoad: true
                }
            }
        }, 
        {   
			xtype:'textfield',
			name:'batch_number',
			fieldLabel:'Batch Number',
		},
        {
            xtype: 'datefield',
            fieldLabel: 'Expiry Date',
            name: 'expiry_date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },  
        {   
			xtype:'textfield',
			name:'quantity',
			fieldLabel:'Quantity',
		},
       
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveSeizureProductDetails',
            table_name: 'tra_reported_products',
            storeID: 'newProductseizureGridStr',
            action: 'save_caseCharges_details'
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
});