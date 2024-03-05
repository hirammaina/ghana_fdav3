/**
 * Created by Softclans
 */
 Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.LicenseInformationFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'licenseInformationFrm',
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
    items: [
        {
            xtype: 'hiddenfield',
            name: 'isReadOnly'
        },
        {
            xtype: 'hiddenfield',
            name: 'enforcement_id'
        },
        {
            xtype: 'hiddenfield',
            name: 'reg_premise_id'
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
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Is Entity Registered',
            name: 'is_facility_registered',
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
                var link_registered_premise_btn=combo.up('form').down('button[name=link_registered_premise]'),
                    permit_no_txt=combo.up('form').down('textfield[name=permit_no]'),
                    premise_name_txt=combo.up('form').down('textfield[name=premise_name]'),
                    premise_type_txt=combo.up('form').down('combo[name=premise_type]');
                    country_id=combo.up('form').down('combo[name=country_id]');
                    region_id=combo.up('form').down('combo[name=region_id]');
                    district_id=combo.up('form').down('combo[name=district_id]');
                    physical_address=combo.up('form').down('textfield[name=physical_address]');
                    postal_address=combo.up('form').down('textfield[name=postal_address]');
                    start_date=combo.up('form').down('textfield[name=start_date]');
                    end_date=combo.up('form').down('textfield[name=end_date]');


                    if(value==1){
						link_registered_premise_btn.enable();
					}else{
                        permit_no_txt.setValue('');
                        premise_name_txt.setValue("");
                        premise_type_txt.setValue("");
                        country_id.setValue("");
                        region_id.setValue("");
                        district_id.setValue("");
                        physical_address.setValue("");
                        postal_address.setValue("");
                        start_date.setValue("");
                        end_date.setValue("");
						link_registered_premise_btn.disable();
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
				    handler: 'showRegistererdFacilitySelectionList',
					
                }
            ]
        },
		{   
			xtype:'textfield',
			name:'premise_name',
			fieldLabel:'Entity Trade Name',
		},
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Type of Entity Assessed/Line of Business',
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
            identity:'country_id',
            allowBlank:false,
            forceSelection: true,
            // hidden:true,
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
            fieldLabel: 'District',
            name: 'region_id',
            identity:'region_id',
            forceSelection: true,
            allowBlank:false,
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
            fieldLabel: 'Region/City/Town',
            name: 'district_id',
            identity:'district_id',
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
            name: 'physical_address',
            allowBlank:false,
        },
        {
            xtype: 'textfield',
            fieldLabel: 'Postal Address',
            allowBlank:true,
            name: 'postal_address',
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Assessment Start Date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            name: 'start_date',
            allowBlank:false,
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        },
        {
            xtype: 'datefield',
            fieldLabel: 'Assessment End Date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            name: 'end_date',
            allowBlank:false,
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00'
        }
    ],
});