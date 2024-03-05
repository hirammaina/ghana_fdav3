Ext.define('Admin.view.Enforcement.views.forms.SuspectInforFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'suspectinforFrm',
    itemId: 'suspectinforFrm',
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
    items: [
        // {
        //     xtype: 'hiddenfield',
        //     value: 'tra_enforcement_applications',
        //     name: 'table_name'
        // },
        {
            xtype: 'hiddenfield',
            name: 'enforcement_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'annual_workplan_id'
        },
        // {
        //     xtype: 'hiddenfield',
        //     name: 'sub_module_id'
        // },
        // {
        //     xtype: 'hiddenfield',
        //     name: 'section'
        // },
        // {
        //     xtype: 'fieldcontainer',
        //     layout: 'column',
        //     defaults: {
        //         labelAlign: 'top'
        //     },
        //     fieldLabel: 'Annual Workplan',
        //     hidden:true,
        //     items: [
        //         {
        //             xtype: 'textfield',
        //             name: 'workplan_name',
        //             readOnly: true,
        //             columnWidth: 0.9,
        //         },
        //         {
        //             xtype: 'button',
        //             iconCls: 'x-fa fa-search',
        //             columnWidth: 0.1,
        //             tooltip: 'Search',
        //             name: 'link_annual_workplan',
        //             // childXtype: 'monitoringworkplangrid',
        //             winTitle: 'Workplan Selection',
        //             handler:'showAnnualWorkplanSelectionList',
        //             winWidth: '50%',
        //         }
        //     ]
        // },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Entity Being Reported',
            identity:'entity_name',
            name: 'entity_type_id',
            allowBlank: false,
            valueField: 'id',
            displayField: 'name',
            forceSelection: false,
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            extraParams: {
                                table_name: 'par_entity_types'
                            }
                        }
                    },
                    isLoad: true
                }, 
                change: function(combo, newVal,oldval, eopts){
                    var form = combo.up('form')
                        is_registered =form.down('combo[name=is_registered]'),
                        certificate_no =form.down('fieldcontainer[fieldLabel=Registered No]'),
                        brand_name= form.down('textfield[name=brand_name]'),
                        common_name = form.down('textfield[name=common_name]'),
                        product_section_id= form.down('combo[identity=product_section_id]'),
                        prodclass_category_id= form.down('combo[name=prodclass_category_id]'),
                        batch_number= form.down('textfield[name=batch_number]'),
                        expiry_date= form.down('datefield[name=expiry_date]'),
                        is_facility_registered =form.down('combo[name=is_facility_registered]'),
                        permit_no = form.down('fieldcontainer[fieldLabel=Permit No]'),
                        premise_name = form.down('textfield[name=premise_name]'),
                        premise_type = form.down('combo[name=premise_type]');

                    if(newVal == 1){//product
                        is_registered.setVisible(true);
                        certificate_no.setVisible(true);
                        brand_name.setVisible(true);
                        common_name.setVisible(true);
                        product_section_id.setVisible(true);
                        prodclass_category_id.setVisible(true);
                        batch_number.setVisible(true);
                        expiry_date.setVisible(true);
                        is_facility_registered.setVisible(true);
                        permit_no.setVisible(true);
                        premise_name.setVisible(true);
                        premise_type.setVisible(true);
                    } else if (newVal == 2){//facility
                        is_registered.setVisible(false);
                        certificate_no.setVisible(false);
                        brand_name.setVisible(false);
                        common_name.setVisible(false);
                        product_section_id.setVisible(false);
                        prodclass_category_id.setVisible(false);
                        batch_number.setVisible(false);
                        expiry_date.setVisible(false);
                        is_facility_registered.setVisible(true);
                        permit_no.setVisible(true);
                        premise_name.setVisible(true);
                        premise_type.setVisible(true);
                    }else{//person
                        is_registered.setVisible(false);
                        certificate_no.setVisible(false);
                        brand_name.setVisible(false);
                        common_name.setVisible(false);
                        product_section_id.setVisible(false);
                        prodclass_category_id.setVisible(false);
                        batch_number.setVisible(false);
                        expiry_date.setVisible(false);
                        is_facility_registered.setVisible(false);
                        permit_no.setVisible(false);
                        premise_name.setVisible(false);
                        premise_type.setVisible(false);
                    }
                }
            },
            
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Is Product Registered',
            name: 'is_registered',
            store: 'confirmationstr',
            hidden:true,
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
				var brand_name_txt=combo.up('form').down('textfield[name=brand_name]');
                var common_name_txt=combo.up('form').down('textfield[name=common_name]');
				var	certificate_no=combo.up('form').down('textfield[name=certificate_no]');
				var	link_registered_product_btn=combo.up('form').down('button[name=link_registered_product]');
                var	section_id=combo.up('form').down('combo[name=section_id]');
                var	prodclass_category_id=combo.up('form').down('combo[name=prodclass_category_id]');

                    if(value==1){
						link_registered_product_btn.enable();
					}else{
                        brand_name_txt.setValue("");
                        common_name_txt.setValue("");
                        certificate_no.setValue("");
                        section_id.setValue("");
                        prodclass_category_id.setValue("");
						link_registered_product_btn.disable();		
					}
			 }
            }
        },
		{
            xtype: 'fieldcontainer',
			readOnly:true,
            layout: 'column',
            hidden:true,
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
				    disabled:true,
				    handler: 'showRegistererdProductSelectionList',
					
                }
            ]
        },
		{   
			xtype:'textfield',
			name:'brand_name',
			fieldLabel:'Brand Name(Product Name)',
            hidden:true, 
		},
        {   
			xtype:'textfield',
			name:'common_name',
			fieldLabel:'Common Name',
            hidden:true,
		},
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Product Type',
            name: 'section_id',
            identity:'product_section_id',
            forceSelection: true,
            hidden:true,
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
            identity:'prodclass_category_id',
            forceSelection: true,
            hidden:true,
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
            hidden:true,
		},
        {
            xtype: 'datefield',
            fieldLabel: 'Expiry Date',
            name: 'expiry_date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            hidden:true,
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
        
        //PREMISE
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Is Facility Registered',
            name: 'is_facility_registered',
            store: 'confirmationstr',
            hidden:true,
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
				var premise_name_txt=combo.up('form').down('textfield[name=premise_name]');
                var premise_type=combo.up('form').down('textfield[name=premise_type]');
				var	permit_no=combo.up('form').down('textfield[name=permit_no]');
				var	link_registered_premise_btn=combo.up('form').down('button[name=link_registered_premise]');

                    if(value==1){
						link_registered_premise_btn.enable();
					}else{
                        premise_name_txt.setValue("");
                        premise_type.setValue("");
                        permit_no.setValue("");
						link_registered_premise_btn.disable();

					}
			 }
            }
        },
        {
            xtype: 'fieldcontainer',
			readOnly:true,
            layout: 'column',
            hidden:true,
            defaults: {
                labelAlign: 'top'
            },
            fieldLabel: 'Permit No',
            items: [
                {
                    xtype: 'textfield',
                    name: 'permit_no',
                    readOnly: true,
                    columnWidth: 0.9
                },
                {
					bind:{disabled:'readOnly'},
                    xtype: 'button',
                    iconCls: 'x-fa fa-link',
                    columnWidth: 0.1,
                    tooltip: 'Link Registred Facility',
                    name: 'link_registered_premise',
				    disabled:true,
				    handler: 'showRegistererdFacilitySelectionList',
					
                }
            ]
        },
		{   
			xtype:'textfield',
			name:'premise_name',
			fieldLabel:'Premise Name',
            hidden:true, 
		},
        {
            hidden:true,
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Facility Type',
            name: 'premise_type',
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
                                table_name: 'par_premises_types'
                            }
                        }
                    },
                    isLoad: true
                },
            }
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Country',
            name: 'country_id',
            identity:'suspect_country_id',
            forceSelection: true,
            allowBlank: false,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 1000,
                        url: 'configurations/getConfigParamFromTable',
                        proxy: {
                            extraParams:{
                                table_name: 'par_countries'
                            }
                        }
                    },
                    isLoad: true
                },
                change: function(combo,newValue,old,eopts) {
                    var form=this.up('form'),
                        regionStr=form.down('combo[name=region_id]').getStore();
                    regionStr.removeAll();
                    var filters = JSON.stringify({'country_id':newValue});
                    regionStr.load({params:{filters:filters}});
                },
            }
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Region/City/Town',
            name: 'region_id',
            identity:'suspect_region_id',
            forceSelection: true,
            allowBlank: false,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 1000,
                        proxy: {
                            url: 'configurations/getConfigParamFromTable',
                            extraParams:{
                                table_name: 'par_regions'
                            }
                        }
                    },
                    isLoad: false
                },
                change: function(combo,newValue,old,eopts) {
                    var form=this.up('form'),
                        districtStr=form.down('combo[name=district_id]').getStore();
                    districtStr.removeAll();
                    var filters = JSON.stringify({'region_id':newValue});
                    districtStr.load({params:{filters:filters}});
                }
            }
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'District',
            name: 'district_id',
            identity:'suspect_district_id',
            forceSelection: true,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 1000,
                        proxy: {
                            url: 'configurations/getConfigParamFromTable',
                            extraParams:{
                                table_name: 'par_districts'
                            }
                        }
                    },
                    isLoad: false
                },
            }
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Physical Address',
            name: 'suspect_physical_address',
            allowBlank: false,
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Postal Address',
            name: 'suspect_postal_address',
            allowBlank: true,
        },
        {
            xtype: 'numberfield',
            fieldLabel: 'Telephone',
            name: 'suspect_telephone',
            allowBlank: true,
        },
        {
            xtype:'htmleditor',
            name:'other_details',
            fieldLabel:'Other Details',
        }, 
    ]
});