Ext.define('Admin.view.Enforcement.views.forms.JointOperation.Summaryfrm', {
    extend: 'Ext.form.Panel',
    xtype: 'summaryfrm',
    itemId: 'summaryfrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    height: Ext.Element.getViewportHeight() - 118,
    autoScroll: true,
    bodyPadding: 5,
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
			xtype:'htmleditor',
			name:'summary',
			fieldLabel:'Summary of Findings',
		},
        {   
            xtype:'htmleditor',
			name:'conclusion',
			fieldLabel:'Conclusion',
		},
         {   
            xtype:'textareafield',
			name:'recommendations',
			fieldLabel:'Recommended Action',
            columnWidth:1
		},
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Is Facility Licensed',
            name: 'is_facility_registered',
            store: 'confirmationstr',
           //hidden:true,
            allowBlank:true,
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
				var	permit_no=combo.up('form').down('textfield[name=permit_no]');
                var	physical_address=combo.up('form').down('textfield[name=physical_address]');
                var	telephone=combo.up('form').down('numberfield[name=telephone]');
                var	new_premise_name=combo.up('form').down('textfield[name=new_premise_name]');
                var	new_physical_address=combo.up('form').down('textfield[name=new_physical_address]');
                var	new_telephone=combo.up('form').down('numberfield[name=new_telephone]');
                var permit_container=combo.up('form').down('fieldcontainer[name=permit_container]');
				var	link_registered_premise_btn=combo.up('form').down('button[name=link_registered_premise]');

                    if(value==1){
						link_registered_premise_btn.enable();
                        permit_no.setVisible(true);
                        permit_container.setVisible(true);
                        physical_address.setVisible(true);
                        telephone.setVisible(true);
                        permit_no.allowBlank = true;
                        physical_address.allowBlank = true;
                        telephone.allowBlank = true;
                        premise_name_txt.setVisible(true);
                        premise_name_txt.allowBlank = false;
                        new_premise_name.setVisible(false);
                        new_premise_name.allowBlank = true;
                        new_physical_address.setVisible(false);
                        new_physical_address.allowBlank = true;
                        new_telephone.setVisible(false);
                        new_telephone.allowBlank = true;
					}else{
                        new_premise_name.setVisible(true);
                        new_premise_name.allowBlank = false;
                        new_physical_address.setVisible(true);
                        new_physical_address.allowBlank = false;
                        new_telephone.setVisible(true);
                        new_telephone.allowBlank = false;
                        permit_no.setVisible(false);
                        permit_container.setVisible(false);
                        physical_address.setVisible(false);
                        telephone.setVisible(false);
                        permit_no.allowBlank = true;
                        physical_address.allowBlank = true;
                        telephone.allowBlank = true;
						link_registered_premise_btn.disable();
                        premise_name_txt.setVisible(false);
                        premise_name_txt.allowBlank = true;

					}
			 }
            }
        },
        {
            xtype: 'fieldcontainer',
			readOnly:true,
            layout: 'column',
            name:'permit_container',
            //hidden:true,
           allowBlank:true,
            defaults: {
                labelAlign: 'top'
            },
            fieldLabel: 'License No',
            items: [
                {
                    xtype: 'textfield',
                    name: 'permit_no',
                    readOnly: true,
                    allowBlank:true,
                    columnWidth: 0.9
                },
                {
					bind:{disabled:'readOnly'},
                    xtype: 'button',
                    iconCls: 'x-fa fa-link',
                    columnWidth: 0.1,
                    tooltip: 'Link Licensed Facility',
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
            allowBlank:true,
		},
        {   
			xtype:'textfield',
			name:'physical_address',
			fieldLabel:'Physical Address',
            hidden:true, 
            allowBlank:true,
		},
        
        {   
			xtype:'numberfield',
			name:'telephone',
			fieldLabel:'Telephone',
            hidden:true, 
            allowBlank:true,
		},
        {   
			xtype:'textfield',
			name:'new_premise_name',
			fieldLabel:'Premise Name',
            allowBlank:true,
            hidden:true, 
		},
        {   
			xtype:'textfield',
			name:'new_physical_address',
			fieldLabel:'Physical Address',
            hidden:true, 
            allowBlank:true,
		},
        {   
			xtype:'textfield',
			name:'new_postal_address',
			fieldLabel:'Postal Address',
            hidden:true, 
            allowBlank:true,
		},
        {   
			xtype:'numberfield',
			name:'new_telephone',
			fieldLabel:'Telephone',
            hidden:true, 
            allowBlank:true,
		},
        {
            xtype: 'combo', anyMatch: true,
            name: 'action_id',
            allowBlank: true,
            queryMode: 'local',
            fieldLabel: 'Action Options',
            columnWidth: 1,
            valueField: 'id',
            displayField: 'name',
            listeners: {
                afterrender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            extraParams:{
                                table_name: 'par_joint_decisions'

                            }
                        }
                    },
                    isLoad: true
                },
                change:function(combo,value)
                {
                   var investigator=combo.up('form').down('combo[name=investigator]');
                   var co_investigator=combo.up('form').down('tagfield[name=co_investigator]');
                 
                       if(value==1){
                          investigator.setVisible(true);  
                         investigator.allowBlank = false;
                           co_investigator.setVisible(true);  
                           co_investigator.allowBlank = false;
                       }else{
                        investigator.setVisible(false);  
                        investigator.allowBlank = true;
                        co_investigator.setVisible(false);  
                        co_investigator.allowBlank = true;
                       }
               }
            }
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Investigator',
            name: 'investigator',
            valueField: 'id',
            columnWidth: 1,
            displayField: 'name',
            hidden:true,
            queryMode: 'local',
            growMax: 10,
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            url:'enforcement/getusers'
                        }
                    },
                    isLoad: true
                }, 
            }
            
        }, 
        {
            xtype: 'tagfield',
            fieldLabel: ' Co_Investigators',
            name: 'co_investigator',
            valueField: 'id',
            columnWidth: 1,
            displayField: 'name',
            forceSelection: true,
            filterPickList: true,
            encodeSubmitValue: true,
            hidden:true,
            queryMode: 'local',
            growMax: 10,
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            url:'enforcement/getusers'
                        }
                    },
                    isLoad: true
                }, 
            }
            
        }, 
        
        
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveOperationSummary',
            table_name: 'par_operation_summary',
            storeID: 'summaryGridStr',
            action: 'save_summary_details'
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