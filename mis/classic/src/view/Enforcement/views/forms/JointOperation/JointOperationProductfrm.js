Ext.define('Admin.view.Enforcement.views.forms.JointOperation.JointOperationProductfrm', {
    extend: 'Ext.form.Panel',
    xtype: 'jointOperationProductfrm',
    itemId: 'jointOperationProductfrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    height: Ext.Element.getViewportHeight() - 118,
    autoScroll: true,
    bodyPadding: 1,
    defaults: {
        columnWidth: 0.5,
        margin: 2,
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
            name: 'active_application_code'
        },
         {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Facility',
            name: 'joint_offence_id',
            allowBlank: true,
            valueField: 'facility',
            displayField: 'facility',
            forceSelection: true,
            columnWidth: 1,
            fieldStyle: {
                'color': 'green',
                'font-weight': 'bold'
            },
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            url:'enforcement/getJointOperationDetectedFacilities'
                           // url:'enforcement/getManufacturingSites'
                        }
                    },
                    isLoad: true
                }, 
            }
            
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
				var brand_name_txt=combo.up('form').down('textfield[name=brand_name]');
                var new_product=combo.up('form').down('textfield[name=new_product]');
                var registered_product_container=combo.up('form').down('fieldcontainer[name=registered_product_container]')
				var	certificate_no=combo.up('form').down('textfield[name=certificate_no]');
				var	link_registered_product_btn=combo.up('form').down('button[name=link_registered_product]');

                    if(value==1){
						link_registered_product_btn.enable();
                        registered_product_container.setVisible(true);
                        certificate_no.setVisible(true);
                        certificate_no.allowBlank=true;
                        brand_name_txt.setVisible(true);
                        brand_name_txt.allowBlank=false;
                        new_product.setVisible(false);
                        new_product.allowBlank = true;
					}else{
                        registered_product_container.setVisible(false);
                        certificate_no.setVisible(false);
                        certificate_no.allowBlank=true;
                        brand_name_txt.setVisible(false);
                        brand_name_txt.allowBlank=true;
                        new_product.setVisible(true);
                        new_product.allowBlank=false;
						link_registered_product_btn.disable();		
					}
			 }
            }
        }, 
        {
            xtype: 'fieldcontainer',
			readOnly:true,
            layout: 'column',
          // hidden:true,
            name:'registered_product_container',
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
			fieldLabel:'Product Brand Name',
            allowBlank:true,
           hidden:true
		},
        {   
			xtype:'textfield',
			name:'new_product',
			fieldLabel:'Product Brand Name',
            allowBlank:true,
            hidden:true
		},
        {   
			xtype:'textareafield',
			name:'product_description',
			fieldLabel:'Product Description',
           // hidden:true
		},
        // {   
		// 	xtype:'combo', anyMatch: true,
		// 	name:'dosage_form_id',
		// 	fieldLabel:'Dosage form',
        //     queryMode: 'local',
        //     valueField: 'id',
        //     displayField: 'name',
        //     //hidden:true,
        //     listeners: {
        //         afterrender: {
        //            fn: 'setCompStore',
        //             config: {
        //                 pageSize: 10000,
        //                 proxy: {
        //                     url: 'configurations/getConfigParamFromTable',
        //                     extraParams: {
        //                         table_name: 'par_dosage_forms'
        //                     }
        //                 }
        //             },
        //             isLoad: true
        //         },
        //         change: function(combo, newVal, oldVal, eopts){
                  
        //         }
        //     }
		// },
        {   
			xtype:'textfield',
			name:'pack_size',
			fieldLabel:'Pack size',
           
		},
        {   
			xtype:'datefield',
            fieldLabel: 'Expiry Date',
            name: 'expiry_date',
            submitFormat: 'Y-m-d',
            format: 'd/m/Y',
            altFormats: 'd,m,Y|d.m.Y|Y-m-d|d/m/Y/d-m-Y|d,m,Y 00:00:00|Y-m-d 00:00:00|d.m.Y 00:00:00|d/m/Y 00:00:00',
           // hidden:true
        },  
        {   
			xtype:'textfield',
			name:'batch_number',
			fieldLabel:'Batch Number',
           // hidden:true
		},
        {   
			xtype:'textfield',
			name:'manufacturer',
			fieldLabel:'Manufacturer',
           // hidden:true
		},
        {   
			xtype:'textfield',
			name:'dosage_form',
			fieldLabel:'Dosage form',
           // hidden:true
		},
       
        {   
			xtype:'numberfield',
			name:'quantity',
			fieldLabel:'Quantity',
		},
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Offence Type',
            identity:'Offence_type',
            name: 'offence_type_id',
            allowBlank: true,
            valueField: 'id',
            displayField: 'name',
            forceSelection: true,
            fieldStyle: {
                'color': 'green',
                'font-weight': 'bold'
            },
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            extraParams: {
                                table_name: 'par_offence_types'
                            }
                        }
                    },
                    isLoad: true
                }, 
            }
            
        },
        // {
        //     xtype: 'combo', anyMatch: true,
        //     fieldLabel: 'Type of Offenders',
        //     name: 'type_of_offender_id',
        //     allowBlank: true,
        //     valueField: 'id',
        //     displayField: 'name',
        //     forceSelection: true,
        //     fieldStyle: {
        //         'color': 'green',
        //         'font-weight': 'bold'
        //     },
        //     queryMode: 'local',
        //     listeners: {
        //         beforerender: {
        //             fn: 'setCompStore',
        //             config: {
        //                 proxy: {
        //                     extraParams: {
        //                         table_name: 'par_types_of_offender'
        //                     }
        //                 }
        //             },
        //             isLoad: true
        //         }, 
        //     }
            
        // },
      
        
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveJointProducts',
            table_name: 'par_joint_seized_products',
            storeID: 'jointOperationProductGridStr',
            action: 'save_joint_product_details'
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